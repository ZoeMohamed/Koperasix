<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Detailorder;
use App\Keranjang;
use App\Rekening;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function index()
    {


        //menampilkan semua data pesanan
        $user_id = Auth::user()->id;

        $order = Order::join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', 1)
            ->where('order.user_id', $user_id)->get();
        $dicek = Order::join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', '!=', 1)
            ->Where('order.status_order_id', '!=', 5)
            ->Where('order.status_order_id', '!=', 6)
            ->where('order.user_id', $user_id)->get();
        $histori = Order::join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', '!=', 1)
            ->Where('order.status_order_id', '!=', 2)
            ->Where('order.status_order_id', '!=', 3)
            ->Where('order.status_order_id', '!=', 4)
            ->where('order.user_id', $user_id)->get();




        return view('user.order.order', [
            'order' => $order,
            'dicek' => $dicek,
            'histori' => $histori,
        ]);
    }

    public function detail($id)
    {
        //function menampilkan detail order
        $detail_order = Detailorder::join('products', 'products.id', '=', 'detail_order.product_id')
            ->join('order', 'order.id', '=', 'detail_order.order_id')
            ->select('products.name as nama_produk', 'products.image', 'detail_order.*', 'products.price', 'order.*')
            ->where('detail_order.order_id', $id)
            ->get();
        $order = Order::join('users', 'users.id', '=', 'order.user_id')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'users.name as nama_pelanggan', 'status_order.name as status')
            ->where('order.id', $id)
            ->first();

        return view('user.order.detail', [
            'detail' => $detail_order,
            'order'  => $order
        ]);
    }

    public function sukses()
    {
        //menampilkan view terimakasih jika order berhasil dibuat
        return view('user.terimakasih');
    }

    public function kirimbukti($id, Request $request)
    {

        //mengupload bukti pembayaran
        $order = Order::findOrFail($id);
        if ($request->file('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran')->store('buktibayar', 'public');

            $order->bukti_pembayaran = $file;
            $order->status_order_id  = 2;

            $order->save();
        }
        return redirect()->route('user.order');

        // Briva Payment
    }

    public function pembayaran($id)
    {
        //menampilkan view pembayaran
        return view('user.order.pembayaran', [
            'rekening' => Rekening::all(),
            'order' => Order::findOrFail($id)
        ]);
    }

    public function pesananditerima($id)
    {
        //function untuk menerima pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 5;
        $order->save();

        return redirect()->route('user.order');
    }

    public function pesanandibatalkan($id)
    {
        //function untuk membatalkan pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 6;
        $order->save();

        return redirect()->route('user.order');
    }

    public function simpan(Request $request)
    {
        //untuk menyimpan pesanan ke table order
        $cek_invoice = Order::where('invoice', $request->invoice)->count();
        if ($cek_invoice < 1) {
            $userid = Auth::user()->id;
            //jika pelanggan memilih metode cod maka insert data yang ini
            if ($request->metode_pembayaran == 'cod') {
                Order::create([
                    'invoice' => $request->invoice,
                    'user_id' => $userid,
                    'subtotal' => $request->subtotal,
                    'status_order_id' => 1,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'biaya_cod' => 10000,
                    'no_hp' => $request->no_hp,
                    'pesan' => $request->pesan
                ]);
            } else {
                //jika memilih transfer maka data yang ini
                Order::create([
                    'invoice' => $request->invoice,
                    'user_id' => $userid,
                    'subtotal' => $request->subtotal,
                    'status_order_id' => 1,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'no_hp' => $request->no_hp,
                    'pesan' => $request->pesan
                ]);
            }

            $order = Order::where('invoice', $request->invoice)->first();

            $barang = Keranjang::where('user_id', $userid)->get();
            //lalu masukan barang2 yang dibeli ke table detail order
            foreach ($barang as $brg) {
                Detailorder::create([
                    'order_id' => $order->id,
                    'product_id' => $brg->products_id,
                    'qty' => $brg->qty,
                ]);
            }
            //lalu hapus data produk pada keranjang pembeli
            Keranjang::where('user_id', $userid)->delete();

            return redirect()->route('user.order.sukses');
        }

        return redirect()->route('user.keranjang');
    }

    public function tes(Request $request)
    {
        $key =  config('midtrans.key');

        $hashed = hash('sha512', $request->order_id. $request->status_code. $request->gross_amount. $request->key);


        if($hashed == $request->signature_key){
            if($request->transaction_status == 'capture'){
                // $order = Order::find($req)
            }
        }

        // //SAMPLE REQUEST START HERE

        // // Set your Merchant Server Key
        // \Midtrans\Config::$serverKey = config('midtrans.key');
        // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        // \Midtrans\Config::$isProduction = false;
        // // Set sanitization on (default)
        // \Midtrans\Config::$isSanitized = true;
        // // Set 3DS transaction for credit card to true
        // \Midtrans\Config::$is3ds = true;

        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => rand(),
        //         'gross_amount' => 10000,
        //     ),
        //     'customer_details' => array(
        //         'first_name' => 'budi',
        //         'last_name' => 'pratama',
        //         'email' => 'budi.pra@example.com',
        //         'phone' => '08111222333',
        //     ),
        // );

        // $snapToken = \Midtrans\Snap::getSnapToken($params);


        // return view('user.order.order',$snapToken);
        //     try {
        //         DB::beginTransaction();
        //         $serverkey = config('midtrans.key');

        //         $x = Http::withBasicAuth($serverkey, '')->post('https://app.sandbox.midtrans.com/snap/v1/transactions', [
        //             'payment_type' => 'bank_transfer',
        //             'transaction_details' => [
        //                 'order_id' => Str::uuid()->toString(),
        //                 'gross_amount' => 9000
        //             ],
        //             'bank_transfer' => [
        //                 'bank' => "bri"
        //             ]
        //         ]);

        //         DB::commit();

        //         return response()->json(
        //             [$x->json()]
        //         );
        //     } catch (Exception $e) {
        //         DB::rollBack();

        //         return response()->json([
        //             "x" => "xx"
        //         ]);
        //     }
        // }
    }
}