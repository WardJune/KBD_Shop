@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.js@2.6.0/dist/instantsearch.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/instantsearch.js@2.6.0/dist/instantsearch-theme-algolia.min.css">
@endpush

@section('content')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">Search Result </h1>
            <div id="stats-container"></div>
        </div>
    </div>
    {{-- breadcrumb --}}
    <div class="container mb-md-4">


        <div id="hits"></div>

        <div id="pagination"></div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@2.6.0"></script>
    <script src="{{ asset('assets/js/algolia-search.js') }}"></script>
@endpush
