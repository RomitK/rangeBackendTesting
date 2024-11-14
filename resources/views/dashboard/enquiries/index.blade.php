@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Enquiries</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Enquiries</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="d-flex justify-content-end pt-3 mr-5">
                            <form id="form-filter-users" class="form-inline">
                                <div class="form-group">
                                    <label class="sr-only" for="inputUnlabelUsername">Search</label>
                                    <input id="search-query" type="text" class="form-control w-full"
                                           placeholder="Search..."
                                           autocomplete="off">
                                </div>
                                <div class="form-group px-3">
                                    <button id="btn-filter-admins" type="submit" class="btn btn-primary btn-outline">
                                        Search
                                    </button>
                                    <a id="btn-clear" class="btn btn-primary ml-2 text-white">Clear</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            {!! $dataTable->table(['id' => 'enquiries-table'], true) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        let $table = $('#enquiries-table');

        $('#form-filter-users').submit(function(e) {
            e.preventDefault();
            $table.DataTable().draw();
        });

        $('#btn-clear').click(function() {
            $('#search-query').val('');
            $table.DataTable().draw();
        });

        $table.on('preXhr.dt', function ( e, settings, data ) {
            data.filter = {
                q: $('#search-query').val(),
            };
        });
    </script>
@endpush
