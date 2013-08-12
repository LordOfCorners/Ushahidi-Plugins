<?php echo"<script>


</script>" ?>

<h1> Inventory Management via SMS - Settings</h1>
<?php print form::open(); ?>

	<?php if ($form_error) { ?>
	<!-- red-box -->
		<div class="red-box">
			<h3><?php echo Kohana::lang('ui_main.error');?></h3>
			<ul>
				<?php
				foreach ($errors as $error_item => $error_description)
				{
				// print "<li>" . $error_description . "</li>";
				print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
				}
				?>
			</ul>
			</div>
	<?php } ?>


	<?php  if ($form_saved) {?>
		<!-- green-box -->
		<div class="green-box">
		<h3><?php echo Kohana::lang('ui_main.configuration_saved');?></h3>
		</div>
	<?php } ?>

<h4> 
	<br/>Inventory Management via SMS allows you to create custom codes that an end user can send to Ushahidi, where each code has key report information tied to it. The message sent by the end user first includes a code that provides all of the location information for a report, and the rest of the codes each correspond to a particular missing item missing form inventory and provide the rest of the report information, such as category, title, description, etc. <br/>A new report is created for each item code, using the original location code for every report. 
	
	<br/>For incoming SMS messages to work with this plugin, the following format and ordering must be used. Use as many Item Codes as necessary.  <br/>	
	<div style="padding:10px;margin:20px; font-style:italic; border: 1px solid black;"> &lt;Location Code&gt;&lt;delimiter&gt;
	&lt;Item Code&gt;&lt;delimiter&gt;&lt;Item Code&gt;&lt;delimiter&gt;
	&lt;Item Code&gt;&lt;delimiter&gt;&lt;Item Code&gt;&lt;delimiter&gt;
	&lt;Item Code&gt;&lt;delimiter&gt;&lt;Item Code&gt;</div><br/>
	
	So for example if we use a space as our delimiter, a sample message might look like the following:<br/> <!-- note, this setting is turned off right now, always use a space-->
	
	<div style="padding:10px;margin:20px; font-style:italic; border: 1px solid black;">141803 m12 m14 m22 m24 e21 e22 e37</div>
	
	These codes correspond to the ones you will set up below. <br />
	
	The location name, description, and coordinates would be pulled from the first code, and then a new report will be made for each of the rest of the codes, which all correspond to a specific missing item. <br />
	 
	<br/>
	
<!--	To figure out a category's ID number look at the status bar when mousing over the edit or delete link in the Catgories Manage Page in the
	administrative interface. This should be located in admin/manage on your Ushahidi site.-->
<h4>
<br/>
<br/>


<div>
	<div class="row">
		
		<h4>What character should be the delimiter between fields in a text message?</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			This is not actually set up to do anything right now. Separate your codes with a space, until further notice.
			<br/>Separate your codes with a space, until further notice..
		</h6>
		<?php print form::input('delimiter', $form['delimiter'], ' class="text"'); ?>		
	</div>
	<br/>
	<div class="row">
		<h4>Enter your first location</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			Codes are case insensative. For example "AbC" and "abc" will be treated as the same code. 
		</h6>
		<?php
		for($i=0; $i < $form['location_count']; $i++){
		echo("<br/> Code: ");
		print form::input('location_code'.$i, $form['location_code'.$i], ' class="text"');
		echo("<br/> Location Name: ");
		print form::input('location_description'.$i, $form['location_description'.$i], ' class="text"');
		echo("Latitude: ");
		print form::input('latitude'.$i, $form['latitude'.$i], ' class="text"'); 		
		echo("Longitude: ");
		print form::input('longitude'.$i, $form['longitude'.$i], ' class="text"');
		}?>
		<button id="newLocation" type="button">Add new location</button>
		
	</div>
	<br/>
	
		<div class="row">
		<h4>Enter an Item</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			It may be helpful to categorize your item codes by placing a letter at the beginning. <br /> To figure out a category's ID number look at the status bar 
			when mousing over the edit or delete link in the Catgories Manage Page in the
	administrative interface. <br />This should be located in admin/manage on your Ushahidi site  
		</h6>
		
		Code: <?php print form::input('code_word', $form['code_word'], ' class="text"'); ?> <!-- needs correct variables, this is just for mockup purposes --><br/>
		Item Name: <?php print form::input('code_word', $form['code_word'], ' class="text"'); ?> <!-- needs correct variables, this is just for mockup purposes -->
		Item Description: <?php print form::input('code_word', $form['code_word'], ' class="text"'); ?> <!-- needs correct variables, this is just for mockup purposes -->
		Category ID: <?php print form::input('code_word', $form['code_word'], ' class="text"'); ?> <!-- needs correct variables, this is just for mockup purposes -->
				
	</div>
	
	
	
	<div class="row">
		<h4>White listed phone numbers</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			Enter a list of phone numbers, each number on a different line, that are allowed to send in SMSs that are automatically made into reports. 
			<br/>Numbers must be in the exact same format as when they're recieved. If you want any number to be able to use this leave the list blank.
		</h6>
		<?php print form::textarea('whitelist', $form['whitelist'], ' rows="12" cols="40"') ?>		
	</div>
	
	
	
	
</div>
<br/>

<input type="image" src="<?php echo url::base() ?>media/img/admin/btn-save-settings.gif" class="save-rep-btn" style="margin-left: 0px;" />

<?php print form::close(); ?>

