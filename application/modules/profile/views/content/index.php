<?php

$num_columns	= 11;
$can_delete	= $this->auth->has_permission('Profile.Content.Delete');
$can_edit		= $this->auth->has_permission('Profile.Content.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class='admin-box'>
	<h3>
		<?php echo lang('profile_area_title'); ?>
	</h3>
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class='table table-striped'>
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th class='column-check'><input class='check-all' type='checkbox' /></th>
					<?php endif;?>
					
					<th><?php echo lang('profile_field_name'); ?></th>
					<th><?php echo lang('profile_field_contact'); ?></th>
					<th><?php echo lang('profile_field_phone'); ?></th>
					<th><?php echo lang('profile_field_website'); ?></th>
					<th><?php echo lang('profile_field_email'); ?></th>
					<th><?php echo lang('profile_field_pan'); ?></th>
					<th><?php echo lang('profile_field_tin'); ?></th>
					<th><?php echo lang('profile_field_excise_nbr'); ?></th>
					<th><?php echo lang('profile_column_deleted'); ?></th>
					<th><?php echo lang('profile_column_created'); ?></th>
					<th><?php echo lang('profile_column_modified'); ?></th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'>
						<?php echo lang('bf_with_selected'); ?>
						<input type='submit' name='delete' id='delete-me' class='btn btn-danger' value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('profile_delete_confirm'))); ?>')" />
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
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/content/profile/edit/' . $record->id, '<span class="icon-pencil"></span> ' .  $record->name); ?></td>
				<?php else : ?>
					<td><?php e($record->name); ?></td>
				<?php endif; ?>
					<td><?php e($record->contact); ?></td>
					<td><?php e($record->phone); ?></td>
					<td><?php e($record->website); ?></td>
					<td><?php e($record->email); ?></td>
					<td><?php e($record->pan); ?></td>
					<td><?php e($record->tin); ?></td>
					<td><?php e($record->excise_nbr); ?></td>
					<td><?php echo $record->deleted > 0 ? lang('profile_true') : lang('profile_false'); ?></td>
					<td><?php e($record->created_on); ?></td>
					<td><?php e($record->modified_on); ?></td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'><?php echo lang('profile_records_empty'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
    echo form_close();
    
    echo $this->pagination->create_links();
    ?>
</div>