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
                        <p class="h3">Return & Refund</p>
                        <span class="text-muted">Manage your profile information to control, protect and secure your
                            account</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('order.return', $order->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-3" for="reason">Reason</label>
                                <textarea class="col-md-6 form-control form-control-sm rounded-0" id="reason" name="reason"
                                    rows="3"></textarea>
                                @error('reason')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3" for="refund_transfer">Refund Transfer</label>
                                <input type="text" class="col-md-6 form-control form-control-sm rounded-0"
                                    name="refund_transfer" value="">
                                @error('refund_transfer')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3" for="">Photo</label>
                                <input type="file" name="photo" class="col-md-6 " required>
                                @error('photo')
                                    <p class="text-danger">{{ $message }}</p>
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
