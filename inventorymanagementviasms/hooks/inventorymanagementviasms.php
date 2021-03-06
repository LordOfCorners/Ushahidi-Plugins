<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Inventory Management via SMS Hook - Load All Events
 *
 * @author	   Open Health Networks
 * @package	   Inventory Management via SMS
 *
 * Many thanks to John Etherton for his SMSautomate plugin, which was a great help and provided a starting point.
 */

 /*test*/

class inventorymanagementviasms {

	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{

		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));

		$this->settings = ORM::factory('inventorymanagementviasms')
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
		$custom_forms = customforms::get_custom_form_fields();

		//Check to see if Actionable is being used
		$result = ORM::factory('plugin') ->where('plugin_name','actionable')->find();
		if($result->plugin_active == 1 && $result->plugin-installed == 1){
			$actionableExists = true;
		}else{
			$actionableExists = false;
		}

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
		$default_message_text = $this->settings->default_message;
		$auto_approve = $this->settings->auto_approve;

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
			if(strtolower($message_elements[0])=="help" || strtolower($message_elements[0])=="ayuda"){ //should localize
				$help=true;
			}
		}

		//convert strings to uppercase
		for($j=0;$j<$elements_count;$j++){
			$message_elements[$j]= strtoupper($message_elements[$j]);
		}
//if($goodFormat){

		$locations = ORM::factory('inventory_locations')->where('location_code',$message_elements[0])->find();
		if($locations->loaded){
			$location_description=$locations->location_description;
			$location_lat=$locations->latitude;
			$location_long=$locations->longitude;
		}else{
			$goodLocation=false;
			$location_description="";
		}

		for($i=1; $i<$elements_count; $i++){
			if(substr($message_elements[$i], 0,1)=="*"){
				$tempMessageElement = substr($message_elements[$i], 1);
			}else{
				$tempMessageElement = $message_elements[$i];
			}
			$items = ORM::factory('inventory_items')->where('item_code',$tempMessageElement)->find();
			if($items->loaded){
				$title = $items->item_description;
				$category = $items->item_category;
				if ($default_message_text != ""){
					$incident_description = $default_message_text;
				}else {
					$incident_description=$location_description." ".Kohana::lang('inventorymanagementviasms_ui.incident_description')." ".$title;
				}

			}
			else{
				$badCode = true;
				array_push($badCodes, $message_elements[$i]);
				continue;
			}

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
		$incident->incident_alert_status = 1; //Tag incident for sending alerts

		// Incident Evaluation Info

		//don't approve messages from senders marked as spam
		if ($auto_approve == 0) {
			$incident->incident_active = 0;
			$incident->incident_verified = 0;
		}else if ($auto_approve == 1) {
			$reporter = ORM::factory('reporter')->where('service_account',$from)->find();
			if($reporter->level_id==2){
				$incident->incident_active = 0;
				$incident->incident_verified = 0;
			}else{
				$incident->incident_active = 1;
				$incident->incident_verified = 1;
			}
		}
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
		foreach($custom_forms as $field_id => $field_property){
			$saveCustomFields = new Form_Response_Model();
			$saveCustomFields->incident_id = $incident->id;
			$saveCustomFields->form_field_id = $field_id;
			$saveCustomFields->form_response = $locations->$field_property['field_name'];
			$saveCustomFields->save();
		}


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
		//THIS DOES NOT DO ANYTHING BESIDES SAVE DATA. IT IS FOR FUTURE USE TO GROUP REPORTS BASED ON MESSAGE
		$saveMessageID = new Incident_Message_Model();
		$saveMessageID->incident_id = $incident->id;
		$saveMessageID->message_id = $message_id;
		$saveMessageID->save();


		}// end loop to check for asterisk

		if(substr($message_elements[$i], 0,1)=="*" && $actionableExists){ //only run if actionable is being used.


			//search database for report with location title and (report title or category number)
			//get the incident ID
			//edit the actionable database to say action as been taken

			//this query could also easily be used to prevent duplicate reports
			$query = ORM::factory('incident')
				->join('location', 'incident.location_id', 'location.id')
				->where('location.location_name', $location_description)
				->where('incident.incident_title', $title)
				->find_all();

			foreach($query as $row){
		    	$loadActionable =ORM::factory('actionable')
				->where('incident_id',$row->id)
				->find();
				$loadActionable->action_taken=2;
				$loadActionable->resolution_date=date("Y-m-d H:i:s",time());
				$loadActionable->save();
			}

		}//end change actionable loop.




			} // badCode
		} // for loop for items


		if(isset($help)){
			sms::send($from, $sms_from, Kohana::lang('inventorymanagementviasms_ui.help_message'));
		}else if($goodFormat && $goodLocation && !$badCode){
			sms::send($from, $sms_from, Kohana::lang('inventorymanagementviasms_ui.report_submitted'));
		}else if(!$goodFormat && $goodLocation){
			sms::send($from, $sms_from, Kohana::lang('inventorymanagementviasms_ui.invalid_format'));
		}else if(!$goodLocation && $goodFormat){
			sms::send($from, $sms_from, Kohana::lang('inventorymanagementviasms_ui.bad_location'));
		}else if(!$goodFormat && !$goodLocation){
			sms::send($from, $sms_from, Kohana::lang('inventorymanagementviasms_ui.invalid_format'));
		}else if($goodFormat && $goodLocation && $badCode && isset($title)){
			$codeList = "".Kohana::lang('inventorymanagementviasms_ui.bad_item');
			$codeCount = count($badCodes);
			for($b = 0; $b<$codeCount;$b++){
				$codeList = $codeList.$badCodes[$b]." ";
			}
			sms::send($from, $sms_from, Kohana::lang('inventorymanagementviasms_ui.report_submitted')." ".$codeList);
		}else if($goodFormat && $goodLocation && $badCode && !isset($title)){
			$codeList = "".Kohana::lang('inventorymanagementviasms_ui.bad_item');
			$codeCount = count($badCodes);
			for($b = 0; $b<$codeCount;$b++){
				$codeList = $codeList.$badCodes[$b]." ";
			}
			sms::send($from, $sms_from, $codeList);
		}




	} // _parseSMS


} // smsautomate


new inventorymanagementviasms;