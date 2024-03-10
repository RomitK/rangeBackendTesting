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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.pageContents.dubaiGuide-page') }}">Guides</a></li>
                    <li class="breadcrumb-item active">Edit Guide</li>
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
                        <h3 class="card-title">New Guide Form</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-boder" method="POST" action="{{ route('dashboard.guides.update', [$page, $guide->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" value="{{ config('constants.'.$page.'.name') }}" name="page_name">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" value="{{ $guide->title }}" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter Title" name="title" required>
                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="orderBy">Order By</label>
                                        <input type="number" value="{{ $guide->orderBy }}" min="0" class="form-control @error('orderBy') is-invalid @enderror" id="orderBy" placeholder="Enter orderBy" name="orderBy">
                                        @error('orderBy')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="crm_sub_source_id">CRM Subsource Id</label>
                                        <input type="number" value="{{ $guide->crm_sub_source_id }}" min="0" class="form-control @error('crm_sub_source_id') is-invalid @enderror" id="crm_sub_source_id" placeholder="Enter CRM SubSorce Id" name="crm_sub_source_id">
                                        @error('crm_sub_source_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control select1 @error('status') is-invalid @enderror" id="status" name="status">
                                            @foreach (config('constants.statuses') as $key=>$value)
                                            <option value="{{ $key }}" @if ($guide->status === $key) selected @endif>{{ $value }}</option>
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
                                        <label for="guideFile">Guide File</label>
                                        <div class="custom-file   @error('guideFile') is-invalid @enderror">
                                            <input type="file" class="custom-file-input" id="guideFile" name="guideFile" accept="pdf/*">
                                            <label class="custom-file-label" for="guideFile">Choose file</label>
                                        </div>
                                        @error('guideFile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                        @if ($guide->guideFile)
                                        <a href="{{ $guide->guideFile}}" download="" target="_blank">Download File</a>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ $guide->description }}</textarea>
                                        @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="categoryIds">Select Tags</label>
                                        <select multiple="multiple" data-placeholder="Select Tags" style="width: 100%;" class="search_select form-control @error('tagIds') is-invalid @enderror" id="tagIds" name="tagIds[]">
                                            @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}" @if(in_array($tag->id, $guide->tags()->pluck('tag_category_id')->toArray())) selected @endif >{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('tagIds')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="sliderImage">Slider Image</label>
                                        <div class="custom-file   @error('sliderImage') is-invalid @enderror">
                                            <input type="file" class="custom-file-input  @error('sliderImage') is-invalid @enderror" id="sliderImage" name="sliderImage" accept="image/*">
                                            <label class="custom-file-label" for="sliderImage">Choose file</label>
                                        </div>
                                        <img id="sliderImagePreview" style="max-width: 100%; height: 100px; display: none;" />
                                        @error('sliderImage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if ($guide->sliderImage)
                                        <img src="{{ $guide->sliderImage }}" alt="{{ $guide->sliderImage }}" height="200">
                                        @endif
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="featureImage">Feature Image</label>
                                        <div class="custom-file   @error('featureImage') is-invalid @enderror">
                                            <input type="file" class="custom-file-input  @error('featureImage') is-invalid @enderror" id="featureImage" name="featureImage" accept="image/*">
                                            <label class="custom-file-label" for="featureImage">Choose file</label>
                                        </div>
                                        <img id="featureImagePreview" style="max-width: 100%; height: 100px; display: none;" />
                                        @error('featureImage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if ($guide->featureImage)
                                        <img src="{{ $guide->featureImage }}" alt="{{ $guide->featureImage }}" height="200">
                                        @endif
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

@section('js')
<script type="text/javascript">
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    $(document).on('change', '.file-upload', function() {
        var previewId = $(this).data('target');
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + previewId).attr('src', e.target.result);
            $('#' + previewId).show();
        };
        reader.readAsDataURL(this.files[0]);
    });

    document.getElementById('featureImage').addEventListener('change', function(event) {
        showPreview(event, 'featureImagePreview');
    });

    document.getElementById('sliderImage').addEventListener('change', function(event) {
        showPreview(event, 'sliderImagePreview');
    });

    function showPreview(event, previewId) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById(previewId);
            output.src = reader.result;
            output.style.display = 'block'; // Show the image element
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection