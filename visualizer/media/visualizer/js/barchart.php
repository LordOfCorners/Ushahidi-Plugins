<?php 
  		$labels = array();
		$values = array();
		$i=0;

		$db=new Database;
        $result= $db->query('SELECT * FROM `visualizer` ORDER BY `visualizer`.`frequency` DESC LIMIT 0, 10'); /*database query to return first 10 rows in visualizer, sorted 
        																									 by frequency in descending order*/

        foreach($result as $row){
        	
        	$labels[$i] = $row->category_name;  //store category names in an array of labels
        	$values[$i] = $row->frequency; // store value names in an array of values. 
	        $i++;
    	}
    	
    	$settings = ORM::factory('visualizer_settings', 1);
    	$label_a = $settings->label_a; 

?>
<script>

var label0 = "<?php echo $labels[0] ?>";
var label1 = "<?php echo $labels[1] ?>";
var label2 = "<?php echo $labels[2] ?>";
var label3 = "<?php echo $labels[3] ?>";
var label4 = "<?php echo $labels[4] ?>";
var label5 = "<?php echo $labels[5] ?>";
var label6 = "<?php echo $labels[6] ?>";
var label7 = "<?php echo $labels[7] ?>";
var label8 = "<?php echo $labels[8] ?>";
var label9 = "<?php echo $labels[9] ?>";


var value0 = <?php echo $values[0] ?>;
var value1 = <?php echo $values[1] ?>;
var value2 = <?php echo $values[2] ?>;
var value3 = <?php echo $values[3] ?>;
var value4 = <?php echo $values[4] ?>;
var value5 = <?php echo $values[5] ?>;
var value6 = <?php echo $values[6] ?>;
var value7 = <?php echo $values[7] ?>;
var value8 = <?php echo $values[8] ?>;
var value9 = <?php echo $values[9] ?>;

var label_a = "<?php echo $label_a ?>";




var labelType, useGradients, nativeTextSupport, animate;

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};


function init(){
  //init data
  var json = {
      'label': [label_a],
      'values': [
      {
        'label': label0,
        'values': [value0]
      }, 
      {
        'label': label1,
        'values': [value1]
      }, 
      {
        'label': label2,
        'values': [value2]
      }, 
      {
        'label': label3,
        'values': [value3]
      }, 
      {
        'label': label4,
        'values': [value4]
      }, 
      {
        'label': label5,
        'values': [value5]
      },
      {
        'label': label6,
        'values': [value6]
      }, 
      {
        'label': label7,
        'values': [value7]
      },
      {
        'label': label8,
        'values': [value8]
      }/*, 
      {
        'label': label9,
        'values': [value9]
      }*/]
      
  };
  //end
/*
  var json2 = {
      'values': [
      {
        'label': 'date A',
        'values': [10, 40, 15, 7]
      }, 
      {
        'label': 'date B',
        'values': [30, 40, 45, 9]
      }, 
      {
        'label': 'date D',
        'values': [55, 30, 34, 26]
      }, 
      {
        'label': 'date C',
        'values': [26, 40, 85, 28]
      }]
      
  };
*/
    //init BarChart
    var barChart = new $jit.BarChart({
      //id of the visualization container
      injectInto: 'infovis',
      //whether to add animations
      animate: true,
      //horizontal or vertical barcharts
      orientation: 'vertical',
      //bars separation
      barsOffset: 20,
      //visualization offset
      Margin: {
        top:5,
        left: 5,
        right: 7,
        bottom:70
      },
      //labels offset position
      labelOffset: 5,
      //bars style
      type: useGradients? 'stacked:gradient' : 'stacked',
      //whether to show the aggregation of the values
      showAggregates:true,
      //whether to show the labels for the bars
      showLabels:true,
      //labels style
      Label: {
        type: 'HTML', //Native or HTML
        size: 13,
        family: 'Arial',
        color: 'white'
      },
      //add tooltips
      Tips: {
        enable: false,
        onShow: function(tip, elem) {
          tip.innerHTML = "<b>" + elem.name + "</b>: " + elem.value;
        }
      }
    });
    //load JSON data.
    barChart.loadJSON(json);
    //end
    var list = $jit.id('id-list'),
        button = $jit.id('update'),
        orn = $jit.id('switch-orientation');
    //update json on click 'Update Data'
    $jit.util.addEvent(button, 'click', function() {
      var util = $jit.util;
      if(util.hasClass(button, 'gray')) return;
      util.removeClass(button, 'white');
      util.addClass(button, 'gray');
      barChart.updateJSON(json2);
    });
    //dynamically add legend to list
    var legend = barChart.getLegend(),
        listItems = [];
    for(var name in legend) {
      listItems.push('<div class=\'query-color\' style=\'background-color:'
          + legend[name] +'\'>&nbsp;</div>' + name);
    }
    list.innerHTML = '<li>' + listItems.join('</li><li>') + '</li>';
}
</script>
