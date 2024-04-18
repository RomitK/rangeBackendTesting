@extends('dashboard.layout.index')
@section('breadcrumb')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Property Meta Details</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('dashboard/properties') }}">Properties</a></li>
                    <li class="breadcrumb-item active">Property Meta Details</li>
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
                                    <th>Reference Number</th>
                                    <th>Status</th>
                                    <th>Approval Status</th>
                                    <th>Approval By</th>
                                    <th>Added By</th>
                                    <th>Last Updated By</th>
                                    <th>Added At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $property->name }}</td>
                                    <td>{{ $property->reference_number }}</td>
                                    <td>
                                        <span class="badge @if ($property->status === 'active') bg-success @else bg-danger @endif">
                                            {{ $property->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                                    @if ($property->is_approved === config('constants.requested')) bg-info 
                                                    @elseif($property->is_approved === config('constants.approved')) bg-success 
                                                    @elseif($property->is_approved === config('constants.rejected'))  bg-danger @endif">

                                            @if($property->is_approved == config('constants.requested'))
                                            Requested
                                            @elseif($property->is_approved === config('constants.approved'))
                                            Approved
                                            @elseif($property->is_approved === config('constants.rejected'))
                                            Rejected
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $property->approval ? $property->approval->name: '' }}</td>
                                    <td>{{ $property->user ? $property->user->name:'' }}</td>
                                    <td>
                                        @if($property->updatedBy)
                                        {{ $property->updatedBy->name }} ({{ $property->formattedUpdatedAt }})

                                        @endif
                                    </td>
                                    <td>{{ $property->formattedCreatedAt }}</td>
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
                        <h3 class="card-title">Meta Detail Form</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-boder" id="storeForm" method="POST" action="{{ route('dashboard.property.meta.store', $property->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="meta_title">Slug <small class="text-danger">(Example: {{ Str::slug($property->name) }})</small></label>
                                        <input type="text" value="{{ $property->slug }}" class="form-control @error('slug') is-invalid @enderror" id="slug" placeholder="Enter Slug" name="slug">
                                        @error('slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" value="{{ $property->meta_title }}" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" placeholder="Enter Meta Title" name="meta_title">
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
                                        <input type="text" value="{{ $property->meta_keywords }}" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" placeholder="Enter Meta Keywords" name="meta_keywords">
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
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" placeholder="Enter Meta Description" name="meta_description">{{ $property->meta_description }}</textarea>
                                        @error('meta_description')
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