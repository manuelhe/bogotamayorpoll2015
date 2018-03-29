<?php
namespace Mas;

/**
 * Abstract Controller Class
 *
 * @author manuel.he@gmail.com
 */
abstract class Controller {
    protected $config;
    protected $urlParams;
    protected $alerts = array();
    public function __construct(\ArrayAccess $config, $urlParams = '') {
        $this->config = $config;
        $this->urlParams = $urlParams;
        $this->getFlashAlerts();
    }
    protected function getFlashAlerts(){
        if(isset($_SESSION['alerts'])){
            if(is_array($_SESSION['alerts'])){
                $this->alerts = $_SESSION['alerts'];
            }
            $_SESSION['alerts'] = array();
        }
    }
    /**
     * Set a message to be used in the next request.
     *
     * @param string $message
     * @return boolean
     */
    protected function setAlert($message){
        $message = trim((string) $message);
        if(!$message){
            return;
        }
        if(!(isset($_SESSION['alerts']) && is_array($_SESSION['alerts']))){
            $_SESSION['alerts'] = array();
        }
        $_SESSION['alerts'][] = $message;
        return true;
    }
    protected function getAlerts(){
        return $this->alerts;
    }
    /**
     * Abstract response method
     */
    abstract public function response();
}
