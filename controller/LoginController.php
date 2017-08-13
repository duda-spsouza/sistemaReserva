<?php

require_once 'model/usuario.dao.class.php';

class LoginController {
    
    public function redirect($location){
        header('Location: '.$location);
    }
    
    public function handleRequest(){
        $acao = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if(!$acao) {
                $this->showLogin();
            }elseif ( $acao == 'logout') {
                $this->logout();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$acao."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }

    public function logout(){
        unset($_SESSION['idusuario ']);
        unset($_SESSION['loggedin']);
        session_destroy();
        $this->redirect('index.php');
        return;
    }
    
    public function showLogin(){

        $errors = array();

        if (isset($_POST['form-submitted'])) {
            try{
                $id = Usuario::isRegistered($_POST['email'],$_POST['password']);
            }catch(Excpetion $e){
                $errors = $e->getErrors();
                include 'view/login.php';
                return;
            }

            $usuario = new Usuario($id);
            $_SESSION['idusuario'] = $usuario->getId();
            $_SESSION['loggedin'] = true;
            $this->redirect('index.php');
            return;

        }
        
        include 'view/login.php';
    }

    public function showError($title, $message) {
        include 'view/error.php';
    }
}
?>
