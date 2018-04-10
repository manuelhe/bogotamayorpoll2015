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
    $this->getRelatedFieldsFor('candidate');
    $this->getRelatedFieldsFor('location');
    $this->getRelatedFieldsFor('religion');
    $this->getRelatedFieldsFor('salary');
    $this->getRelatedFieldsFor('stratif');
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
            'selected'  => $this->isOptionSelected($item, $d['Field'])
          ];
        }, $matches);
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

  private function getRelatedFieldsFor($tableName) {
    if (!$tableName) {
      throw new InvalidArgumentException('tableName argument is required.');
    }
    try {
      $idFieldName = 'id' . $tableName;
      $values = [];
      $sa = $this->db->query("SELECT * FROM {$tableName}");
      while($d = $sa->fetch(\PDO::FETCH_ASSOC)){
        $values[] = [
          'id'        => $d[$idFieldName], 
          'value'     => $d['name'],
          'selected'  => $this->isOptionSelected($d[$idFieldName], $tableName)
        ];
      }
      $this->fields[$tableName] = $values;
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

  private function isOptionSelected($id, $tableName) {
    if (!isset($this->filterParams[$tableName])) {
      return false;
    }
    if (in_array('', $this->filterParams[$tableName])) {
      return false;
    }
    if (!in_array($id, $this->filterParams[$tableName])) {
      return false;
    }
    return true;
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
