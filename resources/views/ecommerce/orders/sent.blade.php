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
                        <a class="nav-link" href="{{ route('order.dashboard') }}">
                            All Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order.a-payment') }}">Awaiting Payment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order.a-confirm') }}">Awaiting Confirmation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order.process') }}">Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  border-bottom border-warning active "
                            href="{{ route('order.sent') }}">Sent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order.done') }}">Done</a>
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
                                <div class="row mb-3">
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
                                    <div class="col-md-5 align-self-center text-right text-warning">
                                        IDR {{ number_format($detail->product->price) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-md-end">
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
