@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Teams</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Teams</li>
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
                                <a href="{{ route('dashboard.agents.create') }}" class="btn btn-block btn-primary">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    New Team
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Approval Status</th>
                                        <th>Approval By</th>
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $key => $agent)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $agent->name }}</td>
                                            <td>{{ $agent->email }}</td>
                                            <td>
                                                <span
                                                    class="badge @if ($agent->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $agent->status }}
                                                </span>
                                            </td>

                                            <td>
                                                <span
                                                    class="badge 
                                                        @if ($agent->is_approved === config('constants.requested')) bg-info 
                                                        @elseif($agent->is_approved === config('constants.approved')) bg-success 
                                                        @elseif($agent->is_approved === config('constants.rejected'))  bg-danger @endif">

                                                    @if ($agent->is_approved == config('constants.requested'))
                                                        Requested
                                                    @elseif($agent->is_approved === config('constants.approved'))
                                                        Approved
                                                    @elseif($agent->is_approved === config('constants.rejected'))
                                                        Rejected
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{ $agent->approval ? $agent->approval->name : '' }}</td>
                                            <td>{{ $agent->user->name }}</td>
                                            <td>{{ $agent->updatedBy ? $agent->updatedBy->name : '' }}</td>

                                            <td>{{ $agent->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">

                                                <a class="btn btn-warning btn-sm" target="_blanket"
                                                    href="{{ config('app.frontend_url') . 'profile/' . $agent->designationUrl . '/' . $agent->slug }}">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>

                                                <form method="POST"
                                                    action="{{ route('dashboard.agents.destroy', $agent->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.agents.edit', $agent->id) }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </a>
                                                    @if (Auth::user()->role != 'user')
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
