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
                @include('ecommerce.partials.order-card')
            </div>
        </div>
    </div>
@endsection
