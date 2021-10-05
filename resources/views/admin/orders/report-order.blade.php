@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    {{-- header --}}
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">{{ $title }}</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
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
                            <h3 class="align-self-center">{{ $title }}</h3>
                            <form action="{{ route('orders.report') }}" method="get">
                                <div class="input-group mb-3 ">
                                    <input type="text" class="form-control" name="date" id="created_at">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-light" type="submit"
                                            id="button-addon2">Filter</button>
                                    </div>
                                    <a target="_blank" class="btn btn-info ml-md-2" id="exportpdf">Export PDF</a>
                                </div>
                            </form>
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
                                        <td class="align-middle">Rp {{ number_format($order->total) }}</td>
                                        <td class="align-middle">{{ $order->created_at->format('d-m-Y') }}</td>
                                        <td class="align-middle">
                                            {!! $order->status_label !!}
                                            @if ($order->return_count > 0)
                                                <a href="{{ route('orders.return', $order->invoice) }}"
                                                    class="d-block">Request Return</a>
                                            @endif
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
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(document).ready(function() {
            let start = moment().startOf('month')
            let end = moment().endOf('month')

            // change href export pdf button
            $('#exportpdf').attr('href',
                `/admin/orders/report/pdf/${start.format('YYYY-MM-DD')}+${end.format('YYYY-MM-DD')}/{{ \Str::slug($title) }}`
            )

            // init daterangepicker
            $('#created_at').daterangepicker({
                startDate: start,
                endDate: end
            }, function(first, last) {
                // if user change the value , change the href export pdf button
                $('#exportpdf').attr('href',
                    `/admin/orders/report/pdf/${first.format('YYYY-MM-DD')}+${last.format('YYYY-MM-DD')}/{{ \Str::slug($title) }}`
                )
            })
        })
    </script>
@endpush
