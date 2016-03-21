<?php

$num_columns	= 14;
$can_delete	= $this->auth->has_permission('Items.Content.Delete');
$can_edit		= $this->auth->has_permission('Items.Content.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class='admin-box'>
	<h3>
		<?php echo 'My ' . lang('items_area_title'); ?>
		<?php echo anchor(SITE_AREA . '/content/items/create', 'Add', array('class'=>'pull-right')); ?>
	</h3>
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class='table table-striped'>
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th class='column-check'><input class='check-all' type='checkbox' /></th>
					<?php endif;?>
					<?php if(isset($current_user) && $current_user->role_id != 4) : ?>
						<th><?php echo lang('items_field_profile'); ?></th>		
					<?php endif; ?>			
					<th><?php echo lang('items_field_strength'); ?></th>
					<th><?php echo lang('items_field_gsm'); ?></th>
					<th><?php echo lang('items_field_decal'); ?></th>
					<th><?php echo lang('items_field_weight'); ?></th>
					<th><?php echo lang('items_field_type'); ?></th>
					<th><?php echo lang('items_field_mill_name'); ?></th>
					<th><?php echo lang('items_field_condition'); ?></th>
					<th><?php echo lang('items_field_qty'); ?></th>
					<th><?php echo lang('items_field_remarks'); ?></th>
					<?php if(isset($current_user) && $current_user->role_id != 4) : ?>
						<th><?php echo lang('items_column_deleted'); ?></th>
						<th><?php echo lang('items_column_created'); ?></th>
					<?php endif; ?>			
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'>
						<?php echo lang('bf_with_selected'); ?>
						<input type='submit' name='delete' id='delete-me' class='btn btn-danger' value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('items_delete_confirm'))); ?>')" />
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $record) :
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class='column-check'><input type='checkbox' name='checked[]' value='<?php echo $record->id; ?>' /></td>
					<td><?php e($profile_select[$record->profile]); ?></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/content/items/edit/' . $record->id, '<span class="icon-pencil"></span> ' .  $bursting_strength_select[$record->strength]); ?></td>
				<?php else : ?>
					<td><?php e($bursting_strength_select[$record->strength]); ?></td>
				<?php endif; ?>
					<td><?php e($gsm_select[$record->gsm]); ?></td>
					<td><?php e($record->decal); ?></td>
					<td><?php e($record->weight); ?></td>
					<td><?php e($specific_type_select[$record->type]); ?></td>
					<td><?php e($record->mill_name); ?></td>
					<td><?php e($condition_select[$record->condition]); ?></td>
					<td><?php e($record->qty); ?></td>
					<td><?php e($record->remarks); ?></td>
					<?php if(isset($current_user) && $current_user->role_id != 4) : ?>
						<td><?php echo $record->deleted > 0 ? lang('items_true') : lang('items_false'); ?></td>
						<td><?php e($record->created_on); ?></td>
					<?php endif; ?>			

				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'><?php echo lang('items_records_empty'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
    echo form_close();
    
    echo $this->pagination->create_links();
    ?>
</div>