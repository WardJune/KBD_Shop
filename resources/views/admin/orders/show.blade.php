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
                                <li class="breadcrumb-item active" aria-current="page">{{ $order->invoice }}</li>
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
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="align-self-center">
                            Order Detail
                        </h4>
                        <div class="d-md-flex justify-content-between">
                            {{-- show order status 1 (awaiting confirmation) / payment status 0 --}}
                            <form method="post">
                                @csrf
                                {{-- @method('delete') --}}
                                @if ($order->status == 1 && $order->payment->status == 0)
                                    <a href="{{ route('orders.approve-payment', $order->invoice) }}"
                                        class="btn btn-primary btn-sm">Receive Payment</a>
                                @endif
                                @if ($order->status == 3)
                                    <button class="btn btn-info btn-sm"
                                        formaction="{{ route('orders.confirm-order', $order->invoice) }}">Accept
                                        Order</button>
                                @endif
                                @if ($order->deleted_at == null)
                                    <button onclick="event.preventDefault()" class="btn btn-danger btn-sm"
                                        data-toggle="modal" data-target="#deleteModal">Delete
                                        Order</button>

                                    {{-- delete modal --}}
                                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Modal</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure about this one ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">No</button>
                                                    <button class="btn btn-danger"
                                                        formaction="{{ route('orders.destroy', $order->id) }}">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- end delete modal --}}
                                @else
                                    <button class="btn btn-success btn-sm"
                                        formaction="{{ route('orders.restore', $order->id) }}">Restore</button>
                                    <button onclick="event.preventDefault()" class="btn btn-danger btn-sm"
                                        data-toggle="modal" data-target="#deleteModal1">Delete Order Permanently</button>

                                    {{-- delete modal permanent --}}
                                    <div class="modal fade" id="deleteModal1" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Modal</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure? You won't be able to revert this!
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">No</button>
                                                    <button class="btn btn-danger"
                                                        formaction="{{ route('orders.force-destroy', $order->id) }}">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- end delete modal permanent --}}
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-md-3">
                            <div class="col-md-6">
                                <h4>Customer Details</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Name</th>
                                        <td>{{ $order->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $order->customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number</th>
                                        <td>{{ $order->customer_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle">Address</th>
                                        <td class="text-wrap"><span
                                                class="text-wrap">{{ $order->customer_address }}</span>,
                                            {{ $order->district->name }} -
                                            {{ $order->district->city->name }},
                                            {{ $order->district->province->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Status</th>
                                        <td class="d-flex justify-content-md-between">{!! $order->status_label !!}
                                            @if ($order->return_count > 0)
                                                <a href="{{ route('orders.return', $order->invoice) }}"
                                                    class="badge badge-info">Request Return</a>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- order status > 1 show --}}
                                    @if ($order->status > 1)
                                        <tr>
                                            <th class="align-middle">Tracking Number</th>
                                            <td>
                                                @if ($order->status == 2)
                                                    <form action="{{ route('orders.shipping') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="tracking_number"
                                                                required placeholder="Tracking Number">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-light"
                                                                    type="submit">Send</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @else
                                                    {{ $order->tracking_number }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Payment Details</h4>
                                @if ($order->status != 0)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="30%">Name</th>
                                            <td>{{ $order->payment->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Transfer To</th>
                                            <td>{{ $order->payment->transfer_to }}</td>
                                        </tr>
                                        <tr>
                                            <th>Transfer Date</th>
                                            <td>{{ $order->payment->transfer_date }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Proof</th>
                                            <td><a target="_blank"
                                                    href="{{ asset('storage/' . $order->payment->proof) }}">show</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>{!! $order->payment->status_label !!}</td>
                                        </tr>
                                    </table>
                                @else
                                    <h5 class="text-center align-middle">Have Not Confirmed Payment</h5>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Order Details</h4>
                                <table class="table table-borderd">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Weight</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    @foreach ($order->details as $detail)
                                        <tr>
                                            <td>{{ $detail->product->name }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>Rp {{ number_format($detail->price) }}</td>
                                            <td>{{ $detail->weight }} gr</td>
                                            <td>Rp {{ number_format($detail->total) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="4">Shipping : <span
                                                class="font-weight-bold">{{ $order->shipping }}</span></td>
                                        <td>Rp {{ number_format($order->cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Total</th>
                                        <th>Rp {{ number_format($order->total) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
