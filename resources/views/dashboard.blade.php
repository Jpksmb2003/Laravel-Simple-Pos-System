@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<!-- Load the AJAX API -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load the Visualization API and the corechart and bar packages.
    google.charts.load('current', {'packages':['corechart', 'bar', 'line']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawBarChart(); // Call to draw the bar chart
        drawAreaChart(); // Call to draw the area chart
    }

    function drawBarChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Product Name');
        data.addColumn('number', 'Total Quantity');

        data.addRows([
            @foreach($sales as $sale)
                ['{{ $sale->product_name }}', {{ $sale->total_quantity }}],
            @endforeach
        ]);

        var options = {
            title: 'Product Sales Overview',
            width: 600,
            height: 600,
            hAxis: {
                title: 'Product'
            },
            vAxis: {
                title: 'Total Quantity Sold'
            },
            bars: 'horizontal' // Set to 'horizontal' for horizontal bar chart, 'vertical' for vertical
        };

        var barChart = new google.visualization.BarChart(document.getElementById('bar_chart_div'));
        barChart.draw(data, options);
    }

    function drawAreaChart() {
        var areaData = new google.visualization.DataTable();
        areaData.addColumn('string', 'Date');
        areaData.addColumn('number', 'Total Sales');

        areaData.addRows([
            @foreach($totalSales as $sale)
                ['{{ $sale->sale_date }}', {{ $sale->total_sales }}],
            @endforeach
        ]);

        var areaOptions = {
            title: 'Total Sales Analysis',
            width: 600,
            height: 600,
            hAxis: {
                title: 'Date'
            },
            vAxis: {
                title: 'Total Sales'
            },
            series: {
                0: { areaOpacity: 0.5 }
            }
        };

        var areaChart = new google.visualization.AreaChart(document.getElementById('area_chart_div'));
        areaChart.draw(areaData, areaOptions);
    }
</script>

<!-- Container for charts -->
<div class="chart-container" style="display: flex; justify-content: flex-start; align-items: flex-start;">
    <div id="bar_chart_div" style="width: 50%; height: 400px; margin-right: 20px;"></div> <!-- Bar chart div -->
    <div id="area_chart_div" style="width: 50%; height: 400px;"></div> <!-- Area chart div -->
</div>

@endsection
