@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    {{-- header --}}
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Inventory</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                            </ol>
                        </nav>
                    </div>
                    {{-- <div class="col-lg-6 col-5 text-right">
                        <form action="{{ route('product.search') }}" method="get" class="d-inline-block">
                            <div class="form-group">
                                <div class="input-group input-group-alternative mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input name="keyword" type="search" class="form-control form-control-alternative"
                                        placeholder="Search">
                                </div>
                            </div>
                        </form>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-md">
                {{-- alert success --}}
                @if (session('success'))
                    <div class="alert alert-secondary alert-dismissable fade show text-success" role="alert">
                        <span class="alert-inner--icon"><i class="ni ni-check-bold"></i></span>
                        <span class="alert-inner--text"><strong>Success!</strong> {{ session('success') }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        </button>
                        <span aria-hidden="true" class="text-dark">&times;</span>

                @endif

                {{-- session error --}}
                @if (session('error'))
            </div>
            <div class="alert alert-secondary text-danger" role="alert"><strong>{{ session('error') }}</strong>
                @endif

                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h4>Inventory</h4>
                            <div class="right">
                                <a href="{{ route('inventory.adjust') }}"
                                    class="btn btn-sm btn-success d-lg-inline-block">Adjustment Product Quantity</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Merk</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">History</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td><span class="badge badge-warning">{{ $product->category->name }}</span></td>
                                        <td>
                                            @if ($product->merk_id != null)
                                                <span class="badge badge-secondary">{{ $product->merk->name }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->stock->qty }}</td>
                                        <td>
                                            <a href="{{ route('inventory.history-sales', $product->slug) }}"
                                                class="btn btn-sm btn-primary">Sales</a>
                                            <a href="{{ route('inventory.history-adj', $product->slug) }}"
                                                class="btn btn-sm btn-light">Adjustment</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
