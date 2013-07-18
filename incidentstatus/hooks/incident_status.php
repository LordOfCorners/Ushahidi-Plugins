<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Incident Status Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Marco Gnazzo
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
//Requirements::js('plugins/incidentstatus/js/incidentstatus_filter.js');

class incident_status {
	/**
	 *  Registers the main event add method
	**/ 
	public function __construct()
	{
		$this->waiting = "1";	
		$this->taken_on = "";
		$this->resolved = "";
		$this->summary = "";
				
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
		
		
	}
	
	public function filtermap(){
		// This time we'll get the content from our view
		View::factory('incidentstatus/incidentstatus_filter')->render(TRUE);
	}
	/**
	* Adds all the events to the main Ushahidi application	
	**/
	public function add()
	{
		// Only add the events if we are on that controller
		if (Router::$controller == 'reports')
		{
			switch (Router::$method)
			{
				// Hook into the Report Add/Edit Form in Admin
				case 'edit':
					// Hook into the form itself
					Event::add('ushahidi_action.report_form_admin', array($this, '_report_form'));
					
					// Hook into the report_edit (post_SAVE) event
					Event::add('ushahidi_action.report_edit', array($this, '_report_form_submit'));
					break;
				
				// Hook into the User Report view 
				case 'view':
					plugin::add_stylesheet('incidentstatus/views/css/incident_status');
					Event::add('ushahidi_action.report_meta', array($this, '_report_view'));
					break;
			}
		}
		if (Router::$controller == 'main' AND Router::$method == 'index') {
			plugin::add_stylesheet('incidentstatus/views/css/incident_status');
			Event::add('ushahidi_action.main_sidebar_pre_filters', array($this, 'filtermap'));
			

		}
		//Event::add('ushahidi_filter.json_replace_markers',array($this,'filter_json'));
		
	}
	
	
	/**
	* Add incident_status Form input to the Report Submit Form
	**/
	public function _report_form()
	{
		// Load the View
		$form = View::factory('form');
		// Get the ID of the Report
		$id = Event::$data;
		
		if ($id)
		{
			// Load last incident_status
			$report_item = ORM::factory('incident_status')
				->where('incident_id', $id)->orderby('id', 'DESC')
				->find();
			// Load all summaries	
			$summary = ORM::factory('incident_status')
				->where('incident_id', $id)->find_all();
							
			if ($report_item->loaded)
			{
				$this->waiting = $report_item->waiting;			
				$this->taken_on = $report_item->taken_on;
				$this->resolved = $report_item->resolved;								
				$this->summary = $summary;						
			}
		}
		
		$form->waiting = $this->waiting;
		$form->taken_on = $this->taken_on;
		$form->resolved = $this->resolved;
		$form->summaries = $this->summary;
		$form->render(TRUE);
	}
	
	public function filter_json(){
		
			
/*
			echo "<LEIF>";
			echo "</LEIF>";
			exit;
*/

		$incidents = reports::fetch_incidents();
		$json_features = array();
		
		// Extra params for markers only
		// Get the incidentid (to be added as first marker)
		$first_incident_id = (isset($_GET['i']) AND intval($_GET['i']) > 0)? intval($_GET['i']) : 0;
		
		$media_type = (isset($_GET['k']) AND intval($_GET['k']) > 0)? intval($_GET['m']) : 0;
		
		foreach ($incidents as $marker)
		{
			// Handle both reports::fetch_incidents() response and actual ORM objects
			$marker->id = isset($marker->incident_id) ? $marker->incident_id : $marker->id;
			if (isset($marker->latitude) AND isset($marker->longitude))
			{
				$latitude = $marker->latitude;
				$longitude = $marker->longitude;
			}
			elseif (isset($marker->location) AND isset($marker->location->latitude) AND isset($marker->location->longitude))
			{
				$latitude = $marker->location->latitude;
				$longitude = $marker->location->longitude;
			}
			else
			{
				// No location - skip this report
				continue;
			}
			
			// Get thumbnail
			$thumb = "";
			$media = ORM::factory('incident', $marker->id)->media;
			if ($media->count())
			{
				foreach ($media as $photo)
				{
					if ($photo->media_thumb)
					{
						// Get the first thumb
						$thumb = url::convert_uploaded_to_abs($photo->media_thumb);
						break;
					}
				}
			}

			// Get URL from object, fallback to Incident_Model::get() if object doesn't have url or url()
			if (method_exists($marker, 'url'))
			{
				$link = $marker->url();
			}
			elseif (isset($marker->url))
			{
				$link = $marker->url;
			}
			else
			{
				$link = Incident_Model::get_url($marker);
			}
			$item_name = $this->get_title($marker->incident_title, $link);

			$json_item = array();
			$json_item['type'] = 'Feature';
			$json_item['properties'] = array(
				'id' => $marker->id,
				'name' => $item_name,
				'link' => $link,
				'category' => array($category_id),
				'color' => $color,
				'icon' => $icon,
				'thumb' => $thumb,
				'timestamp' => strtotime($marker->incident_date),
				'count' => 1,
				'class' => get_class($marker)
			);
			$json_item['geometry'] = array(
				'type' => 'Point',
				'coordinates' => array($longitude, $latitude)
			);

			if ($marker->id == $first_incident_id)
			{
				array_unshift($json_features, $json_item);
			}
			else
			{
				array_push($json_features, $json_item);
			}
			
			// Get Incident Geometries
			if ($include_geometries)
			{
				$geometry = $this->_get_geometry($marker->id, $marker->incident_title, $marker->incident_date);
				if (count($geometry))
				{
					foreach ($geometry as $g)
					{
						array_push($json_features, $g);
					}
				}
			}
		}
	}
	
	/**
	 * Handle Form Submission and Save Data
	 */
	public function _report_form_submit()
	{
		$id = Event::$data;

		if ($_POST)
		{
			$report_last = ORM::factory('incident_status')
				->where('incident_id', $id->id)->orderby('id', 'DESC')
				->find();
				
			$report_item = ORM::factory('incident_status');
			
			
			if (!$report_last->loaded)
				{
						
					$report = ORM::factory('incident')
					->where('id', $id->id)->find();
					
					$report_ite = ORM::factory('incident_status');	
					$report_ite->incident_id = $id->id;			
					$report_ite->waiting = 1;
					$report_ite->taken_on = 0;
					$report_ite->resolved = 0;		
					$report_ite->summary = "";
					$report_ite->datetime = $report->incident_date;
					$report_ite->save();
					
				}		
			
			$report_last = ORM::factory('incident_status')
				->where('incident_id', $id->id)->orderby('id', 'DESC')
				->find();
						
			$waiting = 0;
			$taken_on = 0;
			$taken = 0;
			
			switch ($_POST['status']) 
			{
   			case 0:
       			$waiting = 1;			
	        	break;

    			case 1:
       		 	$taken_on = 1;		
      			break;

    			case 2:
	        	$taken = 1;
	        	break;
			}		
			
			// If status has changed save it
			if (($report_last->waiting != $waiting ) || ($report_last->taken_on != $taken_on)
				|| ($report_last->resolved != $taken) || ($_POST['summary'] != ""))
			{			
				$report_item->incident_id = $id->id;			
				$report_item->waiting = $waiting;
				$report_item->taken_on = $taken_on;
				$report_item->resolved = $taken;		
				$report_item->summary = $_POST['summary'];
				$report_item->datetime = date("Y-m-d G:i:s");
				$report_item->save();
			}		
		} 
	}
	
	/**
	 * Render the Incident Status Information to the Report on the front end
	 */
	public function _report_view()
	{
		$id = Event::$data;
		if ($id)
		{
			$report_item = ORM::factory('incident_status')
				->where('incident_id', $id)->orderby('id', 'DESC')
				->find();
			
			$summary = ORM::factory('incident_status')
				->where('incident_id', $id)
				->find_all();
			
			if ($report_item->loaded)
			{
				if (($report_item->taken_on) || ($report_item->resolved))
				{
					$report = View::factory('report');
					$report->resolved = $report_item->resolved;
					$report->taken_on = $report_item->taken_on;
					$report->summaries = $summary;
					$report->render(TRUE);
				}
				else 
				{
					$report = View::factory('report_waiting');
					$report->resolved = $report_item->resolved;
					$report->summaries = $summary;
					$report->render(TRUE);		
				}
			}
			else 
				{
					$report = View::factory('report_waiting');
					$report->resolved = $report_item->resolved;
					$report->summaries = $report_item->summary;
					$report->render(TRUE);	
				}
		}
		
	}
	
}

new incident_status;
