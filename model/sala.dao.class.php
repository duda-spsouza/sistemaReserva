<?php
require_once "idao.interface.php";
class Sala implements IDao{

	private $id;
	private $nome;
	private $descricao;

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

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function load($id){
		$database = new Database();
		$database->query("SELECT idsala, nome, descricao FROM sala WHERE idsala = :id");
		$database->bind(":id", $id);
		$cols = $database->single();

		if(!isset($cols["idsala"])){
			throw new Exception("Sala não encontrada.");
		}

		$this->id          = $cols["idsala"];
		$this->label       = utf8_decode($cols["nome"]);
		$this->description = utf8_decode($cols["descricao"]);
	}

	public function save(){
		$database = new Database();
		$database->beginTransaction();
		if(isset($this->id)){
			$database->query("UPDATE sala SET nome = :nome, descricao = :descricao WHERE idsala = :id");
			$database->bind(":id",$this->id);
			$database->bind(":nome",utf8_encode($this->nome));
			$database->bind(":descricao",utf8_encode($this->descricao));
			
			$database->execute();
		}else{
			$database->query("INSERT INTO sala(nome, descricao)VALUES(:nome,:descricao)");
			$database->bind(":nome",utf8_encode($this->nome));
			$database->bind(":descricao",utf8_encode($this->descricao));
			$database->execute();
			$this->id = $database->lastInsertId();
		}
		$database->endTransaction();
	}

	public function erase(){
		if(isset($this->id)){
			$database = new Database();
			$database->beginTransaction();
			$database->query("DELETE FROM sala WHERE idsala = :id");
			$database->bind(":id",$this->id);
			$database->execute();
			$database->endTransaction();
		}
		$this->clear();
	}

	public function clear(){
		unset($this->id);
		unset($this->nome);
		unset($this->descricao);
	}

	public static function getAll($orderBy){
		$database = new Database();
		if(!isset($orderBy)){
			$orderBy = "idsala";
		}
		$database->query("SELECT idsala, nome, descricao FROM sala ORDER BY ".$orderBy);
		$database->execute();

		$rows = $database->resultset();
		$count = $database->rowCount();
		$salas = array();

		if($count!=0){
			for($i=0;$i<$count;$i++){
				$sala = new Sala();
				$sala->setId($rows[$i]['idsala']);
				$sala->setNome(utf8_decode($rows[$i]['nome']));
				$sala->setDescricao(utf8_decode($rows[$i]['descricao']));
				$salas[$i] = $sala;
			}
		}
		return $salas;
	}

}
?>