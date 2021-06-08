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
    @if ($carts)

        <div class="container mb-7">
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

                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($carts as $row)
                        <form method="post">
                            @csrf
                            <tr class="border-bottom">
                                <th>
                                    <div class="media align-items-center">
                                        <img src="{{ asset('/storage/' . $row['product_image']) }}" class="mr-3"
                                            width="75px">
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ $row['product_name'] }}</h5>
                                        </div>
                                    </div>
                                </th>
                                <td class="align-self-center">IDR {{ number_format($row['product_price']) }}</td>
                                <td>
                                    <div class=" d-flex border-bottom border-neutral px-0">
                                        <button
                                            onclick="var result = document.getElementById('sst{{ $row['product_id'] }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                                            class="btn btn-sm shadow-none--hover mr-0" type="button">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                        <input class="form-control form-control-flush text-center" type="text" name="qty[]"
                                            id="sst{{ $row['product_id'] }}" maxlength="5" value="{{ $row['qty'] }}"
                                            class="input-text qty">

                                        <input type="hidden" name="product_id[]" value="{{ $row['product_id'] }}">

                                        <button
                                            onclick="var result = document.getElementById('sst{{ $row['product_id'] }}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                            class="btn btn-sm shadow-none--hover" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>IDR {{ number_format($row['qty'] * $row['product_price']) }}</td>
                                <td><i class="fas fa-times"></i></td>
                            </tr>
                    @endforeach
                    <tr class="border-bottom">
                        <th colspan="3" class="text-right">SUB TOTAL <span class="d-block font-weight-normal">Total Before
                                Shipping</span></th>
                        <td colspan="2">IDR {{ number_format($subTotal) }}</td>
                    </tr>
                    {{-- buttons --}}
                    <tr>
                        <td colspan="3">
                            <a href="{{ route('front.product') }}" class="btn btn-default rounded-0">CONTINUE
                                SHOPPING</a>
                            <button class="btn btn-default rounded-0">EMPTY CART</button>
                            <button class="btn btn-default rounded-0" type="submit"
                                formaction="{{ route('front.update_cart') }}">UPDATE
                                CART</button>
                        </td>
                        <td colspan="2">
                            <a href="{{ route('front.checkout') }}" class="btn btn-default rounded-0">CHECKOUT</a>
                        </td>
                    </tr>
                    </form>
                    {{-- end of buttons --}}
                </tbody>
            </table>
        </div>
    @else
        <div class="container text-center min-vh-100 align-items-center">
            <h4>An order must have at least one item. Your cart is currently empty!</h4>
            <a href="{{ route('front.product') }}" class="btn btn-default rounded-0">Continue Shopping</a>
        </div>

    @endif

@endsection
