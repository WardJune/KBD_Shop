<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OrderDone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $q->whereStatus(3)
                ->where('created_at', '<', now()->subDays(7));
        })->get();

        foreach ($orders as $order) {
            $order->update(['status' => 4]);
        }
        Log::info('Order Confirm : ' . $orders->count());
    }
}
