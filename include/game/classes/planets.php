<?php

// Solar Imperium is licensed under GPL2, Check LICENSE.TXT for mode details //


class Planets
{

	var $DB;
	var $TEMPLATE;
	var $data;
	var $data_footprint;
	var $game_id;
	
	///////////////////////////////////////////////////////////////////////
	//
	///////////////////////////////////////////////////////////////////////
	function Planets($DB,$TEMPLATE)
	{
		$this->DB = $DB;
		$this->TEMPLATE = $TEMPLATE;
		$this->game_id = round($_SESSION["game"]);
	}

	///////////////////////////////////////////////////////////////////////
	//
	///////////////////////////////////////////////////////////////////////
	function load($empire_id)
	{
		$this->data = $this->DB->Execute("SELECT * FROM game".$this->game_id."_tb_planets WHERE empire='".intval($empire_id)."'");	
		if (!$this->data) trigger_error($this->DB->ErrorMsg());
		if ($this->data->EOF) return false;
		$this->data = $this->data->fields;

		$this->data_footprint = md5(serialize($this->data));


		return true;
	}

	///////////////////////////////////////////////////////////////////////
	//
	///////////////////////////////////////////////////////////////////////
	function save()
	{
		if (md5(serialize($this->data)) == $this->data_footprint) return;

		$query = "UPDATE game".$this->game_id."_tb_planets SET ";
		reset($this->data);
		while (list($key,$value) = each($this->data))
		{
			if ($key == "id") continue;
			if ($key == "empire") continue;
			if (is_numeric($key)) continue;
			if ((is_numeric($value)) && ($key != "logo"))
				$query .= "$key=$value,";
			else
				$query .= "$key='".addslashes($value)."',";
			
		}

		$query = substr($query,0,strlen($query)-1); // removing remaining ,
		$query .= " WHERE empire='".$this->data["empire"]."'";
		if (!$this->DB->Execute($query)) trigger_error($this->DB->ErrorMsg());

	}


	///////////////////////////////////////////////////////////////////////
	//
	///////////////////////////////////////////////////////////////////////
	function getCount()
	{
		$count = 0;
		$count += $this->data["food_planets"];
		$count += $this->data["ore_planets"];
		$count += $this->data["tourism_planets"];
		$count += $this->data["supply_planets"];
		$count += $this->data["gov_planets"];
		$count += $this->data["edu_planets"];
		$count += $this->data["research_planets"];
		$count += $this->data["urban_planets"];
		$count += $this->data["petro_planets"];
		$count += $this->data["antipollu_planets"];

		return $count;
	}
	
}


?>
