<?php

require_once 'model/reserva.dao.class.php';

class ReservasController {
    
    private $reservasService = NULL;
    
    public function redirect($location){
        header('Location: '.$location."?op=Reservas");
    }
    
    public function handleRequest(){
        $acao = isset($_GET['ac'])?$_GET['ac']:NULL;

        try {
            if (!$acao || $acao == 'listar') {
                $this->listaReservas();
            }elseif($acao == 'novo' || $acao == 'editar') {
                $this->saveReservas();
            }elseif ( $acao == 'excluir') {

                $this->deleteReservas();
            }elseif($acao == 'exibir') {
                $this->showReservas();
            }else{
                $this->showError("Página não encontrada", "Página para a ação '".$acao."' não foi encontrada!");
            }
        }catch(Exception $e) {
            $this->showError("Application error", $e->getMessage());
        }
    }
    
    public function listaReservas(){
        $ordenar = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        $reservas = Reserva::getAll($ordenar);
        include 'view/reservas.php';
    }

    public function saveReservas(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if($id==NULL){
            $title = 'Nova reserva';
            $reserva = new Reserva();            
        }else{
            $title = 'Editar reserva';
            $reserva = new Reserva($id);
        }
        $salas = Sala::getAll("idsala");
        $errors = array();

        if (isset($_POST['form-submitted'])) {
            $reserva->setUsuario($_SESSION['idusuario']);
            $reserva->setSala(     isset($_POST['idsala']      ) ? $_POST['idsala']      : NULL);
            $reserva->setDescricao(isset($_POST['descricao'] ) ? $_POST['descricao'] : NULL);
            $reserva->setDataIni(    isset($_POST['dataini']     ) ? $_POST['dataini'] : NULL);
            $reserva->setDataFim(date('Y-m-d H:i:s', strtotime($_POST['dataini'])+60*60));

            try {
                $reserva->verificaReserva();
                $reserva->save();
                $this->redirect('index.php');
                return;
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
        
        include 'view/reserva-form.php';
    }
    
    public function deleteReservas() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if (!$id) {
            throw new Exception('Internal error.');
        }
        try{
            $reserva = new Reserva($id);
            $reserva->erase();
        }catch(Exception $e){
            throw new Exception('Reseva não encontrada.');
        }

        $this->redirect('index.php');
    }
    
    public function showReservas() {

        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        try{
            $reserva = new Reserva($id);
        }catch(Exception $e){
            throw new Exception('Reserva não encontrada.');
        }

        include 'view/reserva.php';
    }
    
    public function showError($title, $message) {
        include 'view/error.php';
    }
    
}
?>
