@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ config('constants.'.$page.'.title') }} Page Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url(config('constants.'.$page.'.url')) }}">{{ config('constants.'.$page.'.title') }} Page Content</a></li>
                        <li class="breadcrumb-item active">New {{ config('constants.'.$page.'.title') }} Page Content</li>
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
                            <h3 class="card-title">New {{ config('constants.'.$page.'.title') }} Page Content Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" method="POST"
                        action="{{ route('dashboard.contents.store') }}"
                         enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ config('constants.'.$page.'.name') }}" name="page_name">
                            <div class="card-body">
                                <div class="row">
                                    @if($page == config('constants.termCondition.name') || $page == config('constants.privacyPolicy.name'))
                                    @else
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <div class="custom-file   @error('image') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="image" name="image"
                                                accept="image/*">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter Title" name="title" required>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea id="description"
                                                class="summernote form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                                            @error('description')
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
