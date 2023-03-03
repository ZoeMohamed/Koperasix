@extends('user.app')
@section('content')
    <div class="site-blocks-cover" style="background-image: url({{ asset('shopper') }}/images/hero_1.jpg);" data-aos="fade">
        <div class="container">
            <div class="row align-items-start align-items-md-center justify-content-end">
                <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
                    <h1 class="mb-2">Cari Kebutuhan Seragam Kamu Di Sini</h1>


                    <div class="intro-text text-center text-md-left">
                        <p class="mb-4">Seragam disini terjamin kualitasnya dan tentunya barangnya juga original.</p>
                        <p>
                            <a href="{{ route('user.produk') }}" class="btn btn-sm btn-primary">Belanja Sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section block-3 site-blocks-2 bg-light" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 site-section-heading text-center pt-4">
                    <h2>Produk</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="nonloop-block-3 owl-carousel">
                        @foreach ($produks as $produk)
                            <div class="item">
                                <div class="block-4 text-center">
                                    <a href="{{ route('user.produk.detail', ['id' => $produk->id]) }}">
                                        <figure class="block-4-image">
                                            <img src="{{ asset('storage/' . $produk->image) }}" alt="Image placeholder"
                                                class="img-fluid" width="100%" style="height:300px">
                                        </figure>
                                    </a>
                                    <div class="block-4-text p-4">
                                        <h3><a
                                                href="{{ route('user.produk.detail', ['id' => $produk->id]) }}">{{ $produk->name }}</a>
                                        </h3>
                                        <p class="mb-0">Rp. {{ $produk->price }}</p>
                                        <a href="{{ route('user.produk.detail', ['id' => $produk->id]) }}"
                                            class="btn btn-primary mt-2">Detail</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
