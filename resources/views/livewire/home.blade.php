<div class="container">
    @if(Auth::user())
        @if(Auth::user()->level == 1)
        <div class="col-md-3">
            <a href="{{ url('TambahProduk/') }}" class="btn btn-primary btn-block mb-3">Tambah Products</a>
        </div>
        @endif
    @endif

    
    
    
<div class="row">
    <div class="col-md-4">
        <div class="input-group mb-3">
            <input type="text" wire:model="search" class="form-control" placeholder="Search..." aria-label="Search">
        </div>
        <img src="{{ asset('storage/logo/banner3.jpg') }}" width="750px" height="230px">
    </div>
    <div class="col-md-5">
            
    </div>
    <div class="col-md-3">
        <div class="input-group mb-3">
            <input type="text" wire:model="min" class="form-control" placeholder="Harga Min..." aria-label="Harga Min">
        </div>
        <div class="input-group mb-3">
            <input type="text" wire:model="max" class="form-control" placeholder="Harga Max..." aria-label="Harga Max">
        </div>
        <img src="{{ asset('storage/logo/logo.jpg') }}" width="260px" height="180px" >
    </div>
    
    {{-- <h2 class="text-white float-right">Online Shop Murah Meriah dan Terpercaya</h2> --}}
</div>

<section class="products mb-5">
    <div class="row mt-4">
        @foreach ($products as $product)   
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset('storage/photos/'.$product->gambar) }}" width="200px" height="270px">
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h5><strong>{{ $product->nama }}</strong></h5>
                            <h6><strong>Rp. {{ number_format($product->harga) }}</strong></h6>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button class="btn btn-success btn-block" wire:click="beli({{ $product->id }})">Beli Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        @endforeach
        {{-- {{ $products->links() }} --}}
    </div>  
</section>





</div>