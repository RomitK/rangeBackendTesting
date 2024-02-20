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
                        @if(isset($career) && $career->id)
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/careers/' . $career->id . '/applicants') }}">{{ $career->position }}</a></li>
                        @endif
                        <li class="breadcrumb-item active">View Applicant</li>
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
                                <tbody>
                                    <tr>
                                        <th>Positon</th>
                                        <td>{{ $applicant->career? $applicant->career->position:'' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $applicant->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $applicant->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $applicant->contact_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cover Letter</th>
                                        <td>{!! $applicant->cover_letter  !!}</td>
                                    </tr>
                                    <tr>
                                        <th>CV</th>
                                        <td>@if($applicant->cv)
                                            <a class="btn btn-sm btn-danger" href="{{ asset($applicant->cv) }}" download> Download CV</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Submitted Date</th>
                                        <td>{{ $applicant->submit_date  }}</td>
                                    </tr>
                                    @if($applicant->page_url)
                                    <tr>
                                        <th>Page Url</th>
                                        <td>{{ $applicant->page_url  }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
