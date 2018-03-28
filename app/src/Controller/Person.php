<?php
namespace Controller;

/**
 * Person Controller
 *
 * @author manuel.he@gmail.com
 */
class Person extends \Mas\Controller
{
    public function response() {
        if(!(isset($this->urlParams[0]) && intval($this->urlParams[0]))){
            $this->setAlert("Invalid Person ID");
            header("Location:".$this->config['config']['basePath']);
            return false;
        }
        $this->tmapi = $this->config['tmdb'];
        $personId = intval($this->urlParams[0]);

        //Set template
        $template = new \Mas\Template($this->config['config']['templatesDir']);
        $template->setVar('config', $this->config['config']);
        $template->setVar('alerts', $this->getAlerts());
        $template->setVar('person', $this->getPerson($personId));
        $template->setVar('credits', $this->getCredits($personId));
        echo $template->parse('person.tpl.php');
        $this->tmapi->close();
    }
    protected function getPerson($personId){
        $response = $this->tmapi->apiPerson($personId);
        if(isset($response->status_code) && isset($response->status_message)){
            $this->setAlert($response->status_message);
            header("Location:".$this->config['config']['basePath']);
            return false;
        }
        $response->profile_path = isset($response->profile_path) && $response->profile_path
                    ? $this->config['config']['tmdb']['image_base_url'].'w185'.$response->profile_path
                    : $this->config['config']['tmdb']['no_profile_image'];
        return $response;
    }
    protected function getCredits($personId){
        $response = $this->tmapi->apiPersonCredits($personId);
        if (!isset($response->cast)) {
            return false;
        }
        if (!is_array($response->cast)) {
            return false;
        }
        if (!$response->cast) {
            return false;
        }
        $ret = array();
        while (list($index, $value) = each($response->cast)) {
            $value->title = isset($value->title) && trim($value->title) ? $value->title : 'Untitled';
            $value->mtitle = strlen($value->title) > 40 ? substr($value->title,0,38).'...' : $value->title;
            $value->poster_path = isset($value->poster_path) && $value->poster_path
                    ? $this->config['config']['tmdb']['image_base_url'].'w92'.$value->poster_path
                    : $this->config['config']['tmdb']['no_poster_image'];
            $value->character = isset($value->character) && trim($value->character) ? $value->character : 'Unnamed';
            $value->release_date = isset($value->release_date) && trim($value->release_date) ? $value->release_date : 'Undated';
            $rowIndex = isset($value->release_date) && $value->release_date ? str_replace('-', '', $value->release_date) : $index;
            $ret[$rowIndex] = $value;
        }
        ksort($ret);
        return $ret;
    }
}
