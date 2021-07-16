@extends('layouts.ecommerce' , ['class' => 'bg-neutral'])

@section('content')
    <div class="container">
        <div class="row my-md-5">
            <div class="col-md-2">
                @include('layouts.ecommerce.nav.sidebar-profile')
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card rounded-0 shadow-none bg-secondary">
                            <div class="card-header bg-transparent">
                                <span class="h4">Confirmation Payment</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('payment.save') }}" enctype="multipart/form-data" method="post">
                                    @csrf

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="form-group row">
                                        <label class="col-md-3" for="">Invoice ID</label>
                                        <input type="text" name="invoice"
                                            class="col-md-6 form-control rounded-0 form-control-sm"
                                            value="{{ $order->invoice }}" readonly>
                                        @error('invoice')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3" for="">Name</label>
                                        <input type="text" name="name"
                                            class="col-md-6 form-control rounded-0 form-control-sm" required>
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3" for="">Transfer To</label>
                                        <select name="transfer_to" class="col-md-6 form-control rounded-0 form-control-sm"
                                            required>
                                            <option value="">Choose</option>
                                            <option value="BCA - 1234567">BCA: 1234567 a.n John Doe</option>
                                            <option value="Mandiri - 2345678">Mandiri: 2345678 a.n John Doe
                                            </option>
                                            <option value="BRI - 9876543">BNI: 9876543 a.n John Doe</option>
                                            <option value="BNI - 6789456">BRI: 6789456 a.n John Doe</option>
                                        </select>
                                        @error('transfer_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3" for="">Amount</label>
                                        <input type="number" name="amount" value="{{ $order->subtotal + $order->cost }}"
                                            class="col-md-6 form-control rounded-0 form-control-sm" readonly>
                                        @error('amount')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3" for="">Transfer Date</label>
                                        <input type="date" name="transfer_date" id="transfer_date"
                                            class="col-md-6 form-control rounded-0 form-control-sm" required>
                                        @error('transfer_date')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3" for="">Transfer Proof</label>
                                        <input type="file" name="proof" class="col-md-6 " required>
                                        @error('proof')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group row d-flex">
                                        <button class="btn btn-warning rounded-0 offset-md-3">Confirm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
