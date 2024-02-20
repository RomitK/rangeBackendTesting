@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/projects') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects', $project->id) }}">Sub Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects.bedrooms', [$project->id,$subProject->id ]) }}">Project Bedrooms</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects.bedrooms.specifications', [$project->id,$subProject->id, $bedroom->id ]) }}">Bedroom Specifications</a></li>
                        <li class="breadcrumb-item active">Edit Bedroom Specification</li>
                    </ol>
                </div><!-- /.col -->
                <div class="col-sm-12">
                    <h1 class="m-0">Project({{ $subProject->title }})</h1>
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
                                        <th>Bedroom</th>
                                        <th>Bathroom</th>
                                        <th>Area</th>
                                        <th>Status</th>
                                        <th>Added By</th>
                                        <th>Added At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $bedroom->bedroom_number }}</td>
                                        <td>{{ $bedroom->bathroom_number }}</td>
                                        <td>{{ $bedroom->area }}</td>
                                        <td>
                                            <span class="badge @if ($bedroom->status === 'active') bg-success @else bg-danger @endif">
                                                {{ $bedroom->status }}
                                            </span>
                                        </td>
                                        <td>{{ $bedroom->user->name }}</td>
                                        <td>{{ $bedroom->formattedCreatedAt }}</td>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Bedroom Specification Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" method="POST" action="{{ route('dashboard.projects.subProjects.bedrooms.specifications.update', [$project->id, $subProject->id,$bedroom->id, $specification->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" value="{{ $specification->name }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Name" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="value">Value</label>
                                            <input type="value" value="{{ $specification->value }}" class="form-control @error('value') is-invalid @enderror" id="value" placeholder="Enter Value" name="value" required>
                                            @error('value')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="icon">Icon</label>
                                            <div class="custom-file  @error('icon') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="icon" name="icon" accept="image/*">
                                                <label class="custom-file-label" for="icon">Choose file</label>
                                            </div>
                                            @error('icon')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        @if ($specification->icon)
                                        <img src="{{ $specification->icon }}" src="{{ $specification->icon }}">
                                        @endif
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
