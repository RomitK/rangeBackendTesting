<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title')</title>
    <meta name="description" content=@yield('description')>
    <meta name="keywords" content=@yield('keywords')>
    <link rel="icon" type="image/png"
        href="@if ($favicon) {{ $favicon }} @else {{ asset('frontend/assets/images/favicon.png') }} @endif">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('dashboard/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <!-- <link rel="stylesheet" href="{{ asset('dashboard/plugins/jqvmap/jqvmap.min.css') }}"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dashboard/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/daterangepicker/daterangepicker.css') }}">


    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('dashboard/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">


    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/summernote/summernote-bs4.min.css') }}">


    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/ekko-lightbox/ekko-lightbox.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


    @yield('head')

</head>
<style>
    .arrow {
        cursor: pointer;
        opacity: 0.5;
        /* Dull appearance */
    }

    .arrow.active {
        opacity: 1;
        /* Highlighted appearance */
    }

    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }

    .image-area {
        position: relative;
        width: 100%;
        background: #333;
    }

    .image-area img {
        max-width: 100%;
        height: auto;
    }

    .remove-image {
        display: none;
        position: absolute;
        top: -10px;
        right: -10px;
        border-radius: 10em;
        padding: 2px 6px 3px;
        text-decoration: none;
        font: 700 21px/20px sans-serif;
        background: red;
        border: 3px solid #fff;
        color: #FFF;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5), inset 0 2px 4px rgba(0, 0, 0, 0.3);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        -webkit-transition: background 0.5s;
        transition: background 0.5s;
    }

    .remove-image:hover {
        color: white;
        background: #E54E4E;
        padding: 3px 7px 5px;
        top: -11px;
        right: -11px;
    }

    .remove-image:active {
        background: #E54E4E;
        top: -10px;
        right: -11px;
    }

    .nav-tabs .nav-link {
        color: white;
    }

    .nav-tabs .nav-link {
        background: #0d6efd;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
    }

    /*.select2-container--default .select2-selection--multiple .select2-selection__rendered li {*/
    /*    background: black;*/
    /*}*/
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: black;

    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dashboard/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo"
                height="60" width="60">
        </div> -->

        <!-- Navbar -->
        @include('dashboard.layout.partials.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('dashboard.layout.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('breadcrumb')
            <!-- /.content-header -->
            <!-- Main content -->
            @yield('content')
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        @include('dashboard.layout.partials.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('dashboard/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

    <!-- Bootstrap 4 -->
    <script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('dashboard/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('dashboard/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <!-- <script src="{{ asset('dashboard/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script> -->
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('dashboard/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('dashboard/plugins/moment/moment.min.js') }}"></script>

    <script src="{{ asset('dashboard/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('dashboard/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('dashboard/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <!-- Ekko Lightbox -->
    <script src="{{ asset('dashboard/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dashboard/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="{{ asset('dashboard/dist/js/demo.js') }}"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('dashboard/dist/js/pages/dashboard.js') }}"></script>


    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('dashboard/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- bs-custom-file-input -->
    <script src="{{ asset('dashboard/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('dashboard/plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {

            // Get the current date
            let endDate = new Date();
            // Subtract 7 days from the current date
            let startDate = new Date();
            startDate.setDate(startDate.getDate() - 7);
            // Format the dates as 'YYYY-MM-DD'
            let formattedStartDate = startDate.toISOString().split('T')[0];
            let formattedEndDate = endDate.toISOString().split('T')[0];
            //$('#reportrange').html(formattedStartDate + ' - ' + formattedStartDate);


            toastr.options.timeOut = 10000;
            toastr.options.closeButton = true;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif

            //Date range as a button
            $('#date_range').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf(
                            'month')]
                    },
                    // startDate: moment().subtract(7, 'days'),
                    // endDate: moment()
                },
                function(start, end) {
                    $('#date_range_show').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'))
                    $('#data_range_input').val(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'));
                })



            $('#exportProject').click(function(e) {
                e.preventDefault(); // Prevent default link behavior
                var url = $(this).attr('href'); // Get the URL of the link
                var queryParams = new URLSearchParams(window.location.search); // Get query parameters
                queryParams.set('export', '1');
                var finalUrl = queryParams.toString();
                $.ajax({
                    url: '/dashboard/projects',
                    type: 'GET',
                    data: finalUrl,
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });

            $('#exportCommunity').click(function(e) {
                e.preventDefault(); // Prevent default link behavior
                var url = $(this).attr('href'); // Get the URL of the link
                var queryParams = new URLSearchParams(window.location.search); // Get query parameters
                queryParams.set('export', '1');
                var finalUrl = queryParams.toString();
                $.ajax({
                    url: '/dashboard/communities',
                    type: 'GET',
                    data: finalUrl,
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });


            $('#exportDeveloper').click(function(e) {
                e.preventDefault(); // Prevent default link behavior
                var url = $(this).attr('href'); // Get the URL of the link
                var queryParams = new URLSearchParams(window.location.search); // Get query parameters
                queryParams.set('export', '1');
                var finalUrl = queryParams.toString();
                $.ajax({
                    url: '/dashboard/developers',
                    type: 'GET',
                    data: finalUrl,
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });

            $('#exportProperty').click(function(e) {
                e.preventDefault(); // Prevent default link behavior
                var url = $(this).attr('href'); // Get the URL of the link
                var queryParams = new URLSearchParams(window.location.search); // Get query parameters
                queryParams.set('export', '1');
                var finalUrl = queryParams.toString();
                $.ajax({
                    url: '/dashboard/properties',
                    type: 'GET',
                    data: finalUrl,
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });


            $('#exportAgent').click(function(e) {
                e.preventDefault(); // Prevent default link behavior
                var url = $(this).attr('href'); // Get the URL of the link
                var queryParams = new URLSearchParams(window.location.search); // Get query parameters
                queryParams.set('export', '1');
                var finalUrl = queryParams.toString();
                $.ajax({
                    url: '/dashboard/agents',
                    type: 'GET',
                    data: finalUrl,
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request error:', error);
                    }
                });
            });

        });

        $(function() {
            bsCustomFileInput.init();
        });

        // Initialize select2
        $('.select2').select2();

        // Update original select element based on the order in Select2
        function updateOriginalSelect(selectId) {
            var select = $('#' + selectId);
            var orderedValues = select.next('.select2-container').find('.select2-selection__rendered').children('li[title]')
                .map(function() {
                    return $(this).attr('title');
                }).get();

            select.children('option').sort(function(a, b) {
                var aIndex = orderedValues.indexOf($(a).text());
                var bIndex = orderedValues.indexOf($(b).text());
                return aIndex - bIndex;
            }).appendTo(select);
        }

        // Apply sortable functionality to select2 options
        $('.select2-selection__rendered').sortable({
            items: 'li:not(.select2-search)',
            stop: function(event, ui) {
                var selectId = $(this).closest('.select2-container').prev('select').attr('id');
                updateOriginalSelect(selectId);
            }
        });

        $('.select1').select2({
            allowClear: true

        });
        $('.search_select').select2({
            tags: true
        });
        // $input.select2();
        // $("ul.select2-selection__rendered").sortable({
        //   containment: 'parent'
        // });
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
        $('.show_confirm_1').click(function(event) {
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {

                });
        });

        $(function() {
            $('#reservation').daterangepicker({
                startDate: moment().subtract(6, 'days'), // Start date is today minus 6 days (7 days ago)
                endDate: moment(), // End date is today
                ranges: {
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            });
        });



        $(function() {
            // Summernote
            $('#summernote').summernote({
                imageTitle: {
                    specificAltField: true,
                },
                popover: {
                    image: [
                        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']],
                        ['custom', ['imageTitle']],
                    ],
                },
            });
            $('.summernote').summernote({
                imageTitle: {
                    specificAltField: true,
                },
                popover: {
                    image: [
                        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']],
                        ['custom', ['imageTitle']],
                    ],
                },
            });
        })
        $(function() {
            $(".propertyDatatable").DataTable({
                "ordering": false,
                "info": false,
                "searching": false,
                "responsive": true,
                "bPaginate": false,
                "paging": false,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#propertyTable_wrapper .col-md-6:eq(0)');
        });


        $(function() {
            $(".datatable").DataTable({
                "ordering": true,
                "info": true,
                "searching": true,
                "responsive": true,
                "lengthChange": true,
                "lengthMenu": [10, 25, 50, 75, 100, 150, 200, 250, 500, 1000],
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#propertyTable_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function() {

            var table1 = $('#applicantswithcareer').DataTable({
                "ordering": true,
                "info": true,
                "searching": true,
                "responsive": true,
                "lengthChange": true,
                "lengthMenu": [10, 25, 50, 75, 100, 150, 200, 250, 500, 1000],
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            var table2 = $('#applicantswithoutcareer').DataTable({
                "ordering": true,
                "info": true,
                "searching": true,
                "responsive": true,
                "lengthChange": true,
                "lengthMenu": [10, 25, 50, 75, 100, 150, 200, 250, 500, 1000],
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            var table3 = $('#allapplicants').DataTable({
                "ordering": true,
                "info": true,
                "searching": true,
                "responsive": true,
                "lengthChange": true,
                "lengthMenu": [10, 25, 50, 75, 100, 150, 200, 250, 500, 1000],
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });
            $('#cronTable').DataTable({
                order: [
                    [1, 'asc']
                ],
            });

            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(event) {

                var tabID = $(event.target).attr('data-bs-target');
                if (tabID === '#career') {
                    table1.columns.adjust().responsive.recalc();
                }
                if (tabID == '#withoutcareer') {
                    console.log('lll')
                    table2.columns.adjust().responsive.recalc();
                }
                if (tabID === '#all') {
                    table3.columns.adjust().responsive.recalc();
                }
            });

        });

        $("#storeForm").submit(function(e) {
            e.preventDefault();
            var isValidElements = document.querySelectorAll('.is-invalid');
            isValidElements.forEach((element) => {
                element.classList.remove('is-invalid');
            });
            var invalidFeedElements = document.querySelectorAll('.invalid-feedback');
            invalidFeedElements.forEach(box => {
                box.remove();
            });
            var formData = new FormData($("#storeForm")[0]);
            $.ajax({
                beforeSend: function() {
                    $(this).attr('disabled', true);
                },
                url: e.target.action,
                data: formData,
                type: 'POST',
                processData: false,

                contentType: false,
                success: function(response) {
                    if (response.success == true) {
                        toastr.success(response.message, 'Success');
                        setTimeout(function() {
                            location.href = response.redirect
                        }, 800);

                    } else {
                        toastr.error(response.message);
                    }
                },
                complete: function() {

                    $(this).attr('disabled', false);

                },
                error: function(response) {

                    if (response.status == 500) {
                        toastr.error(response.responseJSON.message);
                    } else if (response.status == 422) {

                        if (typeof response.responseJSON.errors === 'object' && !Array.isArray(response
                                .responseJSON.errors)) {
                            $.each(response.responseJSON.errors, function(field_name, error) {
                                if (field_name.includes(".")) {
                                    toastr.error(error);
                                    new_field_name = field_name.split(".")[0];
                                    console.log(new_field_name);
                                    $(document).find('[id=' + new_field_name + ']').addClass(
                                        'is-invalid')
                                    $(document).find('[id=' + new_field_name + ']').after(
                                        '<span class="invalid-feedback" role="alert"><strong>' +
                                        error + '</strong></span>')
                                }
                                $(document).find('[name=' + field_name + ']').addClass(
                                    'is-invalid')
                                $(document).find('[name=' + field_name + ']').after(
                                    '<span class="invalid-feedback" role="alert"><strong>' +
                                    error + '</strong></span>')
                            })
                        }
                    } else if (response.status == 420) {
                        toastr.error(response.responseJSON.errors.message);
                    } else {
                        toastr.error(response);
                    }
                }
            });
        });

        $('#showItems').on('change', function() {
            var items = this.value;
            var currentUrl = window.location.href;
            var url = new URL(currentUrl);
            url.searchParams.set("page", 1);
            url.searchParams.set("item", items);
            var newUrl = url.href;
            window.location.href = newUrl;
        });

        $(function() {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });

            $('.filter-container').filterizr({
                gutterPixels: 3
            });
            $('.btn[data-filter]').on('click', function() {
                $('.btn[data-filter]').removeClass('active');
                $(this).addClass('active');
            });
        })
    </script>

@yield('js')
@stack('scripts')
</body>

</html>
