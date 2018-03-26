<?php
namespace DataParse;

class FilterData
{
  private $totalRows;
  private $data;
  private $dataDesc;
  private $minDate = 0;
  private $maxDate = 0;
  private $filterParams = [];
  private $lastDataUpdate = 0;
  const VOTE_INDEX = 10;
  const DATE_INDEX = 0;

  public function __construct($filterParams = [])
  {
    $this->dataDesc = array_fill(0, 12, []);
    $this->data = [];
    $this->filterParams = $this->validateParams($filterParams);
    $this->getData();
  }

  private function validateParams($params) {
    if (!$params) {
      return [];
    }
    $ret = [];
    foreach($params as $key => $param) {
      switch($key) {
        case 'date_init' :
          $ret[$key] = strtotime($param);
          break;
        case 'date_end' :
          //Include the whole end day
          $ret[$key] = strtotime($param) + 86399;
          break;
      }
    }
    return $ret;
  }

  private function testFilters($data) {
    $ret = true;
    if (!count($this->filterParams)) {
      return true;
    }
    foreach($this->filterParams as $key => $param) {
      switch($key) {
        case 'date_init' :
          $ret = $ret && strtotime($data[self::DATE_INDEX]) >= $param;
          break;
        case 'date_end' :
          $ret = $ret && strtotime($data[self::DATE_INDEX]) <= $param;
          break;
      }
    }
    return $ret;
  }

  private function getData()
  {
    $count = 0;
    if (($handle = fopen("data/data.csv", "r")) !== false) {

      $this->lastDataUpdate = filectime("data/data.csv");

      while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        $count++;
        if ($count < 2) {
          continue;
        }
        // If user doesn't accept terms and conditions,
        // remove answer from reults
        if ($row[1] === 'No') {
          continue;
        }

        //Set max and min dates
        $date = strtotime($row[self::DATE_INDEX]);
        $this->minDate = !$this->minDate || $this->minDate > $date ? $date : $this->minDate;
        $this->maxDate = !$this->maxDate || $this->maxDate < $date ? $date : $this->maxDate;

        if (!$this->testFilters($row)) {
          continue;
        }

        $num = count($row);
        $rowData = [];
        for ($c = 0; $c < $num; $c++) {

          if ($c > 0 && !in_array($row[$c], $this->dataDesc[$c])) {
            $this->dataDesc[$c][] = $row[$c];
          }
          $rowData[$c] = $c !== self::DATE_INDEX ?
            array_search($row[$c], $this->dataDesc[$c]) :
            $date;
          //if ($c === self::DATE_INDEX) var_dump($row[$c], date('Y-m-d H:i:s', $date));

        }
        $this->data[] = $rowData;
      }
      fclose($handle);
    }
    $this->totalRows = count($this->data);
  }

  public function getResultsFrom($index)
  {
    if (!isset($this->dataDesc[$index])) {
      return false;
    }

    $ret = [];

    //Data filtering
    foreach ($this->data as $v) {
      $idx = $v[$index];
      if (!isset($ret[$idx])) {
        $ret[$idx] = [
          'value'   => $this->dataDesc[$index][$idx],
          'total'   => 0,
          'percent' => 0
        ];
      }
      $ret[$v[$index]]['total']++;
    }

    //Get Percent values
    foreach ($ret as $k => $v) {
      $ret[$k]['percent'] = 100 * $v['total'] / $this->totalRows;
    }

    //Sort results
    foreach ($ret as $key => $row) {
      $total[$key]  = $row['total'];
    }
    array_multisort($total, SORT_DESC, $ret);

    return $ret;
  }

  public function getCorrelatedResults($index)
  {
    self::VOTE_INDEX;
    $ret = [];
    foreach ($this->data as $v) {
      $idx = $v[$index] . '-' . $v[self::VOTE_INDEX];
      if (!isset($ret[$idx])) {
        $ret[$idx] = [
          $this->dataDesc[$index][$v[$index]],
          $this->dataDesc[self::VOTE_INDEX][$v[self::VOTE_INDEX]],
          0
        ];
      }
      $ret[$idx][2]++;
    }
    return json_encode(array_values($ret));
  }

  public function getGoogleGraphData($index)
  {
    $data = $this->getResultsFrom($index);

    $ret = [];
    foreach ($data as $v) {
      $ret[] = [$v['value'], $v['total']];
    }
    return json_encode($ret);
  }

  public function getTotalVotes()
  {
    return $this->totalRows;
  }

  public function getMinDate($format = 'Y-m-d')
  {
  	return date($format, $this->minDate);
  }

  public function getMaxDate($format = 'Y-m-d')
  {
  	return date($format, $this->maxDate);
  }

  public function getLastUpdate($format = 'Y-m-d H:i:s')
  {
  	return date($format, $this->lastDataUpdate);
  }

  public function getFilteredInitDate($format = 'Y-m-d')
  {
    return date($format, isset($this->filterParams['date_init']) ?
                $this->filterParams['date_init'] :
                $this->minDate);
  }

  public function getFilteredEndDate($format = 'Y-m-d')
  {
    return date($format, isset($this->filterParams['date_end']) ?
                $this->filterParams['date_end'] :
                $this->maxDate);
  }
}
