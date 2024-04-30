@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Teams</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/agents') }}">Teams</a></li>
                        <li class="breadcrumb-item active">Edit Team</li>
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
                            <h3 class="card-title">Edit Team Form</h3>
                        </div>

                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.agents.update', $agent->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" value="{{ $agent->name }}"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                placeholder="Enter Name" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                @foreach (config('constants.statuses') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if ($agent->status === $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="is_management">Is Management?</label>
                                            <select class="form-control @error('is_management') is-invalid @enderror"
                                                id="is_management" name="is_management">
                                                <option value="1" @if ($agent->is_management == 1) selected @endif>Yes
                                                </option>
                                                <option value="0" @if ($agent->is_management == 0) selected @endif>No
                                                </option>
                                            </select>
                                            @error('is_management')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="orderBy">Order By</label>
                                            <input type="number" value="{{ $agent->orderBy }}" min="0"
                                                class="form-control @error('orderBy') is-invalid @enderror" id="orderBy"
                                                placeholder="Enter orderBy" name="orderBy" required>
                                            @error('orderBy')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" value="{{ $agent->email }}"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                placeholder="Enter Email" name="email" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="designation">Designation</label>
                                            <input type="designation" value="{{ $agent->designation }}"
                                                class="form-control @error('designation') is-invalid @enderror"
                                                id="designation" placeholder="Enter Designation" name="designation"
                                                required>
                                            @error('designation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="employeeId">Employer Id</label>
                                            <input type="employeeId" value="{{ $agent->employeeId }}"
                                                class="form-control @error('employeeId') is-invalid @enderror"
                                                id="employeeId" placeholder="Enter Employer Id" name="employeeId">
                                            @error('employeeId')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="specialization">Specialization</label>-->
                                    <!--        <input type="specialization" value="{{ $agent->specialization }}"-->
                                    <!--            class="form-control @error('specialization')
is-invalid
@enderror" id="specialization"-->
                                    <!--            placeholder="Enter specialization" name="specialization">-->
                                    <!--        @error('specialization')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="contact_number">Contact Number</label>
                                            <input type="text" value="{{ $agent->contact_number }}"
                                                class="form-control @error('contact_number') is-invalid @enderror"
                                                id="contact_number" placeholder="Enter Contact Number"
                                                name="contact_number" required>
                                            @error('contact_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="whatsapp_number">Whatsapp Number</label>
                                            <input type="text" value="{{ $agent->whatsapp_number }}"
                                                class="form-control @error('whatsapp_number') is-invalid @enderror"
                                                id="whatsapp_number" placeholder="Enter Whatsapp Number"
                                                name="whatsapp_number" required>
                                            @error('whatsapp_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="experience">Work Experience</label>-->
                                    <!--        <input type="text" value="{{ $agent->experience }}"-->
                                    <!--            class="form-control @error('experience')
is-invalid
@enderror" id="experience"-->
                                    <!--            placeholder="Enter Work Experience" name="experience">-->
                                    <!--        @error('experience')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="start_working">Start Working</label>-->
                                    <!--        <input type="text" value="{{ $agent->start_working }}"-->
                                    <!--            class="form-control @error('start_working')
is-invalid
@enderror" id="start_working"-->
                                    <!--            placeholder="Enter Start Working" name="start_working">-->
                                    <!--        @error('start_working')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="linkedin_profile">Linkedin Profile</label>-->
                                    <!--        <input type="text" value="{{ $agent->linkedin_profile }}"-->
                                    <!--            class="form-control @error('linkedin_profile')
is-invalid
@enderror" id="linkedin_profile"-->
                                    <!--            placeholder="Enter Linkedin Profile URL" name="linkedin_profile">-->
                                    <!--        @error('linkedin_profile')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="nationality">Nationality</label>-->
                                    <!--        <input type="text" value="{{ $agent->nationality }}"-->
                                    <!--            class="form-control @error('nationality')
is-invalid
@enderror" id="nationality"-->
                                    <!--            placeholder="Enter Nationality" name="nationality">-->
                                    <!--        @error('nationality')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="languageIds">Languages</label>

                                            <select multiple="multiple" data-placeholder="Select Languages"
                                                style="width: 100%;"
                                                class="select2 form-control @error('languageIds') is-invalid @enderror"
                                                id="languageIds" name="languageIds[]">

                                                @foreach ($languages as $language)
                                                    <option value="{{ $language->id }}"
                                                        @if ($agent->languages) @if (in_array($language->id, $agent->languages->pluck('id')->toArray())) selected @endif
                                                        @endif>{{ $language->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('languageIds')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="developerIds">Developers</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Developers" style="width: 100%;" class="select2 form-control @error('developerIds') is-invalid @enderror" id="developerIds" name="developerIds[]">-->
                                    <!--            @foreach ($developers as $developer)
    -->
                                    <!--            <option value="{{ $developer->id }}"-->
                                    <!--                @if (in_array($developer->id, $agent->developers->pluck('id')->toArray()))
    selected
    @endif-->
                                    <!--                >{{ $developer->name }}</option>-->
                                    <!--
    @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('developerIds')
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
                                    <!--        <label for="communityIds">Communities</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Communities" style="width: 100%;" class="select2 form-control @error('communityIds') is-invalid @enderror" id="communityIds" name="communityIds[]">-->
                                    <!--            @foreach ($communities as $community)
    -->
                                    <!--            <option value="{{ $community->id }}"-->
                                    <!--                @if (in_array($community->id, $agent->communities->pluck('id')->toArray()))
    selected
    @endif>{{ $community->name }}</option>-->
                                    <!--
    @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('communityIds')
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
                                    <!--        <label for="projectIds">Projects</label>-->

                                    <!--        <select multiple="multiple" data-placeholder="Select Projects" style="width: 100%;" class="select2 form-control @error('projectIds') is-invalid @enderror" id="projectIds" name="projectIds[]">-->
                                    <!--            @foreach ($projects as $project)
    -->
                                    <!--            <option value="{{ $project->id }}"  @if (in_array($project->id, $agent->projects->pluck('id')->toArray())) selected @endif>{{ $project->title }}</option>-->
                                    <!--
    @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('projectIds')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="logo">Image<small class="text-danger">(Prefer Size
                                                    300x200)</small></label>

                                            <div class="custom-file   @error('image') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="image"
                                                    name="image" accept="image/*">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                            @if ($agent->image)
                                                <img src="{{ $agent->image }}" alt="{{ $agent->name }}"
                                                    height="300">
                                            @endif
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="logo">Seconary Image</label>
                                            <div class="custom-file   @error('additional_image') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="additional_image"
                                                    name="additional_image" accept="image/*">
                                                <label class="custom-file-label" for="additional_image">Choose
                                                    file</label>
                                            </div>
                                            @error('additional_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                            @if ($agent->additional_image)
                                                <img src="{{ $agent->additional_image }}" alt="{{ $agent->name }}"
                                                    height="300">
                                            @endif

                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="video">Video</label>
                                            <div class="custom-file   @error('video') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="video"
                                                    name="video" accept=".mp4, .mov, .ogg">
                                                <label class="custom-file-label" for="video">Choose file</label>
                                            </div>
                                            @error('video')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($agent->video)
                                                @php $path_info = pathinfo($agent->video);@endphp
                                                <video width="200" height="200" autoplay controls>
                                                    <source src="{{ $agent->video }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        </div>
                                    </div>

                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="license_number">License Number</label>-->
                                    <!--        <input type="text" value="{{ $agent->license_number }}"-->
                                    <!--            class="form-control @error('license_number')
is-invalid
@enderror" id="license_number"-->
                                    <!--            placeholder="Enter License Number" name="license_number">-->
                                    <!--        @error('license_number')
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
                                    <!--        <label for="serviceIds">Services</label>-->
                                    <!--        <select multiple="multiple" data-placeholder="Select Services" style="width: 100%;" class="select2 form-control @error('serviceIds') is-invalid @enderror" id="serviceIds" name="serviceIds[]">-->
                                    <!--            @foreach ($services as $service)
    -->
                                    <!--            <option value="{{ $service->id }}"  @if ($agent->services) -->

                                    <!--                @if (in_array($service->id, $agent->services->pluck('id')->toArray())) selected @endif-->
                                    <!--            @endif>{{ $service->name }}</option>-->
                                    <!--
    @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('serviceIds')
        -->
                                        <!--            <span class="invalid-feedback" role="alert">-->
                                        <!--                <strong>{{ $message }}</strong>-->
                                        <!--            </span>-->
                                        <!--
    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" value="{{ $agent->meta_title }}"
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
                                            <input type="text" value="{{ $agent->meta_keywords }}"
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
                                                placeholder="Enter Meta Description" name="meta_description">{{ $agent->meta_description }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="message">Description</label>
                                            <textarea type="text" class="form-control @error('message') is-invalid @enderror" id="summernote"
                                                placeholder="Enter Description" name="message">{{ $agent->message }}</textarea>
                                            @error('message')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{!! $message !!}</strong>
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
@endsection
