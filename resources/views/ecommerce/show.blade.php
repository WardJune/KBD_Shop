@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">{{ $product->name }} </h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('front.product') }}">Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}

    <div class="container">
        <div class="row justify-content-between">
            <div class="col-md-7 bg-transparent">
                <div class="card rounded-0 shadow-none">
                    <img src="{{ asset('/storage/' . $product->image) }}" alt="" class="img-fluid mx-auto d-block w-75">
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow-none bg-light">
                    <div class="card-body">
                        <h2 class="mb-3">{{ $product->name }}</h2>
                        <p class="h3 text-warning">IDR {{ number_format($product->price) }}</p>
                        <p class="mb-4"> SKU : THISISSKU</p>
                        <h4>Description</h4>
                        <ul class="pl-4">
                            @foreach ($product->desc as $desc)
                                <li>{{ $desc }}</li>
                            @endforeach
                        </ul>
                        <div class="form-group row">
                            <label for="qty" class="col-md-3">Quantity : </label>
                            <input type="number" class="form-control col-md-2 bg-transparent border-0" placeholder="1">
                            <p class="text-danger">{{ $errors->first('weight') }}</p>
                        </div>
                        <hr class="border-dark my-3">

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-danger btn-block"> ADD TO CART</button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-warning btn-block"><i
                                        class="fas fa-heart"></i></button>
                            </div>
                        </div>

                        <button class="btn btn-warning btn-block">Buy It Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
