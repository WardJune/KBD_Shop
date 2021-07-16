@extends('layouts.ecommerce', ['class'=> 'bg-neutral'])

@section('content')

    <div class="container">
        <div class="row my-md-5">
            <div class="col-md-2">
                @include('layouts.ecommerce.nav.sidebar-profile')
            </div>
            <div class="col-md-10">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-none bg-secondary rounded-0">
                            <div class="card-body my-md--2 d-md-flex justify-content-between">
                                <a class="d-md-block" href="{{ route('order.dashboard') }}">
                                    &#60; Back</a>
                                <span class="d-block">Invoice : <span class="font-weight-bold">
                                        {{ $order->invoice }} </span> |
                                    {!! $order->status_label !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm bg-secondary rounded-0">
                            <div class="card-header bg-transparent">
                                <span class="h4">Customer Data</span>
                            </div>
                            <div class="card-body">
                                <table>
                                    <tr>
                                        <td width="35%">Full Name</td>
                                        <td width="6%">:</td>
                                        <th>{{ $order->customer_name }}</th>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <th>{{ $order->customer->email }}</th>
                                    </tr>
                                    <tr>
                                        <td>Phone Number</td>
                                        <td>:</td>
                                        <th>{{ $order->customer_phone }}</th>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>:</td>
                                        <th><span class="text-wrap">{{ $order->customer_address }}</span>,
                                            {{ $order->district->name }}
                                            {{ $order->district->city->name }},
                                            {{ $order->district->city->province->name }}</th>
                                    </tr>
                                    @if ($order->return_count == 1)
                                        <tr>
                                            <td>Request Return</td>
                                            <td>:</td>
                                            <th>{!! $order->return->status_label !!}</th>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-secondary rounded-0 shadow-sm">
                            <div class="card-header bg-transparent">
                                <span class="h4">
                                    Payment
                                    @if ($order->status == 0)
                                        <a href="{{ route('payment.form', $order->invoice) }}"
                                            class="btn btn-warning rounded-0 btn-sm float-right">Confirm</a>
                                    @endif
                                </span>
                            </div>
                            <div class="card-body">
                                @if ($order->payment)
                                    <table>
                                        <tr>
                                            <td width="30%">Name</td>
                                            <td width="5%"> :</td>
                                            <th>{{ $order->payment->name }}</th>
                                        </tr>
                                        <tr>
                                            <td>Transfer Date</td>
                                            <td> :</td>
                                            <th>{{ $order->payment->transfer_date }}</th>
                                        </tr>
                                        <tr>
                                            <td>Amount</td>
                                            <td> :</td>
                                            <th>Rp {{ number_format($order->payment->amount) }}</th>
                                        </tr>
                                        <tr>
                                            <td>Transfer To</td>
                                            <td> :</td>
                                            <th>{{ $order->payment->transfer_to }}</th>
                                        </tr>
                                        <tr>
                                            <td>Transfer Proof</td>
                                            <td> :</td>
                                            <th>
                                                <a href="{{ asset('storage/' . $order->payment->proof) }}"
                                                    target="_blank">Detail</a>
                                            </th>
                                        </tr>
                                    </table>
                                @else
                                    <h4 class="text-center">No Payment Data</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="card bg-secondary rounded-0 shadow-sm">
                            <div class="card-header bg-transparent d-flex justify-content-between">
                                <span class="h4 d-block">Details</span>
                                <div class="">
                                    @if ($order->status >= 3)
                                        @if ($order->status == 4)
                                            <a href="{{ route('order.show-pdf', $order->invoice) }}" target="_blank"
                                                class="btn btn-sm btn-warning rounded-0">Print invoice</a>
                                        @else
                                            <form action="{{ route('order.accept', $order->id) }}" class="inline"
                                                onsubmit="return confirm('r u sure ?')" method="POST">
                                                @csrf
                                                @method('patch')

                                                @if ($order->status == 3 && $order->return_count == 0)
                                                    <a href="{{ route('order.return-form', $order->invoice) }}"
                                                        class="btn btn-warning btn-sm rounded-0">Return</a>
                                                @endif

                                                <button type="submit" class="btn btn-sm btn-warning rounded-0">Receive
                                                    Order</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Weight</th>
                                                <th>SubTotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->details as $row)
                                                <tr>
                                                    <td><span class="text-wrap">{{ $row->product->name }}</span></td>
                                                    <td>{{ number_format($row->price) }}</td>
                                                    <td>{{ $row->qty }} Item</td>
                                                    <td>{{ $row->weight }} gr</td>
                                                    <td>IDR {{ number_format($row->total) }}</td>
                                                </tr>
                                            @endforeach
                                            {{-- <tr>
                                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                                </tr> --}}
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="4">Shipping : {{ $order->shipping }}</td>
                                                <td>IDR {{ number_format($order->cost) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-right" colspan="4">Total</th>
                                                <th>IDR {{ number_format($order->total) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
