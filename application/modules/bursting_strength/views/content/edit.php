<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('bursting_strength_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($bursting_strength->id) ? $bursting_strength->id : '';

?>
<div class='admin-box'>
    <h3>Bursting Strength</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('strength') ? ' error' : ''; ?>">
                <?php echo form_label(lang('bursting_strength_field_strength') . lang('bf_form_label_required'), 'strength', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='strength' type='text' required='required' name='strength' maxlength='255' value="<?php echo set_value('strength', isset($bursting_strength->strength) ? $bursting_strength->strength : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('strength'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('bursting_strength_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/bursting_strength', lang('bursting_strength_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Bursting_Strength.Content.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('bursting_strength_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('bursting_strength_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>