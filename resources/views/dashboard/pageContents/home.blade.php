@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Home Page Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Home Page Content</li>
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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Content</h3>
                                </div>
                               
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                        <form class="form-boder" id="storeForm" method="POST"
                        action="{{ route('dashboard.pageContents.homeStore') }}"
                         enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="brochure">About Dubai Brochure</label>
                                            <div class="custom-file   @error('brochure') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="brochure" name="brochure"
                                                accept="pdf/*">
                                                <label class="custom-file-label" for="brochure">Choose file</label>
                                            </div>
                                            @error('brochure')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($content->brochure)
                                                <a href="{{ $content->brochure }}" download="">Download About Dubai Brochure</a>
                                            @endif
                                            
                                            
                                    
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="our_profile">Our Profile</label>
                                            <div class="custom-file   @error('our_profile') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="our_profile" name="our_profile"
                                                accept="pdf/*">
                                                <label class="custom-file-label" for="our_profile">Choose file</label>
                                            </div>
                                            @error('our_profile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($content->our_profile)
                                                <a href="{{ $content->our_profile }}" download="">Download our_profile</a>
                                            @endif
                                            
                                            
                                    
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
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

            </div>

        </div>
    </section>
@endsection
