<?php
/* Extended Classes for NetCat */
class adwt_Core{ 
   protected $adwt_path = "";
   protected $adwt_path_classes = "";
   protected $adwt_path_local_libs = "";
   //static $debug;
   function __construct(){
	   // Set paths to adwt's  classes & libs
	   global $DOCUMENT_ROOT;
	   $this->adwt_path = dirname(__FILE__);
	   $this->adwt_path_classes = $this->adwt_path."/classes";
	   $this->adwt_path_local_libs = str_replace($DOCUMENT_ROOT,"",$this->adwt_path)."/libs"; 			  
   }
     
   protected function read_all_files_from_dir_to_array($dir){
	   if ($handle = opendir($dir)) {
    		while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$arr_files_in_dir[] = $entry;
				}
      		}
     		closedir($handle);
	   } 
	   return  $arr_files_in_dir;	   
   }
   
   protected function include_class($obj_name){
       $this->get_all_available_classes();
	   if(isset($this->all_classes[$obj_name])){	 	  
		  include_once($this->adwt_path_classes."/".$this->all_classes[$obj_name]);
		  $cls_name = "adwt_".ucfirst($obj_name);
	      $this->$obj_name = new $cls_name;   
		  if(!$this->$obj_name) {
              throw new Exception(__CLASS__."::".__FUNCTION__.": Unable load adwt-extended class \"" . $cls_name . "\"!");
          }else{
			  $this->loaded_classes[] = $obj_name;
			  if($this->$obj_name->parent_object == 'required'){ $this->$obj_name->parent_object = &$this; }
		  } 
	   }
   }
   
   protected function include_classes($objects){
	   //echo dump($objects);
	   if (is_array($objects)){
		   foreach ($objects as $obj_name){
			    //echo $obj_name;
               if(!in_array($obj_name, $this->loaded_classes)){
				  // echo "Here..."
				   $this->include_class($obj_name);	
				   
                   if ($this->$obj_name->relative_classes){
			          $relative_classes = array_filter(explode(",",str_replace(" ","",$this->$obj_name->relative_classes)));
					  if(is_array($relative_classes)){
						  foreach($relative_classes as $rel_obj_name){
							  $this->include_class($rel_obj_name);	
						  }
					  }
		           }
				   
				   			    
			   }
		   }
	   }else{ 
	       //throw new Exception(__CLASS__."::".__FUNCTION__.": The list of objects is not the array!");
	   }
   }
   
   public function get_all_available_classes(){
	   $arr_files_in_dir = $this->read_all_files_from_dir_to_array($this->adwt_path_classes);
	   if (is_array($arr_files_in_dir)){
		   foreach ($arr_files_in_dir as $f){
			   $class_name = str_replace(array("adwt_",".class.php"),"",$f);
			   /* Needs  checking filename Don't forget!!! */
			   $this->all_classes[$class_name] = $f;
		   }
	   }
	   return $this->all_classes;
   }
   
   public function load_classes($list_classes){
	   $arr_classes = array_filter(explode(",",str_replace(" ","",$list_classes)));
	   $this->include_classes($arr_classes);
	   return $this->loaded_classes;
   }
   
   public function get_adwt_path(){
       return $this->adwt_path;
   }
   public static function get_object(){
        static $storage; 
        // check cache 
        if (!isset($storage)) { 
            // init object 
            $storage = new self(); 
        } 
        // return object 
        return is_object($storage) ? $storage : false; 
   }
}