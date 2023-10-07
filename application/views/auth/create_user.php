<?php $this->load->view('templates/head'); ?>
      <link href="<?= base_url() ?>assets/bootstrap/sign-in/signin.css" rel="stylesheet">
<body class="body-register font">

      <main class="align-center">

            <div class="text-center">
            
                  <h1 class="h1"><?php echo lang('create_user_heading');?></h1>
                  <div><?php echo lang('create_user_subheading');?></div>

                  <div id="infoMessage"><?php echo $message;?></div>

                  <?php echo form_open("auth/create_user", '', '', 'form');?>

                  <div class="first-secound-name">
                  
                        <div>
                              <?php echo lang('create_user_fname_label', 'first_name');?>
                              <div class="form-floating">
                                    <?php echo form_input($first_name, '', '', 'form-control input');?>
                              </div>

                        </div>

                        <div>
                              <?php echo lang('create_user_lname_label', 'last_name');?>
                              <div class="form-floating">
                                    <?php echo form_input($last_name, '', '', 'form-control input');?>
                              </div>

                        </div>

                  </div>
                  
                  <div>

                        <label id="username">Nome de usuário:
                        <div class="form-floating">
                              <?php echo form_input($username, '', '', 'form-control input');?>
                        </div>

                  </div>
            
                  <?php
                        if($identity_column!=='email') {
                              echo '<div>';
                              echo lang('create_user_identity_label', 'identity');
                              echo '<br />';
                              echo form_error('identity');
                              echo form_input($identity);
                              echo '</div>';
                        }
                  ?>

                  <div class="first-secound-name">
                  
                        <div>

                              <?php echo lang('create_user_email_label', 'email');?>
                              <div class="form-floating">
                                    <?php echo form_input($email, '', '', 'form-control input');?>
                              </div>

                        </div>

                        <div>
                        
                              <?php echo lang('create_user_phone_label', 'phone');?>
                              <div class="form-floating">
                                    <?php echo form_input($phone, '', '', 'form-control input');?>
                              </div>

                        </div>

                  </div>

                  <div class="first-secound-name">
                  
                        <div>

                              <?php echo lang('create_user_password_label', 'password');?>
                              <div class="form-floating">
                                    <?php echo form_input($password, '', '', 'form-control input');?>
                              </div>

                        </div>

                        <div>
                        
                              <?php echo lang('create_user_password_confirm_label', 'password_confirm');?>
                              <div class="form-floating">
                                    <?php echo form_input($password_confirm, '', '', 'form-control input');?>
                              </div>

                        </div>

                  </div>

                  <p><?php echo form_submit('submit', lang('create_user_submit_btn'), '', 'w-100 btn btn-lg btn-primary');?></p>

                  <?php echo form_close();?>

                  <div class="register-espace text-center">
                        Já possui conta? <a href="<?= base_url() ?>auth/login">Entre aqui</a>
                  </div>
                  <div class="register-espace text-center">
                        <p class="mt-5 mb-3 text-muted">© 2017–2022</p>
                  </div>
            </div>

      </main>

<?php $this->load->view('templates/scripts'); ?>