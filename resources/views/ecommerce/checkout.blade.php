@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    @if (session('error'))
        <p class="text-danger">{{ session('error') }}</p>
    @endif
    <div class="container mt-4 mb-6">
        <div class="row">
            <div class="col-md-7">
                <h3>SHIPPING INFORMATION</h3>
                <hr class="my-3">
                <form class="row" action="" method="post">
                    @csrf
                    <div class="col-md-12 form-group">
                        <label for="customer_name">Full Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                            value="{{ old('customer_name') }}">
                        @error('customer_name')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="customer_phone">Phone Number</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="form-control"
                            value="{{ old('customer_phone') }}">
                        @error('customer_phone')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 form-group">
                        <label for="customer_address">Address</label>
                        <input type="text" name="customer_address" id="customer_address" class="form-control"
                            value="{{ old('customer_address') }}">
                        @error('customer_address')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="province_id">Province</label>
                        <select class="form-control" name="province_id" id="province_id">
                            <option disabled selected value="">Choose Province</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('province_id')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="city_id">City</label>
                        <select class="form-control" name="city_id" id="city_id">
                            <option disabled selected value="">Choose City</option>
                        </select>
                        @error('city_id')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="district_id">District</label>
                        <select class="form-control" name="district_id" id="district_id">
                            <option disabled selected value="">Choose District</option>
                        </select>
                        @error('district_id')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

            </div>
            <div class="col-md-5">
                <div class="card shadow-none border">
                    <div class="card-body">
                        <h3 class="mb-4">ORDER REVIEW</h3>
                        @foreach ($carts as $row)

                            <div class="row mb-3">
                                <div class="col-md-7 ">
                                    <div class="media">
                                        <img class="mr-3 rounded-sm" width="64" height="64"
                                            src="{{ asset('/storage/' . $row['product_image']) }}" alt="">
                                        <div class="media-body align-self-center">
                                            <span class="text-sm">
                                                {{ \Str::limit($row['product_name'], 14) }}</span>
                                            <span class="text-sm text-muted">Qty : {{ $row['qty'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 align-self-center text-right">
                                    IDR {{ number_format($row['product_price']) }}
                                </div>
                            </div>
                        @endforeach

                        <hr>
                        <div class="row">
                            <div class="col-md-7">Subtotal</div>
                            <div class="col-md-5 text-right">IDR {{ number_format($subTotal) }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Shipping</div>
                            <div class="col-md-6 text-right">
                                <small class="small">Calculated at the next step</small>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-7"><span class="font-weight-bold">Total</span></div>
                            <div class="col-md-5 text-right">IDR {{ number_format($subTotal) }}</div>
                        </div>

                        <button type="submit" formaction="{{ route('front.checkout') }}"
                            class="btn btn-block btn-default">Proccess Order</button>
                    </div>
                </div>
            </div>
            </form>

        </div>
    </div>
@endsection

@push('js')
    <script>
        // on change province_id
        $('#province_id').on('change', function() {
            // request api ke  /api/city && send data province_id
            $.ajax({
                url: "{{ url('/api/city') }}",
                type: "GET",
                data: {
                    province_id: $(this).val()
                },
                success: function(html) {
                    // setelah success , select box dengan id city_id dan kosongkan
                    $('#city_id').empty();
                    // then append data baru hasil request ajax
                    $('#city_id').append('<option disabled selected value="">Choose City</option>')
                    $.each(html.data, function(key, item) {
                        $('#city_id').append('<option value="' + item.id + '">' + item.name +
                            '</option>')
                    })
                }
            })
        })

        $('#city_id').on('change', function() {
            $.ajax({
                url: "{{ url('/api/district') }}",
                type: "GET",
                data: {
                    city_id: $(this).val()
                },
                success: function(html) {
                    $('#district_id').empty()
                    $('#district_id').append(
                        '<option disabled selected value="">Choose District</option>')
                    $.each(html.data, function(key, item) {
                        $('#district_id').append('<option value="' + item.id + '">' + item
                            .name +
                            '</option>')
                    })
                }
            })
        })

    </script>
@endpush
