@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Highlights</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Highlights</li>
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

                                <a href="{{ route('dashboard.highlights.create') }}" class="btn btn-block btn-primary">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    New Highlight
                                </a>

                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div class="row">
                               <div class="col-xl-6">
                                  <div class="d-flex"><br/>
                                     <span>Total Record(s): {{ $highlights->total() }} </span>
                                  </div>
                                </div>
                                <div class="col-xl-6 justify-content-end">
                                    {{ Form::select('pagination',get_pagination(),$current_page,array('class'=>'custom-select w-auto float-right','id'=>'showItems')) }}
                                </div>
                            </div>
                            <form  method="GET">
                                  
                                    <div class="row mb-2">
                                      
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                   @foreach (config('constants.statusesOption') as $key=>$value)
                                                        <option value="{{ $key }}" @if(request()->status == $key) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="type">Approval Status</label>
                                            <select class="form-control" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedWithAll') as $key=>$value)
                                                    <option value="{{ $key }}" @if(request()->is_approved == $key) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                         <div class="col-sm-6">
                                            <label for="keyword"> Keyword</label>
                                                <input type="text" value="{{ request()->keyword }}"
                                                    class="form-control" id="keyword"
                                                    placeholder="Enter Name" name="keyword">
                                         </div>
                                    </div>
                                   <br>
                                  <div class="row">
                                     
                                    <div class="col-xl-3">
                                        <button type="submit" class="btn btn-block btn-primary search_clear_btn" name="submit_filter" value="1">Search</button>
                                    </div>
                
                                    <div class="col-md-3">
                                        @if(request()->submit_filter)
                                            <a class="btn btn-block btn-warning search_clear_btn" href="{{ url('dashboard/highlights') }}">Clear Search</a>
                                        @endif
                                    </div>
                
                                </div>
                                  
                              </form>
                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Logo</th>
                                        <th>Status</th>
                                        <th>Approval Status</th>
                                        <th>Approval By</th>
                                        <th>Added By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($highlights as $key => $highlight)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $highlight->name }}</td>
                                            <td>
                                                <ul class="list-inline">
                                                    <li class="list-inline-item">
                                                        <img alt="{{ $highlight->name }}" class="table-avatar" src="{{ $highlight->image }}" style="padding:2%; background:#007bff">
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge @if ($highlight->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $highlight->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($highlight->is_approved === config('constants.requested')) bg-info 
                                                    @elseif($highlight->is_approved === config('constants.approved')) bg-success 
                                                    @elseif($highlight->is_approved === config('constants.rejected'))  bg-danger @endif">
                                                  
                                                    @if($highlight->is_approved == config('constants.requested')) 
                                                        Requested
                                                    @elseif($highlight->is_approved === config('constants.approved')) 
                                                    Approved
                                                    @elseif($highlight->is_approved === config('constants.rejected'))  
                                                    Rejected
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{ $highlight->approval ? $highlight->approval->name: '' }}</td>
                                            <td>{{ $highlight->user->name }}</td>
                                            <td>{{ $highlight->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.highlights.destroy', $highlight->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.highlights.edit', $highlight->id) }}">
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
                            <div class="row">
                                <div class="col-12 text-center pagination">
                                     {!! $highlights->links() !!}
                                </div>
                                
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
