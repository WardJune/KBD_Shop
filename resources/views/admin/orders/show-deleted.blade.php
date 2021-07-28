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
                                <li class="breadcrumb-item " aria-current="page"><a
                                        href="{{ route('orders.index') }}">Orders</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Deleted Orders</li>
                            </ol>
                        </nav>
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
                            <h4 class="align-self-center">Deleted Order Lists</h4>
                            <!-- FORM UNTUK FILTER DAN PENCARIAN -->
                            <form action="{{ route('orders.deleted') }}" method="get">
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
                                            <label><strong>Address:</strong>
                                                {{ \Str::limit($order->customer_address, 10) }} ,
                                                {{ $order->district->name }} -
                                                {{ $order->district->city->name }},
                                                {{ $order->district->city->province->name }}</label>
                                        </td>
                                        <td class="align-middle">Rp {{ number_format($order->subtotal) }}</td>
                                        <td class="align-middle">{{ $order->created_at->format('d-m-Y') }}</td>
                                        <td class="align-middle">
                                            {!! $order->status_label !!}
                                            @if ($order->return_count > 0)
                                                <a href="{{ route('orders.return', $order->invoice) }}"
                                                    class="d-block">Request Return</a>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('orders.force-destroy', $order->id) }}" method="post">
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
