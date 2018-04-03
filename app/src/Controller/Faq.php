<?php
namespace Controller;

/**
 * FAQ Controller
 *
 * @author manuel.he@gmail.com
 */
class Faq extends \Mas\Controller
{
    public function response() {
        $templateVars['title'] = 'Preguntas Frecuentes';
        $templateVars['alerts'] = $this->getAlerts();
        $templateVars['activeMenuItem'] = 'Preguntas frecuentes';
        $templateVars['socialThumb'] = '/assets/images/faq_thumb.jpg';

        $layout = new \Mas\LayoutHelper($this->config);
        echo $layout->render('faq.tpl.php', $templateVars);
    }
}
