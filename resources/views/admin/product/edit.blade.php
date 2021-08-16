@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')

    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Product</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--6">
        <div class="animated fadeIn">
            <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Edit New Product</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Product Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name') ?? $product->name }}" required>
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="desc">Description</label>
                                    <input class="form-control mb-1" type="text" name="desc[]" required
                                        value="{{ $product->desc[0] }}">
                                    <input class="form-control mb-1" type="text" name="desc[]"
                                        value="{{ $product->desc[1] }}">
                                    <input class="form-control " type="text" name="desc[]"
                                        value="{{ $product->desc[2] }}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Full Description</label>

                                    {{-- tambah id untuk digunakan ckeditor --}}
                                    <textarea name="fulldesc" id="description"
                                        class="form-control">{{ old('description') ?? $product->fulldesc }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="1"
                                            {{ old('status') || $product->status == '1' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0"
                                            {{ old('status') || $product->status == '0' ? 'selected' : '' }}>
                                            Disable</option>
                                    </select>
                                    <p class="text-danger">{{ $errors->first('status') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="category_id">Category</label>

                                    {{-- Data pilihan kategori --}}
                                    <select name="category_id" class="form-control" id="category">
                                        <option value disabled>Pilih</option>
                                        @foreach ($category as $row)
                                            <option value="{{ $row->id }}"
                                                {{ old('category_id') || $product->category_id == $row->id ? 'selected' : '' }}>
                                                {{ $row->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category_id') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select name="type" class="form-control" id='type'>
                                        <option disabled selected>Choose Type</option>
                                        @foreach ($data['type'] as $type)
                                            <option value="{{ $type->name }}"
                                                {{ old('type') || $type->name == $product->type ? 'selected' : '' }}>
                                                {{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('type') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="size">Size</label>
                                    <select name="size" class="form-control" id="size"
                                        {{ $product->size == null ? 'disabled' : '' }}>
                                        <option disabled selected>Choose Size</option>
                                        @if ($data['size'] ?? '')
                                            @foreach ($data['size'] as $size)
                                                <option value="{{ $size->name }}"
                                                    {{ old('size') || $size->name == $product->size ? 'selected' : '' }}>
                                                    {{ $size->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="text-danger">{{ $errors->first('size') }}</p>
                                </div>

                                {{-- Data pilihan merk --}}
                                <div class="form-group">
                                    <label for="merk_id">Merk</label>
                                    <select name="merk_id" id="merk_id" class="form-control">
                                        <option value='null' {{ $product->merk_id == null ? 'selected' : '' }}>none
                                        </option>
                                        @foreach ($merk as $val)
                                            <option value="{{ $val->id }}"
                                                {{ old('merk_id') || $product->merk_id == $val->id ? 'selected' : '' }}>
                                                {{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('merk_id') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" class="form-control"
                                        value="{{ old('price') ?? $product->price }}" required>
                                    <p class="text-danger">{{ $errors->first('price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="weight">Weight</label>
                                    <input min="0.1" step="0.1" type="number" name="weight" class="form-control"
                                        value="{{ old('weight') ?? $product->weight }}" required>
                                    <p class="text-danger">{{ $errors->first('weight') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <div class="input-group mb-2 rounded">
                                        <div class="custom-file">
                                            <input name="image" type="file" class="custom-file-input" id="inputGroupFile01"
                                                aria-describedby="inputGroupFileAddon01" value="{{ old('image') }}">
                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                        <p class="text-danger">{{ $errors->first('image') }}</p>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    {{-- load ckeditor --}}
    <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');

        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass('selected').html(fileName);
        })

        $('#category').on('change', function() {
            $.ajax({
                url: "{{ url('api/type') }}",
                type: "GET",
                data: {
                    category_id: $(this).val()
                },
                success: function(html) {
                    $('#type').removeAttr('disabled')
                    $('#type').empty()
                    $('#type').append('<option disabled selected>Choose Type</option>')
                    $.each(html.data['type'], function(key, item) {
                        $('#type').append(`<option value="${item.name}"> ${item.name}</option>`)
                    });

                    if (html.data['size']) {
                        $('#size').removeAttr("disabled")
                        $('#size').empty()
                        $('#size').append('<option disabled selected>Choose Size</option>')
                        $.each(html.data['size'], function(key, item) {
                            $('#size').append(
                                `<option value="${item.name}"> ${item.name}</option>`)
                        })
                    } else {
                        $('#size').prop('disabled', true)
                        $('#size').empty();
                        $('#size').append('<option disabled selected>Choose Size</option>')
                    };
                }
            })
        })
    </script>
@endpush
