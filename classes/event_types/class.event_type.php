<?php

class Event_Type {
	private $name;
	private $type;
	private $description;
	private $color;
	
	static private $counter;
	
	public function __construct($args = null) {
		if(!is_null($args)) {
			$args = explode(":::", $args);
			$this->name			= $args[0];
			$this->type			= $args[1];
			$this->description	= $args[2];
			$this->color		= $args[3];
		}
		self::$counter++;
	}
	
	public function renderJSON() {
		if(!is_null($this->name)) {
			$json = implode(":::", array($this->name, $this->type, $this->description, $this->color));
		} else {
			$json = "";
		}
			
		return $json;
	}
	
	/*
	 * Getter / Setter Methods for Class Parameters
	 */
	public function getName() {
		return $this->name;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getColor() {
		return $this->color;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function setColor($color) {
		$this->color = $color;
	}
	
}

?>