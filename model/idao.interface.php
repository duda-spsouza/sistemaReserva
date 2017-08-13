<?php
interface IDao{

	public function save();
	public function load($id);
	public function erase();
	public function clear();
}
?>