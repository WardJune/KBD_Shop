<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Payment;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /// getOrder by condition / parameter where id = auth id 
    private function getOrder($status = null)
    {
        if ($status != null) {
            $orders = Order::withCount(['return'])->where('customer_id', auth('customer')->user()->id)
                ->where('status', $status)->latest()->get();
        } else {
            $orders = Order::withCount(['return'])->where('customer_id', auth('customer')->user()->id);
        }
        return $orders;
    }

    /// getTelegram curl 
    private function getTelegram($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $params);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $content = curl_exec($ch);
        curl_close($ch);

        return json_decode($content, true);
    }

    /// private sendmessage function for bot telegram
    private function sendMessage($invoice, $reason)
    {
        // get token telegram from env file
        $key = env('TELEGRAM_KEY');
        // request getTelegram
        $chat = $this->getTelegram('https://api.telegram.org/' . $key . '/getUpdates', '');

        if ($chat['ok']) {
            //get id penerima telegram
            $chat_id = $chat['result'][0]['message']['chat']['id'];

            $text = "Hey Admin, Ada yang baru nich . Pesanan dengan InvoiceID '$invoice'. Melakukan permintaan return / refund dengan alasan '$reason' , Buruan cek!";

            return $this->getTelegram('https://api.telegram.org/' . $key . '/sendMessage', '?chat_id=' . $chat_id . '&text=' . $text);
        }
    }


    public function dashboard()
    {
        $orders = $this->getOrder()->latest()->get();
        return view('ecommerce.orders.dashboard', compact('orders'));
    }

    public function awaitingPayment()
    {
        $orders = $this->getOrder('0');
        return view('ecommerce.orders.awaiting-payment', compact('orders'));
    }

    public function awaitingConfirm()
    {
        $orders = $this->getOrder(1);
        return view('ecommerce.orders.awaiting-confirm', compact('orders'));
    }

    public function process()
    {
        $orders = $this->getOrder(2);
        return view('ecommerce.orders.process', compact('orders'));
    }

    public function sent()
    {
        $orders = $this->getOrder(3);
        return view('ecommerce.orders.sent', compact('orders'));
    }

    public function done()
    {
        $orders = $this->getOrder(4);
        return view('ecommerce.orders.done', compact('orders'));
    }

    public function show(Order $order)
    {
        if (Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return view('ecommerce.orders.show', compact('order'));
        }
        return abort(403, 'You are not supposed to do that :(');
    }

    public function paymentForm(Order $order)
    {
        if (Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return view('ecommerce.orders.payment', compact('order'));
        }
        return abort(403, 'You are not supposed to do that :(');
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
                $filename = request()->invoice . '-proof.' . $file->extension();
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

    public function pdf(Order $order)
    {
        if (!Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return redirect(route('order.show', $order->invoice));
        }

        $pdf = \PDF::loadView('ecommerce.orders.pdf', compact('order'));

        return $pdf->stream();
    }

    public function acceptOrder(Order $order)
    {
        if (!Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return redirect(route('order.show', $order->invoice));
        }

        $order->update(['status' => 4]);

        return redirect()->back()->with(['success' => 'Order Confirmed']);
    }

    public function returnForm(Order $order)
    {
        return view('ecommerce.orders.return-form', compact('order'));
    }

    public function returnProcess($id)
    {
        $this->validate(request(), [
            'reason' => 'required|string',
            'refund_transfer' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        $return = OrderReturn::where('order_id', $id)->first();

        if ($return) return redirect()->back(with(['error' => 'Request Refund on Process']));

        if (request()->hasFile('photo')) {

            $file = request()->photo;
            $filename = time() . Str::random(8) . '.' . $file->extension();
            $file->storeAs('return', $filename);

            OrderReturn::create([
                'order_id' => $id,
                'photo' => 'return/' . $filename,
                'reason' => request()->reason,
                'refund_transfer' => request()->refund_transfer,
                'status' => 0,
            ]);
        }

        $order = Order::where('id', $id)->first();

        $this->sendMessage($order->invoice, request()->reason);

        return redirect()->back()->with(['success' => 'Request Refund Sent']);
    }
}
