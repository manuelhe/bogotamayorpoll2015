<?php
namespace Controller;

/**
 * ParseData Controller
 *
 * @author manuel.he@gmail.com
 */
class ParseData extends \Mas\Controller
{
    public function response() {
        if(!(isset($this->urlParams[0]) && $this->urlParams[0] === $this->config['config']['parse_key'])){
            $this->setAlert("Wrong token to start parse process");
            header("Location:".$this->config['config']['basePath']);
            return false;
        }

        $parseData = new \DataParse\ParseData($this->config);
        echo 'Total imported rows = ' . $parseData->getTotalImportedRows()
          . '<br><br>'
          . 'Total processed rows = ' . $parseData->getTotalProcessedRows();
    }
}
