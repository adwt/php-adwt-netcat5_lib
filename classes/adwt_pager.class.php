<?php
class adwt_Pager extends adwt_Core{
   public $browse_msg = array();
   
   public function __construct(){ 
      
        // шаблон вывода навигации по страницам
		
		$this->browse_msg['prefix'] = "";
		$this->browse_msg['suffix'] = "";
		$this->browse_msg['active'] = "<li class=\\\"active\\\"><a>%PAGE</a></li>";
		$this->browse_msg['unactive'] = "<li><a href=\\\"%URL\\\">%PAGE</a></li>";
		$this->browse_msg['divider'] = "
		";
		
   }
   
   public function get_pagination($cc_env, $totRows, $pages=10,$prevLink='', $nextLink=''  ){
	   global $browse_msg, $sub, $cc;
	   $browse_msg = $this->browse_msg;
	   //$paginator .= "<nav>";
	   if($prevLink || $nextLink ){
          $paginator .= "<ul class=\"pagination pagination-sm\">
		 ".( !$prevLink ? "" : "<li><a href=\"".$prevLink."\">&laquo;</a></li>" )."
         ".browse_messages($cc_env, $pages)."
		 ".( !$nextLink ? "" : "<li><a href=\"".$nextLink."\">&raquo;</a></li>" )."
	     </ul>";
        } 
		return "<nav style=\"overflow:hidden; width:100%;\">".$paginator."<div class=\"pull-right\" style=\"padding:20px 20px\" >Записей <span class=\"badge\">$totRows</span></div></nav>";

	   
   }
   
}
?>
