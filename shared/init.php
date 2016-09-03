<?php
 class init{
  function setup(){
   $arr = parse_ini_file(dirname(__FILE__)."/constants.ini",true);
   foreach($arr as $k => $v){
   if($k == 'site paths'){
    foreach($v as $t => $b){
     if(!defined(strtoupper($t))){
      define(strtoupper($t),$b);
     }
    }
    }
   }
  }
  static function getConf(){
    $arr = parse_ini_file(dirname(__FILE__)."/constants.ini",true);
    $conf = array();
    foreach($arr as $k => $v){
    foreach($v as $t => $b){
     $conf[$k][$t] = $b;
    }
  }
  return $conf;
  }
  function fixUrl(){  
   $arr = array(); 
   if(isset($_GET)){
   $cnt = 0;   
    foreach($_GET as $k => $v){
    $arr[$k] = $v;
     if($cnt == 0){
      $arr[$k] = 'joxp'.$v;
     }
     $cnt++;
    }  
    unset($_GET);  
   }
   else{
    $arr['xtyp'] = 'joxphisto';
   }
   return $arr;      
  }
 }
?>