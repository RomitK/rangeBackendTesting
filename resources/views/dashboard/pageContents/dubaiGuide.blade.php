@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dubai`s Guide Page Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Dubai`s Guide Page Content</li>
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
                        action="{{ route('dashboard.pageContents.dubaiGuideStore') }}"
                         enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="goldenVisa">Golden Visa`s Guide</label>
                                            <div class="custom-file   @error('goldenVisa') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="goldenVisa" name="goldenVisa"
                                                accept="pdf/*">
                                                <label class="custom-file-label" for="goldenVisa">Choose file</label>
                                            </div>
                                            @error('goldenVisa')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($content->goldenVisa)
                                                <a href="{{ $content->goldenVisa }}" download="">Download Golden Visa`s Guide</a>
                                            @endif
                                            
                                            
                                    
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="buyerGuide">Buyer`s Guide</label>
                                            <div class="custom-file   @error('buyerGuide') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="buyerGuide" name="buyerGuide"
                                                accept="pdf/*">
                                                <label class="custom-file-label" for="buyerGuide">Choose file</label>
                                            </div>
                                            @error('buyerGuide')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($content->buyerGuide)
                                                <a href="{{ $content->buyerGuide }}" download="">Download Buyer`s Guide</a>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="propertiesGuide">Luxury Properties`s Guide</label>
                                            <div class="custom-file   @error('propertiesGuide') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="propertiesGuide" name="propertiesGuide"
                                                accept="pdf/*">
                                                <label class="custom-file-label" for="propertiesGuide">Choose file</label>
                                            </div>
                                            @error('propertiesGuide')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($content->propertiesGuide)
                                                <a href="{{ $content->propertiesGuide }}" download="">Download Luxury Properties`s Guide</a>
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
