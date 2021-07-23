@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-7 col-8">
                        <h6 class="h2 text-white d-inline-block mb-0">Adjustment Summary</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-md--6">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="table-responsive-md py-1">
                        <table class="table ">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">qty</th>
                                </tr>
                            </thead>
                            @php $i = 1 @endphp
                            @if ($histories->count() > 0)
                                <tbody>

                                    @foreach ($histories as $history)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $history->product->name }}</td>
                                            <td>{{ $history->created_at->format('d-m-Y') }}</td>
                                            <th>{{ $history->qty }}</th>
                                        </tr>
                                        @php $i++ @endphp
                                    @endforeach
                                    <tr>
                                        <th colspan="3" class="text-right">{{ $histories->count() }} Products</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @else
                                <tbody>
                                    No Data
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
