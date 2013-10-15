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

		// If this is not a super-user account, redirect to dashboard
		if(!$this->auth->logged_in('admin') && !$this->auth->logged_in('superadmin'))
		{
			url::redirect('admin/dashboard');
		}
	}
	
	public function index()
	{
		
		$this->template->content = new View('smsautomate/smsautomate_admin');
		
		//create the form array
		$form = array
		(
		    'delimiter' => "",
			'code_word' => "",
			'whitelist' => "",
			'location_count' => ORM::factory('inventory_locations')->count_all(),
			'item_count' => ORM::factory('inventory_items')->count_all()

		);
		for($i=0; $i < $form['location_count']; $i++){
		$form['location_description'.$i] = "";
		$form['location_code'.$i] = "";
		$form['longitude'.$i] = "";
		$form['latitude'.$i] = "";
		}
		
		for($i=0; $i < $form['item_count']; $i++){
		$form['item_description'.$i] = "";
		$form['item_code'.$i] = "";
		$form['item_category'.$i] = "";
		}
		
		$newItemCount=0;
		$newLocationCount=0;
		
		$errors = $form;
		$form_error = FALSE;
		$form_saved = FALSE;
				
		// check, has the form been submitted if so check the input values and save them
		if ($_POST)
		{
		

			// Instantiate Validation, use $post, so we don't overwrite $_POST
			// fields with our own things
			$post = new Validation($_POST);
			
			for($i=$form['location_count']; $i < $form['location_count']+$post->newLocationCount; $i++){
				$form['location_description'.$i] = "";
				$form['location_code'.$i] = "";
				$form['longitude'.$i] = "";
				$form['latitude'.$i] = "";
			}
			
			for($i=$form['item_count']; $i < $form['item_count']+$post->newItemCount; $i++){
				$form['item_description'.$i] = "";
				$form['item_code'.$i] = "";
				$form['item_category'.$i] = "";
			}
			
			// Add some filters
			$post->pre_filter('trim', TRUE);
			$post->add_rules('delimiter', 'length[1,1]');
			$post->add_rules('code_word', 'length[1,11]');
			
			 if ($post->validate())
			{
				
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
				ORM::factory('inventory_locations')->delete_all();
				for($i=0; $i < $form['location_count']+ $newLocationCount; $i++){
					$_locationCode = "location_code".$i;
					$_locationDesc = "location_description".$i;
					$_latitude = "latitude".$i;
					$_longitude = "longitude".$i;
	
					if(isset($post->$_locationCode)){
						$locations = new Inventory_locations_Model();
						$locations->location_code = strtoupper($post->$_locationCode);
						$locations->location_description = $post->$_locationDesc;
						$locations->latitude = $post->$_latitude;
						$locations->longitude = $post->$_longitude;
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
	
					if(isset($post->$_itemCode)){
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
			}
			
			// No! We have validation errors, we need to show the form again,
			// with the errors
			else
			{
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
			foreach($listers as $item)
			{
				$count++;
				if($count > 1)
				{
					$whitelist = $whitelist."\n";
				}
				$whitelist = $whitelist.$item->phone_number;
			}
			$form['whitelist'] = $whitelist;
		}
		
		
		
		$this->template->content->form_saved = $form_saved;
		$this->template->content->newLocationCount = $newLocationCount;
		$this->template->content->newItemCount = $newItemCount;
		$this->template->content->form = $form;
		$this->template->content->form_error = $form_error;
		$this->template->content->errors = $errors;
		
	}//end index method
	
	

	
}