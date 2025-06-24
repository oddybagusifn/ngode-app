<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use App\Models\Order;

class MidtransController extends Controller
{

    public function callback(Request $request)
    {
        Log::info('BI SNAP Notifikasi Masuk');

        Log::info($request->all());

        // dd($request->all());

        // Atur config Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Ambil notifikasi dari Midtrans
        $notification = new Notification();

        $transaction = $notification->transaction_status;
        $type = 'midtrans';
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        $order = Order::where('order_id', $orderId)->first();

        $order->payment_method = $type;

        if ($transaction == 'settlement') {
            $order->status = 'paid';
        } elseif ($transaction == 'pending') {
        } elseif ($transaction == 'expired') {
        }

        $order->save();

        return response()->json(['message' => 'Notifikasi diterima']);
    }
}
