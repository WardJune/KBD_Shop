@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container mt-4 mb-6">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="alert-text">{{ session('error') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-7">
                <h3>SHIPPING INFORMATION</h3>
                <hr class="my-3">
                <form class="row" action="" method="post">
                    @csrf
                    @if ($addresses->count() > 0)
                        <div class="col-md-12 form-group">
                            <label for="address_book">Address Book</label>
                            <select class="form-control" name="address_book" id="address_book">
                                <option selected value="">New Address</option>
                                @foreach ($addresses as $address)
                                    <option selected value="{{ $address->id }}">{{ $address->title }}</option>
                                @endforeach
                            </select>
                            @error('address_book')
                                <small class="text-danger mb-2">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                    <div class="col-md-12 form-group">
                        <label for="customer_name">Full Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                            value="{{ old('customer_name') }}">
                        @error('customer_name')
                            <small class="text-danger mb-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 form-group">
                        <label for="customer_phone">Phone Number</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="form-control"
                            value="{{ old('customer_phone') }}">
                        @error('customer_phone')
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
                        @foreach ($carts as $cart)

                            <div class="row mb-3">
                                <div class="col-md-7 ">
                                    <div class="media">
                                        <img class="mr-3 rounded-sm" width="64" height="64"
                                            src="{{ asset('/storage/' . $cart->product->image) }}" alt="">
                                        <div class="media-body align-self-center">
                                            <span class="text-sm">
                                                {{ \Str::limit($cart->product->name, 14) }}</span>
                                            <span class="text-sm text-muted">Qty : {{ $cart->qty }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 align-self-center text-right">
                                    IDR {{ number_format($cart->product->price) }}
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

                        <button type="submit" formaction="{{ route('cart.process-checkout') }}"
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
        $('#address_book').on('change', function() {
            // request api ke  /api/city && send data province_id
            $.ajax({
                url: "{{ url('/api/address') }}",
                type: "GET",
                data: {
                    address_book: $(this).val()
                },
                success: function(html) {
                    $('#customer_name').val(html.data.customer_name);
                    $('#customer_phone').val(html.data.customer_phone);
                    $('#customer_address').val(html.data.customer_address);

                    // province
                    $('#province_id').append(
                        `<option selected value="${html.data.district.province.id}"> ${html.data.district.province.name}</option>`
                    )
                    // city
                    $('#city_id').empty();
                    $.each(html.city, function(key, item) {
                        $('#city_id').append(`<option ${item.id == html.data.district.city.id ?
                            'selected' : ''}  value="${item.id}"> ${item.name}
                            </option>`)
                    })
                    // district
                    $('#district_id').empty()
                    $.each(html.district, function(key, item) {
                        $('#district_id').append(
                            `<option ${item.id == html.data.district_id ? 'selected' : ''} value="${item.id}"> ${item.name}</option>`
                        )
                    })
                }
            })
        })
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
