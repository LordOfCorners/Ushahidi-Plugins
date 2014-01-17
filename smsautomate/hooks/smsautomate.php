<?php defined('SYSPATH') or die('No direct script access.');
/**
 * smsautomate Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
 
 /*test*/

class smsautomate {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
	
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
		
		$this->settings = ORM::factory('smsautomate')
				->where('id', 1)
				->find();
	}
	

	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		Event::add('ushahidi_action.message_sms_add', array($this, '_parse_sms'));		
	}

	/**
	 * Check the SMS message and parse it
	 */
	public function _parse_sms()
	{
	
		$result = mysql_query("SHOW TABLES LIKE 'actionable'"); //see if Actionable plugin is being used.
		$actionableExists = mysql_num_rows($result) > 0;

		//the message
		$message = Event::$data->message;
		$message_id = Event::$data->id;
		$from = Event::$data->message_from;
		$reporterId = Event::$data->reporter_id;
		$message_date = Event::$data->message_date;
		$sms_from = Kohana::config("settings.sms_no1");
		$goodLocation = true;
		$goodFormat = true;
		$badCodes = array();
		$badCode = false;
	/*
	//check to see if we're using the white list, and if so, if our SMSer is whitelisted
		$num_whitelist = ORM::factory('smsautomate_whitelist')
		->count_all();
		if($num_whitelist > 0)
		{
			//check if the phone number of the incoming text is white listed
			$whitelist_number = ORM::factory('smsautomate_whitelist')
				->where('phone_number', $from)
				->count_all();
			if($whitelist_number == 0)
			{
				return;
			}
		}
*/
		
		//the delimiter
		$delimiter = $this->settings->delimiter;
/*  		$delimiter = " "; */

		
	//add trim to exploded array
		
		//split up the string using the delimiter
		$message = trim($message);
		$message_elements = explode(" ", $message);
		
		//echo Kohana::debug($message_elements);
		
		//check if the message properly exploded
		$elements_count = count($message_elements);
		
		if($elements_count <= 1){
			$goodFormat = false;
			if(strtolower($message_elements[0])=="help" || strtolower($message_elements[0])=="ayuda"){
				$help=true;
			}
		}
		
		//convert strings to uppercase
		for($j=0;$j<$elements_count;$j++){
			$message_elements[$j]= strtoupper($message_elements[$j]);
		}
//if($goodFormat){
		if($message_elements[0]=="170404"){			
			$location_description = "Campur C/S";
			$facilityType = "Centro de Salud";
			$municipality = "San Pedro Carcha";
			$department = "Alta Verapaz";
			$dms = "Campur";
			$das = "Alta Verapaz";
			$location_lat = 15.63346211;
			$location_long = -90.04852005;
		}
		else if($message_elements[0]=="170405"){			
			$location_description = "Cojaj P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Pedro Carcha";
			$department = "Alta Verapaz";
			$dms = "Campur";
			$das = "Alta Verapaz";
			$location_lat = 15.56380798;
			$location_long = -90.13027784;
		}
		else if($message_elements[0]=="171307"){			
			$location_description = "Chisec C/S";
			$facilityType = "Centro de Salud";
			$municipality = "Chisec";
			$department = "Alta Verapaz";
			$dms = "Chisec";
			$das = "Alta Verapaz";
			$location_lat = 15.81277802;
			$location_long = -90.29316432;
		}
		else if($message_elements[0]=="171408"){			
			$location_description = "Coban C/S";
			$facilityType = "Centro de Salud";
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.4782715;
			$location_long = -90.37221338;
		}
		else if($message_elements[0]=="171409"){			
			$location_description = "Chitocan P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.64839723;
			$location_long = -90.41503693;
		}
		else if($message_elements[0]=="171413"){			
			$location_description = "Choval P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.54126377;
			$location_long = -90.37282672;
		}
		else if($message_elements[0]=="171412"){			
			$location_description = "Purribal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.79626294;
			$location_long = -90.72859163;
		}
		else if($message_elements[0]=="171411"){			
			$location_description = "Salacuin P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.8474214;
			$location_long = -90.71565122;
		}
		else if($message_elements[0]=="171414"){			
			$location_description = "Saxoc P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.57986623;
			$location_long = -90.38670149;
		}
		else if($message_elements[0]=="171410"){			
			$location_description = "Secocpur P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.71714901;
			$location_long = -90.42187901;
		}
		else if($message_elements[0]=="171433"){			
			$location_description = "Hospital Nacional Hellen Lossi de Laugerud";
			$facilityType = "Hospital"; //IS THIS RIGHT??!
			$municipality = "Coban";
			$department = "Alta Verapaz";
			$dms = "Coban";
			$das = "Alta Verapaz";
			$location_lat = 15.47864154;
			$location_long = -90.37248065;
		}
		else if($message_elements[0]=="170207"){			
			$location_description = "San Pedro Carcha C/S";
			$facilityType = "Centro de Salud";
			$municipality = "San Pedro Carcha";
			$department = "Alta Verapaz";
			$dms = "San Pedro Carcha";
			$das = "Alta Verapaz";
			$location_lat = 15.47829619;
			$location_long = -90.31531701;
		}
		else if($message_elements[0]=="170210"){			
			$location_description = "Caquigual P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Pedro Carcha";
			$department = "Alta Verapaz";
			$dms = "San Pedro Carcha";
			$das = "Alta Verapaz";
			$location_lat = 15.5630467;
			$location_long = -90.32004352;
		}
		else if($message_elements[0]=="170226"){			
			$location_description = "Chacalte P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Pedro Carcha";
			$department = "Alta Verapaz";
			$dms = "San Pedro Carcha";
			$das = "Alta Verapaz";
			$location_lat = 15.47152234;
			$location_long = -90.16217432;
		}
		else if($message_elements[0]=="170208"){			
			$location_description = "Pocola P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Pedro Carcha";
			$department = "Alta Verapaz";
			$dms = "San Pedro Carcha";
			$das = "Alta Verapaz";
			$location_lat = 15.54819649;
			$location_long = -90.24542417;
		}
		else if($message_elements[0]=="170208"){			
			$location_description = "Semesche P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Pedro Carcha";
			$department = "Alta Verapaz";
			$dms = "San Pedro Carcha";
			$das = "Alta Verapaz";
			$location_lat = 15.43998555;
			$location_long = -90.17642302;
		}
		else if($message_elements[0]=="131404"){			
			$location_description = "Cuilco CAIMI";
			$facilityType = "Centro de Atencion Integral Materno Infantil";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.40637972;
			$location_long = -91.94151268;
		}
		else if($message_elements[0]=="131410"){			
			$location_description = "Agua Dulce P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.52386567;
			$location_long = -92.02583177;
		}
		else if($message_elements[0]=="131440"){			
			$location_description = "Agua Sembrada P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.37949963;
			$location_long = -92.03120284;
		}
		else if($message_elements[0]=="131438"){			
			$location_description = "Canibal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131439"){			
			$location_description = "Cua P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131435"){			
			$location_description = "El Chilcal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131433"){			
			$location_description = "El Herrador P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131408"){			
			$location_description = "El Rodeo P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.45103756;
			$location_long = -92.05637855;
		}
		else if($message_elements[0]=="131409"){			
			$location_description = "El Zapotillo P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.47120655;
			$location_long = -92.0386779;
		}
		else if($message_elements[0]=="131407"){			
			$location_description = "Hierba Buena P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.42828312;
			$location_long = -92.03867773;
		}
		else if($message_elements[0]=="131442"){			
			$location_description = "La Laguna Frontera P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131441"){			
			$location_description = "Mojubal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131406"){			
			$location_description = "Posonicapa Chiquito P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.32488294;
			$location_long = -91.99431806;
		}
		else if($message_elements[0]=="131436"){			
			$location_description = "Toril Islam P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131434"){			
			$location_description = "Tuya P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131405"){			
			$location_description = "Vuelta Grande P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.36994983;
			$location_long = -91.99752268;
		}
		else if($message_elements[0]=="131437"){			
			$location_description = "Yulva P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Cuilco";
			$department = "Huehuetenango";
			$dms = "Cuilco";
			$das = "Huehuetenango";
			$location_lat = 15.43739377;
			$location_long = -91.98376617;
		}
		else if($message_elements[0]=="131203"){			
			$location_description = "San Idelfonso Ixtahuacan CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "San Idelfonso Ixtahuacan";
			$department = "Huehuetenango";
			$dms = "San Idelfonso Ixtahuacan";
			$das = "Huehuetenango";
			$location_lat = 15.41859054;
			$location_long = -91.76938394;
		}
		else if($message_elements[0]=="131204"){			
			$location_description = "Acal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Idelfonso Ixtahuacan";
			$department = "Huehuetenango";
			$dms = "San Idelfonso Ixtahuacan";
			$das = "Huehuetenango";
			$location_lat = 15.41126565;
			$location_long = -91.80894048;
		}
		else if($message_elements[0]=="131217"){			
			$location_description = "Chanchiquia P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Idelfonso Ixtahuacan";
			$department = "Huehuetenango";
			$dms = "San Idelfonso Ixtahuacan";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131218"){			
			$location_description = "Chiquilila P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Idelfonso Ixtahuacan";
			$department = "Huehuetenango";
			$dms = "San Idelfonso Ixtahuacan";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131219"){			
			$location_description = "El Papal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Idelfonso Ixtahuacan";
			$department = "Huehuetenango";
			$dms = "San Idelfonso Ixtahuacan";
			$das = "Huehuetenango";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="131701"){			
			$location_description = "Tectitan CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "Tectitan";
			$department = "Huehuetenango";
			$dms = "Tectitan";
			$das = "Huehuetenango";
			$location_lat = 15.30641503;
			$location_long = -92.06014268;
		}
		else if($message_elements[0]=="131707"){			
			$location_description = "Agua Caliente P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Tectitan";
			$department = "Huehuetenango";
			$dms = "Tectitan";
			$das = "Huehuetenango";
			$location_lat = 15.36685728;
			$location_long = -92.05643607;
		}
		else if($message_elements[0]=="131702"){			
			$location_description = "Chiste P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Tectitan";
			$department = "Huehuetenango";
			$dms = "Tectitan";
			$das = "Huehuetenango";
			$location_lat = 15.28542192;
			$location_long = -92.03578423;
		}
		else if($message_elements[0]=="131708"){			
			$location_description = "Toninquin P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Tectitan";
			$department = "Huehuetenango";
			$dms = "Tectitan";
			$das = "Huehuetenango";
			$location_lat = 15.28841434;
			$location_long = -92.07215177;
		}
		else if($message_elements[0]=="131709"){			
			$location_description = "Totanan P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Tectitan";
			$department = "Huehuetenango";
			$dms = "Tectitan";
			$das = "Huehuetenango";
			$location_lat = 15.29921201;
			$location_long = -92.0230072;
		}
		else if($message_elements[0]=="260208"){			
			$location_description = "Actxumbal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.44189137;
			$location_long = -91.1603386;
		}
		else if($message_elements[0]=="260221"){			
			$location_description = "Nueva America P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.66978459;
			$location_long = -91.1506389;
		}
		else if($message_elements[0]=="260222"){			
			$location_description = "Palop P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.51499782;
			$location_long = -91.29077176;
		}
		else if($message_elements[0]=="260207"){			
			$location_description = "Pulay P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.43149389;
			$location_long = -91.09642924;
		}
		else if($message_elements[0]=="260206"){			
			$location_description = "Rio Azul P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.43222059;
			$location_long = -91.11582513;
		}
		else if($message_elements[0]=="260211"){			
			$location_description = "Salquil Grande P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.48624081;
			$location_long = -91.25479836;
		}
		else if($message_elements[0]=="260209"){			
			$location_description = "San Juan Acul P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.40546951;
			$location_long = -91.18998458;
		}
		else if($message_elements[0]=="260218"){			
			$location_description = "Sumal Grande P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.54207639;
			$location_long = -91.18988511;
		}
		else if($message_elements[0]=="260219"){			
			$location_description = "Sumalito P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.53700649;
			$location_long = -91.12198874;
		}
		else if($message_elements[0]=="260220"){			
			$location_description = "Trapichitos P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.55656348;
			$location_long = -91.12447154;
		}
		else if($message_elements[0]=="260210"){			
			$location_description = "Tzalbal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.45154837;
			$location_long = -91.21032641;
		}
		else if($message_elements[0]=="260212"){			
			$location_description = "Vicalama P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.51178598;
			$location_long = -91.21994975;
		}
		else if($message_elements[0]=="260228"){			
			$location_description = "Hospital  Distrital Nebaj";
			$facilityType = "Hospital"; //IS THIS RIGHT??!
			$municipality = "Nebaj";
			$department = "Quiche";
			$dms = "Nebaj";
			$das = "Ixil";
			$location_lat = 15.40025241;
			$location_long = -91.1424751;
		}
		else if($message_elements[0]=="141811"){			
			$location_description = "La Parroquia CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="141809"){			
			$location_description = "La Taña CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.58091327;
			$location_long = -90.82918589;
		}
		else if($message_elements[0]=="141807"){			
			$location_description = "Caracolito P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.41804686;
			$location_long = -90.82581283;
		}
		else if($message_elements[0]=="141820"){			
			$location_description = "Chola P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.36590278;
			$location_long = -90.85664519;
		}
		else if($message_elements[0]=="141804"){			
			$location_description = "El Pinal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.40160989;
			$location_long = -90.77138873;
		}
		else if($message_elements[0]=="141818"){			
			$location_description = "San Ranch Chituj P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.39746613;
			$location_long = -90.87086729;
		}
		else if($message_elements[0]=="141819"){			
			$location_description = "Union 31 de Mayo P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.55711808;
			$location_long = -90.83143475;
		}
		else if($message_elements[0]=="141806"){			
			$location_description = "Caracol PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.41383539;
			$location_long = -90.81705769;
		}
		else if($message_elements[0]=="141803"){			
			$location_description = "El Palmar PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.28618965;
			$location_long = -90.80433838;
		}
		else if($message_elements[0]=="141805"){			
			$location_description = "Las Pacayas PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.42872616;
			$location_long = -90.76945576;
		}
		else if($message_elements[0]=="141808"){			
			$location_description = "Sicache PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.28652527;
			$location_long = -90.84986738;
		}
		else if($message_elements[0]=="141896"){			
			$location_description = "Hospital Distrital Uspantan";
			$facilityType = "Hospital"; //IS THIS RIGHT??!
			$municipality = "San Miguel Uspantan";
			$department = "Quiche";
			$dms = "Uspantan";
			$das = "Quiche";
			$location_lat = 15.34623796;
			$location_long = -90.87592961;
		}
		else if($message_elements[0]=="120248"){			
			$location_description = "Concepcion Tutuapa C/M";
			$facilityType = "C/M"; //FIND TRANSLATION!
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.23758222;
			$location_long = -91.84422785;
		}
		else if($message_elements[0]=="120216"){			
			$location_description = "Antiguo Tutuapa P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.29019344;
			$location_long = -91.80585418;
		}
		else if($message_elements[0]=="120215"){			
			$location_description = "Hispache P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.30833849;
			$location_long = -91.93409493;
		}
		else if($message_elements[0]=="120217"){			
			$location_description = "Sochel P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.32446052;
			$location_long = -91.78549157;
		}
		else if($message_elements[0]=="120218"){			
			$location_description = "Tuichuna P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.31084786;
			$location_long = -91.77981124;
		}
		else if($message_elements[0]=="120214"){			
			$location_description = "Tuizmo P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.26240109;
			$location_long = -91.95006022;
		}
		else if($message_elements[0]=="120228"){			
			$location_description = "Belajuyape U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="120223"){			
			$location_description = "Buena Vista U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.36370987;
			$location_long = -91.80699881;
		}
		else if($message_elements[0]=="120219"){			
			$location_description = "Chamul U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.35082211;
			$location_long = -91.77440086;
		}
		else if($message_elements[0]=="120220"){			
			$location_description = "El Remate U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.25056594;
			$location_long = -91.88605597;
		}
		else if($message_elements[0]=="120227"){			
			$location_description = "La Laguna U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.38687975;
			$location_long = -91.87514455;
		}
		else if($message_elements[0]=="120225"){			
			$location_description = "Lacandon U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.23884046;
			$location_long = -91.89859189;
		}
		else if($message_elements[0]=="120221"){			
			$location_description = "Los Encuentros U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.35988714;
			$location_long = -91.83313501;
		}
		else if($message_elements[0]=="120224"){			
			$location_description = "Sichivila U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.32193342;
			$location_long = -91.86903582;
		}
		else if($message_elements[0]=="120222"){			
			$location_description = "Tuijoj U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.27281896;
			$location_long = -91.87379998;
		}
		else if($message_elements[0]=="120229"){			
			$location_description = "Tuimuca U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="120226"){			
			$location_description = "Yamoj U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Concepcion Tutuapa";
			$department = "San Marcos";
			$dms = "Concepcion Tutuapa";
			$das = "San Marcos";
			$location_lat = 15.33377701;
			$location_long = -91.90835903;
		}
		else if($message_elements[0]=="122111"){			
			$location_description = "Cieneguillas U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.18426778;
			$location_long = -91.8946507;
		}
		else if($message_elements[0]=="122114"){			
			$location_description = "Las Manzanas U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.1579233;
			$location_long = -91.87733575;
		}
		else if($message_elements[0]=="122112"){			
			$location_description = "Choapequez U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.0890908;
			$location_long = -91.86506672;
		}
		else if($message_elements[0]=="122110"){			
			$location_description = "Nueva Esperanza U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.14472045;
			$location_long = -91.88474871;
		}
		else if($message_elements[0]=="122113"){			
			$location_description = "Once de Mayo U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.12550223;
			$location_long = -91.89683586;
		}
		else if($message_elements[0]=="122116"){			
			$location_description = "San Andres U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.13591672;
			$location_long = -91.95043461;
		}
		else if($message_elements[0]=="122107"){			
			$location_description = "Tuichan U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.07463691;
			$location_long = -91.85136375;
		}
		else if($message_elements[0]=="122108"){			
			$location_description = "Tuiladrillo U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.10193834;
			$location_long = -91.85402995;
		}
		else if($message_elements[0]=="122109"){			
			$location_description = "Tuiquinamble U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.12951612;
			$location_long = -91.92464144;
		}
		else if($message_elements[0]=="122115"){			
			$location_description = "Yuinima U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.13833748;
			$location_long = -91.98753701;
		}
		else if($message_elements[0]=="122118"){			
			$location_description = "Bexoncan UMS";
			$facilityType = "Unidad Minima de Salud";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.16236391;
			$location_long = -91.98606113;
		}
		else if($message_elements[0]=="122117"){			
			$location_description = "El Plan UMS";
			$facilityType = "Unidad Minima de Salud";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.15715508;
			$location_long = -91.88457057;
		}
		else if($message_elements[0]=="122103"){			
			$location_description = "Ixchiguan CAIMI";
			$facilityType = "Centro de Atencion Integral Materno Infantil";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.1595808;
			$location_long = -91.92645321;
		}
		else if($message_elements[0]=="122104"){			
			$location_description = "Buena Vista Choapequez P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.08350979;
			$location_long = -91.85627841;
		}
		else if($message_elements[0]=="122106"){			
			$location_description = "Calapte P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.16507679;
			$location_long = -91.89629345;
		}
		else if($message_elements[0]=="122105"){			
			$location_description = "San Antonio Ixchiguan P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Ixchiguan";
			$department = "San Marcos";
			$dms = "Ixchiguan";
			$das = "San Marcos";
			$location_lat = 15.13570835;
			$location_long = -91.90098021;
		}
		else if($message_elements[0]=="120902"){			
			$location_description = "Choanla P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 15.19056733;
			$location_long = -91.93059932;
		}
		else if($message_elements[0]=="120903"){			
			$location_description = "Pavolaj P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 15.20554524;
			$location_long = -91.93792157;
		}
		else if($message_elements[0]=="120904"){			
			$location_description = "San Fernando P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 15.23150414;
			$location_long = -91.94214987;
		}
		else if($message_elements[0]=="120910"){			
			$location_description = "Esquipulas U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="120908"){			
			$location_description = "La Union U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 15.2682293;
			$location_long = -91.99239204;
		}
		else if($message_elements[0]=="120906"){			
			$location_description = "Laguna Grande U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 15.25235298;
			$location_long = -91.97121555;
		}		
		else if($message_elements[0]=="120909"){			
			$location_description = "San Rafael Iguil U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}
		else if($message_elements[0]=="120907"){			
			$location_description = "Santa Cruz Buena Vista U/M";
			$facilityType = "Urgencias Médicas";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 15.22686227;
			$location_long = -91.9234244;
		}	
		else if($message_elements[0]=="120901"){			
			$location_description = "San Jose Ojetenam CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "San Jose Ojetenam";
			$department = "San Marcos";
			$dms = "San Jose Ojetenam";
			$das = "San Marcos";
			$location_lat = 15.2341229;
			$location_long = -91.97099343;
		}	
		else if($message_elements[0]=="70878"){			
			$location_description = "Guineales C/M";
			$facilityType = "C/M"; //FIND TRANSLATION
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "Guineales";
			$das = "Solola";
			$location_lat = 14.67229608;
			$location_long = -91.38462438;
		}	
		else if($message_elements[0]=="70836"){			
			$location_description = "Pacanal IB P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "Guineales";
			$das = "Solola";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}	
		else if($message_elements[0]=="70835"){			
			$location_description = "Pasaquijuyup P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "Guineales";
			$das = "Solola";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING!
		}	
		else if($message_elements[0]=="70603"){			
			$location_description = "Nahuala CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "Nahuala";
			$das = "Solola";
			$location_lat = 14.84042998;
			$location_long = -91.3157985; 
		}
		else if($message_elements[0]=="70604"){			
			$location_description = "Pachipac P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "Nahuala";
			$das = "Solola";
			$location_lat = 14.83570438;
			$location_long = -91.34163608; 
		}
		else if($message_elements[0]=="70301"){			
			$location_description = "Xejuyup CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "No.9 Xejuyup Boca Costa De Nahuala";
			$das = "Solola";
			$location_lat = 14.66257908;
			$location_long = -91.42608539; 
		}
		else if($message_elements[0]=="70304"){			
			$location_description = "Paculam P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "No.9 Xejuyup Boca Costa De Nahuala";
			$das = "Solola";
			$location_lat = 14.64852668;
			$location_long = -91.38187715; 
		}
		else if($message_elements[0]=="70303"){			
			$location_description = "Palacal P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Nahuala";
			$department = "Solola";
			$dms = "No.9 Xejuyup Boca Costa De Nahuala";
			$das = "Solola";
			$location_lat = 14.62857444;
			$location_long = -91.45746763; 
		}
		else if($message_elements[0]=="80501"){			
			$location_description = "Santa Lucia La Reforma CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "Santa Lucia La Reforma";
			$department = "Totonicapan";
			$dms = "Santa Lucia La Reforma";
			$das = "Totonicapan";
			$location_lat = 15.13031451;
			$location_long = -91.23747657; 
		}
		else if($message_elements[0]=="80504"){			
			$location_description = "Arroyo Sacasiguan PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Santa Lucia La Reforma";
			$department = "Totonicapan";
			$dms = "Santa Lucia La Reforma";
			$das = "Totonicapan";
			$location_lat = 15.19207855;
			$location_long = -91.24644472; 
		}
		else if($message_elements[0]=="80505"){			
			$location_description = "Pabaquit Sacasiguan PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Santa Lucia La Reforma";
			$department = "Totonicapan";
			$dms = "Santa Lucia La Reforma";
			$das = "Totonicapan";
			$location_lat = 15.1821438;
			$location_long = -91.27064894; 
		}
		else if($message_elements[0]=="80502"){			
			$location_description = "Pamaria PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Santa Lucia La Reforma";
			$department = "Totonicapan";
			$dms = "Santa Lucia La Reforma";
			$das = "Totonicapan";
			$location_lat = 15.17928575;
			$location_long = -91.31017245; 
		}
		else if($message_elements[0]=="80503"){			
			$location_description = "San Luis Sibila PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Santa Lucia La Reforma";
			$department = "Totonicapan";
			$dms = "Santa Lucia La Reforma";
			$das = "Totonicapan";
			$location_lat = 15.18810598;
			$location_long = -91.32540369; 
		}
		else if($message_elements[0]=="80611"){			
			$location_description = "Santa Maria Chiquimula CAP";
			$facilityType = "Centro de Atencion Permanente";
			$municipality = "Santa Maria Chiquimula";
			$department = "Totonicapan";
			$dms = "Santa Maria Chiquimula";
			$das = "Totonicapan";
			$location_lat = 15.02784764;
			$location_long = -91.33462298; 
		}
		else if($message_elements[0]=="80604"){			
			$location_description = "Chuicaca PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Santa Maria Chiquimula";
			$department = "Totonicapan";
			$dms = "Santa Maria Chiquimula";
			$das = "Totonicapan";
			$location_lat = 0; //MISSING!
			$location_long = 0; //MISSING! 
		}
		else if($message_elements[0]=="80602"){			
			$location_description = "El Rancho PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Santa Maria Chiquimula";
			$department = "Totonicapan";
			$dms = "Santa Maria Chiquimula";
			$das = "Totonicapan";
			$location_lat = 15.02023054;
			$location_long = -91.377036;
		}
		else if($message_elements[0]=="80603"){			
			$location_description = "Xecachelaj PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Santa Maria Chiquimula";
			$department = "Totonicapan";
			$dms = "Santa Maria Chiquimula";
			$das = "Totonicapan";
			$location_lat = 14.98051299;
			$location_long = -91.34405862;
		}
		else if($message_elements[0]=="80701"){			
			$location_description = "Totonicapan CENAPA";
			$facilityType = "Centro de Atencion Pacientes Ambulatorios";
			$municipality = "Totonicapan";
			$department = "Totonicapan";
			$dms = "Totonicapan";
			$das = "Totonicapan";
			$location_lat = 14.91358449;
			$location_long = -91.36681262;
		}
		else if($message_elements[0]=="80704"){			
			$location_description = "Barraneche P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Totonicapan";
			$department = "Totonicapan";
			$dms = "Totonicapan";
			$das = "Totonicapan";
			$location_lat = 14.8250274;
			$location_long = -91.22380611;
		}
		else if($message_elements[0]=="80705"){			
			$location_description = "Maczul P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Totonicapan";
			$department = "Totonicapan";
			$dms = "Totonicapan";
			$das = "Totonicapan";
			$location_lat = 14.94100917;
			$location_long = -91.21628448;
		}
		else if($message_elements[0]=="80723"){			
			$location_description = "Nimasac P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Totonicapan";
			$department = "Totonicapan";
			$dms = "Totonicapan";
			$das = "Totonicapan";
			$location_lat = 14.92452682;
			$location_long = -91.48229352;
		}
		else if($message_elements[0]=="80703"){			
			$location_description = "Panquix P/S";
			$facilityType = "Puesto de Salud";
			$municipality = "Totonicapan";
			$department = "Totonicapan";
			$dms = "Totonicapan";
			$das = "Totonicapan";
			$location_lat = 14.88482269;
			$location_long = -91.30857623;
		}
		else if($message_elements[0]=="80702"){			
			$location_description = "Chipuac PSF";
			$facilityType = "Puesto de Salud Fortalecido";
			$municipality = "Totonicapan";
			$department = "Totonicapan";
			$dms = "Totonicapan";
			$das = "Totonicapan";
			$location_lat = 14.86845922;
			$location_long = -91.37661863;
		}
		else if($message_elements[0]=="80781"){			
			$location_description = "Hospital Nacional Dr. Jose Felipe Flores";
			$facilityType = "Hospital"; //IS THIS RIGHT??!
			$municipality = "Totonicapan";
			$department = "Totonicapan";
			$dms = "Totonicapan";
			$das = "Totonicapan";
			$location_lat = 14.91408841;
			$location_long = -91.38060499;
		}else{
			$goodLocation=false;
		}
/*
		else{
			$locationError=TRUE;
			$location_description = "Error";
			$location_lat = 0;
			$location_long = 0;
			$elements_count=2; //make loop run only once.
		}
*/
		

		
		// 225 = á
		// 233 = é
		// 237 = í
		// 243 = ó
		// 250 = ú
		// 253 = ý
		for($i=1; $i<$elements_count; $i++){
		
		if($message_elements[$i]=="01"){
			$title = "Termómetro oral";
			$category = "7";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//old stuff (number codes)
		else if($message_elements[$i]=="02"){
			$title = "Lámpara cuello de ganso";
			$category = "8";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="03"){
			$title = "Estetoscopio";
			$category = "9";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="04"){
			$title = "Esfigmomanómetro";
			$category = "10";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="05"){
			$title = "Recipiente de plástico de 30ml (Multistix)";
			$category = "11";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}				
		else if($message_elements[$i]=="06"){
			$title = "Cinta de castilla";
			$category = "12";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="07"){
			$title = "Guantes descartables";
			$category = "13";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}						
		else if($message_elements[$i]=="08"){
			$title = "Equipo de papanicolau";
			$category = "15";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="09"){
			$title = "Espéculos";
			$category = "16";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}				
		else if($message_elements[$i]=="10"){
			$title = "Algodón";
			$category = "17";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="11"){
			$title = "Gasas";
			$category = "18";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="12"){
			$title = "Esparadrapo microporoso (Micropore)";
			$category = "19";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
		else if($message_elements[$i]=="13"){
			$title = "Hoja para bisturi";
			$category = "20";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
		else if($message_elements[$i]=="14"){
			$title = "Jeringas descartables";
			$category = "21";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
		else if($message_elements[$i]=="15"){
			$title = "Equipo de venoclisis";
			$category = "22";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
		else if($message_elements[$i]=="16"){
			$title = "Férula para fracturas";
			$category = "23";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
		else if($message_elements[$i]=="17"){
			$title = "Problemas con personales";
			$category = "24";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
		else if($message_elements[$i]=="18"){
			$title = "Servicio de agua todo el tiempo";
			$category = "25";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
		else if($message_elements[$i]=="19"){
			$title = "Energia eléctrica todo el tiempo";
			$category = "26";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="20"){
			$title = "Medicina para la fiebre adultos";
			$category = "27";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="21"){
			$title = "Medicina para la fiebre niños";
			$category = "28";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="22"){
			$title = "Antibióticos para pulmonía adultos";
			$category = "29";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="23"){
			$title = "Antibióticos para pulmonía niños";
			$category = "30";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="24"){
			$title = "Acido fólico para embarazadas";
			$category = "31";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="25"){
			$title = "Hierro para niños";
			$category = "32";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="26"){
			$title = "Desparasitantes para niños";
			$category = "33";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="27"){
			$title = "Medicina para amebas";
			$category = "34";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="28"){
			$title = "Antibiótico para diarreas";
			$category = "35";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="29"){
			$title = "Sueros orales para deshidratación";
			$category = "36";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="30"){
			$title = "Medicina para la gastritis";
			$category = "37";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="31"){
			$title = "Medicina para la conjuntivitis";
			$category = "38";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}		
		else if($message_elements[$i]=="32"){
			$title = "Soluciones intravenosas para deshidratación";
			$category = "39";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}		
		else if($message_elements[$i]=="33"){
			$title = "Anestesia local";
			$category = "40";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}		
		else if($message_elements[$i]=="34"){
			$title = "Medicinas para el asma y alergia pulmonar para niños";
			$category = "41";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}		
		
		/*CAIMI*/
		//EQUIPMENT
		//Laboratorio
		else if($message_elements[$i]=="E11" || $message_elements[$i]=="*E11"){
			$title = "Depósito de sangre";
			$category = "43";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E12" || $message_elements[$i]=="*E12"){
			$title = "Equipo de rayos x";
			$category = "44";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E13" || $message_elements[$i]=="*E13"){
			$title = "Microscopio";
			$category = "45";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E14" || $message_elements[$i]=="*E14"){
			$title = "Insumos de laboratorio";
			$category = "46";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E15" || $message_elements[$i]=="*E15"){
			$title = "Equipo análisis de calidad de agua";
			$category = "47";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Cirugia
		else if($message_elements[$i]=="E21" || $message_elements[$i]=="*E21"){
			$title = "Equipo e insumos para cesarea (completo)";
			$category = "48";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E22" || $message_elements[$i]=="*E22"){
			$title = "Equipo de anesthesia";
			$category = "49";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E23" || $message_elements[$i]=="*E23"){
			$title = "Equipos de cirugía menor";
			$category = "51";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E24" || $message_elements[$i]=="*E24"){
			$title = "Equipos de sutura";
			$category = "50";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E25" || $message_elements[$i]=="*E25"){
			$title = "Hilos de sutura";
			$category = "85";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}		
		else if($message_elements[$i]=="E26" || $message_elements[$i]=="*E26"){
			$title = "Pinzas de anillos";
			$category = "78";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E27" || $message_elements[$i]=="*E27"){
			$title = "Bandejas";
			$category = "79";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E28" || $message_elements[$i]=="*E28"){
			$title = "Tijeras";
			$category = "80";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E29" || $message_elements[$i]=="*E29"){
			$title = "Hoja para bisturi";
			$category = "92";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Traumas
		else if($message_elements[$i]=="E31" || $message_elements[$i]=="*E31"){
			$title = "Vendas de yeso";
			$category = "54";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E32" || $message_elements[$i]=="*E32"){
			$title = "Ferula fracturas";
			$category = "86";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Urgencias
		else if($message_elements[$i]=="E41" || $message_elements[$i]=="*E41"){
			$title = "Equipo de resucitación (AMBU y mascarilla)";
			$category = "55";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E42" || $message_elements[$i]=="*E42"){
			$title = "Oxígeno incluido cilindro, humidificador y manómetro";
			$category = "56";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Maternidad
		else if($message_elements[$i]=="E51" || $message_elements[$i]=="*E51"){
			$title = "Equipos para parto";
			$category = "58";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E52" || $message_elements[$i]=="*E52"){
			$title = "Equipo papanicolau";
			$category = "15";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E53" || $message_elements[$i]=="*E53"){
			$title = "Cinta de Castilla o clamps";
			$category = "95";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E54" || $message_elements[$i]=="*E54"){
			$title = "Espéculos";
			$category = "91";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Asistencia
		else if($message_elements[$i]=="E61" || $message_elements[$i]=="*E61"){
			$title = "Algodón";
			$category = "17";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E62" || $message_elements[$i]=="*E62"){
			$title = "Angiokath";
			$category = "59";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E63" || $message_elements[$i]=="*E63"){
			$title = "Guantes estériles";
			$category = "60";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E64" || $message_elements[$i]=="*E64"){
			$title = "Guantes descartables";
			$category = "83";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E65" || $message_elements[$i]=="*E65"){
			$title = "Jeringas descartables";
			$category = "84";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E66" || $message_elements[$i]=="*E66"){
			$title = "Micropore";
			$category = "19";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E67" || $message_elements[$i]=="*E67"){
			$title = "Equipo de Venoclisis";
			$category = "81";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E68" || $message_elements[$i]=="*E68"){
			$title = "Gasas";
			$category = "82";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Examen
		else if($message_elements[$i]=="E71" || $message_elements[$i]=="*E71"){
			$title = "Estetoscopios para adultos";
			$category = "9";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E72" || $message_elements[$i]=="*E72"){
			$title = "Esfigmomanómetros adultos";
			$category = "73";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E73" || $message_elements[$i]=="*E73"){
			$title = "Lámparas cuello de ganso";
			$category = "8";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E74" || $message_elements[$i]=="*E74"){
			$title = "Termómetro oral";
			$category = "75";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="E75" || $message_elements[$i]=="*E75"){
			$title = "Termómetro rectal";
			$category = "76";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		

		//NEW EQUIPMENT (NO CODES)
		




	/*	else if($message_elements[$i]==""){
			$title = "Estetoscopio";
			$category = "87";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		} 
		else if($message_elements[$i]==""){
			$title = "Esfigmomanómetro ";
			$category = "88";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		} 
		else if($message_elements[$i]==""){
			$title = "Recipiente de plástico de 30ml (Multistix)";
			$category = "89";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		} */



		//MEDICINE
		//Fiebre
		else if($message_elements[$i]=="M11" || $message_elements[$i]=="*M11"){
			$title = "Medicina para la fiebre adultos";
			$category = "27";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M12" || $message_elements[$i]=="*M12"){
			$title = "Medicina para la fiebre niños";
			$category = "28";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Nutricion
		else if($message_elements[$i]=="M21" || $message_elements[$i]=="*M21"){
			$title = "Hierro para niños";
			$category = "32";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M22" || $message_elements[$i]=="*M22"){
			$title = "Vitaminas para niños";
			$category = "62";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Ojos
		else if($message_elements[$i]=="M31" || $message_elements[$i]=="*M31"){
			$title = "Medicina para la conjuntivitis";
			$category = "38";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Cirugias
		else if($message_elements[$i]=="M41" || $message_elements[$i]=="*M41"){
			$title = "Anestesia local";
			$category = "40";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M42" || $message_elements[$i]=="*M42"){
			$title = "Anestesia para operaciones";
			$category = "63";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M43" || $message_elements[$i]=="*M43"){
			$title = "Desinfectante para equipo";
			$category = "64";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Gastrointestinales
		else if($message_elements[$i]=="M51" || $message_elements[$i]=="*M51"){
			$title = "Desparasitantes para niños";
			$category = "33";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M52" || $message_elements[$i]=="*M52"){
			$title = "Medicina para amebas";
			$category = "34";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M53" || $message_elements[$i]=="*M53"){
			$title = "Antibiótico para diarreas";
			$category = "35";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M54" || $message_elements[$i]=="*M54"){
			$title = "Medicina para la gastritis";
			$category = "37";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M55" || $message_elements[$i]=="*M55"){
			$title = "Sueros orales para deshidratación";
			$category = "36";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M56" || $message_elements[$i]=="*M56"){
			$title = "Soluciones intravenosas para deshidratación";
			$category = "39";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}		
		//Pulmonares
		else if($message_elements[$i]=="M61" || $message_elements[$i]=="*M61"){
			$title = "Antibióticos para pulmonía adultos";
			$category = "29";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M62" || $message_elements[$i]=="*M62"){
			$title = "Antibióticos para pulmonía niños";
			$category = "30";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M63" || $message_elements[$i]=="*M63"){
			$title = "Medicinas para el asma para niños";
			$category = "41";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}	
	    else if($message_elements[$i]=="M64" || $message_elements[$i]=="*M64"){
			$title = "Medicinas para el asma y alergia pulmonar para niños";
			$category = "94";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Maternidad
		else if($message_elements[$i]=="M71" || $message_elements[$i]=="*M71"){
			$title = "Oxitocina para acelerar el parto";
			$category = "65";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M72" || $message_elements[$i]=="*M72"){
			$title = "Magnesio sulfato para la presión en el embarazo";
			$category = "66";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M73" || $message_elements[$i]=="*M73"){
			$title = "Hierro para embarazadas";
			$category = "67";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M74" || $message_elements[$i]=="*M74"){
			$title = "Acido fólico para embarazadas";
			$category = "31";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		//Trauma
		else if($message_elements[$i]=="M81" || $message_elements[$i]=="*M81"){
			$title = "Relajante muscular";
			$category = "68";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M82" || $message_elements[$i]=="*M82"){
			$title = "Medicina para el dolor para adultos";
			$category = "69";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}
		else if($message_elements[$i]=="M83" || $message_elements[$i]=="*M83"){
			$title = "Medicina para el dolor niños";
			$category = "70";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
		}												
		else{
/*
			$title = "You have not entered a title for this code yet";
			$category = "5";
			$incident_description=$location_description." (".$facilityType.") ".Kohana::lang('smsautomate_ui.incident_description')." ".$title;
*/
			$badCode = true;
			array_push($badCodes, $message_elements[$i]);
			continue;
		}
		//}// goodFormat
		
/*
	    if($locationError==TRUE){
			$title = "Location Error";
			$category = "96";
			$incident_description="The location code used does not exist";
		}
*/
		// STEP 1: SAVE LOCATION
		if($goodFormat && $goodLocation){
		if(substr($message_elements[$i], 0,1)!="*"){
		$categories = array(1);
		$location = new Location_Model();
		$location->location_name = $location_description;
		$location->latitude = $location_lat;//15.38;
		$location->longitude = $location_long;//-90.92;
		$location->location_date = $message_date;
		$location->save();
			
		//STEP 2: Save the incident
		$incident = new Incident_Model();
		$incident->location_id = $location->id;
		$incident->user_id = 0;
		$incident->incident_title = $title;  
		$incident->incident_description = $incident_description;
		$incident->incident_date = $message_date;  
		$incident->incident_dateadd = $message_date;
		$incident->incident_mode = 2;
		// Incident Evaluation Info
		$incident->incident_active = 1;
		$incident->incident_verified = 1;
		//Save
		$incident->save();
		error_log($incident->id);
		
		if($actionableExists){
	
		// STEP 3: SAVE REPORTS AS ACTIONABLE
			$SaveActionable = new Actionable_Model();
			$SaveActionable-> incident_id = $incident->id;
			$SaveActionable-> actionable = 1;
			//save
			$SaveActionable->save();
		}
		
		// STEP 4: SAVE CUSTOM FIELDS
		//facilityType
		$saveFacilityType = new Form_Response_Model();
		$saveFacilityType->incident_id = $incident->id;
		$saveFacilityType->form_field_id=8; 
		$saveFacilityType->form_response=$facilityType;
		$saveFacilityType->save();
		
		//facilityName
		$saveFacilityName = new Form_Response_Model();
		$saveFacilityName->incident_id = $incident->id;
		$saveFacilityName->form_field_id=12; 
		$saveFacilityName->form_response=$location_description;
		$saveFacilityName->save();
		
		//Municipality Name
		$saveMunicipality = new Form_Response_Model();
		$saveMunicipality->incident_id = $incident->id;
		$saveMunicipality->form_field_id=7;
		$saveMunicipality->form_response=$municipality;
		$saveMunicipality->save();
		
		//Department Name
		$saveDepartment = new Form_Response_Model();
		$saveDepartment->incident_id = $incident->id;
		$saveDepartment->form_field_id=3;
		$saveDepartment->form_response=$department;
		$saveDepartment->save();
		
		//DMS
		$saveDMS = new Form_Response_Model();
		$saveDMS->incident_id = $incident->id;
		$saveDMS->form_field_id=10;
		$saveDMS->form_response=$dms;
		$saveDMS->save();
		
		//DAS
		$saveDAS = new Form_Response_Model();
		$saveDAS->incident_id = $incident->id;
		$saveDAS->form_field_id=11;
		$saveDAS->form_response=$das;
		$saveDAS->save();
		
		//STEP 5: Record Approval
		$verify = new Verify_Model();
		$verify->incident_id = $incident->id;
		$verify->user_id = 0;
		$verify->verified_date = date("Y-m-d H:i:s",time());
		if ($incident->incident_active == 1)
		{
			$verify->verified_status = '1';
		}
		elseif ($incident->incident_verified == 1)
		{
			$verify->verified_status = '2';
		}
		elseif ($incident->incident_active == 1 && $incident->incident_verified == 1)
		{
			$verify->verified_status = '3';
		}
		else
		{
			$verify->verified_status = '0';
		}
		$verify->save();
		
		//STEP 6: SAVE "FROM" INFORMATION
		$person = new Incident_Person_Model();
		$person->incident_id = $incident->id;
		$person->person_first = $from;
		$person->person_date = date("Y-m-d H:i:s",time());
		$person->save();		

		
		// STEP 7: SAVE CATEGORIES
		ORM::factory('Incident_Category')->where('incident_id',$incident->id)->delete_all();		// Delete Previous Entries

			if(is_numeric($category))
			{
				$incident_category = new Incident_Category_Model();
				$incident_category->incident_id = $incident->id;
				$incident_category->category_id = $category;
				$incident_category->save();
			}
			
			Event::run('ushahidi_action.report_add', $incident);
		

		//don't forget to set incident_id in the message
		//Event::$data->incident_id = $incident->id;
		//Event::$data->save();
		//sleep(1);
		// STEP 8: SAVE MESSAGE ID
		$saveMessageID = new Incident_Message_Model();
		$saveMessageID->incident_id = $incident->id;
		$saveMessageID->message_id = $message_id;
		$saveMessageID->save(); 
		
		
		}// end loop to check for asterisk
		
		if(substr($message_elements[$i], 0,1)=="*" && $actionableExists){ //only run if actionable is being used. 
		
		
			//search database for report with location title and (report title or category number)
			//get the incident ID
			//edit the actionable database to say action as been taken

			$query = ORM::factory('incident')
				->join('location', 'incident.location_id', 'location.id')
				->where('location.location_name', $location_description)
				->where('incident.incident_title', $title)
				->find_all();

			foreach($query as $row){
		    	$loadActionable =ORM::factory('actionable')
				->where('incident_id',$row->id)
				->find();
				$loadActionable->action_taken=1;
				$loadActionable->action_date=date("Y-m-d H:i:s",time());
				$loadActionable->save();
			}

		}//end change actionable loop.

				
				
			
			} // badCode
		} // for loop for items
		

		if(isset($help)){
			sms::send($from, $sms_from, Kohana::lang('smsautomate_ui.help_message'));
		}else if($goodFormat && $goodLocation && !$badCode){
			sms::send($from, $sms_from, Kohana::lang('smsautomate_ui.report_submitted'));
		}else if(!$goodFormat && $goodLocation){
			sms::send($from, $sms_from, Kohana::lang('smsautomate_ui.invalid_format'));
		}else if(!$goodLocation && $goodFormat){
			sms::send($from, $sms_from, Kohana::lang('smsautomate_ui.bad_location'));
		}else if(!$goodFormat && !$goodLocation){
			sms::send($from, $sms_from, Kohana::lang('smsautomate_ui.invalid_format'));
		}else if($goodFormat && $goodLocation && $badCode && isset($title)){
			$codeList = "".Kohana::lang('smsautomate_ui.bad_item');
			$codeCount = count($badCodes);
			for($b = 0; $b<$codeCount;$b++){
				$codeList = $codeList.$badCodes[$b]." ";
			}
			sms::send($from, $sms_from, Kohana::lang('smsautomate_ui.report_submitted')." ".$codeList);
		}else if($goodFormat && $goodLocation && $badCode && !isset($title)){
			$codeList = "".Kohana::lang('smsautomate_ui.bad_item');
			$codeCount = count($badCodes);
			for($b = 0; $b<$codeCount;$b++){
				$codeList = $codeList.$badCodes[$b]." ";
			}
			sms::send($from, $sms_from, $codeList);
		}

			
 

	} // _parseSMS
	

} // smsautomate


new smsautomate;