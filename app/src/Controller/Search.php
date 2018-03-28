<?php
namespace Controller;

/**
 * Search Controller
 *
 * @author manuel.he@gmail.com
 */
class Search extends \Mas\Controller
{
    public function response() {
        $search = $this->formValidation();
        $results = $this->searchPerson($search);
        //Set template
        $template = new \Mas\Template($this->config['config']['templatesDir']);
        $template->setVar('config', $this->config['config']);
        $template->setVar('alerts', $this->getAlerts());
        $template->setVar('search', $search);
        $template->setVar('results', $results);
        echo $template->parse('search.tpl.php');
    }
    protected function formValidation(){
        $params = isset($_POST) && $_POST ? $_POST : false;
        if(!$params){
            $this->setAlert('Invalid request.');
            header("Location:".$this->config['config']['basePath']);
            return false;
        }
        if(!(isset($params['search']) && is_string($params['search']) && trim($params['search']))){
            $this->setAlert('You must submit an Actor/Actress name');
            header("Location:".$this->config['config']['basePath']);
            return false;
        }
        return $params['search'];
    }
    protected function searchPerson($search){
        $tmapi = $this->config['tmdb'];
        $response = $tmapi->apiSearchPerson($search);
        $tmapi->close();
        if(!(isset($response->results) && is_array($response->results) && $response->results)){
            $this->setAlert("No results looking for: <strong>{$search}</strong>");
            header("Location:".$this->config['config']['basePath']);
            return false;
        }
        if(count($response->results) === 1){
            header("Location:{$this->config['config']['basePath']}person/{$response->results[0]->id}");
            return true;
        }
        $ret = array();
        foreach ($response->results as $v) {
            $v->profile_path = isset($v->profile_path) && $v->profile_path
                    ? $this->config['config']['tmdb']['image_base_url'].'w92'.$v->profile_path
                    : $this->config['config']['tmdb']['no_profile_image'];
            $v->link = $this->config['config']['basePath'].'person/'.$v->id;
            $ret[$v->popularity] = $v;
        }
        krsort($ret);
        return $ret;
    }
}
