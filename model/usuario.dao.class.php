<?php
require_once "idao.interface.php";
class Usuario implements IDao{

	private $id;
	private $nome;
	private $email;
	private $hash;

    public function __construct($id = -1){
    	if(($id != -1)){
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

	public function setEmail($email){
		$this->email = $email;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setPassword($password){
		$this->hash = Util::createHash($password);
	}

	public function setHash($hash){
		$this->hash = $hash;
	}

	public function getHash(){
		return $this->hash;
	}

	public function load($id){
		$database = new Database();
		$database->query("SELECT idusuario, nome, email, senha FROM usuario WHERE idusuario = :id");
		$database->bind(":id", $id);
		$cols = $database->single();

		if(!isset($cols["idusuario"])){
			throw new Exception("Usuário não encontrado.");
		}

		$this->id       = $cols["idusuario"];
		$this->nome     = utf8_decode($cols["nome"]);
		$this->senha     = $cols["senha"];
		$this->email = $cols["email"];
	}

	public function save(){
		$database = new Database();
		$database->beginTransaction();
		if(isset($this->id)){
			$database->query("UPDATE usuario SET nome = :nome, email = :email, senha = :senha WHERE idusuario = :id");
			$database->bind(":id",$this->id);
			$database->bind(":nome",utf8_encode($this->nome));
			$database->bind(":email",$this->email);
			$database->bind(":senha",$this->hash);
			
			$database->execute();

		}else{
			$database->query("INSERT INTO usuario(nome, email, senha)VALUES(:nome,:email,:senha)");
			$database->bind(":nome",utf8_encode($this->nome));
			$database->bind(":email",$this->email);
			$database->bind(":senha",$this->hash);
			$database->execute();
			$this->id = $database->lastInsertId();
		}
		$database->endTransaction();
	}

	public function erase(){
		if(isset($this->id)){
			$database = new Database();
			$database->beginTransaction();
			$database->query("DELETE FROM usuario WHERE idusuario = :id");
			$database->bind(":id",$this->id);
			$database->execute();
			$database->endTransaction();
		}
		$this->clear();
	}

	public function clear(){
		unset($this->id);
		unset($this->nome);
		unset($this->email);
		unset($this->hash);
	}

	public static function isRegistered($email, $password){

		$database = new Database();
		$database->query("SELECT idusuario, nome, email, senha FROM usuario WHERE email = :email");
		$database->bind(":email",$email);

		$cols = $database->single();

		if($database->RowCount() == 0){
			throw new Exception("Usuário não cadastrado.");
		}elseif($cols["senha"] <> Util::createHash($password)){
			throw new Exception("Senha incorreta.");
		}else{
			return $cols["idusuario"];
		}
	}

	public static function getAll($orderBy){
		$database = new Database();
		if(!isset($orderBy)){
			$orderBy = "idusuario";
		}
		$database->query("SELECT idusuario, nome, email, senha FROM usuario ORDER BY ".$orderBy);
		$database->execute();

		$rows = $database->resultset();
		$count = $database->rowCount();
		$usuarios = array();

		if($count!=0){
			for($i=0;$i<$count;$i++){
				$usuario = new Usuario();
				$usuario->setId($rows[$i]['idusuario']);
				$usuario->setNome(utf8_decode($rows[$i]['nome']));
				$usuario->setEmail($rows[$i]['email']);
				$usuario->setHash($rows[$i]['senha']);
				$usuarios[$i] = $usuario;
			}
		}
		return $usuarios;
	}
}
?>