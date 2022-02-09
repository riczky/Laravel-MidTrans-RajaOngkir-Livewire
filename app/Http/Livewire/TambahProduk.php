<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\Produk;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;



class TambahProduk extends Component
{

    public $nama, $harga, $berat, $gambar;
    use WithFileUploads;
    public function mount()
    {
        if (Auth::user()) {
            if (Auth::user()->level !== 1) {
                return redirect()->to('');
            }
        }
    }

    public function store()
    {
        // Validasi
        $this->validate(
            [
                'nama'  => 'required',
                'harga' => 'required',
                'berat' => 'required',
                'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]
        );

        // Proses Data File Gambar
        $nama_gambar = md5($this->gambar . microtime()) . '.' . $this->gambar->extension();
        Storage::disk('public')->putFileAs('photos', $this->gambar, $nama_gambar);

        // Memasukkan data ke database
        Produk::create(
            [
                'nama' => $this->nama,
                'harga' => $this->harga,
                'berat' => $this->berat,
                'gambar' => $nama_gambar
            ]
        );

        return redirect()->to('');
    }

    public function render()
    {
        return view('livewire.tambah-produk')->extends('layouts.app')->section('content');
    }
}
