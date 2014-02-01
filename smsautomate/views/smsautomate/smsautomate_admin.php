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
	<?php 
		$form['location_count'] = ORM::factory('inventory_locations')->count_all();
		
	} ?>
	
	<?php 
		if($form['location_count'] == 0){
			$form['location_count'] = 1;
		}
		$addLocationString = "'<p id=\'item'+locationCount+'\'>Code: ' + '<input type=\'text\' name=\'location_code' + locationCount + '\' id=\'location_code' + locationCount + '\' class=\'text\'>  Location Name: '+'<input type=\'text\' name=\'location_description' + locationCount + '\' id=\'location_description' + locationCount + '\' class=\'text\'> Latitude: '+'<input type=\'text\' name=\'latitude' + locationCount + '\' id=\'latitude' + locationCount + '\' class=\'text\'> Longitude: '+'<input type=\'text\' name=\'longitude' + locationCount + '\' id=\'longitude' + locationCount + '\' class=\'text\'>";
	?>

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
		
		<h4>What character should be the delimiter between fields in a text message? - DISABLED</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			Separate your codes with a space, until further notice.
		</h6>
		<?php print form::input('delimiter', $form['delimiter'], ' class="text" disabled="disabled"'); ?>		
	</div>
	<br/>
	<div class="row" id="locationDiv">
		<h4>Enter your first location</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			Codes are case insensitive. For example "AbC" and "abc" will be treated as the same code. 
		</h6>
				<?php
						//var_dump($disp_custom_fields);
		for($i=0; $i < $form['location_count']; $i++){
		//for($i=0; $i < $form['location_count']+$newLocationCount; $i++){
			echo("<p id='location".$i."'> Code: ");
			//echo("<script>console.log($newLocationCount + ' ' + $i);</script>");
			print form::input('location_code'.$i, $form['location_code'.$i], ' class="text"');
			echo(" Location Name: ");
			print form::input('location_description'.$i, $form['location_description'.$i], ' class="text"');
			echo(" Latitude: ");
			print form::input('latitude'.$i, $form['latitude'.$i], ' class="text"'); 		
			echo(" Longitude: ");
			print form::input('longitude'.$i, $form['longitude'.$i], ' class="text"');
			echo("<br >");
		
				foreach ($disp_custom_fields as $field_id => $field_property)
				{	
							// Get the field value
	
					if ($field_property['field_type'] == 7){ //DROPDOWN
						$id_name = 'id="custom_field_'.$field_id.'"';
	
	/*
						$field_value = ( ! empty($form['custom_field'.$i][$field_id]))
							? $form['custom_field'.$i][$field_id]
							: $field_property['field_default'];
	*/
						$defaults = explode('::',$field_property['field_default']);
			
						$default = (isset($defaults[1])) ? $defaults[1] : 0;
			
						if (isset($form['custom_field'][$field_id][$i]))
						{
							if($form['custom_field'][$field_id][$i] != '')
							{
								$default = $form['custom_field'][$field_id][$i];
							}
						}
						
						$options = explode(',',$defaults[0]);
						
	
						
							$ddoptions = array();
						// Semi-hack to deal with dropdown boxes receiving a range like 0-100
						if (preg_match("/[0-9]+-[0-9]+/",$defaults[0]) AND count($options == 1))
						{
							$dashsplit = explode('-',$defaults[0]);
							$start = $dashsplit[0];
							$end = $dashsplit[1];
							for($i = $start; $i <= $end; $i++)
							{
								$ddoptions[$i] = $i;
							}
						}
						else
						{
							foreach($options as $op)
							{
								$op = trim($op);
								$ddoptions[$op] = $op;
							}
						}
						
						echo $field_property['field_name'].": ";
					}
					print form::dropdown("custom_field[".$field_id.']'."[".$i."]",$ddoptions,$default,"id = \"custom_field[".$field_id."]"."[".$i."]"."\"");
					echo("<br >");

					//
					//ob_start();
					//print form::dropdown("custom_field[".$field_id.']'.$i,$ddoptions,$default,"id = \"custom_field[".$field_id."]".$i."\"");
					//$customFieldFormTemp = ob_get_contents();
					//ob_end_clean();
					
					//$customFieldForm = (string)$customFieldFormTemp;
	/* 				preg_replace('/"/', "'", $customFieldForm); */
					//$addLocationString .= $customFieldForm;
					
				}
	
			//print form::input('item_description'.$i, $form['item_description'.$i], ' class="text"');

		echo("<button onclick='removeElement(&#39;locationDiv&#39;, this,1);' type='button'>Delete</button>");
		echo("</p>");
		
		}?>
				
		<span id="newLocations"> </span>
	</div>
		<button onclick="addLocation()" type="button">Add new location</button>
	<br/>
	
		<div class="row" id="itemDiv">
		<h4>Enter an Item</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			It may be helpful to categorize your item codes by placing a letter at the beginning. <br /> To figure out a category's ID number look at the status bar 
			when mousing over the edit or delete link in the Catgories Manage Page in the
	administrative interface. <br />This should be located in admin/manage on your Ushahidi site  
		</h6>
		
		<?php
		$categories = ORM::factory('category')->find_all();
		for($i=0; $i < $form['item_count']+$newItemCount; $i++){
		echo("<p id='item".$i."'> Code: ");
		print form::input('item_code'.$i, $form['item_code'.$i], ' class="text"');
		echo(" Item Description: ");
		print form::input('item_description'.$i, $form['item_description'.$i], ' class="text"');
		echo(" Item Category: "); //would be great to make this a dropdown of categories. 
		echo("<select name='item_category".$i."'>");
		foreach($categories as $row){
			if($form['item_category'.$i] == $row->id){
				echo("<option selected='selected' value='".$row->id."'>".$row->category_title."</option>");
			}
			else{
				echo("<option value='".$row->id."'>".$row->category_title."</option>");
			}
		}
		echo("</select>");
		echo("<button onclick='removeElement(&#39;itemDiv&#39;, &#39;item".$i."&#39;,0)' type='button'>Delete</button>");
/* 		print form::input('item_category'.$i, $form['item_category'.$i], ' class="text"'); 		 */
		echo("</p>");		
		}
?>

	</div>
	<button onclick="addItem()" type="button">Add new item</button>
	
	
	
	<div class="row">
		<h4>White listed phone numbers - DISABLED</h4>
		<h6 style="margin-top:1px; padding-top:1px;margin-bottom:1px; padding-bottom:1px;">
			Enter a list of phone numbers, each number on a different line, that are allowed to send in SMSs that are automatically made into reports. 
			<br/>Numbers must be in the exact same format as when they're recieved. If you want any number to be able to use this leave the list blank.
		</h6>
		<?php print form::textarea('whitelist', $form['whitelist'], ' rows="12" cols="40" disabled') ?>		
	</div>
	
	
	
	
</div>
<br/>
<input name="newLocationCount" type="hidden" id="newLocationCount" value="" />
<input name="newItemCount" type="hidden" id="newItemCount" value="" />
<input type="image" src="<?php echo url::base() ?>media/img/admin/btn-save-settings.gif" class="save-rep-btn" style="margin-left: 0px;" />

<?php print form::close(); ?>

<!-- store location count in javascript -->
<?php 
$form['location_count'] = ORM::factory('inventory_locations')->count_all();
$form['item_count'] = ORM::factory('inventory_items')->count_all();


echo"<script>
var locationCount = $form[location_count];
var itemCount = $form[item_count];
</script>";
 ?>
 <?php echo "<script>
function addLocation()
{
	var sourceNode = document.getElementById('location0');
	var attributesToBump = ['id', 'name']; 
    locationCount++;
    var out = sourceNode.cloneNode(true);
    if (out.hasAttribute('id')) { out['id'] = bump(out['id']); }
    var nodes = out.getElementsByTagName('*');
    for (var i = 0, len1 = nodes.length; i < len1; i++) {
    	var node = nodes[i];
        for (var j = 0, len2 = attributesToBump.length; j < len2; j++) {
        	var attribute = attributesToBump[j];
            if (node.hasAttribute(attribute)) {
            	node[attribute] = bump(node[attribute]);
            	node.value = '';
            }
        }
    }
    sourceNode.parentNode.appendChild(out);
    function bump(/*String*/str) {
    	if(str.substring(0,3) == 'cus'){
    		str = str.substring(0,str.length-2);
    		return str + '' + (locationCount-1) + ']';
    	}
    	else{
	    	str  = str.substring(0,str.length-1);
	    	return str + '' + (locationCount-1);
    	}
    }
    document.getElementById('newLocationCount').value = locationCount+1;
}</script>";
?>
<?php
echo "<script>
function addItem()
{
	itemCount = itemCount+1;
	var string = '<p id = \'item' + itemCount + '\'>Code: ' + '<input type=\'text\' name=\'item_code' + itemCount + '\' id=\'item_code' + itemCount + '\' class=\'text\'> Item Description: '+'<input type=\'text\' name=\'item_description' + itemCount + '\' id=\'item_description' + itemCount + '\' class=\'text\'>';
	
	string += ' Item Category: <select name=\'item_category' + itemCount +'\'>';
	for(var i=0; i<catArray.length;i++){
	string += '<option value=\''+catIDs[i]+'\'>'+ catArray[i] +'</option>';
	}
	string += '</select><button onclick=\'removeElement(&#39;itemDiv&#39;,&#39;item'+itemCount+'&#39;,0)\' type=\'button\'>Delete</button></p>';

	$( '#itemDiv' ).append(string);
	
	
	document.getElementById('newItemCount').value = itemCount+1;
}

function removeElement(parent,toDelete,type) {
	
  var d = document.getElementById(parent);
  switch(type){
  	case 0:
  	  	var olddiv = document.getElementById(toDelete);
  		d.removeChild(olddiv);
  		itemCount--;
  		document.getElementById('newItemCount').value = itemCount-$form[item_count];
  		break;
  	case 1:
  		var olddiv = document.getElementById(toDelete.parentNode.id);
  		if(toDelete.parentNode.id != 'location0'){
  			d.removeChild(olddiv);
  			locationCount--;
  			document.getElementById('newLocationCount').value = locationCount-$form[location_count];
  		} 
  		break;
  	default:
  		//break;
  
  }
  
}


</script>" ?>



	<?php echo("<script> 
				var catArray = new Array();
				var catIDs = new Array();");
		  foreach($categories as $row){
		   		echo("catArray.push('$row->category_title');");
		   		echo("catIDs.push('$row->id');");
		   }
		  echo("</script>");
		  ?>


