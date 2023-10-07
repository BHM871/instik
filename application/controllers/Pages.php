<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

    public function index() {
		$data['title'] = 'Instik - Initial Page';

		$this->load->view('pages/initial_page', $data);
	}

    public function feed() {
        
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url().'auth/login');
        }
        
        $data['title'] = 'Instik - Feed Page';

        $this->geral_model->set_news_profile();
        $data['posts'] = $this->geral_model->get_posts();

        $this->load->view('pages/feed', $data);
    }

    public function create_post() {

        if (!$this->ion_auth->logged_in()) {
            redirect(base_url().'auth/login');
        }

        $data['title'] = 'Instik - Add Post';

        $this->form_validation->set_rules('texto', 'Texto', 'required');

        if ($this->form_validation->run() === FALSE) {
            $userSession = $this->ion_auth_model->user()->row();

            $data['user'] = $userSession;

            $this->load->view('pages/create_post', $data);            
        }
        else {
            $this->geral_model->set_news_post();
            redirect(base_url().'pages/feed');
        }
        
    }

    public function create_coment() {

        if (!$this->ion_auth->logged_in()) {
            redirect(base_url().'auth/login');
        }

        $data['title'] = 'Instik - Add Coment';

        $this->form_validation->set_rules('texto', 'Texto', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['coment'] = $this->geral_model->get_post();
            $data['user'] = $this->ion_auth->user()->row();
    
            $this->load->view('pages/create_coment', $data);
        }
        else {
            $this->geral_model->set_news_coment();
            redirect(base_url().'pages/feed');
        }
    }

    public function about() {

        if (!$this->ion_auth->logged_in()) {
            redirect(base_url().'auth/login');
        }

        $data['title'] = "Instik - About Page";

        $this->load->view('pages/about', $data);
    }

}
