@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">About Page</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">About Page</li>
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
                <div class="col-6">
                    <div class="card card-primary" style="max-height: 100vh;overflow-y:auto;">
                        <div class="card-header">
                            <h3 class="card-title">About Page Content Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.aboutPage.contents.store') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="')">Introduction Logo</label>
                                            <div class="custom-file   @error('image') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="image"
                                                    name="image"  accept="image/*">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($image)
                                                <img src="{{ $image }}" alt="{{ $image }}" height="100">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="title" value="{{ $title }}"
                                                class="form-control @error('title') is-invalid @enderror" id="title"
                                                placeholder="Enter Title" name="title" required>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="less_description">Less Description</label>
                                            <textarea id="less_description" class="summernote form-control @error('less_description') is-invalid @enderror"
                                                name="less_description">{{ $less_description }}</textarea>
                                            @error('less_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="more_description">More Description</label>
                                            <textarea id="more_description" class="summernote form-control @error('more_description') is-invalid @enderror"
                                                name="more_description">{{ $more_description }}</textarea>
                                            @error('more_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="video">Video Iframe</label>
                                            <textarea id="video_iframe" class=" form-control @error('video_iframe') is-invalid @enderror"
                                                name="video_iframe">{{ $video_iframe }}</textarea>
                                           
                                            @error('video_iframe')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="video">Video</label>
                                            <div class="custom-file  @error('video') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="video"
                                                    name="video" accept=".mp4, .mov, .ogg">
                                                <label class="custom-file-label" for="video">Choose file</label>
                                            </div>
                                            @error('video')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($video)
                                                <video width="200" height="200" autoplay controls>
                                                    <source src="{{ $video }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary ">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-6">
                    <div class="card card-primary" style="max-height: 100vh;overflow-y:auto;">
                        <div class="card-header">
                            <h3 class="card-title">Gallery Section</h3>
                        </div>
                        <form class="form-boder" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.about.gallery.store') }}">
                            @csrf
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($gallery as $galleryImage)

                                        <div class="col-md-9">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <a class="example-image-link" href="{{ $galleryImage['path'] }}"
                                                        data-lightbox="example-set">
                                                        <img src="{{ $galleryImage['path'] }}" height="100">
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-3 append-buttons">
                                            <div class="clearfix">
                                                <a href="{{ route('dashboard.about.gallery.destroy', $galleryImage['id']) }}" class="btn btn-danger w-100">
                                                    <i class="fa fa-times fa-fw"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-md-12">
                                        <div class="dynamic-field">
                                            <div class="row" style="align-items: center;">
                                                <div class="col-md-10" id="dynamic-field-1">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="images">Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input"
                                                                    id="" name="images[]"  accept="image/*">
                                                                <label class="custom-file-label" for="images">Choose
                                                                    file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-25 append-buttons">
                                                    <div class="clearfix" style="margin-top: 10px;">
                                                        <button type="button" id="add-button"
                                                            class="btn btn-secondary w-100">
                                                            <i class="fa fa-plus fa-fw"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">CEO Section</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.ceoPage.contents.store') }}">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="image">CEO Image</label>
                                            <div class="custom-file   @error('image') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" name="image"
                                                accept="image/*">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($ceoImage)
                                                <img src="{{ $ceoImage }}" alt="{{ $ceoImage }}"
                                                    height="150">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">CEO Message Title</label>
                                            <input type="text" value="{{ $ceoMessageTitle }}"
                                                class="form-control @error('itle') is-invalid @enderror" id="title"
                                                placeholder="Enter CEO Message Title" name="title" required>
                                            @error('ceo_message_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="description">CEO Message</label>
                                            <textarea id="description" class="summernote form-control @error('description') is-invalid @enderror"
                                                name="description">{{ $ceoMessage }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="video">CEO Video Iframe</label>
                                            <textarea id="ceo_video_iframe" class=" form-control @error('ceo_video_iframe') is-invalid @enderror"
                                                name="ceo_video_iframe">{{ $ceo_video_iframe }}</textarea>
                                            </div>
                                            @error('ceo_video_iframe')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="video">CEO Video</label>
                                            <div class="custom-file  @error('video') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="ceo_video"
                                                    name="video" accept=".mp4, .mov, .ogg">
                                                <label class="custom-file-label" for="video">Choose file</label>
                                            </div>
                                            @error('video')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($ceoVideo)
                                                <video width="200" height="200" autoplay controls>
                                                    <source src="{{ $ceoVideo }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
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
                    <!-- /.card -->
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Partner Section</h3>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('dashboard.partners.create') }}"
                                        class="btn float-right btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Partner
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Added At</th>
                                        <th>Added By</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partners as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->name }}</td>

                                            <td>
                                                <span
                                                    class="badge @if ($value->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $value->status }}
                                                </span>
                                            </td>
                                            <td>{{ $value->user->name }}</td>
                                            <td>{{ $value->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.partners.destroy', $value->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.partners.edit', $value->id) }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </a>
                                                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#add-button").click(function() {
                html = '<div class="row" style="align-items: center;">\
                                <div class="col-md-10" id="dynamic-field-1">\
                                    <div class="row">\
                                        <div class="col-sm-12">\
                                            <div class="form-group">\
                                                <label for="image">Image</label>\
                                                <div class="custom-file">\
                                                    <input type="file"  class="form-control" name="images[]"  accept="image/*" required/>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="col-md-2 mt-25 append-buttons">\
                                    <div class="clearfix" style="margin-top: 10px;">\
                                        <a href="javascript:void(0);" class="btn btn-danger w-100 remove-row"><i class="fa fa-times fa-fw"></i>\</a>\
                                    </div>\
                                </div>\
                            </div>'
                $(".dynamic-field").before($(html));
            });
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('.row').remove();
        });
    </script>
@endsection
