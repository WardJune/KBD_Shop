@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    {{-- header --}}
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Orders</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a
                                        href="{{ route('orders.index') }}">Orders</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a
                                        href="{{ route('orders.show', $order->invoice) }}">{{ $order->invoice }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Request Return</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-md--6">
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Order Details
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Customer Details</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Name</th>
                                        <td>{{ $order->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $order->customer_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reason</th>
                                        <td><span class="text-wrap">{{ $order->return->reason }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Bank Account Number (Refund)</th>
                                        <td>{{ $order->return->refund_transfer }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{!! $order->return->status_label !!}</td>
                                    </tr>
                                    @if ($order->return->status == 0)
                                        <tr>
                                            <td colspan="2" class="text-right">
                                                <form action="" onsubmit="return confirm('Kamu Yakin?');" method="post">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <button type="submit"
                                                        formaction="{{ route('orders.approve-return', 2) }}"
                                                        class="btn btn-danger rounded-sm">Reject</button>
                                                    <button type="submit"
                                                        formaction="{{ route('orders.approve-return', 1) }}"
                                                        class="btn btn-success rounded-sm">Approve</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                </table>

                            </div>
                            <div class="col-md-6">
                                <h4>Image</h4>
                                <a href="{{ asset('storage/' . $order->return->photo) }}" target="_blank"
                                    rel="noopener noreferrer">
                                    <img src="{{ asset('storage/' . $order->return->photo) }}" class="img-responsive"
                                        height="250" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
