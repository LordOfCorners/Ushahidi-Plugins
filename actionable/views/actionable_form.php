<div style="border-top: 2px solid gainsboro; border-bottom: 2px solid gainsboro; margin-top: 20px; padding: 0 10px 20px 10px; background-color: #eee;">
<!-- report is actionable -->
<div class="row">
	<h4>
		<?php echo Kohana::lang('actionable.actionable') ?>:
    <span><?php echo Kohana::lang('actionable.action_status_description'); ?></span>
  </h4>
    <?php print form::radio('actionable', '0', $actionable == 0); ?> <?php echo Kohana::lang('actionable.not_actionable') ?>
    <?php print form::radio('actionable', '1', $actionable == 1); ?> <?php echo Kohana::lang('actionable.actionable') ?>
		<?php print form::radio('actionable', '2', $actionable == 2); ?> <?php echo Kohana::lang('actionable.urgent') ?>
</div>
<!-- / report is actionable -->

<!-- report is acted on -->
<div class="row">
  <h4>
    <?php echo Kohana::lang('actionable.action_taken') ?>: <?php print form::radio('action_taken', '1', $action_taken == 1); ?> 
    <span><?php echo Kohana::lang('actionable.action_summary_description'); ?></span>
  </h4>
  <textarea name="action_summary" id="action_summary" style=" height: 60px;"><?php echo $action_summary; ?></textarea>
</div>
<!-- / report is acted on -->

<!-- legal case opened -->
<div class="row">
  <h4>
    <?php echo Kohana::lang('actionable.legal') ?>: <?php print form::radio('action_taken', '3', $action_taken == 3); ?> 
    <span><?php echo Kohana::lang('actionable.legal_summary_description'); ?></span>
  </h4>
  <textarea name="legal_summary" id="legal_summary" style=" height: 60px;"><?php echo $legal_summary; ?></textarea>
</div>
<!-- / legal case opened-->

<!-- report dropped -->
<div class="row">
  <h4>
    <?php echo Kohana::lang('actionable.dropped') ?>: <?php print form::radio('action_taken', '4', $action_taken == 4); ?> 
    <span><?php echo Kohana::lang('actionable.dropped_summary_description'); ?></span>
  </h4>
  <textarea name="dropped_summary" id="dropped_summary" style=" height: 60px;"><?php echo $dropped_summary; ?></textarea>
</div>
<!-- / legal case opened-->

<!-- report is resolved -->
<div class="row">
  <h4>
    <?php echo Kohana::lang('actionable.resolved') ?>: <?php print form::radio('action_taken', '2', $action_taken == 2); ?> 
    <span><?php echo Kohana::lang('actionable.resolution_summary_description'); ?></span>
  </h4>
  <textarea name="resolution_summary" id="resolution_summary" style=" height: 60px;"><?php echo $resolution_summary; ?></textarea>
</div>
<!-- / report is resolved -->

</div>