<?php
namespace DataParse;

class FilterFields
{
  private $db;
  private $fields = [];
  private $filterParams = [];
  private $selectedFilters = [];

  public function __construct($config, Array $filterParams = [], \DataParse\GetData $parsedData)
  {
    $this->db = $config['db'];
    $this->filterParams = $filterParams;

    if (isset($filterParams['date_init']) && isset($filterParams['date_end']) && (
        $filterParams['date_init'] !== $parsedData->getMinDate() ||
        $filterParams['date_end'] !== $parsedData->getMaxDate())) {
      $this->selectedFilters[] = [
        'id' => 'date',
        'label' => "{$filterParams['date_init']} ⇾ {$filterParams['date_end']}",
        'filter' => 'date_range'
      ];
    }

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
          $isSelected = $this->isOptionSelected($item, $d['Field']);
          if ($isSelected) {
            $this->selectedFilters[] = [
              'id' => $item,
              'label' => $item,
              'filter' => $d['Field']
            ];
          }
          return [
            'id'        => $item, 
            'value'     => $item,
            'selected'  => $isSelected
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
        $isSelected = $this->isOptionSelected($d[$idFieldName], $tableName);
        if ($isSelected) {
          $this->selectedFilters[] = [
            'id' => $d[$idFieldName],
            'label' => $d['name'],
            'filter' => $tableName
          ];
        }
        $values[] = [
          'id'        => $d[$idFieldName], 
          'value'     => $d['name'],
          'selected'  => $isSelected
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

  /**
   * Returns selected filters, obviously
   * 
   * @return array
   */
  public function getSelectedFilters() {
    return $this->selectedFilters;
  }

}
