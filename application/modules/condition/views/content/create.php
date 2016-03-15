<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('condition_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($condition->id) ? $condition->id : '';

?>
<div class='admin-box'>
    <h3>Condition</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('condition') ? ' error' : ''; ?>">
                <?php echo form_label(lang('condition_field_condition') . lang('bf_form_label_required'), 'condition', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='condition' type='text' required='required' name='condition' maxlength='255' value="<?php echo set_value('condition', isset($condition->condition) ? $condition->condition : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('condition'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('condition_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/condition', lang('condition_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>