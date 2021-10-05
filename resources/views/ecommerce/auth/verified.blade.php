@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="card shadow-none w-50 m-7">
            <div class="card-body text-center">
                <h2 class="text-muted">Verify Your Email Address</h2>
                <span class="text-muted">Before proceeding, please check your email for a verification link. If you didn't
                    receive
                    the email, <a href=""
                        onclick=" event.preventDefault(); document.getElementById('requestForm').submit();">
                        click here to request another</a></span>
            </div>
        </div>
    </div>
@endsection
