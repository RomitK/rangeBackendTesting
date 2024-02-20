@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seller`s Guide Page Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Seller`s Guide Page Content</li>
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
                        action="{{ route('dashboard.pageContents.sellerGuide') }}"
                         enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="sellerGuide">Seller`s Guide</label>
                                            <div class="custom-file   @error('sellerGuide') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="sellerGuide" name="sellerGuide"
                                                accept="pdf/*">
                                                <label class="custom-file-label" for="sellerGuide">Choose file</label>
                                            </div>
                                            @error('sellerGuide')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            
                                            @if ($content->sellerGuide)
                                                <a href="{{ $content->sellerGuide }}" download="">Download Seller`s Guide</a>
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
