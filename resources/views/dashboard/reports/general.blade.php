@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Communities</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col (LEFT) -->
                <div class="col-md-12">
                    <!-- LINE CHART -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">New Added</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Select Date: (<span id="reportrange"></span>)</label>

                                <div class="input-group">
                                    <button type="button" class="btn btn-default float-right" id="daterange-btn">
                                        <i class="far fa-calendar-alt"></i> Date Range
                                        <i class="fas fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="chart">
                                <canvas id="myChart"
                                    style="min-height: 500; height: 600px; max-height: 600px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->


                </div>
                <!-- /.col (RIGHT) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('js')
<script>
    $(document).ready(function() {
        let currentPathName = window.location.pathname;
        if (currentPathName === '/dashboard/general-report') {
            // Get the current date
            let endDate = new Date();
            // Subtract 7 days from the current date
            let startDate = new Date();
            startDate.setDate(startDate.getDate() - 7);
            // Format the dates as 'YYYY-MM-DD'
            let formattedStartDate = startDate.toISOString().split('T')[0];
            let formattedEndDate = endDate.toISOString().split('T')[0];
            $('#reportrange').html(formattedStartDate + ' - ' + formattedStartDate);
            fetchDataAndUpdateChart(formattedStartDate, formattedEndDate);
        }

        // Date range as a button
        $('#daterange-btn').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(7, 'days'),
            endDate: moment()
        }, function(start, end) {
            $('#reportrange').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            fetchDataAndUpdateChart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });

        // Initialize chart
        const myChart = new Chart("myChart", {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    { label: "Communities", data: [], borderColor: "red", fill: false },
                    { label: "Developers", data: [], borderColor: "orange", fill: false },
                    { label: "Projects", data: [], borderColor: "green", fill: false },
                    { label: "Properties", data: [], borderColor: "blue", fill: false },
                    { label: "Media", data: [], borderColor: "gray", fill: false }
                ]
            },
            options: {
                responsive: true,
                legend: { position: 'top', display: true }
            }
        });

        // Fetch data and update chart
        function fetchDataAndUpdateChart(startDate, endDate) {
            $.ajax({
                url: '/dashboard/ajaxData',
                type: 'GET',
                data: { startDate, endDate },
                success: function(response) {
                    const { interval, communityCounts, developerCounts, projectCounts, propertyCounts, mediaCounts } = response;
                    const xValues = generateDateRange(new Date(startDate), new Date(endDate));
                    myChart.data.labels = xValues;
                    updateChart(myChart, communityCounts, developerCounts, projectCounts, propertyCounts, mediaCounts);
                    myChart.update();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Update chart datasets
        function updateChart(chart, communityCounts, developerCounts, projectCounts, propertyCounts, mediaCounts) {
            chart.data.datasets[0].data = mapCountsToDates(chart.data.labels, communityCounts);
            chart.data.datasets[1].data = mapCountsToDates(chart.data.labels, developerCounts);
            chart.data.datasets[2].data = mapCountsToDates(chart.data.labels, projectCounts);
            chart.data.datasets[3].data = mapCountsToDates(chart.data.labels, propertyCounts);
            chart.data.datasets[4].data = mapCountsToDates(chart.data.labels, mediaCounts);
        }

        // Map counts to dates
        function mapCountsToDates(labels, counts) {
            return labels.map(label => {
                const formattedLabel = moment(label, 'MMMM D, YYYY').format('YYYY-MM-DD');
                return counts[formattedLabel] || 0;
            });
        }

        // Generate date range
        function generateDateRange(startDate, endDate) {
            const dates = [];
            const currentDate = moment(startDate);
            while (currentDate <= endDate) {
                dates.push(currentDate.format('MMMM D, YYYY'));
                currentDate.add(1, 'days');
            }
            return dates;
        }
    });
</script>

@endsection
