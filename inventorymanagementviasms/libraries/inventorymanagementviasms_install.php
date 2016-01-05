<?php
/**
 * Performs install/uninstall methods for the Inventory Management via SMS plugin
 *
 * @author	   Open Health Networks
 * @package	   Inventory Management via SMS
 *
 * Many thanks to John Etherton for his SMSautomate plugin, which was a great help and provided a starting point.
 */


class inventorymanagementviasms_Install {

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
		$custom_forms = customforms::get_custom_form_fields();

		// Create the database tables.
		// Also include table_prefix in name
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'inventorymanagementviasms` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `delimiter` varchar(1) NOT NULL,
				  `code_word` varchar(11) NOT NULL,
				  `default_message` varchar(11) NOT NULL,

				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'inventorymanagementviasms_whitelist` (
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

				$customFields = "";
				foreach ($custom_forms as $field_id => $field_property){
					// Get the field value
					if ($field_property['field_type'] == 7){ //DROPDOWN
						$customFields .= "`".$field_property['field_name']."` varchar(255) NOT NULL,";
					}
				}

				$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'inventory_locations` (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`location_code` varchar(255) NOT NULL COMMENT \'code used to indicate location\',
					`location_description` varchar(255) NOT NULL,
					`latitude` double NOT NULL,
					`longitude` double NOT NULL,
					'.$customFields.'
					PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');


		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'inventory_items` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `item_code` varchar(255) NOT NULL COMMENT \'code used to indicate item\',
			  `item_description` varchar(255) NOT NULL,
			  `item_category` int(11) NOT NULL COMMENT \'the number here corresponds to the category table\',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		$num_settings = ORM::factory('inventorymanagementviasms')
				->where('id', 1)
				->count_all();
		if($num_settings == 0)
		{
			$settings = ORM::factory('inventorymanagementviasms');
			$settings->id = 1;
			$settings->delimiter = " ";
			$settings->default_message = "";
			$settings->code_word = "abc";
			$settings->save();
		}

	}

	/**
	 * Deletes the database tables for the actionable module
	 */
	public function uninstall()
	{
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'inventorymanagementviasms`');
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'inventorymanagementviasms_whitelist`');
	}
}