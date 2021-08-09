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
                                <li class="breadcrumb-item"><a href=""><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Orders</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="{{ route('orders.deleted') }}" class="btn btn-sm btn-success">Deleted Order</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">New</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $new }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-purple text-white rounded-circle shadow">
                                            <i class="fab fa-first-order"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">New Order Awaiting Payment</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Confirm</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $confirm }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-red text-white rounded-circle shadow">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Awating Confirmation</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Process</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $process }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Proccesing Order</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Sent</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $sent }}
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Sending Order</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Done</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $done }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Orders Done</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="align-self-center">Order Lists</h4>
                            <!-- FORM UNTUK FILTER DAN PENCARIAN -->
                            <form action="{{ route('orders.index') }}" method="get">
                                <div class="d-flex justify-content-between">
                                    <select name="status" class="form-control mr-2">
                                        <option value="">Filter Status</option>
                                        <option value="0">New (awaiting payment)</option>
                                        <option value="1">Confirm</option>
                                        <option value="2">Process</option>
                                        <option value="3">Sent</option>
                                        <option value="4">Done</option>
                                    </select>
                                    <div class="input-group mb-3 ">
                                        <input type="text" class="form-control" name="keyword" placeholder="Search.."
                                            value="{{ request()->keyword }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light" type="submit"
                                                id="button-addon2">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- FORM UNTUK FILTER DAN PENCARIAN -->
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif


                    <!-- TABLE UNTUK MENAMPILKAN DATA ORDER -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="align-middle"><strong>{{ $order->invoice }}</strong></td>
                                        <td class="align-middle">
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <span class="d-block"><strong>Telp:</strong>
                                                {{ $order->customer_phone }}</span>
                                            <label class="text-wrap"><strong>Address:</strong>
                                                {{ \Str::limit($order->customer_address, 10) }} ,
                                                {{ $order->district->name }} -
                                                {{ $order->district->city->name }},
                                                {{ $order->district->city->province->name }}</label>
                                        </td>
                                        <td class="align-middle">Rp {{ number_format($order->subtotal) }}</td>
                                        <td class="align-middle">{{ $order->created_at->format('d-m-Y h:i:s') }}</td>
                                        <td class="align-middle">
                                            {!! $order->status_label !!}
                                            @if ($order->return_count > 0)
                                                <a href="{{ route('orders.return', $order->invoice) }}"
                                                    class="d-block">Request Return</a>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('orders.destroy', $order->id) }}" method="post">
                                                @csrf
                                                {{-- @method('delete') --}}
                                                <a href="{{ route('orders.show', $order->invoice) }}"
                                                    class="btn btn-info btn-sm"><span class="fas fa-eye"></span></a>
                                                <button class="btn btn-danger btn-sm"><span
                                                        class="fas fa-trash-alt"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {!! $orders->links() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
