<div class="action-taken clearingfix">
	<?php if (!$action_taken && $actionable == 1) { ?>
    <div id="actionable-badge">
      <?php echo Kohana::lang('actionable.action_needed');?>
    </div>
	<?php }; ?>
  <?php if (!$action_taken && $actionable == 2) { ?>
    <div id="action-urgent-badge">
      <?php echo Kohana::lang('actionable.action_urgent');?>
    </div>
	<?php }; ?>
  <?php if ($action_taken == 1) { ?>
    <div id="action-taken-badge">
      <?php echo Kohana::lang('actionable.action_taken');?>
    </div>
	<?php }; ?>
	<?php if ($action_taken == 2) { ?>
    <div id="action-resolved-badge">
      <?php echo Kohana::lang('actionable.resolved');?>
    </div>
	<?php }; ?>
	<?php if ($action_date) { ?>
		<div id="action-date">
		<strong><?php echo Kohana::lang('actionable.action_date');?>: </strong><?php echo $action_date; ?>
	</div><br />
	<?php }; ?>
  <?php if ($action_summary) { ?>
		<div id="action-summary">
		<strong><?php echo Kohana::lang('actionable.summary');?>: </strong><?php echo $action_summary; ?>
		</div><br />
	<?php }; ?>
		<?php if ($resolution_date) { ?>
		<div id="resolution-date">
		<strong><?php echo Kohana::lang('actionable.resolution_date');?>: </strong><?php echo $resolution_date; ?>
		</div>
	<?php }; ?>
  <?php if ($resolution_summary) { ?>
		<div id="resolution-summary">
		<strong><?php echo Kohana::lang('actionable.summary');?>: </strong><?php echo $resolution_summary; ?>
		</div>
	<?php }; ?>
</div>