@extends('dashboard.layout.index')
@section('breadcrumb')
    <style>
        .pagination {
            display: flex;
            justify-content: center;
        }

        /*.pagination li {*/
        /*  display: block;*/
        /*}*/
        /*#DataTables_Table_0_paginate{*/
        /*    display:none;*/
        /*}*/
        /*#DataTables_Table_0_info{*/
        /*    display:none;*/
        /*}*/
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Projects</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Projects</li>
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
                                @can(config('constants.Permissions.real_estate'))
                                    <a href="{{ route('dashboard.projects.create') }}" class="btn btn-block btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Project
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="d-flex"><br />
                                        <span>Total Record(s): {{ $projects->total() }} </span>
                                    </div>
                                </div>
                                <div class="col-xl-6 justify-content-end">
                                    {{ Form::select('pagination', get_pagination(), $current_page, ['class' => 'custom-select w-auto float-right', 'id' => 'showItems']) }}
                                </div>
                            </div>


                            <form method="GET">
                                @php
                                    $seletectCompletionStatus = request()->completion_status_ids
                                        ? request()->completion_status_ids
                                        : [];
                                    $seletectAccommodations = request()->accommodation_id
                                        ? request()->accommodation_id
                                        : [];
                                    $seletectCommunities = request()->community_ids ? request()->community_ids : [];
                                    $seletectDevelopers = request()->developer_ids ? request()->developer_ids : [];
                                    $seletectUpdatedUsers = request()->updated_user_ids
                                        ? request()->updated_user_ids
                                        : [];
                                    $seletectAddedUsers = request()->added_user_ids ? request()->added_user_ids : [];
                                @endphp
                                <div class="row mb-2">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="project_source">Project Source</label>
                                            <select class="form-control" id="project_source" name="project_source">
                                                @foreach (config('constants.propertySourcesWithAll') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->project_source == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="community_ids">Communities</label>
                                            <select multiple="multiple" data-placeholder="Select Communities"
                                                style="width: 100%;" class=" form-control select1" id="community_ids"
                                                name="community_ids[]">
                                                @foreach ($communities as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (in_array($key, $seletectCommunities)) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="community_ids">Developers</label>
                                            <select multiple="multiple" data-placeholder="Select Developers"
                                                style="width: 100%;" class=" form-control select1" id="developer_ids"
                                                name="developer_ids[]">
                                                @foreach ($developers as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (in_array($key, $seletectDevelopers)) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-2">
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
                                            <label for="type">Approval Status</label>
                                            <select class="form-control" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedWithAll') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->is_approved == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="type"> Website Status</label>
                                            <select class="form-control" id="website_status" name="website_status">
                                                @foreach (config('constants.newStatuses') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->website_status == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="completion_status_id">Completion Status</label>
                                            <select multiple="multiple" data-placeholder="Select Completion Status"
                                                style="width: 100%;" class=" form-control select1" id="completion_status_id"
                                                name="completion_status_ids[]">
                                                @foreach ($completionStatuses as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (in_array($key, $seletectCompletionStatus)) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="display_on_home">Display on Home Page </label>
                                            <select class="form-control" id="display_on_home" name="display_on_home">
                                                <option value="">All</option>
                                                <option value="1" @if (request()->display_on_home === '1') selected @endif>Yes
                                                </option>
                                                <option value="0" @if (request()->display_on_home === '0') selected @endif>No
                                                </option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="updated_brochure">Brochure </label>
                                            <select class="form-control" id="updated_brochure" name="updated_brochure">
                                                <option value="">All</option>
                                                <option value="1" @if (request()->updated_brochure === '1') selected @endif>
                                                    Updated</option>
                                                <option value="0" @if (request()->updated_brochure === '0') selected @endif>
                                                    Need to Update</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="is_valid">Permit Number+QR Code </label>
                                            <select class="form-control" id="is_valid" name="is_valid">
                                                <option value="">All</option>
                                                <option value="1" @if (request()->is_valid === '1') selected @endif>
                                                    Exist</option>
                                                <option value="0" @if (request()->is_valid === '0') selected @endif>
                                                    Not Exist</option>

                                            </select>
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="qr_link">QR Code </label>
                                            <select class="form-control" id="qr_link" name="qr_link">
                                                <option value="">All</option>
                                                <option value="1" @if (request()->qr_link === '1') selected @endif>
                                                    Exist</option>
                                                <option value="0" @if (request()->qr_link === '0') selected @endif>
                                                    Not Exist</option>

                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="community_ids">Added By</label>
                                            <select multiple="multiple" data-placeholder="Select User" style="width: 100%;"
                                                class=" form-control select1" id="added_user_ids" name="added_user_ids[]">
                                                @foreach ($users as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (in_array($key, $seletectAddedUsers)) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="community_ids">Updated By</label>
                                            <select multiple="multiple" data-placeholder="Select User"
                                                style="width: 100%;" class=" form-control select1" id="updated_user_ids"
                                                name="updated_user_ids[]">
                                                @foreach ($users as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (in_array($key, $seletectUpdatedUsers)) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="keyword"> Keyword</label>
                                        <input type="text" value="{{ request()->keyword }}" class="form-control"
                                            id="keyword" placeholder="Enter Name" name="keyword">
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="date_range">Added At <span
                                                    id="date_range_show">{{ request()->data_range_input }}</span></label>
                                            <input type="hidden" value="{{ request()->data_range_input }}"
                                                name="data_range_input" id="data_range_input">
                                            <div class="input-group">
                                                <button type="button" class="btn btn-default float-right"
                                                    id="date_range" style="font-size: 13px; padding: 9px;">
                                                    <i class="far fa-calendar-alt"></i> Date Range
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="upcoming_project">Upcoming</label>
                                            <div class="form-control d-flex justify-content-center align-items-center">
                                                <input 
                                                    type="checkbox" 
                                                    class="form-check-input @error('upcoming_project') is-invalid @enderror" 
                                                    id="upcoming_project" 
                                                    name="upcoming_project" 
                                                    style="margin-left: 1px;"
                                                    @if (request()->upcoming_project) checked @endif
                                                >
                                            </div>
                                            @error('upcoming_project')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xl-3">
                                        <button type="submit" class="btn btn-block btn-primary search_clear_btn"
                                            name="submit_filter" value="1">Search</button>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="btn btn-block btn-info search_clear_btn" id="exportProject"
                                            href="{{ url('dashboard/projects') }}">Download</a>
                                    </div>
                                    <div class="col-md-3">
                                        @if (request()->submit_filter)
                                            <a class="btn btn-block btn-warning search_clear_btn"
                                                href="{{ url('dashboard/projects') }}">Clear Search</a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Reference Number</th>
                                        <th>Permit Number</th>
                                        <th>Completion Status</th>
                                        <th>Display On Home</th>
                                        <th>Website Status</th>
                                        {{-- <th>Status</th>

                                        <th>Approval Status</th> --}}
                                        <th>Order Number <span class="arrow up"
                                                onclick="orderBy('projectOrder', 'asc')">&#x25B2;</span><span
                                                class="arrow down"
                                                onclick="orderBy('projectOrder', 'desc')">&#x25BC;</span></th>
                                        <th>Approval By</th>
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $key => $project)
                                        <tr>
                                            <td>{{ $sr_no_start++ }}</td>
                                            <td>{{ $project->title }}</td>
                                            <td>{{ $project->reference_number }}</td>
                                            <td>{{ $project->permit_number }}</td>
                                            <td>{{ $project->completionStatus ? $project->completionStatus->name : '' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge @if ($project->is_display_home === 1) bg-success @else bg-danger @endif">
                                                    @if ($project->is_display_home === 1)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($project->website_status === config('constants.NA')) bg-info 
                                                    @elseif($project->website_status === config('constants.available')) bg-success 
                                                    @elseif($project->website_status === config('constants.rejected'))  bg-danger 
                                                    @elseif($project->website_status === config('constants.requested'))  bg-warning @endif">

                                                    {{ ucfirst($project->website_status) }}
                                                </span>
                                            </td>

                                            {{-- <td>
                                                <span
                                                    class="badge @if ($project->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $project->status }}
                                                </span>
                                            </td>

                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($project->is_approved === config('constants.requested')) bg-info 
                                                    @elseif($project->is_approved === config('constants.approved')) bg-success 
                                                    @elseif($project->is_approved === config('constants.rejected'))  bg-danger @endif">

                                                    @if ($project->is_approved == config('constants.requested'))
                                                        Requested
                                                    @elseif($project->is_approved === config('constants.approved'))
                                                        Approved
                                                    @elseif($project->is_approved === config('constants.rejected'))
                                                        Rejected
                                                    @endif
                                                </span>
                                            </td> --}}
                                            <td>
                                                {{ $project->projectOrder }}
                                            </td>
                                            <td>{{ $project->approval ? $project->approval->name : '' }}</td>
                                            <td>{{ $project->user->name }}</td>
                                            <td>
                                                @if ($project->updatedBy)
                                                    {{ $project->updatedBy->name }} ({{ $project->formattedUpdatedAt }})
                                                @endif
                                            </td>
                                            <td>{{ $project->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.projects.destroy', $project->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ config('app.frontend_url') . 'projects/' . $project->slug }}"
                                                        target="_blanket">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                    <a class="btn btn-secondary btn-sm"
                                                        href="{{ route('dashboard.projects.meta', $project->id) }}">
                                                        <i class="fas fa-database"></i>
                                                        Meta Details
                                                    </a>


                                                    @if ($project->updated_brochure == 0 && in_array(Auth::user()->role, config('constants.isAdmin')))
                                                        <a class="btn btn-dark btn-sm"
                                                            href="{{ route('dashboard.projects.updateBrochure', $project->id) }}"
                                                            target="_blanket">
                                                            <i class="fas fa-road"></i>
                                                            Update Brochure
                                                        </a>
                                                    @endif
                                                    @if ($project->brochure)
                                                        <a class="btn btn-dark btn-sm" href="{{ $project->brochure }}"
                                                            target="_blanket">
                                                            <i class="fas fa-file"></i>
                                                            View Brochure
                                                        </a>
                                                    @endif
                                                    @can(config('constants.Permissions.real_estate'))
                                                        <a class="btn btn-success btn-sm"
                                                            href="{{ route('dashboard.projects.paymentPlans', $project->id) }}">
                                                            <i class="fas fa-tasks"></i>
                                                            Payment Plan({{ $project->mPaymentPlans->count() }})
                                                        </a>

                                                        <a class="btn btn-warning btn-sm"
                                                            href="{{ route('dashboard.projects.subProjects', $project->id) }}">
                                                            <i class="fas fa-project-diagram"></i>
                                                            Unit ({{ $project->subProjects->count() }})
                                                        </a>


                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('dashboard.projects.edit', $project->id) }}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            Edit
                                                        </a>

                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('dashboard.projects.inventory', $project->id) }}">
                                                            <i class="fas fa-store nav-icon"></i>
                                                            Inventory File
                                                        </a>
                                                    @endcan
                                                    @if (Auth::user()->role != 'user')
                                                        <a class="btn btn-dark btn-sm"
                                                            href="{{ route('dashboard.projects.logs', $project->id) }}">
                                                            <i class="fas fa-database"></i>
                                                            History
                                                        </a>
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
                            <div class="row">
                                <div class="col-12 text-center pagination">
                                    {!! $projects->appends(request()->query())->links() !!}
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

@section('js')
    <script type="text/javascript">
        function orderBy(field, direction) {
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('orderby', field);
            currentUrl.searchParams.set('direction', direction);

            // Redirect to the new URL
            window.location.href = currentUrl.href;
        }

        document.addEventListener('DOMContentLoaded', function() {
            var currentUrl = new URL(window.location.href);
            var orderBy = currentUrl.searchParams.get('orderby');
            var direction = currentUrl.searchParams.get('direction');

            if (orderBy && direction) {
                var arrows = document.querySelectorAll('.arrow');
                arrows.forEach(function(arrow) {
                    arrow.classList.remove('active');
                });

                var activeArrow = document.querySelector(`[onclick="orderBy('${orderBy}', '${direction}')"]`);
                if (activeArrow) {
                    activeArrow.classList.add('active');
                }
            }
        });
    </script>
@endsection
