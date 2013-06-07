$('.incidentstatus_filters li a').click(function() {
		var mediaType = parseFloat(this.id.replace('media_', '')) || 0;
		
		$('.incidentstatus_filters li a').attr('class', '');
		$(this).addClass('active');

		// Update the report filters
		map.updateReportFilters({k: mediaType});
		
		return false;
	});