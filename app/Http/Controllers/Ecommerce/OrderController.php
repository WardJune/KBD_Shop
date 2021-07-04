<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    private function getOrder()
    {
        $orders = Order::where('customer_id', auth('customer')->user()->id);
        return $orders;
    }
    public function dashboard()
    {
        $orders = $this->getOrder()->latest()->get();

        return view('ecommerce.orders.dashboard', compact('orders'));
    }

    public function awaitingPayment()
    {
        $orders = $this->getOrder()->where('status', 0)->latest()->get();

        return view('ecommerce.orders.awaiting-payment', compact('orders'));
    }

    public function awaitingConfirm()
    {
        $orders = $this->getOrder()->where('status', 1)->latest()->get();

        return view('ecommerce.orders.awaiting-confirm', compact('orders'));
    }

    public function process()
    {
        $orders = $this->getOrder()->where('status', 2)->latest()->get();

        return view('ecommerce.orders.process', compact('orders'));
    }

    public function sent()
    {
        $orders = $this->getOrder()->where('status', 3)->latest()->get();

        return view('ecommerce.orders.sent', compact('orders'));
    }

    public function done()
    {
        $orders = $this->getOrder()->where('status', 4)->latest()->get();

        return view('ecommerce.orders.done', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('ecommerce.orders.show', compact('order'));
    }

    public function paymentForm(Order $order)
    {
        return view('ecommerce.orders.payment', compact('order'));
    }

    public function savePayment()
    {
        $this->validate(request(), [
            'invoice' => 'required|exists:orders,invoice',
            'name' => 'required|string',
            'transfer_to' => 'required|string',
            'transfer_date' => 'required',
            'amount' => 'required|integer',
            'proof' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        DB::beginTransaction();

        try {
            // get order
            $order = $this->getOrder()->where('invoice', request()->invoice)->first();

            //  make sure the status is 0 && there is image file
            if ($order->status == 0 && request()->hasFile('proof')) {
                $file = request()->proof;
                $filename = request()->invoice . '-proof' . $file->extension();
                $file->storeAs('proofs', $filename);

                Payment::create([
                    'order_id' => $order->id,
                    'name' => request()->name,
                    'transfer_to' => request()->transfer_to,
                    'transfer_date' => request()->transfer_date,
                    'amount' => request()->amount,
                    'proof' => 'proofs/' . $filename,
                    'status' => false
                ]);

                // update order status
                $order->update(['status' => 1]);

                DB::commit();

                return redirect(route('order.show', $order->invoice))->with(['status' => 'Success, Waiting for Confirmation']);
            }

            return redirect()->back()->with(['error' => 'Make sure you enter the data correctly']);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
