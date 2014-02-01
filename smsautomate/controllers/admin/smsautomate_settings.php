 <?php defined('SYSPATH') or die('No direct script access.');
/**
 * SMS Automate Administrative Controller
 *
 * @author	   John Etherton
 * @package	   SMS Automate
 */

class Smsautomate_settings_Controller extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->template->this_page = 'settings';
		$this->db = Database::instance();

		// If this is not a super-user account, redirect to dashboard
		if(!$this->auth->logged_in('admin') && !$this->auth->logged_in('superadmin'))
		{
			url::redirect('admin/dashboard');
		}
	}
	
	public function index()
	{
		$newItemCount=0;
		$newLocationCount=0;
		$this->template->content = new View('smsautomate/smsautomate_admin');
		$custom_forms = customforms::get_custom_form_fields();
		$this->template->content->disp_custom_fields = $custom_forms;

		//create the form array
		$form = array
		(
		    'delimiter' => "",
			'code_word' => "",
			'whitelist' => "",
			'location_count' => ORM::factory('inventory_locations')->count_all(),
			'item_count' => ORM::factory('inventory_items')->count_all()

		);
		//for($i=0; $i < $form['location_count']; $i++){
		for($i=0; $i < $form['location_count']; $i++){
			$form['location_description'.$i] = "";
			$form['location_code'.$i] = "";
			$form['longitude'.$i] = "";
			$form['latitude'.$i] = "";
			//loop through all the custom fields and add a new form for each
				foreach ($custom_forms as $field_id => $field_property){	
					// Get the field value
					if ($field_property['field_type'] == 7){ //DROPDOWN
/*
					$field_value = ( ! empty($form['custom_field'][$field_id][$i]))
						? $form['custom_field'][$field_id][$i]
						: $field_property['field_default'];
*/
					$form['custom_field'][$field_id][$i] ="";
					}
				}
		}
/*
			var_dump($form);
			die;
*/
		
		for($i=0; $i < $form['item_count']+$newItemCount; $i++){
			$form['item_description'.$i] = "";
			$form['item_code'.$i] = "";
			$form['item_category'.$i] = "";
		}
		

		
		$errors = $form;
		$form_error = FALSE;
		$form_saved = FALSE;
				
		// check, has the form been submitted if so check the input values and save them
		if ($_POST)
		{
		

			// Instantiate Validation, use $post, so we don't overwrite $_POST
			// fields with our own things
			$post = new Validation($_POST);
						
/*
			var_dump($form);
			die;
	
*/

			for($i=0; $i < $form['location_count']+$post->newLocationCount; $i++){
				$form['location_description'.$i] = "";
				$form['location_code'.$i] = "";
				$form['longitude'.$i] = "";
				$form['latitude'.$i] = "";
				foreach ($custom_forms as $field_id => $field_property){	
					// Get the field value
					//custom_field[".$field_id."]".$i -- FROM THE VIEW
					if ($field_property['field_type'] == 7){ //DROPDOWN
					//$form['custom_field'][$field_id][$i] ="";
					/*
					$field_value = ( ! empty($form['custom_field'][$field_id][$i]))
							? $form['custom_field'][$field_id][$i]
							: $field_property['field_default'];
*/
					}
				}
				
			}
			
			for($i=0; $i < $form['item_count']+$post->newItemCount; $i++){
				$form['item_description'.$i] = "";
				$form['item_code'.$i] = "";
				$form['item_category'.$i] = "";
			}
		
			// Add some filters
			$post->pre_filter('trim', TRUE);
			$post->add_rules('delimiter', 'length[1,1]');
			$post->add_rules('code_word', 'length[1,11]');
			
			 if ($post->validate()){		
			
				$customFields = "";
				foreach ($custom_forms as $field_id => $field_property){		
					// Get the field value
					if ($field_property['field_type'] == 7){ //DROPDOWN
						$customFields .= "`".$field_property['field_name']."` varchar(255) NOT NULL,";
					}	
				}
				//$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'inventory_locations`');
				/*
$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'inventory_locations` (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`location_code` varchar(255) NOT NULL COMMENT \'code used to indicate location\',
					`location_description` varchar(255) NOT NULL,
					`latitude` double NOT NULL,
					`longitude` double NOT NULL,
					'.$customFields.'
					PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
*/
				
				$settings = ORM::factory('smsautomate')
					->where('id', 1)
					->find();
				$settings->delimiter = $post->delimiter;
				$settings->code_word = $post->code_word;
				$settings->save();
				$form_saved = TRUE;
				if($post->newLocationCount!=null){
					$newLocationCount=$post->newLocationCount;
				}
				if($post->newItemCount!=null){
					$newItemCount=$post->newItemCount;
				}
				$form = arr::overwrite($form, $post->as_array());
/*
				var_dump($form);
				die;
*/
				ORM::factory('inventory_locations')->delete_all();
				for($i=0; $i < $form['location_count']+ $newLocationCount; $i++){
					$_locationCode = "location_code".$i;
					$_locationDesc = "location_description".$i;
					$_latitude = "latitude".$i;
					$_longitude = "longitude".$i;
					foreach ($custom_forms as $field_id => $field_property){	
						// Get the field value
						//custom_field[".$field_id."]".$i -- FROM THE VIEW
						if ($field_property['field_type'] == 7){ //DROPDOWN
							/*
$field_value = ( ! empty($form['custom_field'][$field_id][$i]))
								? $form['custom_field'][$field_id].$i
								: $field_property['field_default'];
*/
						}		
					}
	
					if(isset($post->$_locationCode) && $post->$_locationCode != ""){
						$locations = new Inventory_locations_Model();
						$locations->location_code = strtoupper($post->$_locationCode);
						$locations->location_description = $post->$_locationDesc;
						$locations->latitude = $post->$_latitude;
						$locations->longitude = $post->$_longitude;
							foreach($custom_forms as $field_id => $field_property){
								$locations->$field_property['field_name'] =  $form['custom_field'][$field_id][$i];
							}
						$locations->save();
					}
					else{
						continue;
					}
				}
				
				ORM::factory('inventory_items')->delete_all();
				for($i=0; $i < $form['item_count']+ $newItemCount; $i++){
					$_itemCode = "item_code".$i;
					$_itemDesc = "item_description".$i;
					$_itemCat = "item_category".$i;
	
					if(isset($post->$_itemCode) && $post->$_itemCode!=""){
						$items = new Inventory_items_Model();
						$items->item_code = strtoupper($post->$_itemCode);
						$items->item_description = $post->$_itemDesc;
						$items->item_category = $post->$_itemCat;
						$items->save();
					}
					else{
						continue;
					}
				}

				
/* This would need to be uncommented if the whitelist is re-implemented 
				//do the white list
				
				//delete everything in the white list db to make room for the new ones
				ORM::factory('smsautomate_whitelist')->delete_all();
				
				$whitelist = nl2br(trim($post->whitelist));
				if($whitelist != "" && $whitelist != null)
				{
					$whitelist_array = explode("<br />", $whitelist);
					//now put back the new ones
					foreach($whitelist_array as $item)
					{
						$whitelist_item = ORM::factory('smsautomate_whitelist');
						$whitelist_item->phone_number = trim($item);
						$whitelist_item->save();
					}
				}
*/

			}
			
			// No! We have validation errors, we need to show the form again,
			// with the errors
			else{
				// repopulate the form fields
				$form = arr::overwrite($form, $post->as_array());

				// populate the error fields, if any
				$errors = arr::overwrite($errors, $post->errors('settings'));
				$form_error = TRUE;
			}
		}
		else
		{
			//get settings from the database
			$settings = ORM::factory('smsautomate')
				->where('id', 1)
				->find();
			$form['delimiter'] = $settings->delimiter;
			$form['code_word'] = $settings->code_word;
			
			
			$j=0;			
			$locations = ORM::factory('inventory_locations')->find_all();
			foreach($locations as $row){
				$form['location_code'.$j] = $row->location_code;
				$form['location_description'.$j] = $row->location_description;
				$form['latitude'.$j] = $row->latitude;
				$form['longitude'.$j] = $row->longitude;
				//Load custom fields from DB
				
				foreach($custom_forms as $field_id => $field_property){
					  $form['custom_field'][$field_id][$j] = $row->$field_property['field_name'] ;
				}

				$j++;
			}
				
			$k=0;			
			$items = ORM::factory('inventory_items')->find_all();
			foreach($items as $row){
				$form['item_code'.$k] = $row->item_code;
				$form['item_description'.$k] = $row->item_description;
				$form['item_category'.$k] = $row->item_category;
				$k++;
			}
			
			
			//get the white listed numbers
			$whitelist = "";
			$count = 0;
			$listers = ORM::factory('smsautomate_whitelist')->find_all();
			foreach($listers as $item){
				$count++;
				if($count > 1){
					$whitelist = $whitelist."\n";
				}
				$whitelist = $whitelist.$item->phone_number;
			}
			$form['whitelist'] = $whitelist;
/*
			$page = $_SERVER['PHP_SELF'];
			$sec = "0";
			header("Refresh: $sec; url=$page");
*/
		}// end of post
		
		
		
		$this->template->content->form_saved = $form_saved;
		$this->template->content->newLocationCount = $newLocationCount;
		$this->template->content->newItemCount = $newItemCount;
		$this->template->content->form = $form;
		$this->template->content->form_error = $form_error;
		$this->template->content->errors = $errors;

		
		}//end index method
	
	

	
}