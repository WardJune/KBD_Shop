@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-warning">{{ session('status') }}</div>
        @endif
        <h1 class="text-center mt-md-5">User Profile</h1>
        <hr class="border-warning">
        <ul class="nav justify-content-center border-0 mb-4">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.user') }}" aria-controls="user" aria-selected="true">User
                    Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.password-edit') }}">Change
                    Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border-bottom border-warning active " href="">Address Book</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#order">Order History</a>
            </li>
        </ul>
    </div>

    <div class="container justify-content-center card w-75 shadow-none">
        @if ($addresses)
            <div class="card-body text-center mb-5">
                <a href="{{ route('profile.address-form') }}" class="btn btn-outline-default rounded-0 ">Add
                    New Address
                    Book</a>
                <div class="row mt-4">
                    @foreach ($addresses as $address)
                        <div class="col-md-6">
                            <div class="card shadow-none bg-secondary rounded-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 text-default">
                                            <h4>{{ $address->title }}</h4>
                                            <span>{{ $address->customer_address }}</span>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center">
                                            <a href="{{ route('profile.address-edit', $address->id) }}"
                                                class="btn btn-sm btn-default rounded-0">Edit</a>
                                            <form action="{{ route('profile.address-destroy', $address->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-default rounded-0">X</button>
                                            </form>
                                        </div>
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
                <a href="{{ route('profile.address-form') }}" class="btn btn-outline-default rounded-0">Add New Address
                    Book</a>
            </div>
        @endif
    </div>
@endsection
