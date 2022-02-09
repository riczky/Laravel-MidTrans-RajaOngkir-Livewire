<?php

namespace App\Http\Livewire;

use Kavist\RajaOngkir\RajaOngkir;
use Livewire\Component;
use App\Models\Belanja;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class TambahOngkir extends Component
{
    public $belanja;
    private $apiKey = 'f8dc2ad58312960366b9bb12e4a07c0e';
    public $provinsi_id, $kota_id, $jasa, $daftarProvinsi, $daftarKota, $nama_jasa;
    public $result = [];

    public function mount($id)
    {
        if (!Auth::user()) {
            return redirect()->route('login');
        }
        $this->belanja = Belanja::find($id);

        if ($this->belanja->user_id != Auth::user()->id) {
            return redirect()->to('');
        }
    }


    public function getOngkir()
    {
        // jika datanya kosong
        if (!$this->provinsi_id || !$this->kota_id || !$this->jasa) {
            return;
        }

        // Mengambil data produk
        $produk = Produk::find($this->belanja->produk_id);

        // mengambil biaya ongkir        
        $rajaOngkir = new RajaOngkir($this->apiKey);
        $cost = $rajaOngkir->ongkosKirim([
            'origin'        => 155,     // ID kota/kabupaten asal (Jakarta Utara)
            'destination'   => $this->kota_id,      // ID kota/kabupaten tujuan
            'weight'        => $produk->berat,    // berat barang dalam gram
            'courier'       => $this->jasa    // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        ])->get();

        // Pengecekan 
        // dd($cost);

        // nama jasa
        $this->nama_jasa = $cost[0]['name'];
        // dd($this->nama_jasa);

        foreach ($cost[0]['costs'] as $row) {
            $this->result[] = array(
                'description'   => $row['description'],
                'biaya'         => $row['cost'][0]['value'],
                'etd'           => $row['cost'][0]['etd']
            );
        }
        // dd($this->result);
    }

    public function save_ongkir($biaya_pengiriman)
    {
        $this->belanja->total_harga += $biaya_pengiriman;
        $this->belanja->status = 1;
        $this->belanja->update();

        return redirect()->to('BelanjaUser');
    }


    public function render()
    {
        // Semua Provinsi
        $rajaOngkir = new RajaOngkir($this->apiKey);
        $this->daftarProvinsi = $rajaOngkir->provinsi()->all();
        // dd($this->daftarProvinsi);

        // $this->provinsi_id = 6;
        // Daftar kota/kabupaten berdasarkan id provinsinya
        if ($this->provinsi_id) {
            $this->daftarKota = $rajaOngkir->kota()->dariProvinsi($this->provinsi_id)->get();
            // dd($this->daftarKota);
        }

        return view('livewire.tambah-ongkir')->extends('layouts.app')->section('content');
    }
}
