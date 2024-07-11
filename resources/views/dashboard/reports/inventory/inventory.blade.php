@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project Inventory</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/inventory-report') }}">Inventory Report</a>
                        </li>
                        <li class="breadcrumb-item active">Project Inventory</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('dashboard.reports.inventory.singleProject')
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <form class="form-boder" id="storeForm" method="POST"
                                action="{{ route('dashboard.projects.inventoryUpdate', $project->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="customFile">Upload Primary Market Inventory File <small><a
                                                href="{{ asset('dashboard/inventorySample.xlsx') }}"
                                                download="">(Download
                                                Sample
                                                File)</a></small></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="inventoryFile"
                                            accept=".xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>

                                    @error('inventoryFile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary storeBtn">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="card">

                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>SR.NO</th>
                                        <th>Name</th>
                                        <th>Refernce No.</th>
                                        <th>Project Unit</th>
                                        <th>Price </th>
                                        <th>Website Status</th>
                                        <th>Property Type</th>

                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->properties as $key => $property)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $property->name }}</td>
                                            <td>{{ $property->reference_number }}</td>
                                            <td>
                                                @if ($property->project)
                                                    {{ $property->subProject->title }}
                                                @endif
                                            </td>
                                            <td>{{ $property->price }}</td>
                                            <td>
                                                <span
                                                    class="badge 
                                                @if ($property->website_status === config('constants.NA')) bg-info 
                                                @elseif($property->website_status === config('constants.available')) bg-success 
                                                @elseif($property->website_status === config('constants.rejected'))  bg-danger 
                                                @elseif($property->website_status === config('constants.requested'))  bg-warning @endif">

                                                    {{ ucfirst($property->website_status) }}
                                                </span>
                                            </td>
                                            <td>{{ $property->accommodations ? $property->accommodations->name : '' }}</td>
                                            <td>{{ $property->formattedUpdatedAt }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
