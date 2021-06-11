@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card w-50 shadow-none bg-secondary rounded-0">
            <div class="card-body p-6">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <h2 class="text-center">LOGIN TO ENTER</h2>
                <hr class="border-warning">
                <form action="{{ route('customer.post_login') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" class="form-control rounded-0" name="email" id="email">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control rounded-0" name="password" id="password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label class="custom-control-label" for="customCheck1">Keep me Logged in</label>
                    </div>

                    <button type="submit" class="btn btn-block btn-outline-default rounded-0">Log In</button>
                    <div class="text-center mt-3">
                        <a href="#">Forgot Password ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
