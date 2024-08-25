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
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/inventoryReport') }}">Primary Market Inventory
                                Report</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('dashboard.inventoryReport.list', $project->id) }}">Inventory List</a>
                        </li>
                        <li class="breadcrumb-item active">New Inventory</li>
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
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">New Inventory Form</h3>
                </div>
                <form class="form-boder" method="POST" id="storeForm"
                    action="{{ route('dashboard.inventoryReport.store', $project->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="list_type" value="{{ config('constants.primary')}}">
                    <input type="hidden" name="website_status" value="{{config('constants.requested')}}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title">Unit Type</label>
                                    <input type="text" value="{{ old('title') }}"
                                        class="form-control @error('title') is-invalid @enderror" id="title"
                                        placeholder="Enter Name" name="title" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="website_status">Wesbite Status</label>
                                    @if (in_array(Auth::user()->role, config('constants.isAdmin')))
                                        <select class="form-control @error('website_status') is-invalid @enderror"
                                            id="website_status" name="website_status">
                                            @foreach (config('constants.newStatusesWithoutAll') as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                        <select class="form-control @error('website_status') is-invalid @enderror"
                                            id="website_status" name="website_status">
                                            @foreach (config('constants.approvedRequested') as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @error('website_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="accommodation_id">Property Type</label>
                                    <div class="input-group">

                                        <div class="overflow-hidden noSideBorder flex-grow-1">
                                            <select data-placeholder="Select Accommodation" style="width: 100%;"
                                                class="select2 form-control @error('accommodation_id') is-invalid @enderror"
                                                id="accommodation_id" name="accommodation_id">
                                                @foreach ($accommodations as $accommodation)
                                                    <option value="{{ $accommodation->id }}">
                                                        {{ $accommodation->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('accommodation_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="bedrooms">Bedrooms</label>
                                    bedrooms
                                    <select data-placeholder="Select Bedrooms" style="width: 100%;"
                                        class="form-control select1 @error('bedrooms') is-invalid @enderror" id="bedrooms"
                                        name="bedrooms" required>
                                        @foreach (config('constants.bedrooms') as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>

                                    @error('bedrooms')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="area">Plot Area</label>
                                    <div class="input-group">

                                        <input type="text" value="{{ old('area') }}"
                                            class="form-control @error('area') is-invalid @enderror" id="area"
                                            name="area" required>
                                        @error('area')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="builtup_area">Build-up Area <small>(For villa property type must
                                            enter the buil-up area)</small> </label>
                                    <div class="input-group">

                                        <input type="text" value="{{ old('builtup_area') }}"
                                            class="form-control @error('builtup_area') is-invalid @enderror"
                                            id="builtup_area" name="builtup_area">
                                        @error('builtup_area')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="starting_price">Price</label>
                                    <input type="text" value="{{ old('starting_price') }}"
                                        class="form-control @error('starting_price') is-invalid @enderror"
                                        id="starting_price" name="starting_price" required>
                                    @error('starting_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="floorPlan">Floor Plan <small class="text-danger">(Prefer Size
                                            600x400)</small></label>
                                    <div class="custom-file   @error('floorPlan') is-invalid @enderror">
                                        <input type="file" class="custom-file-input" id="floorPlan"
                                            name="floorPlan[]" accept="image/*" multiple>
                                        <label class="custom-file-label" for="floorPlan">Choose file</label>
                                    </div>
                                    @error('floorPlan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function updateProperty(propertyId, field, value) {
                fetch(`/dashboard/projects/update-property/${propertyId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            field,
                            value
                        })
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
                input.addEventListener('input', function() {
                    const propertyId = this.getAttribute('data-property-id');
                    updateProperty(propertyId, 'price', this.value);
                });
            });

            document.querySelectorAll('.area-input').forEach(input => {
                input.addEventListener('input', function() {
                    const propertyId = this.getAttribute('data-property-id');
                    updateProperty(propertyId, 'area', this.value);
                });
            });

            document.querySelectorAll('.website-status-select').forEach(select => {
                select.addEventListener('change', function() {
                    const propertyId = this.getAttribute('data-property-id');
                    updateProperty(propertyId, 'website_status', this.value);
                });
            });
        });
    </script>
@endsection
