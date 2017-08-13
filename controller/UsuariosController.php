<?php

require_once 'model/usuario.dao.class.php';

class UsuariosController {
    
    public function redirect($location){
        header('Location: '.$location."?op=Usuarios");
    }
    
    public function handleRequest(){
        $action = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if (!$action || $action == 'listar') {
                $this->listUsuarios();
            }elseif($action == 'novo' || $action == 'editar') {
                $this->saveUsuario();
            }elseif ( $action == 'excluir') {

                $this->deleteUsuario();
            }elseif($action == 'exibir') {
                $this->showUsuario();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$action."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function listUsuarios(){
        $orderBy = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $usuarios = Usuario::getAll($orderBy);
        include 'view/usuarios.php';
    }

    public function saveUsuario(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if($id==NULL){
            $title = 'Novo usuário';
            $usuario = new Usuario();            
        }else{
            $title = 'Editar usuário';
            $usuario = new Usuario($id);
        }

        $errors = array();

        if (isset($_POST['form-submitted'])) {
            $usuario->setNome(    isset($_POST['nome']    ) ? $_POST['nome']     : NULL);
            $usuario->setEmail(isset($_POST['email']) ? $_POST['email'] : NULL);

            if($usuario->getId()!=NULL){
                if($usuario->getHash() != Util::createHash($_POST['oldpassword'])){
                    $errors[] = "A senha anterior não corresponde.";
                    include 'view/usuario-form.php';
                    return;
                }
            }

            if($_POST['password'] != $_POST['passwordconf']){
                $errors[] = "A confirmação não corresponde.";
                include 'view/usuario-form.php';
                return;
            }
            
            $usuario->setPassword(isset($_POST['password']) ? $_POST['password'] : NULL);

            try {
                $usuario->save();
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        
        include 'view/usuario-form.php';
    }
    
    public function deleteUsuario() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        try{
            $usuario = new Usuario($id);
            $usuario->erase();
        }catch(Exception $e){
            throw new Exception('Usuário não encontrado.');
        }

        $this->redirect('index.php');
    }
    
    public function showUsuario() {

        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        try{
            $usuario = new Usuario($id);
        }catch(Exception $e){
            throw new Exception('Usuário não encontrado.');
        }

        include 'view/usuario.php';
    }
    
    public function showError($title, $message) {
        include 'view/error.php';
    }
    
}
?>
