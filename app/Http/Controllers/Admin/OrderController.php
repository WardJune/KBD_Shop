<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withCount(['return'])->latest();

        // search query keyword
        if (request()->keyword != '') {
            $orders = $orders->where(function ($keyword) {
                $keyword->where('customer_name', 'like', '%' . request()->keyword . '%')
                    ->orWhere('invoice', 'like', '%' . request()->keyword . '%')
                    ->orWhere('customer_address', 'like', '%' . request()->keyword . '%');
            });
        }

        // filter by status
        if (request()->status != '') {
            $orders = $orders->where('status', request()->status);
        }

        $orders = $orders->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        $order->details()->delete();
        Storage::delete($order->payment->proof);
        $order->payment()->delete();

        return redirect(route('orders.index'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function acceptPayment(Order $order)
    {
        $order->payment->update(['status' => 1]);
        $order->update(['status' => '2']);
        return redirect()->back();
    }

    public function shippingOrder()
    {
        $order = Order::where('id', request()->order_id)->first();

        $order->update([
            'status' => 3,
            'tracking_number' => request()->tracking_number,
        ]);

        Mail::to($order->customer->email)->send(new OrderMail($order));

        return redirect()->back();
    }

    public function returnShow(Order $order)
    {
        return view('admin.orders.return', compact('order'));
    }

    public function approveReturn($status)
    {
        $order = Order::where('id', request()->order_id)->first();
        $order->return->update(['status' => $status]);
        $order->update(['status' => 4]);

        return redirect()->back();
    }
}
