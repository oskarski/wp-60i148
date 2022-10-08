<?php

use valueObjects\FormControl;

?>
<form id="<?php echo $form_id; ?>" class="<?php echo $form_class; ?>"
      action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post">
    <div class="row">
        <div class="col-md-6">
			<?php
			/**
			 * @var FormControl $contact_name_control
			 */
			echo $contact_name_control->render(); ?>
        </div>
        <div class="col-md-6">
			<?php
			/**
			 * @var FormControl $contact_email_control
			 */
			echo $contact_email_control->render(); ?>
        </div>
        <div class="col-md-12">
			<?php
			/**
			 * @var FormControl $contact_message_control
			 */
			echo $contact_message_control->render(); ?>
        </div>
        <div class="col-md-12">
			<?php
			/**
			 * @var FormControl $action_control
			 */
			echo $action_control->render(); ?>
			<?php
			/**
			 * @var FormControl $contact_submit_control
			 */
			echo $contact_submit_control->render(); ?>
        </div>
    </div>
</form>