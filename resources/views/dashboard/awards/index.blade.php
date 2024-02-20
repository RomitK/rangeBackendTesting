@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Awards</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Awards</li>
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

                                <a href="{{ route('dashboard.awards.create') }}" class="btn btn-block btn-primary">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    New Award
                                </a>

                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            
                            <div class="row">
                               <div class="col-xl-6">
                                  <div class="d-flex"><br/>
                                     <span>Total Record(s): {{ $awards->total() }} </span>
                                  </div>
                                </div>
                                <div class="col-xl-6 justify-content-end">
                                    {{ Form::select('pagination',get_pagination(),$current_page,array('class'=>'custom-select w-auto float-right','id'=>'showItems')) }}
                                </div>
                            </div>
                            <form  method="GET">
                                   @php 
                                    $seletectDevelopers =request()->developer_ids?request()->developer_ids:[]  
                                   @endphp
                                    <div class="row mb-2">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                   @foreach (config('constants.statusesOption') as $key=>$value)
                                                        <option value="{{ $key }}" @if(request()->status == $key) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                   
                                         <div class="col-sm-4">
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
                                                <a class="btn btn-block btn-warning search_clear_btn" href="{{ url('dashboard/awards') }}">Clear Search</a>
                                            @endif
                                        </div>
                
                                    </div>
                                  
                                </form>
                            <table class="table table-hover text-nowrap table-striped propertyDatatable">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Order Number <span class="arrow up" onclick="orderBy('awardOrder', 'asc')">&#x25B2;</span><span class="arrow down" onclick="orderBy('awardOrder', 'desc')">&#x25BC;</span></th>
                                        <th>Added By</th>
                                        <th>Last Updated By</th>
                                        <th>Added At</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($awards as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->title }}</td>

                                            <td>
                                                <span
                                                    class="badge @if ($data->status === 'active') bg-success @else bg-danger @endif">
                                                    {{ $data->status }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $data->awardOrder}}
                                            </td>
                                            <td>{{ $data->user->name }}</td>
                                            <td>{{ $data->updatedBy ? $data->updatedBy->name : ''}}</td>
                                            <td>{{ $data->formattedCreatedAt }}</td>
                                            <td class="project-actions text-right">
                                                <form method="POST"
                                                    action="{{ route('dashboard.awards.destroy', $data->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.awards.edit', $data->id) }}">
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
@section('js')
	<script type="text/javascript">
    function orderBy(field, direction) {
    var currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('orderby', field);
    currentUrl.searchParams.set('direction', direction);
    
    // Redirect to the new URL
    window.location.href = currentUrl.href;
}

document.addEventListener('DOMContentLoaded', function() {
    var currentUrl = new URL(window.location.href);
    var orderBy = currentUrl.searchParams.get('orderby');
    var direction = currentUrl.searchParams.get('direction');

    if (orderBy && direction) {
        var arrows = document.querySelectorAll('.arrow');
        arrows.forEach(function(arrow) {
            arrow.classList.remove('active');
        });

        var activeArrow = document.querySelector(`[onclick="orderBy('${orderBy}', '${direction}')"]`);
        if (activeArrow) {
            activeArrow.classList.add('active');
        }
    }
});
</script>
@endsection
