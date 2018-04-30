<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width">
<?php if ($socialTags):?>
    <?php echo $socialTags;?>
<?php endif;?>
    <link rel="stylesheet" type="text/css" href="./assets/style.css?v=<?php echo $config['appVersion'];?>">
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $config['gAnalyticsId'];?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo $config['gAnalyticsId'];?>');
    </script>
<?php if (isset($header_content)):?>
    <?php echo $header_content;?>
<?php endif;?>
  </head>

  <body>
    <div class="container">
        <div class="header">
            <span class="header_logo">
                <a href="<?php echo $config['baseUrl'];?>"><?php echo $config['smSiteName'];?></a>
            </span>
            <div class="header_menu-activator" id="menu_activator">&#9776;</div>
            <div class="header_menu" id="header_menu">
                <ul>
<?php if(isset($menuItems)){foreach($menuItems as $item):?>
                    <li class="item<?php if($activeMenuItem == $item['title']){echo ' active';}?>">
                        <a href="<?php echo $item['url'];?>"><?php echo $item['title'];?></a>
                    </li>
<?php endforeach;}?>
                </ul>
            </div>
        </div>

<?php if($alerts):?>
            <div class="row">
                <div class="span12">
                    <div class="alert alert-block">
                        <a class="close">×</a>
                        <ul>
<?php foreach($alerts as $v):?>
                            <li><?php echo $v?></li>
<?php endforeach?>
                        </ul>
                    </div>
                </div>
            </div>
<?php endif?>