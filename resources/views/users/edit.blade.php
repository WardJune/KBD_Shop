@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    <div class="header bg-primary pb-7">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Users</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Users Management</a>
                                </li>
                                <li class="breadcrumb-item active">{{ $customer->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-md-4">Edit User</h3>
                        <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-md-3">
                                        <input type="text" name="name" class="form-control" placeholder="Full Name"
                                            value="{{ old('name', $customer->name) }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-md-3">
                                        <input type="number" name="phone" class="form-control" placeholder="Phone Number"
                                            value="{{ old('phone', $customer->phone) }}">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-md-3">
                                        <input readonly type="email" name="email" class="form-control" placeholder="Email"
                                            value="{{ old('email', $customer->email) }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-md-3">
                                        <select class="form-control" name="gender">
                                            <option disabled selected>Gender</option>
                                            <option {{ $customer->gender == 'male' ? 'selected' : '' }} value="male">Male
                                            </option>
                                            <option {{ $customer->gender == 'female' ? 'selected' : '' }} value="female">
                                                Female</option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-md-3">
                                        <select class="form-control" name="email_verified_at">
                                            @if ($customer->email_verified_at)
                                                <option selected value="{{ $customer->email_verified_at }}">
                                                    Verified at
                                                    {{ $customer->email_verified_at->format('d-m-Y h:i:s') }}</option>
                                            @else
                                                <option selected disabled>Unverified</option>
                                                <option value="now">Verified Now</option>
                                            @endif
                                        </select>
                                        @error('email_verified_at')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-md-3">
                                        <input type="password" name="password" class="form-control" placeholder="Password"
                                            disabled value="{{ old('password', $customer->password) }}">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection
