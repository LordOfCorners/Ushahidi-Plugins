<?php 
		ORM::factory('visualizer')->delete_all();

		$category_array = ORM::factory('category')->find_all();
		foreach($category_array as $cat)
		{
		   $visualizer = ORM::factory('visualizer'); // load/reload visualizer table
		   $count_array = ORM::factory('incident')->where('incident_title', $cat->category_title); //search for all incident titles that match the category title. This 																									   method only works for us right now because most people won't have the same 																								   title  names as their categories.......
		   $counter = $count_array->count_all(); //stores number of returned results into counter variable
	 	   $visualizer->category_name = $cat->category_title; // sets visualizer table categories
	 	   $visualizer->frequency = $counter; // sets visualizer table frequencies 
	 	   $visualizer->category_color = $cat->category_color; //this would set the category color
	 	   $visualizer->save(); // saves visualizer table 
    	} 
 
    	$settings = ORM::factory('visualizer_settings', 1);
    	$bar_title = $settings->bar_graph_title; 
    	$bar_description = $settings->bar_graph_description; 
?>   


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Visualizer</title>

		<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/visualizer/media/visualizer/css/BarChart.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo url::base(); ?>plugins/visualizer/media/visualizer/css/base.css" />
<!-- 		<script type="text/javascript" src="<?php echo url::base(); ?>plugins/visualizer/media/visualizer/js/barchart.js"></script> -->
		<?php include 'plugins/visualizer/media/visualizer/js/barchart.php'; ?>
		<script type="text/javascript" src="<?php echo url::base(); ?>plugins/visualizer/media/visualizer/js/jit.js"></script>
		<script type="text/javascript">

		</script>
	</head>
<body onload="init();">
<div id="container">

<div id="left-container">



        <div class="text">
        <h4>
	        	<?php echo $bar_title ?>
        </h4> 

            <?php echo $bar_description ?> <br /><br />
            Click the Update button to update the JSON data.
            
        </div>
        <ul id="id-list"></ul>
        <a id="update" href="#" class="theme button white">Update Data</a>

</div>

<div id="center-container">
    <div id="infovis"></div>    
</div>

<div id="right-container">

<div id="inner-details"></div>

</div>

<div id="log"></div>
</div>
</body>
</html>
