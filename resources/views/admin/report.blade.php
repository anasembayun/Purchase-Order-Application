@extends('admin.layouts.admin')

@section('title', __('Reporting'))

@section('content')
<div class="col-md-12">
    <div class="pull-right">
        <input name="dates" style="width: 100%"/>
    </div>
</div>
<br>
<div class="col-md-6">
    <div id="registration_usage" class="x_panel tile overflow_hidden">
        <div class="x_title">
            <h2>Expenses</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-wrench"></i>
                    </a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div>
                <canvas id="canvas"></canvas>
            </div>
        </div>
    </div>
</div> 

@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/dashboard.js')) }}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://pivottable.js.org/dist/pivot.js "></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        var jsonfile = <?php echo $datas; ?>;
        var labels = jsonfile.duasatu.months.map(function(e) {
        return e.month;
        });
        var data_2021 = jsonfile.duasatu.months.map(function(e) {
        return e.total;
        });
        var data_2022 = jsonfile.duadua.months.map(function(e) {
        return e.total;
        });
        var data_2023 = jsonfile.duatiga.months.map(function(e) {
        return e.total;
        });

        var ctx = canvas.getContext('2d');
        var config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '2021',
                data: data_2021,
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            },
            {
                label: '2022',
                data: data_2022,
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1
            },
            {
                label: '2023',
                data: data_2023,
                borderColor: 'rgb(255, 205, 86)',
                tension: 0.1
            }
        ]
        }
        };

        var chart = new Chart(ctx, config);
    </script>
    <script>
        $('input[name="dates"]').daterangepicker();
        var productPriceRange = {
        _defaults: {
            type: 'doughnut',
            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
            data: {
                labels: [
                    '<script 50000',
                    '50000 - 99999',
                    '100000 - 999999',
                    '>= 1000000'
                ],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        "#3498DB",
                        "#3498DB",
                        "#9B59B6",
                        "#E74C3C",
                    ],
                    hoverBackgroundColor: [
                        "#36CAAB",
                        "#49A9EA",
                        "#B370CF",
                        "#E95E4F",
                    ]
                }]
            },
            options: {
                legend: false,
                responsive: false
            }
        },
    }
    </script>  
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/dashboard.css')) }}
    <link rel="stylesheet" type="text/css" href="https://pivottable.js.org/dist/pivot.css">
@endsection
