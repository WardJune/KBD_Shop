@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">Your Wishlist </h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="{{ route('front') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}
    <div class="container mb-7">
        @if ($wishlists->count() > 0)
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
                            <h4>AVAILBILITY</h4>
                        </th>
                        <th colspan="2">
                            <h4>ACTION</h4>
                        </th>
                    </tr>
                </thead>
                <tbody class="">
                    @php $i = 1; @endphp
                    @foreach ($wishlists as $wishlist)
                        <form method="post">
                            @csrf
                            <tr class="border-bottom">
                                <input type="hidden" name="product_id" value="{{ $wishlist->product_id }}">
                                <input type="hidden" name="qty" value="1">
                                <th class="align-middle">
                                    <a href="{{ route('front.show', $wishlist->product->slug) }}">
                                        <div class="media align-items-center">
                                            <img src="{{ asset('/storage/' . $wishlist->product->image) }}" class="mr-3"
                                                width="75px">
                                            <div class="media-body">
                                                <h5 class="mt-0">{{ $wishlist->product->name }}<h5>
                                            </div>
                                        </div>
                                    </a>
                                </th>
                                <td class="align-middle">IDR {{ number_format($wishlist->product->price) }}</td>
                                <td class="align-middle">{{ $wishlist->stock_label }}</td>
                                <td class="align-middle"><button {{ $wishlist->button_status }}
                                        class="btn btn-sm btn-default rounded-0" type="submit"
                                        formaction="{{ route('cart.add') }}">Add To
                                        Cart</button>
                                </td>
                                <td class="align-middle"><a data-toggle="tooltip" data-placement="right" title="Delete"
                                        href="{{ route('wishlist.show') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('deleteForm{{ $i }}').submit();"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                        </form>
                        <form action="{{ route('wishlist.destroy', $wishlist->id) }}" id="deleteForm{{ $i }}"
                            method="POST">
                            @csrf
                        </form>
                        @php $i++ @endphp
                    @endforeach
                </tbody>
            </table>

        @else
            <div class="container text-center min-vh-100 ">
                <h4 class="mb-3 align-content-center">Your wishlist is currently empty!
                </h4>
                <a href="{{ route('front') }}" class="btn btn-default rounded-0 align-content-center">Continue
                    Shopping</a>
            </div>
        @endif
    </div>
@endsection
