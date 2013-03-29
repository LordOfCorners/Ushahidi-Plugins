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
		//the message
		$message = Event::$data->message;
		$from = Event::$data->message_from;
		$reporterId = Event::$data->reporter_id;
		$message_date = Event::$data->message_date;


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
		$message_elements = explode(" ", $message);
		
		//echo Kohana::debug($message_elements);
		
		//check if the message properly exploded
		$elements_count = count($message_elements);
		
		//convert strings to uppercase
		for($j=0;$j<$elements_count;$j++){
			$message_elements[$j]= strtoupper($message_elements[$j]);
		}
		
		if($message_elements[0]=="170404"){			
			$location_description = "Campur";
			$municipality = "San Pedro Carcha";
			$location_lat = 15.63346211;
			$location_long = -90.04852005;
		}

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
			$incident_description="This is a default description";
		}
		//old stuff (number codes)
		else if($message_elements[$i]=="02"){
			$title = "Lámpara cuello de ganso";
			$category = "8";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="03"){
			$title = "Estetoscopio";
			$category = "9";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="04"){
			$title = "Esfigmomanómetro";
			$category = "10";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="05"){
			$title = "Recipiente de plástico de 30ml (Multistix)";
			$category = "11";
			$incident_description="This is a default description";
		}				
		else if($message_elements[$i]=="06"){
			$title = "Cinta de castilla";
			$category = "12";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="07"){
			$title = "Guantes descartables";
			$category = "13";
			$incident_description="This is a default description";
		}						
		else if($message_elements[$i]=="08"){
			$title = "Equipo de papanicolau";
			$category = "15";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="09"){
			$title = "Espéculos";
			$category = "16";
			$incident_description="This is a default description";
		}				
		else if($message_elements[$i]=="10"){
			$title = "Algodón";
			$category = "17";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="11"){
			$title = "Gasas";
			$category = "18";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="12"){
			$title = "Esparadrapo microporoso (Micropore)";
			$category = "19";
			$incident_description="This is a default description";
		}	
		else if($message_elements[$i]=="13"){
			$title = "Hoja para bisturi";
			$category = "20";
			$incident_description="This is a default description";
		}	
		else if($message_elements[$i]=="14"){
			$title = "Jeringas descartables";
			$category = "21";
			$incident_description="This is a default description";
		}	
		else if($message_elements[$i]=="15"){
			$title = "Equipo de venoclisis";
			$category = "22";
			$incident_description="This is a default description";
		}	
		else if($message_elements[$i]=="16"){
			$title = "Férula para fracturas";
			$category = "23";
			$incident_description="This is a default description";
		}	
		else if($message_elements[$i]=="17"){
			$title = "Problemas con personales";
			$category = "24";
			$incident_description="This is a default description";
		}	
		else if($message_elements[$i]=="18"){
			$title = "Servicio de agua todo el tiempo";
			$category = "25";
			$incident_description="This is a default description";
		}	
		else if($message_elements[$i]=="19"){
			$title = "Energia eléctrica todo el tiempo";
			$category = "26";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="20"){
			$title = "Medicina para la fiebre adultos";
			$category = "27";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="21"){
			$title = "Medicina para la fiebre niños";
			$category = "28";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="22"){
			$title = "Antibióticos para pulmonía adultos";
			$category = "29";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="23"){
			$title = "Antibióticos para pulmonía niños";
			$category = "30";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="24"){
			$title = "Acido fólico para embarazadas";
			$category = "31";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="25"){
			$title = "Hierro para niños";
			$category = "32";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="26"){
			$title = "Desparasitantes para niños";
			$category = "33";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="27"){
			$title = "Medicina para amebas";
			$category = "34";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="28"){
			$title = "Antibiótico para diarreas";
			$category = "35";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="29"){
			$title = "Sueros orales para deshidratación";
			$category = "36";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="30"){
			$title = "Medicina para la gastritis";
			$category = "37";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="31"){
			$title = "Medicina para la conjuntivitis";
			$category = "38";
			$incident_description="This is a default description";
		}		
		else if($message_elements[$i]=="32"){
			$title = "Soluciones intravenosas para deshidratación";
			$category = "39";
			$incident_description="This is a default description";
		}		
		else if($message_elements[$i]=="33"){
			$title = "Anestesia local";
			$category = "40";
			$incident_description="This is a default description";
		}		
		else if($message_elements[$i]=="34"){
			$title = "Medicinas para el asma y alergia pulmonar para niños";
			$category = "41";
			$incident_description="This is a default description";
		}		
		
		/*CAIMI*/
		//EQUIPMENT
		//Laboratorio
		else if($message_elements[$i]=="E11"){
			$title = "Depósito de sangre";
			$category = "43";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E12"){
			$title = "Equipo de rayos x";
			$category = "44";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E13"){
			$title = "Microscopio";
			$category = "45";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E14"){
			$title = "Insumos de laboratorio";
			$category = "46";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E15"){
			$title = "Equipo análisis de calidad de agua";
			$category = "47";
			$incident_description="This is a default description";
		}
		//Cirugia
		else if($message_elements[$i]=="E21"){
			$title = "Equipo e insumos para cesarea (completo)";
			$category = "48";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E22"){
			$title = "Equipo de anesthesia";
			$category = "49";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E23"){
			$title = "Equipos de cirugía menor";
			$category = "51";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E24"){
			$title = "Equipos de sutura";
			$category = "50";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E25"){
			$title = "Hilos de sutura";
			$category = "85";
			$incident_description="This is a default description";
		}		
		else if($message_elements[$i]=="E26"){
			$title = "Pinzas de anillos";
			$category = "78";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E27"){
			$title = "Bandejas";
			$category = "79";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E28"){
			$title = "Tijeras";
			$category = "80";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E29"){
			$title = "Hoja para bisturi";
			$category = "92";
			$incident_description="This is a default description";
		}
		//Traumas
		else if($message_elements[$i]=="E31"){
			$title = "Vendas de yeso";
			$category = "54";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E32"){
			$title = "Ferula fracturas";
			$category = "86";
			$incident_description="This is a default description";
		}
		//Urgencias
		else if($message_elements[$i]=="E41"){
			$title = "Equipo de resucitación (AMBU y mascarilla)";
			$category = "55";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E42"){
			$title = "Oxígeno incluido cilindro, humidificador y manómetro";
			$category = "56";
			$incident_description="This is a default description";
		}
		//Maternidad
		else if($message_elements[$i]=="E51"){
			$title = "Equipos para parto";
			$category = "58";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E52"){
			$title = "Equipo papanicolau";
			$category = "15";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E53"){
			$title = "Cinta de Castilla o clamps";
			$category = "95";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E54"){
			$title = "Espéculos";
			$category = "91";
			$incident_description="This is a default description";
		}
		//Asistencia
		else if($message_elements[$i]=="E61"){
			$title = "Algodón";
			$category = "17";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E62"){
			$title = "Angiokath";
			$category = "59";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E63"){
			$title = "Guantes estériles";
			$category = "60";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E64"){
			$title = "Guantes descartables";
			$category = "83";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E65"){
			$title = "Jeringas descartables";
			$category = "84";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E66"){
			$title = "Micropore";
			$category = "19";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E67"){
			$title = "Equipo de Venoclisis";
			$category = "81";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E68"){
			$title = "Gasas";
			$category = "82";
			$incident_description="This is a default description";
		}
		//Examen
		else if($message_elements[$i]=="E71"){
			$title = "Estetoscopios para adultos";
			$category = "9";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E72"){
			$title = "Esfigmomanómetros adultos";
			$category = "73";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E73"){
			$title = "Lámparas cuello de ganso";
			$category = "8";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E74"){
			$title = "Termómetro oral";
			$category = "75";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="E75"){
			$title = "Termómetro rectal";
			$category = "76";
			$incident_description="This is a default description";
		}

		//NEW EQUIPMENT (NO CODES)
		




	/*	else if($message_elements[$i]==""){
			$title = "Estetoscopio";
			$category = "87";
			$incident_description="This is a default description";
		} 
		else if($message_elements[$i]==""){
			$title = "Esfigmomanómetro ";
			$category = "88";
			$incident_description="This is a default description";
		} 
		else if($message_elements[$i]==""){
			$title = "Recipiente de plástico de 30ml (Multistix)";
			$category = "89";
			$incident_description="This is a default description";
		} */



		//MEDICINE
		//Fiebre
		else if($message_elements[$i]=="M11"){
			$title = "Medicina para la fiebre adultos";
			$category = "27";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M12"){
			$title = "Medicina para la fiebre niños";
			$category = "28";
			$incident_description="This is a default description";
		}
		//Nutricion
		else if($message_elements[$i]=="M21"){
			$title = "Hierro para niños";
			$category = "32";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M22"){
			$title = "Vitaminas para niños";
			$category = "62";
			$incident_description="This is a default description";
		}
		//Ojos
		else if($message_elements[$i]=="M31"){
			$title = "Medicina para la conjuntivitis";
			$category = "38";
			$incident_description="This is a default description";
		}
		//Cirugias
		else if($message_elements[$i]=="M41"){
			$title = "Anestesia local";
			$category = "40";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M42"){
			$title = "Anestesia para operaciones";
			$category = "63";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M43"){
			$title = "Desinfectante para equipo";
			$category = "64";
			$incident_description="This is a default description";
		}
		//Gastrointestinales
		else if($message_elements[$i]=="M51"){
			$title = "Desparasitantes para niños";
			$category = "33";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M52"){
			$title = "Medicina para amebas";
			$category = "34";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M61"){
			$title = "Antibiótico para diarreas";
			$category = "35";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M62"){
			$title = "Medicina para la gastritis";
			$category = "37";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M63"){
			$title = "Sueros orales para deshidratación";
			$category = "36";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M64"){
			$title = "Soluciones intravenosas para deshidratación";
			$category = "39";
			$incident_description="This is a default description";
		}		
		//Pulmonares
		else if($message_elements[$i]=="M71"){
			$title = "Antibióticos para pulmonía adultos";
			$category = "29";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M72"){
			$title = "Antibióticos para pulmonía niños";
			$category = "30";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M73"){
			$title = "Medicinas para el asma para niños";
			$category = "41";
			$incident_description="This is a default description";
		}	
	    else if($message_elements[$i]=="M74"){
			$title = "Medicinas para el asma y alergia pulmonar para niños";
			$category = "94";
			$incident_description="This is a default description";
		}
		//Maternidad
		else if($message_elements[$i]=="M81"){
			$title = "Oxitocina para acelerar el parto";
			$category = "65";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M82"){
			$title = "Magnesio sulfato para la presión en el embarazo";
			$category = "66";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M83"){
			$title = "Hierro para embarazadas";
			$category = "67";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M84"){
			$title = "Acido fólico para embarazadas";
			$category = "31";
			$incident_description="This is a default description";
		}
		//Trauma
		else if($message_elements[$i]=="M91"){
			$title = "Relajante muscular";
			$category = "68";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M92"){
			$title = "Medicina para el dolor para adultos";
			$category = "69";
			$incident_description="This is a default description";
		}
		else if($message_elements[$i]=="M93"){
			$title = "Medicina para el dolor niños";
			$category = "70";
			$incident_description="This is a default description";
		}												
		else{
			$title = "You have not entered a title for this code yet";
			$category = "5";
			$incident_description="This is a default description";
		}
		
		// STEP 1: SAVE LOCATION
		$categories = array(1);
		$location = new Location_Model();
		$location->location_name = $location_description;
		$location->latitude = $location_lat;//15.38;
		$location->longitude = $location_long;//-90.92;
		$location->location_date = $message_date;
		$location->save();
		
		// STEP 2: SAVE CUSTOM FIELDS
		$saveMunicipality = new Form_Response_Model();
		$saveMunicipality->incident_id = $location->id;
		$saveMunicipality->form_field_id=1;
		$saveMunicipality->form_response=$municipality;
		$saveMunicipality->save();
				
		//STEP 3: Save the incident
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
		//STEP 4: Record Approval
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
		
		
		// STEP 3: SAVE CATEGORIES
		ORM::factory('Incident_Category')->where('incident_id',$incident->id)->delete_all();		// Delete Previous Entries

			if(is_numeric($category))
			{
				$incident_category = new Incident_Category_Model();
				$incident_category->incident_id = $incident->id;
				$incident_category->category_id = $category;
				$incident_category->save();
			}
		

		//don't forget to set incident_id in the message
		//Event::$data->incident_id = $incident->id;
		//Event::$data->save();
		//sleep(1);
		}


	}
	

}


new smsautomate;