<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('profile_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($profile->id) ? $profile->id : '';

?>
<div class='admin-box'>
    <h3><?php e(lang('profile_module_name')); ?></h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_name') . lang('bf_form_label_required'), 'name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='name' type='text' required='required' name='name' maxlength='255' value="<?php echo set_value('name', isset($profile->name) ? $profile->name : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('name'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('contact') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_contact') . lang('bf_form_label_required'), 'contact', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='contact' type='text' required='required' name='contact' maxlength='255' value="<?php echo set_value('contact', isset($profile->contact) ? $profile->contact : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('contact'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('phone') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_phone') . lang('bf_form_label_required'), 'phone', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='phone' type='text' required='required' name='phone' maxlength='255' value="<?php echo set_value('phone', isset($profile->phone) ? $profile->phone : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('phone'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('website') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_website'), 'website', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='website' type='text' name='website' maxlength='255' value="<?php echo set_value('website', isset($profile->website) ? $profile->website : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('website'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('email') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_email'), 'email', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='email' type='text' name='email' maxlength='255' value="<?php echo set_value('email', isset($profile->email) ? $profile->email : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('email'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('pan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_pan'), 'pan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='pan' type='text' name='pan' maxlength='50' value="<?php echo set_value('pan', isset($profile->pan) ? $profile->pan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('pan'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('tin') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_tin'), 'tin', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='tin' type='text' name='tin' maxlength='255' value="<?php echo set_value('tin', isset($profile->tin) ? $profile->tin : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tin'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('excise_nbr') ? ' error' : ''; ?>">
                <?php echo form_label(lang('profile_field_excise_nbr'), 'excise_nbr', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='excise_nbr' type='text' name='excise_nbr' maxlength='255' value="<?php echo set_value('excise_nbr', isset($profile->excise_nbr) ? $profile->excise_nbr : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('excise_nbr'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('profile_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/profile', lang('profile_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>