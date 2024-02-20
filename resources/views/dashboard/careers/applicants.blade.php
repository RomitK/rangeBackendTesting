@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Career Management</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/careers') }}">Career Management</a></li>
                        <li class="breadcrumb-item active">Career Applicants</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            @if($career)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Added By</th>
                                        <th>Added At</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $career->position }}</td>
                                        <td>
                                            <span
                                                class="badge @if ($career->status === 'active') bg-success @else bg-danger @endif">
                                                {{ $career->status }}
                                            </span>
                                        </td>
                                        <td>{{ $career->user->name }}</td>
                                        <td>{{ $career->formattedCreatedAt }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card">
                       
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        @if($career)@else
                                        <th>Position</th>
                                        @endif
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Applied At</th>
                                        <th>Download CV</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($applicants as $applicant)
                                        <tr>
                                            <td scope="row">{{ $i++ }}</td>
                                            @if($career)@else
                                            <td>{{ $applicant->career? $applicant->career->position:'' }}</td>
                                            @endif
                                            <td>{{ $applicant->name }}</td>
                                            <td>{{ $applicant->email }}</td>
                                            <td>{{ $applicant->contact_number }}</td>
                                            <td>{{ $applicant->formattedCreatedAt }}</td>
                                            <td>@if($applicant->cv)
                                                <a class="btn btn-sm btn-info" href="{{ asset($applicant->cv) }}" download>  CV</a>
                                                @endif</td>
                                            <td>
                                                @if($career)
                                                    <form method="POST" action="{{ route('dashboard.careers.applicant.destroy', $applicant->id) }}">
                                                @else
                                                    <form method="POST" action="{{ route('dashboard.careers.applicant.destroy', $applicant->id) }}">
                                                @endif
                                                    @csrf
                                                    @method('DELETE')

                                                @if($career)
                                                <a class="btn btn-warning btn-sm"
                                                    href="{{ route('dashboard.careers.applicant', [$career->id,$applicant->id]) }}">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                                @else
                                                <a class="btn btn-warning btn-sm"
                                                    href="{{ route('dashboard.careers.singleApplicant', $applicant->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                                @endif
                                                
                                                @if($career)
                                                   
                                                    <input type="hidden" value="{{ $career->id }}" name="career">
                                                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                                @else
                                                
                                                    @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm show_confirm">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </button>
                                                @endif
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
