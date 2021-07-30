<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\ProductStock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel Order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::where(function ($q) {
            $q->where('status', 0)
                ->where('created_at', '<', now()->subMinutes(10));
        })->get();

        foreach ($orders as $order) {
            Log::info('order');

            foreach ($order->details()->get() as $detail) {
                $update = $detail->product->stock->qty + $detail->qty;
                ProductStock::whereProductId($detail->product->id)->update([
                    'qty' => $update
                ]);
            }
            $order->details()->forceDelete();
            $order->forceDelete();
        }
        Log::info(now()->format('d-m-Y H:i:s') . ' ' . $orders->count() . " Orders has been canceled");
    }
}
