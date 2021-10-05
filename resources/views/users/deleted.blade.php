@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    <div class="header bg-primary pb-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Users</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Users Management</a>
                                </li>
                                <li class="breadcrumb-item active">Deleted User</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--8">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3 class="mb-0">Deleted Customer</h3>
                            </div>
                            @if ($customers->count() > 0)
                                <div class="col-md-6 d-flex align-items-center justify-content-md-end">
                                    <form action="{{ route('customer.deleted') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" name="q"
                                                placeholder="Search.." value="{{ request()->q }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-light btn-sm rounded-right mr-md-3"
                                                    type="submit" id="button-addon2">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <form method="POST">
                                        @csrf
                                        <button class="btn-sm btn btn-success"
                                            formaction="{{ route('customer.restore-all') }}">Restore All</button>
                                        <button class="btn-sm btn btn-danger"
                                            formaction="{{ route('customer.delete-all') }}">Delete All</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Creation Date</th>
                                    <th scope="col">status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <form method="post">
                                    @csrf
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->name }}</td>
                                            <td>
                                                {{ $customer->email }}
                                            </td>
                                            <td>{{ $customer->created_at->format('h:i:s d-m-Y') }}</td>
                                            <td>{!! $customer->is_verified_label !!}</td>
                                            <td class="text-right">
                                                <button type="submit" class="btn btn-sm btn-info"
                                                    formaction="{{ route('customer.restore', $customer->id) }}"><span
                                                        class="fas fa-trash-restore-alt"></span></button>
                                                <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault()"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal{{ $customer->id }}"><span
                                                        class="fas fa-trash-alt"></span></a>
                                            </td>
                                        </tr>
                                        {{-- modal delete --}}
                                        <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Modal</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure? , You won't be able to revert this!
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">No</button>
                                                        <button
                                                            formaction="{{ route('customer.force-delete', $customer->id) }}"
                                                            class="btn btn-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- end modal delete --}}
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Data</td>
                                        </tr>
                                    @endforelse

                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $customers->links() }}
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
