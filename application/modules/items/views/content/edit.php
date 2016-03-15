<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('items_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($items->id) ? $items->id : '';

?>
<div class='admin-box'>
    <h3>Items</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            
            <?php 
                echo form_dropdown(array('name' => 'profile', 'required' => 'required'), $profile_select, set_value('profile', isset($items->profile) ? $items->profile : ''), lang('items_field_profile') . lang('bf_form_label_required'));
            ?>

            <?php 
                echo form_dropdown(array('name' => 'strength', 'required' => 'required'), $bursting_strength_select, set_value('strength', isset($items->strength) ? $items->strength : ''), lang('items_field_strength') . lang('bf_form_label_required'));
            ?>

            <?php 
                echo form_dropdown(array('name' => 'gsm', 'required' => 'required'), $gsm_select, set_value('gsm', isset($items->gsm) ? $items->gsm : ''), lang('items_field_gsm') . lang('bf_form_label_required'));
            ?>
            
            <div class="control-group<?php echo form_error('decal') ? ' error' : ''; ?>">
                <?php echo form_label(lang('items_field_decal'), 'decal', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='decal' type='text' name='decal' maxlength='10' value="<?php echo set_value('decal', isset($items->decal) ? $items->decal : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('decal'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('weight') ? ' error' : ''; ?>">
                <?php echo form_label(lang('items_field_weight'), 'weight', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='weight' type='text' name='weight' maxlength='255' value="<?php echo set_value('weight', isset($items->weight) ? $items->weight : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('weight'); ?></span>
                </div>
            </div>

            <?php 
                echo form_dropdown(array('name' => 'type'), $specific_type_select, set_value('type', isset($items->type) ? $items->type : ''), lang('items_field_type'));
            ?>

            <div class="control-group<?php echo form_error('mill_name') ? ' error' : ''; ?>">
                <?php echo form_label(lang('items_field_mill_name'), 'mill_name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='mill_name' type='text' name='mill_name' maxlength='255' value="<?php echo set_value('mill_name', isset($items->mill_name) ? $items->mill_name : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('mill_name'); ?></span>
                </div>
            </div>

            <?php 
                echo form_dropdown(array('name' => 'condition'), $condition_select, set_value('condition', isset($items->condition) ? $items->condition : ''), lang('items_field_condition'));
            ?>

            <div class="control-group<?php echo form_error('qty') ? ' error' : ''; ?>">
                <?php echo form_label(lang('items_field_qty'), 'qty', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='qty' type='text' name='qty' maxlength='16' value="<?php echo set_value('qty', isset($items->qty) ? $items->qty : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('qty'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('orig_qty') ? ' error' : ''; ?>">
                <?php echo form_label(lang('items_field_orig_qty'), 'orig_qty', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='orig_qty' type='text' name='orig_qty' maxlength='16' value="<?php echo set_value('orig_qty', isset($items->orig_qty) ? $items->orig_qty : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('orig_qty'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('remarks') ? ' error' : ''; ?>">
                <?php echo form_label(lang('items_field_remarks'), 'remarks', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='remarks' type='text' name='remarks' maxlength='4000' value="<?php echo set_value('remarks', isset($items->remarks) ? $items->remarks : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('remarks'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('items_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/items', lang('items_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Items.Content.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('items_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('items_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>