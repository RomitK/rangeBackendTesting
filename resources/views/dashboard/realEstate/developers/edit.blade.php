@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Developers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/developers') }}">Developers</a></li>
                        <li class="breadcrumb-item active">Edit Developer</li>
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
                            <h3 class="card-title">Edit Developer Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.developers.update', $developer->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" placeholder="Enter Name" name="name"
                                                value="{{ $developer->name }}">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="display_on_home">Display on Home Page</label>
                                            <select
                                                class="form-control select1 @error('display_on_home') is-invalid @enderror"
                                                id="display_on_home" name="display_on_home" required>
                                                <option value="1" @if ($developer->display_on_home === 1) selected @endif>Yes
                                                </option>
                                                <option value="0" @if ($developer->display_on_home === 0) selected @endif>No
                                                </option>
                                            </select>
                                            @error('display_on_home')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="developerOrder">Order By</label>
                                            <input type="number" value="{{ $developer->developerOrder }}" min="0"
                                                class="form-control @error('developerOrder') is-invalid @enderror"
                                                id="developerOrder" placeholder="Enter developerOrder"
                                                name="developerOrder">
                                            @error('developerOrder')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="website_status">Wesbite Status</label>
                                            @if (in_array(Auth::user()->role, config('constants.isAdmin')))
                                                <select class="form-control @error('website_status') is-invalid @enderror"
                                                    id="website_status" name="website_status">
                                                    @foreach (config('constants.newStatusesWithoutAll') as $key => $value)
                                                        <option value="{{ $key }}"
                                                            @if ($developer->website_status == $key) selected @endif>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                                <select class="form-control @error('website_status') is-invalid @enderror"
                                                    id="website_status" name="website_status">
                                                    @foreach (config('constants.approvedRequested') as $key => $value)
                                                        <option value="{{ $key }}"
                                                            @if ($developer->website_status == $key) selected @endif>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            @error('website_status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="logo">Logo<small class="text-danger">(Prefer Size 427x198,
                                                    SVG)</small></label>
                                            <div class="custom-file   @error('logo') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="logo"
                                                    name="logo" accept="image/*">
                                                <label class="custom-file-label" for="logo">Choose file</label>
                                            </div>
                                            @if ($developer->logo)
                                                <img src="{{ $developer->logo }}" alt="{{ $developer->name }}"
                                                    height="198" width="380">
                                            @endif
                                            @error('logo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

 
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="short_description">Description<small class="text-danger">(Not more
                                                    400 characters)</small></label>
                                            <textarea id="short_description" class="summernote form-control @error('short_description') is-invalid @enderror"
                                                name="short_description">{{ $developer->short_description }}</textarea>
                                            @error('short_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" value="{{ $developer->meta_title }}"
                                                class="form-control @error('meta_title') is-invalid @enderror"
                                                id="meta_title" placeholder="Enter Meta Title" name="meta_title">
                                            @error('meta_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords<small class="text-danger">(Multiple
                                                    keywords separated with comas)</small></label>
                                            <input type="text" value="{{ $developer->meta_keywords }}"
                                                class="form-control @error('meta_keywords') is-invalid @enderror"
                                                id="meta_keywords" placeholder="Enter Meta Keywords"
                                                name="meta_keywords">
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
                                                placeholder="Enter Meta Description" name="meta_description">{{ $developer->meta_description }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">

                                        <table id="items" class="table table-no-more table-bordered mb-none ">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">
                                                        <label for="gallery">Gallery<small
                                                                class="text-danger">(1294×600)</small></label>
                                                    </th>
                                                </tr>
                                                <tr style="">
                                                    <th>Image</th>
                                                    <th>Title</th>
                                                    <th>Order</th>
                                                    <th>Action</th>
                                                </tr>
                                                @error('gallery')
                                                    <tr>
                                                        <th colspan="4">
                                                            @error('gallery')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                            @error('gallery.*')
                                                                <span class="invalid-feedback" role="alert"
                                                                    style="display: block">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror

                                                        </th>
                                                    </tr>
                                                @enderror
                                            </thead>

                                            @php

                                                $total_gallery_row_count = count($developer->gallery);
                                                $count = 0;
                                            @endphp

                                            @if (count($developer->gallery) > 0)
                                                @foreach ($developer->gallery as $gallery)
                                                    <tr class="item-row">
                                                        <td>
                                                            <input type="hidden"
                                                                name="gallery[{{ $count }}][old_gallery_id]"
                                                                value="{{ $gallery['id'] }}">
                                                            <img src="{{ $gallery['path'] }}"
                                                                alt="{{ $gallery['path'] }}" width=""
                                                                height="100" style="padding: 10px">
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ $gallery['title'] }}"
                                                                class="form-control" placeholder="Enter Title"
                                                                name="gallery[{{ $count }}][title]">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0"
                                                                value="{{ $gallery['order'] }}" class="form-control"
                                                                placeholder="Enter Order"
                                                                name="gallery[{{ $count }}][order]">
                                                        </td>

                                                        <td>

                                                            <a class="btn btn-sm btn-danger" id="fix_delete"
                                                                onclick="deleteGallery({{ $gallery['id'] }})"
                                                                title="Remove row">X Remove</a>

                                                        </td>
                                                    </tr>
                                                    <script>
                                                        var name = '<?php echo $gallery['id']; ?>';
                                                    </script>

                                                    <?php $count = $count + 1; ?>
                                                @endforeach
                                            @else
                                                <tr class="item-row" style="border-bottom: solid 1px black">
                                                    <td>
                                                        <input type="hidden"
                                                            name="gallery[{{ $count }}][old_gallery_id]"
                                                            value="0">
                                                        <!--<input type="file" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Installment" name="rows[0][name]" >-->

                                                        <div class="custom-file   @error('gallery') is-invalid @enderror">
                                                            <input type="file"
                                                                class="custom-file-input @error('gallery') is-invalid @enderror"
                                                                id="gallery" name="gallery[0][file]" accept="image/*"
                                                                onchange="previewImage(event)">
                                                            <label class="custom-file-label" for="gallery">Choose
                                                                file</label>
                                                        </div>
                                                        <img id="image-preview" src="#" alt="Image Preview"
                                                            style="display: none;" height="100" />

                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control @error('key') is-invalid @enderror"
                                                            placeholder="Enter Title" name="gallery[0][title]">
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0"
                                                            class="form-control @error('value') is-invalid @enderror"
                                                            placeholder="Enter Order" name="gallery[0][order]">
                                                    </td>

                                                    <td>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr id="hiderow">
                                                <td colspan="16">
                                                    <a id="addrow" href="javascript:;"class="btn btn-info btn-sm"
                                                        title="Add a row">Add New</a>
                                                </td>
                                            </tr>
                                        </table>

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
            var count = <?php echo $total_gallery_row_count; ?> + 1;
            $("#addrow").click(function() {
                i++;
                count++;
                var id = count;
                $(".item-row:last").after('<tr class="item-row" style="border-bottom: solid 1px black">' +
                    '<td class="main_td">' +
                    '<input type="file" accept="image/*" class="form-control file-upload" data-target="previewImage' +
                    id + '" name="gallery[' + id + '][file]" required>' +
                    '<img id="previewImage' + id +
                    '" src="#" alt="Image Preview" style="display: none; max-width: 100px; max-height: 100px;" />' +
                    '</td>' +
                    '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Title" name="gallery[' +
                    id + '][title]" required></td>' +
                    '<td class="main_td"><input type="number" min="0" class="form-control" placeholder="Enter Order" name="gallery[' +
                    id + '][order]"></td>' +
                    '<td data-title="Action" class="main_td">' +
                    '<a class="Remove mybtn btn btn-danger btn-sm" href="javascript:;"  title="Remove row"> x Remove</a>' +
                    '</td><input type="hidden"  name="gallery[' + id +
                    '][old_gallery_id]" value="0"></tr>');
                if ($(".delete").length > 0) $(".delete").show();
            });

            $(document).on('click', '.delete', function() {
                $(this).parents('.item-row').remove();
                if ($(".delete").length < 1) $(".delete").hide();

            });
            $(document).on('click', '.Remove', function() {
                $(this).parent().parent().remove();
                $(".del").eq(-1).text('+ Add');
                $('.del').eq(-1).attr('class', 'btn btn-primary btn-sm addrow');
            });

        });

        function deleteGallery(id) {
            var developerId = {{ $developer->id }};
            if (confirm('This Data is saved ! Are you sure you want to delete this?')) {
                $.ajax({

                    url: '{{ url('/') }}/dashboard/developers/' + developerId + '/media/' + id,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    type: 'GET',
                    success: function(result) {
                        console.log(result);
                        location.reload();

                    }
                });
            }
        }
    </script>
@endsection
