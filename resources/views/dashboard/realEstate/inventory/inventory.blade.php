@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project Primary Market Inventory List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/inventoryReport') }}">Primary Market Inventory Report</a>
                        </li>
                        <li class="breadcrumb-item active">Project Primary Market Inventory List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('dashboard.realEstate.inventory.singleProject')
            <div class="row">
                <div class="col-12">
                    {{--<div class="card">

                        <div class="card-body">
                             <form class="form-boder" id="storeForm" method="POST"
                                action="{{ route('dashboard.projects.inventoryUpdate', $project->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">

                                    <label for="customFile">Upload Primary Market Inventory File <small>
                                            

                                            <a href="{{ route('dashboard.projects.inventoryDownload', $project->id) }}">
                                                Download Sample FIle</a>

                                           
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile"
                                                    name="inventoryFile"
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
                    </div>--}}
                    <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <a href="{{ route('dashboard.inventoryReport.create', $project->id) }}"
                                    class="btn btn-block btn-primary">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    New Inventory
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>SR.NO</th>
                                        <th>Name</th>
                                        <th>Refernce No.</th>
                                        <th>Project Unit</th>
                                        <th>Price </th>
                                        <th>Website Status</th>
                                        <th>Area</th>
                                        <th>Property Type</th>

                                        {{-- <th>Updated At</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->properties->where('property_source', 'crm') as $key => $property)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $property->name }}</td>
                                        <td>{{ $property->reference_number }}</td>
                                        <td>
                                            @if ($property->project && $property->subProject)
                                                {{ $property->subProject->title }}
                                            @endif
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="price"
                                                data-property-id="{{ $property->id }}"
                                                value="{{ $property->price }}"
                                                class="form-control price-input"
                                            />
                                        </td>
                                        <td>
                                            <select
                                                name="website_status"
                                                data-property-id="{{ $property->id }}"
                                                class="form-control form-select website-status-select"
                                                
                                            >
                                                <option value="{{ config('constants.NA') }}" @if ($property->website_status === config('constants.NA')) selected @endif>NA</option>
                                                <option value="{{ config('constants.available') }}" @if ($property->website_status === config('constants.available')) selected @endif disabled>Available</option>
                                                <option value="{{ config('constants.rejected') }}" @if ($property->website_status === config('constants.rejected')) selected @endif disabled>Rejected</option>
                                                <option value="{{ config('constants.requested') }}" @if ($property->website_status === config('constants.requested')) selected @endif>Requested</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                name="area"
                                                data-property-id="{{ $property->id }}"
                                                value="{{ $property->area }}"
                                                class="form-control area-input"
                                            />
                                        </td>
                                        <td>{{ $property->accommodations ? $property->accommodations->name : '' }}</td>
                                        {{-- <td>{{ $property->formattedUpdatedAt }}</td> --}}
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
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
        function updateProperty(propertyId, field, value) {
            fetch(`/dashboard/projects/update-property/${propertyId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ field, value })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                toastr.success('Inventory has been updated successfully');

                // Removing the bg-danger class
                $("#singleProjectRow").removeClass('bg-danger');

                // Updating the date to the current date
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                $("#inventoryDate").html(formattedDate);
            } else {
                toastr.error('Update failed: ' + data.message);
            }
            }).catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred. Please try again.');
            });
        }
    
        document.querySelectorAll('.price-input').forEach(input => {
            input.addEventListener('input', function () {
                const propertyId = this.getAttribute('data-property-id');
                updateProperty(propertyId, 'price', this.value);
            });
        });
    
        document.querySelectorAll('.area-input').forEach(input => {
            input.addEventListener('input', function () {
                const propertyId = this.getAttribute('data-property-id');
                updateProperty(propertyId, 'area', this.value);
            });
        });
    
        document.querySelectorAll('.website-status-select').forEach(select => {
            select.addEventListener('change', function () {
                const propertyId = this.getAttribute('data-property-id');
                updateProperty(propertyId, 'website_status', this.value);
            });
        });
    });
    </script>
@endsection
