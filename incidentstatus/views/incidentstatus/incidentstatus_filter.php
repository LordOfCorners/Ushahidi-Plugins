<!--
				<?php
				// Action::main_sidebar_post_filters - Add Items to the Entry Page after filters
				Event::run('ushahidi_action.main_sidebar_post_filters');
				?>
-->
<script type="text/javascript">
jQuery(function() {
$('.incidentstatus_filters li a').click(function() {
		var mediaType = parseFloat(this.id.replace('media_', '')) || 0;
		
		$('.incidentstatus_filters li a').attr('class', '');
		$(this).addClass('active');

		// Update the report filters
		map.updateReportFilters({k: mediaType});
		console.log("filter");
		
		return false;
	});
	});
</script>
<div id="incident-report-type-filter" class="incidentstatus_filters">
					<h3><?php echo Kohana::lang('incident_status.incident_status'); ?></h3>
						<ul>
							<li><a id="media_0" class="active" href="#"><span><?php echo Kohana::lang('incident_status.waiting'); ?></span></a></li>
							<li><a id="media_4" href="#"><span><?php echo Kohana::lang('incident_status.taken_on'); ?></span></a></li>
							<li><a id="media_1" href="#"><span><?php echo Kohana::lang('incident_status.resolved'); ?></span></a></li>
						</ul>
						<div class="floatbox">

							</div>
							<!-- / report type filters -->

<!--
											<?php
				// Action::main_sidebar_post_filters - Add Items to the Entry Page after filters
				Event::run('ushahidi_action.main_sidebar_post_filters');
				?>
-->

				</div>

				
