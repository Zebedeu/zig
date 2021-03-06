<?php 
namespace App\Controllers;
use System\Controller\Controller;
use System\Post\Post;
use System\Get\Get;
use System\Session\Session;

use App\Models\Usuario;

class LoginController extends Controller
{
	protected $post;
	protected $get;
	protected $layout;

	public function __construct()
	{
		parent::__construct();
		$this->layout = 'login';

		$this->post = new Post();
		$this->get = new Get();
	}

	public function index()
	{
		$this->view('login/index', $this->layout);
	}

	public function logar()
	{
		if ($this->post->hasPost()) {
			$email = $this->post->data()->email;
			$password = $this->post->data()->password;

			$usuario = new Usuario();
			$dadosUsuario = $usuario->findBy('email', $email);
           
			if ($usuario->userExist(['email' => $email, 'password' => $password])) {
				
				Session::set('idUsuario', $dadosUsuario->id);
				Session::set('idPerfil', $dadosUsuario->id_perfil);
				Session::set('idPapel', $dadosUsuario->id_papel);
				Session::set('idCliente', $dadosUsuario->id_cliente);
				Session::set('nomeUsuario', $dadosUsuario->nome);
				Session::set('idSexoUsuario', $dadosUsuario->id_sexo);
				Session::set('emailUsuario', $dadosUsuario->email);
				Session::set('imagem', $dadosUsuario->imagem);

				return $this->get->redirectTo("home/index");
			}
            
            Session::flash('error', 'Usuário não encontrado!');
			return $this->get->redirectTo("login/index");
		}
	}

	public function logout()
	{
		Session::logout();
		return $this->get->redirectTo("login/index");
	}
}