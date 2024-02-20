@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Socail Setting</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Socail Setting</li>
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
                            <h3 class="card-title">Socail Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" files="true" method="POST" enctype="multipart/form-data" action="{{ route('dashboard.social-info.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" value="{{ $email }}" class="form-control " id="email" placeholder="Enter Email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="contact_number">Contact Number</label>
                                            <input type="text" value="{{ $contact_number }}" class="form-control " id="contact_number" placeholder="Enter Contact Number" name="contact_number">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="telephone_number">Telephone Number</label>
                                            <input type="text" value="{{ $telephone_number }}" class="form-control " id="telephone_number" placeholder="Enter Telephone Number" name="telephone_number">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="tollfree_number">Toll Free Number</label>
                                            <input type="text" value="{{ $tollfree_number }}" class="form-control " id="tollfree_number" placeholder="Enter Toll Free Number" name="tollfree_number">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="whatsapp">WhatsApp URL</label>
                                            <input type="text" value="{{ $whatsapp }}" class="form-control " id="whatsapp" placeholder="Enter WhatsApp Link" name="whatsapp">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="whatsapp_number">WhatsApp Number</label>
                                            <input type="text" value="{{ $whatsapp_number }}" class="form-control " id="whatsapp_number" placeholder="Enter WhatsApp Number" name="whatsapp_number">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="facebook">Facebook</label>
                                            <input type="text" value="{{ $facebook }}" class="form-control " id="facebook" placeholder="Enter Facebook Page Link" name="facebook">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="twitter">Twitter</label>
                                            <input type="text" value="{{ $twitter }}" class="form-control " id="twitter" placeholder="Enter Twitter Page Link" name="twitter">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="linkedin">Linkedin</label>
                                            <input type="text" value="{{ $linkedin }}" class="form-control " id="linkedin" placeholder="Enter Linkedin Page Link" name="linkedin">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="instagram">Instagram</label>
                                            <input type="text" value="{{ $instagram }}" class="form-control " id="instagram" placeholder="Enter Instagram Page Link" name="instagram">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tiktok">Tiktok</label>
                                            <input type="text" value="{{ $tiktok }}" class="form-control " id="tiktok" placeholder="Enter Tiktok Page Link" name="tiktok">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="youtube">Youtube</label>
                                            <input type="text" value="{{ $youtube }}" class="form-control " id="youtube" placeholder="Enter Youtube Page Link" name="youtube">
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
