<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Download Reports Controller.
 * This controller will take care of downloading reports.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author Marco Gnazzo
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

Class Visualizer_Controller extends Main_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->template->this_page = 'visualizer';
		$this->template->header->this_page = 'visualizer';
		$this->template->content = new View('visualizer/visualizer_view');
		$this->template->content->title = Kohana::lang('ui_admin.visualize_reports');
	}


}
