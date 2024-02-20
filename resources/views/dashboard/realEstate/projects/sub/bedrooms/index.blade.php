@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project({{ $project->title }})</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/projects') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects', $project->id) }}">Sub Projects</a></li>
                        <li class="breadcrumb-item active">Project Bedrooms</li>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Added By</th>
                                        <th>Added At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $subProject->title }}</td>
                                        <td>
                                            <span
                                                class="badge @if ($subProject->status === 'active') bg-success @else bg-danger @endif">
                                                {{ $subProject->status }}
                                            </span>
                                        </td>
                                        <td>{{ $subProject->user->name }}</td>
                                        <td>{{ $subProject->formattedCreatedAt }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <a href="{{ route('dashboard.projects.subProjects.bedrooms.create', [$project->id, $subProject->id]) }}" class="btn btn-block btn-primary">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    New Bedroom Info
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Bedroom</th>
                                        <th>Bathroom</th>
                                        <th>Area</th>
                                        <th>Status</th>
                                        <th>Added By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($subProject->projectBedrooms as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->bedroom_number }}</td>
                                            <td>{{ $value->bathroom_number }}</td>
                                            <td>{{ $value->area }}</td>
                                            <td>
                                                <span
                                                    class="badge @if ($project->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $project->status }}
                                                </span>
                                            </td>
                                            <td>{{ $value->user->name }}</td>
                                            <td>{{ $value->formattedCreatedAt }}</td>

                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.projects.subProjects.bedrooms.destroy', [$project->id, $subProject->id, $value->id]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-secondary btn-sm"
                                                        href="{{ route('dashboard.projects.subProjects.bedrooms.specifications', [$project->id, $subProject->id, $value->id]) }}">
                                                        <i class="fa fa-cogs"></i>
                                                        Specifications
                                                    </a>
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.projects.subProjects.bedrooms.edit', [$project->id, $subProject->id, $value->id]) }}">
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
