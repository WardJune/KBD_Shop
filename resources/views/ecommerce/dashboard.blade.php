@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('contents')
    {{-- breadcrumb --}}
    <div class="jumbotron jumbotron-fluid bg-light">
        <div class="container mt-md--4 mb-md--5">
            <h1 class="display-4">Product </h1>
            <nav class="" aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item font"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- breadcrumb --}}

    <section>
        <div class="container">
            This is Temporary Dashboard
        </div>
    </section>
@endsection
