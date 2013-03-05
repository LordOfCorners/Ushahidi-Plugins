<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Visualizer Hook
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Michael Kahane <kaham340@newschool.edu> 
 * @package	   
 * @copyright  
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */



class visualizer {
 
    public function __construct()
    {
        // Hook into routing
        Event::add('system.pre_controller', array($this, 'add'));
        Event::add('ushahidi_action.nav_main_top', array($this, '_add_nav'));
		
    }
 
    public function add()
    { 

    }
    
    public function _add_nav()
	{
		$page = Event::$data;
		// Add plugin link to nav_main_top
		echo "<li><a href='" . url::site() . "visualizer' class='".($page == 'visualizer' ? 'active' : '')."'>" . 			      		utf8::strtoupper(Kohana::lang('visualizer_ui.visualize_reports')) . "</a></li>";

	}
	
	public function fillTable()
	{
	

	}
	
	
 
/*	public function hello()
	{
	    // This time we'll get the content from our view
	    View::factory('visualizer/visualizer_view')->render(TRUE);
	} */
 
}
 
//instatiation of hook
new visualizer;