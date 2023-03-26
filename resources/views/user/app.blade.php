<!DOCTYPE html>
<html lang="en">

<head>
    <title>Web Koperasi &mdash; SMKN 10 Jakarta </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700">
    <link rel="stylesheet" href="{{ asset('shopper') }}/fonts/icomoon/style.css">

    <link rel="stylesheet" href="{{ asset('shopper') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/owl.theme.default.min.css">


    <link rel="stylesheet" href="{{ asset('shopper') }}/css/aos.css">

    <link rel="stylesheet" href="{{ asset('shopper') }}/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
</head>

<body>

    <div class="site-wrap">
        <header class="site-navbar" role="banner">
            <div class="site-navbar-top">
                <div class="container">
                    <div class="row align-items-center">

                        <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
                            <form action="{{ route('user.produk.cari') }}" method="get" class="site-block-top-search">
                                @csrf
                                <span class="icon icon-search2"></span>
                                <input type="text" class="form-control border-0" name="cari" placeholder="Search">
                            </form>
                        </div>

                        <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
                            <div class="site-logo">
                                <a href={{ route('home') }} class="js-logo-clone">Koperasi 10</a>


                            </div>
                        </div>



                        <div class="col-6 col-md-4 order-3 order-md-3 text-right">
                            <div class="top-right links">
                                <div class="site-top-icons">
                                    <ul>
                                        @if (Route::has('login'))
                                            @auth
                                                <li>
                                                    <div class="dropdown">
                                                        <a class="dropdown-toggle" id="dropdownMenuButton"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="icon icon-person"></span>
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item"
                                                                href="{{ route('user.alamat') }}">Pengaturan Alamat</a>
                                                            <a class="dropdown-item" href="#">Pengaturan Akun</a>
                                                            <a class="dropdown-item" href="#">

                                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                                                    <i class="mdi mdi-logout mr-2 text-primary"></i> Logout
                                                                </a>

                                                                <form id="logout-form" action="{{ route('logout') }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                </form>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php
                                                    $user_id = \Auth::user()->id;
                                                    $total_keranjang = \DB::table('keranjang')
                                                        ->select(DB::raw('count(id) as jumlah'))
                                                        ->where('user_id', $user_id)
                                                        ->first();
                                                    ?>
                                                    <a href="{{ route('user.keranjang') }}" class="site-cart">
                                                        <span class="icon icon-add_shopping_cart"></span>
                                                        <span class="count">{{ $total_keranjang->jumlah }}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <?php
                                                    $user_id = \Auth::user()->id;
                                                    $total_order = \DB::table('order')
                                                        ->select(DB::raw('count(id) as jumlah'))
                                                        ->where('user_id', $user_id)
                                                        ->where('status_order_id', '!=', 5)
                                                        ->where('status_order_id', '!=', 6)
                                                        ->first();
                                                    ?>
                                                    <a href="{{ route('user.order') }}" class="site-cart">
                                                        <span class="icon icon-shopping_cart"></span>
                                                        <span class="count">{{ $total_order->jumlah }}</span>
                                                    </a>
                                                </li>
                                            @else
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle" id="dropdownMenuButton"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="icon icon-person"></span>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="{{ route('login') }}">Masuk</a>
                                                        @if (Route::has('register'))
                                                            <a class="dropdown-item"
                                                                href="{{ route('register') }}">Daftar</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endauth
                                </div>
                                @endif
                                <li class="d-inline-block d-md-none ml-md-0"><a href="#"
                                        class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a>
                                </li>
                            </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="site-navigation text-right text-md-center" role="navigation">
                <div class="container">
                    <ul class="site-menu js-clone-nav d-none d-md-block">
                        <li class="{{ Request::path() === '/' ? '' : '' }}"><a href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="{{ Request::path() === 'produk' ? '' : '' }}"><a
                                href="{{ route('user.produk') }}">Produk</a></li>
                    </ul>
                </div>
            </nav>
        </header>

        @yield('content')
    </div>

    <script src="{{ asset('shopper') }}/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    <script src="{{ asset('shopper') }}/js/jquery-ui.js"></script>
    <script src="{{ asset('shopper') }}/js/popper.min.js"></script>
    <script src="{{ asset('shopper') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('shopper') }}/js/owl.carousel.min.js"></script>
    <script src="{{ asset('shopper') }}/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ asset('shopper') }}/js/aos.js"></script>

    <script src="{{ asset('shopper') }}/js/main.js"></script>
    @yield('js')
</body>

</html>
