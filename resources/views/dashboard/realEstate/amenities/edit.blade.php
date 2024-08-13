@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Amenities</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/amenities') }}">Amenities</a></li>
                        <li class="breadcrumb-item active">Edit Amenity</li>
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
                            <h3 class="card-title">Edit Amenity Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.amenities.update', $amenity->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Name <small class="text-danger">(Not More than 2 Word)</small> </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" placeholder="Enter Name" name="name" value="{{ $amenity->name }}">
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
                                            <select class="form-control select1 @error('status') is-invalid @enderror" id="status" name="status">
                                                @foreach (config('constants.statuses') as $key=>$value)
                                                    <option value="{{ $key }}" @if ($amenity->status === $key) selected @endif>{{ $value }}</option>
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
                                            <label for="is_approved">Approval Status</label>
                                             @if(in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRejected') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $amenity->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRequested') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $amenity->is_approved) selected @endif>{{ $value }}</option>
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
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="logo">Blue Icon<small class="text-danger">(Color Code: #2878bc, Prefer Size 60x60)</small></label>
                                            <div class="custom-file   @error('image') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                            @if($amenity->image )
                                            <img src="{{ $amenity->image }}" alt="{{ $amenity->name }}">
                                            @endif
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="image1">Orange Icon<small class="text-danger">(color Code:#F58120, Prefer Size 60x60)</small></label>
                                            <div class="custom-file   @error('image1') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="image1" name="image1" accept="image/*">
                                                <label class="custom-file-label" for="image1">Choose file</label>
                                            </div>
                                            @if($amenity->image1 )
                                            <img src="{{ $amenity->image1 }}" alt="{{ $amenity->name }}">
                                            @endif
                                            @error('image1')
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
