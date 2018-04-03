<?php
namespace Controller;

/**
 * About Controller
 *
 * @author manuel.he@gmail.com
 */
class About extends \Mas\Controller
{
  public function response() {
    $templateVars['title'] = '¿Qué es Voto Colombia 2018';
    $templateVars['alerts'] = $this->getAlerts();
    $templateVars['activeMenuItem'] = 'Sobre nosotros';

    $layout = new \Mas\LayoutHelper($this->config);
    echo $layout->render('about.tpl.php', $templateVars);
  }
}
