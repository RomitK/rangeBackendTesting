@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bulk SMS Setting</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Bulk SMS Setting</li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Bulk SMS Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" files="true" method="POST" enctype="multipart/form-data" action="{{ route('dashboard.bulk-sms.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="bulk_sms_api_key">API Key</label>
                                            <input type="text" value="{{ $bulk_sms_api_key }}" class="form-control " id="bulk_sms_api_key" placeholder="Enter API KEY" name="bulk_sms_api_key">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="bulk_sms_application_id">Application ID</label>
                                            <input type="text" value="{{ $bulk_sms_application_id }}" class="form-control " id="bulk_sms_application_id" placeholder="Enter Application ID" name="bulk_sms_application_id">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="bulk_sms_message_id">Message ID</label>
                                            <input type="text" value="{{ $bulk_sms_message_id }}" class="form-control " id="bulk_sms_message_id" placeholder="Enter Message ID" name="bulk_sms_message_id">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="bulk_sms_sender_id">Sender ID</label>
                                            <input type="text" value="{{ $bulk_sms_sender_id }}" class="form-control " id="bulk_sms_sender_id" placeholder="Enter Sender ID" name="bulk_sms_sender_id">
                                        </div>
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
