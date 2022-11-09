function drawAreaChart(d) {

  var data = google.visualization.arrayToDataTable(d);

  var options = {
    title: 'Visual Representation of Data',
    hAxis: {title: d[0][0],  titleTextStyle: {color: '#333'}},
    vAxis: {minValue: 0}
  };

  var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}


function drawBarChart(d) {

  var data = google.visualization.arrayToDataTable(d);

  var options = {
    chart: {
      title: 'Visual Representation of Data',
    },
    bars: 'horizontal' // Required for Material Bar Charts.
  };

  var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}


function drawColumnChart(d) {

  var data = google.visualization.arrayToDataTable(d);

  var options = {
    chart: {
      title: 'Visual Representation of Data',
    }
    
  };

  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}


function drawPieChart(d) {

  var data = google.visualization.arrayToDataTable(d);

  var options = {
    title: 'Visual Representation of Data',
  };

  var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

  chart.draw(data, options);
}


function drawLineChart(d) {

  var data = google.visualization.arrayToDataTable(d);

  var options = {
    title: 'Visual Representation of Data',
    // curveType: 'function',
    legend: { position: 'bottom' }
  };

  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

  chart.draw(data, options);
}


function drawScatterChart(d) {

  var data = google.visualization.arrayToDataTable(d);

  var options = {
    title: 'Visual Representation of Data',
    hAxis: {title: d[0][0],  titleTextStyle: {color: '#333'}},
    vAxis: {title: d[0][1], titleTextStyle: {color: '#333'}},
    legend: 'none'
  };

  var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));

  chart.draw(data, options);
}


function drawHistogramChart(d) {

  var data = google.visualization.arrayToDataTable(d);

  var options = {
    title: 'Visual Representation of Data',
    legend: { position: 'none' },
  };

  var chart = new google.visualization.Histogram(document.getElementById('chart_div'));
  chart.draw(data, options);
}


function drawCandlestickChart(d) {
  var data = google.visualization.arrayToDataTable(d.slice(1), true);

  var options = {
    legend:'none'
  };

  var chart = new google.visualization.CandlestickChart(document.getElementById('chart_div'));

  chart.draw(data, options);
}
