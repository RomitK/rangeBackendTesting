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
                        <li class="breadcrumb-item active">Edit Payment Plans</li>
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
                                        <th>Added At</th>
                                        <th>Added By</th>
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
                            <h3 class="card-title">Edit Payment Plan Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" method="POST" action="{{ route('dashboard.projects.paymentPlans.update', [$project->id, $payment->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label for="name">Payment Title</label>
                                            <input type="text" value="{{ $payment->value }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Payment Title" name="name" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="is_approved">Approval Status</label>
                                             @if(in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRejected') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $payment->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @elseif(!in_array(Auth::user()->role, config('constants.isAdmin')))
                                            <select class="form-control @error('is_approved') is-invalid @enderror" id="is_approved" name="is_approved">
                                                @foreach (config('constants.approvedRequested') as $key=>$value)
                                                <option value="{{ $key }}" @if($key === $payment->is_approved) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @error('is_approved')
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
                                                @php $paymentPlans =  $payment->paymentPlans;
                                                    $total_payment_row_count = $payment->paymentPlans->count();
                                                    $count = 0;
                                                @endphp
                                               
                                                @if (count($payment->paymentPlans) > 0)
                                                    @foreach ($paymentPlans as $paymentPlan)
                                                        <tr class="item-row">
                                                            <!--<td>-->
                                                                
                                                            <!--    <input type="text" value="{{ $paymentPlan->name }}" class="form-control" placeholder="Enter Installment" name="rows[{{ $count }}][name]" required>-->
                                                            <!--</td>-->
                                                            <td>
                                                                <input type="text" value="{{ $paymentPlan->value }}" class="form-control" placeholder="Enter Milestone" name="rows[{{ $count }}][value]" required>
                                                            </td>
                                                           
                                                            <td>
                                                                <input type="hidden" name="rows[{{ $count }}][old_payment_row_id]" value="{{ $paymentPlan->id }}">
                                                                <input type="text" value="{{ $paymentPlan->key }}" class="form-control" placeholder="Enter Percentage" name="rows[{{ $count }}][key]" required>
                                                            </td>
                                                            
                                                            <td>
                                                                <a class="btn btn-sm btn-danger" id="fix_delete"  onclick="deletePayment({{ $paymentPlan->id }})" title="Remove row">X Remove</a>
                                                            </td>
                                                        </tr>
                                                        <script>
                                                            var name = '<?php echo $paymentPlan->id; ?>';
                                                            
                                                        </script>
            
                                                        <?php $count = $count + 1; ?>
                                                    @endforeach
                                                @else
                                                    <tr class="item-row" style="border-bottom: solid 1px black">
                                                        <!--<td>-->
                                                            
                                                        <!--    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Installment" name="rows[0][name]" required>-->
                                                        <!--</td>-->
                                                        <td>
                                                            <input type="text" class="form-control @error('value') is-invalid @enderror" placeholder="Enter Milestone" name="rows[0][value]" required>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="rows[{{ $count }}][old_payment_row_id]" value="0">
                                                            <input type="text" class="form-control @error('key') is-invalid @enderror" placeholder="Enter Percentage" name="rows[0][key]" required>
                                                        </td>
                                                        
                                                        <td>
                                                        </td>
                                                    </tr>
                                                @endif
                                                 <tr id="hiderow">
                                                    <td colspan="16">
                                                        <a id="addrow" href="javascript:;"class="btn btn-info btn-sm" title="Add a row">Add New</a></td>
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
        var count = <?php echo $total_payment_row_count; ?> + 1;
        $("#addrow").click(function() {
            i++;
            count++;
            var id = count;
            $(".item-row:last").after('<tr class="item-row" style="border-bottom: solid 1px black">' +
                // '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Installment" name="rows[' + id +'][name]" required></td>' +
                '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Milestone" name="rows[' + id +'][value]" required></td>' +
                '<td class="main_td"><input type="text" class="form-control" placeholder="Enter Percentage" name="rows[' + id +'][key]" required></td>' +
                
                '<td data-title="Action" class="main_td"> <button type="button" class="btn btn-primary btn-sm addrow" id=\"updateRow' +  id +  '\">+ Add</button> ' +
                    '<a class="Remove mybtn btn btn-danger btn-sm" href="javascript:;"  title="Remove row"> x Remove</a>' +'</td><input type="hidden"  name="rows[' + id +
                    '][old_payment_row_id]" value="0"></tr>');
            if ($(".delete").length > 0) $(".delete").show();
        });

        $(document).on('click', '.delete', function() {
            $(this).parents('.item-row').remove();
            if ($(".delete").length < 1) $(".delete").hide();

        });
         $(document).on('click', '.Remove', function() {
                $(this).parent().parent().remove();
                $(".del").eq(-1).text('+ Add');
                $('.del').eq(-1).attr('class', 'btn btn-primary btn-sm addrow');
        });

    });
        function deletePayment(id) {
            if (confirm('This Data is saved ! Are you sure you want to delete this?')) {
                $.ajax({
                    url: '{{ url('/') }}/deletePaymentPlanAjax/' + id,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    type: 'DELETE',
                    success: function(result) {
                        console.log(result);
                        location.reload();

                    }
                });
            }
        }
</script>
@endsection
