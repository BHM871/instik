<?php $this->load->view('templates/head'); ?>
    <link href="<?= base_url() ?>assets/bootstrap/offcanvas-navbar/offcanvas.css" rel="stylesheet">

    <?php $this->load->view('templates/navbar'); ?>
 
    <main class="container form container-feed">

      <div class="my-3 p-3 bg-body rounded shadow-sm feed">
          
        <?php
          if (count($posts) === 0){
            echo '<div class="text-center"><p>Não há publicações</p></div>';
          } else { 
            foreach ($posts as $post): ?>
              <div class="container-post">

                <div class="container-post-profile">

                  <img class="photo-profile" alt="Foto de perfil" src="https://th.bing.com/th/id/OIP.AFu21pNJplyfKdPZNvqcdwHaHa?pid=ImgDet&rs=1">
                  <p class="post-username">@<?= $post['username'] ?></p>

                </div>

                <div>

                  <p class="description"><?= $post['texto'] ?></p>

                </div>

                <div class="container-coment">

                  <p>Comentários</p>

                  <form class="coment-form" method="POST" action="<?= base_url() ?>pages/create_coment">
                    <input class="coment-post-id" type="text" value="<?= $post['id'] ?>" name="id_post">
                    <input class="coment-submit" type="submit" value="Adicionar">
                  </form>

                  <?php foreach ($post['coments'] as $coment): ?>

                    <div class="coment">

                      <p class="coment-username">@<?= $coment['username'] ?></p>
                      <p class="coment-text"><?= $coment['texto'] ?></p>

                    </div>

                  <?php endforeach; ?>
                </div>
              </div>

        <?php 
            endforeach;
          } 
        ?>

        <small class="d-block text-end mt-3">
          <a href="'.base_url().'pages/feed">Mais publicações</a>
        </small>
      </div>

      </div>
      
    </main>
    <?php $this->load->view('templates/scripts'); ?>