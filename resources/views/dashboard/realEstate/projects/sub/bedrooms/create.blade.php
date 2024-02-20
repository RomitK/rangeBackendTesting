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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects.bedrooms', [$project->id, $subProject->id]) }}">Project Bedrooms</a></li>
                        <li class="breadcrumb-item active">New Project Bedrooms</li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">New Bedroom Info Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder"  id="storeForm" method="POST" action="{{ route('dashboard.projects.subProjects.bedrooms.store', [$project->id, $subProject->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="title">Bedroom</label>
                                            <input type="text" value="{{ old('bedroom_number') }}" class="form-control @error('bedroom_number') is-invalid @enderror" id="bedroom_number" placeholder="Enter Bedroom" name="bedroom_number" required>
                                            @error('bedroom_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="title">Bathroom</label>
                                            <input type="text" value="{{ old('bathroom_number') }}" class="form-control @error('bathroom_number') is-invalid @enderror" id="bathroom_number" placeholder="Enter Bathroom" name="bathroom_number" required>
                                            @error('bathroom_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="title">Area</label>
                                            <input type="text"  value="{{ old('area') }}" class="form-control @error('area') is-invalid @enderror" id="area" placeholder="Enter Area" name="area" required>
                                            @error('bathroom_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="status"
                                                name="status">
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
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="text" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" id="price"  name="price" required>
                                            @error('price')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="floorplan_image">FloorPlan Image</label>
                                            <div class="custom-file  @error('floorplan_image') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="floorplan_image" name="floorplan_image" accept="image/*">
                                                <label class="custom-file-label" for="floorplan_image">Choose file</label>
                                            </div>
                                            @error('floorplan_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="floorplan_file">FloorPlan File</label>
                                            <div class="custom-file  @error('floorplan_file') is-invalid @enderror">
                                                <input type="file" class="custom-file-input" id="floorplan_file" name="floorplan_file" accept=".pdf">
                                                <label class="custom-file-label" for="floorplan_file">Choose file</label>
                                            </div>
                                            @error('floorplan_file')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
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
