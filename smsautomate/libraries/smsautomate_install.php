<?php
/**
 * Performs install/uninstall methods for the smsautomate plugin
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   smsautomate Installer
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Smsautomate_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db = Database::instance();
	}

	/**
	 * Creates the required database tables for the actionable plugin
	 */
	public function run_install()
	{
		// Create the database tables.
		// Also include table_prefix in name
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'smsautomate` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `delimiter` varchar(1) NOT NULL,
				  `code_word` varchar(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
				
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'smsautomate_whitelist` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `phone_number` varchar(20) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
			
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'incident_message` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `incident_id` int(11) NOT NULL COMMENT \'incident_id of the new report that is created\',
			  `message_id` int(11) NOT NULL COMMENT \'message_id of the incoming message\',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
			
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'inventory_locations` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `location_code` int(11) NOT NULL COMMENT \'code used to indicate location\',
			  `location_description` varchar(255) NOT NULL,
			  `latitude` double NOT NULL,
			  `longitude` double NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
			
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'inventory_items` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `item_code` int(11) NOT NULL COMMENT \'code used to indicate item\',
			  `item_description` varchar(255) NOT NULL,
			  `item_category` int(11) NOT NULL COMMENT \'the number here corresponds to the category table\',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
		
		$num_settings = ORM::factory('smsautomate')
				->where('id', 1)
				->count_all();
		if($num_settings == 0)
		{
			$settings = ORM::factory('smsautomate');
			$settings->id = 1;
			$settings->delimiter = ";";
			$settings->code_word = "abc";
			$settings->save();
		}
		
	}

	/**
	 * Deletes the database tables for the actionable module
	 */
	public function uninstall()
	{
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'smsautomate`');
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'smsautomate_whitelist`');
	}
}