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
                        <form class="form-boder" id="storeForm" method="POST" action="{{ route('dashboard.projects.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="developer">Developer</label>
                                            <select class="form-control select1 @error('developer_id') is-invalid @enderror"
                                                id="developer_id" name="developer_id" data-placeholder="Select Developer">
                                                <option></option>
                                                @foreach ($developers as $developer)
                                                    <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('developer_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="main_community_id">Main Community</label>
                                            <div class="input-group">
                                                
                                            <select data-placeholder="Select Community"
                                                class=" form-control select1 @error('main_community_id') is-invalid @enderror"
                                                id="main_community_id" name="main_community_id">
                                                <option></option>
                                               
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
                                            <label for="sub_community_id">Sub Community</label>
                                            <select data-placeholder="Select Community"
                                                class=" form-control select1 @error('sub_community_id') is-invalid @enderror"
                                                id="sub_community_id" name="sub_community_id">
                                                    <option></option>
                                            </select>
                                            @error('sub_community_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
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
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="sub_title">Sub Title (Part1)</label>-->
                                    <!--        <input type="text" value="{{ old('sub_title') }}"-->
                                    <!--            class="form-control @error('sub_title') is-invalid @enderror" id="sub_title"-->
                                    <!--            placeholder="Enter Sub Title" name="sub_title">-->
                                    <!--        @error('sub_title')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="sub_title_1">Sub Title (Part2)</label>-->
                                    <!--        <input type="text" value="{{ old('sub_title_1') }}"-->
                                    <!--            class="form-control @error('sub_title') is-invalid @enderror" id="sub_title_1"-->
                                    <!--            placeholder="Enter Sub Title" name="sub_title_1">-->
                                    <!--        @error('sub_title_1')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-2">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="emirate">Select Emirate</label>-->
                                    <!--        <select class="select1 form-control @error('emirate') is-invalid @enderror"-->
                                    <!--            id="emirate" name="emirate" required>-->
                                    <!--            @foreach (config('constants.emirates') as $key => $value)-->
                                    <!--                <option value="{{ $value }}">{{ $value }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('emirate')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                @foreach (config('constants.statuses') as $key => $value)
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
                                    <!--<div class="col-sm-3">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="is_feature">Is New Lanuch?</label>-->
                                    <!--        <select class="form-control @error('is_new_launch') is-invalid @enderror"-->
                                    <!--            id="is_new_launch" name="is_new_launch">-->
                                    <!--            @foreach (config('constants.booleanOptions') as $key=>$value)-->
                                    <!--            <option value="{{ $key }}">{{ $value }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('is_new_launch')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-3">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="is_feature">Is Featured?</label>-->
                                    <!--        <select class="form-control @error('is_featured') is-invalid @enderror"-->
                                    <!--            id="is_featured" name="is_featured">-->
                                    <!--            @foreach (config('constants.booleanOptions') as $key=>$value)-->
                                    <!--            <option value="{{ $key }}">{{ $value }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('is_featured')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="is_display_home">Is Display on Home Page?</label>
                                            <select class="form-control @error('is_display_home') is-invalid @enderror"
                                                id="is_display_home" name="is_display_home">
                                                @foreach (config('constants.booleanOptions') as $key=>$value)
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
                                    <!--<div class="col-sm-2">-->
                                    <!--    <label for="starting_price">Starting Price</label>-->
                                    <!--    <div class="input-group">-->
                                            
                                    <!--        <input type="text" value="{{ old('starting_price') }}"-->
                                    <!--            class="form-control @error('starting_price') is-invalid @enderror"-->
                                    <!--            id="starting_price" name="starting_price" placeholder="1.2 Million">-->
                                    <!--        @error('starting_price')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                        <!-- /input-group -->
                                    <!--</div>-->

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="completion_date">HandOver</label>
                                            <div class="input-group">
                                               
                                                <input type="text" value="{{ old('completion_date') }}"
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
                                    <!--<div class="col-sm-2">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="bedrooms">Bedrooms</label>-->
                                    <!--        <input type="text" value="{{ old('bedrooms') }}"-->
                                    <!--            class="form-control @error('bedrooms') is-invalid @enderror"-->
                                    <!--            id="bedrooms" name="bedrooms" placeholder="1-4">-->
                                    <!--        @error('bedrooms')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <!--<div class="col-sm-2">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="area">Area </label>-->
                                    <!--        <div class="input-group">-->
                                                
                                    <!--            <input type="text" value="{{ old('area') }}"-->
                                    <!--                class="form-control @error('area') is-invalid @enderror"-->
                                    <!--                id="area" name="area">-->
                                    <!--            @error('area')-->
                                    <!--                <span class="invalid-feedback" role="alert">-->
                                    <!--                    <strong>{{ $message }}</strong>-->
                                    <!--                </span>-->
                                    <!--            @enderror-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="area_unit">Area Unit</label>-->
                                    <!--        <select name="area_unit" id="area_unit"-->
                                    <!--            class="form-control @error('area_unit') is-invalid @enderror">-->
                                    <!--            <option value=" sqft"> sqft</option>-->
                                    <!--        </select>-->
                                    <!--        @error('area_unit')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="accommodation_id">Accommodation</label>
                                            <div class="input-group">
                                                
                                                <div class="overflow-hidden noSideBorder flex-grow-1">
                                                <select  data-placeholder="Select Accommodation" style="width: 100%;"
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
                                    
                                    
                                    
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="amenities">Highlighted Amenities</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Highlighted Amenities"-->
                                    <!--            style="width: 100%;"-->
                                    <!--            class="select2 form-control @error('amenities') is-invalid @enderror"-->
                                    <!--            id="highlight_amenities" name="highlight_amenities[]">-->
                                    <!--            @foreach ($amenities as $amenity)-->
                                    <!--                <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>-->
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
                                    <!--        <label for="amenities">Amenities</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Amenities"-->
                                    <!--            style="width: 100%;"-->
                                    <!--            class="select2 form-control @error('amenities') is-invalid @enderror"-->
                                    <!--            id="amenities" name="amenities[]">-->
                                    <!--            @foreach ($amenities as $amenity)-->
                                    <!--                <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>-->
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
                                    <!--        <label for="categoryIds">Select Tags</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Tags"-->
                                    <!--            style="width: 100%;"-->
                                    <!--            class="select2 form-control @error('tagIds') is-invalid @enderror"-->
                                    <!--            id="tagIds" name="tagIds[]">-->
                                    <!--            @foreach ($tags as $tag)-->
                                    <!--                <option value="{{ $tag->id }}">{{ $tag->name }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('tagIds')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="highlights">Highlights</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Highlights" style="width: 100%;" class="select2 form-control @error('highlightIds') is-invalid @enderror" id="highlights" name="highlightIds[]">-->
                                    <!--            @foreach ($highlights as $highlight)-->
                                    <!--            <option value="{{ $highlight->id }}">{{ $highlight->name }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('highlightIds')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                           <label for="features_description">HIGHTLIGHTS Description</label>
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
                                            <label for="short_description">Short Description<small class="text-danger">(Not more 300 characters)</small></label>
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
                                            <label for="address">Address</label>
                                            <input type="text" value="{{ old('address') }}" id="address-input"
                                                name="address" class="form-control map-input">
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

                                    
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="agent_id">Agent</label>-->
                                    <!--        <select class="form-control select1 @error('agent_id') is-invalid @enderror"-->
                                    <!--            id="agent_id" name="agent_id">-->
                                    <!--            @foreach ($agents as $agent)-->
                                    <!--                <option value="{{ $agent->id }}">{{ $agent->name }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('agent_id')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                   

                                    
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="factsheet">FactSheet</label>-->
                                    <!--        <div class="custom-file  @error('factsheet') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input @error('factsheet') is-invalid @enderror" id="floorPlan"-->
                                    <!--                name="factsheet" accept=".pdf">-->
                                    <!--            <label class="custom-file-label" for="factsheet">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('factsheet')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="paymentPlan">Payment Plan</label>-->
                                    <!--        <div class="custom-file  @error('paymentPlan') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input @error('paymentPlan') is-invalid @enderror" id="paymentPlan"-->
                                    <!--                name="paymentPlan" accept=".pdf">-->
                                    <!--            <label class="custom-file-label" for="paymentPlan">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('paymentPlan')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
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
                                            <label for="meta_keywords">Meta Keywords<small class="text-danger">(Multiple keywords separated with comas)</small></label>
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
                                            <label for="brochure">Brochure <small class="text-danger">(PreferSize  less than or equal 2 MB)</small>  </label>
                                            <div class="custom-file   @error('brochure') is-invalid @enderror">
                                                <input type="file" class="custom-file-input @error('brochure') is-invalid @enderror" id="brochure"
                                                    name="brochure" accept=".pdf">
                                                <label class="custom-file-label" for="brochure">Choose file</label>
                                            </div>
                                            @error('brochure')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="saleOffer">Sale Offer <small class="text-danger">(PreferSize  less than or equal 2 MB)</small>  </label>
                                            <div class="custom-file  @error('saleOffer') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="saleOffer" name="saleOffer" accept=".pdf" >
                                                <label class="custom-file-label" for="saleOffer">Choose file</label>
                                            </div>
                                            @error('saleOffer')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="mainImage">Main Image <small class="text-danger">(Prefer Dimension800x600)</small></label>
                                            <div class="custom-file   @error('mainImage') is-invalid @enderror">
                                                <input type="file" class="custom-file-input   @error('mainImage') is-invalid @enderror" id="mainImage"
                                                    name="mainImage" accept="image/*">
                                                <label class="custom-file-label" for="mainImage">Choose file</label>
                                            </div>
                                            @error('mainImage')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="video">Video</label>-->
                                    <!--        <div class="custom-file   @error('video') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input @error('video') is-invalid @enderror" id="video"-->
                                    <!--                name="video" accept=".mp4, .mov, .ogg">-->
                                    <!--            <label class="custom-file-label" for="video">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('video')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exteriorGallery">Exterior Gallery <small class="text-danger">(Prefer Dimension800x600)</small></label>
                                            <div class="custom-file  @error('exteriorGallery') is-invalid @enderror">
                                                <input type="file" class="custom-file-input @error('exteriorGallery') is-invalid @enderror @error('exteriorGallery.*') is-invalid @enderror" id="exteriorGallery"
                                                    name="exteriorGallery[]" multiple accept="image/*">
                                                <label class="custom-file-label" for="exteriorGallery">Choose file</label>
                                            </div>
                                            @error('exteriorGallery')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @error('exteriorGallery.*')
                                                <span class="invalid-feedback" role="alert" style="display: block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="interiorGallery">Interior Gallery <small class="text-danger">(Prefer Dimension400x400)</small></label>
                                            <div class="custom-file  @error('interiorGallery') is-invalid @enderror">
                                                <input type="file" class="custom-file-input  @error('interiorGallery') is-invalid @enderror @error('interiorGallery.*') is-invalid @enderror" id="interiorGallery"
                                                    name="interiorGallery[]" multiple accept="image/*">
                                                <label class="custom-file-label" for="interiorGallery">Choose file</label>
                                            </div>
                                            @error('interiorGallery')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @error('interiorGallery.*')
                                                <span class="invalid-feedback" role="alert" style="display: block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
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
        
        $('#developer_id').on('change', function(e) {
            var developer_id = e.target.value;
            $.ajax({
                url: "{{ route('dashboard.developers.communities') }}",
                type: "POST",
                data: {
                    developer_id: developer_id
                },
                success: function(data) {
                    $('#main_community_id').empty();
                    $('#main_community_id').append('<option value=""></option>');
                    $.each(data.communities, function(index, community) {
                        $('#main_community_id').append('<option value="' + community.id + '">' + community.name + '</option>');
                    })
                }
            })
        });
        
        $('#main_community_id').on('change', function(e) {
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
                        $('#sub_community_id').append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                    })
                }
            })
        });
    });
</script>
@endsection
