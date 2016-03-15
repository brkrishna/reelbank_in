<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('gsm_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($gsm->id) ? $gsm->id : '';

?>
<div class='admin-box'>
    <h3>GSM</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('gms') ? ' error' : ''; ?>">
                <?php echo form_label(lang('gsm_field_gms') . lang('bf_form_label_required'), 'gms', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='gms' type='text' required='required' name='gms' maxlength='255' value="<?php echo set_value('gms', isset($gsm->gms) ? $gsm->gms : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('gms'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('gsm_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/gsm', lang('gsm_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>