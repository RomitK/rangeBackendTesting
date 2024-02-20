@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Video Gallery</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Video Gallery</li>
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
                            <div class="row float-right">

                                    <a href="{{ route('dashboard.video-gallery.create') }}" class="btn btn-block btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Video
                                    </a>

                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Added At</th>
                                        <th>Added By</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($videoGallery as $key => $video)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $video->title }}</td>
                                            <td>
                                                <span
                                                    class="badge @if ($video->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $video->status }}
                                                </span>
                                            </td>
                                            <td>{{ $video->user->name }}</td>
                                            <td>{{ $video->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST" action="{{ route('dashboard.video-gallery.destroy', $video->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                   
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.video-gallery.edit', $video->id) }}">
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
