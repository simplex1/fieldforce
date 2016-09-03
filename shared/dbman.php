<?php
require_once "init.php";
 
 class dbman{     
  function connect(){
   $sin = "_INSTANCE_DBOPS";   
   if(!isset($GLOBALS[$sin])){
    $GLOBALS[$sin] = & new dbops();
    return $GLOBALS[$sin];      
   } 
  else{
   return $GLOBALS[$sin];
  }   
  }
  
  function __destruct()
	{
		$GLOBALS[$sin]->disconnect($GLOBALS[$sin]->con);
	}
 }
 
 class dbops{
   var $con;    
   
   function dbops(){
    $this->con = $this->connect();
   }
   
   function connect(){
    $conf = init::getConf();
    $host = $conf['dbinfo']['host'];
    $port = $conf['dbinfo']['port'];
    $unam = $conf['dbinfo']['username'];
    $upass = $conf['dbinfo']['password'];
    $database = $conf['dbinfo']['database'];
    $dbtype = $conf['dbinfo']['dbtype'];
    if($dbtype == 'mysql'){
    $conn = mysql_connect("$host:$port",$unam,$upass);
    if($conn){
     mysql_select_db($database,$conn);
     return $conn;
    }
    else return false;
    }
    if($dbtype == 'sqlite'){
      $dbpath =  $conf['dbinfo']['dbpath'];
      $dbfile = "$dbpath/$database.db";
      $conn = sqlite_open("$dbfile",0666,$err);
      if($conn){
      //setup db for the first time
       if(!filesize($dbfile)){
         $sq = "$dbpath/setup.sql";
         $qry = file_get_contents($sq);
         sqlite_exec($conn,$qry); 
       }       
      return $conn;
      }else{
       return false;
      }
    }
   }
   
   function getDBType(){
    $conf = init::getConf();
    $dbtype = $conf['dbinfo']['dbtype'];
    return $dbtype;
   }
   
   function disconnect($conn){
   $dbtype = $this->getDBType();
    if($dbtype == 'mysql')
     mysql_close($conn);
    if($dbtype == 'sqlite')
     sqlite_close($conn);
    //$this->con = null; 
   }
   
   function stripquotes($str){
   $str = str_replace("?","",$str);
   $str = str_replace("'","",$str);
   $str = str_replace("`","",$str);
   return $str;
   }
   
   function getAll($qry,$tp=null){
   $dbtype = $this->getDBType();
   //$qry = stripslashes($qry);
   /*if($this->con == null){
    $this->con = $this->connect();
    }*/
    if($this->con){
      $fn = "$dbtype".'_query';
      if($dbtype == 'mysql')     
      $res = $fn($qry, $this->con);
      if($dbtype == 'sqlite')
      $res = $fn($this->con, $qry);      
      if(!$res){
       if($dbtype == 'mysql') 
       return mysql_error($this->con);
       if($dbtype == 'sqlite'){
        return sqlite_error_string(sqlite_last_error($this->con));
       } 
      }else{
        $cnt = 0;
        $recs = array();
        if(is_null($tp)){        
        $func = "$dbtype".'_fetch_object';
         while ($row = $func($res)){        
          $recs[$cnt] = $row;
          $cnt++;
        }
        }
        else{
        $func = "$dbtype".'_fetch_array';
        if($dbtype == 'mysql'){         
        while($row = $func($res,MYSQL_ASSOC)){
         foreach($row as $k => $v){         
          $recs[$cnt][$k] = $v;
         }
         $cnt++;
         }
        }
        if($dbtype == 'sqlite'){  
        while($row = $func($res,SQLITE_ASSOC)){             
         foreach($row as $k => $v){         
          $recs[$cnt][$k] = $v;
         }
         $cnt++; 
        }
        }
        }
        return $recs;        
      }
      //$this->disconnect($this->con);
    }else{
     $ern = "$dbtype".'_error';
     return $ern();
    }
   }
   
   function getAffectedRows(){
      return mysql_affected_rows($this->con);
   }
   
   function getInsertId(){
      return mysql_insert_id($this->con);
   }
   
   function getNextId($tabNm){
    //get primary key
    $qry = "describe $tabNm";
    $ds = $this->getAll($qry);
    for($a=0;$a<count($ds);$a++){
     if($ds[$a]->Key == 'PRI')
     $col = $ds[$a]->Field;
    }    
    $qry = "select IFNULL(max($col)+1,1) as $col from $tabNm";
    $dat = $this->getAll($qry);
    return $dat[0]->$col;
   }
   
   function getColumns($tb){
     $qry = "desc $tb";
     $recs = $this->getAll($qry);
     $cols = "";     
     for($a=0;$a<count($recs);$a++){      
      $cols[$a] = $recs[$a]->Field;
     }
     return $cols;
   }
   
   function addRecord($tabNm,$rec){
    if(is_array($rec)){
     $qry = "desc $tabNm";
     $cols = $this->getAll($qry);
     $sav = "insert into $tabNm(";
     for($a=0;$a<count($cols);$a++){
      $fld = $cols[$a]->Field;
      $sav .= $fld;
      if($a<count($cols)-1)
       $sav .= ', ';
     }
     $sav .= ") values(";
     for($a=0;$a<count($cols);$a++){
      $fld = $cols[$a]->Field;
      if($cols[$a]->Key == 'PRI')
      $val = $this->getNextId($tabNm);
      else
      $val = $rec[$fld];
      $sav .= "'".$val."'";
      if($a<count($cols)-1)
       $sav .= ', ';
     }
     $sav . ")";
     $this->runDml($sav);
     return true;
    }
    else return false;
   }
   
   function getPrimaryKey($tabNm){
    $qry = "desc $tabNm";
    $cols = $this->getAll($qry);
    for($a=0;$a<count($cols);$a++){
     if($cols[$a]->Key == 'PRI')
     return $cols[$a]->Key;
    }
   }
   
   function updateRecord($tabNm,$rec){
    if(is_array($rec)){
     $qry = "desc $tabNm";
     $cols = $this->getAll($qry);
     $upd = "update $tabNm set ";
     $pk = $this->getPrimaryKey($tabNm);
     $pkvl = $rec[$pk];
     for($a=0;$a<count($cols);$a++){
      $fld = $cols[$a]->Field;            
       $vl = $rec[$fld];
       $upd .= "$fld = '$vl'";
      if($a<count($cols)-1)
       $upd .= ', ';      
     }
     $upd .= " where $pk = $pkvl";
     $this->runDml($sav);
     return true;
    }
    else return false;
   }
   
   function hasPayed(){
    return true;
   }
   
   function createForms(){
    $conf = initServer::getConf();
    $db = $conf['dbinfo']['database'];
    $qry = "show table status from $db WHERE Engine IS NOT NULL";
    $dat = $this->getAll($qry);
    for($a=0;$a<count($dat);$a++){
     $tbl = $dat[$a]->Name;
     $fil = HMP.TPL."/$tbl".'_fm.html';
     $frm = "<div id=\"fmcont\">\n";
     //only create if not exist
     if(!file_exists($fil)){
     $frm .= "<form id=\"$tbl\" method=\"post\">
              <fieldset>
              <ol>";
     $qry = "desc $tbl";
     $cols = $this->getAll($qry);
     for($b=0;$b<count($cols);$b++){
      $fld = $cols[$b]->Field;
      $ky = $cols[$b]->Key;
      $typ = $cols[$b]->Type;
      $nl = ($cols[$b]->Null == 'No')?'':'ncp';      
      if($ky == 'MUL')
      {
       $frm .= "
               <li class=\"$nl\">
                 \t<label for=\"$fld\">$fld:</label>
                 \t<select class=\"required\" name=\"$fld\" id=\"$fld\"></select><span>(required)</span>
               </li>
              ";
      }
      if($typ == 'text')
      {
       $frm .= "
               <li class=\"$nl\">
                 \t<label for=\"$fld\">$fld:</label>
                 \t<textarea class=\"required\" name=\"$fld\" id=\"$fld\"></textarea><span>(required)</span>
               </li>
              ";
      }
      if($typ == 'datetime' || $typ == 'date' )
      {
       $lt = substr($fld,0,1).'inline';
       $frm .= "
               <li class=\"$nl\">
                \t<label for=\"$fld\">$fld:</label>
                \t<input class=\"required\" type=\"text\" value=\"\" name=\"$fld\" id=\"$fld\" readonly /><span>(required)</span>
                \t<div id=\"$lt\"></div> <p style=\"clear: both;\"></p>
                </li>
              ";
      }
      if($typ == 'char(1)')
      {
       $frm .= "<li class=\"$nl\">
                 \t<nobr>$fld
                 \t<input type=\"radio\" id=\"$fld\" name=\"$fld\" value=\"0\" />$fld
                 \t<input type=\"radio\" id=\"$fld\" name=\"$fld\" value=\"1\" />$fld
                \t</nobr>
               </li>
              "; 
      }
      else{
      if($ky == 'PRI')
      {
       //primary key dont include
      }
      else{
       $frm .= "
               <li class=\"$nl\">
                 \t<label for=\"$fld\">$fld:</label>
                 \t<input class=\"required\" type=\"text\" name=\"$fld\" id=\"$fld\" /><span>(required)</span>
               </li>
              ";
       }       
      }
      }
      $frm .= "
              </ol>  
               </fieldset>
               </form>
               </div>
              ";
       file_put_contents($fil,$frm);               
     }
    }
   }
   
   function makeSelect($flds){
    $qry = 'select';
    $cnt = 0;
    foreach($flds as $k){
     $qry .= " $k";
     if($cnt < count($flds)-1)
     $qry .= ','; 
     $cnt++;   
    }
    return $qry;
   }
   
   function getJSON($qry,$flds){
    $dat = $this->getAll($qry);
    $jsi = "[";    
    $cnt = count($dat);
    if(count($dat)>0){
    $fcnt = count($flds);        
    for($a=0;$a<$cnt;$a++){
     $jsi .= " {";
     $ct = 0;
      foreach($flds as $k){
       $jsi .= "\"$k\" : \"{$dat[$a]->$k}\"";
       if($ct<($fcnt-1)){
       $jsi .= ",";
       }
       $ct++;
      }
     $jsi .= " }";
     if($a<($cnt-1)){
       $jsi .= ",";
      }
    }
    $jsi .= "]";
    return $jsi;   
    }
    else return false;
   }
   
   function getTable($qry,$flds,$cus=false){
    $dat = $this->getAll($qry);    
    $jt = "";    
    $cnt = count($dat);    
    //headers
    if(count($dat)>0){
    $jt .= "<thead><tr>";
    foreach($flds as $k => $v){
       $jt .= "<th>$v</th>";       
      }            
    $jt .= "</tr></thead>";
    //content
    $jt .= "<tbody>";  
    for($a=0;$a<$cnt;$a++){
     $jt .= "<tr>";     
      foreach($flds as $k => $v){
      if($cus){
       if('b.win' == $k){
       $hd = "b.betorders_id";       
        $jt .= "<td>Win:<input class=\"normal\" id=\"set_win\" name=\"set_win\" type=\"checkbox\" value=\"{$dat[$a]->$hd}\" onclick=\"doWin()\" />
                Lose:<input class=\"normal\" id=\"set_loss\" name=\"set_loss\" type=\"checkbox\" value=\"{$dat[$a]->$hd}\" onclick=\"doLoss()\" />
               </td>";
       }              
       else
        $jt .= "<td>{$dat[$a]->$k}</td>";
      }      
       else{
       $jt .= "<td>{$dat[$a]->$k}</td>";
       }                     
      }
     $jt .= "</tr>";     
    }
    $jt .= "</tbody>";    
    return $jt;   
    }
    else return false;
   }
   
   function getCSV($qry,$ck){
    $dat = $this->getAll($qry,$ck);
    $cs = "";    
    $cnt = count($dat);
    //headers
    if($cnt>0){
    for($a=0;$a<$cnt;$a++){
    $ct = 0;
    $b = $a + 1;
    $cs .= "$b,";
      foreach($dat[$a] as $k => $v){
        $cs .= $v;
        if($ct < $ck -1)
        $cs .= ',';
        $ct++;
      }
     $cs .= '\n'; 
    }
    return $cs;
    }else return false;
   }
   
   function runDml($qry){
   $dbtype = $this->getDBType();
   //$qry = $this->stripquotes($qry);
    /*if($this->con == null){
    $this->con = $this->connect();
    } */
    if($this->con){
    $fn = "$dbtype".'_query';
     if($dbtype == 'mysql')     
      $rt = $fn($qry, $this->con);
      if($dbtype == 'sqlite')
      $rt = $fn($this->con, $qry);     
      if(!$rt){
       if($dbtype == 'mysql') 
       return mysql_error($this->con);
       if($dbtype == 'sqlite'){
        return sqlite_error_string(sqlite_last_error($this->con));
      }else{
        //might extend to return results
      }
      //$this->disconnect($this->con);
    }else{
     if($dbtype == 'mysql') 
     return mysql_error($this->con);
    }
   }
 }
 }
?>