<?php
class Geral_model extends CI_Model {

    public function set_news_profile() {

        $user = $this->ion_auth->user()->row();

        $profiles = $this->db->get('profile')->result();
        $exist = false;

        foreach ($profiles as $profile) {
            if ($profile->id_user === $user->id) {
                $exist = true;
            }
        }

        if (!$exist) {
            $data = array(
                'nome' => ($user->first_name.' '.$user->last_name),
                'data_criacao' => $user->created_on,
                'telefone' => $user->phone,
                'id_user' => $user->id
            );
    
            $this->db->insert('profile', $data);
        }

    }

    public function set_news_post() {

        $user = $this->ion_auth_model->user()->row();

        $profiles = $this->db->get('profile')->result();
        $exist = false;

        foreach ($profiles as $profile) {
            if ($profile->id_user == $user->id) {
                $exist = true;
            }
        }

        if ($exist) {
            $slug = url_title($user->username, 'dash', TRUE);

            $data = array(
                'slug' => $slug,
                'nome' => $user->username,
                'texto' => $this->input->post('texto'),
                'id_perfil' => $profile->id
            );

            return $this->db->insert('publication', $data);
        }
    }

    public function set_news_coment() {
        $id_post = $this->input->post('id_post');

        $user = $this->ion_auth_model->user()->row();
        $perfis = $this->db->get('profile')->result();
        $perfil = [];
        foreach ($perfis as $perf) {
            if ($perf->id_user === $user->id) {
                $perfil = (array)$perf;
            }
        }

        $data = array(
            'texto' => $this->input->post('texto'),
            'id_post' => $id_post,
            'id_perfil' => $perfil['id']
        );

        return $this->db->insert('coment', $data);

    }

    public function get_posts() {
        $postsO = $this->inverse_array('publication');
        $posts = [];

        foreach ($postsO as $post) {
            $coments = $this->db->where('coment.id_post', $post->id);
            
            $comentsF = [];
            foreach ($coments as $com) {
                $perfil = $this->search('profile', $com->id_perfil);
                $user = $this->search('users', $perfil->id_user);

                $comentsA = array(
                    'id' => $com->id,
                    'username' => $user->username,
                    'texto' => $com->texto
                );

                $comentsF[$comentsA['id']] = $comentsA;
            }

            $posts[$post->id] = array(
                'id' => $post->id,
                'username'=> $post->nome,
                'texto' => $post->texto,
                'coments' => $comentsF
            );

        }

        return $posts;
    }

    public function get_post() {
        $posts = $this->db->get('publication')->result();
        $post = [];
        $output['id'] = $this->input->post('id_post');

        foreach ($posts as $pos) {
            if ($pos->id == $this->input->post('id_post')) {
                $post = (array)$pos;
                $output['texto'] = $post['texto'];
            }
        }

        $perfil = $this->search('profile', $post['id_perfil']);
        $user = $this->search('users', $perfil->id_user);

        $output['username'] = $user->username;
        
        return $output;
    }
    

    private function inverse_array(String $str = null) {
        $array = $this->db->get($str)->result();
        $array = (array)$array;
        $output = array_reverse($array);
        return $output;
    }

    private function search($table, $key) {
        $var = $this->db->get($table)->result();
        foreach ($var as $value) {
            if ($value->id === $key) {
                return $value;
            }
        }
    }

}