@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card w-50 shadow-none bg-secondary rounded-0">
            <div class="card-body p-6">
                <h2 class="text-center">RESET PASSWORD</h2>
                <hr class="border-warning">
                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control rounded-0" name="email" value="{{ old('email') }}"
                            required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
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

                    <button type="submit" class="btn btn-block btn-outline-default rounded-0">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
