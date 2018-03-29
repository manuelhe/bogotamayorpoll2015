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
        $template = new \Mas\Template($this->config['config']['templatesDir']);
        $params = isset($_POST) && $_POST ? $_POST : false;
        $parsedData = new \DataParse\GetData($this->config, $params);

        $template->setVar('config', $this->config['config']);
        $template->setVar('alerts', $this->getAlerts());
        $template->setVar('parsedData', $parsedData);
        echo $template->parse('home.tpl.php');
    }
}
