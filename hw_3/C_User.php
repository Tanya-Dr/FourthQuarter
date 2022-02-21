<?php
// спагетти-код
class C_User extends C_Base{	
	private $user;

	public function __construct(){
		$this->user = new M_User();
	}

	public function action_login(){
		if($_SESSION['id']){						//
			header('location: index.php?c=User&act=profile');	// повторяющийся кусок кода
			exit();							//
		}
		$this->title = 'LOGIN';

		$this->contentBlock = 'v_login.tmpl';
		if($this->isPost()){
			$email = $this->checkData($_POST['email']);
			$pass = $this->checkData($_POST['pass']);
			$errText = $this->user->login($email,$pass);		
			if($errText == "success"){
				$this->needToRender = false;
				echo "ok";
			}else{
				$this->ajax = true;
				$this->content = ['errText' => $errText];
				$this->contentBlock = 'v_error.tmpl';
			}
		}		
	}

	public function action_signup(){
		if($_SESSION['id']){						//
			header('location: index.php?c=User&act=profile');	// повторяющийся кусок кода
			exit();							//
		}
		$this->title = 'SIGNUP';
		$this->contentBlock = 'v_signup.tmpl';
		if($this->isPost()){
			$email = $this->checkData($_POST['email']);
			$nickname = $this->checkData($_POST['nickname']);
			$pass = $this->checkData($_POST['pass']);
			$passConfirm = $this->checkData($_POST['passConfirm']);

			if($pass != $passConfirm){
				$errText = 'Incorrect password';
			}else{
				$errText = $this->user->signup($email,$pass,$nickname);
			}            		
			if($errText == "success"){
				$this->needToRender = false;
				echo "ok";
			}else{
				$this->ajax = true;
				$this->content = ['errText' => $errText];
				$this->contentBlock = 'v_error.tmpl';
			}
		}
	}

	public function action_profile(){
		if(!$_SESSION['id']){					//
			header('location: index.php?c=User&act=login');	// повторяющийся кусок кода
			exit();						//
		}
		$this->title = 'PROFILE';
		
		$data = $this->user->profile($_SESSION['id']);
		$this->content = $data;
		$this->contentBlock = 'v_profile.tmpl';
	}

	public function action_logout(){
		if($_SESSION['id']){
			$this->user->logout();
		}
		header('location: index.php?c=User&act=login');
	}	
}
