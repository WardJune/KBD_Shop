<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman index Order
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $new = Order::whereStatus(0)->count();
        $confirm = Order::whereStatus(1)->count();
        $process = Order::whereStatus(2)->count();
        $sent = Order::whereStatus(3)->count();
        $done = Order::whereStatus(4)->count();

        $orders = Order::with(['district.city.province'])->withCount(['return'])->latest();
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
        return view('admin.orders.index', compact('orders', 'new', 'confirm', 'process', 'sent', 'done'));
    }

    /**
     * Menampilkan halaman Deleted Orders
     * 
     * @return \Illuminate\View\View
     */
    public function showDeleted()
    {
        $orders = Order::onlyTrashed()->with(['district.city.province'])->withCount(['return'])->latest();

        if (request()->keyword != '') {
            $orders = $orders->where(function ($keyword) {
                $keyword->where('customer_name', 'like', '%' . request()->keyword . '%')
                    ->orWhere('invoice', 'like', '%' . request()->keyword . '%')
                    ->orWhere('customer_address', 'like', '%' . request()->keyword . '%');
            });
        }

        if (request()->status != '') {
            $orders = $orders->where('status', request()->status);
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.show-deleted', compact('orders'));
    }

    /**
     * Menghapus sementara Spesifik Order 
     * 
     * @param Order $order
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        /** order dengan status 1 forceDelete */
        if ($order->status == 1) {
            foreach ($order->details()->get() as $detail) {
                $qty = $detail->product->stock->qty + $detail->qty;
                ProductStock::whereProductId($detail->product_id)
                    ->update(['qty' => $qty]);
            }

            if ($order->payment_count > 0) {
                Storage::delete($order->payment->proof);
                $order->payment()->forceDelete();
            }

            $order->details()->forceDelete();
            $order->forceDelete();

            return redirect(route('orders.index'));
        }

        $order->delete();

        return redirect(route('orders.index'));
    }

    /**
     * Menghapus secara permanent spesifik Order beserta relasi yang terkait
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDestroy($id)
    {
        $order = Order::withTrashed()->whereId($id)->first();

        $order->details()->forceDelete();

        if ($order->payment_count > 0) {
            Storage::delete($order->payment->proof);
            $order->payment()->forceDelete();

            if ($order->return_count > 0) {
                Storage::delete($order->return->photo);
                $order->return->ForceDelete();
            }
        }
        $order->forceDelete();

        return redirect(route('orders.deleted'));
    }

    /**
     * Merestore Order yang telah dihapus sementara
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $order = Order::withTrashed()->whereId($id)->restore();
        return redirect(route('orders.index'));
    }

    /**
     * Menampilkan Halaman Spesifik Order
     * 
     * @param Order $order
     * 
     * @return \Illuminate\View\View
     */
    public function show($invoice)
    {
        $order = Order::withTrashed()->whereInvoice($invoice)->first();
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Method ini melakukan Penerimaan Pembayaran Order 
     * 
     * @param Order $order
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptPayment(Order $order)
    {
        $order->payment->update(['status' => 1]);
        $order->update(['status' => '2']);
        return redirect()->back();
    }

    /**
     * Method ini mengatasi Status Pengiriman Order
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shippingOrder()
    {
        $order = Order::where('id', request()->order_id)->firstOrFail();
        $order->update([
            'status' => 3,
            'tracking_number' => request()->tracking_number,
        ]);
        Mail::to($order->customer->email)->send(new OrderMail($order));
        return redirect()->back();
    }

    /**
     * Menampilkan Halaman Order Return/Refund
     * 
     * @param Order $order
     * 
     * @return \Illuminate\View\View
     */
    public function returnShow(Order $order)
    {
        return view('admin.orders.return', compact('order'));
    }

    /**
     * Method ini melakukan Confirmasi Terhadap Request Return yang dilakukan oleh Customer
     * 
     * @param mixed $status
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmReturn($status)
    {
        $order = Order::where('id', request()->order_id)->first();
        $order->return->update(['status' => $status]);
        $order->update(['status' => 4]);

        return redirect()->back();
    }

    public function confirmOrder(Order $order)
    {
        $order->update(['status' => 4]);
        return back();
    }

    /**
     * Laporan Order
     * 
     * @return \Illuminate\View\View
     */
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
        $orders = Order::with(['district.city.province'])->withTrashed()->whereBetween('created_at', [$start, $end])->get();

        return view('admin.orders.report-order', compact('orders', 'title'));
    }

    /**
     * Method ini menangani pembuatan PDF berdasarkan Laporan yang di Ekspor
     * 
     * @param mixed $daterange
     * @param mixed $title
     * 
     * @return void
     */
    public function orderReportPdf($daterange, $title)
    {
        $date = explode('+', $daterange);

        $start = Carbon::parse($date[0])->format('Y-m-d' .  ' 00:00:01');
        $end = Carbon::parse($date[1])->format('Y-m-d' .  ' 23:59:59');

        if ($title == 'return-order-report') {
            $orders = Order::withTrashed()->has('return')->whereBetween('created_at', [$start, $end])->get();
            $title = 'Laporan Return Order';
        } else {
            $orders = Order::withTrashed()->whereBetween('created_at', [$start, $end])->get();
            $title = 'Laporan Order';
        }

        $pdf = \PDF::loadView('admin.orders.report-pdf', compact('orders', 'date', 'title'));

        return $pdf->stream();
    }


    /**
     * Laporan Return Order
     * 
     * @return \Illuminate\View\View
     */
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
        $orders = Order::with(['district.city.province'])->withTrashed()->has('return')->whereBetween('created_at', [$start, $end])->get();

        return view('admin.orders.report-order', compact('orders', 'title'));
    }


    public function test()
    {
        // ->format('Y-m-d H:i:s')
        $order = OrderDetail::find(6)->created_at;
        $now = Carbon::now()->format('Y-m-d H:i:s');
        // $da = $order->addHour(24);
        $de = '2021-07-16 13:46:37';

        if ($de == $order->format('Y-m-d H:i:s')) {
            return 'true';
        } else {
            $arr = [1, 2, 3, 4, 5];
            foreach ($arr as $ar) {
                if ($ar == 4) {
                    return 'stop';
                }
            }
        }
        return $order;
        // return $od->created_at->format('Y-m-d H:i:s');
    }
}
