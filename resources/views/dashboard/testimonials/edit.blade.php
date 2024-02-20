@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Testimonials</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/testimonials') }}">Testimonials</a></li>
                        <li class="breadcrumb-item active">Edit Testimonial</li>
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
                            <h3 class="card-title">Edit Testimonial Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" files="true" method="POST" enctype="multipart/form-data" action="{{ route('dashboard.testimonials.update',$testimonial->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Client Name</label>
                                            <input type="text" value="{{ $testimonial->client_name }}"
                                                class="form-control @error('client_name') is-invalid @enderror" id="client_name"
                                                placeholder="Enter Client Name" name="client_name" required>
                                            @error('client_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="logo">Client Image</label>-->
                                    <!--        <div class="custom-file   @error('image') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input  @error('image') is-invalid @enderror" id="image" name="image"-->
                                    <!--            accept="image/*">-->
                                    <!--            <label class="custom-file-label" for="image">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        <img src="{{ $testimonial->image }}" alt="{{ $testimonial->name }}" style="width:100px;height:100px;object-fit:cover;">-->
                                    <!--        @error('image')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control select1 @error('status') is-invalid @enderror" id="status"
                                                name="status">
                                                @foreach (config('constants.statuses') as $key=>$value)
                                                <option value="{{ $key }}" @if ($testimonial->status === $key) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="is_approved">Approval Status</label>
                                             @if(in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRejected') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $testimonial->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRequested') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $testimonial->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @error('is_approved')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="agent_id">Agent Name</label>-->
                                    <!--        <select data-placeholder="Select Agent" style="width: 100%;" class=" form-control select1 @error('agent_id') is-invalid @enderror" id="agent_id" name="agent_id" required>-->
                                    <!--            @foreach ($agents as $agent)-->
                                    <!--            <option value="{{ $agent->id }}" @if ($testimonial->agent_id === $agent->id) selected @endif>{{ $agent->name }}</option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->
                                    <!--        @error('agent_id')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="rating">Rating</label>
                                            <select data-placeholder="Select Rating" style="width: 100%;" class=" form-control select1 @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                                                @foreach (config('constants.rating') as $value)
                                                <option value="{{ $value }}" @if ($testimonial->rating == $value)  selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('rating')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="name">Testimonial Title</label>-->
                                    <!--        <input type="text" value="{{ $testimonial->feedback_title }}"-->
                                    <!--            class="form-control @error('feedback_title') is-invalid @enderror" id="feedback_title"-->
                                    <!--            placeholder="Enter Testimonial Title" name="feedback_title" required>-->
                                    <!--        @error('feedback_title')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="feedback">Feedback</label>
                                            <textarea type="text" class="form-control @error('feedback') is-invalid @enderror" id="feedback" placeholder="Enter Feedback" rows="4" name="feedback">{{ $testimonial->feedback }}</textarea>
                                            @error('feedback')
                                                <span class="invalid-feedback" role="alert">
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
@endsection
