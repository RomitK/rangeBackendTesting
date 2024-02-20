@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Media</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/articles') }}">Media</a></li>
                        <li class="breadcrumb-item active">New Media</li>
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
                            <h3 class="card-title">New Media Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder"  id="storeForm" method="POST" action="{{ route('dashboard.articles.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter Title" name="title" required >
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="author">Author Name</label>-->
                                    <!--        <input type="text" value="{{ old('author') }}" class="form-control @error('author') is-invalid @enderror" id="author" placeholder="Enter Author Name" name="author" required>-->
                                    <!--        @error('author')-->
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
                                            name="status"required>
                                                @foreach (config('constants.statuses') as $key=>$value)
                                                <option value="{{ $key }}">{{ $value }}</option>
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
                                            <label for="article_type">Media Type</label>
                                            <select class="form-control select1 @error('article_type') is-invalid @enderror" id="article_type"
                                                name="article_type" required>
                                                @foreach (config('constants.mediaTypes') as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                            @error('article_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="article_event">Media Event</label>
                                            <input type="text" value="{{ old('article_event') }}" class="form-control @error('article_event') is-invalid @enderror" id="article_event" placeholder="Enter Media Event" name="article_event" required>
                                            @error('article_event')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="publish_at">Publish Date</label>
                                            <input type="date" value="{{ old('publish_at') }}" class="form-control @error('publish_at') is-invalid @enderror" id="publish_at" name="publish_at" required>
                                            @error('publish_at')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="mediaOrder">Media Order</label>
                                            <input type="number" value="{{ old('mediaOrder') }}"
                                                class="form-control @error('mediaOrder') is-invalid @enderror" id="mediaOrder"
                                                 name="mediaOrder" min="1">
                                            @error('mediaOrder')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                  
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" value="{{ old('meta_title') }}"
                                                class="form-control @error('meta_title') is-invalid @enderror" id="meta_title"
                                                placeholder="Enter Meta Title" name="meta_title">
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
                                            <input type="text" value="{{ old('meta_keywords') }}"
                                                class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords"
                                                placeholder="Enter Meta Keywords" name="meta_keywords">
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
                                                placeholder="Enter Meta Description" name="meta_description">{{ old('meta_description') }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="short_content">Short Content</label>
                                            <textarea class="form-control @error('short_content') is-invalid @enderror" name="short_content"></textarea>
                                            @error('short_content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <textarea id="summernote" class="form-control @error('content') is-invalid @enderror" name="content"></textarea>
                                            @error('content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="mainImage">Main Image<small class="text-danger">(420×400)</small></label>
                                            <div class="custom-file   @error('mainImage') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="mainImage" name="mainImage"  accept="image/*">
                                                <label class="custom-file-label" for="mainImage">Choose file</label>
                                            </div>
                                            <img id="mainImagePreview" style="max-width: 100%; height: 100px; display: none;" />
                                            @error('mainImage')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="additionalImage">Additional Image<small class="text-danger">(800×400)</small></label>
                                            <div class="custom-file   @error('additionalImage') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="additionalImage" name="additionalImage"  accept="image/*">
                                                <label class="custom-file-label" for="additionalImage">Choose file</label>
                                            </div>
                                            <img id="additionalImagePreview" style="max-width: 100%; height: 100px; display: none;" />
                                            @error('additionalImage')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                     <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="article_additional_video">Additional Vedio URL</label>
                                            <input type="text" value="{{ old('article_additional_video') }}"
                                                class="form-control @error('article_additional_video') is-invalid @enderror" id="article_additional_video"
                                                placeholder="Enter Additional Vedio URL" name="article_additional_video">
                                            @error('article_additional_video')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="items" class="table table-no-more table-bordered mb-none ">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">
                                                        <label for="gallery">Gallery<small class="text-danger">(800×400)</small></label>
                                                    </th>
                                                </tr>
                                                <tr style="">
                                                    <th>Image</th>
                                                    <th>Title</th>
                                                    <th>Order</th>
                                                    <th>Action</th>
                                                </tr>
                                                @error('imageGallery')
                                                <tr>
                                                    <th colspan="4">
                                                        @error('gallery')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                        @error('gallery.*')
                                                            <span class="invalid-feedback" role="alert" style="display: block">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                            
                                                    </th>
                                                </tr>
                                                @enderror
                                            </thead>
        
                                            <tr class="item-row" style="border-bottom: solid 1px black">
                                                <td>
                                                    <!--<input type="file" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Installment" name="rows[0][name]" >-->
                                                    
                                                    <div class="custom-file   @error('gallery') is-invalid @enderror">
                                                        <input type="file" class="custom-file-input @error('gallery') is-invalid @enderror" id="gallery" name="gallery[0][file]" accept="image/*" onchange="previewImage(event)">
                                                        <label class="custom-file-label" for="gallery">Choose file</label>
                                                       
                                                    </div>
                                                    <img id="image-preview" src="#" alt="Image Preview" style="display: none;" height="100"/>
                                            
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control @error('key') is-invalid @enderror" placeholder="Enter Title" name="gallery[0][title]" >
                                                </td>
                                                <td>
                                                    <input type="number" min="0" class="form-control @error('value') is-invalid @enderror" placeholder="Enter Order" name="gallery[0][order]" >
                                                </td>
                                               
                                                <td><a class="btn btn-block btn-primary btn-sm addrow updateRow0" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                                </td>
                                            </tr>
        
                                        </table>
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


    $(document).ready(function() {
        var i = 1;
        var count = 0;
        $(document).on('click', '.addrow', function() {
            $(this).text('x Remove');
            $(this).attr('class', 'btn btn-danger btn-sm del');
            $(".item-row:last").find('.mybtn').hide();
            i++;
            count++;
            var id = count;
        
            var newRow = '<tr class="item-row" style="border-bottom: solid 1px black">' +
                         '<td class="main_td">' +
                         '  <input type="file" accept="image/*" class="form-control file-upload" data-target="previewImage' + id + '" name="gallery[' + id +'][file]" required>' +
                         '  <img id="previewImage' + id + '" src="#" alt="Image Preview" style="display: none; max-width: 100px; max-height: 100px;" />' +
                         '</td>' +
                         '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Title" name="gallery[' + id +'][title]"></td>' +
                         '<td class="main_td"><input type="number" min="0" class="form-control" placeholder="Enter Order" name="gallery[' + id +'][order]"></td>' +
                         '<td data-title="Action" class="main_td"> <button type="button" class="btn btn-primary btn-sm addrow" id="updateRow' +  id +  '">+ Add</button> ' +
                         '  <a class="Remove mybtn btn btn-danger btn-sm" href="javascript:;" title="Remove row"> x Remove</a>' +
                         '</td>' +
                         '</tr>';
        
            $(".item-row:last").after(newRow);
        });

        $(document).on('click', '.del', function() {
                $(this).parent().parent().remove();
            });
            $(document).on('click', '.Remove', function() {
                $(this).parent().parent().remove();
                $(".del").eq(-1).text('+ Add');
                $('.del').eq(-1).attr('class', 'btn btn-primary btn-sm addrow');
            });
        })
    
    
        document.getElementById('mainImage').addEventListener('change', function(event) {
            showPreview(event, 'mainImagePreview');
        });
        
        document.getElementById('additionalImage').addEventListener('change', function(event) {
            showPreview(event, 'additionalImagePreview');
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
