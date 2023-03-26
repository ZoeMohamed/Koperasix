<?php

namespace App\Http\Controllers\user;

use App\Alamat;
use App\Http\Controllers\Controller;
use App\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class CheckoutController extends Controller
{
    public function index()
    {
        //ambil session user id
        $id_user = Auth::user()->id;

        //ambil produk apa saja yang akan dibeli user dari table keranjang
        $carts = Keranjang::join('users', 'users.id', '=', 'keranjang.user_id')
            ->join('products', 'products.id', '=', 'keranjang.products_id')
            ->select('products.name as nama_produk', 'products.image', 'products.weight', 'users.name', 'keranjang.*', 'products.price')
            ->where('keranjang.user_id', '=', $id_user)
            ->get();

        //lalu hitung jumlah berat total dari semua produk yang akan di beli
        // $berattotal = 0;
        // foreach ($carts as $item) {
        //     $berattotal += ($item->weight * $item->qty);
        // }

        //lalu ambil id kota si pelanngan
        $city_destination = Alamat::where('user_id', $id_user)->first()->cities_id;

        //ambil id kota toko
        $alamat_toko = DB::table('alamat_toko')->first();


        //lalu ambil alamat user untuk ditampilkan di view
        $alamat_user = Alamat::join('cities', 'cities.city_id', '=', 'alamat.cities_id')
            ->join('provinces', 'provinces.province_id', '=', 'cities.province_id')
            ->select('alamat.*', 'cities.title as kota', 'provinces.title as prov')
            ->where('alamat.user_id', $id_user)
            ->first();



        //SAMPLE REQUEST START HERE

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 10000,
            ),
            'customer_details' => array(
                'first_name' => 'budi',
                'last_name' => 'pratama',
                'email' => 'budi.pra@example.com',
                'phone' => '08111222333',
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);



        //buat kode invoice sesua tanggalbulantahun dan jam
        return view('user.checkout', [
            'invoice' => 'ALV' . Date('Ymdhi'),
            'keranjangs' => $carts,
            'alamat' => $alamat_user,
            'snap_token' => $snapToken
        ]);
    }
}