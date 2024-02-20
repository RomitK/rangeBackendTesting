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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.paymentPlans', $project->id) }}">Payment Plans</a></li>
                        <li class="breadcrumb-item active">New Payment Plans</li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">New Payment Plan Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="" method="POST" action="{{ route('dashboard.projects.paymentPlans.store', $project->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                     <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Payment Title</label>
                                            <input type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Payment Title" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="items" class="table table-no-more table-bordered mb-none ">
                                            <thead>
                                                <tr style="">
                                                    <th>Payments</th>
                                                    <th>Percentage</th>
                                                    <!--<th>Milestones</th>-->
                                                   
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
        
                                            <tr class="item-row" style="border-bottom: solid 1px black">
                                                <!--<td>-->
                                                <!--    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Installment" name="rows[0][name]" >-->
                                                <!--</td>-->
                                                <td>
                                                    <input type="text" class="form-control @error('value') is-invalid @enderror" placeholder="Enter Milestone" name="rows[0][value]" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control @error('key') is-invalid @enderror" placeholder="Enter Percentage" name="rows[0][key]"  required>
                                                </td>
                                                <td><a class="btn btn-block btn-primary btn-sm addrow updateRow0" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                                </td>
                                            </tr>
        
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        var i = 1;
        var count = 0;
        $(document).on('click', '.addrow', function() {
            $(this).text('x Remove');
            $(this).attr('class', 'btn btn-danger btn-sm del');
            $(".item-row:last").find('.mybtn').hide();
                i++;
                count++;
                var id = count;

                $(".item-row:last").after('<tr class="item-row" style="border-bottom: solid 1px black">' +
                    '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Milestone" name="rows[' + id +'][value]" required></td>' +
                    // '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Installment" name="rows[' + id +'][name]" required></td>' +
                    '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Percentage" name="rows[' + id +'][key]" required></td>' +
                    '<td data-title="Action" class="main_td"> <button type="button" class="btn btn-primary btn-sm addrow" id=\"updateRow' +  id +  '\">+ Add</button> ' +
                        '<a class=" Remove mybtn btn btn-danger btn-sm" href="javascript:;"  title="Remove row"> x Remove</a>' +
                        '</td></tr>');
                }
            );

            $(document).on('click', '.del', function() {
                $(this).parent().parent().remove();
            });
            $(document).on('click', '.Remove', function() {
                $(this).parent().parent().remove();
                $(".del").eq(-1).text('+ Add');
                $('.del').eq(-1).attr('class', 'btn btn-primary btn-sm addrow');
            });
    })
</script>
@endsection
