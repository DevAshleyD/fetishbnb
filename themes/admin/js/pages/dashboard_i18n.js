$(function () {
    //Widgets count
    $('.count-to').countTo();
    //Sales count to
    $('.sales-count-to').countTo({
        formatter: function (value, options) {
            return '$' + value.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, ' ').replace('.', ',');
        }
    });
});


// Amcharts Visitors Chart
var chartVisits;
var chartVisitsData = [];
var chartVisitsCursor;

AmCharts.ready(function () {
    // generate some data first
    generateChartData();

    // SERIAL CHART
    chartVisits = new AmCharts.AmSerialChart();

    chartVisits.dataProvider = chartVisitsData;
    chartVisits.categoryField = "date";
    chartVisits.balloon.bulletSize = 5;

    // listen for "dataUpdated" event (fired when chartVisits is rendered) and call zoomChart method when it happens
    chartVisits.addListener("dataUpdated", zoomChart);

    // AXES
    // category
    var categoryAxis = chartVisits.categoryAxis;
    categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
    categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
    categoryAxis.dashLength = 1;
    categoryAxis.minorGridEnabled = true;
    categoryAxis.twoLineMode = true;
    categoryAxis.dateFormats = [{
        period: 'fff',
        format: 'JJ:NN:SS'
    }, {
        period: 'ss',
        format: 'JJ:NN:SS'
    }, {
        period: 'mm',
        format: 'JJ:NN'
    }, {
        period: 'hh',
        format: 'JJ:NN'
    }, {
        period: 'DD',
        format: 'DD'
    }, {
        period: 'WW',
        format: 'DD'
    }, {
        period: 'MM',
        format: 'MMM'
    }, {
        period: 'YYYY',
        format: 'YYYY'
    }];

    categoryAxis.axisColor = "#DADADA";

    // value
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.axisAlpha = 0;
    valueAxis.dashLength = 1;
    chartVisits.addValueAxis(valueAxis);

    // GRAPH
    var graph = new AmCharts.AmGraph();
    graph.title = "red line";
    graph.valueField = "visits";
    graph.bullet = "round";
    graph.bulletBorderColor = "#FFFFFF";
    graph.bulletBorderThickness = 2;
    graph.bulletBorderAlpha = 1;
    graph.lineThickness = 2;
    graph.lineColor = "#5fb503";
    graph.negativeLineColor = "#efcc26";
    graph.hideBulletsCount = 50; // this makes the chartVisits to hide bullets when there are more than 50 series in selection
    chartVisits.addGraph(graph);

    // CURSOR
    chartVisitsCursor = new AmCharts.ChartCursor();
    chartVisitsCursor.cursorPosition = "mouse";
    chartVisitsCursor.pan = true; // set it to fals if you want the cursor to work in "select" mode
    chartVisits.addChartCursor(chartVisitsCursor);

    // SCROLLBAR
    var chartVisitsScrollbar = new AmCharts.ChartScrollbar();
    chartVisits.addChartScrollbar(chartVisitsScrollbar);

    chartVisits.creditsPosition = "bottom-right";

    // WRITE
    chartVisits.write("chart_visits");
});

// generate some random data, quite different range
function generateChartData() {
    totalVisits = JSON.parse(totalVisits);
    $.each(totalVisits, function( index, value ) {
        var newDate = new Date(value.date_updated);

        chartVisitsData.push({
            date: newDate,
            visits: parseInt(value.total_visits),
        });
    });
}

// this method is called when chartVisits is first inited as we listen for "dataUpdated" event
function zoomChart() {
    // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
    chartVisits.zoomToIndexes(chartVisitsData.length - 40, chartVisitsData.length - 1);
}




// Profit chart
var chartProfits;
var chartProfitsData = [];
totalSales = JSON.parse(totalSales);
$.each(totalSales, function( index, value ) {
    var newDate = new Date(value.date_added);

    chartProfitsData.push({
        "date": newDate,
        "value": value.total_amount,
    });
});

AmCharts.ready(function () {
    // SERIAL CHART
    chartProfits = new AmCharts.AmSerialChart();

    chartProfits.dataProvider = chartProfitsData;
    chartProfits.dataDateFormat = "YYYY-MM-DD";
    chartProfits.categoryField = "date";


    // AXES
    // category
    var categoryAxis = chartProfits.categoryAxis;
    categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
    categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
    categoryAxis.gridAlpha = 0.1;
    categoryAxis.minorGridAlpha = 0.1;
    categoryAxis.axisAlpha = 0;
    categoryAxis.minorGridEnabled = true;
    categoryAxis.inside = true;

    // value
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.tickLength = 0;
    valueAxis.axisAlpha = 0;
    valueAxis.showFirstLabel = false;
    valueAxis.showLastLabel = false;
    chartProfits.addValueAxis(valueAxis);

    // GRAPH
    var graph = new AmCharts.AmGraph();
    graph.dashLength = 3;
    graph.lineColor = "#00CC00";
    graph.valueField = "value";
    graph.dashLength = 3;
    graph.bullet = "round";
    graph.balloonText = "[[category]]<br><b><span style='font-size:14px;'>value:[[value]]</span></b>";
    chartProfits.addGraph(graph);

    // CURSOR
    var chartProfitsCursor = new AmCharts.ChartCursor();
    chartProfitsCursor.valueLineEnabled = true;
    chartProfitsCursor.valueLineBalloonEnabled = true;
    chartProfits.addChartCursor(chartProfitsCursor);

    // SCROLLBAR
    var chartProfitsScrollbar = new AmCharts.ChartScrollbar();
    chartProfits.addChartScrollbar(chartProfitsScrollbar);

    // HORIZONTAL GREEN RANGE
    var guide = new AmCharts.Guide();
    guide.value = 10;
    guide.toValue = 20;
    guide.fillColor = "#00CC00";
    guide.inside = true;
    guide.fillAlpha = 0.2;
    guide.lineAlpha = 0;
    valueAxis.addGuide(guide);

    // TREND LINES
    // first trend line
    var trendLine = new AmCharts.TrendLine();
    // note,when creating date objects 0 month is January, as months are zero based in JavaScript.
    trendLine.initialDate = new Date(2012, 0, 2, 12); // 12 is hour - to start trend line in the middle of the day
    trendLine.finalDate = new Date(2012, 0, 11, 12);
    trendLine.initialValue = 10;
    trendLine.finalValue = 19;
    trendLine.lineColor = "#CC0000";
    chartProfits.addTrendLine(trendLine);

    // second trend line
    trendLine = new AmCharts.TrendLine();
    trendLine.initialDate = new Date(2012, 0, 17, 12);
    trendLine.finalDate = new Date(2012, 0, 22, 12);
    trendLine.initialValue = 16;
    trendLine.finalValue = 10;
    trendLine.lineColor = "#CC0000";
    chartProfits.addTrendLine(trendLine);

    // WRITE
    chartProfits.write("chart_profits");
});