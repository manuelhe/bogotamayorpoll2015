<?php namespace DataParse;

error_reporting(E_ALL);


class FilterData
{
  private $totalRows;
  private $data;
  private $dataDesc;
  const VOTE_INDEX = 11;

  public function __construct()
  {
    $this->dataDesc = array_fill(0, 12, []);
    $this->data = [];
    $this->getData();
  }

  private function getData()
  {
    $count = 0;
    if (($handle = fopen("data/data.csv", "r")) !== false) {
      while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        $count++;
        if ($count < 2) {
          continue;
        }
        $num = count($row);
        $rowData = [];
        for ($c = 0; $c < $num; $c++) {
          if ($c > 0 && !in_array($row[$c], $this->dataDesc[$c])) {
            $this->dataDesc[$c][] = $row[$c];
          }
          $rowData[$c] = $c > 0 ?
            array_search($row[$c], $this->dataDesc[$c]) :
            strtotime($row[$c]);
        }
        $this->data[] = $rowData;
      }
      fclose($handle);
    }
    $this->totalRows = count($this->data);
  }

  public function getResultsFrom($index)
  {
    if (!isset($this->dataDesc[$index])) {
      return false;
    }

    $ret = [];

    //Data filtering
    foreach ($this->data as $v) {
      $idx = $v[$index];
      if (!isset($ret[$idx])) {
        $ret[$idx] = [
          'value'   => $this->dataDesc[$index][$idx],
          'total'   => 0,
          'percent' => 0
        ];
      }
      $ret[$v[$index]]['total']++;
    }

    //Get Percent values
    foreach ($ret as $k => $v) {
      $ret[$k]['percent'] = 100 * $v['total'] / $this->totalRows;
    }

    //Sort results
    foreach ($ret as $key => $row) {
      $total[$key]  = $row['total'];
    }
    array_multisort($total, SORT_DESC, $ret);

    return $ret;
  }

  public function getCorrelatedResults($index)
  {
    self::VOTE_INDEX;
    $ret = [];
    foreach ($this->data as $v) {
      $idx = $v[$index] . '-' . $v[self::VOTE_INDEX];
      if (!isset($ret[$idx])) {
        $ret[$idx] = [
          $this->dataDesc[$index][$v[$index]],
          $this->dataDesc[self::VOTE_INDEX][$v[self::VOTE_INDEX]],
          0
        ];
      }
      $ret[$idx][2]++;
    }
    return json_encode(array_values($ret));
  }

  public function getGoogleGraphData($index)
  {
    $data = $this->getResultsFrom($index);

    $ret = [];
    foreach ($data as $v) {
      $ret[] = [$v['value'], $v['total']];
    }
    return json_encode($ret);
  }

  public function getTotalVotes()
  {
    return $this->totalRows;
  }
}

$parsedData = new \DataParse\FilterData();

$baseUrl = ((
  (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") ||
  (isset($_SERVER['HTTP_REFERER']) && strpos(strtolower($_SERVER['HTTP_REFERER']), 'https') !== FALSE ) ||
  (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') ||
  (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'],'secure') !== FALSE)) ? 'https' : 'http') .
  '://'. str_replace('//','/',$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/');

?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Resultados Encuesta Voto Alcaldía de Bogotá 2015</title>
    <meta name="viewport" content="width=device-width">
    <meta property="og:title" content="Resultados Intención de Voto Alcadía de Bogotá 2015">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Voto Bogotá 2015">
    <meta property="og:url" content="<?php echo $baseUrl?>">
    <meta property="og:image" content="<?php echo $baseUrl?>/res/fb_thumb.png">
    <meta property="og:image:width" content="1300">
    <meta property="og:image:height" content="780">
    <meta property="og:description" content="Esta fue una encuesta informal sin ninguna base sólida que no intentó determinar nada, igual que las que hacen los grandes encuestadores del mundo mundial. Esta fue una encuesta anónima no patrocinada por ninguna entidad, partido o candidato.">
    <meta name="twitter:card" content="player">
    <meta name="twitter:title" content="Resultados Intención de Voto Alcadía de Bogotá 2015">
    <meta name="twitter:url" content="<?php echo $baseUrl?>">
    <meta name="twitter:image" content="<?php echo $baseUrl?>/res/twt_thumb.jpg">
    <meta name="twitter:player:width" content="435">
    <meta name="twitter:player:height" content="261">
    <meta name="twitter:player" content="<?php echo $baseUrl?>">
    <meta name="twitter:description" content="Esta fue una encuesta informal sin ninguna base sólida que no intentó determinar nada, igual que las que hacen los grandes encuestadores del mundo mundial. Esta fue una encuesta anónima no patrocinada por ninguna entidad, partido o candidato.">
    <meta name="twitter:site" content="@fractalsoftware">

    <style>
    *,*:before,*:after{box-sizing:border-box;}html{font-size: 100%;}body{font: 16px/1.4 Helvetica, Arial, sans-serif;margin:0;padding:0;background:#c8d3d9}h1,h2,h3,h4{margin-top:0}
    .row{display:table;width:100%}.row:before,.row:after{content:" ";display:table}.column{float:left;padding-left:.83333rem;padding-right:.83333rem;width:100%}.colspan-1{width:8.33333%}.colspan-2{width:16.66667%}.colspan-3{width:25%}.colspan-4{width:33.33333%}.colspan-5{width:41.66667%}.colspan-6{width:50%}.colspan-7{width:58.33333%}.colspan-8{width:66.66667%}.colspan-9{width:75%}.colspan-10{width:83.33333%}.colspan-11{width:91.66667%}.colspan-12{width:100%}
      .container{width:80rem;margin:0 auto;padding:2rem 1rem;border-left:1px solid #ccc;border-left:1px solid #bbb;background:#fff}
      .navigation ul{margin:2rem 0 0;padding:0;list-style:none;background:#fff}.navigation li{display:inline-block;cursor:pointer;padding:10px 30px;color:#375DD8}.navigation li.active,.navigation li:hover{border-bottom:5px solid #FF8436}.navigation li.active{color:#789}.tab_content{border:1px solid #d5d6d9;display:none;padding:1em}.tab_content.active{display:block}.correlated-graph{padding: 2rem 0}.correlated-graph h3{margin-bottom:0.5rem;font-size:1.2rem;color:#666;text-align:center}.correlated-graph > div{margin:0 auto;width:800px}.total_votes{margin:1rem;text-align:right;color:#666}.total_votes span{font-weight:bold;color:#35b}
    </style>

    <!--Load the AJAX API-->

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      var basicDataSets = {
        age: {
          title: "Rangos de edad en años",
          columns: ['Edad','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(2);?>
        },
        sex: {
          title: "Género",
          columns: ['Género','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(3);?>
        },
        wage: {
          title: "Ingresos Mensuales",
          columns: ['Ingresos','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(4);?>
        },
        location: {
          title: "Lugar de Residencia",
          columns: ['Ubicación','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(5);?>
        },
        stratif: {
          title: "Estrato",
          columns: ['Estrato','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(6);?>
        },
        religion: {
          title: "Creencia religiosa",
          columns: ['Religión','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(7);?>
        },
        bloodtype: {
          title: "Tipo de Sangre",
          columns: ['Tipo de Sangre','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(8);?>
        },
        willvote: {
          title: "Va a votar en las próximas elecciones",
          columns: ['Votante activo','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(9);?>
        },
        politicparty: {
          title: "Pertenece a un grupo político",
          columns: ['Militante','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(10);?>
        },
        vote: {
          title: "Votos por Candidato",
          columns: ['Candidato','Votos'],
          data: <?php echo $parsedData->getGoogleGraphData(11);?>
        }
      };
      var correlatedDataSets = {
        age: {
          title: "Edad vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(2);?>
        },
        sex: {
          title: "Género vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(3);?>
        },
        wage: {
          title: "Ingresos vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(4);?>
        },
        location: {
          title: "Ubicación vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(5);?>
        },
        stratif: {
          title: "Estrato vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(6);?>
        },
        religion: {
          title: "Religión vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(7);?>
        },
        bloodtype: {
          title: "Tipo de Sangre vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(8);?>
        },
        willvote: {
          title: "Votante Activo vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(9);?>
        },
        politicparty: {
          title: "Militante político vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(10);?>
        }
      };

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart','sankey']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(function () {
        drawBasicChart(basicDataSets.age, 'chart1_div', 'BarChart');
        drawBasicChart(basicDataSets.sex, 'chart2_div');
        drawBasicChart(basicDataSets.wage, 'chart3_div');
        drawBasicChart(basicDataSets.location, 'chart4_div', 'BarChart');
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

    </script>
  </head>

  <body>
    <div class="container">
      <!--Div that will hold the pie chart-->
      <h1>Resultados Intención de Voto Alcaldía de Bogotá 2015</h1>

      <p>
        Esta fue una encuesta informal sin ninguna base sólida que no intentó determinar nada, igual que las que hacen los grandes encuestadores del mundo mundial. Esta fue una encuesta anónima no patrocinada por ninguna entidad, partido o candidato.
      </p>
      <p>Los siguiente resultados no pueden ser tomados más que por un corto y tartamudo vaticinio.</p>
      <p>La tabla de datos puede descargarse desde el siguiente enlace: <a href="./data/data.csv" download="ResultadosVotoBogota2015.csv">Resultados Voto Bogota 2015</a></p>
      <div class="total_votes">Total votos: <span><?php echo $parsedData->getTotalVotes()?></span></div>

      <div class="navigation">
        <ul>
          <li class="active">Resultados Generales</li>
          <li class="">Resultados Correlacionados</li>
          <li class="">Algunas Conclusiones</li>
        </ul>
      </div>

      <div id="content_tabs">

        <div class="tab_content active">
          <div class="row">
            <div id="chart1_div" class="column colspan-6"></div>
            <div id="chart2_div" class="column colspan-6"></div>
          </div>
          <div class="row">
            <div id="chart3_div" class="column colspan-6"></div>
            <div id="chart4_div" class="column colspan-6"></div>
          </div>
          <div class="row">
            <div id="chart5_div" class="column colspan-6"></div>
            <div id="chart6_div" class="column colspan-6"></div>
          </div>
          <div class="row">
            <div id="chart7_div" class="column colspan-6"></div>
            <div id="chart8_div" class="column colspan-6"></div>
          </div>
          <div class="row">
            <div id="chart9_div" class="column colspan-6"></div>
            <div id="chart10_div" class="column colspan-6"></div>
          </div>
        </div>

        <div class="tab_content">
          <div id="chart11_div" class="correlated-graph"></div>
          <div id="chart12_div" class="correlated-graph"></div>
          <div id="chart13_div" class="correlated-graph"></div>
          <div id="chart14_div" class="correlated-graph"></div>
          <div id="chart15_div" class="correlated-graph"></div>
          <div id="chart16_div" class="correlated-graph"></div>
          <div id="chart17_div" class="correlated-graph"></div>
          <div id="chart18_div" class="correlated-graph"></div>
          <div id="chart19_div" class="correlated-graph"></div>
        </div>

        <div class="tab_content">
          <h2>Algunas conclusiones obias y otras no tanto</h2>
          <ul>
            <li>Tengo muy pocos amigos y soy un pésimo community manager.</li>
            <li>O existe una gran colonia rusa ortodoxa en Colombia o muchos no saben ni a que religión pertenecen.</li>
            <li>Son los jóvenes los que más participan en redes sociales pero, como siempre, los viejos somos los que elegimos al final.</li>
            <li>La candidata Clara López es la que más maquinaria política tiene detrás suyo, Casí el 40% de sus electores son militantes activos de algún grupo político, seguramente del Polo o de los Progresistas. Igual sucede con Francisco Santos, pero la muestra es muy pequeña para determinar algo.</li>
            <li>Muchos de los votantes de Daniel Raisberck son Ateos, pero los Agnósticos están con Enrique Peñalosa.</li>
            <li>El único Pastafarinista va a votar por Clara López. Ya sabemos con quien está el Monstruo Volador del Espagueti con Albóndigas.</li>
            <li>Por supuesto, los cristianos están con Ricardo Arias.</li>
            <li>¿Qué tienen en común un Zorastrasista, un Fetichista y un Rastafari? Votar por Daniel Raisberck y armar unas parrandas voladoras.</li>
            <li>Así llegue en taxi a los debates, a Francisco Santos no lo quieren ni mis familiares y amigos afiliados al gremio.</li>
            <li>Esto de estar sin trabajo me está empezando a afectar. Si siguen así las cosas, voy a terminar lanzándome a algún puesto de elección popular o aun peor, me contratan en La FM para que les amañe sus encuestas.</li>
            <li>Los más jóvenes son los que más apoyan a Clara López, un 77% de sus electores están entre los 18 y los 28 años. Así mismo los más políticamente apáticos están entre los 18 y los 23 años.</li>
            <li>Las mujeres tienen menos intención de participación en política. Las mismas son las mayores electores de candidatos como Francisco Santos y Ricardo Arias.</li>
          </ul>
        </div>

      </div>

    </div>
  </body>
  <script>
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
  </script>

</html>
