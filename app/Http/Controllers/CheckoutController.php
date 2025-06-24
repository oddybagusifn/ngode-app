<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Cart;
use Midtrans\Config;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        $apiKey = '92fe18cb91d81b605393db36e45a5ed7ef24cf0cd557ba1b9d8552c1e27a4907';

        $provinces = Cache::remember('provinces', now()->addHours(6), function () use ($apiKey) {
            $response = Http::get('https://api.binderbyte.com/wilayah/provinsi', [
                'api_key' => $apiKey
            ]);

            if ($response->successful()) {
                return $response->json()['value'];
            }

            return [];
        });


        $couriers = Cache::remember('popular_couriers', now()->addHours(6), function () use ($apiKey) {
            $response = Http::get('https://api.binderbyte.com/v1/list_courier', [
                'api_key' => $apiKey
            ]);

            if ($response->successful()) {
                $popularCodes = ['jne', 'jnt', 'sicepat', 'anteraja', 'tiki', 'pos'];
                $allCouriers = $response->json();
                return array_filter($allCouriers, fn($c) => in_array($c['code'], $popularCodes));
            }

            return [];
        });

        $totalProduk = 0;
        foreach ($cartItems as $item) {
            $totalProduk += $item->price * $item->quantity;
        }

        $biayaKemasan = 20000;
        $biayaPengiriman = 25000;
        $biayaLayanan = 5000;
        $diskon = 20000;

        $totalHarga = $totalProduk + $biayaKemasan + $biayaPengiriman + $biayaLayanan - $diskon;

        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = false;

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 100000,
            ],
            'customer_details' => [
                'first_name' => 'Nama',
                'email' => 'email@example.com',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);


        return view('user.checkout', compact('cartItems', 'provinces', 'couriers', 'totalHarga', 'snapToken'));
    }


    public function getKabupaten(Request $request)
    {
        $apiKey = '92fe18cb91d81b605393db36e45a5ed7ef24cf0cd557ba1b9d8552c1e27a4907';

        if (!$request->has('provinsi_id')) {
            return response()->json(['error' => 'provinsi_id tidak dikirim'], 400);
        }

        $provinsiId = $request->provinsi_id;

        $response = Http::get('https://api.binderbyte.com/wilayah/kabupaten', [
            'api_key' => $apiKey,
            'id_provinsi' => $provinsiId
        ]);

        if ($response->successful()) {
            return response()->json($response->json()['value']);
        }

        return response()->json(['error' => 'Gagal mengambil kabupaten'], 500);
    }

    public function getKecamatan(Request $request)
    {
        $apiKey = '92fe18cb91d81b605393db36e45a5ed7ef24cf0cd557ba1b9d8552c1e27a4907';
        $kabupatenId = $request->kabupaten_id;

        $response = Http::get('https://api.binderbyte.com/wilayah/kecamatan', [
            'api_key' => $apiKey,
            'id_kabupaten' => $kabupatenId,
        ]);

        if ($response->successful()) {
            return response()->json($response->json()['value']);
        }

        return response()->json(['error' => 'Gagal mengambil kecamatan'], 500);
    }

    public function getKelurahan(Request $request)
    {
        $apiKey = '92fe18cb91d81b605393db36e45a5ed7ef24cf0cd557ba1b9d8552c1e27a4907';
        $kecamatanId = $request->kecamatan_id;

        $response = Http::get('https://api.binderbyte.com/wilayah/kelurahan', [
            'api_key' => $apiKey,
            'id_kecamatan' => $kecamatanId,
        ]);

        if ($response->successful()) {
            return response()->json($response->json()['value']);
        }

        return response()->json(['error' => 'Gagal mengambil kelurahan'], 500);
    }



    public function store(Request $request)
    {
        // dd($request->all());

        $user = Auth::user();

        $customerName = $request->input('person_name');
        $email = $user->email;
        $totalHarga = (int) $request->input('total_price');
        $products = $request->input('products');
        $orderId = 'NGODE-' . rand(100000, 999999);

        $order = Order::create([
            'order_id'        => $orderId,
            'user_id'         => $user->id,
            'person_name'     => $customerName,
            'phone'           => $request->phone,
            'address'         => $request->address,
            'province'        => $request->province,
            'city'            => $request->city,
            'subdistrict'     => $request->subdistrict,
            'village'         => $request->village,
            'postal_code'     => $request->postal_code,
            'delivery_method' => $request->pengiriman,
            'courier' => $request->courier,
            'payment_method'  => 'midtrans',
            'total_price'     => $totalHarga,
            'status'          => 'paid',
        ]);

        foreach ($products as $product) {
            OrderDetail::create([
                'order_id'     => $order->id,
                'product_id'   => $product['product_id'],
                'product_name' => $product['name'],
                'quantity'     => $product['quantity'],
                'price'        => $product['price'],
                'size'        => $product['size'],
            ]);
        }

        // Midtrans item_details
        $itemDetails = [];
        foreach ($products as $product) {
            $itemDetails[] = [
                'id' => $product['product_id'],
                'price' => (int) $product['price'],
                'quantity' => (int) $product['quantity'],
                'name' => $product['name'] . ' (' . $product['size'] . ')',
            ];
        }

        // Tambahan biaya
        $itemDetails[] = ['id' => 'biaya_kemasan', 'price' => 20000, 'quantity' => 1, 'name' => 'Biaya Kemasan'];
        $itemDetails[] = ['id' => 'biaya_pengiriman', 'price' => 25000, 'quantity' => 1, 'name' => 'Biaya Pengiriman'];
        $itemDetails[] = ['id' => 'biaya_layanan', 'price' => 5000, 'quantity' => 1, 'name' => 'Biaya Layanan'];
        $itemDetails[] = ['id' => 'diskon', 'price' => -20000, 'quantity' => 1, 'name' => 'Diskon'];

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalHarga,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $customerName ?? 'Customer',
                'email' => $email,
                'billing_address' => [
                    'address' => $request->address ?? '-',
                    'city' => $request->city ?? '-',
                    'postal_code' => $request->postal_code ?? '-',
                ],
            ],
            'callbacks' => [
                'finish' => route('checkout.success'),
                'unfinish' => route('checkout.cancel'),
                'error' => route('checkout.failed'),
            ],
            'notification_url' => 'https://984e-118-96-101-192.ngrok-free.app/midtrans/callback',
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        Cart::where('user_id', $user->id)->delete();

        return view('user.payment-popup', compact('snapToken'));
    }
}
