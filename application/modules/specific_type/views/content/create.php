<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('specific_type_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($specific_type->id) ? $specific_type->id : '';

?>
<div class='admin-box'>
    <h3>Specific Type</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('type') ? ' error' : ''; ?>">
                <?php echo form_label(lang('specific_type_field_type') . lang('bf_form_label_required'), 'type', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='type' type='text' required='required' name='type' maxlength='255' value="<?php echo set_value('type', isset($specific_type->type) ? $specific_type->type : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('type'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('specific_type_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/specific_type', lang('specific_type_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>