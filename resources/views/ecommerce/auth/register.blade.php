@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card w-75 shadow-none bg-secondary rounded-0">
            <div class="card-body p-6">
                @if (session('status'))
                    <div class="alert alert-info">{{ session('status') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <h2 class="text-center">CREATE YOUR ACCOUNT</h2>
                <hr class="border-warning">
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control rounded-0" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="phone">Phone Number</label>
                            <input type="number" class="form-control rounded-0" name="phone" value="{{ old('phone') }}"
                                required>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control rounded-0" name="email" value="{{ old('email') }}"
                            required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control rounded-0" name="password" id="password" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control rounded-0" name="password_confirmation"
                                id="password_confirmation" required>
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="justify-content-center d-flex">
                        <button type="submit" class="btn btn-outline-default rounded-0">Create
                            Account</button>
                    </div>
                </form>
                <h4 class="text-muted text-center mt-3">Already have an Account ? Log In <a class="text-warning"
                        href="{{ route('login') }}">Here</a>
                </h4>
            </div>
        </div>
    </div>
    </div>
@endsection
