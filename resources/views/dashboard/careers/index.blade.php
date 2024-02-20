@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Career Mangement</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Career Management</li>
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
                                <div class="col text-right" >
                                    <a class="btn btn-success " href="{{ route('dashboard.careers.allApplicants') }}">
                                        <i class="fas fa-users"></i>
                                        Applicants({{ $applicants }})
                                    </a>
                                    <a href="{{ route('dashboard.careers.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Job Post
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
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Added By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($careers as $key => $career)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $career->position }}</td>

                                            <td>
                                                <span
                                                    class="badge @if ($career->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $career->status }}
                                                </span>
                                            </td>
                                            <td>{{ $career->user->name }}</td>
                                            <td>{{ $career->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.careers.destroy', $career->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.careers.edit', $career->id) }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </a>
                                                    <a class="btn btn-warning btn-sm" target="_blanket"
                                                        href="{{ config('app.frontend_url')."careers/".$career->slug }}">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                    
                                                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                    <a class="btn btn-success btn-sm"
                                                        href="{{ url('dashboard/careers/' . $career->id . '/applicants') }}">
                                                        <i class="fas fa-users"></i>
                                                        Applicants({{ $career->applicants->count() }})
                                                    </a>
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
