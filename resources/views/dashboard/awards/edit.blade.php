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
                        <li class="breadcrumb-item"><a href="{{ url('dashboard/awards') }}">Awards</a></li>
                        <li class="breadcrumb-item active">Edit Award</li>
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
                            <h3 class="card-title">Edit Award Form</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-boder" id="storeForm" files="true" method="POST" enctype="multipart/form-data"
                            action="{{ route('dashboard.awards.update', $award->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" value="{{ $award->title }}"
                                                class="form-control @error('title') is-invalid @enderror" id="title"
                                                placeholder="Enter Title" name="title" required>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="position">Position</label>-->
                                    <!--        <input type="text" value="{{ $award->position }}"-->
                                    <!--            class="form-control @error('position') is-invalid @enderror" id="position"-->
                                    <!--            placeholder="Enter Position" name="position" required>-->
                                    <!--        @error('position')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="year">Year</label>
                                           
                                                
                                            <select name="year" id="year" class="form-control  @error('year') is-invalid @enderror" required>
                                                @php
                                                $startYear = 2016;
                                                $currentYear = date('Y');
                                                @endphp
                                        
                                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                                   <option value="{{ $year }}" @if($award->year == $year) selected @endif>{{ $year }}</option>
                                                @endfor
                                            </select>
                                            
                                            @error('year')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control select1 @error('status') is-invalid @enderror" id="status"
                                                name="status">
                                                @foreach (config('constants.statuses') as $key=>$value)
                                                <option value="{{ $key }}" @if($award->status == $key) selected @endif> {{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="awardOrder">Award Order</label>
                                            <input type="number" value="{{ $award->awardOrder }}"
                                                class="form-control @error('awardOrder') is-invalid @enderror" id="awardOrder"
                                                 name="awardOrder" min="1">
                                            @error('awardOrder')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="developer">Developer</label>
                                            <select class="form-control select1 @error('developer_id') is-invalid @enderror" id="developer" name="developer_id" @selected(true)>
                                                @foreach ($developers as $developer)
                                                <option value="{{ $developer->id }}" @if($award->developer_id == $developer->id) selected @endif>{{ $developer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('developer_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="trophy">Trophy</label>
                                            <div class="custom-file   @error('trophy') is-invalid @enderror">
                                                <input type="file" class="custom-file-input   @error('badge') is-invalid @enderror" id="trophy" name="trophy"
                                                accept="image/*">
                                                <label class="custom-file-label" for="trophy">Choose file</label>
                                            </div>
                                            <img id="trophyPreview"style="max-width: 100%; height: 100px; display: none;" />
                                            @error('trophy')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if ($award->trophy)
                                                <img src="{{ $award->trophy }}" alt="{{ $award->trophy }}" height="100">
                                            @endif
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="badge">Badge</label>-->
                                    <!--        <div class="custom-file   @error('badge') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input   @error('badge') is-invalid @enderror" id="badge" name="badge"-->
                                    <!--            accept="image/*">-->
                                    <!--            <label class="custom-file-label" for="badge">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('badge')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--        @if ($award->badge)-->
                                    <!--            <img src="{{ $award->badge }}" alt="{{ $award->badge }}" height="100">-->
                                    <!--        @endif-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-4">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="gallery">Gallery</label>-->
                                    <!--        <div class="custom-file   @error('gallery') is-invalid @enderror">-->
                                    <!--            <input type="file" class="custom-file-input @error('gallery') is-invalid @enderror" id="gallery" name="gallery[]"-->
                                    <!--            accept="image/*" multiple>-->
                                    <!--            <label class="custom-file-label" for="gallery">Choose file</label>-->
                                    <!--        </div>-->
                                    <!--        @error('gallery')-->
                                    <!--            <span class="invalid-feedback" role="alert">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->
                                    <!--        @error('gallery.*')-->
                                    <!--            <span class="invalid-feedback" role="alert" style="display: block">-->
                                    <!--                <strong>{{ $message }}</strong>-->
                                    <!--            </span>-->
                                    <!--        @enderror-->

                                    <!--        @if($award->gallery)-->
                                    <!--        <br><br>-->
                                    <!--            @if (count($award->gallery) > 1)-->
                                    <!--                <a class="btn btn-danger btn-sm float-right col-md-12 mb-2-->
                                    <!--                onclick="return confirm('Are you sure to delete all images?')" href="{{ route('dashboard.awards.medias.delete', $award->id) }}">-->
                                    <!--                    <i class="fas fa-trash"></i>-->
                                    <!--                    Delete All Images-->
                                    <!--                </a>-->
                                    <!--            @endif-->
                                    <!--            <div class="row">-->
                                    <!--            @foreach ($award->gallery as $img)-->
                                    <!--            <div class="col-md-6">-->
                                    <!--            <div class="image-area">-->
                                    <!--                <img src="{{ $img['path'] }}" alt="{{ $img['path'] }}" width="" height="100" style="padding: 10px">-->
                                    <!--                <a class="remove-image" onclick="return confirm('Are you sure to delete the image?')" href="{{ route('dashboard.awards.media.delete', [$award->id,$img['id'] ]) }}" style="display: inline;">&#215;</a>-->
                                    <!--            </div>-->
                                    <!--            </div>-->
                                    <!--            @endforeach-->
                                    <!--            </div>-->
                                    <!--        @endif-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary storeBtn">Submit</button>
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

        document.getElementById('trophy').addEventListener('change', function(event) {
            showPreview(event, 'trophyPreview');
        });
        
        function showPreview(event, previewId) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block'; // Show the image element
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    })

</script>
@endsection
