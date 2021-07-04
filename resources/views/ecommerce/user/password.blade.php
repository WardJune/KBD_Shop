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
                <div class="container rounded-0 card shadow-none bg-secondary">
                    <div class="card-header bg-transparent">
                        <h3>Change Password</h3>
                        <span class="text-muted">For the security of your account, please do not share your password with
                            others.</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.password-update') }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group row">
                                <label for="old_password" class="col-md-3 ">Current
                                    Password</label>
                                <input type="password" class="form-control form-control-sm rounded-0 col-md-6"
                                    name="old_password" id="old_password" required>
                                @error('old_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-3 ">Password</label>
                                <input type="password" class="form-control form-control-sm rounded-0 col-md-6"
                                    name="password" id="password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-3 ">Confirm
                                    Password</label>
                                <input type="password" class="form-control form-control-sm rounded-0 col-md-6"
                                    name="password_confirmation" id="password_confirmation" required>
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <button type="submit" class="col-md-2 offset-md-3 btn btn-outline-default rounded-0">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
