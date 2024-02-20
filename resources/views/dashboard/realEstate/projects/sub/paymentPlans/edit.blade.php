@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project Payment Plan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/projects') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects',$project->id) }}">Available Units</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.subProjects.paymentPlans', [$project->id, $subProject->id]) }}">Payment Plans</a></li>
                        <li class="breadcrumb-item active">Edit Payment Plans</li>
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
                                        <th>Added At</th>
                                        <th>Added By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $project->title }}</td>
                                        <td>
                                            <span
                                                class="badge @if ($project->status === 'active') bg-success @else bg-danger @endif">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td>{{ $project->user->name }}</td>
                                        <td>{{ $project->formattedCreatedAt }}</td>
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
                            <h3 class="card-title">New Payment Plan Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" method="POST" action="{{ route('dashboard.projects.subProjects.paymentPlans.update', [$project->id, $subProject->id, $payment->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Installment</label>
                                            <input type="text" value="{{ $payment->name }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Installment" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="value">Percentage </label>
                                            <input type="text" value="{{ $payment->key }}" class="form-control @error('key') is-invalid @enderror" id="key" placeholder="Enter Percentage" name="key" required>
                                            @error('value')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="value">Milestone</label>
                                            <input type="text" value="{{ $payment->value }}" class="form-control @error('value') is-invalid @enderror" id="value" placeholder="Enter Milestone" name="value" >
                                            @error('value')
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
