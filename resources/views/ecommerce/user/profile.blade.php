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
                <div class="container card shadow-none rounded-0 bg-secondary">
                    <div class="card-header bg-transparent">
                        <p class="h3">User Information</p>
                        <span class="text-muted">Manage your profile information to control, protect and secure your
                            account</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.user-edit') }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group row">
                                <label class="col-md-3" for="name">Name</label>
                                <input type="text" class="col-md-6 form-control form-control-sm rounded-0" name="name"
                                    value="{{ old('name') ?? auth('customer')->user()->name }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3" for="email">Email Address</label>
                                <input type="email" class="col-md-6 form-control form-control-sm rounded-0" name="email"
                                    value="{{ auth('customer')->user()->email }}" disabled>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3" for="phone">Phone Number</label>
                                <input type="number" class="col-md-6 form-control form-control-sm rounded-0" name="phone"
                                    value="{{ old('phone') ?? auth('customer')->user()->phone }}" required>
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3" for="gender">Gender</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="male" name="gender" class="custom-control-input" value="male"
                                        {{ auth('customer')->user()->gender == 'male' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="male">Male</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="female" name="gender" class="custom-control-input"
                                        value="female"
                                        {{ auth('customer')->user()->gender == 'female' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="female">Female</label>
                                </div>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row d-flex">
                                <button type="submit" class="col-md-3 offset-md-3 btn btn-outline-default rounded-0">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


{{-- simpan --}}
{{-- <h1 class="text-center mt-md-5">User Profile</h1>
        <hr class="border-warning">
        <ul class="nav justify-content-center border-0 mb-4">
            <li class="nav-item">
                <a class="nav-link border-bottom border-warning active " href="#user" aria-controls="user"
                    aria-selected="true">User
                    Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.password-edit') }}">Change
                    Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.address') }}">Address Book</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#order">Order History</a>
            </li>
        </ul> --}}
