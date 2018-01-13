<?php
class ClassLoader{
	public $model;
	function Load($model){
		if(!file_exists("model/".$model.".php")){
			return "Can't load Class file \"".$model."\"";
		}
		return file_get_contents("model/".$model.".php");
	}
}