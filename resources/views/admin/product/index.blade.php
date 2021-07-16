@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    {{-- header --}}
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
                                <li class="breadcrumb-item active" aria-current="page">Product</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        <form action="{{ route('product.search') }}" method="get" class="d-inline-block">
                            <div class="form-group">
                                <div class="input-group input-group-alternative mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input name="keyword" type="search" class="form-control form-control-alternative"
                                        placeholder="Search">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- page content --}}
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-md">
                {{-- alert success --}}
                @if (session('success'))
                    <div class="alert alert-secondary alert-dismissable fade show text-success" role="alert">
                        <span class="alert-inner--icon"><i class="ni ni-check-bold"></i></span>
                        <span class="alert-inner--text"><strong>Success!</strong> {{ session('success') }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true" class="text-dark">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- session error --}}
                @if (session('error'))
                    <div class="alert alert-secondary text-danger" role="alert"><strong>{{ session('error') }}</strong>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h4>Product</h4>
                            <div class="right">
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#uploadModal">
                                    Mass Upload
                                </button>
                                <a href="{{ route('product.create') }}"
                                    class="btn btn-sm btn-success d-lg-inline-block">Add
                                    New Product</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Created_at</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @forelse ($products as $prod)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td><img src="{{ asset('/storage/' . $prod->image) }}" width="100px"
                                                height="100px"></td>
                                        <td>
                                            <p>{{ $prod->name }}</p>
                                            <label> Category : <span
                                                    class="badge badge-primary">{{ $prod->category->name }}</span></label>
                                            @if ($prod->merk_id != null)
                                                <label> Merk : <span
                                                        class="badge badge-warning">{{ $prod->merk->name }}</span></label>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($prod->price) }}</td>
                                        <td>{{ $prod->created_at->format('d-m-Y') }}</td>
                                        <td> {!! $prod->status_label !!} </td>
                                        <td>
                                            <a href="{{ route('product.edit', $prod->id) }}"
                                                class="btn btn-info btn-sm">Edit</a>
                                            {{-- button trigger modal --}}
                                            {{-- delete --}}
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal{{ $prod->id }}">
                                                Delete
                                            </button>
                                            {{-- button trigger modal --}}
                                        </td>
                                    </tr>
                                    {{-- modal delete --}}
                                    <div class="modal fade" id="deleteModal{{ $prod->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure about this one ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">No</button>
                                                    <form action="{{ route('product.destroy', $prod->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- end modal delete --}}
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-dark">No Data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- Mass Upload Modal --}}
                        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Mass Upload Form</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('product.bulk') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="form-control-label" for="category">Category</label>
                                                <select class="form-control" id="category" name="category_id">
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="text-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Excel File </label><small class="text-muted"> *).xls
                                                    format file only</small>
                                                <div class="input-group mb-3 rounded">
                                                    <div class="custom-file">
                                                        <input name="file" type="file" class="custom-file-input"
                                                            id="inputGroupFile01" aria-describedby="inputGroupFileAddon01"
                                                            value="{{ old('file') }}">
                                                        <label class="custom-file-label" for="inputGroupFile01">Choose
                                                            file</label>
                                                    </div>
                                                    <p class="text-danger">{{ $errors->first('file') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- Mass Upload Modal --}}
                    </div>
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass('selected').html(fileName);
        })
    </script>
@endpush
