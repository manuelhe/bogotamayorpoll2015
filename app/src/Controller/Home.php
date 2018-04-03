<?php
namespace Controller;

/**
 * Home Controller
 *
 * @author manuel.he@gmail.com
 */
class Home extends \Mas\Controller
{
    public function response() {
        $params = isset($_POST) && $_POST ? $_POST : false;
        $parsedData = new \DataParse\GetData($this->config, $params);

        $templateVars['title'] = $this->config['config']['siteTitle'];
        $templateVars['alerts'] = $this->getAlerts();
        $templateVars['parsedData'] = $parsedData;
        $templateVars['activeMenuItem'] = 'Resultados';

        $layout = new \Mas\LayoutHelper($this->config);
        echo $layout->render('home.tpl.php', $templateVars);
    }
}
