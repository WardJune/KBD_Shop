@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-warning">{{ session('status') }}</div>
        @endif
        <div class="row my-md-5">
            <div class="col-md-2">
                @include('layouts.ecommerce.nav.sidebar-profile')
            </div>
            <div class="col-md-10">
                <div class="container card rounded-0 shadow-none bg-secondary">
                    <div class="card-header bg-transparent justify-content-between align-items-center d-flex">
                        <h4>Address Book</h4>
                        <a href="{{ route('profile.address-form') }}" class="btn btn-outline-default rounded-0 ">Add
                            New Address</a>
                    </div>
                    @if ($addresses->count() > 0)
                        <div class="card-body mb-5">
                            <div class="row mt-4">
                                @foreach ($addresses as $address)
                                    <div class="card col-md-12 shadow-none rounded-sm">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8 offset-md-1 row">
                                                    <div class="col-md-4 text-muted">
                                                        <span>Address Title</span>
                                                        <span class="d-block">Full Name</span>
                                                        <span>Telp</span>
                                                        <span class="d-md-block">Address</span>
                                                    </div>
                                                    <div class="col-md-8 text-default">
                                                        <span class="font-weight-bold">{{ $address->title }}</span>
                                                        <span
                                                            class="d-block font-weight-bold">{{ $address->customer_name }}</span>
                                                        <span>{{ $address->customer_phone }}</span>
                                                        <span class="d-md-block">{{ $address->customer_address }}</span>
                                                        <span
                                                            class="d-block">{{ $address->district->city->name . '-' . $address->district->name }}</span>
                                                        <span>{{ $address->district->province->name }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-center">
                                                    <a href="{{ route('profile.address-edit', $address->id) }}"
                                                        class="btn btn-sm btn-default rounded-0">Edit</a>
                                                    <form action="{{ route('profile.address-destroy', $address->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-default rounded-0">X</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="card-body text-center my-5">
                            <p class="text-muted">Your address book is empty. Click Add New Address to add contacts.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
