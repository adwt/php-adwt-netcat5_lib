<?php
class adwt_Table extends adwt_Core{

   public $table_css_class = '';
   public $table_attr = '';
   public $table_header = '';
   public $rows = array();
   
   public function __construct(){  
      // Does nothing...
   }
 
   public function init($css_class='', $attr=''){
	   $new_table = new adwt_Table();
	   $new_table->table_css_class = "table table-condensed ".$css_class;
	   $new_table->table_attr  = $attr;
	   return $new_table;
	   
   }
   
   // Function for setting table header 
   // @args - each arg as string like 'Name1##class1 class2##num_colspan','Name2##class2','Name3'... 
   // Returns full table thead tags
   public function setHeader(){
	   $arg_list = func_get_args();
	   
	   if(is_array($arg_list)){
		  $this->table_header .= "<thead>\n<tr>\n";
		  foreach($arg_list as $th){
			  $th_params = explode("##",$th);
			  $this->table_header .= "\t<th".( $th_params[1] ? " class=\"".$th_params[1]."\"" : null).($th_params[2] ? " colspan=\"".$th_params[2]."\"" : null).">".$th_params[0]."</th>\n"; 
		  }
		  $this->table_header .= "</tr>\n</thead>\n";	   
	   }
	      
	   return $this->table_header;
   }
   // Function for setting table header 
   // @args - each arg as string like 'TD_value1##class1 class2##num_colspan','Name2##class2','Name3'... 
   // Returns full table thead tags   
   public function setRow(){
	   $arg_list = func_get_args();
	   if(is_array($arg_list)){
		  $table_row = "<tr>\n";
		  foreach($arg_list as $td){
			  $td_params = explode("##",$td);
			  $table_row .= "\t<td".( $td_params[1] ? " class=\"".$td_params[1]."\"" : null).($td_params[2] ? " colspan=\"".$td_params[2]."\"" : null).">".$td_params[0]."</td>\n"; 
		  }
		  $table_row .= "</tr>\n";
		  return $this->rows[] = $table_row;   
	   }	   
   }
   

   public function getHeader(){
	   return $this->table_header;
   }
   public function getRows(){
	   return "<tbody>\n".implode("",$this->rows)."</tbody>\n";
   }

   public function getTable(){
	   return "<table class=\"".$this->table_css_class."\"".($this->table_attr ? " ".$this->table_attr : null ).">"
	         .$this->getHeader()
	         .$this->getRows()
			 ."</table>";   
   }
   
   
}
?>
