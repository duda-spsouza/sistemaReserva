<?php
require_once "idao.interface.php";
require_once "usuario.dao.class.php";
require_once "sala.dao.class.php";
class Reserva implements IDao{
// de acordo com o bd
	private $id;//id reserva
	private $idusuario;//id do usuario 
	private $idsala;// id da sala
	private $usuario;//usuario
	private $sala;//sala
	private $dataini;//data inicio
	private $datafim;//data fim
	private $descricao;// descricao

    public function __construct($id = -1){
    	if($id!=-1){
    		$this->load($id);
    	}
    }

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function getUsuario(){
		if(!isset($this->usuario)){
			if(isset($idusuario)){
				$this->usuario = new Usuario($idusuario);
			}else{
				$this->usuario = new Usuario();
			}
		}
		return $this->usuario;
	}

	public function setUsuario($usuario){
		$this->usuario = $usuario;
		$this->idusuario = $this->usuario->getId();
	}

	public function getSala(){
		if(!isset($this->sala)){
			if(isset($idsala)){
				echo 'setou';
				$this->sala= new Sala($idsala);
			}else{
				$this->sala = new Sala();
			}
		}
		return $this->sala;
	}

	public function setSala($sala){
		$this->sala= $sala;
		$this->idsala = $this->sala->getId();
	}

	public function getDataIni(){
		return $this->dataini;
	}

	public function setDataIni($dataini){
		$this->dateini = $dataini;
	}

	public function getDataFim(){
		return $this->datafim;
	}

	public function setDataFim($datafim){
		$this->datafim = $datafim;
	}


	public function load($id){
		unset($this->usuario);
		unset($this->sala);

		$database = new Database();
		$database->query("SELECT idreserva, usuario_id, sala_id, descricao, date_ini, date_fim FROM reserva WHERE idreserva = :id");
		$database->bind(":id", $id);
		$cols = $database->single();

		if(!isset($cols["idreserva"])){
			throw new Exception("Reserva não encontrada.");
		}

		$this->id          = $cols["idreserva"];
		$this->usuario     = $cols["usuario_id"];
		$this->idsala      = $cols["sala_id"];
		$this->descricao   = utf8_decode($cols["descricao"]);
		$this->dateini     = $cols["date_ini"];
		$this->datefim     = $cols["date_fim"];
	}

	public function save(){
		$database = new Database();
		$database->beginTransaction();
		if(isset($this->id)){
			$database->query("UPDATE reserva SET usuario_id = :idusuario, sala_id = :idsala, descricao = :descricao, date_ini = :date_ini, date_fim = :date_fim WHERE idreserva = :id");
			$database->bind(":id",$this->id);
			$database->bind(":idusuario",$this->idusuario);
			$database->bind(":idsala",$this->idsala);
			$database->bind(":descricao",utf8_encode($this->descricao));
			$database->bind(":date_ini",$this->dateini);
			$database->bind(":date_fim",$this->datefim);
			
			$database->execute();
		}else{
			$database->query("INSERT INTO reserva(usuario_id, sala_id, descricao, date_ini, date_fim)VALUES(:idusuario,:idsala,:descricao,:date_ini, :date_fim)");
			$database->bind(":idusuario",$this->iduser);
			$database->bind(":idsala",$this->idroom);
			$database->bind(":descricao",utf8_encode($this->descricao));
			$database->bind(":date_ini",$this->dateini);
			$database->bind(":date_fim",$this->datefim);
			$database->execute();
			$this->id = $database->lastInsertId();
		}
		$database->endTransaction();
	}

	public function erase(){
		if(isset($this->id)){
			$database = new Database();
			$database->beginTransaction();
			$database->query("DELETE FROM reserva WHERE idreserva = :id");
			$database->bind(":id",$this->id);
			$database->execute();
			$database->endTransaction();
		}
		$this->clear();
	}

	public function clear(){
		unset($this->id);
		unset($this->idusuario);
		unset($this->idsala);
		unset($this->usuario);
		unset($this->sala);
		unset($this->date);
	}

	public function setUsuarioId($id){
		$this->idusuario = $id;
		$this->Usuario = new Usuario($id);
	}

	public function setSalaId($id){
		$this->idsala = $id;
		$this->sala = new Sala($id);
	}

	public static function getAll($orderBy){
		$database = new Database();
		if(!isset($orderBy)){
			$orderBy = "idreserva";
		}
		$database->query("SELECT idreserva, descricao, sala_id, usuario_id, date_ini, date_fim FROM reserva ORDER BY ".$orderBy);
		$database->execute();

		$rows = $database->resultset();
		$count = $database->rowCount();
		$reservas = array();

		if($count!=0){
			for($i=0;$i<$count;$i++){
				$reserva = new Reserva();
				$reserva->setId($rows[$i]['idreserva']);
				$reserva->setDescricao(utf8_decode($rows[$i]['descricao']));
				$reserva->setSalaId($rows[$i]['sala_id']);
				$reserva->setUsuarioId($rows[$i]['usuario_id']);
				$reserva->setDataIni($rows[$i]['date_ini']);
				$reserva->setDataFim($rows[$i]['date_fim']);
				$reservas[$i] = $reserva;
			}
		}
		return $reservas;
	}

	public function verificaReserva(){
		$database = new Database();
		$database->query("SELECT * FROM reserva WHERE usuario_id = :idusuario AND ((date_fim >= :date_ini AND date_ini <= :date_ini) OR (date_ini <= :date_fim AND date_fim >= :date_fim))");
		$database->bind(":idusuario",$this->idusuario);
		$database->bind(":date_ini",$this->dateini);
		$database->bind(":date_fim",$this->datefim);
		$database->execute();
		if($database->rowCount() > 0){
			throw new Exception('Existe conflito de reservas para o usuário.');
		}

		$database->query("SELECT * FROM reserva WHERE sala_id = :idsala AND ((date_fim >= :date_ini AND date_ini <= :date_ini) OR (date_ini <= :date_fim AND date_fim >= :date_fim))");
		$database->bind(":idsala",$this->idsala);
		$database->bind(":date_ini",$this->dateini);
		$database->bind(":date_fim",$this->datefim);
		$database->execute();
		if($database->rowCount() > 0){
			throw new Exception('Existe conflito de reservas para a sala selecionada.');
		}


	}
}
?>