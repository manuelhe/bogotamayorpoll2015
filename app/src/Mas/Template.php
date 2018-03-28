<?php
namespace Mas;

/**
 * Template Class
 * This is a simple class for implementing template files.
 * The template files are PHP files, with access to all PHP functionality.
 *
 * Example usage:
 * $t = new Template();
 * $t->setVar("firstname", "Patrick");
 * $t->setVar("lastname", "Fitzgerald");
 * $t->setVars(array("addr1"=>"North Ave.", "state"=>"GA"));
 * print $t->parse("templatefile.php");
 */
class Template {
  /**
   * Array to hold the variables used in the template files
   * @var array
   */
  private $data;
  /**
   * Location of template files. This will be appended to the
   * front of the template filename, so be sure it ends in a slash.
   * @var string
   */
  private $basedir;
  /**
   * Array of cached template files
   *
   * @var array
   */
  private $cache;
  /**
   * Class Constructor
   *
   * @param string $basedir
   * @param array $data
   */
  public function __construct($basedir = "", Array $data = array()) {
    $this->basedir = $basedir;
    $this->data = $data;
    $this->cache = array();
  }
  public function setVar($var, $value) {
    $this->data[$var] = $value;
  }
  public function setVars($data = array()) {
    $this->data = array_merge($this->data, $data);
  }
  public function clearVar($var) {
    unset($this->data[$var]);
  }
  public function clearVars() {
    $this->data = array();
  }
  /**
   * Retrieve the contents of a template file and parse it with PHP
   *
   * @param string $templateFilename
   * @return string
   */
  public function parse($templateFilename) {
    // If a basedir is defined,
    // append it to the front of the filename
    if ($this->basedir) {
      $templateFilename = $this->basedir . $templateFilename;
    }
    // If the template file isn't already in the cache, fetch it
    if (!isset($this->cache[$templateFilename])) {

      // Since we're going to eval() the template,
      // we'll turn off PHP processing at the beginning of the file,
      // then restore it after the file. This will make the eval()
      // be equivalent to include()'ing the file
      $this->cache[$templateFilename] =
        "?" . ">\n" .
        implode("",file($templateFilename)) .
        "<" . "?php\n" ;
    }

    // Turn off errors and warnings
    //$this->e = error_reporting(E_ERROR | E_PARSE);

    // Turn on output buffering
    ob_start();

    // Create a local variable for each template variable
    extract($this->data);

    // Include and parse the template file
    eval($this->cache[$templateFilename]);

    // Get the results of the included file
    $string = ob_get_contents();

    // Stop output buffering and clear the buffer
    ob_end_clean();

    // Restore previous error reporting level
    // error_reporting($this->e);

    // Return the parsed template
    return $string;
  }
  /**
   * Retrieve the contents of a template file but do not parse it with PHP.
   *
   * @param string $filename
   * @return string
   */
  public function noparse($filename) {
    // If a basedir is defined,
    // append it to the front of the filename
    if ($this->basedir) {
      $filename = $this->basedir . $filename;
    }

    // Get the file
    $string = implode('',file($filename));

    // Return the parsed template
    return $string;
  }
}
