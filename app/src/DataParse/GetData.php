<?php
namespace DataParse;

class GetData
{
  private $db;
  private $totalRows = 0;
  private $totalDisplayedRows = 0;
  private $minDate = '';
  private $maxDate = '';
  private $filterParams = [];
  private $filterQueryPortion = '';
  private $lastDataUpdate = '';

  public function __construct($config, $filterParams = [])
  {
    $this->db = $config['db'];
    $this->filterParams = $this->validateParams($filterParams);
    $this->setMiscDataProperties();
  }

  private function validateParams($params) {
    if (!$params) {
      return [];
    }
    $ret = [];
    $queryPortion = [];
    foreach($params as $key => $param) {
      if (!trim($param)) {
        continue;
      }
      switch($key) {
        case 'date_init' :
          $ret[$key] = strtotime($param);
          $queryPortion[] = "an.date >= '$param 00:00:00'";
          break;
        case 'date_end' :
          //Include the whole end day
          $date = strtotime($param) + 86399;
          $ret[$key] = $date;
          $queryPortion[] = "an.date <= '". date('Y-m-d H:i:s', $date) . "'";
          break;
        case 'age' :
        case 'gender' :
        case 'bloodtype' :
        case 'willvote' :
        case 'politicparty' :
          $queryPortion[] = "an.{$key} = '{$param}'";
          break;
      }
    }
    $this->filterQueryPortion = ' WHERE ' . implode(' AND ', $queryPortion);
    return $ret;
  }

  public function setMiscDataProperties() {
    try {
      $sa = $this->db->query('SELECT MIN(date) AS min_date, MAX(date) AS max_date, COUNT(*) AS total FROM answers AS an');
      while($d = $sa->fetch(\PDO::FETCH_ASSOC)){
        $this->minDate = substr($d['min_date'], 0, 10);
        $this->maxDate = substr($d['max_date'], 0, 10);
        $this->lastDataUpdate = $d['max_date'];
        $this->totalRows = intval($d['total']);
        $this->totalDisplayedRows = intval($d['total']);
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
    if ($this->filterQueryPortion) {
      try {
        $sa = $this->db->query("SELECT COUNT(*) AS total FROM answers AS an $this->filterQueryPortion");
        while($d = $sa->fetch(\PDO::FETCH_ASSOC)){
          $this->totalDisplayedRows = intval($d['total']);
        }
      } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
      }
    }
  }

  public function getCorrelatedResults($field)
  {
    $sql = "SELECT {$field} AS field, cn.name, COUNT(*) AS total
        FROM answers AS an
        LEFT JOIN `candidate` AS cn ON an.idcandidate = cn.idcandidate
        {$this->filterQueryPortion}
        GROUP BY an.{$field}, an.`idcandidate`
        ORDER BY total DESC";
    if (in_array($field, ['location', 'religion', 'salary', 'stratif'])) {
      $sql = "SELECT rt.name AS field, cn.name, COUNT(*) AS total
        FROM answers AS an
        LEFT JOIN `candidate` AS cn ON an.idcandidate = cn.idcandidate
        LEFT JOIN {$field} AS rt ON an.id{$field} = rt.id{$field}
        {$this->filterQueryPortion}
        GROUP BY an.id{$field}, an.`idcandidate`
        ORDER BY total DESC";
    }

    $ret = [];
    try {
      $sa = $this->db->query($sql);
      while($d = $sa->fetch(\PDO::FETCH_ASSOC)){
        $ret[] = [$d['field'], $d['name'], intval($d['total'])];
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
    return json_encode($ret);
  }

  public function getGoogleGraphData($field)
  {
    $sql = "SELECT `{$field}` AS field, COUNT(*) AS total
      FROM answers AS an
      {$this->filterQueryPortion}
      GROUP BY (`{$field}`) ORDER BY total DESC";
    if (in_array($field, ['candidate', 'location', 'religion', 'salary', 'stratif'])) {
      $sql = "SELECT cn.name AS field, COUNT(*) AS total
        FROM answers AS an
        LEFT JOIN `{$field}` AS cn ON an.id{$field} = cn.id{$field}
        {$this->filterQueryPortion}
        GROUP BY (an.id{$field})
        ORDER BY total DESC";
    }

    $ret = [];
    try {
      $sa = $this->db->query($sql);
      while($d = $sa->fetch(\PDO::FETCH_ASSOC)){
        $ret[] = [$d['field'], intval($d['total'])];
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
    return json_encode($ret);
  }

  public function getTotalVotes()
  {
    return $this->totalRows;
  }

  public function getTotalDisplayedVotes()
  {
    return $this->totalDisplayedRows;
  }

  public function getMinDate()
  {
  	return $this->minDate;
  }

  public function getMaxDate()
  {
  	return $this->maxDate;
  }

  public function getLastUpdate()
  {
  	return $this->lastDataUpdate;
  }

  public function getFilteredInitDate()
  {
    return isset($this->filterParams['date_init']) ? date('Y-m-d', $this->filterParams['date_init']) : $this->minDate;
  }

  public function getFilteredEndDate()
  {
    return isset($this->filterParams['date_end']) ? date('Y-m-d', $this->filterParams['date_end']) : $this->maxDate;
  }
}
