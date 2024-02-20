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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects', $project->id) }}">Available Units</a></li>
                        <li class="breadcrumb-item active">New Available Unit</li>
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
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Added At</th>
                                        <th>Added By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $project->title }}</td>
                                        <td>
                                            <span
                                                class="badge @if ($project->status === 'active') bg-success @else bg-danger @endif">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td>{{ $project->user->name }}</td>
                                        <td>{{ $project->formattedCreatedAt }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
           
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">New Available Unit Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" method="POST" action="{{ route('dashboard.projects.subProjects.store', $project->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">Unit Type</label>
                                            <input type="text" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter Name" name="title" required>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="status"
                                                name="status">
                                                @foreach (config('constants.statuses') as $key=>$value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="list_type">Type</label>
                                            <select class="form-control @error('list_type') is-invalid @enderror" id="list_type"
                                                name="list_type">
                                                @foreach (config('constants.listTypes') as $key=>$value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('list_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="accommodation_id">Property Type</label>
                                            <div class="input-group">
                                                
                                                <div class="overflow-hidden noSideBorder flex-grow-1">
                                                    <select   data-placeholder="Select Accommodation" style="width: 100%;" class="select2 form-control @error('accommodation_id') is-invalid @enderror" id="accommodation_id" name="accommodation_id">
                                                        @foreach ($accommodations as $accommodation)
                                                        <option value="{{ $accommodation->id }}">{{ $accommodation->name }}</option>
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
                                                class="form-control select1 @error('bedrooms') is-invalid @enderror"
                                                id="bedrooms" name="bedrooms" required>
                                                @foreach ($bedrooms as $value)
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <!--<input type="number" min="0"  value="{{ old('bedrooms') }}" class="form-control @error('bedrooms') is-invalid @enderror" id="bedrooms"  name="bedrooms" placeholder="1" required>-->
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
                                                    class="form-control @error('area') is-invalid @enderror"
                                                    id="area" name="area" required>
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
                                            <label for="builtup_area">Build-up Area <small>(For villa property type must enter the buil-up area)</small> </label>
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
                                    
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="area_unit">Area Unit</label>-->
                                    <!--        <select name="area_unit" id="area_unit" class="form-control @error('area_unit') is-invalid @enderror">-->
                                    <!--            <option value=" sqft"> sqft</option>-->
                                    <!--        </select>-->
                                    <!--        @error('area_unit')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="starting_price">Price</label>
                                            <input type="text" value="{{ old('starting_price') }}" class="form-control @error('starting_price') is-invalid @enderror" id="starting_price"  name="starting_price" required>
                                            @error('starting_price')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                     <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="floorPlan">Floor Plan <small class="text-danger">(Prefer Size 600x400)</small></label>
                                            <div class="custom-file   @error('floorPlan') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="floorPlan" name="floorPlan[]" accept="image/*" multiple required>
                                                <label class="custom-file-label" for="floorPlan">Choose file</label>
                                            </div>
                                            @error('floorPlan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="amenities">Amenities</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Amenities" style="width: 100%;" class="select2 form-control @error('amenities') is-invalid @enderror" id="amenities" name="amenities[]">-->
                                    <!--            @foreach ($amenities as $amenity)-->
                                    <!--            <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('amenities')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="short_description">Description</label>-->
                                    <!--        <textarea id="short_description" class="summernote form-control @error('short_description') is-invalid @enderror" name="short_description"></textarea>-->
                                    <!--        @error('short_description')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                
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
