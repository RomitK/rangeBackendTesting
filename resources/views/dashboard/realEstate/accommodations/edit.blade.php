@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Property Types</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/accommodations') }}">Property Types</a></li>
                        <li class="breadcrumb-item active">Edit Property Type</li>
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
                            <h3 class="card-title">Edit Property Type Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.accommodations.update', $accommodation->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Name" name="name" value="{{ $accommodation->name }}" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control select1 @error('status') is-invalid @enderror" id="status"
                                                name="status">
                                                @foreach (config('constants.statuses') as $key=>$value)
                                                    <option value="{{ $key }}" @if ($accommodation->status === $key) selected @endif>{{ $value }}</option>
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
                                            <label for="type">Type</label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="type" name="type">
                                                @foreach (config('constants.accommodationType') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $accommodation->type) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                   
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="is_approved">Approval Status</label>
                                             @if(in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRejected') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $accommodation->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRequested') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $accommodation->is_approved) selected @endif>{{ $value }}</option>
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
                                   
                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="image">Logo<small class="text-danger">(Prefer Size 60x30)</small></label>-->
                                    <!--        <div class="custom-file   @error('image') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">-->
                                    <!--            <label class="custom-file-label" for="image">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @if($accommodation->image)-->
                                    <!--        <img src="{{ $accommodation->image }}" alt="{{ $accommodation->name }}">-->
                                    <!--        @endif-->
                                    <!--        @error('image')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
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
@endsection
