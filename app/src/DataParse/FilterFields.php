<?php
namespace DataParse;

class FilterFields
{
  private $db;
  private $fields = [];
  private $filterParams = [];

  public function __construct($config, $filterParams = [])
  {
    $this->db = $config['db'];
    $this->filterParams = $filterParams;
    $this->getEnumFields();
  }

  private function getEnumFields() {
    try {
      $sa = $this->db->query("SHOW COLUMNS FROM answers 
        WHERE Field IN ('age', 'gender', 'bloodtype', 'willvote', 'politicparty')");
      while($d = $sa->fetch(\PDO::FETCH_ASSOC)){
        preg_match("/^enum\(\'(.*)\'\)$/", $d['Type'], $matches);
        $matches = explode("','", $matches[1]);
        $this->fields[$d['Field']] = array_map(function ($item) use ($d) {
          return [
            'id'        => $item, 
            'value'     => $item,
            'selected'  => isset($this->filterParams[$d['Field']]) &&  $this->filterParams[$d['Field']] === $item
          ];
        }, $matches);
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

  /**
   * Returns values list for a given field
   * 
   * @param string $field  
   * @return array
   */
  public function getValuesFor($field) {
    if (!$field) {
      throw new InvalidArgumentException('field argument is required.');
    }
    if (!isset($this->fields[$field])) {
      return [];
    }
    return $this->fields[$field];
  }

}
