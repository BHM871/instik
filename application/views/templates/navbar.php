</head>
<body class="bg-light body-initial font"> 
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Instik</a>
        <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="<?= base_url() ?>pages/feed">Feed</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Notificações</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Perfil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Escolha a Conta</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Opções</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url() ?>pages/create_post">Adicionar publicação</a></li>
                <li><a class="dropdown-item" href="<?= base_url() ?>admin/usuarios">Opções de Administrador</a></li>
                <li><a class="dropdown-item" href="<?= base_url() ?>pages/about">Sobre</a></li>
                <li><a class="dropdown-item" href="<?= base_url() ?>auth/logout">Sair</a></li>
              </ul>
            </li>
          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Pesquisar</button>
          </form>
        </div>
      </div>
    </nav>