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
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/leads') }}">Leads</a></li>
                        <li class="breadcrumb-item active">View Lead</li>
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
                                <tbody>
                                    <tr>
                                        <form class="form-boder"  files="true" method="POST" enctype="multipart/form-data"
                                            action="{{ route('dashboard.leads.moveToCRM', $lead->id) }}">
                                            @csrf
                                        <th>Move To CRM</th>
                                        <td>
                                            @if($lead->is_crm)
                                             Moved CRM
                                             @else
                                             
                                             <button type="submit" class="btn btn-primary storeBtn">Click Here</button>
                                             @endif
                                        </td>
                                        </form>
                                    </tr>
                                    <tr>
                                        <th>Form Name</th>
                                        <td>{{ $lead->form_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Page URL</th>
                                        <td>{{ $lead->page_url }}</td>
                                    </tr>
                                    @if($lead->property_url)
                                    <tr>
                                        <th>Property URL</th>
                                        <td>{{ $lead->property_url }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->name)
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $lead->name }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->email)
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $lead->email }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->phone)
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $lead->phone }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->detail)
                                    <tr>
                                        <th>Detail</th>
                                        <td>{{ $lead->detail }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->booking_date)
                                    <tr>
                                        <th>Booking Date</th>
                                        <td>{{ $lead->booking_date }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->booking_time)
                                    <tr>
                                        <th>Booking Time</th>
                                        <td>{{ $lead->booking_time }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->message)
                                    <tr>
                                        <th>Message</th>
                                        <td>{{ $lead->message }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->submit_date)
                                    <tr>
                                        <th>Submit Date</th>
                                        <td>{{ $lead->submit_date }}</td>
                                    </tr>
                                    @endif
                                    @if($lead->file_url)
                                    <tr>
                                        <th>File URL</th>
                                        <td>{{ $lead->file_url }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
