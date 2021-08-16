@extends('layouts.app', ['class' => 'bg-secondary'])

@push('css')
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/inventory.css') }}">
@endpush
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Adjustment</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Adjustment</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-md--6">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="aa-input-container" id="aa-input-container">
                                    <input type="search" id="aa-search-input" class="aa-input-search"
                                        placeholder="Search product..." name="search" autocomplete="off"
                                        onclick="clearThis(this)" />
                                </div>
                            </div>

                            {{-- <div class="col-md-4 ">
                                <input type="text" name="search" id="search" class="form-control"
                                    placeholder="Type to Select Product..." onclick="clearThis(this)">

                            </div> --}}
                            <div class="col-md-8 d-md-flex align-items-center justify-content-end">
                                <span class="text-muted">Adjust Quantity of Products</span>
                            </div>
                        </div>
                    </div>
                    <form method="post">
                        @csrf
                        <div class="table-responsive">
                            <table class="table align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th colspan="2" scope="col">qty</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer foot d-none justify-content-between">
                            <span id="totalProduct" class="d-block">..</span>
                            <button class="btn btn-primary" formaction="{{ route('inventory.update') }}">Adjust
                                Quantity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script src="{{ asset('assets/js/algolia-inventory.js') }}"></script>
@endpush
