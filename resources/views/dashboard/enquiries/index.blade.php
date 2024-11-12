@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Enquiries</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Enquiries</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Campaign Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Property Status</th>
                                        <th>Property Type</th>
                                        <th>No of Bedrooms</th>
                                        <th>Min Price</th>
                                        <th>Max Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($enquiries as $key => $enquiry)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $enquiry->campaign_id }}</td>
                                        <td>{{ $enquiry->name }}</td>
                                        <td>{{ $enquiry->email }}</td>
                                        <td>{{ $enquiry->mobile_country_code }} {{$enquiry->mobile}}</td>
                                        <td>{{ Str::title(Str::replace('_', ' ', $enquiry->property_status)) }}</td>
                                        <td>{{ Str::title(Str::replace('_', ' ', $enquiry->property_type)) }}</td>
                                        <td>{{ $enquiry->number_of_rooms }}</td>
                                        <td>{{ $enquiry->min_price }}</td>
                                        <td>{{ $enquiry->max_price }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
