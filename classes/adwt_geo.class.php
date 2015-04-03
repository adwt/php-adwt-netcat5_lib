<?php
class adwt_Geo extends adwt_Core{
   protected $api;
   protected $locale = 'ru-RU';
   protected $limit = 1; 
   protected $provider = 'yandex';
   
   public function __construct($provider=''){ 
     if(!$this->api){ 
       switch($provider){ 
	     case 'yandex' :		 
	       $this->api = $this->api_yandex_map(); 
		   break;
	     case 'google' :		 
	       $this->api = $this->api_google_map(); 
		   break;
		 default:
	       $this->api = $this->api_yandex_map(); 
		   break;		   		  
	    }
		
	 }
   }
   public function api_google_map(){
		return 'Coming soon... may be... ;)';
   }
   
   public function api_yandex_map(){
	  global $DOCUMENT_ROOT; 
	  include $DOCUMENT_ROOT."/php-adwt-netcat5_lib/php_libs/php-yandex-geo-master/autoload.php"; 
	 // new \Yandex\Geo\Api();
	  
	  return new \Yandex\Geo\Api();  
   }
   // Получение единичных координат по адресу объекта
   public function getGeoInfoByAddress( $address='' ){
	   if($address){
		  $geo_query = $this->api;
		  $geo_query->setQuery($address);
		  $geo_query
               ->setLimit(1) // кол-во результатов
               //->setLang($this->locale) // локаль ответа
               ->load();

           $geo_response = $geo_query->getResponse();
           $geo_objects = $geo_response->getList();
		   //$geo_object = $results[0];
		   return $geo_objects[0]->getData();
           /*
		   return array(
		     'lat'  => $geo_objects[0]->getLatitude(),   // Latitude  / Широта 
			 'lon'  => $geo_objects[0]->getLongitude(),  // Longitude / Долгота
			 'address' => $geo_objects[0]->getAddress(), // Real responsed address / Реальный адрес объекта
 			 'data' =>  $geo_objects[0]->getData()  //  
		   );
		   */
		  
	   }else{
		  return $this->errors[] = "ERROR: adwt_Geo::getCoordinatesByAddress(address) -> Arg is empty! ";   
	   }
	   
   }
   
}
?>
