<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\ReturnRequest;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Get semua Order &/ Berdasarkan parameter status
     * 
     * @param null $status
     * 
     * @return mixed $order
     */
    private function getOrder($status = null)
    {
        $orders = Order::withCount(['return'])->where('customer_id', auth('customer')->user()->id);
        if ($status != null) {
            $orders = $orders->where('status', $status)->latest()->get();
        }
        return $orders;
    }

    /**
     * Method curl untuk Telegram
     * 
     * @param mixed $url
     * @param mixed $params
     * 
     * @var mixed $body
     * 
     * @return mixed
     */
    private function getTelegram($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $params);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $content = curl_exec($ch);
        curl_close($ch);

        $body = json_decode($content, true);
        return $body;
    }

    /**
     * Method untuk mengirimkan pesan(retun product) kepada Admin melalui Telegram 
     * 
     * @param mixed $invoice
     * @param mixed $reason
     * 
     * @var mixed|\Illuminate\Config\Repository $key
     * @var mixed $chat     Get chat response dari method getTelegram
     * 
     * @return void
     */
    private function sendMessage($invoice, $reason)
    {
        $key = config('api.telegram_key');
        $chat = $this->getTelegram('https://api.telegram.org/' . $key . '/getUpdates', '');
        if ($chat['ok']) {
            /** Get pernerima pesan telegram (Admin) */
            $chat_id = $chat['result'][0]['message']['chat']['id'];

            $text = "Hey Admin, Ada yang baru nich . Pesanan dengan InvoiceID '$invoice'. Melakukan permintaan return / refund dengan alasan '$reason' , Buruan cek!";

            return $this->getTelegram('https://api.telegram.org/' . $key . '/sendMessage', '?chat_id=' . $chat_id . '&text=' . $text);
        }
    }

    /**
     * Menampilkan Halaman Dashboard order
     * 
     * @var mixed $orders
     * @return \Illuminate\View\View
     */
    public function dashboard($status = null)
    {
        if ($status == null) {
            $orders = $this->getOrder()->latest()->get();
            $border['null'] = 'border-bottom border-warning active';
        } else {
            $orders = $this->getOrder($status);
            $border[$status] = 'border-bottom border-warning active';
        }
        return view('ecommerce.orders.dashboard', compact('orders', 'border'));
    }

    /**
     * Menampilkan Halaman Spesifik Order
     * 
     * @param Order $order
     * 
     * @return \Illuminate\View\View|abort
     */
    public function show(Order $order)
    {
        if (Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return view('ecommerce.orders.show', compact('order'));
        }
        return abort(403, 'You are not supposed to do that :(');
    }

    /**
     * Menampilkan Halaman Form Payment
     * 
     * @param Order $order
     * 
     * @return \Illuminate\View\View|abort
     */
    public function paymentForm(Order $order)
    {
        if (Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return view('ecommerce.orders.payment', compact('order'));
        }
        return abort(403, 'You are not supposed to do that :(');
    }

    /**
     * Method ini melakukan savePayment untuk Order yang menlakukan konformaso pembayaran
     * Membuat Data Payment berdasarkan Order
     * 
     * @param PaymentRequest $request
     * 
     * @var mixed $order
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws Exception|\Illuminate\Validation\ValidationException
     */
    public function savePayment(PaymentRequest $request)
    {
        DB::beginTransaction();

        try {
            $order = $this->getOrder()->where('invoice', $request->invoice)->first();

            //  make sure the status is 0 && there is image file
            if ($order->status == 0 && $request->hasFile('proof')) {
                $file = $request->proof;
                $filename = $request->invoice . '-proof.' . $file->extension();
                $file->storeAs('proofs', $filename);

                Payment::create([
                    'order_id' => $order->id,
                    'name' => $request->name,
                    'transfer_to' => $request->transfer_to,
                    'transfer_date' => $request->transfer_date,
                    'amount' => $request->amount,
                    'proof' => 'proofs/' . $filename,
                    'status' => false
                ]);

                // update order status
                $order->update(['status' => 1]);

                DB::commit();

                toast('Success, Waiting for Confirmation', 'success');
                return redirect(route('order.show', $order->invoice));
            }

            return redirect()->back()->with(['error' => 'Make sure you enter the data correctly']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Menampilkan Halaman/ PDF Invoice 
     * 
     * @param Order $order
     * 
     * @var mixed $pdf
     * @return \Illuminate\Http\RedirectResponse|mixed 
     */
    public function pdf(Order $order)
    {
        if (!Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return redirect(route('order.show', $order->invoice));
        }

        $pdf = \PDF::loadView('ecommerce.orders.pdf', compact('order'));

        return $pdf->stream();
    }

    /**
     * Method ini menjalankan Penerimaan Pesanan oleh Customer
     * 
     * @param Order $order
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptOrder(Order $order)
    {
        if (!Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return redirect(route('order.show', $order->invoice));
        }

        $order->update(['status' => 4]);

        return redirect()->back()->withToastSuccess('Order Received');
    }

    /**
     * Menampilkan Halaman return Order
     * 
     * @param Order $order
     * 
     * @return \Illuminate\View\View|abort
     */
    public function returnForm(Order $order)
    {
        if (Gate::forUser(auth('customer')->user())->allows('order-show', $order)) {
            return view('ecommerce.orders.return-form', compact('order'));
        }
        return abort(403, 'You are not supposed to do that :(');
    }

    /**
     * Method ini menangani Process Retrun Order 
     * Membuat data OrderReturn
     * 
     * @param ReturnRequest $request
     * @param mixed $id
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function returnProcess(ReturnRequest $request, $id)
    {
        $order = Order::whereId($id)->first();
        $return = OrderReturn::where('order_id', $id)->first();

        if ($return) {
            alert()->info('Request Refund On Process')
                ->autoClose(false)
                ->showConfirmButton('Confirm', '#FA3B0F');
            return back();
        };

        if ($request->hasFile('photo')) {

            $file = $request->photo;
            $filename =  $order->invoice . '-return.' . $file->extension();
            $file->storeAs('return', $filename);

            OrderReturn::create([
                'order_id' => $id,
                'photo' => 'return/' . $filename,
                'reason' => $request->reason,
                'refund_transfer' => $request->refund_transfer,
                'status' => 0,
            ]);
        }

        /** mengirim pesan ke Admin Melalui Telegram */
        $this->sendMessage($order->invoice, $request->reason);

        return redirect()->back()->withToastSuccess('Request Refund Sent');
    }
}
