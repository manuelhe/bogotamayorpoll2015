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
        $filterFields = new \DataParse\FilterFields($this->config, $params);

        $templateVars['title'] = $this->config['config']['siteTitle'];
        $templateVars['alerts'] = $this->getAlerts();
        $templateVars['parsedData'] = $parsedData;
        $templateVars['ageValues'] = $filterFields->getValuesFor('age');
        $templateVars['genderValues'] = $filterFields->getValuesFor('gender');
        $templateVars['bloodtypeValues'] = $filterFields->getValuesFor('bloodtype');
        $templateVars['willvoteValues'] = $filterFields->getValuesFor('willvote');
        $templateVars['politicpartyValues'] = $filterFields->getValuesFor('politicparty');
        $templateVars['candidateValues'] = $filterFields->getValuesFor('candidate');
        $templateVars['locationValues'] = $filterFields->getValuesFor('location');
        $templateVars['religionValues'] = $filterFields->getValuesFor('religion');
        $templateVars['salaryValues'] = $filterFields->getValuesFor('salary');
        $templateVars['stratifValues'] = $filterFields->getValuesFor('stratif');
        $templateVars['activeMenuItem'] = 'Resultados';

        $layout = new \Mas\LayoutHelper($this->config);
        echo $layout->render('home.tpl.php', $templateVars);
    }
}
