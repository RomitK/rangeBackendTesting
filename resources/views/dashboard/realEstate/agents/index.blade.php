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
<style>
    .pagination {
        display: flex;
        justify-content: center;
    }
</style>    
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
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="d-flex"><br />
                                        <span>Total Record(s): {{ $agents->total() }} </span>
                                    </div>
                                </div>
                                <div class="col-xl-6 justify-content-end">
                                    {{ Form::select('pagination', get_pagination(), $current_page, ['class' => 'custom-select w-auto float-right', 'id' => 'showItems']) }}
                                </div>
                            </div>

                            <form method="GET">
                                @php
                                    $seletectAgents = request()->agent_ids ? request()->agent_ids : [];
                                    $seletectProjects = request()->project_ids ? request()->project_ids : [];
                                    $seletectAccommodations = request()->accommodation_ids
                                        ? request()->accommodation_ids
                                        : [];
                                    $seletectUpdatedUsers = request()->updated_user_ids
                                        ? request()->updated_user_ids
                                        : [];
                                    $seletectAddedUsers = request()->added_user_ids ? request()->added_user_ids : [];
                                @endphp
                                <div class="row mb-2">





                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                @foreach (config('constants.statusesOption') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->status == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
				 <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="deparmtent">Department</label>
                                            <select class="form-control" id="department" name="department">
                                                @foreach (config('constants.allDepartments') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->department == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="keyword"> Keyword</label>
                                        <input type="text" value="{{ request()->keyword }}" class="form-control"
                                            id="keyword" placeholder="Enter Name, Reference Number" name="keyword">
                                    </div>

                                </div>
                                <br>
                                <div class="row">

                                    <div class="col-xl-3">
                                        <button type="submit" class="btn btn-block btn-primary search_clear_btn"
                                            name="submit_filter" value="1">Search</button>
                                    </div>

                                    <div class="col-md-3">
                                        <a class="btn btn-block btn-info search_clear_btn" id="exportAgent"
                                            href="{{ url('dashboard/agents') }}">Download</a>
                                    </div>

                                    <div class="col-md-3">
                                        @if (request()->submit_filter)
                                            <a class="btn btn-block btn-warning search_clear_btn"
                                                href="{{ url('dashboard/agents') }}">Clear Search</a>
                                        @endif
                                    </div>

                                </div>

                            </form>

                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
<th>Department</th>
                                        <th>Status</th>
                                        {{-- <th>Approval Status</th>
                                        <th>Approval By</th> --}}
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $key => $agent)
                                        <tr>
                                            <td>{{ $sr_no_start++ }}</td>
                                            <td>{{ $agent->name }}</td>
                                            <td>{{ $agent->email }}</td>
					<td>{{ $agent->department }}</td>
                                            <td>
                                                <span
                                                    class="badge @if ($agent->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $agent->status }}
                                                </span>
                                            </td>

                                            {{-- <td>
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
                                            <td>{{ $agent->approval ? $agent->approval->name : '' }}</td> --}}
                                            <td>{{ $agent->user->name }}</td>
                                            <td>{{ $agent->updatedBy ? $agent->updatedBy->name : '' }}</td>

                                            <td>{{ $agent->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">


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
                                                    <a class="btn btn-warning btn-sm" target="_blanket"
                                                        href="{{ config('app.frontend_url') . 'profile/' . $agent->profileUrl . '/' . $agent->slug }}">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>

                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-12 pagination">
                                    {!! $agents->appends(request()->query())->links() !!}
                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
