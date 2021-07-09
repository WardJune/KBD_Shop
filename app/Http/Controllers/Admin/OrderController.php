<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use App\Models\Order;
use Carbon\Carbon;
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

    /// Order Report
    public function orderReport()
    {
        // init 30 days range on load
        // start of month
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        // end of month
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ', request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }
        $title = "Order Report";
        $orders = Order::whereBetween('created_at', [$start, $end])->get();

        return view('admin.orders.report-order', compact('orders', 'title'));
    }

    public function orderReportPdf($daterange, $title)
    {
        $date = explode('+', $daterange);

        $start = Carbon::parse($date[0])->format('Y-m-d' .  ' 00:00:01');
        $end = Carbon::parse($date[1])->format('Y-m-d' .  ' 23:59:59');

        if ($title == 'return-order-report') {
            $orders = Order::has('return')->whereBetween('created_at', [$start, $end])->get();
            $title = 'Laporan Return Order';
        } else {
            $orders = Order::whereBetween('created_at', [$start, $end])->get();
            $title = 'Laporan Order';
        }

        $pdf = \PDF::loadView('admin.orders.report-pdf', compact('orders', 'date', 'title'));

        return $pdf->stream();
    }


    public function orderReturnReport()
    {
        // init 30 days range on load
        // start of month
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        // end of month
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ', request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }
        $title = "Return Order Report";
        $orders = Order::has('return')->whereBetween('created_at', [$start, $end])->get();

        return view('admin.orders.report-order', compact('orders', 'title'));
    }
}
