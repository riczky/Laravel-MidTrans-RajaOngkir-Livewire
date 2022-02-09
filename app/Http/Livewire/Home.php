<?php

namespace App\Http\Livewire;


use App\Models\Produk;
use App\Models\Belanja;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;


class Home extends Component
{

    // Atribut Filltering
    public $search, $min, $max;

    // Get Data Products
    public $products = [];

    public function beli($id)
    {
        if (!Auth::user()) {
            return Redirect()->route('login');
        }
        // mencari produk
        $produk = Produk::find($id);

        Belanja::create(
            [
                'user_id' => Auth::user()->id,
                'total_harga' => $produk->harga,
                'produk_id' => $produk->id,
                'status'    => 0
            ]
        );

        return redirect()->to('BelanjaUser');
    }

    public function render()
    {
        // Filter harga max
        if ($this->max) {
            $harga_max = $this->max;
        } else {
            $harga_max = 50000000000;
        }

        // Filter harga min
        if ($this->min) {
            $harga_min = $this->min;
        } else {
            $harga_min = 0;
        }

        // Filter Search
        if ($this->search) {
            $this->products = Produk::where('nama', 'like', '%' . $this->search . '%')->where('harga', '>=', $harga_min)->where('harga', '<=', $harga_max)->get();
        } else {
            // Get data Products
            // $this->products = Produk::all();
            $this->products = Produk::where('harga', '>=', $harga_min)->where('harga', '<=', $harga_max)->get();
        }

        return view('livewire.home')->extends('layouts.app')->section('content');
    }
}
