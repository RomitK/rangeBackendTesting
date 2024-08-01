@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Property Listings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/properties') }}">Property Listings</a></li>
                        <li class="breadcrumb-item active">New Listing</li>
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
                            <h3 class="card-title">New Listing Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" method="POST"
                            action="{{ route('dashboard.properties.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="project_id">Project</label>
                                            <select data-placeholder="Select Project" style="width: 100%;"
                                                class="select2 form-control @error('project_id') is-invalid @enderror"
                                                id="project_id" name="project_id" required>
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $value)
                                                    <option value="{{ $value->id }}">{{ $value->title }} (
                                                        {{ $value->developer->name }})</option>
                                                @endforeach
                                            </select>
                                            @error('project_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sub_project_id">Available Unit</label>
                                            <select data-placeholder="Select Available Unit" style="width: 100%;"
                                                class="select2 form-control @error('sub_project_id') is-invalid @enderror"
                                                id="sub_project_id" name="sub_project_id" required>

                                            </select>
                                            @error('sub_project_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="primary_view">Primary View</label>
                                            <input type="text" value="{{ old('primary_view') }}"
                                                class="form-control @error('primary_view') is-invalid @enderror"
                                                id="primary_view" name="primary_view">
                                            @error('primary_view')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Title</label>
                                            <input type="text" value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Enter Name" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="propertyOrder">Property Order</label>
                                            <input type="number" value="{{ old('propertyOrder') }}"
                                                class="form-control @error('propertyOrder') is-invalid @enderror"
                                                id="propertyOrder" name="propertyOrder" min="1">
                                            @error('propertyOrder')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="property_source">Property Source</label>
                                            <select
                                                class="form-control select1 @error('property_source') is-invalid @enderror"
                                                id="property_source" name="property_source">
                                                @foreach (config('constants.propertySources') as $value)
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('property_source')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="website_status">Wesbite Status</label>
                                            @if (in_array(Auth::user()->role, config('constants.isAdmin')))
                                                <select class="form-control @error('website_status') is-invalid @enderror"
                                                    id="website_status" name="website_status">
                                                    @foreach (config('constants.newStatusesWithoutAllOutOfInventory') as $key => $value)
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
                                            <label for="accommodation_id">Property Type</label>
                                            <select
                                                class="form-control select1 @error('accommodation_id') is-invalid @enderror"
                                                id="accommodation_id" name="accommodation_id">
                                                @foreach ($accommodations as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('accommodation_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
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

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="bedrooms">Bedrooms</label>
                                            <select data-placeholder="Select Bedrooms" style="width: 100%;"
                                                class="form-control select1 @error('bedrooms') is-invalid @enderror"
                                                id="bedrooms" name="bedrooms">
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
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="bathrooms">Bathrooms</label>
                                            <input type="number" min="0" value="{{ old('bathrooms') }}"
                                                class="form-control @error('bathrooms') is-invalid @enderror"
                                                id="bathrooms" name="bathrooms">
                                            @error('bathrooms')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="area">Area</label>
                                            <input type="number" min="0" value="{{ old('area') }}"
                                                class="form-control @error('area') is-invalid @enderror" id="area"
                                                name="area">
                                            @error('area')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
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

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="number" min="0" value="{{ old('price') }}"
                                                class="form-control @error('price') is-invalid @enderror" id="price"
                                                name="price">
                                            @error('price')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select
                                                class="form-control select1 @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id">
                                                @foreach ($categories as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="rental_period">Rental Period</label>
                                            <select
                                                class="form-control select1 @error('rental_period') is-invalid @enderror"
                                                id="rental_period" name="rental_period">
                                                <option>Rental Period</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Yearly">Yearly</option>
                                            </select>
                                            @error('rental_period')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
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
                                            <label for="parking_space">Parking Space</label>
                                            <input type="number" min="0" value="{{ old('parking_space') }}"
                                                class="form-control @error('parking_space') is-invalid @enderror"
                                                id="parking_space" name="parking_space">
                                            @error('parking_space')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="exclusive">Is Exclusive?</label>
                                            <select class="form-control select1 @error('exclusive') is-invalid @enderror"
                                                id="exclusive" name="exclusive">
                                                @foreach (config('constants.booleanOptions') as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('exclusive')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="is_luxury_property">Luxury Property?</label>
                                            <select
                                                class="form-control select1 @error('is_luxury_property') is-invalid @enderror"
                                                id="is_luxury_property" name="is_luxury_property">
                                                @foreach (config('constants.booleanOptions') as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('is_luxury_property')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="agent_id">Agent</label>
                                            <select data-placeholder="Select Agent" style="width: 100%;"
                                                class=" form-control select1 @error('agent_id') is-invalid @enderror"
                                                id="agent_id" name="agent_id">
                                                @foreach ($agents as $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('agent_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="is_furniture">Is Furnitue?</label>
                                            <select
                                                class="form-control select1 @error('is_furniture') is-invalid @enderror"
                                                id="is_furniture" name="is_furniture">
                                                @foreach (config('constants.furnitueOption') as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('is_furniture')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Short Description</label>
                                            <textarea id="short_description" class="form-control @error('short_description') is-invalid @enderror"
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
                                            <label for="description">Description</label>
                                            <textarea id="description" class="summernote form-control @error('description') is-invalid @enderror"
                                                name="description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="amenities">Amenities</label>
                                            <select multiple="multiple" data-placeholder="Select Amenities"
                                                style="width: 100%;"
                                                class="select2 form-control @error('amenityIds') is-invalid @enderror"
                                                id="amenities" name="amenityIds[]">
                                                @foreach ($amenities as $amenity)
                                                    <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('amenityIds')
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
                                                name="address" class="form-control map-input">
                                            <input type="hidden" name="address_latitude" id="address-latitude"
                                                value="0" />
                                            <input type="hidden" name="address_longitude" id="address-longitude"
                                                value="0" />

                                            @error('amenties_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div id="address-map-container" style="width:100%;height:400px; ">
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

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="mainImage">Main Image <small class="text-danger">(Prefer Dimension
                                                    600X300)</small> </label>
                                            <div class="custom-file  @error('mainImage') is-invalid @enderror">
                                                <input type="file"
                                                    class="custom-file-input  @error('mainImage') is-invalid @enderror"
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

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="youtube_video">Youtube Video Link</label>
                                            <input type="text" value="{{ old('youtube_video') }}"
                                                class="form-control @error('youtube_video') is-invalid @enderror"
                                                id="youtube_video" placeholder="Enter Youtube Video Link"
                                                name="youtube_video">
                                            @error('youtube_video')
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
                                                            <label for="subImages">Sub Images <small
                                                                    class="text-danger">(Prefer Dimension800x400)</small>
                                                            </label>
                                                        </th>
                                                    </tr>
                                                    <tr style="">
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Order</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @error('subImages')
                                                        <tr>
                                                            <th colspan="4">
                                                                @error('gallery')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                @error('subImages.*')
                                                                    <span class="invalid-feedback" role="alert"
                                                                        style="display: block">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror

                                                            </th>
                                                        </tr>
                                                    @enderror
                                                </thead>

                                                <tr class="item-row" style="border-bottom: solid 1px black">
                                                    <td>
                                                        <div
                                                            class="custom-file   @error('subImages') is-invalid @enderror">
                                                            <input type="file"
                                                                class="custom-file-input @error('subImages') is-invalid @enderror"
                                                                id="subImages" name="subImages[0][file]" accept="image/*"
                                                                onchange="previewImage(event)">
                                                            <label class="custom-file-label" for="subImages">Choose
                                                                file</label>

                                                        </div>
                                                        <img id="image-preview" src="#" alt="Image Preview"
                                                            style="display: none;" height="100" />

                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control @error('key') is-invalid @enderror"
                                                            placeholder="Enter Title" name="subImages[0][title]">
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0"
                                                            class="form-control @error('value') is-invalid @enderror"
                                                            placeholder="Enter Order" name="subImages[0][order]">
                                                    </td>

                                                    <td><a class="btn btn-block btn-primary btn-sm addrow updateRow0"
                                                            href="javascript:;"><i class="fa fa-plus"
                                                                aria-hidden="true"></i> Add</a>
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>

                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="video">Video<small class="text-danger">(PreferSize  less than or equal 2 MB)</small> </label>-->
                                    <!--        <div class="custom-file  @error('video') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input" id="video" name="video" accept=".mp4, .mov, .ogg">-->
                                    <!--            <label class="custom-file-label" for="video">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('video')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->

                                    <!--    </div>-->
                                    <!--</div>-->


                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="qr">QR</label>-->
                                    <!--        <div class="custom-file  @error('qr') is-invalid @enderror">-->
                                    <!--            <input multiple type="file" class="custom-file-input @error('qr') is-invalid @enderror" id="qr"-->
                                    <!--                name="qr" accept="image/*" >-->
                                    <!--            <label class="custom-file-label" for="qr">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('qr')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->



                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="developer_id">Developer</label>-->
                                    <!--        <select data-placeholder="Select Developer" style="width: 100%;"-->
                                    <!--            class=" form-control select1 @error('developer_id')
is-invalid
@enderror"-->
                                    <!--            id="developer_id" name="developer_id">-->
                                    <!--            @foreach ($developers as $value)
    -->
                                    <!--                <option value="{{ $value->id }}">{{ $value->name }}</option>-->
                                    <!--
    @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('developer_id')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->




                                    <!--<div class="col-sm-3">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="year_build_text">Year Build Text</label>-->
                                    <!--        <input type="text" value="{{ old('year_build_text') }}" class="form-control @error('year_build_text') is-invalid @enderror" id="year_build_text" placeholder="Enter Year Build Next" name="year_build_text">-->
                                    <!--        @error('year_build_text')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div> -->


                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="cheque_frequency">Price Unit</label>-->
                                    <!--        <input type="text" value="{{ old('cheque_frequency') }}"-->
                                    <!--            class="form-control @error('cheque_frequency')
is-invalid
@enderror"-->
                                    <!--            id="cheque_frequency" name="cheque_frequency">-->
                                    <!--        @error('cheque_frequency')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="offer_type_id">Offer Type</label>-->
                                    <!--        <select class="form-control select1 @error('offer_type_id') is-invalid @enderror"-->
                                    <!--            id="offer_type_id" name="offer_type_id">-->
                                    <!--            @foreach ($offerTypes as $offerType)
    -->
                                    <!--                <option value="{{ $offerType->id }}">{{ $offerType->name }}</option>-->
                                    <!--
    @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('offer_type_id')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->




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
            $('#community_id').on('change', function(e) {
                var category_id = e.target.value;
                $.ajax({
                    url: "{{ route('dashboard.community.subcommunities') }}",
                    type: "POST",
                    data: {
                        category_id: category_id
                    },
                    success: function(data) {
                        $('#sub_community_id').empty();
                        $.each(data.subcategories, function(index, subcategory) {
                            $('#sub_community_id').append('<option value="' +
                                subcategory.id + '">' + subcategory.name +
                                '</option>');
                        })
                    }
                })
            });
        });

        $('#main_community_id').on('change', function(e) {
            var category_id = e.target.value;
            $.ajax({
                url: "{{ route('dashboard.community.projects') }}",
                type: "POST",
                data: {
                    category_id: category_id
                },
                success: function(data) {
                    $('#project_id').empty();
                    $('#project_id').append('<option value=""></option>');
                    $.each(data.projects, function(index, project) {
                        $('#project_id').append('<option value="' + project.id + '">' + project
                            .title + '</option>');
                    })
                }
            })
        });


        function updateMapWithNewCoords(lat, lng) {
            // Convert lat and lng to numbers and check if they are finite
            const latitude = parseFloat(lat);
            const longitude = parseFloat(lng);

            if (!isFinite(latitude) || !isFinite(longitude)) {
                console.error('Invalid coordinates:', latitude, longitude);
                return; // Or set default coordinates
            }

            const locationInputs = document.getElementsByClassName("map-input");

            for (let i = 0; i < locationInputs.length; i++) {
                const fieldKey = locationInputs[i].id.replace("-input", "");
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

                setLocationCoordinates(fieldKey, latitude, longitude);
                marker.setVisible(true);
            }
        }



        $('#project_id').on('change', function(e) {
            var project_id = e.target.value;

            $.ajax({
                url: "{{ route('dashboard.project.ajax') }}",
                type: "POST",
                data: {
                    project_id: project_id
                },
                success: function(data) {
                    $("#address-input").val(data.project.address)
                    $("#address-latitude").val(data.project.address_latitude)
                    $("#address-longitude").val(data.project.address_longitude)
                    $("#permit_number").val(data.project.permmit_number);
                    $('#amenities').val(data.amenities).trigger('change');
                    updateMapWithNewCoords(data.project.address_latitude, data.project
                        .address_longitude);
                }
            })

            $.ajax({
                url: "{{ route('dashboard.project.subprojects') }}",
                type: "POST",
                data: {
                    project_id: project_id
                },
                success: function(data) {
                    $('#sub_project_id').empty();
                    $('#sub_project_id').append('<option value=""></option>');
                    $.each(data.subProjects, function(index, project) {
                        $('#sub_project_id').append('<option value="' + project.id + '">' +
                            project.title + '</option>');
                    })
                }
            })
        });

        $('#sub_project_id').on('change', function(e) {
            var sub_project_id = e.target.value;
            $.ajax({
                url: "{{ route('dashboard.project.ajax') }}",
                type: "POST",
                data: {
                    project_id: sub_project_id
                },
                success: function(data) {
                    console.log(data.project.starting_price)
                    $("#bedrooms").val(data.project.bedrooms).trigger('change');
                    $("#price").val(data.project.starting_price);
                    $("#area").val(data.project.area).trigger('change');
                    $("#builtup_area").val(data.project.builtup_area).trigger('change');
                    $("#accommodation_id").val(data.project.accommodation_id).trigger('change');

                }
            })
        });
    </script>

    <script type="text/javascript">
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        $(document).on('change', '.file-upload', function() {
            var previewId = $(this).data('target');
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result);
                $('#' + previewId).show();
            };
            reader.readAsDataURL(this.files[0]);
        });


        $(document).ready(function() {
            var i = 1;
            var count = 0;
            $(document).on('click', '.addrow', function() {
                $(this).text('x Remove');
                $(this).attr('class', 'btn btn-danger btn-sm del');
                $(".item-row:last").find('.mybtn').hide();
                i++;
                count++;
                var id = count;

                var newRow = '<tr class="item-row" style="border-bottom: solid 1px black">' +
                    '<td class="main_td">' +
                    '  <input type="file" accept="image/*" class="form-control file-upload" data-target="previewImage' +
                    id + '" name="subImages[' + id + '][file]" required>' +
                    '  <img id="previewImage' + id +
                    '" src="#" alt="Image Preview" style="display: none; max-width: 100px; max-height: 100px;" />' +
                    '</td>' +
                    '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Title" name="subImages[' +
                    id + '][title]"></td>' +
                    '<td class="main_td"><input type="number" min="0" class="form-control" placeholder="Enter Order" name="subImages[' +
                    id + '][order]"></td>' +
                    '<td data-title="Action" class="main_td"> <button type="button" class="btn btn-primary btn-sm addrow" id="updateRow' +
                    id + '">+ Add</button> ' +
                    '  <a class="Remove mybtn btn btn-danger btn-sm" href="javascript:;" title="Remove row"> x Remove</a>' +
                    '</td>' +
                    '</tr>';

                $(".item-row:last").after(newRow);
            });

            $(document).on('click', '.del', function() {
                $(this).parent().parent().remove();
            });
            $(document).on('click', '.Remove', function() {
                $(this).parent().parent().remove();
                $(".del").eq(-1).text('+ Add');
                $('.del').eq(-1).attr('class', 'btn btn-primary btn-sm addrow');
            });
        })


        document.getElementById('mainImage').addEventListener('change', function(event) {
            showPreview(event, 'mainImagePreview');
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
