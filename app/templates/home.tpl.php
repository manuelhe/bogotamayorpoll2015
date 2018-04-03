<div class="content-container home">
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

  var basicDataSets = {
    age: {
      title: "Rangos de edad en años",
      columns: ['Edad','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('age');?>
    },
    sex: {
      title: "Género",
      columns: ['Género','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('gender');?>
    },
    wage: {
      title: "Ingresos Mensuales",
      columns: ['Ingresos','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('salary');?>
    },
    location: {
      title: "Lugar de Residencia",
      columns: ['Ubicación','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('location');?>
    },
    stratif: {
      title: "Estrato",
      columns: ['Estrato','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('stratif');?>
    },
    religion: {
      title: "Creencia religiosa",
      columns: ['Religión','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('religion');?>
    },
    bloodtype: {
      title: "Tipo de Sangre",
      columns: ['Tipo de Sangre','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('bloodtype');?>
    },
    willvote: {
      title: "Va a votar en las próximas elecciones",
      columns: ['Votante activo','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('willvote');?>
    },
    politicparty: {
      title: "Pertenece a un grupo político",
      columns: ['Militante','Total'],
      data: <?php echo $parsedData->getGoogleGraphData('politicparty');?>
    },
    vote: {
      title: "Votos por Candidato",
      columns: ['Candidato','Votos'],
      data: <?php echo $parsedData->getGoogleGraphData('candidate');?>
    }
  };
  var correlatedDataSets = {
    age: {
      title: "Edad vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('age');?>
    },
    sex: {
      title: "Género vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('gender');?>
    },
    wage: {
      title: "Ingresos vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('salary');?>
    },
    location: {
      title: "Ubicación vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('location');?>
    },
    stratif: {
      title: "Estrato vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('stratif');?>
    },
    religion: {
      title: "Religión vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('religion');?>
    },
    bloodtype: {
      title: "Tipo de Sangre vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('bloodtype');?>
    },
    willvote: {
      title: "Votante Activo vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('willvote');?>
    },
    politicparty: {
      title: "Militante político vs. Candidato",
      data: <?php echo $parsedData->getCorrelatedResults('politicparty');?>
    }
  };
</script>

<header>
  <h1><?php echo $config['siteTitle'];?></h1>

  <div class="intro-info">
    <div class="intro">
      <div><?php echo $config['siteIntro'];?></div>
    </div>
    <div class="info">
      <div class="total_votes">Total votos: <span><?php echo number_format($parsedData->getTotalVotes())?></span></div>
<?php if($parsedData->getTotalDisplayedVotes() < $parsedData->getTotalVotes()):?>
      <div class="total_votes">Total votos filtrados: <span><?php echo number_format($parsedData->getTotalDisplayedVotes())?></span></div>
<?php endif;?>
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
</div>