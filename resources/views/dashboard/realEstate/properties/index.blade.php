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
                    <h1 class="m-0">Property Listings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Property Listings</li>
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
                                    <a href="{{ route('dashboard.properties.create') }}" class="btn btn-block btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Listing
                                    </a>
                                @endcan
                            </div>

                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="d-flex"><br />
                                        <span>Total Record(s): {{ $properties->total() }} </span>
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

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="exclusive">Exclusive?</label>
                                            <select class="form-control" id="exclusive" name="exclusive">
                                                @foreach ($exclusiveOptions as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->exclusive == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                    </div>

                                    {{-- <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="is_duplicate">Duplicate?</label>
                                            <select class="form-control" id="is_duplicate" name="is_duplicate">
                                                <option value="">All</option>
                                                <option @if (request()->is_duplicate == 'duplicate') selected @endif value="duplicate">
                                                    Duplicate</option>
                                                <option @if (request()->is_duplicate == 'not_duplicate') selected @endif
                                                    value="not_duplicate">Not Duplicate</option>
                                            </select>

                                        </div>
                                    </div> --}}

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select class="form-control" id="category_id" name="category_id">
                                                @foreach ($categories as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->category_id == $key) selected @endif>
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
                                            <label for="type">Is Furnish Status</label>
                                            <select class="form-control select1 @error('is_furniture') is-invalid @enderror"
                                                id="is_furniture" name="is_furniture">

                                                @foreach (config('constants.furnitueOptionWithAll') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->is_furniture === $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="property_source">Property Source</label>
                                            <select class="form-control" id="property_source" name="property_source">
                                                @foreach (config('constants.propertySourcesWithAll') as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->property_source == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="completion_status_id">Completion Status</label>
                                            <select data-placeholder="Select Completion Status" style="width: 100%;"
                                                class=" form-control select1" id="completion_status_id"
                                                name="completion_status_id">
                                                @foreach ($completionStatuses as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (request()->completion_status_id == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="project_id">Project</label>
                                            <select multiple="multiple" data-placeholder="Select Project"
                                                style="width: 100%;"
                                                class="select2 form-control @error('project_id') is-invalid @enderror"
                                                id="project_id" name="project_ids[]">
                                                @foreach ($projects as $key => $project)
                                                    <option value="{{ $key }}"
                                                        @if (in_array($key, $seletectProjects)) selected @endif>
                                                        {{ $project }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="agent_id">Property Type</label>
                                            <select multiple="multiple" data-placeholder="Select Property Type"
                                                style="width: 100%;" class="select2 form-control select1"
                                                id="accommodation_ids" name="accommodation_ids[]">
                                                @foreach ($accommodations as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if (in_array($key, $seletectAccommodations)) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="updated_brochure">Brochure/SaleOffer </label>
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
                                            <label for="permit_number">Permit Number </label>
                                            <select class="form-control" id="permit_number" name="permit_number">
                                                <option value="">All</option>
                                                <option value="1" @if (request()->permit_number === '1') selected @endif>
                                                    Exist</option>
                                                <option value="0" @if (request()->permit_number === '0') selected @endif>
                                                    Not Exist</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="agent_id">Agent</label>
                                            <select multiple="multiple" data-placeholder="Select Agent"
                                                style="width: 100%;" class="select2 form-control select1" id="agent_id"
                                                name="agent_ids[]">
                                                @foreach ($agents as $value)
                                                    <option value="{{ $value->id }}"
                                                        @if (in_array($value->id, $seletectAgents)) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="community_ids">Added By</label>
                                            <select multiple="multiple" data-placeholder="Select User"
                                                style="width: 100%;" class=" form-control select1" id="added_user_ids"
                                                name="added_user_ids[]">
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

                                    <div class="col-sm-3">
                                        <label for="keyword"> Keyword</label>
                                        <input type="text" value="{{ request()->keyword }}" class="form-control"
                                            id="keyword" placeholder="Enter Name, Reference Number" name="keyword">
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="date_range">Added At <span
                                                    id="date_range_show">{{ request()->data_range_input }}</span></label>
                                            <input type="hidden" value="{{ request()->data_range_input }}"
                                                name="data_range_input" id="data_range_input">
                                            <div class="input-group">
                                                <button type="button" class="btn btn-default float-right"
                                                    id="date_range">
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
                                        <a class="btn btn-block btn-info search_clear_btn" id="exportProperty"
                                            href="{{ url('dashboard/properties') }}">Download</a>
                                    </div>

                                    <div class="col-md-3">
                                        @if (request()->submit_filter)
                                            <a class="btn btn-block btn-warning search_clear_btn"
                                                href="{{ url('dashboard/properties') }}">Clear Search</a>
                                        @endif
                                    </div>

                                </div>

                            </form>

                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>SR.NO</th>
                                        <th>Name</th>
                                        <th>Refernce No.</th>
                                        <th>Permit Number</th>
                                        <th>Project</th>
                                        {{-- <th>Is Duplicate</th> --}}
                                        <th>Price</th>

                                        <th>Website Status</th>
                                        <th>Status</th>
                                        <th>Approval Status</th>

                                        <th>Exclusive</th>
                                        <th>Agent</th>
                                        <th>Category</th>
                                        <th>Property Type</th>

                                        <th>Order Number <span class="arrow up"
                                                onclick="orderBy('propertyOrder', 'asc')">&#x25B2;</span><span
                                                class="arrow down"
                                                onclick="orderBy('propertyOrder', 'desc')">&#x25BC;</span></th>
                                        <th>Approval By</th>
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($properties as $key => $property)
                                        <tr>
                                            <td>{{ $sr_no_start++ }}</td>
                                            <td>{{ $property->name }}</td>
                                            <td>{{ $property->reference_number }}</td>
                                            <td>
                                                @if ($property->project)
                                                    {{ $property->project->permit_number }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($property->project)
                                                    {{ $property->project->title }} ({{ $property->subProject->title }})
                                                @endif
                                            </td>
                                            {{-- <td>{{ $property->is_duplicate }}</td> --}}
                                            <td>{{ $property->price }}</td>

                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($property->websiteStatus === config('constants.NA')) bg-info 
                                                    @elseif($property->websiteStatus === config('constants.Available')) bg-success 
                                                    @elseif($property->websiteStatus === config('constants.Rejected'))  bg-danger 
                                                    @elseif($property->websiteStatus === config('constants.Requested'))  bg-warning @endif">

                                                    {{ $property->websiteStatus }}
                                                </span>
                                            </td>

                                            <td>
                                                <span
                                                    class="badge @if ($property->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $property->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($property->is_approved === config('constants.requested')) bg-info 
                                                    @elseif($property->is_approved === config('constants.approved')) bg-success 
                                                    @elseif($property->is_approved === config('constants.rejected'))  bg-danger @endif">

                                                    @if ($property->is_approved == config('constants.requested'))
                                                        Requested
                                                    @elseif($property->is_approved === config('constants.approved'))
                                                        Approved
                                                    @elseif($property->is_approved === config('constants.rejected'))
                                                        Rejected
                                                    @endif
                                                </span>
                                            </td>

                                            <td> <span
                                                    class="badge @if ($property->exclusive === 1) bg-success @else bg-danger @endif">
                                                    @if ($property->exclusive === 1)
                                                        {{ 'Exclusive' }}
                                                    @else
                                                        {{ 'Non-exclusive' }}
                                                    @endif
                                                </span></td>
                                            <td>
                                                @if ($property->agent)
                                                    {{ $property->agent ? $property->agent->name : '' }}
                                                @endif
                                            </td>
                                            <td>{{ $property->category ? $property->category->name : '' }}</td>
                                            <td>{{ $property->accommodations ? $property->accommodations->name : '' }}</td>


                                            <td>
                                                {{ $property->propertyOrder }}
                                            </td>
                                            <td>{{ $property->approval ? $property->approval->name : '' }}</td>
                                            <td>{{ $property->user ? $property->user->name : '' }}</td>
                                            <td>{{ $property->updatedBy ? $property->updatedBy->name : '' }}</td>
                                            <td>{{ $property->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.properties.destroy', $property->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <a class="btn btn-warning btn-sm" target="_blanket"
                                                        href="{{ config('app.frontend_url') . 'properties/' . $property->slug }}">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>

                                                    @if ($property->updated_brochure == 0 && in_array(Auth::user()->role, config('constants.isAdmin')))
                                                        <a class="btn btn-dark btn-sm"
                                                            href="{{ route('dashboard.properties.updateBrochure', $property->id) }}"
                                                            target="_blanket">
                                                            <i class="fas fa-road"></i>
                                                            Update Brochure
                                                        </a>
                                                    @endif
                                                    @if ($property->brochure)
                                                        <a class="btn btn-dark btn-sm" href="{{ $property->brochure }}"
                                                            target="_blanket">
                                                            <i class="fas fa-file"></i>
                                                            View Brochure
                                                        </a>
                                                    @endif


                                                    @if ($property->saleOffer)
                                                        <a class="btn btn-dark btn-sm" href="{{ $property->saleOffer }}"
                                                            target="_blanket">
                                                            <i class="fas fa-file"></i>
                                                            View Sale Offer
                                                        </a>
                                                    @endif

                                                    @can(config('constants.Permissions.real_estate'))
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('dashboard.properties.duplicate', $property->id) }}">
                                                            <i class="fas fa-file"></i>
                                                            Duplicate
                                                        </a>
                                                    @endcan
                                                    @can(config('constants.Permissions.seo'))
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('dashboard.property.meta', $property->id) }}">
                                                            <i class="fas fa-database"></i>
                                                            Meta Details
                                                        </a>
                                                    @endcan
                                                    @can(config('constants.Permissions.real_estate'))
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('dashboard.properties.edit', $property->id) }}">
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
                                <div class="col-12 pagination">
                                    {!! $properties->appends(request()->query())->links() !!}
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
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
