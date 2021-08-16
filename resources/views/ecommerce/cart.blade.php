@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">Your Shopping Cart </h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}
    @if ($cart && $cart->products->count() > 0)
        <div class="container mb-7">
            @if (session('message'))
                <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
                    <span class="alert-text text-sm text-capitalize">{{ session('message') }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{-- @if (session()->has('message'))
                <div class="alert alert-danger rounded-0 text-capitalize">{{ session('message') }}</div>
            @endif --}}
            <table class="table table-borderless cart">
                <thead>
                    <tr>
                        <th scope="col">
                            <h4>ITEM</h4>
                        </th>
                        <th scope="col">
                            <h4>PRICE</h4>
                        </th>
                        <th scope="col">
                            <h4>QUANTITY</h4>
                        </th>
                        <th scope="col">
                            <h4>TOTAL</h4>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($cart->products as $product)
                        <form method="post">
                            @csrf
                            <input type="hidden" name="" value="">
                            <tr class="border-bottom">
                                <th class="align-middle">
                                    <a href="{{ route('front.show', $product->slug) }}">
                                        <div class="media align-items-center">
                                            <img src="{{ asset('/storage/' . $product->image) }}" class="mr-3"
                                                width="75px">
                                            <div class="media-body">
                                                <h5 class="mt-0">{{ $product->name }}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </th>
                                <td class="align-middle">IDR {{ number_format($product->price) }}</td>
                                <td class="align-middle">
                                    <div class=" d-flex border-bottom border-neutral px-0">
                                        <button
                                            onclick="var result = document.getElementById('sst{{ $product->id }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                                            class="btn btn-sm shadow-none--hover mr-0" type="button">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                        <input class="form-control form-control-flush text-center" type="text" name="qty[]"
                                            id="sst{{ $product->id }}" maxlength="5" value="{{ $product->pivot->qty }}"
                                            class="input-text qty">

                                        <input type="hidden" name="product_id[]" value="{{ $product->id }}">

                                        <button
                                            onclick="var result = document.getElementById('sst{{ $product->id }}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                            class="btn btn-sm shadow-none--hover" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="align-middle">IDR {{ number_format($product->pivot->qty * $product->price) }}
                                </td>
                                <td class="align-middle"><a href="{{ route('cart.destroy', $product->id) }}"><i
                                            class="fas fa-times"></i></a></td>
                            </tr>
                    @endforeach
                    <tr class="border-bottom">
                        <th colspan="3" class="text-right">SUB TOTAL <span class="d-block font-weight-normal">Total Before
                                Shipping</span></th>
                        <td colspan="2">IDR {{ number_format($subTotal ?? 0) }}</td>
                    </tr>
                    {{-- buttons --}}
                    <tr>
                        <td colspan="3">
                            <a href="{{ route('front.category', 'keyboard') }}"
                                class="btn btn-default rounded-0">CONTINUE
                                SHOPPING</a>
                            <button class="btn btn-default rounded-0" formaction="{{ route('cart.empty') }}">EMPTY
                                CART</button>
                            <button class="btn btn-default rounded-0" type="submit"
                                formaction="{{ route('cart.update') }}">UPDATE CART</button>
                        </td>
                        <td colspan="2">
                            <a href="{{ route('cart.checkout') }}" class="btn btn-default rounded-0">CHECKOUT</a>
                        </td>
                    </tr>
                    </form>
                    {{-- end of buttons --}}
                </tbody>
            </table>
        </div>
    @else
        <div class="container text-center min-vh-100 ">
            <h4 class="mb-3 align-content-center">An order must have at least one item. Your cart is currently empty!</h4>
            <a href="{{ route('front.category'), 'keyboard' }}"
                class="btn btn-default rounded-0 align-content-center">Continue
                Shopping</a>
        </div>

    @endif

@endsection
