@extends('layouts.app', ['class' => 'bg-secondary'])

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Adjustment</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Adjustment</li>
                            </ol>
                        </nav>
                    </div>
                    {{-- <div class="col-lg-6 col-5 text-right">
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
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-md--6">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header row">
                        <div class="col-md-4 ">
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Type to Select Product..." onclick="clearThis(this)">

                        </div>
                        <div class="col-md-8 d-md-flex align-items-center justify-content-end">
                            <span class="text-muted">Adjust Quantity of Products</span>
                        </div>
                    </div>
                    <form method="post">
                        @csrf
                        <div class="table-responsive">
                            <table class="table align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th colspan="2" scope="col">qty</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer foot d-none justify-content-between">
                            <span id="totalProduct" class="d-block">..</span>
                            <button class="btn btn-primary" formaction="{{ route('inventory.update') }}">Adjust
                                Quantity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script type="text/javascript">
        let route = "{{ route('autocomplete') }}";
        let footer = $('.card-footer.foot')
        let totalProduct = $('#totalProduct')

        $('#search').typeahead({
            source: function(query, process) {
                return $.get(route, {
                    query: query
                }, (data) => process(data));
            },
            afterSelect: (data) => {
                $('#tbody').append(temp(data.id, data.name))

                let item = $('#tbody tr').length
                if (item > 0) {
                    footer.removeClass('d-none')
                    footer.addClass('d-md-flex')
                    totalProduct.text(`${item} Product`)
                }
            }
        });

        function clearThis(target) {
            target.value = ''
        }

        function toggleFooter() {}

        function destroy(id) {
            let item = $(`tr#${id}`)
            item.remove()
            let th = $('#tbody tr').length

            if (th == 0) {
                footer.removeClass('d-md-flex')
                footer.addClass('d-none')
            } else {
                totalProduct.text(`${th} Product`)
            }
        }

        function temp(id, name) {
            let comp = `<tr id=${id}>
                        <td>${name}</td>
                        <td>
                            <div class=" d-flex border-bottom border-neutral px-0">
                                <button
                                    onclick="var result = document.getElementById('sst${id}'); var sst = result.value; if( !isNaN( sst ) ) result.value--;return false;"
                                    class="btn btn-sm shadow-none--hover mr-0" type="button">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <input class="form-control form-control-flush text-center " type="number"
                                    name="qty[]" id="sst${id}" maxlength="5" value="1" class="input-text qty">

                                <input type="hidden" name="product_id[]" value="${id}">

                                <button
                                    onclick="var result = document.getElementById('sst${id}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                    class="btn btn-sm shadow-none--hover" type="button">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                         <td class="align-middle"><a href="javascript:void(0)" onclick="destroy(${id})"><i class="fas fa-times"></i></a></td>
                    </tr>`

            return comp
        }
    </script>
@endpush
