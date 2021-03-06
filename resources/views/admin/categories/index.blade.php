@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Category</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Category</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- page content --}}
    <div class="container-fluid mt--6 ">
        <div class="row">
            <div class="col-md-8">
                {{-- session success --}}
                {{-- @if (session('success'))
            <div class="alert alert-secondary alert-dismissible fade show text-success" role="alert">
                <span class="alert-inner--icon"><i class="ni ni-check-bold"></i></span>
                <span class="alert-inner--text"><strong>Success!</strong> {{session('success')}}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" class="text-dark">&times;</span>
                </button>
            </div>
            @endif --}}
                {{-- session error --}}
                @if (session('error'))
                    <div class="alert alert-secondary text-danger" role="alert"><strong>{{ session('error') }}</strong>
                    </div>
                @endif

                <div class="card bg-gradient-secondary border-default">
                    <div class="card-header bg-transparent">
                        <div class="mb-0">
                            <h4 class="text-dark">Category</h4>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center ">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- loop data --}}
                                @php
                                    $i = 1;
                                @endphp
                                @forelse ($category as $val)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $val->name }}</td>
                                        <td>{{ $val->created_at->format('d-m-y') }}</td>
                                        <td>
                                            {{-- button trigger modal --}}
                                            {{-- edit --}}
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#editModal{{ $val->id }}">
                                                Edit
                                            </button>
                                            {{-- delete --}}
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal{{ $val->id }}">
                                                Delete
                                            </button>
                                            {{-- button trigger modal --}}
                                        </td>
                                    </tr>
                                    {{-- modal for edit Category --}}
                                    <div class="modal fade" id="editModal{{ $val->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Modal</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('category.update', $val->id) }}" method="post">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="modal-body ">
                                                        <div class="form-group">
                                                            <label for="name" id="name">Category Name</label>
                                                            <input type="text" name="name" id="name" class="form-control"
                                                                required value="{{ $val->name }}">
                                                            @error('name')
                                                                <div class="text-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer mt--4">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="deleteModal{{ $val->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Modal</h5>
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
                                                    <form action="{{ route('category.destroy', $val->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- end of modal --}}
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-dark">No Data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            {{-- Add New Category Form --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-bottom">
                        Add New Category
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="name" id="name">Category Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                                @error('name')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success ">Add New</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
