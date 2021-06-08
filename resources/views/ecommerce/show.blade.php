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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
                <span class="alert-text text-sm">"{{ session('success') }}" has been
                    added to
                    your cart. You can <a href="{{ route('front.show_cart') }}">view
                        your cart</a> and checkout any time.</span>
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
                        <p class="h3 text-warning">IDR {{ number_format($product->price) }}</p>
                        <p class="mb-4"> SKU : THISISSKU</p>
                        <h4>Description</h4>
                        <ul class="pl-4">
                            @foreach ($product->desc as $desc)
                                <li>{{ $desc }}</li>
                            @endforeach
                        </ul>
                        <form action="" method="post">
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
                                    <input class="form-control form-control-flush text-center" type="text" name="qty"
                                        id="sst" minlength="1" value="1" title="Quantity:" class="input-text qty">
                                    <button
                                        onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                        class="btn btn-sm shadow-none--hover" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    {{-- end button --}}
                                </div>
                                @error('qty')
                                    <small class="text-danger ml-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <hr class="border-dark my-3">

                            <div class="row mb-3">
                                <div class="col-md-8 col-8">
                                    <button type="submit" class="btn btn-danger btn-block"
                                        formaction="{{ route('front.cart') }}">
                                        ADD TO CART</button>
                                </div>
                                <div class="col-md-4 col-4">
                                    <button type="submit" class="btn btn-warning btn-block"><i
                                            class="fas fa-heart"></i></button>
                                </div>
                            </div>

                            <button class="btn btn-warning btn-block" type="submit" formaction="">BUY
                                IT
                                NOW</button>
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
                    <title>A generic square placeholder image with a white border around it, making it resemble a photograph
                        taken with an old instant camera</title>
                    <rect width="100%" height="100%" fill="#6c757d"></rect>
                </svg>
                <svg class="bd-placeholder-img img-thumbnail rounded-0 mr-2" width="75" height="75"
                    xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>A generic square placeholder image with a white border around it, making it resemble a photograph
                        taken with an old instant camera</title>
                    <rect width="100%" height="100%" fill="#6c757d"></rect>
                </svg>
                <svg class="bd-placeholder-img img-thumbnail rounded-0 mr-2" width="75" height="75"
                    xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>A generic square placeholder image with a white border around it, making it resemble a photograph
                        taken with an old instant camera</title>
                    <rect width="100%" height="100%" fill="#6c757d"></rect>
                </svg>
                <svg class="bd-placeholder-img img-thumbnail rounded-0 mr-2" width="75" height="75"
                    xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>A generic square placeholder image with a white border around it, making it resemble a photograph
                        taken with an old instant camera</title>
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
            <li class="nav-item" role="presentation">
                <a class="nav-link border-0 rounded-0" id="specifications-tab" data-toggle="tab" href="#specifications"
                    role="tab" aria-controls="specifications" aria-selected="false">Specifications</a>
            </li>
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
                {!! nl2br($product->fulldesc) !!}
            </div>
            <div class="tab-pane " id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa adipisci a nemo ipsam sunt vero perspiciatis
                enim veritatis consequatur, suscipit repudiandae at inventore eligendi ullam impedit asperiores illo ex.
                Nobis obcaecati nemo minima sapiente, numquam excepturi. Ullam, quos, quidem facere rerum officia veniam
                obcaecati odit dolore, ipsum repellat ab harum repellendus. Inventore molestias ex natus, error commodi
                blanditiis aliquam eos nulla quas a cupiditate porro, consequatur iusto exercitationem impedit itaque, nam
                id nobis voluptates. Enim doloribus mollitia consectetur perferendis voluptatum dicta delectus possimus,
                dolor facere labore modi eius, magnam dolorem velit veniam quod amet totam commodi eveniet repudiandae
                tempora? Laborum.
            </div>
        </div>

        <section class="container mt-6 mb-7 text-center justify-content-center">
            <h1>RELATED PRODUCT</h1>
            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="card rounded-0 bg-pink">
                        <div class="card-header bg-transparent">
                            <!-- Title -->
                            <h5 class="h3 mb-0">Card title</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis non
                                dolore est fuga nobis ipsum illum eligendi nemo iure repellat, soluta, optio minus ut
                                reiciendis voluptates enim impedit veritatis officiis.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card rounded-0 bg-pink">
                        <div class="card-header bg-transparent">
                            <!-- Title -->
                            <h5 class="h3 mb-0">Card title</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis non
                                dolore est fuga nobis ipsum illum eligendi nemo iure repellat, soluta, optio minus ut
                                reiciendis voluptates enim impedit veritatis officiis.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card rounded-0 bg-pink">
                        <div class="card-header bg-transparent">
                            <!-- Title -->
                            <h5 class="h3 mb-0">Card title</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis non
                                dolore est fuga nobis ipsum illum eligendi nemo iure repellat, soluta, optio minus ut
                                reiciendis voluptates enim impedit veritatis officiis.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
