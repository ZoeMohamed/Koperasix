<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = DB::table('users')
         
            ->where('users.role', '=', 'customer')->get();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }
}