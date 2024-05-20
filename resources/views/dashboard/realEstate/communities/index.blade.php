@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Communities</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Communities</li>
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
                                @can(config('constants.Permissions.real_estate'))
                                    <a href="{{ route('dashboard.communities.create') }}" class="btn btn-block btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Community
                                    </a>
                                @endcan

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="d-flex"><br />
                                        <span>Total Record(s): {{ $communities->total() }} </span>
                                    </div>
                                </div>
                                <div class="col-xl-6 justify-content-end">
                                    {{ Form::select('pagination', get_pagination(), $current_page, ['class' => 'custom-select w-auto float-right', 'id' => 'showItems']) }}
                                </div>
                            </div>
                            <form method="GET">
                                @php
                                    $seletectDevelopers = request()->developer_ids ? request()->developer_ids : [];
                                @endphp
                                <div class="row mb-2">

                                    <div class="col-sm-1">
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



                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="project_id">Developers</label>
                                            <select multiple="multiple" data-placeholder="Select Developers"
                                                style="width: 100%;" class="select2 form-control" id="developer_ids"
                                                name="developer_ids[]">
                                                @foreach ($developers as $key => $developer)
                                                    <option
                                                        value="{{ $key }}"@if (in_array($key, $seletectDevelopers)) selected @endif>
                                                        {{ $developer }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="keyword"> Keyword</label>
                                        <input type="text" value="{{ request()->keyword }}" class="form-control"
                                            id="keyword" placeholder="Enter Name" name="keyword">
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="date_range">Added At <span
                                                    id="date_range_show">{{ request()->data_range_input }}</span></label>
                                            <input type="hidden" value="{{ request()->data_range_input }}"
                                                name="data_range_input" id="data_range_input">
                                            <div class="input-group">
                                                <button type="button" class="btn btn-default float-right" id="date_range">
                                                    <i class="far fa-calendar-alt"></i> Date Range
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                            </div>
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
                                        <a class="btn btn-block btn-info search_clear_btn" id="exportCommunity"
                                            href="{{ url('dashboard/communities') }}">Download</a>
                                    </div>
                                    <div class="col-md-3">
                                        @if (request()->submit_filter)
                                            <a class="btn btn-block btn-warning search_clear_btn"
                                                href="{{ url('dashboard/communities') }}">Clear Search</a>
                                        @endif
                                    </div>

                                </div>

                            </form>

                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>SR.NO</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Approval Status</th>
                                        <th>Display on Home</th>
                                        <th>Order Number <span class="arrow up"
                                                onclick="orderBy('communityOrder', 'asc')">&#x25B2;</span><span
                                                class="arrow down"
                                                onclick="orderBy('communityOrder', 'desc')">&#x25BC;</span></th>
                                        <th>Approval By</th>
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($communities as $key => $community)
                                        <tr>
                                            <td>{{ $sr_no_start++ }}</td>
                                            <td>{{ $community->name }}</td>
                                            <td>
                                                <span
                                                    class="badge @if ($community->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $community->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($community->is_approved === config('constants.requested')) bg-info 
                                                    @elseif($community->is_approved === config('constants.approved')) bg-success 
                                                    @elseif($community->is_approved === config('constants.rejected'))  bg-danger @endif">

                                                    @if ($community->is_approved == config('constants.requested'))
                                                        Requested
                                                    @elseif($community->is_approved === config('constants.approved'))
                                                        Approved
                                                    @elseif($community->is_approved === config('constants.rejected'))
                                                        Rejected
                                                    @endif
                                                </span>
                                            </td>

                                            <td>
                                                <span
                                                    class="badge @if ($community->display_on_home === 1) bg-success @else bg-danger @endif">
                                                    @if ($community->display_on_home === 1)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                {{ $community->communityOrder }}
                                            </td>
                                            <td>{{ $community->approval ? $community->approval->name : '' }}</td>
                                            <td>{{ $community->user->name }}</td>
                                            <td>{{ $community->updatedBy ? $community->updatedBy->name : '' }}</td>
                                            <td>{{ $community->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.communities.destroy', $community->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <!--<a class="btn btn-dark btn-sm"-->
                                                    <!--    href="{{ route('dashboard.communities.stats', $community->id) }}">-->
                                                    <!--    <i class="fas fa-road"></i>-->
                                                    <!--    Stats-->
                                                    <!--</a>-->

                                                    <a class="btn btn-warning btn-sm" target="_blanket"
                                                        href="{{ config('app.frontend_url') . 'communities/' . $community->slug }}">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                    @can(config('constants.Permissions.seo'))
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('dashboard.community.meta', $community->id) }}">
                                                            <i class="fas fa-database"></i>
                                                            Meta Details
                                                        </a>
                                                    @endcan
                                                    @can(config('constants.Permissions.real_estate'))
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('dashboard.communities.edit', $community->id) }}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            Edit
                                                        </a>
                                                    @endcan
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
                            <div class="row">
                                <div class="col-12 text-center pagination">
                                    {!! $communities->links() !!}
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
