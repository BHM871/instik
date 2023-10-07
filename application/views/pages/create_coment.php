<?php $this->load->view('templates/head'); ?>
    <link href="<?= base_url() ?>assets/bootstrap/sign-in/signin.css" rel="stylesheet">

    <body class="login-background font">
        <main class="align-center">

            <?php echo form_open((base_url().'pages/create_coment'), [], [], 'form'); ?>

                <div class="text-center"><h1 class="h3 mb-3 fw-normal max-width">Adicionar um comentário</h1></div>
                
                <div class="container-post">

                    <div class="container-post-profile">
            
                        <img class="photo-profile" alt="Foto de perfil" src="https://th.bing.com/th/id/OIP.AFu21pNJplyfKdPZNvqcdwHaHa?pid=ImgDet&rs=1">
                        <label class="post-username">@<?= $coment['username'] ?></label>
          
                    </div>

                    <div>

                        <p class="description"><?= $coment['texto'] ?></p>

                    </div>

                    <div class="container-coment">

                        <p>Comentários</p>

                        <div class="coment">

                            <p class="coment-username">@<?= $user->username ?></p>
                            <label id="name">Diga o que está pensando</label>
                            <div class="form-floating">
                                <textarea type="text" class="form-control input" id="floatingPost" placeholder="Texto do comentário" name="texto" required></textarea>
                                <input class="coment-post-id" type="number" value="<?= $coment['id'] ?>" name="id_post">
                            </div>

                        </div>

                    </div>

                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Adicionar</button>

            </form>
                
            <div class="back" style="margin: 0px; animation-name: none;">
                <a class="back" style="margin: 0px; animation-name: none;" href="<?= base_url() ?>pages/feed">Voltar</a>
            </div>

            <div class="register-espace text-center">
                <p class="mt-5 mb-3 text-muted">© 2017–2022</p>
            </div>

        </main>

        <?php $this->load->view('templates/scripts'); ?>