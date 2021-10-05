@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- start carousel --}}
    <section class="mt-4 container">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active" data-interval="2500">
                    <img width="800" height="450" role="img" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100"
                        src="{{ asset('assets/img/banner/MKID-banner-ZOMO-Distributor_1360.jpg') }}" alt="">
                </div>
                <div class="carousel-item" data-interval="2500">
                    <img width="800" height="450" role="img" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100"
                        src="{{ asset('assets/img/banner/MKID-banner-GANSS-GK87-PRO_1360.jpg') }}" alt="">
                </div>
                <div class="carousel-item" data-interval="2500">
                    <img width="800" height="450" role="img" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100"
                        src="{{ asset('assets/img/banner/MKID-banner-Tai-Hao-Indonesia_1360.jpg') }}" alt="">
                </div>
                <div class="carousel-item" data-interval="2500">
                    <img width="800" height="450" role="img" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100"
                        src="{{ asset('assets/img/banner/MKID-banner-MX-Silent-RGB-Black_1360.jpg') }}" alt="">
                </div>
            </div>

        </div>
    </section>
    {{-- end carousel --}}

    {{-- start card --}}
    <section class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('front.category', 'keyboard') }}">
                    <div class="card card-category rounded-0">
                        <img class="card-img-top rounded-0" src="{{ asset('assets/img/banner/mecha1.jpg') }}">
                        <div class="card-img-overlay">
                            <div class="card-body">
                                <h1 class="card-title text-white">Mechanical Keyboard</h1>
                                <blockquote class="blockquote text-white mb-0">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.
                                    </p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('front.category', 'keycaps') }}">
                    <div class="card card-category img-fluid rounded-0">
                        <img class="card-img-top rounded-0" src="{{ asset('assets/img/banner/keycap1.jpg') }}">
                        <div class="card-img-overlay">
                            <div class="card-body">
                                <h1 class="card-title text-white">Keycaps</h1>
                                <blockquote class="blockquote text-white mb-0">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.
                                    </p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('front.category', 'switch') }}">
                    <div class="card card-category img-fluid rounded-0">
                        <img class="card-img-top rounded-0" src="{{ asset('assets/img/banner/switch1.jpg') }}">
                        <div class="card-img-overlay">
                            <div class="card-body">
                                <h1 class="card-title text-white">Switch</h1>
                                <blockquote class="blockquote text-white mb-0">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.
                                    </p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <div class="card card-category img-fluid rounded-0">
                    <img class="card-img-top rounded-0" src="{{ asset('assets/img/banner/acc1.jpg') }}">
                    <div class="card-img-overlay">
                        <div class="card-body">
                            <h1 class="card-title text-white">Accessories</h1>
                            <blockquote class="blockquote text-white mb-0">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- end card --}}

    {{-- start new arrival --}}
    <section class="container mt-4 text-center">
        <h1>NEW ARRIVALS</h1>
        <div class="row mt-3">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <a href="{{ route('front.show', $product->slug) }}">
                        <div class="card bg-transparent shadow-none border-0 text-center">

                            <img class="card-img-top" src="{{ asset('/storage/' . $product->image) }}"
                                alt="Image placeholder">

                            <h5 class="h3 card-title mt-1 mb-0 font-weight-500">{{ $product->name }}</h5>
                            <h5 class="h3 text-warning  font-weight-normal">IDR {{ number_format($product->price) }}</h5>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
    {{-- end new arrival --}}

    {{-- banner --}}
    <section class="mt-5">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Fluid jumbotron</h1>
                <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its
                    parent.</p>
            </div>
        </div>
    </section>
    {{-- end banner --}}

    {{-- popular prduct --}}
    <section class="container mt-4 text-center justify-content-center">
        <h1>POPULAR PRODUCT</h1>
        <div class="row mt-4">
            @foreach ($popular as $pop)
                <div class="col-md-3">
                    <a href="{{ route('front.show', $pop->product->slug) }}">
                        <div class="card bg-transparent shadow-none border-0 text-center">
                            <img class="card-img-top" src="{{ asset('/storage/' . $pop->product->image) }}"
                                alt="Image placeholder">
                            <h5 class="h3 card-title mt-3 mb-0 font-weight-500">{{ $pop->product->name }}</h5>
                            <h5 class="h3 text-warning  font-weight-normal">IDR {{ number_format($pop->product->price) }}
                            </h5>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <a href="{{ route('front.category', 'keyboard') }}" class="btn btn-warning rounded-0">See All Products</a>
    </section>
    {{-- end popular prduct --}}

    {{-- another banner --}}
    <section class="mt-5">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Fluid jumbotron</h1>
                <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its
                    parent.</p>
            </div>
        </div>
    </section>
    {{-- another banner --}}
@endsection
