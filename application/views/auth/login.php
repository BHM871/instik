<?php $this->load->view('templates/head'); ?>
  <link href="<?= base_url() ?>assets/bootstrap/sign-in/signin.css" rel="stylesheet">
<body class="login-background font">

  <main class="align-center">

    <div class="text-center">
      
      <h1><?php echo lang('login_heading');?></h1>
      <p><?php echo lang('login_subheading');?></p>

      <div id="infoMessage"><?php echo $message;?></div>

      <?php echo form_open("auth/login", '', '', 'form');?>

        <?php echo lang('login_identity_label', 'identity');?>
        <div class="form-floating">
          <?php echo form_input($identity, '', '', 'form-control input');?>
        </div>

        <?php echo lang('login_password_label', 'password');?>
        <div class="form-floating">
          <?php echo form_input($password, '', '', 'form-control input');?>
        </div>

        <p>
          <?php echo lang('login_remember_label', 'remember');?>
          <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
        </p>


        <p><?php echo form_submit('submit', lang('login_submit_btn'), '', 'w-100 btn btn-lg btn-primary');?></p>

      <?php echo form_close();?>

      <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>

      <div class="register-espace text-center">
        Não possui conta? <a href="<?= base_url() ?>auth/create_user">Registre-se</a>
      </div>
      <div class="register-espace text-center">
        <p class="mt-5 mb-3 text-muted">© 2017–2022</p>
      </div>
    </div>
  </main>
</body>
<?php $this->load->view('templates/scripts'); ?>