<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $config['siteTitle'];?></title>
    <meta name="viewport" content="width=device-width">
    <meta property="og:title" content="<?php echo $config['siteTitle'];?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="<?php echo $config['smSiteName'];?>">
    <meta property="og:url" content="<?php echo $config['baseUrl']?>">
    <meta property="og:image" content="<?php echo $config['baseUrl']?><?php echo $config['ogImage'];?>">
    <meta property="og:image:width" content="1300">
    <meta property="og:image:height" content="780">
    <meta property="og:description" content="<?php echo $config['smDescription'];?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $config['siteTitle'];?>">
    <meta name="twitter:url" content="<?php echo $config['baseUrl']?>">
    <meta name="twitter:image" content="<?php echo $config['baseUrl']?><?php echo $config['twitter']['image'];?>">
    <meta name="twitter:description" content="<?php echo $config['smDescription'];?>">
    <meta name="twitter:site" content="<?php echo $config['twitter']['site'];?>">
    <meta name="twitter:creator" content="<?php echo $config['twitter']['creator'];?>">
    <link rel="stylesheet" type="text/css" href="./assets/style.css">

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
          data: <?php echo $parsedData->getGoogleGraphData(11);?>
        },
        stratif: {
          title: "Estrato",
          columns: ['Estrato','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(5);?>
        },
        religion: {
          title: "Creencia religiosa",
          columns: ['Religión','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(6);?>
        },
        bloodtype: {
          title: "Tipo de Sangre",
          columns: ['Tipo de Sangre','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(7);?>
        },
        willvote: {
          title: "Va a votar en las próximas elecciones",
          columns: ['Votante activo','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(8);?>
        },
        politicparty: {
          title: "Pertenece a un grupo político",
          columns: ['Militante','Total'],
          data: <?php echo $parsedData->getGoogleGraphData(9);?>
        },
        vote: {
          title: "Votos por Candidato",
          columns: ['Candidato','Votos'],
          data: <?php echo $parsedData->getGoogleGraphData(10);?>
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
          data: <?php echo $parsedData->getCorrelatedResults(11);?>
        },
        stratif: {
          title: "Estrato vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(5);?>
        },
        religion: {
          title: "Religión vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(6);?>
        },
        bloodtype: {
          title: "Tipo de Sangre vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(7);?>
        },
        willvote: {
          title: "Votante Activo vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(8);?>
        },
        politicparty: {
          title: "Militante político vs. Candidato",
          data: <?php echo $parsedData->getCorrelatedResults(9);?>
        }
      };
    </script>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $config['gAnalyticsId'];?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo $config['gAnalyticsId'];?>');
    </script>
  </head>

  <body>
    <div class="container">
      <header>
        <h1><?php echo $config['siteTitle'];?></h1>
        <div class="intro-info">
          <div class="intro">
            <div><?php echo $config['siteIntro'];?></div>
          </div>
          <div class="info">
            <div class="total_votes">Total votos: <span><?php echo $parsedData->getTotalVotes()?></span></div>
            <div class="last_update">Última actualización: <span><?php echo $parsedData->getLastUpdate()?></span></div>
          </div>
        </div>


<?php if ($config['filters']['enabled']):?>
        <!-- Filters -->
        <span id="show-filters" class="filter-cta">&#x25A6; Filtrar Resultados</span>
        <form id="filters" action="./" method="post">
          <div class="dates">
            <label for="date_init">
              <span class="label">Fecha Inicio:</span>
              <input type="date" id="date_init" name="date_init"
                     min="<?php echo $parsedData->getMinDate();?>"
                     max="<?php echo $parsedData->getMaxDate();?>"
                     value="<?php echo $parsedData->getFilteredInitDate();?>"
                     required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
              <span class="validity"></span>
            </label>
            <label for="date_end">
              <span class="label">Fecha Fin:</span>
              <input type="date" id="date_end" name="date_end"
                     min="<?php echo $parsedData->getMinDate();?>"
                     max="<?php echo $parsedData->getMaxDate();?>"
                     value="<?php echo $parsedData->getFilteredEndDate();?>"
                     required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
              <span class="validity"></span>
            </label>
          </div>
          <label>
            <span class="label">&nbsp;</span>
            <button type="submit" class="button">Filtrar</button>
          </label>

        </form>
<?php endif;?>
      </header>

      <div class="navigation">

        <ul>
          <li class="active">Resultados Generales</li>
          <li class="">Resultados Correlacionados</li>
<?php if ($config['conclusions']['enabled']):?>
          <li class="">Algunas Conclusiones</li>
<?php endif;?>
        </ul>

      </div>

      <div id="content_tabs">

        <div class="tab_content active">
            <div id="chart1_div"></div>
            <div id="chart2_div"></div>
            <div id="chart3_div"></div>
            <div id="chart4_div"></div>
            <div id="chart5_div"></div>
            <div id="chart6_div"></div>
            <div id="chart7_div"></div>
            <div id="chart8_div"></div>
            <div id="chart9_div"></div>
            <div id="chart10_div"></div>
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
<?php if ($config['conclusions']['enabled']):?>
        <div class="tab_content">
          <h2><?php echo $config['conclusions']['title'];?></h2>
          <?php echo $config['conclusions']['body'];?>
        </div>
<?php endif;?>
      </div>

      <footer>
        <div>
          <a href="https://github.com/manuelhe/bogotamayorpoll2015" target="_blank">GitHub</a>
          |
          <a href="<?php echo $config['facebookUrl'];?>">Facebook</a>
          |
          Hecho por <a href="https://twitter.com/fractalsoftware">@fractalsoftware</a>
          |
          Versión: <?php echo $config['appVersion'];?>
        </div>
        <div>
          <a rel="license" href="//creativecommons.org/licenses/by/4.0/" title="Creative Commons Attribution 4.0 International license" target="_blank"><img src="//i.creativecommons.org/l/by/4.0/88x31.png" alt="License"></a>
        </div>

      </footer>

    </div>
    <script type="text/javascript" src="./assets/scripts.js"></script>
  </body>
</html>
