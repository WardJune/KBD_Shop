@extends('layouts.ecommerce', ['class' => 'bg-neutral'])

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-warning">{{ session('status') }}</div>
        @endif
        <h1 class="text-center mt-md-5">User Profile</h1>
        <hr class="border-warning">
        <ul class="nav justify-content-center border-0 mb-4">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.user') }}" aria-controls="user" aria-selected="true">User
                    Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.password-edit') }}">Change
                    Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border-bottom border-warning active " href="{{ route('profile.address') }}">Address
                    Book</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="#order">Order History</a>
            </li>
        </ul>
    </div>

    <div class="container justify-content-center card w-50 shadow-none">
        <div class="card-body">
            <form class="row" action="{{ route('profile.address-update', $address->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="col-md-12 form-group">
                    <label for="title">Address Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ old('title') ?? $address->title }}" placeholder="Example : Home/Office">
                    @error('title')
                        <small class="text-danger mb-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12 form-group">
                    <label for="customer_name">Full Name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                        value="{{ old('customer_name') ?? $address->customer_name }}">
                    @error('customer_name')
                        <small class="text-danger mb-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12 form-group">
                    <label for="customer_phone">Phone Number</label>
                    <input type="number" name="customer_phone" id="customer_phone" class="form-control"
                        value="{{ old('customer_phone') ?? $address->customer_phone }}">
                    @error('customer_phone')
                        <small class="text-danger mb-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12 form-group">
                    <label for="customer_address">Address</label>
                    <input type="text" name="customer_address" id="customer_address" class="form-control"
                        value="{{ old('customer_address') ?? $address->customer_address }}">
                    @error('customer_address')
                        <small class="text-danger mb-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 form-group">
                    <label for="province_id">Province</label>
                    <select class="form-control" name="province_id" id="province_id">
                        <option disabled selected value="">Choose Province</option>
                        @foreach ($provinces as $province)
                            <option {{ $province->id == $address->district->province->id ? 'selected' : '' }}
                                value="{{ $province->id }}">
                                {{ $province->name }}
                            </option>
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
                        @foreach ($cities as $city)
                            <option {{ $city->id == $address->district->city->id ? 'selected' : '' }}
                                value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <small class="text-danger mb-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 form-group">
                    <label for="district_id">District</label>
                    <select class="form-control" name="district_id" id="district_id">
                        <option disabled selected value="">Choose District</option>
                        @foreach ($districts as $district)
                            <option {{ $district->id == $address->district_id ? 'selected' : '' }}
                                value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                    @error('district_id')
                        <small class="text-danger mb-2">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12 justify-content-center d-flex">
                    <button type="submit" class="btn btn-outline-default rounded-0">Save Changes</button>
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
