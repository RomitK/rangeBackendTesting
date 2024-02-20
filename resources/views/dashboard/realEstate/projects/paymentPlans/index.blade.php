@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project Payment Plan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/projects') }}">Projects</a></li>
                        
                        <li class="breadcrumb-item active">Payment Plans</li>
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
                            <table class="table table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Added By</th>
                                        <th>Added At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $project->title }}</td>
                                        <td>
                                            <span
                                                class="badge @if ($project->status === 'active') bg-success @else bg-danger @endif">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td>{{ $project->user->name }}</td>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row float-right">
                                <a href="{{ route('dashboard.projects.paymentPlans.create', $project->id) }}" class="btn btn-block btn-primary">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    New Payment Plan
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>SR.NO</th>
                                        <th>Title</th>
                                        <th>Approval Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    @foreach ($project->mPaymentPlans as $key => $value)
                                    
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->value }}</td>
                                             <td>
                                                <span
                                                    class="badge 
                                                    @if ($value->is_approved === config('constants.requested')) bg-info 
                                                    @elseif($value->is_approved === config('constants.approved')) bg-success 
                                                    @elseif($value->is_approved === config('constants.rejected'))  bg-danger @endif">
                                                  
                                                    @if($value->is_approved == config('constants.requested')) 
                                                        Requested
                                                    @elseif($value->is_approved === config('constants.approved')) 
                                                    Approved
                                                    @elseif($value->is_approved === config('constants.rejected'))  
                                                    Rejected
                                                    @endif
                                                    
                                                    
                                                </span>
                                            </td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('dashboard.projects.paymentPlans.destroy', [$project->id, $value->id]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.projects.paymentPlans.edit', [$project->id, $value->id]) }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </a>
                                                     @if(Auth::user()->role != 'user')
                                                    <button type="submit" class="btn btn-danger btn-sm show_confirm">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                    @endif
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
