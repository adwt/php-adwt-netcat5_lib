<?php
$NETCAT_FOLDER = join( strstr(__FILE__, "/") ? "/" : "\\", array_slice( preg_split("/[\/\\\]+/", __FILE__), 0, -4 ) ).( strstr(__FILE__, "/") ? "/" : "\\" );

include_once ($NETCAT_FOLDER."vars.inc.php");
//echo $INCLUDE_FOLDER;
require ($INCLUDE_FOLDER."index.php");

$nc_auth = nc_auth::get_object();

	// Класс авторизации через uLogin
	class adwt_authEx_ulogin extends nc_authEx{
		
		public function __construct() {
        	parent::__construct();
        	$this->name = 'ulogin';
    	}
		
		public function get_info_ulogin() {
			$user_info_json = file_get_contents('http://ulogin.ru/token.php?token='.$_POST['token'].'&host='.$_SERVER['HTTP_HOST']);
			$user = json_decode($user_info_json, true);
			return $user;

		}
		
   		public function make_ulogin2nc_user($user) {
       		 $nc_core = nc_Core::get_object();
        	// соответствие полей
            $fl[Login] = $user['network']."_".$user['identity'];
			$fl[Email] = $user['email'];
			$fl[ContactName]  = implode(" ", array($user['last_name'],$user['first_name']));
			//$fl[LastName] = $user['last_name'];
			$fl[ForumName] =  implode(" ", array($user['last_name'],$user['first_name']));
			$fl[ForumAvatar] = $user['photo'];
			
      		// группы
        	$groups = $nc_core->get_settings('group', 'auth');


        	$add_fields['UserType'] = $this->name."_".$user['network'];
        	$password = md5(rand(6, 100).time());

        	if (!$nc_core->NC_UNICODE) $fl = $nc_core->utf8->array_utf2win($fl);
            
            $user_id = $nc_core->db->get_var("SELECT User_ID FROM `User` WHERE Email = '".$user['email']."' AND Confirmed LIMIT 1");

        	if(!$user_id){ 
              $user_id = $nc_core->user->add($fl, $groups, $password, $add_fields); 
            }
        	$nc_core->db->query("INSERT INTO `Auth_ExternalAuth` (User_ID, ExternalUser ) VALUES ('".$user_id."','".$nc_core->db->escape($fl[Login])."' ) ");

        	//$this->eval_addaction($user_id, $user, $ex_user_id);
        	return $user_id;
    	}
	
	} 

    $adwt_auth_ulogin = new adwt_authEx_ulogin();
	//echo dump($adwt_auth_ulogin);
    $userinfo = $adwt_auth_ulogin->get_info_ulogin();
	if(is_array($userinfo)){
       $user_id = $db->get_var("SELECT User_ID FROM `Auth_ExternalAuth` WHERE ExternalUser = '".$userinfo['network']."_".$userinfo['uid']."' ");
		if(!$user_id){
		   $user_id = $adwt_auth_ulogin->make_ulogin2nc_user($userinfo);
		}
		$nc_core->user->authorize_by_id($user_id, NC_AUTHTYPE_EX);
	}
   
//header("Location: http://".$HTTP_HOST.$REQUESTED_FROM);
header("Location: http://".$HTTP_HOST.$REQUESTED_FROM);
exit;

//$groups = $nc_core->get_settings('group', 'auth');

//dump($groups);



/*

$nc_result_msg = ob_get_clean();

if ($File_Mode) {
    require_once $INCLUDE_FOLDER.'index_fs.inc.php';

    echo $template_header;
    echo $nc_result_msg;
    echo $template_footer;
} else {
    eval("echo \"".$template_header."\";");
    echo $nc_result_msg;
    eval("echo \"".$template_footer."\";");
}

*/

?>