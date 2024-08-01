@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Available Units</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/projects') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('dashboard.projects.subProjects', $project->id) }}">Available Units</a></li>
                        <li class="breadcrumb-item active">Edit Available Unit</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('dashboard.realEstate.projects.singleRow')
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Available Unit Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" method="POST" id="storeForm"
                            action="{{ route('dashboard.projects.subProjects.update', [$project->id, $subProject]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Unit Type</label>
                                            <input type="text" value="{{ $subProject->title }}"
                                                class="form-control @error('title') is-invalid @enderror" id="title"
                                                placeholder="Enter Name" name="title" required>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="website_status">Wesbite Status</label>
                                            @if (in_array(Auth::user()->role, config('constants.isAdmin')))
                                                <select class="form-control @error('website_status') is-invalid @enderror"
                                                    id="website_status" name="website_status">
                                                    @foreach (config('constants.newStatusesWithoutAll') as $key => $value)
                                                        <option value="{{ $key }}"
                                                            @if ($subProject->website_status == $key) selected @endif>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                                <select class="form-control @error('website_status') is-invalid @enderror"
                                                    id="website_status" name="website_status">
                                                    @foreach (config('constants.approvedRequested') as $key => $value)
                                                        <option value="{{ $key }}"
                                                            @if ($subProject->website_status == $key) selected @endif>
                                                            {{ $value }}</option>
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



                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="list_type">Type</label>
                                            <select class="form-control @error('list_type') is-invalid @enderror"
                                                id="list_type" name="list_type">
                                                @foreach (config('constants.listTypes') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if ($subProject->list_type == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('list_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="accommodation_id">Property Type</label>
                                            <div class="input-group">

                                                <div class="overflow-hidden noSideBorder flex-grow-1">
                                                    <select data-placeholder="Select Accommodation" style="width: 100%;"
                                                        class="select2 form-control @error('accommodation_id') is-invalid @enderror"
                                                        id="accommodation_id" name="accommodation_id">
                                                        @foreach ($accommodations as $accommodation)
                                                            <option value="{{ $accommodation->id }}"
                                                                @if ($accommodation->id == $subProject->accommodation_id) selected @endif>
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
                                            <select data-placeholder="Select Bedrooms" style="width: 100%;"
                                                class="form-control select1 @error('bedrooms') is-invalid @enderror"
                                                id="bedrodoms" name="bedrooms" required>
                                                @foreach (config('constants.bedrooms') as $value)
                                                    <option value="{{ $value }}"
                                                        @if ($subProject->bedrooms == $value) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>

                                            <!--<input type="number" min="0"  value="{{ $subProject->bedrooms }}" class="form-control @error('bedrooms') is-invalid @enderror" id="bedrooms"  name="bedrooms" placeholder="1" required>-->
                                            @error('bedrooms')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="area">Area</label>
                                            <div class="input-group">

                                                <input type="number" min="0" value="{{ $subProject->area }}"
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

                                                <input type="text" value="{{ $subProject->builtup_area }}"
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

                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="area_unit">Area Unit</label>-->
                                    <!--        <select name="area_unit" id="area_unit" class="form-control @error('area_unit') is-invalid @enderror">-->
                                    <!--            <option value=" sqft"> sqft</option>-->
                                    <!--        </select>-->
                                    <!--        @error('area_unit')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="starting_price">Starting Price</label>
                                            <input type="text" value="{{ $subProject->starting_price }}"
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
                                            <label for="floorPlan">Floor Plan (Prefer Size 600x400)</label>
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

                                            @if ($subProject->floorPlan)
                                                <br><br>
                                                <!--@if (count($subProject->floorPlan) > 1)
    -->
                                                <!--    <a class="btn btn-danger btn-sm float-right"-->
                                                <!--    onclick="return confirm('Are you sure to delete all images?')"-->
                                                <!--    href="{{ route('dashboard.subProjects.floorplansDestroy', [$project->id, $subProject->id]) }}">-->
                                                <!--        <i class="fas fa-trash"></i>-->
                                                <!--        Delete FloorPlan Images-->
                                                <!--    </a>-->
                                                <!--
    @endif-->
                                                <div class="row">

                                                </div>
                                                @foreach ($subProject->floorPlan as $img)
                                                    <div class="col-md-6">
                                                        <div class="image-area">
                                                            <img src="{{ $img['path'] }}" alt="{{ $img['path'] }}"
                                                                width="" height="100" style="padding: 10px">
                                                            <a class="remove-image"
                                                                onclick="return confirm('Are you sure to delete the image?')"
                                                                href="{{ route('dashboard.subProjects.floorplanDestroy', [$project->id, $subProject->id, $img['id']]) }}"
                                                                style="display: inline;">&#215;</a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>

                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="amenities">Amenities</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Amenities" style="width: 100%;" class="select2 form-control @error('amenities') is-invalid @enderror" id="amenities" name="amenities[]">-->
                                    <!--            @foreach ($amenities as $amenity)
    -->
                                    <!--            <option value="{{ $amenity->id }}" @if (in_array($amenity->id, $subProject->amenities->pluck('id')->toArray())) selected @endif>{{ $amenity->name }}</option>-->
                                    <!--
    @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('amenities')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="short_description">Description</label>-->
                                    <!--        <textarea id="short_description" class="summernote form-control @error('short_description') is-invalid @enderror"
                                        name="short_description">{{ $subProject->short_description }}</textarea>-->
                                    <!--        @error('short_description')
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
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
