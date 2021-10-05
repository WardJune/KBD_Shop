@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4 text-capitalize">{{ $slugs ?? 'Product' }}</h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active text-capitalize" aria-current="page">{{ $slugs ?? 'Product' }}</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}

    <div class="container mb-5">
        <div class="row px-3">
            <div class="col-md-2 pl-0">
                <div class="border-bottom">
                    Filter By :
                </div>
            </div>

            <div class="col-md-10 pl-0">
                <form id="sortForm" action="{{ route('front.category', $slugs)}}" class="d-inline">
                <div class="border-bottom justify-content-between d-flex">
                    <span>Sort by: 
                        <select id="sort" class="border-0 bg-transparent rounded-0" name="sort">
                            <option value="" >Default</option>
                            <option {{ ($data['sort'] ?? '') && $data['sort'] == ('name-asc') ? 'selected' : '' }} value="name-asc">Name, A - Z </option>
                            <option {{ ($data['sort'] ?? '') && $data['sort'] == ('name-desc') ? 'selected' : '' }} value="name-desc">Name, Z - A</option>
                            <option {{ ($data['sort'] ?? '') && $data['sort'] == ('price-asc') ? 'selected' : '' }} value="price-asc">Price, Low to High</option>
                            <option {{ ($data['sort'] ?? '') && $data['sort'] == ('price-desc') ? 'selected' : '' }} value="price-desc">Price, High to Low</option>
                        </select>
                    </span>
                   
                    <span>
                        <a href="#" id="grid"><i class="fa fa-th text-muted"></i></a>
                        <a href="#" id="list"><i class="fa fa-th-list text-light"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="row px-3 mt-3">
            <div class="col-md-2 pl-0">
                <a href="{{ route('front.category', $slugs) }}" class="justify-content-between d-flex mb-2">
                    <span>Clear Filter</span>
                    <span class="text-warning font-weight-bold">x</span>
                </a>

                <ul class="navbar-nav">
                    @include('ecommerce.partials.sidebar-kebs')
                    @if ($slugs == 'keyboard')
                        @include('ecommerce.partials.sidebar-kebs-size')
                        @include('ecommerce.partials.sidebar-kebs-switch')
                    @elseif ($slugs == 'switch')
                        @include('ecommerce.partials.sidebar-switch')
                    @else
                        @include('ecommerce.partials.sidebar-keycap-type')
                    @endif
                </ul>
            </div>
        </form>
            <div class="col-md-10 pl-0">
                <div class="row" id="contain">
                    @forelse ($products as $product)
                        <div class="col-md-4 col-6">
                            <a href="{{ route('front.show', $product->slug) }}" class="d-block">
                                <div class="card bg-transparent w-100 shadow-none border-0 text-center shadow--hover">
                                    <img class="card-img-top" src="{{ asset('/storage/' . $product->image) }}"
                                        alt="Image placeholder">
                                    <div class="d-flex flex-column justify-content-between">
                                        <h5 class="h3 card-title mt-1 mb-0 font-weight-500">{{ $product->name }}</h5>
                                        <h5 class="h3 text-warning ">IDR {{ number_format($product->price) }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div> 
                    @empty
                        <div class="col-md-12 text-center align-content-center">
                            <h2 class="text-warning">Sorry, no exact matches were found</h2>
                        </div>
                    @endforelse
                </div>
                {{ $products->appends($data)->links() }}
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $('#sort').on('change', function(){
            $('#sortForm').submit();
        })
        
        $('.filterRadio').on('click', function(){
            $('#sortForm').submit();
        })

        $('.filterSize').on('click', function(){
            $('#sortForm').submit();
        })

        $('.filterSwitch').on('click', function(){
            $('#sortForm').submit();
        })

        $('.filterType').on('click', function(){
            $('#sortForm').submit();
        })

        $('.filterKeycap').on('click', function(){
            $('#sortForm').submit();
        })

        $('#grid').on('click', function() {
            event.preventDefault();
            $('#contain').empty()
            $('#contain').append(`@foreach ($products as $product)<div class="col-md-4 col-6"><a href="{{ route("front.show", $product->slug) }}" class="d-block"><div class="card bg-transparent w-100 shadow-none border-0 text-center shadow--hover"><img class="card-img-top" src="{{ asset("/storage/" . $product->image) }}"alt="Image placeholder"><div class="d-flex flex-column justify-content-between"><h5 class="h3 card-title mt-1 mb-0 font-weight-500">{{ $product->name }}</h5><h5 class="h3 text-warning ">IDR {{ number_format($product->price) }}</h5></div></div></a></div>@endforeach`)
        })

        $('#list').on('click', function() {
            event.preventDefault();
            $('#contain').empty()
            $('#contain').append(`@foreach ($products as $product)<div class="col-md-12">
                            <a href="{{ route('front.show', $product->slug) }}" class="d-block">
                                <div class="card rounded-0 shadow-none shadow--hover">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <img class="img-thumbnail border-0 shadow-none p-0"
                                                    src="{{ asset('/storage/' . $product->image) }}"
                                                    alt="Image placeholder">
                                            </div>
                                            <div class="col-md-7 align-self-center">
                                                <h5 class="h3 mb-3 font-weight-bold">{{ $product->name }}
                                                </h5>
                                                <span
                                                    class="text-muted">{!! \Str::limit($product->fulldesc, 150, '...') !!}</span>
                                            </div>
                                            <div class="col-md-2 align-self-center text-right">
                                                <span class="h3 text-warning ">IDR
                                                    {{ number_format($product->price) }}</span>
                                                {!! $product->stock_status !!}
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="qty" value="1">
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <button class="btn btn-warning btn-sm rounded-0" {{ $product->button_status }}>Add to Cart</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>@endforeach`)
        })


    </script>
@endpush
