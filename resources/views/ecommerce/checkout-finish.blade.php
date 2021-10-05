@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">Order Confirmation </h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Confirmation</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}

    <div class="container my-7">
        <div class="mb-5 text-center">
            <h3 class="text-center text-warning">Thank you. Your order has been received</h3>
            @if ($order->payment_count < 1)
                <span class="d-block text-muted">After you make payment, please confirm payment via the link below</span>
                <span class="d-block text-default mb-md-2">Confirm payment before 23:00, otherwise the order will be
                    automatically
                    canceled</span>
                <a href="{{ route('payment.form', $order->invoice) }}">Confirm Payment</a>
            @endif
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4 class="border-bottom">Order Info</h4>
                <div class="row">
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li>Invoice</li>
                            <li>Date</li>
                            <li>Total</li>
                            <li>Payment Method</li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li>: {{ $order->invoice }}</li>
                            <li>: {{ $order->created_at }}</li>
                            <li>: IDR {{ number_format($order->total) }}</li>
                            <li>: Bank Transfer</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class=" col-md-6">
                <h4 class="border-bottom">Shipping Address</h4>
                <div class="row">
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li>Address</li>
                            <li>Province</li>
                            <li>City</li>
                            <li>District</li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li>: {{ $order->customer_address }}</li>
                            <li>: {{ $order->district->province->name }}</li>
                            <li>: {{ $order->district->city->name }}</li>
                            <li>: {{ $order->district->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-none bg-secondary mt-4 rounded-sm">
            <div class="card-body">
                <h3>Order Details</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $row)
                                <tr>
                                    <td>{{ $row->product->name }}</td>
                                    <th>x {{ $row->qty }}</th>
                                    <td>IDR {{ number_format($row->qty * $row->price) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2">SUBTOTAL</th>
                                <td>IDR {{ number_format($order->subtotal) }}</td>
                            </tr>
                            <tr>
                                <th>SHIPPING</th>
                                <td>{{ $order->shipping }}</td>
                                <td>IDR {{ number_format($order->cost) }}</td>
                            </tr>
                            <tr>
                                <th colspan="2">TOTAL</th>
                                <th>IDR {{ number_format($order->total) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
