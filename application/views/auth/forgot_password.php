<?php $this->load->view('templates/head'); ?>
      <link href="<?= base_url() ?>assets/bootstrap/sign-in/signin.css" rel="stylesheet">
<body class="login-background font">

      <main class="align-center">

            <div class="text-center">
                  
                  <h1><?php echo lang('forgot_password_heading');?></h1>
                  <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

                  <div id="infoMessage"><?php echo $message;?></div>

                  <?php echo form_open("auth/forgot_password", '', '', 'form');?>

                        <label for="identity"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label> <br />
                        <div class="form-floating">
                              <?php echo form_input($identity, '', '', 'form-control input');?>
                        </div>

                        <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'), '', 'w-100 btn btn-lg btn-primary');?></p>

                  <?php echo form_close();?>

            </div>

      </main>

<?php $this->load->view('templates/scripts'); ?>

