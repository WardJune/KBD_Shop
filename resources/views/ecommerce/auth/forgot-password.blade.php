@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card w-50 shadow-none bg-secondary rounded-0">
            <div class="card-body p-6">
                @if (session('message'))
                    <div class="alert alert-info">{{ session('message') }}</div>
                @endif

                <h2 class="text-center">FORGOT PASSWORD</h2>
                <hr class="border-warning">

                <p class="text-muted text-md-center">If you can't remember your password, please enter your email address
                    and click the
                    submit button. We will send you a link to reset your password.</p>
                <form action="{{ route('password.email') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control rounded-0" name="email" value="{{ old('email') }}"
                            required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-block btn-outline-default rounded-0">SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
@endsection
