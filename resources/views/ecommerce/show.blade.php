@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">{{ $product->name }} </h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('front.category', $product->category->slug) }}">Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                <span class="alert-text text-sm">"{{ $product->name }}" has been
                    added to
                    your cart. You can <a href="{{ route('cart.show') }}">view
                        your cart</a> and checkout any time.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('wishlist'))
            <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                <span class="alert-text text-sm">"{{ $product->name }}" has been
                    added to
                    your wishlist.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                        <p class="h3 text-warning mb-3">IDR {{ number_format($product->price) }}</p>
                        <h4>Description</h4>
                        <ul class="pl-4">
                            @foreach ($product->desc as $desc)
                                @if ($desc != null)
                                    <li>{{ $desc }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <form method="post">
                            @csrf
                            {{-- input hidden id --}}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="row align-items-center mb-4">
                                <label class="col-md-3" for="qty">Quantity:</label>
                                {{-- button increase and decrement --}}
                                <div class=" d-flex col-md-4 border-bottom border-neutral px-0">
                                    <button
                                        onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;"
                                        class="btn btn-sm shadow-none--hover mr-0" type="button">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input class="form-control form-control-flush text-center" type="number" name="qty"
                                        id="sst" min="1" minlength="1" value="1" title="Quantity:" class="input-text qty">
                                    <button
                                        onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                        class="btn btn-sm shadow-none--hover" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    {{-- end button --}}
                                </div>
                                <div class="ml-md-2">Stock : <span
                                        class="font-weight-bold">{{ $product->stock->qty }}</span>
                                </div>
                            </div>
                            @error('qty')
                                <div class="mt-md--3">
                                    <small class="text-danger ml-2">{{ $message }}</small>
                                </div>
                            @enderror
                            <hr class="border-dark my-3">

                            <div class="row mb-3">
                                <div class="col-md-8 col-8">
                                    <button {{ $product->button_status }} type="submit" class="btn btn-danger btn-block"
                                        formaction="{{ route('cart.add', ['cart' => 'cart']) }}">ADD TO
                                        CART
                                    </button>
                                </div>
                                <div class="col-md-4 col-4">
                                    <button type="submit"
                                        class="btn btn-block {{ $wishlist == null ? 'btn-warning' : 'btn-outline-warning' }}"
                                        data-toggle="tooltip" data-placement="top"
                                        title="{{ $wishlist == null ? 'Add To Wishlist' : 'Remove From Wishlist' }}"
                                        formaction="{{ $wishlist == null ? route('wishlist.add') : route('wishlist.destroy', $wishlist->id) }}"><i
                                            class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>

                            <button {{ $product->button_status }} class="btn btn-warning btn-block" type="submit"
                                formaction="{{ route('cart.add', ['buy' => 'buy']) }}">BUY IT NOW
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-7">
                <svg class="bd-placeholder-img img-thumbnail rounded-0 mr-2" width="75" height="75"
                    xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#6c757d"></rect>
                </svg>
                <svg class="bd-placeholder-img img-thumbnail rounded-0 mr-2" width="75" height="75"
                    xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#6c757d"></rect>
                </svg>
                <svg class="bd-placeholder-img img-thumbnail rounded-0 mr-2" width="75" height="75"
                    xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#6c757d"></rect>
                </svg>
                <svg class="bd-placeholder-img img-thumbnail rounded-0 mr-2" width="75" height="75"
                    xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#6c757d"></rect>
                </svg>
            </div>
        </div>

        <hr>

        <ul class="nav nav-tabs justify-content-center border-0 mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active border-0 rounded-0" id="overview-tab" data-toggle="tab" href="#overview"
                    role="tab" aria-controls="overview" aria-selected="true">Overview</a>
            </li>
            @if ($product->specifications()->count() > 0)
                <li class="nav-item" role="presentation">
                    <a class="nav-link border-0 rounded-0" id="specifications-tab" data-toggle="tab" href="#specifications"
                        role="tab" aria-controls="specifications" aria-selected="false">Specifications</a>
                </li>
            @endif
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
                <div class="container px-md-5">
                    {!! nl2br($product->fulldesc) !!}
                </div>
            </div>
            <div class="tab-pane " id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                <div class="container px-md-5">
                    @foreach ($product->specifications as $spec)
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <h4> {{ $spec->name }} </h4>
                            </div>
                            <div class="col-md-4">: {{ $spec->pivot->value }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <section class="container mt-6 mb-7 text-center justify-content-center">
            <h1>RELATED PRODUCT</h1>
            <div class="row mt-5">
                @foreach ($related as $rel)
                    <div class="col-md-3">
                        <a href="{{ route('front.show', $rel->slug) }}">
                            <div class="card bg-transparent shadow-none border-0 text-center">
                                <img class="card-img-top" src="{{ asset('/storage/' . $rel->image) }}"
                                    alt="Image placeholder">
                                <h5 class="h3 card-title mt-3 mb-0 font-weight-500">{{ $rel->name }}</h5>
                                <h5 class="h3 text-warning  font-weight-normal">IDR
                                    {{ number_format($rel->price) }}
                                </h5>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
