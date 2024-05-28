@extends('dashboard.layout.index')
@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Properties Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Properties Report</li>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select Date: (<span id="reportrange"></span>)</label>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-default float-right"
                                                id="daterange-properties">
                                                <i class="far fa-calendar-alt"></i> Date Range
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end justify-content-end">
                                    <input type="hidden" value="" name="data_range_input" id="data_range_input">
                                    <button class="btn btn-danger btn-md" id="download-button">Download</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="chart">
                                                <canvas id="donutChartStatus"
                                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="chart">
                                                <canvas id="pieChartApproval"
                                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="chart">
                                                <canvas id="permitChartStatus"
                                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div id="statusWiseData"></div>
                                    <div id="approvalWiseData"></div>
                                    <div id="permitWiseData"></div>
                                </div>
                            </div>
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
    <script>
        $(function() {

            let donutChartStatus;
            let pieChartApproval;
            let barChartPermit;
            let barChart;


            const donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
            };

            function createOrUpdateDonutChartStatus(chart, data, id) {
                if (chart) {
                    chart.data = data;
                    chart.update();
                } else {
                    const chartCanvas = $('#' + id).get(0).getContext('2d');
                    chart = new Chart(chartCanvas, {
                        type: 'doughnut',
                        data: data,
                        options: donutOptions
                    });
                }
                return chart;
            }


            function createOrUpdatePieChartApproval(data) {
                if (pieChartApproval) {
                    pieChartApproval.data = data;
                    pieChartApproval.update();
                } else {
                    const pieChartCanvas = $('#pieChartApproval').get(0).getContext('2d');
                    pieChartApproval = new Chart(pieChartCanvas, {
                        type: 'pie',
                        data: data,
                        options: donutOptions
                    });
                }
            }

            function transformDataForDonutChart(data) {
                const labels = data.map(item => item.status);
                const counts = data.map(item => item.count);
                const colors = data.map(item => item.color);

                return {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: colors
                    }]
                };
            }

            function transformDataForPieChart(data) {
                const labels = data.map(item => item.status);
                const counts = data.map(item => item.count);
                const colors = data.map(item => item.color);

                return {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: colors
                    }]
                };
            }

            function generateStatusWiseDataHTML(data) {
                let html =
                    '<h5>Status Wise Data:</h5><table class="table"><thead><tr><th>Status</th><th>Count</th></tr></thead><tbody>';
                data.forEach(item => {
                    html += `<tr><td>${item.status}</td><td>${item.count}</td></tr>`;
                });
                html += '</tbody></table>';
                return html;
            }

            function generatePermitWiseDataHTML(data) {

                let html =
                    '<h5>Permit Wise Data:</h5><table class="table"><thead><tr><th>Permit</th><th>Count</th></tr></thead><tbody>';
                data.forEach(item => {
                    html += `<tr><td>${item.status}</td><td>${item.count}</td></tr>`;
                });
                html += '</tbody></table>';
                return html;
            }

            function generateProjectPropertiesDataHTML(data) {
                let html =
                    '<h5>Project Properties Data:</h5><table class="table"><thead><tr><th>Project Name</th><th>Units</th><th>Properties</th></tr></thead><tbody>';
                data.forEach(item => {
                    html +=
                        `<tr><td>${item.name}</td><td>${item.units}</td><td>${item.properties}</td></tr>`;
                });
                html += '</tbody></table>';
                return html;
            }

            function generateApprovalWiseDataHTML(data) {
                let html =
                    '<h5>Approval Wise Data:</h5><table class="table"><thead><tr><th>Approval Status</th><th>Count</th></tr></thead><tbody>';
                data.forEach(item => {
                    html += `<tr><td>${item.status}</td><td>${item.count}</td></tr>`;
                });
                html += '</tbody></table>';
                return html;
            }

            function updateStatusWiseDataText(data) {
                $('#statusWiseData').html(generateStatusWiseDataHTML(data));
            }

            function updatePermitWiseDataText(data) {

                $('#permitWiseData').html(generatePermitWiseDataHTML(data));
            }

            function updateApprovalWiseDataText(data) {
                $('#approvalWiseData').html(generateApprovalWiseDataHTML(data));
            }

            function updateProjectPropertiesDataText(data) {
                $('#projectPropertiesData').html(generateProjectPropertiesDataHTML(data));
            }

            function fetchAndRenderData(startDate, endDate) {
                $.ajax({
                    url: '/dashboard/ajaxPropertyReport',
                    type: 'GET',
                    data: {
                        startDate: startDate.format('YYYY-MM-DD'),
                        endDate: endDate.format('YYYY-MM-DD')
                    },
                    success: function(response) {
                        const transformedDataStatus = transformDataForDonutChart(response.data[
                            'statusWiseData']);
                        createOrUpdateDonutChartStatus(donutChartStatus, transformedDataStatus,
                            'donutChartStatus');
                        updateStatusWiseDataText(response.data['statusWiseData']);

                        const permitDataStatus = transformDataForDonutChart(response.data[
                            'permitWiseData']);
                        createOrUpdateDonutChartStatus(barChartPermit, permitDataStatus,
                            'permitChartStatus');
                        updatePermitWiseDataText(response.data['permitWiseData']);

                        const transformedDataApproval = transformDataForPieChart(response.data[
                            'approvalWiseData']);
                        createOrUpdatePieChartApproval(transformedDataApproval);
                        updateApprovalWiseDataText(response.data['approvalWiseData']);


                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            // Function to generate data for the bar chart
            function generateBarChartData(data) {

                const communityNames = data.map(item => item.name);
                const projectCounts = data.map(item => item.units);
                const propertyCounts = data.map(item => item.properties);

                return {
                    labels: communityNames,
                    datasets: [{
                            label: 'Units',
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            data: projectCounts
                        },
                        {
                            label: 'Properties',
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            data: propertyCounts
                        }
                    ]
                };
            }

            // Function to generate options for the bar chart
            function generateBarChartOptions() {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            stacked: false
                        }],
                        yAxes: [{
                            stacked: false,
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    hover: {
                        mode: 'nearest'
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                // Get the dataset label and the current value
                                const datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                                const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

                                // Format the label and value
                                return datasetLabel + ': ' + value;
                            }
                        }
                    }
                };
            }

            // Function to render the bar chart
            function renderBarChart(data) {

                const barChartCanvas = $('#barChart').get(0).getContext('2d');
                const barChartData = generateBarChartData(data);
                const barChartOptions = generateBarChartOptions();

                // Destroy the previous chart instance if it exists
                if (barChart) {
                    barChart.destroy();
                }

                barChart = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: barChartData,
                    options: barChartOptions
                });
            }

            $('#daterange-properties').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                startDate: moment().subtract(7, 'days'),
                endDate: moment()
            }, function(start, end) {
                // $('#daterange-properties').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                //     'MMMM D, YYYY'));

                $('#reportrange').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                $('#data_range_input').val(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                fetchAndRenderData(start, end);
            });

            $(document).ready(function() {
                const currentPathName = window.location.pathname;
                if (currentPathName === '/dashboard/properties-report') {
                    const endDate = moment();
                    const startDate = moment().subtract(7, 'days');

                    $('#reportrange').html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format(
                        'MMMM D, YYYY'));

                    $('#data_range_input').val(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format(
                        'MMMM D, YYYY'));

                    fetchAndRenderData(startDate, endDate);
                }
            });

            // Add click event for download button
            $('#download-button').click(function() {
                const dateRangeText = $('#data_range_input').val();

                const dates = dateRangeText.split(' - ');
                const startDate = moment(dates[0], 'MMMM D, YYYY').format('YYYY-MM-DD');
                const endDate = moment(dates[1], 'MMMM D, YYYY').format('YYYY-MM-DD');

                $.ajax({
                    url: '/dashboard/ajaxPropertyReport',
                    type: 'GET',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        download: 1
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

        });
    </script>
@endsection
