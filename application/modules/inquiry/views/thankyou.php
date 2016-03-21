<div class="row-fluid">
	<p>You have shown interest in the following item</p>
	<div>
		<table class="table table-bordered table-condensed" style="width:50%">
			<tr><td><b>Bursting Strength</b></td><td><?php e($bursting_strength_select[$item->strength]); ?></td></tr>
			<tr><td><b>GSM</b></td><td><?php e($gsm_select[$item->gsm]); ?></td></tr>
			<tr><td><b>Decal</b></td><td><?php e($item->decal); ?></td></tr>
			<tr><td><b>Specific Type</b></td><td><?php e($specific_type_select[$item->type]); ?></td></tr>
			<tr><td><b>Condition</b></td><td><?php e($condition_select[$item->condition]); ?></td></tr>
		</table>
	</div>
	<p>The reelbank team has been notified of this request and you will hear from them shortly</p>
	<p><?php echo anchor(base_url(), 'Back to Items list'); ?></p>
</div>