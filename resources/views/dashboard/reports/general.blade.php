@extends('dashboard.layout.index')

@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">General Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <style>
        .flex-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .row-full-height {
            height: 60vh;
            /* Adjust this height as needed */
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col (LEFT) -->
                <div class="col-md-12">
                    <!-- LINE CHART -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select Date: (<span id="reportrange"></span>)</label>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-default float-right" id="daterange-btn">
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
                        <div class="card-body">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Data Date Wise</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="chart">
                                            <canvas id="dateCountLineChart"
                                                style="min-height: 500; height: 600px; max-height: 600px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="dateCountTableData"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Data Status Wise</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="chart">
                                                <canvas id="barChartStatus"
                                                    style="min-height: 300px; height: 500px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="statusCountTableData"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Projects Permit-Status Wise</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">


                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="chart">
                                                <canvas id="projectPermitPieChart"
                                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="projectPermitWiseData"></div>
                                        </div>
                                    </div>


                                </div>
                            </div>


                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Properties Permit/Category Wise</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">


                                        <div class="col-md-8">
                                            <div class="flex-center">
                                                <h4>Properties Category Wise</h4>
                                                <div class="chart">
                                                    <canvas id="propertyCategoryPieChart"
                                                        style="min-height: 150px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <h4>Properties Category Wise</h4>
                                            <div id="propertyCategoryWiseData"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="flex-center">
                                                <h4>Properties Permit Wise</h4>
                                                <div class="chart">
                                                    <canvas id="propertyPermitPieChart"
                                                        style="min-height: 150px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <h4>Properties Permit Wise</h4>
                                            <div id="propertyPermitWiseData"></div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="flex-center">
                                                <h4>Properties Permit-Category Wise</h4>
                                                <div class="chart">
                                                    <canvas id="propertyPermitCategoryPieChart"
                                                        style="min-height: 150px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <h4>Properties Permit-Category Wise</h4>
                                            <div id="propertyPermitCategoryWiseData"></div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Properties(Available) Agent Wise</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="chart">
                                                <canvas id="barChartPropertyAgent"
                                                    style="min-height: 500px; height: 500px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="barChartPropertyAgentCountTableData"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Media Category Wise</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="chart">
                                                <canvas id="mediaCategoryPieChart"
                                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="mediaCategoryWiseData"></div>
                                        </div>
                                    </div>
                                </div>
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
        $(function() {

            let barChartApproval;
            let barChartPropertyAgent;
            let barChartStatus;
            let barChartPermit;
            let projectPermitPieChart;
            let mediaCategoryPieChart;
            let propertyPermitPieChart;
            let propertyCategoryPieChart;
            let propertyPermitCategoryPieChart;

            const donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
            };

            // Date range as a button
            $('#daterange-btn').daterangepicker({
                ranges: {
                    'Full': [moment('2023-09-15'), moment()],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf(
                        'month')]
                },
                startDate: moment().subtract(7, 'days'),
                endDate: moment()
            }, function(start, end, label) {
                $('#reportrange').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                $('#data_range_input').val(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                fetchDataAndupdateDateCountChart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

            });

            // Initialize line chart
            const dateCountLineChart = new Chart("dateCountLineChart", {
                type: "line",
                data: {
                    labels: [],
                    datasets: [{
                            label: "Communities",
                            data: [],
                            borderColor: "red",
                            fill: false
                        },
                        {
                            label: "Developers",
                            data: [],
                            borderColor: "orange",
                            fill: false
                        },
                        {
                            label: "Projects",
                            data: [],
                            borderColor: "green",
                            fill: false
                        },
                        {
                            label: "Properties",
                            data: [],
                            borderColor: "blue",
                            fill: false
                        },
                        {
                            label: "Media",
                            data: [],
                            borderColor: "gray",
                            fill: false
                        },
                        {
                            label: "Guides",
                            data: [],
                            borderColor: "pink",
                            fill: false
                        },
                        {
                            label: "Careers",
                            data: [],
                            borderColor: "yellow",
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                        display: true
                    }
                }
            });

            // Fetch data and update charts
            function fetchDataAndupdateDateCountChart(startDate, endDate) {
                $.ajax({
                    url: '/dashboard/ajaxData',
                    type: 'GET',
                    data: {
                        startDate,
                        endDate
                    },
                    success: function(response) {
                        const {
                            interval,
                            communities,
                            developers,
                            projects,
                            properties,
                            medias,
                            guides,
                            careers
                        } = response.data['getCountsByDate'];


                        const xValues = generateDateRange(new Date(startDate), new Date(endDate));
                        dateCountLineChart.data.labels = xValues;
                        updateDateCountChart(dateCountLineChart, communities, developers, projects,
                            properties,
                            medias, guides, careers);
                        dateCountLineChart.update();

                        statusCountBarChart(response.data['getCountsByStatus']);



                        updateTableDataForDate(communities, developers, projects, properties, medias,
                            guides,
                            careers);
                        updateTableDataForStatus(response.data['getCountsByStatus']);

                        createOrUpdateProjectPermitPieChart(transformDataForProjectPermitChart(response
                            .data[
                                'projectPermitCounts']));
                        updateProjectPermitWiseDataText(response.data['projectPermitCounts']);


                        createOrUpdateMediaCategoryPieChart(transformDataForPieChart(response.data[
                            'blogCategoryCounts']));
                        updateMediaCategoryWiseDataText(response.data['blogCategoryCounts']);


                        createOrUpdatePropertyPermitPieChart(transformDataForProjectPermitChart(response
                            .data['propertyPermitCounts']));
                        updatePropertyPermitWiseDataText(response.data['propertyPermitCounts']);



                        createOrUpdatePropertyCategoryPieChart(transformDataForProjectPermitChart(
                            response.data[
                                'propertyCateoryCounts']));
                        updatePropertyCategoryWiseDataText(response.data['propertyCateoryCounts']);



                        createOrUpdatePropertyPermitCategoryPieChart(
                            transformDataForPropertyPermitCategoryChart(response.data[
                                'propertyPermitCategoryCount']));
                        updatePropertyCategoryPermitWiseDataText(response.data[
                            'propertyPermitCategoryCount']);



                        propertyAgentCountBarChart(response.data['propertyAgentWiseCounts']);
                        updatePropeertyAgentWiseDataText(response.data['propertyAgentWiseCounts']);

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            function generatePieDataWiseHTML(data) {
                let totalCount = 0; // Initialize total count
                let html = '<table class="table"><thead><tr><th>Data</th><th>Count</th></tr></thead><tbody>';

                // Loop through data to generate rows and sum the counts
                data.forEach(item => {
                    html += `<tr><td>${item.status}</td><td>${item.count}</td></tr>`;
                    totalCount += item.count; // Sum the counts
                });

                // Add the total row
                html += `<tr><td><strong>Total</strong></td><td><strong>${totalCount}</strong></td></tr>`;
                html += '</tbody></table>';

                return html;
            }

            function generateBarDataWiseHTML(data) {
                let tableHtml =
                    '<table class="table table-bordered"><thead><tr><th>Status</th><th>Available</th><th>NA</th><th>Rejected</th><th>Requested</th><th>Total</th></tr></thead><tbody>';

                // Initialize variables to hold the total counts
                let totalAvailable = 0;
                let totalNA = 0;
                let totalRejected = 0;
                let totalRequested = 0;

                data.forEach(item => {
                    const available = item.count.available;
                    const na = item.count.NA;
                    const rejected = item.count.rejected;
                    const requested = item.count.requested;
                    const total = available + na + rejected + requested;

                    // Accumulate totals
                    totalAvailable += available;
                    totalNA += na;
                    totalRejected += rejected;
                    totalRequested += requested;

                    tableHtml += `<tr>
                                    <td>${item.status}</td>
                                    <td>${available}</td>
                                    <td>${na}</td>
                                    <td>${rejected}</td>
                                    <td>${requested}</td>
                                    <td>${total}</td>
                                </tr>`;
                });

                // Calculate the grand total
                const grandTotal = totalAvailable + totalNA + totalRejected + totalRequested;

                // Add the totals row at the end
                tableHtml += `<tr class="totals-row">
                                <th>Total</th>
                                <th>${totalAvailable}</th>
                                <th>${totalNA}</th>
                                <th>${totalRejected}</th>
                                <th>${totalRequested}</th>
                                <th>${grandTotal}</th>
                            </tr>`;

                tableHtml += '</tbody></table>';
                return tableHtml;
            }

            function updateProjectPermitWiseDataText(data) {

                $('#projectPermitWiseData').html(generateBarDataWiseHTML(data));

            }

            function updateMediaCategoryWiseDataText(data) {

                $('#mediaCategoryWiseData').html(generatePieDataWiseHTML(data));
            }

            function updatePropertyPermitWiseDataText(data) {

                $('#propertyPermitWiseData').html(generateBarDataWiseHTML(data));
            }

            function updatePropertyCategoryWiseDataText(data) {

                $('#propertyCategoryWiseData').html(generateBarDataWiseHTML(data));
            }

            function updatePropertyCategoryPermitWiseDataText(data) {

                let tableHtml =
                    '<table class="table table-bordered"><thead><tr><th>Status</th><th>Ready</th><th>Offplan</th><th>Rent</th><th>Total</th></tr></thead><tbody>';

                // Initialize variables to hold the total counts
                let totalready = 0;
                let totaloffplan = 0;
                let totalrent = 0;


                data.forEach(item => {
                    const ready = item.count.ready;
                    const offplan = item.count.offplan;
                    const rent = item.count.rent;

                    const total = ready + offplan + rent;

                    // Accumulate totals
                    totalready += ready;
                    totaloffplan += offplan;
                    totalrent += rent;

                    tableHtml += `<tr>
                                    <td>${item.status}</td>
                                    <td>${ready}</td>
                                    <td>${offplan}</td>
                                    <td>${rent}</td>
                                    <td>${total}</td>
                                </tr>`;
                });

                // Calculate the grand total
                const grandTotal = totalready + totaloffplan + totalrent;

                // Add the totals row at the end
                tableHtml += `<tr class="totals-row">
                                <th>Total</th>
                                <th>${totalready}</th>
                                <th>${totaloffplan}</th>
                                <th>${totalrent}</th>
                                <th>${grandTotal}</th>
                            </tr>`;

                tableHtml += '</tbody></table>';


                $('#propertyPermitCategoryWiseData').html(tableHtml);
            }


            function createOrUpdateProjectPermitPieChart(data) {
                if (projectPermitPieChart) {
                    projectPermitPieChart.data = data;
                    projectPermitPieChart.update();
                } else {
                    const pieChartCanvas = $('#projectPermitPieChart').get(0).getContext('2d');
                    projectPermitPieChart = new Chart(pieChartCanvas, {
                        type: 'bar',
                        data: data,
                        options: donutOptions
                    });
                }
            }

            function createOrUpdateMediaCategoryPieChart(data) {
                if (mediaCategoryPieChart) {
                    mediaCategoryPieChart.data = data;
                    mediaCategoryPieChart.update();
                } else {
                    const pieChartCanvas = $('#mediaCategoryPieChart').get(0).getContext('2d');
                    mediaCategoryPieChart = new Chart(pieChartCanvas, {
                        type: 'pie',
                        data: data,
                        options: donutOptions
                    });
                }
            }

            function createOrUpdatePropertyPermitPieChart(data) {
                if (propertyPermitPieChart) {
                    propertyPermitPieChart.data = data;
                    propertyPermitPieChart.update();
                } else {
                    const pieChartCanvas = $('#propertyPermitPieChart').get(0).getContext('2d');
                    propertyPermitPieChart = new Chart(pieChartCanvas, {
                        type: 'bar',
                        data: data,
                        options: donutOptions
                    });
                }
            }

            function createOrUpdatePropertyCategoryPieChart(data) {
                if (propertyCategoryPieChart) {
                    propertyCategoryPieChart.data = data;
                    propertyCategoryPieChart.update();
                } else {
                    const pieChartCanvas = $('#propertyCategoryPieChart').get(0).getContext('2d');
                    propertyCategoryPieChart = new Chart(pieChartCanvas, {
                        type: 'bar',
                        data: data,
                        options: donutOptions
                    });
                }
            }

            function createOrUpdatePropertyPermitCategoryPieChart(data) {



                if (propertyPermitCategoryPieChart) {
                    propertyPermitCategoryPieChart.data = data;
                    propertyPermitCategoryPieChart.update();
                } else {
                    const pieChartCanvas = $('#propertyPermitCategoryPieChart').get(0).getContext('2d');
                    propertyPermitCategoryPieChart = new Chart(pieChartCanvas, {
                        type: 'bar',
                        data: data,
                        options: donutOptions
                    });
                }
            }

            function transformDataForPropertyCategporyChart(data) {
                const labels = data.map(item => item.status);
                const availableData = data.map(item => item.count.available);
                const naData = data.map(item => item.count.NA);
                const rejectedData = data.map(item => item.count.rejected);
                const requestedData = data.map(item => item.count.requested);

                return {
                    labels: labels,
                    datasets: [{
                            label: 'Available',
                            data: availableData,
                            backgroundColor: 'green',
                            borderColor: 'rgba(60,141,188,0.8)',
                            borderWidth: 1
                        },
                        {
                            label: 'NA',
                            data: naData,
                            label: 'NA',
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rejected',
                            data: rejectedData,
                            backgroundColor: 'rgba(255, 0, 0, 0.5)',
                            borderColor: 'rgba(255, 0, 0, 0.5)',
                            borderWidth: 1
                        },
                        {
                            label: 'Requested',
                            data: requestedData,
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }
                    ]
                };
            }

            function transformDataForProjectPermitChart(data) {
                const labels = data.map(item => item.status);
                const availableData = data.map(item => item.count.available);
                const naData = data.map(item => item.count.NA);
                const rejectedData = data.map(item => item.count.rejected);
                const requestedData = data.map(item => item.count.requested);

                return {
                    labels: labels,
                    datasets: [{
                            label: 'Available',
                            data: availableData,
                            backgroundColor: 'green',
                            borderColor: 'rgba(60,141,188,0.8)',
                            borderWidth: 1
                        },
                        {
                            label: 'NA',
                            data: naData,
                            label: 'NA',
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rejected',
                            data: rejectedData,
                            backgroundColor: 'rgba(255, 0, 0, 0.5)',
                            borderColor: 'rgba(255, 0, 0, 0.5)',
                            borderWidth: 1
                        },
                        {
                            label: 'Requested',
                            data: requestedData,
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }
                    ]
                };
            }

            function transformDataForPropertyPermitCategoryChart(data) {
                const labels = data.map(item => item.status);
                const offplanData = data.map(item => item.count.offplan);
                const readyData = data.map(item => item.count.ready);
                const rentData = data.map(item => item.count.rent);

                return {
                    labels: labels,
                    datasets: [{
                            label: 'offplan',
                            data: offplanData,
                            backgroundColor: 'green',
                            borderColor: 'rgba(60,141,188,0.8)',
                            borderWidth: 1
                        },
                        {
                            label: 'Ready',
                            data: readyData,
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderColor: 'rgba(210, 214, 222, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rent',
                            data: rentData,
                            backgroundColor: 'rgba(255, 0, 0, 0.5)',
                            borderColor: 'rgba(255, 0, 0, 0.5)',
                            borderWidth: 1
                        },


                    ]
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

            function propertyAgentCountBarChart(data) {
                const agentNames = data.map(agent => agent.agent_name);
                const readyCounts = data.map(agent => agent.ready);
                const offplanCounts = data.map(agent => agent.offplan);
                const rentCounts = data.map(agent => agent.rent);

                const barChartData = {
                    labels: agentNames,
                    datasets: [{
                        label: 'Ready',
                        data: readyCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue color for Ready
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Offplan',
                        data: offplanCounts,
                        backgroundColor: 'rgba(255, 206, 86, 0.6)', // Yellow color for Offplan
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Rent',
                        data: rentCounts,
                        backgroundColor: 'rgba(128, 128, 128, 0.6)', // Gray color for Rent
                        borderColor: 'rgba(128, 128, 128, 1)',
                        borderWidth: 1
                    }]
                };

                const barChartOptions = {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                };

                const barChartPropertyAgentCanvas = $('#barChartPropertyAgent').get(0).getContext('2d');
                const myBarChart = new Chart(barChartPropertyAgentCanvas, {
                    type: 'bar',
                    data: barChartData,
                    options: barChartOptions
                });
            }


            function generateRandomColors(count) {
                const colors = [];
                for (let i = 0; i < count; i++) {
                    const color =
                        `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.2)`;
                    colors.push(color);
                }
                return colors;
            }


            function approvalCountBarChart(data) {
                const barChartApprovalCanvas = $('#barChartApproval').get(0).getContext('2d');
                const barChartApprovalData = {
                    labels: ['Communities', 'Developers', 'Projects', 'Properties', 'Media', 'Guides',
                        'Careers'
                    ],
                    datasets: [{
                        label: 'Requested',
                        backgroundColor: 'rgba(0, 0, 255, 1)',
                        borderColor: 'rgba(0, 0, 255, 1)',
                        data: [data.communities.requested, data.developers.requested, data.projects
                            .requested, data
                            .properties.requested, data.medias.requested, data.guides.requested,
                            data.careers
                            .requested
                        ]
                    }, {
                        label: 'Rejected',
                        backgroundColor: 'rgba(255, 0, 0, 1)',
                        borderColor: 'rgba(255, 0, 0, 1)',
                        data: [data.communities.rejected, data.communities.rejected, data.projects
                            .rejected, data
                            .properties.rejected, data.medias.rejected, data.guides.rejected, data
                            .careers
                            .rejected
                        ]
                    }, {
                        label: 'Approval',
                        backgroundColor: 'rgba(0, 128, 0, 0.5)',
                        borderColor: 'rgba(0, 128, 0, 0.5)',
                        data: [data.communities.approved, data.communities.approved, data.projects
                            .approved, data
                            .properties.approved, data.medias.approved, data.guides.approved, data
                            .careers
                            .approved
                        ]
                    }]
                };

                const barChartApprovalOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    datasetFill: false
                };

                if (barChartApproval) {
                    barChartApproval.destroy();
                }

                barChartApproval = new Chart(barChartApprovalCanvas, {
                    type: 'bar',
                    data: barChartApprovalData,
                    options: barChartApprovalOptions
                });
            }


            function statusCountBarChart(data) {
                const barChartCanvas = $('#barChartStatus').get(0).getContext('2d');
                const barChartData = {
                    labels: ['Communities', 'Developers', 'Projects', 'Properties', 'Media', 'Guides',
                        'Careers'
                    ],
                    datasets: [{
                            label: 'Available',
                            backgroundColor: 'green',
                            borderColor: 'rgba(60,141,188,0.8)',
                            data: [data.communities.available, data.developers.available, data.projects
                                .available,
                                data
                                .properties.available, data.medias.available, data.guides.available,
                                data.careers
                                .available
                            ]
                        }, {
                            label: 'NA',
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderColor: 'rgba(210, 214, 222, 1)',
                            data: [data.communities.NA, data.developers.NA, data.projects
                                .NA, data
                                .properties.NA, data.medias.NA, data.guides.NA, data
                                .careers
                                .NA
                            ]
                        }, {
                            label: 'Rejected',
                            backgroundColor: 'rgba(255, 0, 0, 0.5)',
                            borderColor: 'rgba(255, 0, 0, 0.5)',
                            data: [data.communities.rejected, data.developers.rejected, data.projects
                                .rejected, data
                                .properties.rejected, data.medias.rejected, data.guides.rejected, data
                                .careers
                                .rejected
                            ]
                        },
                        {
                            label: 'Requested',
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            data: [data.communities.requested, data.developers.requested, data.projects
                                .requested, data
                                .properties.requested, data.medias.requested, data.guides.requested,
                                data
                                .careers
                                .requested
                            ]
                        }
                    ]
                };

                const barChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    datasetFill: false
                };

                if (barChartStatus) {
                    barChartStatus.destroy();
                }

                barChartStatus = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: barChartData,
                    options: barChartOptions
                });
            }

            // Update line chart datasets
            function updateDateCountChart(chart, communities, developers, projects, properties, medias, guides,
                careers) {
                chart.data.datasets[0].data = mapCountsToDates(chart.data.labels, communities);
                chart.data.datasets[1].data = mapCountsToDates(chart.data.labels, developers);
                chart.data.datasets[2].data = mapCountsToDates(chart.data.labels, projects);
                chart.data.datasets[3].data = mapCountsToDates(chart.data.labels, properties);
                chart.data.datasets[4].data = mapCountsToDates(chart.data.labels, medias);
                chart.data.datasets[5].data = mapCountsToDates(chart.data.labels, guides);
                chart.data.datasets[6].data = mapCountsToDates(chart.data.labels, careers);
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

            function updatePropeertyAgentWiseDataText(propertyAgentWiseCounts) {
                let tableHtml =
                    '<table class="table table-bordered"><thead><tr><th>Agent Name</th><th>Ready</th><th>Offplan</th><th>Rent</th><th>Total</th></tr></thead><tbody>';

                propertyAgentWiseCounts.forEach(agent => {
                    const total = parseInt(agent.ready) + parseInt(agent.offplan) + parseInt(agent.rent);
                    const row = `<tr>
                        <td>${agent.agent_name}</td>
                        <td>${agent.ready}</td>
                        <td>${agent.offplan}</td>
                        <td>${agent.rent}</td>
                        <td>${total}</td>
                    </tr>`;
                    tableHtml += row; // Append the row to the tableHtml
                });

                tableHtml += '</tbody></table>';
                $('#barChartPropertyAgentCountTableData').html(tableHtml);
            }


            function updateTableDataForStatus(countsByStatus) {
                let tableHtml =
                    '<table class="table table-bordered"><thead><tr><th>Data</th><th>Available</th><th>NA</th><th>Rejected</th><th>Requested</th><th>Total Active</th><th>Total Inactive</th><th>Total</th></tr></thead><tbody>';

                for (let [key, value] of Object.entries(countsByStatus)) {

                    const total = value.available + value.NA + value.rejected + value.requested;
                    const active = value.available + value.NA;
                    const inactive = value.rejected + value.requested;
                    tableHtml += `<tr>
                                <td>${key.charAt(0).toUpperCase() + key.slice(1)}</td>
                                <td>${value.available}</td>
                                <td>${value.NA}</td>
                                <td>${value.rejected}</td>
                                <td>${value.requested}</td>
                                <td>${active}</td>
                                <td>${inactive}</td>
                                <td>${total}</td>
                            </tr>`;
                }

                tableHtml += '</tbody></table>';
                $('#statusCountTableData').html(tableHtml);
            }

            function updateTableDataForApproval(countsByStatus) {
                let tableHtml =
                    '<table class="table table-bordered"><thead><tr><th>Data</th><th>Requested</th><th>Rejected</th><th>Approved</th><th>Total</th></tr></thead><tbody>';

                for (let [key, value] of Object.entries(countsByStatus)) {
                    const total = value.requested + value.rejected + value.approved;
                    tableHtml += `<tr>
                                <td>${key.charAt(0).toUpperCase() + key.slice(1)}</td>
                                <td>${value.requested}</td>
                                <td>${value.rejected}</td>
                                <td>${value.approved}</td>
                                <td>${total}</td>
                            </tr>`;
                }

                tableHtml += '</tbody></table>';
                $('#approvalCountTableData').html(tableHtml);
            }

            function updateTableDataForDate(communities, developers, projects, properties, medias, guides,
                careers) {
                let tableHtml =
                    '<table class="table table-bordered"><thead><tr><th>Date</th><th>Communities</th><th>Developers</th><th>Projects</th><th>Properties</th><th>Medias</th><th>Guides</th><th>Careers</th><th>Total</th></tr></thead><tbody>';

                const allDates = Object.keys({
                    ...communities,
                    ...developers,
                    ...projects,
                    ...properties,
                    ...medias,
                    ...guides,
                    ...careers
                }).sort((a, b) => new Date(b) - new Date(a)); // Sort dates in descending order

                let totalCommunities = 0;
                let totalDevelopers = 0;
                let totalProjects = 0;
                let totalProperties = 0;
                let totalMedia = 0;
                let totalGuide = 0;
                let totalAgent = 0;

                let initialRows = '';
                let allRows = '';

                allDates.forEach((date, index) => {
                    const communityCount = communities[date] || 0;
                    const developerCount = developers[date] || 0;
                    const projectCount = projects[date] || 0;
                    const propertyCount = properties[date] || 0;
                    const mediaCount = medias[date] || 0;
                    const guideCount = guides[date] || 0;
                    const agentCount = careers[date] || 0;

                    const totalCount = communityCount + developerCount + projectCount + propertyCount +
                        mediaCount + guideCount + agentCount;

                    const row = `<tr>
                    <td>${date}</td>
                    <td>${communityCount}</td>
                    <td>${developerCount}</td>
                    <td>${projectCount}</td>
                    <td>${propertyCount}</td>
                    <td>${mediaCount}</td>
                    <td>${guideCount}</td>
                    <td>${agentCount}</td>
                    <td>${totalCount}</td>
                </tr>`;

                    if (index < 10) {
                        initialRows += row;
                    }
                    allRows += row;

                    totalCommunities += communityCount;
                    totalDevelopers += developerCount;
                    totalProjects += projectCount;
                    totalProperties += propertyCount;
                    totalMedia += mediaCount;
                    totalGuide += guideCount;
                    totalAgent += agentCount;
                });

                const grandTotal = totalCommunities + totalDevelopers + totalProjects + totalProperties +
                    totalMedia + totalGuide + totalAgent;

                const totalsRow = `<tr class="totals-row">
                <th>Total</th>
                <th>${totalCommunities}</th>
                <th>${totalDevelopers}</th>
                <th>${totalProjects}</th>
                <th>${totalProperties}</th>
                <th>${totalMedia}</th>
                <th>${totalGuide}</th>
                <th>${totalAgent}</th>
                <th>${grandTotal}</th>
            </tr>`;

                initialRows += totalsRow;
                allRows += totalsRow;

                tableHtml += initialRows + '</tbody></table>';
                tableHtml += '<button id="showAllBtn" class="btn btn-primary">Show All</button>';
                tableHtml +=
                    '<button id="hideAllBtn" class="btn btn-secondary" style="display:none;">Hide All</button>';
                tableHtml +=
                    '<table id="allDataTable" class="table table-bordered" style="display:none;"><thead><tr><th>Date</th><th>Communities</th><th>Developers</th><th>Projects</th><th>Properties</th><th>Medias</th><th>Guides</th><th>Careers</th><th>Total</th></tr></thead><tbody>';
                tableHtml += allRows + '</tbody></table>';

                $('#dateCountTableData').html(tableHtml);

                $('#showAllBtn').on('click', function() {
                    $(this).hide();
                    $('#hideAllBtn').show();
                    $('#allDataTable').show();
                });

                $('#hideAllBtn').on('click', function() {
                    $(this).hide();
                    $('#showAllBtn').show();
                    $('#allDataTable').hide();
                });
            }

            $(document).ready(function() {
                let currentPathName = window.location.pathname;
                if (currentPathName === '/dashboard/general-report') {
                    const endDate = moment();
                    const startDate = moment().subtract(7, 'days');

                    $('#reportrange').html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format(
                        'MMMM D, YYYY'));
                    $('#data_range_input').val(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format(
                        'MMMM D, YYYY'));

                    fetchDataAndupdateDateCountChart(startDate.format('YYYY-MM-DD'), endDate.format(
                        'YYYY-MM-DD'));
                }
            });

            // Add click event for download button
            $('#download-button').click(function() {
                const dateRangeText = $('#data_range_input').val();

                const dates = dateRangeText.split(' - ');
                const startDate = moment(dates[0], 'MMMM D, YYYY').format('YYYY-MM-DD');
                const endDate = moment(dates[1], 'MMMM D, YYYY').format('YYYY-MM-DD');

                $.ajax({
                    url: '/dashboard/ajaxData',
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
