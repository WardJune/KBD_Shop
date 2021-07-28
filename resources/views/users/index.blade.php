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
                                <li class="breadcrumb-item active">Users Management</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-5 col-lg-6 text-right">
                        <a href="{{ route('customer.deleted') }}" class="btn btn-sm btn-neutral">Deleted User</a>
                    </div>
                </div>
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Active Customer</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $verified + $unverified }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Verified Customer</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $verified }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last week</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Unverifed Customer</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $unverified }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                            <i class="fas fa-user-times"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                                    <span class="text-nowrap">Since yesterday</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Deleted Customer</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $deleted }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="fas fa-user-slash"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Customer</h3>
                            </div>
                            <div class="col-4 text-right">
                                <form action="{{ route('customer.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" name="q"
                                            placeholder="Search.." value="{{ request()->q }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light btn-sm mr-md-2 rounded-right "
                                                type="submit" id="button-addon2">Search</button>
                                        </div>
                                        <a href="{{ route('customer.create') }}" class="btn btn-sm btn-primary">Add
                                            user</a>
                                    </div>
                                </form>
                            </div>
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
                                    @foreach ($customers as $customer)
                                        @csrf
                                        @method('delete')
                                        <tr>
                                            <td>{{ $customer->name }}</td>
                                            <td>
                                                {{ $customer->email }}
                                            </td>
                                            <td>{{ $customer->created_at->format('h:i:s d-m-Y') }}</td>
                                            <td>{!! $customer->is_verified_label !!}</td>
                                            <td class="text-right">
                                                <a href="{{ route('customer.edit', $customer->id) }}"
                                                    class="btn btn-sm btn-info"><span class="fas fa-pencil-alt"></span></a>
                                                <button class="btn btn-sm btn-danger"
                                                    formaction="{{ route('customer.destroy', $customer->id) }}"><span
                                                        class="fas fa-trash-alt"></span></button>
                                            </td>
                                        </tr>
                                    @endforeach
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
