<?php
/*
  Server HTTP app
  Param : censDat
*/
require_once "global.php";
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class StartDay{
 var $db;
 var $tasks;
 function StartDay(){
  $this->db = dbmani::connect();
  $this->tasks = array(                      
                      'run'=>'__run'                      
                     );                   
 }  

 function run(){              
      $qry = "CALL start_day(@result)";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->close();  
          
      $qry = "SELECT @result AS result";
      $stmt = $this->db->con->prepare($qry);
      $stmt->execute(); 
      $stmt->bind_result($result);                 
      $stmt->fetch();            
      $stmt->close();   
            
      return json_encode(array('result'=>$result));             
  }
 
}

$mp = new StartDay();
echo $mp->run();
?>
