<table class="table table-hover text-nowrap table-striped propertyDatatable">
    <thead>
        <tr>

            <th>Name</th>
            <th>Reference Number</th>
            <th>Permit Number</th>
            <th>Website Status</th>
            <th>Inventory</th>
            <th>Last Updated By</th>

        </tr>
    </thead>
    <tbody>
        <tr class="{{ $project->date_diff && $project->date_diff > 25 ? 'bg-danger' : '' }}">

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
                {{
                    $project->inventory_update ? $project->inventory_update->format('d m Y') : 'No Date'
                }}
                {{-- {{ $project->last_property_update ? $project->last_property_update->format('d m Y') : 'No properties' }} --}}
            </td>

        </tr>

    </tbody>
</table>
