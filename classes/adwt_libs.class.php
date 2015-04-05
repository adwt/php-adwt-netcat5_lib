<?php
class adwt_Libs extends adwt_Core{
   public $loaded_libs = array();
   protected $adwt_path_local_libs ='/php-adwt-netcat5_lib/libs';
   protected $libs = array(
      'chosen' => array(
	     'last_stable_version' => '1.3.0',
	     '1.3.0' => array(
		    'local' => array(
			    'css' => 'chosen.min.css', 'js' => 'chosen.jquery.min.js'
			 )
		  )
	   ),
	   'bootstrap' => array(
	     'last_stable_version' => '3.3.2',
	     '3.3.2' => array(
		    'local' => array(
			    'css' => 'css/bootstrap.min.css', 'js' => 'js/bootstrap.min.js'
			 ),
			 'cdn' => array(
			    'css' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
				'js'  => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js'
			 )
		  )
	   ),
	   'bootstrap__js' => array(
	     'last_stable_version' => '3.3.2',
	     '3.3.2' => array(
		    'local' => array(
			    'js' => 'js/bootstrap.min.js'
			 ),
			 'cdn' => array(
				'js'  => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js'
			 )
		  )
	   ),  
	   'bootstrap__css' => array(
	     'last_stable_version' => '3.3.2',
	     '3.3.2' => array(
		    'local' => array(
			    'css' => 'css/bootstrap.min.css'
			 ),
			 'cdn' => array(
			    'css' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css'
			 )
		  )
	   ),	    
 	   'fontawesome' => array(
	     'last_stable_version' => '4.3.0',
	     '4.3.0' => array(
		    'local' => array(
			    'css' => 'css/font-awesome.min.css'
			 ),
			 'cdn' => array(
			    'css' => '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'
			 )
		  )
	   ),
	   'colorbox' => array(
	     'last_stable_version' => '1.5.15',
	     '1.5.15' => array(
		    'local' => array(
			    'css' => 'example1/colorbox.css',				
				'js' => array('jquery.colorbox-min.js','i18n/jquery.colorbox-ru.js')
			 )
		  )
	   ),
	   'validate' => array(
	     'last_stable_version' => '1.13.1',
	     '1.13.1' => array(
		    'local' => array(				
				'js' => array('jquery.validate.min.js','message_ru.js')
			 )
		  )
	   )
   
   );
   
   public function __construct(){  
      //parent::__construct();
	  //$this->adwt_path_local_libs = parent::get_path_local_libs();
       
   }
   
   public function load($list_libs, $load_target = 'local'){
       $html = '';
	   $arr_libs = array_filter(explode(",",str_replace(" ","",$list_libs))); 
	   foreach($arr_libs as $l){
		   if(!in_array($l,$this->loaded_libs) || empty($arr_libs)){
			   $html .= $this->load_lib($l,$load_target);
			   $this->loaded_libs[] = $l;   
		   }
	   }
	   return $html;
	   
   }
   public function load_lib($lib_name, $lib_target = 'local'){
	   $html = '';
	   $lib = $this->libs[$lib_name][$this->libs[$lib_name]['last_stable_version']][$lib_target];
       if($lib_target == 'local'){ 
	      $path_to_local_lib = $this->adwt_path_local_libs."/".$lib_name."/".$this->libs[$lib_name]['last_stable_version']; 
	   }else{
		  $path_to_local_lib = ''; 
	    }	    
	   if(!empty($lib)){

		  foreach($lib as $k=>$v){
			  if(is_array($v)){
                 $listen_v = &$v;
			  }else{
				 $listen_v = array($v); 				  
			  }
	          foreach($listen_v as $l){
 	             switch($k){
				 case 'css':
				    $html .= $this->load_css($path_to_local_lib."/".$l); 
					break;
				 case 'js':
				    $html .= $this->load_js($path_to_local_lib."/".$l); 
					break;
				  
			     }
			  } 

		  }   
	   }else{
		   
	   }
	   return $html;
	   
   }
   public function load_css($css_path){
	   return "<link href=\"$css_path\" rel=\"stylesheet\" type=\"text/css\">\n";   
	   
   }  
   public function load_js($js_path){
	      
	   return "<script  type=\"text/javascript\" src=\"$js_path\"></script>\n";
   }   
}

?>