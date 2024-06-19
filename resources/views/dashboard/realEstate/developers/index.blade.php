@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Developers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Developers</li>
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
                                    <a href="{{ route('dashboard.developers.create') }}" class="btn btn-block btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Developer
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="d-flex"><br />
                                        <span>Total Record(s): {{ $developers->total() }} </span>
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

                                    {{-- <div class="col-sm-3">
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
                                    </div> --}}
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="type">Website Status</label>
                                            <select class="form-control" id="website_status" name="website_status">
                                                @foreach (config('constants.newStatuses') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->website_status == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
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
                                        <a class="btn btn-block btn-info search_clear_btn" id="exportDeveloper"
                                            href="{{ url('dashboard/developers') }}">Download</a>
                                    </div>
                                    <div class="col-md-3">
                                        @if (request()->submit_filter)
                                            <a class="btn btn-block btn-warning search_clear_btn"
                                                href="{{ url('dashboard/developers') }}">Clear Search</a>
                                        @endif
                                    </div>

                                </div>

                            </form>
                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Logo</th>

                                        <th>Order Number <span class="arrow up"
                                                onclick="orderBy('developerOrder', 'asc')">&#x25B2;</span><span
                                                class="arrow down"
                                                onclick="orderBy('developerOrder', 'desc')">&#x25BC;</span></th>
                                        <th>Website Status</th>
                                        {{-- <th>Status</th>
                                        <th>Approval Status</th> --}}

                                        <th>Approval By</th>
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($developers as $key => $developer)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $developer->name }}</td>
                                            <td>
                                                <ul class="list-inline">
                                                    <li class="list-inline-item">
                                                        @if ($developer->logo)
                                                            <img alt="{{ $developer->name }}" class="table-avatar"
                                                                width="100" src="{{ $developer->logo }}">
                                                        @endif
                                                    </li>
                                                </ul>
                                            </td>

                                            <td>
                                                {{ $developer->developerOrder }}
                                            </td>
                                          <td>
                                                <span
                                                    class="badge 
                                                    @if ($developer->website_status === config('constants.NA')) bg-info 
                                                    @elseif($developer->website_status === config('constants.available')) bg-success 
                                                    @elseif($developer->website_status === config('constants.rejected'))  bg-danger 
                                                    @elseif($developer->website_status === config('constants.requested'))  bg-warning @endif">

                                                    {{ ucfirst($developer->website_status) }}
                                                </span>
                                            </td>
                                               {{--<td>
                                                <span
                                                    class="badge @if ($developer->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $developer->status }}
                                                </span>
                                            </td> 
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($developer->is_approved === config('constants.requested')) bg-info 
                                                    @elseif($developer->is_approved === config('constants.approved')) bg-success 
                                                    @elseif($developer->is_approved === config('constants.rejected'))  bg-danger @endif">

                                                    @if ($developer->is_approved == config('constants.requested'))
                                                        Requested
                                                    @elseif($developer->is_approved === config('constants.approved'))
                                                        Approved
                                                    @elseif($developer->is_approved === config('constants.rejected'))
                                                        Rejected
                                                    @endif
                                                </span>
                                            </td>--}}
                                            <td>{{ $developer->approval ? $developer->approval->name : '' }}</td>
                                            <td>{{ $developer->user->name }}</td>
                                            <td>{{ $developer->updatedBy ? $developer->updatedBy->name : '' }}</td>
                                            <td>{{ $developer->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.developers.destroy', $developer->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <a class="btn btn-warning btn-sm" target="_blanket"
                                                        href="{{ config('app.frontend_url') . 'developers/' . $developer->slug }}">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                   
                                                    @can(config('constants.Permissions.seo'))
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('dashboard.developers.meta', $developer->id) }}">
                                                            <i class="fas fa-search-plus"></i>
                                                            Meta Details
                                                        </a>
                                                    @endcan
                                                    @can(config('constants.Permissions.real_estate'))
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('dashboard.developers.edit', $developer->id) }}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            Edit
                                                        </a>
                                                    @endcan
                                                    @if (Auth::user()->role != 'user')
                                                        <a class="btn btn-dark btn-sm"
                                                            href="{{ route('dashboard.developers.logs', $developer->id) }}">
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

                                    {!! $developers->appends(request()->query())->links() !!}
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
