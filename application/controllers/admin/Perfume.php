<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perfume extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		// $this->load->model('Categoria_model');
		// $this->load->library('form_validation');
		// $this->load->library('session');
		// $this->session->userdata('loggedin') == TRUE || redirect('user/login');
	}

	private function configurarUpload()
	{
		$config['upload_path']   = 'assets/admin/upload/'; // Configuração do diretório de upload
		$config['allowed_types'] = 'jpg|png|jpeg'; // Tipos de arquivo permitidos
		$config['overwrite']     = TRUE; // Substituir arquivo se já existir
		$config['max_size']      = 3072;  // Tamanho máximo do arquivo (em kilobytes)
		$config['detect_mime']   = TRUE;

		$this->load->library('upload', $config); // Carrega a biblioteca de upload com as configurações

		return $config;
	}

	public function index()
	{
		$dados['perfumes'] = $this->Perfume_model->buscaperfumes();

		$dados['subview'] = 'admin/perfume/listagem';

		$this->load->vars($dados);

		$this->load->view('admin/layout_main_admin');
	}

	public function novo()
	{
		// $dados["titulo"] = "Sistemas/Aplicativos";
		$dados['marcas'] = $this->Marca_model->buscaMarcas();

		$dados['subview'] = 'admin/perfume/insertEdit';

		// echo "<pre>";
		// print_r($dados);
		// exit;

		$this->load->vars($dados);

		$this->load->view('admin/layout_main_admin');
	}

	public function create()
	{
		$config = $this->configurarUpload(); // Configuração do upload

		// Verifica se o upload foi bem-sucedido
		if ($this->upload->do_upload('imagem')) {
			$file_data = $this->upload->data();
			$file_name = $file_data['file_name']; // Nome do arquivo após o upload
			log_message('debug', 'Upload bem-sucedido: ' . $file_name);
		} else {
			// Se o upload falhar, exibe mensagens de erro e redireciona
			$this->session->set_flashdata('error_msg', $this->upload->display_errors());
			log_message('error', 'Erro ao fazer o upload da imagem: ' . $this->upload->display_errors());
			// redirect(current_url());
			redirect('admin/perfume/novo');
		}

		// Dados do perfume
		$perfume = array(
			"nome" => $this->input->post("nome"),
			"descricao_info" => $this->input->post("descricao_info"),
			"marca" => $this->input->post("marca"),
			"tipo" => $this->input->post("tipo"),
			"volume" => $this->input->post("volume"),
			"preco" => $this->input->post("preco"),
			"estoque" => $this->input->post("estoque"),
			"imagem" => $file_name // Nome do arquivo após o upload
		);

		$this->Perfume_model->inserir($perfume); // Insere o perfume no banco de dados
		
		$this->session->set_flashdata('success', 'Perfume editado com sucesso');

		redirect('admin/perfume');
	}


	public function edit($id)
	{
		$dados['perfume'] = $this->Perfume_model->buscaperfumePorId($id);
		$dados['marcas'] = $this->Marca_model->buscaMarcas();

		$dados['subview'] = 'admin/perfume/insertEdit';

		$this->load->vars($dados);

		$this->load->view('admin/layout_main_admin');
	}


	public function update($id)
	{
		$perfume = array(
			"nome" => $this->input->post("nome"),
			"descricao_info" => $this->input->post("descricao_info"),
			"marca" => $this->input->post("marca"),
			"tipo" => $this->input->post("tipo"),
			"volume" => $this->input->post("volume"),
			"preco" => $this->input->post("preco"),
			"estoque" => $this->input->post("estoque")
		);

		// Verifica se o campo de imagem foi enviado
		if (!empty($_FILES['imagem']['name'])) {
			$config = $this->configurarUpload();
			$this->load->library('upload', $config);

			// Verifica se o upload foi bem-sucedido
			if ($this->upload->do_upload('imagem')) {
				$file_data = $this->upload->data();
				$file_name = $file_data['file_name'];
				$perfume['imagem'] = $file_name; // Atualiza o nome da imagem
			} else {
				// Se o upload falhar, imprime mensagem de erro e termina o script
				$this->session->set_flashdata('error_msg', 'Erro ao fazer o upload da imagem: ' . $this->upload->display_errors());
				redirect('admin/perfume/edit/' . $id);
			}
		}

		$this->Perfume_model->atualizar($id, $perfume);
		$this->session->set_flashdata('success', 'Perfume editado com sucesso');

		redirect('admin/perfume');
	}

	// $this->Perfume_model->inserir($perfume);
	// $this->Perfume_model->atualizar($id, $perfume);
	// $this->session->set_flashdata('msg', 'perfume editada com sucesso');
	// $this->session->set_flashdata('msg', 'perfume cadastrada com sucesso');
}
