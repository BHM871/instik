<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    private function crud() : Grocery_CRUD {

        if (!$this->ion_auth->is_admin()) {
            if($this->ion_auth->logged_in()) {
                redirect(base_url().'pages/feed');
            } else {
                redirect(base_url().'auth/login');
            }
        }

        $crud = new Grocery_CRUD;
        $crud->set_language('pt-br.portuguese');
        return $crud;
    }

    private function output($crud, $title) {
        $render = $crud->render();
        $render = (array)$render;

        $output['data'] = $render;
        $output['title'] = $title;

        $this->load->view('example', $output);
    }

    public function usuarios() {

        $crud = $this->crud();

        $crud->set_table('users');
        $crud->set_subject('Usuário');

        $crud->display_as('created_on', 'Data de criação');
        $crud->display_as('last_login', 'Data do último login');
        $crud->columns([
            'id',
            'first_name',
            'email',
            'password',
            'created_on',
            'last_login',
        ]);

        $crud->edit_fields([
            'first_name',
            'email',
            'password',
            'last_login'
        ]);
        $crud->add_fields([
            'first_name',
            'email',
            'password',
            'created_on',
            'last_login',
        ]);

        $crud->set_rules('first_name', 'Nome', 'required', ['required' => 'Nome é obrigatório']);
        $crud->set_rules('email', 'email', 'required|valid_email', ['required' => 'email é obrigatório', 'valid_email' => 'Preencha com um email válido']);
        $crud->set_rules('password', 'Senha', 'required', ['required' => 'Coloque uma senha']);
        $crud->set_rules('last_login', 'Data do último login', 'required');
        
        $this->output($crud, 'Admin - Usuários');
    }

    public function perfis() {

        $crud = $this->crud();
        
        $crud->set_table('profile');
        $crud->set_subject('Perfil');

        $crud->set_relation('id_user', 'users', 'email');

        $crud->display_as('foto', 'Imagem perfil');
        $crud->display_as('data_nascimeto', 'Data de Nascimento');
        $crud->display_as('numero_segui', 'Seguidores');
        $crud->display_as('numero_publi', 'Posts');
        $crud->display_as('bio', 'Biografia');
        $crud->display_as('id_user', 'Usuário');
        $crud->columns([
            'id',
            'nome',
            'foto',
            'telefone',
            'data_nascimento',
            'bio',
            'numero_segui',
            'numero_publi',
            'id_user'
        ]);

        $crud->set_field_upload('foto', 'assets/uploads/files/images');
        $crud->edit_fields([
            'nome',
            'foto',
            'telefone',
            'bio',
            'numero_segui',
            'numer_publi'
        ]);
        $crud->add_fields([
            'nome',
            'foto',
            'telefone',
            'data_nascimento',
            'bio',
            'numero_segui',
            'numero_publi',
            'id_user'
        ]);

        $crud->set_rules('nome', 'Nome', 'required');
        $crud->set_rules('data_nascimento', 'Data de nascimento', 'required');
        $crud->set_rules('bio', 'Bioagrafia', 'required');
        $crud->set_rules('numero_seguidores', 'Seguidores', 'required');
        $crud->set_rules('numer_publicacao', 'Posts', 'required');
        $crud->set_rules('id_user', 'Usuário', 'required');

        $this->output($crud, 'Admin - Perfis');
    }

    public function publicacoes() {
    
        $crud = $this->crud();

        $crud->set_table('publication');
        $crud->set_subject('Publicação');

        $crud->set_relation('id_perfil', 'profile', 'nome');

        $crud->display_as('data_criacao', 'Data de criação');
        $crud->display_as('id_perfil', 'Meu profile');
        $crud->columns([
            'id',
            'titulo',
            'texto',
            'data_criacao',
            'id_perfil'
        ]);

        $crud->edit_fields([
            'titulo',
            'texto',
            'id_perfil'
        ]);
        $crud->add_fields([
            'titulo',
            'texto',
            'data_criacao',
            'id_perfil'
        ]);

        $crud->set_rules('texto', 'Conteúdo', 'required', ['required' => 'Você precisa inserir um conteúdo para publicar']);
        $crud->set_rules('data_criacao', 'Data de criação', 'required');
        $crud->set_rules('id_perfil', 'profile', 'required');

        $this->output($crud, 'Admin - Publicações');
    }

    public function comentarios() {

        $crud = $this->crud();
        
        $crud->set_table('coment');
        $crud->set_subject('Comentário');

        $crud->set_relation('id_publi', 'publication', 'titulo');
        $crud->set_relation('id_perfil', 'profile', 'nome');

        $crud->display_as('data_criacao', 'Data de criação');
        $crud->display_as('id_publi', 'Publicação');
        $crud->display_as('id_perfil', 'Meu profile');
        $crud->columns([
            'id',
            'texto',
            'data_criacao',
            'id_publi',
            'id_perfil'
        ]);

        $crud->edit_fields([
            'texto',
            'id_publi',
            'id_perfil'
        ]);
        $crud->add_fields([
            'texto',
            'data_criacao',
            'id_publi',
            'id_perfil'
        ]);

        $crud->set_rules('texto', 'Texto', 'required');
        $crud->set_rules('data_criacao', 'Data de criação', 'required');
        $crud->set_rules('id_publi', 'Publicação', 'required');
        $crud->set_rules('id_perfil', 'profile', 'required');
        
        $this->output($crud, 'Admin - Comentários');
    }

    public function seguidores() {
        
        $crud = $this->crud();

        $crud->set_table('followers');
        $crud->set_subject('Seguidor');

        $crud->set_relation('id_user', 'users', 'first_name');
        $crud->set_relation('id_perfil', 'profile', 'nome');

        $crud->display_as('data', 'Desde');
        $crud->display_as('id_user', 'Seguindo');
        $crud->display_as('id_perfil', 'Meu profile');
        $crud->columns([
            'id',
            'id_user',
            'data',
            'id_perfil'
        ]);
        
        $crud->edit_fields([
            'data',
            'id_user',
            'id_perfil'
        ]);
        $crud->add_fields([
            'data',
            'id_user',
            'id_perfil'
        ]);

        $crud->set_rules('data', 'Seguindo desde', 'required');
        $crud->set_rules('id_user', 'Usuário', 'required');
        $crud->set_rules('id_perfil', 'profile', 'required');

        $this->output($crud, 'Admin - Seguidores');
    }

}