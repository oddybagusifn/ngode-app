<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\Http;


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

        $provinces = [];
        $response = Http::get('https://api.binderbyte.com/wilayah/provinsi', [
            'api_key' => $apiKey
        ]);

        if ($response->successful()) {
            $provinces = $response->json()['value'];
        }


        $couriers = [];
        $response = Http::get('https://api.binderbyte.com/v1/list_courier', [
            'api_key' => $apiKey
        ]);

        if ($response->successful()) {
            $popularCodes = ['jne', 'jnt', 'sicepat', 'anteraja', 'tiki', 'pos'];
            $allCouriers = $response->json();

            // Filter hanya kurir populer
            $couriers = array_filter($allCouriers, function ($courier) use ($popularCodes) {
                return in_array($courier['code'], $popularCodes);
            });
        }

        $totalProduk = 0;
        foreach ($cartItems as $item) {
            $totalProduk += $item->price * $item->quantity;
        }

        $biayaKemasan = 20000;
        $biayaPengiriman = 25000;
        $biayaLayanan = 5000;
        $diskon = 20000;

        $totalHarga = $totalProduk + $biayaKemasan + $biayaPengiriman + $biayaLayanan - $diskon;


        return view('user.checkout', compact('cartItems', 'provinces', 'couriers', 'totalHarga'));
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

        dd($request->all());

        return back()->with('success', 'Produk berhasil dimasukkan ke keranjang.');
    }
}
