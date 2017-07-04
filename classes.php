<?php
	class Option implements \JsonSerializable
	{
		var $id;
		var $detail;
		function __construct( $par1, $par2 ) {
		   $this->id = $par1;
		   $this->detail = $par2;
		}
		function setId($par){
			$this->id = $par;
		}
		function getId($par){
			return $this->id;
		}
		function getDetail(){
			return $this->detail;
		}
		function setDetail($par){
			$this->detail = $par;
		}
		    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
	}
	class Measure implements \JsonSerializable
	{
		var $id;
		var $detail;
		var $options;
		function __construct( $par1, $par2) {
		   $this->id = $par1;
		   $this->detail = $par2;
		}
		function addOption($par){
			$options[] = $par;
		}
		function setOptions($par){
			$this->options = $par;
		}
		function getOptions(){
			return $this->options;
		}
		function getDetail(){
			return $this->detail;
		}
		function setDetail($par){
			$this->detail = $par;
		}
		function getId(){
			return $this->id;
		}
		function setId($par){
			$this->id = $par;
		}
		    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
	}
	class Election implements \JsonSerializable
	{
		var $id;
		var $detail;
		var $measures;
		var $electionName;
		var $startDate;
		var $stopDate;		
		
		function addMeasure($par){
			measures[] = $par;
		}
		
		function getMeasures(){
			return $this->measures;
		}
		function setMeasures($par){
			$this->measures = $par;
		}
		
		function getDetail(){
			return $this->detail;
		}
		function setDetail($par){
			$this->detail = $par;
		}
		function getId(){
			return $this->id;
		}
		function setId($par){
			$this->id = $par;
		}
		function __construct( $par1, $par2) {
		   $this->id = $par1;
		   $this->detail = $par2;
		}
		function getElectionName(){
			return $this->electionName;
		}
		function setElectionName($par){
			$this->electionName = $par;
		}
		function getStartDate(){
			return $this->startDate;
		}
		function setStartDate($par){
			$this->startDate = $par;
		}
		function getStopDate(){
			return $this->stopDate;
		}
		function setStop($par){
			$this->stopDate = $par;
		}
		    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
		
	}
?>