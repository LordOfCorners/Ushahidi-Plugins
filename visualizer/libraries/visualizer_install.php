<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Performs install/uninstall methods for the Visualizer plugin
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Michael Kahane <kaham340@newschool.edu> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Visualizer Installer
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */



class Visualizer_Install {
 
    /**
     * Constructor to load the shared database library
     */
    public function __construct()
    {
		$this->db = Database::instance();
    }
 
    /**
     * Creates the required database tables for the visualizer plugin
     */
    public function run_install()
    {
      		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'visualizer` (
      		      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      			  `category_name` varchar(255) NULL,
				  `frequency` int(10) unsigned NULL,
				  `category_color` varchar(255) NULL,
				  
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
				
			$this->db->query("CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix').'visualizer_settings` (
				  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				  `bar_graph_title` varchar(100) DEFAULT NULL,
				  `bar_graph_description` varchar(200) DEFAULT NULL,
				  `label_a` varchar(100) DEFAULT NULL,

				  

			   	  PRIMARY KEY (`id`)
			    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
		
			
			
	}
 
    /**
     * Deletes the database tables for the visualizer plugin
     */
    public function uninstall()
    {
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'visualizer`');
        $this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'visualizer_settings`');


    }
}