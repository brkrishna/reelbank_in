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
    <h3>Item Details</h3>

        <div>
            <table class="table table-bordered table-condensed" style="width:50%">
                <tr><td><b>Bursting Strength</b></td><td><?php e($bursting_strength_select[$items->strength]); ?></td></tr>
                <tr><td><b>GSM</b></td><td><?php e($gsm_select[$items->gsm]); ?></td></tr>
                <tr><td><b>Decal</b></td><td><?php e($items->decal); ?></td></tr>
                <tr><td><b>Weight</b></td><td><?php e($items->weight); ?></td></tr>
                <tr><td><b>Specific Type</b></td><td><?php e($specific_type_select[$items->type]); ?></td></tr>
                <tr><td><b>Condition of Reel</b></td><td><?php e($condition_select[$items->condition]); ?></td></tr>
                <tr><td><b>Available Quantity</b></td><td><?php e($items->qty); ?></td></tr>
                <tr><td><b>Mill Name</b></td><td><?php e($items->mill_name); ?></td></tr>
                <tr><td><b>Remarks</b></td><td><?php e($items->remarks); ?></td></tr>
            </table>
        </div>
        <p><?php echo anchor(base_url(), 'Back to Items list'); ?></p>

</div>