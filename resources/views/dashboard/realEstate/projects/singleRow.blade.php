<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-hover text-nowrap table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reference Number</th>
                            <th>Permit Number</th>
                            <th>Completion Status</th>
                            <th>Display On Home</th>
                            <th>Website Status</th>
                           
                            <th>Order Number</th>
                            <th>Approval By</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Added At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
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
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
