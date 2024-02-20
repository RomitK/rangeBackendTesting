@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Leads</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Leads</li>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Form Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>

                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $key => $lead)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $lead->form_name }}</td>
                                            <td>{{ $lead->email }}</td>
                                            <td>{{ $lead->phone }}</td>

                                            <td>{{ $lead->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST" action="{{ route('dashboard.leads.destroy', $lead->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('dashboard.leads.show', $lead->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
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
