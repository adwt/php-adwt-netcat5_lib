<?php
class adwt_Form extends adwt_Core{
   
   static $cc;
   static $sub;
   static $class_id;
   static $mode='';
   static $filename='';
   static $enc='';
   static $message;
   static $catalogue;
   static $chosen_fields=array();
   public $css_class_form='form-horizontal';
   public $css_class_left_col='col-lg-4 col-md-4 col-sm-12 col-xs-12';
   public $css_class_right_col='col-lg-8 col-md-8 col-sm-12 col-xs-12';
   static $submit_button_name = '';
   public $submit_button_class = 'btn btn-block btn-success btn-lg';
   protected $users_css='';
   protected $users_jscript='';
   public $css;
   public $jscript;   
   
   public function __construct(){  

   }
   
   public function init($form_cc=0, $form_sub=0, $form_class_id=0, $form_enc =''){
	  global $cc, $sub, $class_id, $classID, $message, $nc_core, $catalogue ;
	  $new_form = new adwt_Form();
	  $new_form->cc = ($form_cc ? $form_cc : $cc);
	  $new_form->sub = ($form_sub ? $form_sub : $sub);
	  $new_form->class_id = ($form_class_id ? $form_class_id  : ($class_id ? $class_id : $classID));
	  if($message){
		  $new_form->message = $message;
		  $new_form->filename = 'message';
		  $new_form->mode = 'change';
	  }else{
		 $new_form->filename = 'add';
		 $new_form->mode = 'add';			
	  }
	  $new_form->enc = ($form_enc ? $form_enc : 'multipart/form-data');
	  $new_form->catalogue = $catalogue;
	  return $new_form;
	  
   }
   public function get_js(){
	   global $inside_admin;
	   $script_start = " <script type=\"text/javascript\">\n";
	   
	   if($inside_admin){
		   $script_start .= "\tjQuery(this).ajaxComplete(function(){\n";
		   $script_end .= "});\n";
	   }else{
		   $script_start .= "\tjQuery(document).ready(function(){\n";
		   $script_end .= "});\n";
	   }
	   $script_end .= "</script>\n";
	   if(is_array($this->jscript)){
		   $script .= join("\n",$this->jscript);
	   }else{
		   $script .=  $this->jscript."\n";  
	   }
       
	   return $script_start.$script.$this->users_jscript.$script_end;
   }
   
   public function get_css(){
       $css_start = " <style>\n";
	   $css_end = "</style>\n";
	   if(is_array($this->css)){
		   $css = join("\n",$this->css)."\n";
	   }else{
		   $css = $this->css."\n";
	   }

	   return $css_start.$css.$this->users_css.$css_end;
   }
   
   public function get_header(){
	  global $SUB_FOLDER, $HTTP_ROOT_PATH, $nc_core;
	  global $admin_mode,$inside_admin,$curPos,$f_Parent_Message_ID,$systemTableID,$current_cc,$f_Checked;
	  global $f_Priority , $f_Keyword, $f_ncTitle, $f_ncKeywords, $f_ncDescription;
	  
	  if($inside_admin){
		  $form_id = "adminForm";
		  $html .="
		    <style>		  
			  #adminForm label{ display:block; font-weight: bold; font-size: 1.1em; }
			  .nc_form{ padding:20px;  background-color:#f6f6f6; margin:20px 20px 0px 0px; }
			  .nc_form table td{ vertical-align:top;}
			</style>";
	  }else{
		  $form_id = "AdminForm__".$this->sub."-".$this->cc."-".$this->class_id."_".$this->mode."";
		  
		  //$this->css[] =  "form.nc_form{ padding:20px; background-color:#f6f6f6; border-radius:7px;}";
		   
		  /*$html .="
		    <style>
			  form.nc_form{ padding:20px; background-color:#f6f6f6; border-radius:7px;}
			</style>";		  
			*/
	  }
          $this->jscript[] = "
			     jQuery('ul.dropdown-menu.adwt a').click(function(){
					 var obj_parent_input_group =  jQuery(this).parents('div.input-group'); 
					 obj_parent_input_group.children('input.form-control').val((jQuery(this).attr('title') ? jQuery(this).attr('title') : jQuery(this).html()));
					 jQuery(this).parents('ul.dropdown-menu').hide('fast');
				  });
			  
			  ";
			  
	  if($inside_admin){
				  $this->css[] = "
				  form.nc_form  .input-group{ white-space: nowrap !important; position:relative; }
				  form.nc_form  .input-group ul.dropdown-menu{ z-index:999; width:auto; height: auto; overflow-y: scroll; border: solid 1px #dadada; display:none; position:absolute; left:0px; top: 35px; background:#fff; margin:0;padding:10px 0; }
                  form.nc_form  .input-group ul.dropdown-menu li{ display:block; margin:0; padding: 5px 20px;}
				  form.nc_form  .input-group-btn{ display:inline; width:25px;}
				  form.nc_form  /*.input-group*/ input.form-control{ width:90% !important; } 
				  "; 
				  $this->jscript[] = "
				  jQuery('button.dropdown-toggle').unbind('click');
				  jQuery('button.dropdown-toggle').click(function(){
					     var obj_parent_input_group = jQuery(this).parent('div.input-group-btn');
						
						 obj_parent_input_group.children('ul.dropdown-menu').toggle('showOrHide');

					  });
				  "; 
	   }
	 $html .="<form name='$form_id' enctype='".$this->enc."' method='post' id='$form_id' class='nc_form".(!$inside_admin ? " ".$this->css_class_form : null )."' action='".$SUB_FOLDER.$HTTP_ROOT_PATH.$this->filename.".php'>
     <div id='nc_moderate_form'>
	 <div class='nc_clear'></div>
     <input name='admin_mode' type='hidden' value='$admin_mode' />
     ".$nc_core->token->get_input()."
     <input name='catalogue' type='hidden' value='".$this->catalogue."' />
     <input name='cc' type='hidden' value='".$this->cc."' />
     <input name='sub' type='hidden' value='".$this->sub."' />
	 <input name='posting' type='hidden' value='1' />
     <input name='curPos' type='hidden' value='$curPos' />
	 <input name='message' type='hidden' value='".$this->message."' />
     <input name='f_Parent_Message_ID' type='hidden' value='$f_Parent_Message_ID' />
     ".nc_form_moderate($this->mode, $admin_mode, 0, $systemTableID, $current_cc, (isset($f_Checked) ? $f_Checked  : null), $f_Priority , $f_Keyword, $f_ncTitle, $f_ncKeywords, $f_ncDescription )."
    </div>";
	return $html;
   }
   
   public function get_warning($warnText=''){
	   global $inside_admin;
	   if ($warnText && !$inside_admin){
		  return "<div class=\"warnText alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> $warnText</div>";   
	   }else{
		  return ''; 
	   }  	   
   }
   

   
   public function get_submit_button(){
	   global $inside_admin;
	   if($this->submit_button_name ==''){
	      switch($this->mode){
			 case 'add':
		       $this->submit_button_name =  NETCAT_MODERATION_BUTTON_ADD; 
			   break;
			 case 'change':
			   $this->submit_button_name =  NETCAT_MODERATION_BUTTON_EDIT; 
			   break;
			 default:
			   break;
	      }
       }
	   
	   if(!$inside_admin){
	       return "<input type=\"submit\" class=\"".$this->submit_button_class."\" value=\"".$this->submit_button_name."\" >";
	   }else{
		   return '';   
	   }
   }
   
   static function get_ul_dropdown_with_distinct_values($field_name, $class_id, $cc=0, $checked='0'){
	   global $nc_core;
	   $sql = "SELECT DISTINCT `$field_name` FROM Message".(int)$class_id." WHERE `$field_name` !='' ".($cc ? " AND Sub_Class_ID = '$cc'" : "" )."  ".($checked ? " AND Checked" : "" )." ORDER BY $field_name";
	   //$sql = 'SELECT';
	   $results = $nc_core->db->get_results( $sql , ARRAY_A );
	 
	   if(is_array($results)){
		   $html = "
		   <div class=\"input-group-btn\">
			  <button type=\"button\" class=\"btn btn-default dropdown-toggle\" id=\"dropdownMenu-$field_name\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-caret-down\"></i></button>
		    <ul class=\"dropdown-menu dropdown-menu-right adwt\" role=\"menu\"  aria-labelledby=\"dropdownMenu-$field_name\">";
		    foreach($results as $r){
		            $html .= "<li><a href=\"javascript:void(0)\" >".$r[$field_name]."</a></li>";
			}
		    $html .= "
			</ul>
		   </div>
				  
            ";
		     
		   return $html;
	   }else{ 
	      return '';
	   }
	   
   }
   
   public function get_footer(){
	   global $AUTH_USER_ID, $current_cc, $MODULE_VARS, $inside_admin;
	   
	   if(!$inside_admin  && $this->css_class_form == 'form-horizontal'){
		   $empty_col = "<div class=\"".$this->css_class_left_col."\"></div>";
		   $field_wrapper_begin = "<div class=\"".$this->css_class_right_col."\">";
		   $field_wrapper_end = "</div>";  
	   }
	   
	   $html = "";
	   if (!$AUTH_USER_ID && $current_cc['UseCaptcha'] && $MODULE_VARS['captcha']) { 
	     $html .= "
		 <div class=\"form-group\">
		    <label for=\"nc_captcha_code\" ".(!$inside_admin  && $this->css_class_form == 'form-horizontal' ? "class=\"control-label ".$this->css_class_left_col."\"" : null).">".NETCAT_MODERATION_CAPTCHA."<span class=\"text-danger\">*</span>:</label>".
		 "  ".$field_wrapper_begin."<div class=\"text-nowrap\"><input type=\"text\" id=\"nc_captcha_code\" name=\"nc_captcha_code\" size=\"6\" class=\"form-control\" style=\"display:inline-block; width:100px !important;\"> ".nc_captcha_formfield().$field_wrapper_end."</div>
		 </div>
		 "; 
	   }
	   $html .="
	   $empty_col
	   $field_wrapper_begin
	     <div ".($inside_admin ? "style='background-color:lightblue; margin-top:15px; padding:15px;'": "class=\"row alert alert-info\"").">".NETCAT_MODERATION_INFO_REQFIELDS."</div>
	   $field_wrapper_end 
		 ".
	   "<div class=\"form-group\">".
	   $empty_col.$field_wrapper_begin.$this->get_submit_button().$field_wrapper_end.
	   "</div>
	   </form>
	   ".$this->get_css()."
	   ".$this->get_js()."
	   ";

	   return $html;
	   
   }
   /*
    *  Examples: 
	*  (1) $form->get_field('string','Name','Название*:', 'placeholder="Ввведите без ошибок..." required', 1,$f_Name );
    */
   public function get_field($type, $name, $label='', $attr='', $current_value='' ){
	   global $inside_admin, $nc_core;
	   $field_header = '<div class="form-group">';
	   $field_footer = '</div>';
		   if( $label != '' ){
			   $field_label = "<label for=\"field-$name\" ".(!$inside_admin  && $this->css_class_form == 'form-horizontal' ? "class=\"control-label ".$this->css_class_left_col."\"" : null).">$label</label>";
			   $nc_label_control = 0;
		   }else{
			   $nc_label_control = 1;
		   }   
	   
	   
       if(!$inside_admin && $this->css_class_form == 'form-horizontal'){
		   $field_wrapper_begin = "<div class=\"".$this->css_class_right_col."\">";
		   $field_wrapper_end = "</div>";
	   }
	   
	   
	   switch($type){
		   case 'string':
		      $field_body = nc_string_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value);
		      break;
		   case 'string_select':
		      $field_body = "
			  <div class=\"input-group\">
			     ".nc_string_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value)."
                 ".$this->get_ul_dropdown_with_distinct_values($name, $this->class_id)."
			  </div>
			  ";			  
		      break;
			case 'int_select':
		      $field_body = "
			  <div class=\"input-group\">
			     ".nc_int_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value)."
                 ".$this->get_ul_dropdown_with_distinct_values($name, $this->class_id)."
			  </div>
			  ";  
		      break;
		   case 'string_select_cc':
		      $field_body = "
			  <div class=\"input-group\">
			     ".nc_string_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value)."
                 ".$this->get_ul_dropdown_with_distinct_values($name, $this->class_id, $this->cc)."
			  </div>
			  ";			  
		      break;
			case 'int_select_cc':
		      $field_body = "
			  <div class=\"input-group\">
			     ".nc_int_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value)."
                 ".$this->get_ul_dropdown_with_distinct_values($name, $this->class_id, $this->cc)."
			  </div>
			  ";  
		      break;
		   case 'bool':
		      /*nc_bool_field('isNew', "", ($class_id ? $class_id : $classID ), 1)*/
			  if(!$inside_admin && $this->css_class_form == 'form-horizontal'){ $field_label = "<div class=\"".$this->css_class_left_col."\">&nbsp;</div>"; }else{  $field_label = ""; }
		      $field_body ="<div class=\"checkbox\"><label>".nc_bool_field($name, "id=\"field-$name\"", $this->class_id, $nc_label_control)." $label</label></div>";    
		      break;
		   case 'int':
		      $field_body = nc_int_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value);
		      break;			  
		   case 'float':
		      $field_body = nc_float_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value);
		      break;
		   case 'chosen_list':
   		      $field_body = nc_list_field($name, "class=\"form-control chzn-select-$name\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value);
              $this->jscript[] = "\tjQuery('.chzn-select-$name').chosen({width:'98%'}); \n";
		      break; 
		   case 'chosen_multilist':
   		      $field_body = nc_multilist_field($name, "class=\"form-control chzn-select-$name\" $attr id=\"field-$name\"", 'select', $this->class_id, $nc_label_control, $current_value);
              $this->jscript[] = "\tjQuery('.chzn-select-$name').chosen({width:'98%'}); \n";		      
		      break;
		   case 'list':
   		      $field_body = nc_list_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value);
		      break; 			  
		   case 'text':
   		      $field_body = nc_text_field($name, "class=\"form-control\" $attr id=\"field-$name\"", $this->class_id, $nc_label_control, $current_value);
		      break;
		   case '':
		      break;
		   default:
		      $field_body = "<p class=\"form-control-static\">Unknown field $name:$type! But yet...;)</p>";
			  break;
	   }
	   
	   return $field_header.
	          $field_label.
			  $field_wrapper_begin.
			  $field_body.
			  $field_wrapper_end.
			  $field_footer;
   }
   
}

?>
