<?php
namespace DataParse;

class ParseData
{
  private $db;
  private $maxRowToImport = 100;
  private $totalProcessedRows = 0;
  private $totalImportedRows = 0;
  private $candidateTable = [];
  private $locationTable = [];
  private $religionTable = [];
  private $salaryTable = [];
  private $stratifTable = [];
  private $colIndexes = [
    'date'=>0,
    'answer'=>1,
    'age'=>2,
    'gender'=>3,
    'salary'=>4,
    'stratif'=>5,
    'religion'=>6,
    'bloodtype'=>7,
    'willvote'=>8,
    'politicparty'=>9,
    'candidate'=>10,
    'location'=>11
  ];

  public function __construct(\ArrayAccess $config)
  {
    $this->db = $config['db'];
    $this->candidateTable = $this->getAuxTable('candidate');
    $this->locationTable = $this->getAuxTable('location');
    $this->religionTable = $this->getAuxTable('religion');
    $this->salaryTable = $this->getAuxTable('salary');
    $this->stratifTable = $this->getAuxTable('stratif');
    $this->resetAnswersTable();
    $this->parse();
  }

  private function resetAnswersTable()
  {
    try {
      $sa = $this->db->query("TRUNCATE TABLE `answers`");
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

  private function getAuxTable($table)
  {
    $ret = [];
    try {
      $sa = $this->db->query("SELECT * FROM " . $table);
      while($d = $sa->fetch(\PDO::FETCH_ASSOC)){
        $ret[$d['name']] = $d['id' . $table];
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
    return $ret;
  }

  private function parse()
  {
    $count = 0;
    $insertedCols = 0;
    if (($handle = fopen("app/data/data.csv", "r")) !== false) {
      $rowsToInsert = [];
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
        //Check relational values index
        if (!(isset($this->candidateTable[$row[$this->colIndexes['candidate']]]) &&
              isset($this->locationTable[$row[$this->colIndexes['location']]]) &&
              isset($this->religionTable[$row[$this->colIndexes['religion']]]) &&
              isset($this->salaryTable[$row[$this->colIndexes['salary']]]) &&
              isset($this->stratifTable[$row[$this->colIndexes['stratif']]])
             )) {
          continue;
        }

        if (count($rowsToInsert) && count($rowsToInsert) % $this->maxRowToImport === 0) {
          //Insert data and reset rows array
          $this->insertData($rowsToInsert);
          $this->totalImportedRows += $this->maxRowToImport;
          $rowsToInsert = [];
        }

        $rowsToInsert[] = [
          date('Y-m-d H:i:s', strtotime($row[$this->colIndexes['date']])),
          $row[$this->colIndexes['age']],
          $row[$this->colIndexes['gender']],
          $this->salaryTable[$row[$this->colIndexes['salary']]],
          $this->stratifTable[$row[$this->colIndexes['stratif']]],
          $this->religionTable[$row[$this->colIndexes['religion']]],
          $row[$this->colIndexes['bloodtype']],
          $row[$this->colIndexes['willvote']],
          $row[$this->colIndexes['politicparty']],
          $this->candidateTable[$row[$this->colIndexes['candidate']]],
          $this->locationTable[$row[$this->colIndexes['location']]]
        ];
      }
      fclose($handle);
      //Insert remanent data
      $this->insertData($rowsToInsert);
      //Set Totals
      $this->totalImportedRows += count($rowsToInsert);
      $this->totalProcessedRows = $count;
    }
  }

  private function insertData($rows) {
    $paramArray = [];
    $sqlArray = [];

    $sql = "INSERT INTO `answers`
      (date, age, gender, idsalary, idstratif, idreligion, bloodtype, willvote, politicparty, idcandidate, idlocation) values ";
    foreach($rows as $row){
      $sqlArray[] = '(' . implode(',', array_fill(0, count($row), '?')) . ')';
      foreach($row as $element){
        $paramArray[] = $element;
      }
    }
    $sql .= implode(',', $sqlArray);
    try {
      $sa = $this->db->prepare($sql);
      $sa->execute($paramArray);
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

  public function getTotalImportedRows()
  {
    return $this->totalImportedRows;
  }

  public function getTotalProcessedRows()
  {
    return $this->totalProcessedRows;
  }

}
