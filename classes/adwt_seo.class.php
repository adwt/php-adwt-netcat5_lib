<?php
class adwt_SEO extends adwt_Core{
   protected $relative_classes = 'libs';
   public $parent_object = 'required';
   public $arr_landing_all_metatags = array();
   public $current_url ;
   public function __construct(){ 
       //$this->parent_object = $parent;
       //$this->arr_landing_all_metatags['default']['H1'] = $nc_core->page->get_h1();
       global $nc_core, $current_sub;
       $this->current_url = $nc_core->url->source_url();
       $this->arr_landing_all_metatags[$this->current_url]['title'] = $nc_core->page->get_title();
       $this->arr_landing_all_metatags[$this->current_url]['H1'] = ( $current_sub[AlterTitle] ? $current_sub[AlterTitle] : $nc_core->page->get_h1() );
       $this->arr_landing_all_metatags[$this->current_url]['keywords'] = $nc_core->page->get_keywords(); 
       $this->arr_landing_all_metatags[$this->current_url]['description'] = $nc_core->page->get_description(); 
	  
   }
   
   public function set_landing_metatags($arr_metatags){  
       //dump($nc_core);
       if(count($arr_metatags)){
          $this->arr_landing_all_metatags = array_merge($arr_metatags, $this->arr_landing_all_metatags);
       }    
	   //$this->arr_landing_all_metatags = $arr_metatags;	   
   }

   public function get_landing_metatags(){
       
	   return $this->arr_landing_all_metatags;
	   
   }
   
   public function get_metatags_from_cc_settings(){
    
       global $nc_core, $cc_array; 
       if(count($cc_array)>1){
          foreach($cc_array as $cc){
            unset($CustomSettings);
              $str_cc_settings = $nc_core->sub_class->get_by_id($cc,'CustomSettings');
              eval($str_cc_settings);
              $url = $this->current_url."#".$nc_core->db->get_var("SELECT EnglishName FROM Sub_Class WHERE Sub_Class_ID = '$cc'");
              if($CustomSettings['ccTitle']){ 
                 $arr_metatags[$url]['Title'] = $CustomSettings['ccTitle']; 
              }else{ 
                 $arr_metatags[$url]['Title'] = $this->arr_landing_all_metatags[$this->current_url]['title']; 
              }
              if($CustomSettings['ccH1']){ 
                 $arr_metatags[$url]['H1'] = $CustomSettings['ccH1']; 
              }else{ 
                 $arr_metatags[$url]['H1'] = $this->arr_landing_all_metatags[$this->current_url]['H1']; 
              }
              if($CustomSettings['ccKeywords']){ 
                 $arr_metatags[$url]['Keywords'] = $CustomSettings['ccKeywords']; 
              }else{ 
                 $arr_metatags[$url]['Keywords'] = $this->arr_landing_all_metatags[$this->current_url]['keywords']; 
              }
              if($CustomSettings['ccDescription']){ 
                 $arr_metatags[$url]['Description'] = $CustomSettings['ccDescription']; 
              }else{ 
                 $arr_metatags[$url]['Description'] = $this->arr_landing_all_metatags[$this->current_url]['description']; 
              }
          }
       }
       
	   //return $arr_metatags;
	   $this->set_landing_metatags($arr_metatags);
	}
	
	public function get_landing_engine_init_handler($tags_selector = 'a[href^="#"]'){
	    $code_js = "
	    <script type=\"text/javascript\">
	    \$('document').ready(function(){
	       \$('".$tags_selector."').bind('click',function(){
	          adwt_seo_set_metatags(\$(this).attr('href'));
	       });
	    });
	    </script>
	    ";
	    return $code_js;
	
	}
	
	public function get_landing_engine_by_cc($tags_selector=''){
	   
	   //dump($this->parent_object->libs->load_lib('purl'));
	   $this->get_metatags_from_cc_settings();
	   $code_js  = $this->parent_object->libs->load_lib('purl');
	   $code_js .= "
	   <script type=\"text/javascript\">
	    var adwt_seo_landing_all_metatags = ".str_replace("\\/","/",json_encode($this->arr_landing_all_metatags, JSON_UNESCAPED_UNICODE )).";
	    var url_lp_default = window.location.protocol+'//'+window.location.hostname+window.location.pathname;
	    
	    var tags_default = adwt_seo_landing_all_metatags[url_lp_default];
	    
	    
	    console.info(adwt_seo_landing_all_metatags); // if you check it... 
	    console.info(tags_default); // if you check it... 
	    //console.info(\$(document).is('title'));
	    
	    /* ---------------------------------------------------------------------- */
	    /* You can use this function for changing metatags from the array bellow. */
	    /* In order to do that simply add this function to your event handler     */
	    /* ATTENTION: 'jQuery' and 'purl' are required!                           */
	    /* ---------------------------------------------------------------------- */
		function adwt_seo_set_metatags(href){
		    // alert(href);
		    var href_parsed = purl(href);
			// Possible anchors: href='#', href='#anchor', href='/pathname/#anchor', href='http://hostname/pathname/#anchor'
			// Therefore we have to make 
			var url_href = url_lp_default +'#'+href_parsed.attr('fragment');
		    
		    if( href_parsed.attr('fragment')){
		        
			  	var tags_new = adwt_seo_landing_all_metatags[url_href];
			  	
	    			    		
	    		if(tags_new.Title) \$('title').text(tags_new.Title); 
				else \$('title').text(tags_default.Title); 
				
	    		if(tags_new.H1) \$('h1').text(tags_new.H1); 
	    		else \$('h1').text(tags_default.H1); 
	    		
	    		if(tags_new.Keywords) \$('meta[name=\"Keywords\"]').attr('content', tags_new.Keywords); 
	    		else \$('meta[name=\"Keywords\"]').attr('content', tags_default.Keywords); 
	    		
	    		if(tags_new.Description) \$('meta[name=\"description\"]').attr('content', tags_new.Description); 
	    		else \$('meta[name=\"description\"]').attr('content', tags_default.Description); 
	    		
	    		return true;
	    	
			}else{
			  return false;
			}
	    	      
	       
	    };
	    \$('title, meta, h1').ready(function(){ adwt_seo_set_metatags(window.location.href); });
	   </script>
	   ".($tags_selector !='' ? $this->get_landing_engine_init_handler($tags_selector) : null );
       return $code_js; 
      
	}
	

  
   
}
?>
