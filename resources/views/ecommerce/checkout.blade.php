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
                <h3>SHIPPING ADDRESS</h3>
                <hr class="my-3">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        @if ($addresses->count() > 0)
                            <div class="col-md-12 form-group">
                                <label for="address_book">Address Book</label>
                                <select class="form-control form-control-sm rounded-0" name="address_book"
                                    id="address_book">
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
                            <input type="text" class="form-control form-control-sm rounded-0" id="customer_name"
                                name="customer_name" value="{{ old('customer_name') }}">
                            @error('customer_name')
                                <small class="text-danger mb-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="customer_phone">Phone Number</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                class="form-control form-control-sm rounded-0" value="{{ old('customer_phone') }}">
                            @error('customer_phone')
                                <small class="text-danger mb-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12 form-group">
                            <label for="customer_address">Address</label>
                            <input type="text" name="customer_address" id="customer_address"
                                class="form-control form-control-sm rounded-0" value="{{ old('customer_address') }}">
                            @error('customer_address')
                                <small class="text-danger mb-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="province_id">Province</label>
                            <select class="form-control form-control-sm rounded-0" name="province_id" id="province_id">
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
                            <select class="form-control form-control-sm rounded-0" name="city_id" id="city_id">
                                <option disabled selected value="">Choose City</option>
                            </select>
                            @error('city_id')
                                <small class="text-danger mb-2">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="district_id">District</label>
                            <select class="form-control form-control-sm rounded-0" name="district_id" id="district_id">
                                <option disabled selected value="">Choose District</option>
                            </select>
                            @error('district_id')
                                <small class="text-danger mb-2">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <h3>SHIPPING</h3>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Courier</label>
                            <input type="hidden" name="weight" id="weight" value="{{ $weight }}">
                            <select class="form-control form-control-sm" name="courier" id="courier" required>
                                <option value="">Choose Courier</option>
                            </select>
                        </div>
                    </div>
                    <h3>PAYMENT</h3>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="payment" checked>
                                <label class="form-check-label" for="payment">
                                    Bank Transfer
                                </label>
                            </div>
                            <ul class="list-unstyled text-muted">
                                <li>BCA - Warda Rifjunanto - 091782902</li>
                                <li>BRI - Warda Rifjunanto - 9017364816489</li>
                                <li>BNI - Warda Rifjunanto - 8163494638</li>
                                <li>MANDIRI - Warda Rifjunanto - 13123847</li>
                                <li>CIMB NIAGA - Warda Rifjunanto - 193846491</li>
                            </ul>
                        </div>
                    </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow-none border rounded-0">
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
                                            <span class="d-block text-sm text-muted">x{{ $cart->qty }}</span>
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
                            <div class="col-md-6 text-right" id="ongkir">
                                ~
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-7"><span class="font-weight-bold">Total</span></div>
                            <div class="col-md-5 text-right" id="total">IDR {{ number_format($subTotal) }}</div>
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
        $('#district_id').on('change', function() {
            //loading effect
            $('#courier').empty()
            $('#courier').append('<option value="">Loading...</option>')

            //request api
            $.ajax({
                url: "{{ url('/api/cost') }}",
                type: "POST",
                data: {
                    destination: $('#city_id').val(),
                    weight: $('#weight').val()
                },
                success: function(html) {
                    $('#courier').empty()
                    $('#courier').append('<option value="">Pilih Kurir</option>')

                    //loop data
                    $.each(html.rajaongkir.results[0].costs, function(key, item) {
                        let courier =
                            `jne - ${item.service}(Rp ${new Intl.NumberFormat().format(item.cost[0].value)})`
                        let value = `jne-${item.service}-${item.cost[0].value}`

                        $('#courier').append(`<option value="${value}">${courier}</option>`)
                    })
                }
            });
        })
        $('#courier').on('change', function() {
            let val = $(this).val().split('-')
            $('#ongkir').text(`IDR ${new Intl.NumberFormat().format(val[2])}`)

            let subtotal = '{{ $subTotal }}'
            let total = parseInt(subtotal) + parseInt(val[2])
            $('#total').text(`IDR ${new Intl.NumberFormat().format(total)}`)
        })
        // on change province_id
        $('#address_book').on('change', async function() {
            // request api ke  /api/city && send data province_id
            await $.ajax({
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
            $('#courier').empty()
            $('#courier').append('<option value="">Loading...</option>')
            await $.ajax({
                url: "{{ url('/api/cost') }}",
                type: "POST",
                data: {
                    destination: $('#city_id').val(),
                    weight: $('#weight').val()
                },
                success: function(html) {
                    $('#courier').empty()
                    $('#courier').append('<option value="">Pilih Kurir</option>')

                    $.each(html.rajaongkir.results[0].costs, function(key, item) {
                        let courier =
                            `jne - ${item.service}(Rp${new Intl.NumberFormat().format(item.cost[0].value)})`
                        let value = `jne-${item.service}-${item.cost[0].value}`

                        $('#courier').append('<option value="' + value + '">' + courier +
                            '</option>')
                    })
                }
            });
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
