<?php
class Project_model extends Kiel_Model{

	public function __construct()
	{
		parent::__construct();
	}

	public function add($data)
	{
		$data['id'] = sha1($data['name'].$this->time.'projects'.openssl_random_pseudo_bytes(20));
		$data['date_created'] = $this->time;
		$data['date_updated'] = $this->time;
		$data['active'] = 1;

		$res = $this->data_handler->insert('project',$data);
		if($res['affected_rows'] == 1){
			return $data;
		} else {
			return false;
		}

	}

	public function get_projects()
	{
		return $this->data_handler->get('project',null);
	}

}


?>