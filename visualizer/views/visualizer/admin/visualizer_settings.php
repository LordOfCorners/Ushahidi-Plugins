<table style="width: 630px;" class="my_table">
	<tr>
		<td>
			<span class="big_blue_span"><?php echo Kohana::lang('ui_main.step');?> 1:</span>
		</td>
		<td>
			<h4 class="fix">Enter information for your Bar Graph.</h4>
			<div class="row">
				<h4>Bar Graph Title</h4>
				<?php print form::input('bar_graph_title', $form['bar_graph_title'], ' class="text title_2"'); ?>
			</div>
			<div class="row">
				<h4>Bar Graph Description</h4>
				<?php print form::input('bar_graph_description', $form['bar_graph_description'], ' class="text title_2"'); ?>
			</div>
			<div class="row">
				<h4>Label A Title</h4>
				<?php print form::input('label_a', $form['label_a'], ' class="text title_2"'); ?>
			</div>
		</td>
	</tr>	
							
</table>