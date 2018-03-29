<?php
namespace Mas;
/**
 * Application routing class
 *
 * @author manuel.he@gmail.com
 */
class Router {
    protected $requestUri = 'home';
    protected $config;
    protected $server;
    protected $get;
    /**
     * Class constructor
     *
     * @param \ArrayAccess $config Pimple Dependence Injection object
     */
    public function __construct(\ArrayAccess $config, $server, $get) {
        $this->config = $config;
        $this->server = $server;
        $this->get = $get;
        $this->requestUri = $this->getRequestUri();
    }
    /**
     * Run app
     *
     * @return boolean
     */
    public function run(){
        $requestUri = explode('/', $this->requestUri);
        if(!(isset($requestUri[0]) && $requestUri[0])){
            $this->send404();
            return false;
        }
        $controllerClass = '\\Controller\\'.ucfirst(strtolower($requestUri[0]));
        if(!class_exists($controllerClass)){
            $this->send404();
            return false;
        }
        unset($requestUri[0]);
        $controller = new $controllerClass($this->config,  array_values($requestUri));
        return $controller->response();
    }
    /**
     * Send Not Found page
     */
    protected function send404(){
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit();
    }
    /**
     * Get requested URI
     *
     * @return string
     */
    protected function getRequestUri() {
        if (!isset($this->server['REQUEST_URI']) OR !isset($this->server['SCRIPT_NAME'])) {
            return '';
        }
        $uri = $this->server['REQUEST_URI'];
        if (strpos($uri, $this->server['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($this->server['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($this->server['SCRIPT_NAME'])) === 0) {
            $uri = substr($uri, strlen(dirname($this->server['SCRIPT_NAME'])));
        }
        if (strncmp($uri, '?/', 2) === 0) {
            $uri = substr($uri, 2);
        }
        $parts = preg_split('#\?#i', $uri, 2);
        $uri = $parts[0];
        if (isset($parts[1])) {
            $this->server['QUERY_STRING'] = $parts[1];
            parse_str($this->server['QUERY_STRING'], $this->get);
        } else {
            $this->server['QUERY_STRING'] = '';
            $this->get = array();
        }
        if ($uri == '/' || empty($uri)) {
            return 'home';
        }
        $uri = parse_url($uri, PHP_URL_PATH);
        // Do some final cleaning of the URI and return it
        return $this->removeInvisibleCharacters(str_replace(array('//', '../'), '/', trim($uri, '/')));
    }
    /**
     * Clear invisible or URL dangerous characters
     *
     * @param string $str
     * @param string $urlEncoded
     * @return string
     */
    protected function removeInvisibleCharacters($str, $urlEncoded = TRUE) {
        $nonDisplayables = array();
        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)
        if ($urlEncoded) {
            $nonDisplayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
            $nonDisplayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
        }
        $nonDisplayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127
        do {
            $str = preg_replace($nonDisplayables, '', $str, -1, $count);
        } while ($count);
        return $str;
    }
}
