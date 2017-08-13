<?php

require_once 'model/sala.dao.class.php';

class QuartosController {
    
    public function redirect($location){
        header('Location: '.$location."?op=Quartos");
    }
    
    public function handleRequest(){
        $action = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if (!$action || $action == 'listar') {
                $this->listQuartos();
            }elseif($action == 'novo' || $action == 'editar') {
                $this->saveQuarto();
            }elseif ( $action == 'excluir') {

                $this->deleteQuarto();
            }elseif($action == 'exibir') {
                $this->showQuarto();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$action."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function listQuartos(){
        $orderBy = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $quartos = Sala::getAll($orderBy);
        include 'view/quartos.php';
    }

    public function saveQuarto(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if($id==NULL){
            $title = 'Nova sala';
            $quarto = new Sala();            
        }else{
            $title = 'Editar sala';
            $quarto = new Sala($id);
        }

        $errors = array();

        if (isset($_POST['form-submitted'])) {
            $quarto->setNome(    isset($_POST['nome']    ) ? $_POST['nome']     : NULL);
            $quarto->setDescricao(isset($_POST['descricao']) ? $_POST['descricao'] : NULL);

            try {
                $quarto->save();
                $this->redirect('index.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            }
        }
        
        include 'view/quarto-form.php';
    }
    
    public function deleteQuarto() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        try{
            $quarto = new Sala($id);
            $quarto->erase();
        }catch(Exception $e){
            throw new Exception('Sala não encontrado.');
        }

        $this->redirect('index.php');
    }
    
    public function showQuarto() {

        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        try{
            $quarto = new Quarto($id);
        }catch(Exception $e){
            throw new Exception('Sala não encontrado.');
        }

        include 'view/quarto.php';
    }
    
    public function showError($title, $message) {
        include 'view/error.php';
    }
    
}
?>
