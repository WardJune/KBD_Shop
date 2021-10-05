@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container">
        <div class="row my-md-5">
            <div class="col-md-2">
                @include('layouts.ecommerce.nav.sidebar-profile')
            </div>
            <div class="col-md-10">
                <ul class="nav justify-content-md-around border-0 mb-3 bg-secondary">
                    <li class="nav-item">
                        <a class="nav-link {{ $border['null'] ?? '' }} " href="{{ route('order.dashboard') }}">
                            All Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $border[0] ?? '' }}" href="{{ route('order.dashboard', 0) }}">Awaiting
                            Payment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $border[1] ?? '' }}" href="{{ route('order.dashboard', 1) }}">Awaiting
                            Confirmation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $border[2] ?? '' }}" href="{{ route('order.dashboard', 2) }}">Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $border[3] ?? '' }}" href="{{ route('order.dashboard', 3) }}">Sent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $border[4] ?? '' }}" href="{{ route('order.dashboard', 4) }}">Done</a>
                    </li>
                </ul>
                @forelse ($orders as $order)
                    <div class="card shadow-none rounded-0 bg-secondary mb-3">
                        <div class="card-header bg-transparent d-md-flex justify-content-md-between">
                            <div>Invoice : <span class="h4">{{ $order->invoice }}</span></div>
                            <div>Status : {!! $order->status_label !!}</div>
                        </div>
                        <div class="card-body">
                            @foreach ($order->details as $detail)
                                <div class="row mb-2">
                                    <div class="col-md-7 ">
                                        <div class="media">
                                            <img class="mr-3 rounded-sm" width="64" height="64"
                                                src="{{ asset('/storage/' . $detail->product->image) }}" alt="">
                                            <div class="media-body align-self-center">
                                                <span class="text-sm">
                                                    {{ $detail->product->name }}</span>
                                                <span class="text-sm text-muted d-md-block">x{{ $detail->qty }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 align-self-center text-right text-muted">
                                        IDR {{ number_format($detail->product->price) }}
                                    </div>
                                </div>
                            @endforeach
                            <hr class="mb-md-3">
                            <div class="d-md-flex justify-content-between">
                                <span class="d-block text-muted">{{ $order->details->count() }} Product</span>
                                <span class="d-block">Total <span class="text-warning">IDR
                                        {{ number_format($order->total) }}</span></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-md-end">
                            @if ($order->status == 3 && $order->return_count == 0)
                                <form action="{{ route('order.accept', $order->id) }}" class="inline mr-md-2"
                                    onsubmit="return confirm('r u sure ?')" method="POST">
                                    @csrf
                                    @method('patch')
                                    <button type="submit" class="btn btn-warning rounded-0">Receive
                                        Order</button>
                                </form>
                                <a href="{{ route('order.return-form', $order->invoice) }}"
                                    class="btn btn-warning rounded-0">Return</a>
                            @endif
                            <a href="{{ route('order.show', $order->invoice) }}"
                                class="btn btn-warning rounded-0">Details</a>
                        </div>
                    </div>
                @empty

                @endforelse
            </div>
        </div>
    </div>
@endsection
