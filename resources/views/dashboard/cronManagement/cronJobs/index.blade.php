@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cron Jobs</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Cron Jobs</li>
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
                            <table class="table table-hover text-nowrap table-striped " id="cronTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Property Cron</td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-info btn-sm" href=""
                                                target="_blank">
                                                <i class="fas fa-cog"></i>
                                                Run
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Property Main Image Cron</td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-info btn-sm"
                                                href="" target="_blank">
                                                <i class="fas fa-cog"></i>
                                                Run
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Property gallery Cron</td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-info btn-sm"
                                                href="" target="_blank">
                                                <i class="fas fa-cog"></i>
                                                Run
                                            </a>
                                        </td>
                                    </tr>
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
