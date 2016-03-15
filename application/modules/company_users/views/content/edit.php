<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('company_users_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($company_users->id) ? $company_users->id : '';

?>
<div class='admin-box'>
    <h3>Company Users</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <?php // Change the values in this array to populate your dropdown as required
                $options = array(
                );
                echo form_dropdown(array('name' => 'profile', 'required' => 'required'), $options, set_value('profile', isset($company_users->profile) ? $company_users->profile : ''), lang('company_users_field_profile') . lang('bf_form_label_required'));
            ?>

            <?php // Change the values in this array to populate your dropdown as required
                $options = array(
                );
                echo form_dropdown(array('name' => 'user', 'required' => 'required'), $options, set_value('user', isset($company_users->user) ? $company_users->user : ''), lang('company_users_field_user') . lang('bf_form_label_required'));
            ?>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('company_users_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/company_users', lang('company_users_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Company_Users.Content.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('company_users_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('company_users_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>