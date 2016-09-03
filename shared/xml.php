<?php
class xml{
//for event driven
  function createParser($typ=0){
   $xp = xml_parser_create();
   if($typ == 0){
   xml_set_element_handler($xp, "startMgr", "endMgr");
   xml_set_character_data_handler($xp, "contentMgr");
   }   
   return $xp;
  }
  function freeParser($xp){
   xml_parser_free($xp);
  }
  function getXmlArray($data, $ret=array()){
   $xp = $this->createParser(1);
   xml_parse_into_struct($xp,$data,$ret[0], $ret[1]);
   $this->freeParser($xp);  
   return $ret;
  }
  function startMgr($xp, $tag, $attrs){
  }
  function endMgr($xp, $tag){
  }
  function contentMgr($xp, $val){
  }
}
?>