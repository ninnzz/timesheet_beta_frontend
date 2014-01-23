<?php
class Time_entry_model extends Kiel_Model{

	public function __construct()
	{
		parent::__construct();
	}

	public function add_time_entry($data)
	{
		$data['id'] = substr(sha1($data['title'].$this->time.'time_entry'.openssl_random_pseudo_bytes(20)),0,32);
		$data['date_created'] = $this->time;
		$data['date_updated'] = $this->time;
		if(($res = $this->data_handler->insert('time_entry',$data)) && $res['affected_rows'] == 1){
			return $data;
		} else {
			return false;
		}
	}

	public function update_time_entry($id,$data)
	{
		$data['date_updated'] = $this->time;
		if(($res = $this->data_handler->update_where('time_entry',$data,array('id'=>$id))) && $res['affected_rows'] == 1){
			return $data;
		} else {
			return false;
		}	
	}

	public function time_entry_by_interval($typ,$id,$start,$end)
	{	
		$q = "SELECT * FROM time_entry WHERE {$typ} ='{$id}' AND start_time BETWEEN $start AND $end";	
		$res = $this->data_handler->query($q);
		return $res?$res:false;
	}

}

?>
