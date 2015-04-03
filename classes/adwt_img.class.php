<?php
class adwt_Img extends adwt_Core{
   public $imgs;
   //protected $message_id;
   public function __construct(){  
      // Does nothing... 
   }
   
   public function init($img){
       global $DOCUMENT_ROOT;
	   if($img){
		   
		  $new_imgs_array = new adwt_Img(); 
		  if(is_object($img)){ // If field is nc_multifield
			 $new_imgs_array->imgs = $img->records;
		  }elseif(is_string($img)){
			 $new_imgs_array->imgs[0][Path] = $img;
			 //$new_imgs_array->imgs[0][Size] = $$img_size;			 
		  }
		  
		  foreach($new_imgs_array->imgs as $k=>$i){
			$i_size = @getimagesize($DOCUMENT_ROOT."/".$i[Path]);
			$new_imgs_array->imgs[$k]['adwt_img_params'] = $i_size;
			$new_imgs_array->imgs[$k]['Orientation'] = $this->get_img_orientation($i_size[0],$i_size[1]);
		  }		  
		  return $new_imgs_array;	 
		  
	  }else{
		 return null;  
	  }
   }
   
   /*
   * Function for making up the thumbnail
   * @img string/object param of NetCat as 'f_Pic'
   * @return html-sting
   */
   public function get_tbn($img=null, $w, $h, $alt='', $mode='crop'){

   }
   /*
   * Function for getting up the img orintation
   * @i_size array params from standart php function GETIMAGE
   * @return sting 'vertical/horisontal/square'
   */
   public function get_img_orientation($w,$h=1){    
	    $ratio = $w/$h;
		switch($ratio){
		  case 1:
		    return 'square';
		  	break; 
		  case $ratio > 1:
			return 'horizontal';
			break;
		  case $ratio < 1:
			return 'vertical';
			break;
		  default:
			return '';
			break;
		 }		     
   } 
   
   public function get_static_collection ($id_collection = 'default'){
	   
	    
	    $html .= "
		      <style>
			    .adwt--img--list{ padding-bottom: 15px;}
                .adwt--img--list__item{ margin-top: 15px;}
                .adwt--img__crop{ position:relative; overflow:hidden; border-radius:3%; }
                .adwt--img--list__item .adwt--img__crop{border-radius:10%;}
                .adwt--img__crop .fa{ position:absolute; top:-5%; left:-5%; z-index:100; padding-left:50%; padding-top:50%}
                a:hover .adwt--img__crop{ box-shadow: 0px 0px 10px rgba(150,150,150,0.7);}
                .adwt--img__crop .fa{ opacity:0.7;}
                a:hover .adwt--img__crop .fa{ opacity:1; }
                .adwt--img__crop img.horizontal{ position:absolute; top:0px; height:100%; width:auto; margin-left:-25%; margin-top:0; z-index:-1;}
                .adwt--img__crop img.vertical{ position:absolute; left:0px; height:auto; width:100%; margin-left:0; margin-top:-25%; z-index:-1;}
              </style>		
		"; 
		
		$html .= "
		
		       <script>
			       jQuery(document).ready(function(){
					  jQuery('div.adwt--img__crop').each(function(){
						  jQuery(this).height(jQuery(this).width());  
					  });
                      jQuery('.adwt--gallery__$id_collection').colorbox({ rel:'adwt--gallery__$id_collection'});
                   });
               </script>
		
		";
		// Makes the first big tbn
		$html .= "
		  <div class=\"row\">
            <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 adwt--img__item\">
              <a href=\"".$this->imgs[0][Path]."\" class=\"adwt--gallery__$id_collection\">
               <div class=\"adwt--img__crop\" style=\"height:332px;\">
                 <i class=\"fa fa-search-plus fa-inverse fa-3x\"></i>
                 <img src=\"".$this->imgs[0][Path]."\" alt=\"".$this->imgs[0][Name]."\" class=\"".$this->imgs[0][Orientation]."\"/>
               </div>
               </a>
            </div>
          </div>
		
		";
		// Makes others tbns
		$imgs_count = count($this->imgs);
		if ( $imgs_count > 1 ){
		  $html .="
		  <div class=\"row\">
		  ";	
			for ($i=0; $i++ < $imgs_count-1;){
			    $html .="				
			<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-4 adwt--img--list__item\">
              <a href=\"".$this->imgs[$i][Path]."\" class=\"adwt--gallery__$id_collection\">
               <div class=\"adwt--img__crop\" style=\"height:85px;\">
                 <i class=\"fa fa-search-plus fa-inverse\"></i>
                 <img src=\"".$this->imgs[$i][Path]."\" alt=\"".$this->imgs[$i][Name]."\" class=\"".$this->imgs[$i][Orientation]."\" />
               </div>
               </a>             
            </div>
				";	
				
		    }
		  $html .="
		  </div>
		  ";
	    }
		
	    return $html;   
   }
   
}
?>
