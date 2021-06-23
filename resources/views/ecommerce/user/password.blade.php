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
                <a class="nav-link " href="{{ route('profile.user') }}" aria-controls="user" aria-selected="true">User
                    Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border-bottom border-warning active " href="route('profile.password-edit')">Change
                    Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('profile.address') }}">Address Book</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#order">Order History</a>
            </li>
        </ul>

        <div class="container justify-content-center card w-50 shadow-none">
            <div class="card-body">
                <form action="{{ route('profile.password-update') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="old_password">Current Password</label>
                        <input type="password" class="form-control rounded-0" name="old_password" id="old_password"
                            required>
                        @error('old_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control rounded-0" name="password" id="password" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control rounded-0" name="password_confirmation"
                            id="password_confirmation" required>
                        @error('password_confirmation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="justify-content-center d-flex">
                        <button type="submit" class="btn btn-outline-default rounded-0">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
