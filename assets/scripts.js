//Interactions
(function (document, google){
	function drawBasicChart(source, elemId, chartType, settings) {
	  var chart,
	    options = {'title':source.title,
	               'width':600,
	               'height':300},
	    data = new google.visualization.DataTable(),
	    elem = document.getElementById(elemId);
	  if (settings) {
	    options = merge(options, settings);
	  }
	  chartType = chartType || 'PieChart';
	  data.addColumn('string', source.columns[0]);
	  data.addColumn('number', source.columns[1]);
	  data.addRows(source.data);
	  switch (chartType) {
	    case 'PieChart':
	      chart = new google.visualization.PieChart(elem);
	      break;
	    case 'BarChart':
	      chart = new google.visualization.BarChart(elem);
	      break;
	  };
	  chart.draw(data, options);
	}
	function drawCorrelationChart(source, elemId, settings) {
	  var data = new google.visualization.DataTable(),
	    options = {
	      width: 800,
	      height: 500
	    },
	    elem = document.getElementById(elemId),
	    chart;
	  data.addColumn('string', 'De');
	  data.addColumn('string', 'Para');
	  data.addColumn('number', 'Peso');
	  data.addRows(source.data);
	  if (settings) {
	    options = merge(options, settings);
	  }
	  chart = new google.visualization.Sankey(elem);
	  chart.draw(data, options);
	  elem.insertAdjacentHTML('afterbegin', '<h3>' + source.title + '</h3>');
	}
	var merge = function() {
	    var obj = {},
	        i = 0,
	        il = arguments.length,
	        key;
	    for (; i < il; i++) {
	        for (key in arguments[i]) {
	            if (arguments[i].hasOwnProperty(key)) {
	                obj[key] = arguments[i][key];
	            }
	        }
	    }
	    return obj;
	};
  // Load the Visualization API and the piechart package.
  google.load('visualization', '1.0', {'packages':['corechart','sankey']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(function () {
    drawBasicChart(basicDataSets.age, 'chart1_div', 'BarChart');
    drawBasicChart(basicDataSets.sex, 'chart2_div');
    drawBasicChart(basicDataSets.wage, 'chart3_div');
    drawBasicChart(basicDataSets.location, 'chart4_div', 'BarChart', {'height':500});
    drawBasicChart(basicDataSets.stratif, 'chart5_div');
    drawBasicChart(basicDataSets.religion, 'chart6_div', 'BarChart', {'height':500});
    drawBasicChart(basicDataSets.bloodtype, 'chart7_div');
    drawBasicChart(basicDataSets.willvote, 'chart8_div');
    drawBasicChart(basicDataSets.politicparty, 'chart9_div');
    drawBasicChart(basicDataSets.vote, 'chart10_div');

    drawCorrelationChart(correlatedDataSets.age, 'chart11_div');
    drawCorrelationChart(correlatedDataSets.sex, 'chart12_div');
    drawCorrelationChart(correlatedDataSets.wage, 'chart13_div');
    drawCorrelationChart(correlatedDataSets.location, 'chart14_div');
    drawCorrelationChart(correlatedDataSets.stratif, 'chart15_div');
    drawCorrelationChart(correlatedDataSets.religion, 'chart16_div');
    drawCorrelationChart(correlatedDataSets.bloodtype, 'chart17_div');
    drawCorrelationChart(correlatedDataSets.willvote, 'chart18_div');
    drawCorrelationChart(correlatedDataSets.politicparty, 'chart19_div');
  });

  //Tabs
  var tabLinks = document.querySelectorAll('.navigation li');
    var tabContent = document.querySelectorAll('#content_tabs .tab_content');
    Array.prototype.forEach.call(tabLinks, function(el, i){
      el.addEventListener('click', function(event) {
        Array.prototype.forEach.call(tabLinks, function(el2){
          el2.className = (el === el2) ? 'active' : '';
        });
        Array.prototype.forEach.call(tabContent, function(el2, i2){
          el2.className = (i === i2) ? 'tab_content active' : 'tab_content';
        });
      });
  });

  //Date inputs dynamic range adjusts
  let dateInitInput = document.getElementById('date_init');
  let dateEndInput = document.getElementById('date_end');
  if (dateInitInput && dateEndInput) {
  	dateInitInput.addEventListener('change', () => {
	  	dateEndInput.min = dateInitInput.value;
	  });
	  dateEndInput.addEventListener('change', () => {
	  	dateInitInput.max = dateEndInput.value;
	  });	
  }

}(document, google));