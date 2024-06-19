<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-hover text-nowrap table-striped propertyDatatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Refernce No.</th>
                            <th>Permit Number</th>
                            <th>Project</th>
                            {{-- <th>Is Duplicate</th> --}}
                            <th>Price</th>

                            <th>Website Status</th>
                            {{-- <th>Status</th>
                                        <th>Approval Status</th> --}}

                            <th>Exclusive</th>
                            <th>Agent</th>
                            <th>Category</th>
                            <th>Property Type</th>

                            <th>Order Number <span class="arrow up"
                                    onclick="orderBy('propertyOrder', 'asc')">&#x25B2;</span><span class="arrow down"
                                    onclick="orderBy('propertyOrder', 'desc')">&#x25BC;</span></th>
                            <th>Approval By</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Added At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
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
                                                    @if ($property->website_status === config('constants.NA')) bg-info 
                                                    @elseif($property->website_status === config('constants.available')) bg-success 
                                                    @elseif($property->website_status === config('constants.rejected'))  bg-danger 
                                                    @elseif($property->website_status === config('constants.requested'))  bg-warning @endif">

                                    {{ ucfirst($property->website_status) }}
                                </span>
                            </td>
                            {{-- 
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
                                            </td> --}}

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
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
