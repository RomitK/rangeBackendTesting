@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Testimonials</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Testimonials</li>
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

                                    <a href="{{ route('dashboard.testimonials.create') }}"
                                        class="btn btn-block btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        New Testimonial
                                    </a>

                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Client Name</th>
                                        
                                        <th>Status</th>
                                        <th>Approval Status</th>
                                        <th>Approval By</th>
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testimonials as $key => $testimonial)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $testimonial->client_name }}</td>
                                            <td>
                                                <span
                                                    class="badge @if ($testimonial->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $testimonial->status }}
                                                </span>
                                            </td>
                                            <td>
                                                    <span
                                                        class="badge 
                                                        @if ($testimonial->is_approved === config('constants.requested')) bg-info 
                                                        @elseif($testimonial->is_approved === config('constants.approved')) bg-success 
                                                        @elseif($testimonial->is_approved === config('constants.rejected'))  bg-danger @endif">
                                                      
                                                        @if($testimonial->is_approved == config('constants.requested')) 
                                                            Requested
                                                        @elseif($testimonial->is_approved === config('constants.approved')) 
                                                        Approved
                                                        @elseif($testimonial->is_approved === config('constants.rejected'))  
                                                        Rejected
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>{{ $testimonial->approval ? $testimonial->approval->name: '' }}</td>
                                                <td>{{ $testimonial->user->name }}</td>
                                                <td>{{ $testimonial->updatedBy ? $testimonial->updatedBy->name : ''}}</td>
                                            <td>{{ $testimonial->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.testimonials.destroy', $testimonial->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.testimonials.edit', $testimonial->id) }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
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
