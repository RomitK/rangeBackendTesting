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
                    <h1 class="m-0">Inventory Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <style>
        .flex-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .row-full-height {
            height: 60vh;
            /* Adjust this height as needed */
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col (LEFT) -->
                <div class="col-md-12">
                    <!-- LINE CHART -->
                    <div class="card">

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


                                    <div class="col-sm-4">
                                        <label for="keyword"> Keyword</label>
                                        <input type="text" value="{{ request()->keyword }}" class="form-control"
                                            id="keyword" placeholder="Enter Name" name="keyword">
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xl-3">
                                        <button type="submit" class="btn btn-block btn-primary search_clear_btn"
                                            name="submit_filter" value="1">Search</button>
                                    </div>

                                    <div class="col-md-3">
                                        @if (request()->submit_filter)
                                            <a class="btn btn-block btn-warning search_clear_btn"
                                                href="{{ url('dashboard/inventory-report') }}">Clear Search</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                            <p>if last Inventory update more then 25 days then its marks as red</p>
                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Reference Number</th>
                                        <th>Permit Number</th>
                                        <th>Website Status</th>
                                        <th>Inventory</th>
                                        <th>Last Updated By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $key => $project)
                                        <tr
                                            class="{{ $project->date_diff && $project->date_diff > 25 ? 'bg-danger' : '' }}">
                                            <td>{{ $sr_no_start++ }}</td>
                                            <td>{{ $project->title }}</td>
                                            <td>{{ $project->reference_number }}</td>
                                            <td>{{ $project->permit_number }}</td>
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
                                            <td>
                                                <span class="badge bg-success"> {{ $project->available_count }} </span>
                                                <span class="badge bg-info"> {{ $project->na_count }}</span>
                                                <span class="badge bg-warning"> {{ $project->requested_count }}</span>
                                                <span class="badge bg-danger"> {{ $project->rejected_count }}</span>
                                            </td>
                                            <td>
                                                {{ $project->last_property_update ? $project->last_property_update->format('d m Y') : 'No properties' }}
                                            </td>
                                            <td>
                                                <a class="btn btn-dark btn-sm"
                                                    href="{{ route('dashboard.projects.inventoryList', $project->id) }}">
                                                    <i class="fas fa-database"></i>
                                                    Update Inventory
                                                </a>

                                                <a class="btn btn-info btn-sm"
                                                    href="{{ route('dashboard.projects.inventory', $project->id) }}">
                                                    <i class="fas fa-store nav-icon"></i>
                                                    Inventory File
                                                </a>

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
                <!-- /.col (RIGHT) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('js')
@endsection
