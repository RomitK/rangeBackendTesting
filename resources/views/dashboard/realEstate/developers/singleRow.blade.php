<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-hover text-nowrap table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Order Number</th>
                            <th>Website Status</th>
                            

                            <th>Approval By</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Added At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $developer->name }}</td>


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
                            
                            <td>{{ $developer->approval ? $developer->approval->name : '' }}</td>
                            <td>{{ $developer->user->name }}</td>
                            <td>{{ $developer->updatedBy ? $developer->updatedBy->name : '' }}</td>
                            <td>{{ $developer->formattedCreatedAt }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
