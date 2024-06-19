<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-hover text-nowrap table-striped">
                    <thead>
                        <tr>

                            <th>Name</th>
                            <th>Website Status</th>
                            <th>Display on Home</th>
                            <th>Order Number</th>
                            <th>Approval By</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Added At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>

                            <td>{{ $community->name }}</td>
                            <td>
                                <span
                                    class="badge 
                                    @if ($community->website_status === config('constants.NA')) bg-info 
                                    @elseif($community->website_status === config('constants.available')) bg-success 
                                    @elseif($community->website_status === config('constants.rejected'))  bg-danger 
                                    @elseif($community->website_status === config('constants.requested'))  bg-warning @endif">

                                    {{ ucfirst($community->website_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge @if ($community->display_on_home === 1) bg-success @else bg-danger @endif">
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
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
