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
        </ul>

        <div class="container justify-content-center card w-50 shadow-none">
            <div class="card-body">
                <form action="{{ route('profile.user-edit') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control rounded-0" name="name"
                            value="{{ old('name') ?? auth('customer')->user()->name }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control rounded-0" name="email"
                            value="{{ auth('customer')->user()->email }}" disabled>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="phone">Phone Number</label>
                            <input type="number" class="form-control rounded-0" name="phone"
                                value="{{ old('phone') ?? auth('customer')->user()->phone }}" required>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="male" {{ auth('customer')->user()->gender == 'male' ? 'selected' : '' }}>
                                    Male
                                </option>
                                <option value="female"
                                    {{ auth('customer')->user()->gender == 'female' ? 'selected' : '' }}>
                                    Female</option>
                            </select>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="justify-content-center d-flex">
                        <button type="submit" class="btn btn-outline-default rounded-0">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
