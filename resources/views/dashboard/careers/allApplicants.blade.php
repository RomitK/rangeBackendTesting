@extends('dashboard.layout.index')
@section('head')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.css"/>
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>
@endsection
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
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="career-tab" data-bs-toggle="tab" data-bs-target="#career"
                                type="button" role="tab" aria-controls="career" aria-selected="true">Applicants with Position</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="withoutcareer-tab" data-bs-toggle="tab" data-bs-target="#withoutcareer"
                                type="button" role="tab" aria-controls="withoutcareer" aria-selected="false">Applicants without Position</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                                type="button" role="tab" aria-controls="all" aria-selected="false">All Applicants</button>
                        </li>
                    </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="career" role="tabpanel" aria-labelledby="career-tab">
                                <table id="applicantswithcareer" class="table table-hover text-nowrap table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Position</th>
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
                                        @foreach ($withApplicants as $applicant)
                                            <tr>
                                                <td scope="row">{{ $i++ }}</td>
                                                <td>{{ $applicant->career ? $applicant->career->position : '' }}</td>
                                                <td>{{ $applicant->name }}</td>
                                                <td>{{ $applicant->email }}</td>
                                                <td>{{ $applicant->contact_number }}</td>
                                                <td>{{ $applicant->formattedCreatedAt }}</td>
                                                <td>@if ($applicant->cv)
                                                    <a class="btn btn-sm btn-info"
                                                        href="{{ asset($applicant->cv) }}" download> 
                                                        CV</a>
                                                @endif</td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('dashboard.careers.applicant.destroy', $applicant->id) }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <a class="btn btn-warning btn-sm"
                                                            href="{{ route('dashboard.careers.applicant', [$applicant->career->id, $applicant->id]) }}">
                                                            <i class="fas fa-eye"></i>
                                                            View
                                                        </a>

                                                        
                                                        <input type="hidden" value="{{ $applicant->career->id }}"
                                                            name="career">
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm show_confirm">
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
                            <div class="tab-pane fade" id="withoutcareer" role="tabpanel" aria-labelledby="withoutcareer-tab">
                                <table id="applicantswithoutcareer" class="table table-hover text-nowrap table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
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
                                        @foreach ($withoutCareerApplicants as $applicant)
                                            <tr>
                                                <td scope="row">{{ $i++ }}</td>

                                                <td>{{ $applicant->name }}</td>
                                                <td>{{ $applicant->email }}</td>
                                                <td>{{ $applicant->contact_number }}</td>
                                                <td>{{ $applicant->formattedCreatedAt }}</td>
                                                <td>
                                                    @if ($applicant->cv)
                                                        <a class="btn btn-sm btn-info"
                                                            href="{{ asset($applicant->cv) }}" download> 
                                                            CV</a>
                                                    @endif
                                                </td>
                                               
                                                <td>
                                                    <form method="POST"
                                                    action="{{ route('dashboard.careers.applicant.destroy', $applicant->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('dashboard.careers.singleApplicant', $applicant->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>

                                                    
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm show_confirm">
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
                            <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                                <table id="allapplicants" class="table table-hover text-nowrap table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Position</th>
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
                                        @foreach ($allApplicants as $applicant)
                                            <tr>
                                                <td scope="row">{{ $i++ }}</td>
                                                <td>{{ $applicant->career ? $applicant->career->position : '' }}</td>
                                                <td>{{ $applicant->name }}</td>
                                                <td>{{ $applicant->email }}</td>
                                                <td>{{ $applicant->contact_number }}</td>
                                                <td>{{ $applicant->formattedCreatedAt }}</td>
                                                <td>
                                                    @if ($applicant->cv)
                                                        <a class="btn btn-sm btn-info"
                                                            href="{{ asset($applicant->cv) }}" download>
                                                            CV</a>
                                                    @endif
                                                </td>
                                               
                                                <td>
                                                    @if ($applicant->career)
                                                    <form method="POST"
                                                        action="{{ route('dashboard.careers.applicant.destroy', $applicant->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden"
                                                            value="{{ $applicant->career->id }}" name="career">
                                                            @else
                                                            <form method="POST"
                                                                action="{{ route('dashboard.careers.applicant.destroy', $applicant->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                @endif
                                                    @if ($applicant->career)
                                                        <a class="btn btn-warning btn-sm"
                                                            href="{{ route('dashboard.careers.applicant', [$applicant->career->id, $applicant->id]) }}">
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
                                                    
                                                    @if ($applicant->career)
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm show_confirm">
                                                                <i class="fas fa-trash"></i>
                                                                Delete
                                                        
                                                    @else
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm show_confirm">
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
