@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Property Log History</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/properties') }}">Properties</a></li>
                        <li class="breadcrumb-item active">Property Log History</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('dashboard.realEstate.properties.singleRow')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if (count($property->logActivity) > 0)
                            <div class="card-header">
                                <div class="row float-right">
                                    <div class="col-md-12">
                                        <a class="btn btn-block btn-info" id="exportDeveloperLog"
                                            href="{{ route('dashboard.properties.logs', ['property' => $property->id, 'export' => 1]) }}">Download</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>User Name</th>
                                        <th>Website Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($property->logActivity as $key => $value)
                                        @php
                                            $properties = json_decode($value->properties, true);

                                            $website_status = null;

                                            if (
                                                isset($properties['new']) &&
                                                array_key_exists('website_status', $properties['new'])
                                            ) {
                                                $website_status = $properties['new']['website_status'];
                                            }
                                            $attribute = isset($properties['updateAttribute'])
                                                ? $properties['updateAttribute']
                                                : null;

                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->formattedCreatedAt }}</td>
                                            <td>{{ $value->user->name }}</td>
                                            <td>
                                                @if ($website_status)
                                                    <span
                                                        class="badge 
                                                    @if ($website_status === config('constants.NA')) bg-info 
                                                    @elseif($website_status === config('constants.available')) bg-success 
                                                    @elseif($website_status === config('constants.rejected'))  bg-danger 
                                                    @elseif($website_status === config('constants.requested'))  bg-warning @endif">
                                                        {{ ucfirst($website_status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $value->description }}
                                                @if ($attribute)
                                                    <small>Update columns:{{ $attribute }} </small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
