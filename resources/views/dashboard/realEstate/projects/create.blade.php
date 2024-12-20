@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Projects</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/projects') }}">Projects</a></li>
                        <li class="breadcrumb-item active">New Project</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">New Project Form</h3>

                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" method="POST"
                            action="{{ route('dashboard.projects.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="main_community_id">Community</label>
                                            <div class="input-group">

                                                <select data-placeholder="Select Community"
                                                    class=" form-control select1 @error('main_community_id') is-invalid @enderror"
                                                    id="main_community_id" name="main_community_id" required>
                                                    <option></option>
                                                    @foreach ($communities as $community)
                                                        <option value="{{ $community->id }}">{{ $community->name }}</option>
                                                    @endforeach

                                                </select>
                                                @error('main_community_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="developer">Developer</label>
                                            <select class="form-control select1 @error('developer_id') is-invalid @enderror"
                                                id="developer_id" name="developer_id" data-placeholder="Select Developer"
                                                required>
                                                <option></option>

                                            </select>
                                            @error('developer_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="projectOrder">Project Order</label>
                                            <input type="number" value="{{ old('projectOrder') }}"
                                                class="form-control @error('projectOrder') is-invalid @enderror"
                                                id="projectOrder" name="projectOrder" min="1">
                                            @error('projectOrder')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Name</label>
                                            <input type="text" value="{{ old('title') }}"
                                                class="form-control @error('title') is-invalid @enderror" id="title"
                                                placeholder="Enter Name" name="title">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="permit_number">Permit Number</label>
                                            <input type="text" value="{{ old('permit_number') }}"
                                                class="form-control @error('permit_number') is-invalid @enderror"
                                                id="permit_number" placeholder="Enter Permit Number" name="permit_number">
                                            @error('permit_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="reference_number">Reference Number</label>
                                            <input type="text" value="{{ old('reference_number') }}"
                                                class="form-control @error('reference_number') is-invalid @enderror"
                                                id="reference_number" placeholder="Enter Reference Number"
                                                name="reference_number">
                                            @error('reference_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-3">
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
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="is_display_home">Is Display on Home Page?</label>
                                            <select class="form-control @error('is_display_home') is-invalid @enderror"
                                                id="is_display_home" name="is_display_home">
                                                @foreach (config('constants.booleanOptions') as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('is_display_home')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="completion_date">HandOver</label>
                                            <div class="input-group">

                                                <input type="date" value="{{ old('completion_date') }}"
                                                    class="form-control @error('completion_date') is-invalid @enderror"
                                                    id="completion_date" name="completion_date">
                                                @error('completion_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="completion_status_id">Completion Status</label>
                                            <select data-placeholder="Select Completion status" style="width: 100%;"
                                                class=" form-control select1 @error('completion_status_id') is-invalid @enderror"
                                                id="completion_status_id" name="completion_status_id">
                                                @foreach ($completionStatuses as $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('completion_status_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="accommodation_id">Property Type</label>
                                            <div class="input-group">

                                                <div class="overflow-hidden noSideBorder flex-grow-1">
                                                    <select data-placeholder="Select Accommodation" style="width: 100%;"
                                                        class="select2 form-control @error('accommodation_id') is-invalid @enderror"
                                                        id="accommodation_id" name="accommodation_id">
                                                        @foreach ($accommodations as $accommodation)
                                                            <option value="{{ $accommodation->id }}">
                                                                {{ $accommodation->name }}
                                                            </option>
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
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="used_for">Used For</label>
                                            <select class="form-control select1 @error('used_for') is-invalid @enderror"
                                                id="used_for" name="used_for">
                                                @foreach (config('constants.accommodationType') as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('used_for')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="project_source">Project Source</label>
                                            <select
                                                class="form-control select1 @error('project_source') is-invalid @enderror"
                                                id="project_source" name="project_source">
                                                @foreach (config('constants.propertySources') as $value)
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('project_source')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="upcoming_project">Upcoming</label>
                                            <div class="form-control d-flex justify-content-center align-items-center">
                                                <input 
                                                    type="checkbox" 
                                                    class="form-check-input @error('upcoming_project') is-invalid @enderror" 
                                                    id="upcoming_project" 
                                                    name="upcoming_project" 
                                                    style="margin-left: 1px;"
                                                >
                                            </div>
                                            @error('upcoming_project')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="features_description">Hightlights Description <small
                                                    class="text-danger">(Not more 400 characters)</small></label>
                                            <textarea id="features_description"
                                                class="summernote form-control @error('features_description') is-invalid @enderror" name="features_description">{{ old('features_description') }}</textarea>
                                            @error('features_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="short_description">Short Description<small
                                                    class="text-danger">(Not more 400 characters)</small></label>
                                            <textarea id="short_description" class="summernote form-control @error('short_description') is-invalid @enderror"
                                                name="short_description">{{ old('short_description') }}</textarea>
                                            @error('short_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="long_description">Long Description</label>
                                            <textarea id="long_description" class="summernote form-control @error('long_description') is-invalid @enderror"
                                                name="long_description">{{ old('long_description') }}</textarea>
                                            @error('long_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="amenities">Amenities<small class="text-danger">(At least 8
                                                    Amenities )</small></label>
                                            <select multiple="multiple" data-placeholder="Select Amenities"
                                                style="width: 100%;"
                                                class="select2 form-control @error('amenities') is-invalid @enderror"
                                                id="amenities" name="amenities[]">
                                                @foreach ($amenities as $amenity)
                                                    <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('amenities')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" value="{{ old('address') }}" id="address-input"
                                                name="address" class="form-control map-input" required>
                                            <input type="hidden" name="address_latitude" id="address-latitude"
                                                value="0" />
                                            <input type="hidden" name="address_longitude" id="address-longitude"
                                                value="0" />

                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div id="address-map-container" style="width:100%;height:200px; ">
                                                <div style="width: 100%; height: 100%" id="address-map"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" value="{{ old('meta_title') }}"
                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                id="meta_title" placeholder="Enter Meta Title" name="meta_title">
                                            @error('meta_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords<small class="text-danger">(Multiple
                                                    keywords separated with comas)</small></label>
                                            <input type="text" value="{{ old('meta_keywords') }}"
                                                class="form-control @error('meta_keywords') is-invalid @enderror"
                                                id="meta_keywords" placeholder="Enter Meta Keywords"
                                                name="meta_keywords">
                                            @error('meta_keywords')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                                placeholder="Enter Meta Description" name="meta_description">{{ old('meta_description') }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="mainImage">Main Image <small class="text-danger">(Prefer Dimension
                                                    600X300)</small></label>
                                            <div class="custom-file   @error('mainImage') is-invalid @enderror">
                                                <input type="file"
                                                    class="custom-file-input   @error('mainImage') is-invalid @enderror"
                                                    id="mainImage" name="mainImage" accept="image/*">
                                                <label class="custom-file-label" for="mainImage">Choose file</label>
                                            </div>
                                            <img id="mainImagePreview"
                                                style="max-width: 100%; height: 100px; display: none;" />

                                            @error('mainImage')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="clusterPlan">Cluster Plan <small class="text-danger">(Prefer
                                                    Dimension 600X1000)</small></label>
                                            <div class="custom-file   @error('clusterPlan') is-invalid @enderror">
                                                <input type="file"
                                                    class="custom-file-input   @error('clusterPlan') is-invalid @enderror"
                                                    id="clusterPlan" name="clusterPlan" accept="image/*">
                                                <label class="custom-file-label" for="clusterPlan">Choose file</label>
                                            </div>
                                            <img id="clusterPlanPreview"
                                                style="max-width: 100%; height: 100px; display: none;" />

                                            @error('clusterPlan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="qr">QR </label>
                                            <div class="custom-file   @error('qr') is-invalid @enderror">
                                                <input type="file"
                                                    class="custom-file-input   @error('qr') is-invalid @enderror"
                                                    id="qr" name="qr" accept="image/*">
                                                <label class="custom-file-label" for="qr">Choose file</label>
                                            </div>
                                            <img id="qrPreview" style="display: none;" />

                                            @error('qr')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="items" class="table table-no-more table-bordered mb-none ">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">
                                                            <label for="gallery">Exterior Gallery<small
                                                                    class="text-danger">(Prefer Dimension
                                                                    1600x800)</small></label>
                                                        </th>
                                                    </tr>
                                                    <tr style="">
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Order</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @error('exteriorGallery')
                                                        <tr>
                                                            <th colspan="4">
                                                                @error('exteriorGallery')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                @error('exteriorGallery.*')
                                                                    <span class="invalid-feedback" role="alert"
                                                                        style="display: block">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror

                                                            </th>
                                                        </tr>
                                                    @enderror
                                                </thead>

                                                <tr class="exterior-item-row" style="border-bottom: solid 1px black">
                                                    <td>
                                                        <div
                                                            class="custom-file   @error('exteriorGallery') is-invalid @enderror">
                                                            <input type="file"
                                                                class="custom-file-input @error('exteriorGallery') is-invalid @enderror"
                                                                id="gallery" name="exteriorGallery[0][file]"
                                                                accept="image/*" onchange="exteriorPreviewImage(event)">
                                                            <label class="custom-file-label" for="gallery">Choose
                                                                file</label>

                                                        </div>
                                                        <img id="exterior-image-preview" src="#"
                                                            alt="Image Preview" style="display: none;" height="100" />

                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control @error('key') is-invalid @enderror"
                                                            placeholder="Enter Title" name="exteriorGallery[0][title]">
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0"
                                                            class="form-control @error('value') is-invalid @enderror"
                                                            placeholder="Enter Order" name="exteriorGallery[0][order]">
                                                    </td>

                                                    <td><a class="btn btn-block btn-primary btn-sm addRowExterior updateRow0"
                                                            href="javascript:;"><i class="fa fa-plus"
                                                                aria-hidden="true"></i> Add</a>
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="items" class="table table-no-more table-bordered mb-none ">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">
                                                            <label for="gallery">Interior Gallery<small
                                                                    class="text-danger">(Prefer Dimension
                                                                    600x600)</small></label>
                                                        </th>
                                                    </tr>
                                                    <tr style="">
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Order</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @error('interiorGallery')
                                                        fdsd
                                                        <tr>
                                                            <th colspan="4">
                                                                @error('interiorGallery')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                @error('interiorGallery.*')
                                                                    <span class="invalid-feedback" role="alert"
                                                                        style="display: block">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror

                                                            </th>
                                                        </tr>
                                                    @enderror
                                                </thead>

                                                <tr class="interior-item-row" style="border-bottom: solid 1px black">
                                                    <td>
                                                        <!--<input type="file" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Installment" name="rows[0][name]" >-->

                                                        <div
                                                            class="custom-file   @error('interiorGallery') is-invalid @enderror">
                                                            <input type="file"
                                                                class="custom-file-input @error('interiorGallery') is-invalid @enderror"
                                                                id="gallery" name="interiorGallery[0][file]"
                                                                accept="image/*" onchange="interiorPreviewImage(event)">
                                                            <label class="custom-file-label" for="gallery">Choose
                                                                file</label>

                                                        </div>
                                                        <img id="interior-image-preview" src="#"
                                                            alt="Image Preview" style="display: none;" height="100" />

                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control @error('key') is-invalid @enderror"
                                                            placeholder="Enter Title" name="interiorGallery[0][title]">
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0"
                                                            class="form-control @error('value') is-invalid @enderror"
                                                            placeholder="Enter Order" name="interiorGallery[0][order]">
                                                    </td>

                                                    <td><a class="btn btn-block btn-primary btn-sm addRowInterior updateRow0"
                                                            href="javascript:;"><i class="fa fa-plus"
                                                                aria-hidden="true"></i> Add</a>
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary storeBtn">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChuU-X16agmkNHRIw5mqaFTcsMsSlASBs&libraries=places&callback=initialize"
        async defer></script>
    <script>
        function initialize() {
            const locationInputs = document.getElementsByClassName("map-input");
            const autocompletes = [];
            const geocoder = new google.maps.Geocoder;
            for (let i = 0; i < locationInputs.length; i++) {
                const input = locationInputs[i];
                const fieldKey = input.id.replace("-input", "");
                const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(
                    fieldKey + "-longitude").value != '';
                const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -33.8688;
                const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 151.2195;
                const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
                    center: {
                        lat: latitude,
                        lng: longitude
                    },
                    zoom: 13
                });
                const marker = new google.maps.Marker({
                    map: map,
                    position: {
                        lat: latitude,
                        lng: longitude
                    },
                });
                marker.setVisible(isEdit);
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.key = fieldKey;
                autocompletes.push({
                    input: input,
                    map: map,
                    marker: marker,
                    autocomplete: autocomplete
                });
            }

            for (let i = 0; i < autocompletes.length; i++) {
                const input = autocompletes[i].input;
                const autocomplete = autocompletes[i].autocomplete;
                const map = autocompletes[i].map;
                const marker = autocompletes[i].marker;
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    marker.setVisible(false);
                    const place = autocomplete.getPlace();
                    geocoder.geocode({
                        'placeId': place.place_id
                    }, function(results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            setLocationCoordinates(autocomplete.key, lat, lng);
                        }
                    });

                    if (!place.geometry) {
                        window.alert("No details available for input: '" + place.name + "'");
                        input.value = "";
                        return;
                    }
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);
                });
            }
        }

        function setLocationCoordinates(key, lat, lng) {
            const latitudeField = document.getElementById(key + "-" + "latitude");
            const longitudeField = document.getElementById(key + "-" + "longitude");
            latitudeField.value = lat;
            longitudeField.value = lng;
        }
    </script>
@endsection
@section('js')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {

            // $('#developer_id').on('change', function(e) {
            //     var developer_id = e.target.value;
            //     $.ajax({
            //         url: "{{ route('dashboard.developers.communities') }}",
            //         type: "POST",
            //         data: {
            //             developer_id: developer_id
            //         },
            //         success: function(data) {
            //             $('#main_community_id').empty();
            //             $('#main_community_id').append('<option value=""></option>');
            //             $.each(data.communities, function(index, community) {
            //                 $('#main_community_id').append('<option value="' + community.id + '">' + community.name + '</option>');
            //             })
            //         }
            //     })
            // });

            $('#main_community_id').on('change', function(e) {
                var category_id = e.target.value;
                $.ajax({
                    url: "{{ route('dashboard.community.developers') }}",
                    type: "POST",
                    data: {
                        category_id: category_id
                    },
                    success: function(data) {
                        $('#developer_id').empty();
                        $('#developer_id').append('<option value=""></option>');
                        $.each(data.developers, function(index, developer) {
                            $('#developer_id').append('<option value="' + developer.id +
                                '">' + developer.name + '</option>');
                        })
                    }
                })
            });
        });
    </script>

    <script type="text/javascript">
        function exteriorPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('exterior-image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function interiorPreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('interior-image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }



        $(document).on('change', '.exterior-file-upload', function() {

            var previewId = $(this).data('target');

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result);
                $('#' + previewId).show();
            };
            reader.readAsDataURL(this.files[0]);
        });

        $(document).on('change', '.interior-file-upload', function() {

            var previewId = $(this).data('target');

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result);
                $('#' + previewId).show();
            };
            reader.readAsDataURL(this.files[0]);
        });


        $(document).ready(function() {
            var exterior = 1;
            var exteriorCount = 0;
            $(document).on('click', '.addRowExterior', function() {
                $(this).text('x Remove');
                $(this).attr('class', 'btn btn-danger btn-sm delExterior');
                $(".exterior-item-row:last").find('.exteriorMybtn').hide();
                exterior++;
                exteriorCount++;
                var exteriorId = exteriorCount;

                var exteriorNewRow =
                    '<tr class="exterior-item-row" style="border-bottom: solid 1px black">' +
                    '<td class="main_td">' +
                    '  <input type="file" class="form-control exterior-file-upload"  accept="image/*" data-target="exteriorPreviewImage' +
                    exteriorId + '" name="exteriorGallery[' + exteriorId + '][file]" required>' +
                    '  <img id="exteriorPreviewImage' + exteriorId +
                    '" src="#" alt="Image Preview" style="display: none; max-width: 100px; max-height: 100px;" />' +
                    '</td>' +
                    '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Title" name="exteriorGallery[' +
                    exteriorId + '][title]"></td>' +
                    '<td class="main_td"><input type="number" min="0" class="form-control" placeholder="Enter Order" name="exteriorGallery[' +
                    exteriorId + '][order]"></td>' +
                    '<td data-title="Action" class="main_td"> <button type="button" class="btn btn-primary btn-sm addRowExterior" id="updateRow' +
                    exteriorId + '">+ Add</button> ' +
                    '  <a class="Remove exteriorMybtn btn btn-danger btn-sm" href="javascript:;" title="Remove row"> x Remove</a>' +
                    '</td>' +
                    '</tr>';

                $(".exterior-item-row:last").after(exteriorNewRow);
            });

            $(document).on('click', '.delExterior', function() {
                $(this).parent().parent().remove();
            });
            $(document).on('click', '.Remove', function() {
                $(this).parent().parent().remove();
                $(".delExterior").eq(-1).text('+ Add');
                $('.delExterior').eq(-1).attr('class', 'btn btn-primary btn-sm addRowExterior');
            });
        })



        var interior = 1;
        var interiorcount = 0;
        $(document).on('click', '.addRowInterior', function() {
            $(this).text('x Remove');
            $(this).attr('class', 'btn btn-danger btn-sm interiorDel');
            $(".interior-item-row:last").find('.interiorMybtn').hide();
            interior++;
            interiorcount++;
            var interiorId = interiorcount;

            var interiorNewRow = '<tr class="interior-item-row" style="border-bottom: solid 1px black">' +
                '<td class="main_td">' +
                '  <input type="file" class="form-control interior-file-upload" accept="image/*" data-target="interiorPreviewImage' +
                interiorId + '" name="exteriorGallery[' + interiorId + '][file]" required>' +
                '  <img id="interiorPreviewImage' + interiorId +
                '" src="#" alt="Image Preview" style="display: none; max-width: 100px; max-height: 100px;" />' +
                '</td>' +
                '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Title" name="exteriorGallery[' +
                interiorId + '][title]"></td>' +
                '<td class="main_td"><input type="number" min="0" class="form-control" placeholder="Enter Order" name="exteriorGallery[' +
                interiorId + '][order]"></td>' +
                '<td data-title="Action" class="main_td"> <button type="button" class="btn btn-primary btn-sm addRowInterior" id="updateRow' +
                interiorId + '">+ Add</button> ' +
                '  <a class="interiorRemove interiorMybtn btn btn-danger btn-sm" href="javascript:;" title="interiorRemove row"> x Remove</a>' +
                '</td>' +
                '</tr>';

            $(".interior-item-row:last").after(interiorNewRow);
        });

        $(document).on('click', '.interiorDel', function() {
            $(this).parent().parent().remove();
        });
        $(document).on('click', '.interiorRemove', function() {
            $(this).parent().parent().remove();
            $(".interiorDel").eq(-1).text('+ Add');
            $('.interiorDel').eq(-1).attr('class', 'btn btn-primary btn-sm addRowInterior');
        });


        document.getElementById('mainImage').addEventListener('change', function(event) {
            showPreview(event, 'mainImagePreview');
        });
        document.getElementById('clusterPlan').addEventListener('change', function(event) {
            showPreview(event, 'clusterPlanPreview');
        });
        document.getElementById('qr').addEventListener('change', function(event) {
            showPreview(event, 'qrPreview');
        });



        function showPreview(event, previewId) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block'; // Show the image element
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
