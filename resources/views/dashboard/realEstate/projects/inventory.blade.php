@php
    use Carbon\Carbon;
@endphp
@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project Inventory Files</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/projects') }}">Projects</a></li>
                        <li class="breadcrumb-item active">Project Inventory Files</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('dashboard.realEstate.projects.singleRow')
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Project Inventory Files</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>SR </th>
                                        <th>Date</th>
                                        <th>User Name</th>
                                        <th>File</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($project->inventory as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ Carbon::parse($value->created_at)->timezone('Asia/Dubai')->format('d m Y H:i:s') }}
                                            </td>
                                            <td>
                                                @if(App\Models\User::find($value->getCustomProperty('uploaded_by')))
                                                {{ App\Models\User::find($value->getCustomProperty('uploaded_by'))->name}}
                                                @endif
                                            </td>
                                            <td>

                                                <a class="btn btn-sm btn-danger" href="{{ $value->getUrl() }}" download>
                                                    Download</a>

                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
