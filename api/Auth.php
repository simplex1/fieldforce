<?php
/*
  Server HTTP app
  Param : censDat
*/

error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
require_once "global.php";    
require_once BASEPATH."/shared/dbmani.php";
require_once BASEPATH."/shared/utilities.php";

class Auth{
 var $db;
 var $tasks;
 var $utils;
 public function __construct(){
  $this->db = dbmani::connect();
  $this->tasks = array(                      
                      'loginMobile'=>'__loginMobile',
                      'loginWeb'=>'__loginWeb',
                      'logout'=>'__logout',
                      'register'=>'__register',
                      'updateDepot'=>'__updateDepot'                      
                     );       
  $this->utils = new utilities();                                 
 }
 
 public function __destruct(){
  //$this->db->con->close();
 }  

 function __register(){
  $qry = "CALL create_account(?,?,?,?,@user_id)";
      $stmt = $this->db->con->prepare($qry);            
      $stmt->bind_param('ssss',$_REQUEST['username'],$_REQUEST['password'],$_REQUEST['first_name'],$_REQUEST['last_name']);
      $stmt->execute();            
      $stmt->close();
      
      $qry = "SELECT @user_id AS user_id";
      $stmt = $this->db->con->prepare($qry);                  
      $stmt->execute();    
      $stmt->bind_result($user_id);
      $stmt->fetch();  
      $stmt->close();            
      
      if($user_id>0){return $this->__loginWeb();}
      else{return "err";}       
 }
 
 function __updateDepot(){
  $qry = "CALL change_depot_coord(?,?,?)";
      $stmt = $this->db->con->prepare($qry);            
      $stmt->bind_param('ddi',$_REQUEST['latitude'],$_REQUEST['longitude'],$_REQUEST['employee_id']);
      $stmt->execute();      
      $stmt->close();   
      return json_encode(array('status'=>'ok'));        
 }

 function __loginMobile(){                        
          $status=1;
          $lang="en";
          //main
          $qry = "CALL login_mobile(?,?,?,?,?)";
      $stmt = $this->db->con->prepare($qry);      
      //echo $this->db->con->error;
      //echo $qry;
      $stmt->bind_param('issss',$status,$_REQUEST['username'],$_REQUEST['password'],$_REQUEST['imei'],$lang);
      $stmt->execute();
      $stmt->bind_result($employee_id,$first_name,$last_name,$tasks,$depot,$latitude,$longitude);
      $stmt->fetch();
      $stmt->close();
      return json_encode(array('employee_id'=>$employee_id,'first_name'=>$first_name,'last_name'=>$last_name,'tasks'=>$tasks,'depot'=>$depot,'latitude'=>$latitude,'longitude'=>$longitude));
  }
  
  function __logout(){
                    
    }
  
  function __loginWeb(){                        
      //main
      $status=1;          
      $qry = "CALL login_web(?,?,?)";
      $stmt = $this->db->con->prepare($qry);            
      //echo $this->db->con->error;
      //echo $qry;
      $stmt->bind_param('iss',$status,$_REQUEST['username'],$_REQUEST['password']);
      $stmt->execute();
      $stmt->bind_result($user_id,$first_name,$last_name,$modules);
      $stmt->fetch();
      $stmt->close();            
      $ret =  "err";
      if(isset($user_id) && isset($first_name) && isset($last_name)){
        @session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['modules'] = $modules;
        $this->utils->storeLogin($this->db,$user_id,'Login');
        $ret = "ok";
      } 
      return $ret;
  }
 
 function process(){
  if(!empty($_REQUEST['job'])){
  $task = $_REQUEST['job'];
  $func = $this->tasks[$task];              
    return $this->$func();   
  }  
  else{
   return 'Invalid Task!';
  }      
 }
 
}

 $mp = new Auth();
 echo $mp->process();

?>
