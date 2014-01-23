<?php
class Time_entry extends Kiel_Controller{


	/*
	* Always pass the args to the parent construct
	*
	*/
	public function __construct($args)
	{
		parent::__construct($args);
		$this->load_model('auth_model');
		$this->load_model('user_model');
		$this->load_model('time_entry_model');
	}

	/**
	*	@param 		title
	*	@param 		project_id
	*	@param 		user_id
	*	@param 		start_time
	*	@param 		details
	*	@return 	error || time_entry
	*/
	public function index_post()
	{
		$this->required_fields(array('access_token','user_id','title','project_id','start_time','details'),$this->post_args);
		$this->check_access_token($this->post_args['access_token']);
		$this->has_scopes(array('web.view','time_entry.add'),$this->post_args['access_token']);

		if($this->user_model->user_exists($this->post_args['user_id'])){
			throw new Exception("That user does not exist.", 1);
		} else {

			$data['title'] = $this->post_args['title'];
			$data['user_id'] = $this->post_args['user_id'];
			$data['project_id'] = $this->post_args['project_id'];
			$data['details'] = $this->post_args['details'];
			$data['start_time'] = $this->post_args['start_time'];
			isset($this->post_args['end_time']) && !empty($this->post_args['end_time']) && $data['end_time'] = $this->post_args['end_time'];

			$res = $this->time_entry_model->add_time_entry($data);
			if(!$res){
				throw new Exception("Something went wrong while adding data. Please try again.", 1);
			}
			$this->response(array('status'=>'Success','data'=>$res),200);
		}
	}


	/**
	*	@param 		time_entry_id
	*	@param 		access_token
	*	@param 		fields to be edited
	*	@return 	error || time_entry
	*/
	public function index_put()
	{
		$this->required_fields(array('access_token','time_entry_id'),$this->put_args);
		$this->check_access_token($this->put_args['access_token']);
		$this->has_scopes(array("web.view","time_entry.edit"),$this->put_args['access_token']);

		$id = $this->put_args['time_entry_id'];

		isset($this->put_args['user_id']) && !empty($this->put_args['user_id']) && $data['user_id'] = $this->put_args['user_id'];
		isset($this->put_args['project_id']) && !empty($this->put_args['project_id']) && $data['project_id'] = $this->put_args['project_id'];
		isset($this->put_args['title']) && !empty($this->put_args['title']) && $data['title'] = $this->put_args['title'];
		isset($this->put_args['details']) && !empty($this->put_args['details']) && $data['details'] = $this->put_args['details'];
		isset($this->put_args['start_time']) && !empty($this->put_args['start_time']) && $data['start_time'] = $this->put_args['start_time'];
		isset($this->put_args['end_time']) && !empty($this->put_args['end_time']) && $data['end_time'] = $this->put_args['end_time'];
		
		$res = $this->time_entry_model->update_time_entry($id,$data);

		if(!$res){
			throw new Exception("Something went wrong while updating data. Please try again.", 1);
		}
		$this->response(array('status'=>'Success','data'=>$res),200);
	}


	/**
	*	@param 		user_id
	*	@param 		access_token
	*	@param 		start_date [0 || start date]
	*	@param 		end_date [0 || end date]
	*	@return 	error || time_entry
	*/
	public function list_get()
	{
		$this->required_fields(array('access_token','user_id','start','end'),$this->get_args);
		$this->check_access_token($this->get_args['access_token']);
		$this->has_scopes(array("web.view"),$this->get_args['access_token']);

		if($this->get_args['start'] == 0 && $this->get_args['end'] == 0){
			$res = $this->time_entry_model->time_entry_by(array('user_id'=>$this->get_args['user_id']));
		} else {
			$res = $this->time_entry_model->time_entry_by_interval('user_id',$this->get_args['user_id'],$this->get_args['start'],$this->get_args['end']);
		}

		if(!$res){
			throw new Exception("Something went wrong while fetching data. Please try again.", 1);
		}
		$this->response(array('status'=>'Success','data'=>$res),200);
	}

	/**
	*	@param 		project_id
	*	@param 		access_token
	*	@param 		start_date [0 || start date]
	*	@param 		end_date [0 || end date]
	*	@return 	error || time_entry
	*/
	public function project_get()
	{
		$this->required_fields(array('access_token','project_id','start','end'),$this->get_args);
		$this->check_access_token($this->get_args['access_token']);
		$this->has_scopes(array("web.view"),$this->get_args['access_token']);

		if($this->get_args['start'] == 0 && $this->get_args['end'] == 0){
			$res = $this->time_entry_model->time_entry_by(array('project_id'=>$this->get_args['project_id']));
		} else {
			$res = $this->time_entry_model->time_entry_by_interval('project_id',$this->get_args['project_id'],$this->get_args['start'],$this->get_args['end']);
		}

		if(!$res){
			throw new Exception("Something went wrong while fetching data. Please try again.", 1);
		}
		$this->response(array('status'=>'Success','data'=>$res),200);
	}
}

?>