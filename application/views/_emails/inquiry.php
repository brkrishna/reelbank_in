<div class="row-fluid">
	<p> Dear <?php e($current_user->display_name . ','); ?>
	<br/><br/>
	You have enquired on behalf of 
	<br/><br/>
	<?php e('Company - ' . $buyer->name); ?>
	<br/>
	<?php e('Contact Name - ' . $buyer->contact); ?>
	<br/>
	<?php e('Contact Phone - ' . $buyer->phone); ?>
	<br/>
	<?php e('Contact Email - ' . $buyer->email); ?>
	<br/>
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
	
	Seller details: 
	<br/><br/>
	<?php e('Company - ' . $seller->name); ?>
	<br/>
	<?php e('Contact Name - ' . $seller->contact); ?>
	<br/>
	<?php e('Contact Phone - ' . $seller->phone); ?>
	<br/>
	<?php e('Contact Email - ' . $seller->email); ?>
	<br/><br/><br/>
	
</div>