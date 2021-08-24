@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    <!-- Header -->
    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total User</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $data['users'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-red text-white rounded-circle shadow">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Total All of User</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Order</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $data['order'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Total All of Order</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Sales</h5>
                                        <span class="h2 font-weight-bold mb-0">IDR
                                            {{ number_format($data['sales']) }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    {!! $data['growth'] !!}
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Products</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $data['products'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-keyboard"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Total All of Product</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Main Content --}}
    {{-- Main Content --}}
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-md-8">
                <div class="card bg-neutral">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-muted text-uppercase ls-1 mb-1">Performance</h6>
                                <h5 class="h3 mb-0">Sales value</h5>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0">
                                        <a href="#" data-toggle="tab" class="nav-link py-2 px-3 active ">
                                            <span class="d-md-block " id="monthly">Monthly</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mr-2 mr-md-0">
                                        <a href="#" data-toggle="tab" class="nav-link py-2 px-3 ">
                                            <span class="d-md-block " id="yearly">Yearly</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div id="sales-chart" class="chart-canvas"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-neutral">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                                <h5 class="h3 mb-0">Total User</h5>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0">
                                        <a href="{{ route('customer.index') }}" class="nav-link py-2 px-3 active bg-red">
                                            <span class="d-md-block ">See All</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div id="user-chart" class="chart-canvas"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                <h5 class="h3 mb-0">Top Selling Product</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="product-chart" class="chart-canvas mb-2"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="row align-item-center">
                                    <div class="col">
                                        <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                                        <h3 class="mb-0">Recent Orders</h3>
                                    </div>
                                    <div class="col">
                                        <ul class="nav nav-pills justify-content-end">
                                            <li class="nav-item mr-2 mr-md-0">
                                                <a href="{{ route('orders.index') }}"
                                                    class="nav-link py-2 px-3 bg-warning active">
                                                    <span class="d-md-block">See All</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light ">
                                        <tr>
                                            <th scope="col">Invoice</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Subtotal + Shippment</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @forelse ($data['orders'] as $order)
                                            <tr>
                                                <td>
                                                    <a
                                                        href="{{ route('orders.show', $order->invoice) }}">{{ $order->invoice }}</a>
                                                </td>
                                                <td>
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </td>
                                                <td>
                                                    <span class="status">{!! $order->status_label !!}</span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold"> IDR
                                                        {{ number_format($order->total) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No Data</td>
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
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
        Highcharts.setOptions({
            lang: {
                thousandsSep: '.'
            },
            credits: {
                enabled: false
            },
            title: false,
            legend: {
                enabled: false
            },
        });

        function getChart(values = {!! json_encode($data['salesChart']->values()) !!}, keys = {!! json_encode($data['salesChart']->keys()) !!}) {
            Highcharts.chart('sales-chart', {
                chart: {
                    type: 'spline'
                },
                colors: ['#2DCEC2'],
                xAxis: {
                    categories: values,
                },
                yAxis: {
                    min: 0,
                    title: false,
                    gridLineDashStyle: 'longdash',
                    gridLineColor: '#A5B1BF'
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:,.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    spline: {
                        marker: {
                            radius: 5,
                            lineWidth: 1,
                            lineColor: '#2DCEC2'
                        }
                    }
                },
                series: [{
                    name: 'Sales',
                    data: keys,
                    lineColor: '#2DCE97',
                    lineWidth: 4

                }]
            })
        }

        getChart();

        $('#monthly').on('click', function() {
            getChart()
        })

        $('#yearly').on('click', function() {
            getChart({!! json_encode($data['salesChartYearly']->values()) !!}, {!! json_encode($data['salesChartYearly']->keys()) !!})
        });


        Highcharts.chart('user-chart', {
            chart: {
                type: 'column'
            },
            colors: ['#F5365C'],
            title: false,
            xAxis: {
                categories: {!! json_encode($data['customersChart']->keys()) !!},
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: false,
                gridLineDashStyle: 'longdash',
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b> {point.y:,.0f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'User',
                data: {!! json_encode($data['customersChart']->values()) !!}

            }]
        });

        Highcharts.chart('product-chart', {
            chart: {
                type: 'bar'
            },
            colors: ['#11CDEF'],
            xAxis: {
                categories: {!! json_encode($data['product']->keys()) !!},
                title: {
                    text: null
                },
                labels: {
                    overflow: 'justify',
                    allowOverlap: true,
                    formatter: function() {
                        return this.value.substring(0, 10) + '...';
                    },
                },
            },
            yAxis: {
                min: 0,
                title: false,
            },
            tooltip: {
                valueSuffix: ' items'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Product',
                data: {!! json_encode($data['product']->values(), JSON_NUMERIC_CHECK) !!},
                lineColor: "#666666"
            }, ]
        });
    </script>
@endpush
