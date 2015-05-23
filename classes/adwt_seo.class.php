<?php
class adwt_SEO extends adwt_Core{

   public $arr_landing_all_metatags = array();
   public function __construct(){  
       global $nc_core;
       $this->arr_landing_all_metatags[default] = $nc_core->page->get_meta_tags();
	  //$this->arr_ral = json_decode($this->json_ral);
	  
   }
   
   public function set_landing_metatags($arr_metatags){ 
    
	   //$this->arr_landing_all_metatags = $arr_metatags;	   
   }
 
   public function get_landing_metatags(){
       
	   return $this->arr_landing_all_metatags;
	   
   }
  
   
}
?>
