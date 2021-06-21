@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">Product </h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}

    <div class="container ">
        <div class="row px-3">
            <div class="col-md-2 pl-0">
                <div class="border-bottom">
                    Filter By :
                </div>
            </div>
            <div class="col-md-10 pl-0">
                <div class="border-bottom justify-content-between d-flex">
                    <span>Sort by: Default

                    </span>
                    <span>
                        <a href="#"><i class="fa fa-th text-muted"></i></a>
                        <a href="#"><i class="fa fa-th-list text-light"></i></a>
                    </span>
                </div>
            </div>
        </div>

        <div class="row px-3 mt-3">
            <div class="col-md-2 pl-0">
                <ul class="list-unstyled">
                    <li>
                        <a href="#" class="justify-content-between d-flex">
                            <span>Keyboard Size</span>
                            <span class="text-warning">+</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="justify-content-between d-flex">
                            <span>Keyboard Size</span>
                            <span class="text-warning">+</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="justify-content-between d-flex">
                            <span>Keyboard Size</span>
                            <span class="text-warning">+</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10 pl-0">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-4 col-6">
                            <a href="{{ route('front.show', $product->slug) }}" class="d-block">
                                <div class="card bg-transparent w-100 shadow-none border-0 text-center">

                                    <img class="card-img-top" src="{{ asset('/storage/' . $product->image) }}"
                                        alt="Image placeholder">
                                    <div class="d-flex flex-column justify-content-between">
                                        <h5 class="h3 card-title mt-1 mb-0 font-weight-500">{{ $product->name }}</h5>
                                        <h5 class="h3 text-warning ">IDR {{ number_format($product->price) }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>

@endsection
