<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function get_all_users($conditions)
	{
		$this->db->select('yh_users.*,grade_name');
		$this->db->from(TBL_USERS);
                $this->db->join('yh_grades', 'yh_grades.grade_id = yh_users.grade_id','left');
		$this->db->where($conditions);
		return $this->db->get()->result();
	}
	
	public function get_all_jobs($conditions){
		return $this->db->get_where(TBL_JOBS, $conditions)->result();
	}
        
	public function get_user_jobs_count($conditions,$job_id,$user_id){
            $this->db->select('yh_grades.*,yh_jobs.*');
            $this->db->from(TBL_JOBS);
            $this->db->join('yh_grades', 'yh_grades.grade_id = yh_jobs.grade_id','left');
            $this->db->where($conditions);
            $this->db->where('yh_jobs.grade_id >=', $this->session->userdata('grade_id'));
            $q = $this->db->where('yh_jobs.job_id NOT IN (SELECT job_id FROM yh_user_to_jobs WHERE user_id= '.$user_id.')', NULL, FALSE);
            return $q -> count_all_results(); 
	}
        
        public function get_user_jobs($conditions,$job_id,$user_id,$limit=0, $start=0){
            if($job_id == 0)
            {
                $this->db->limit($limit, $start);
            }
            $this->db->select('yh_grades.*,yh_jobs.*,yh_user_to_jobs.approved');
            $this->db->from(TBL_JOBS);
            $this->db->join('yh_grades', 'yh_grades.grade_id = yh_jobs.grade_id','left');
            $this->db->join('yh_user_to_jobs', 'yh_user_to_jobs.job_id = yh_jobs.job_id','left');
            $this->db->where($conditions);
            $this->db->where('yh_jobs.grade_id >=', $this->session->userdata('grade_id'));
            
        if($job_id != 0)
            {
                $this->db->where(array('yh_jobs.job_id'=>$job_id));
                return  $this->db->get()->row();
            }
            else
            {
				
		$this->db->where('yh_jobs.job_id NOT IN (SELECT job_id FROM yh_user_to_jobs WHERE user_id= '.$user_id.')', NULL, FALSE);
                return  $this->db->get()->result();
            }   
	}
	
	public function already_applied($conditions)
        {
            $this->db->select('*');
            $this->db->from(TBL_USERS_TO_JOBS);
            $this->db->where($conditions);
            $rows = $this->db->count_all_results();
            return $rows ? true : false;
	}
        public function get_user_jobs_after_applied($conditions,$job_id,$user_id,$limit=0, $start=0)
        {
            $this->db->select('yh_grades.*,yh_jobs.*,yh_user_to_jobs.approved');
            $this->db->from(TBL_JOBS);
            $this->db->join('yh_grades', 'yh_grades.grade_id = yh_jobs.grade_id','left');
            $this->db->join('yh_user_to_jobs', 'yh_user_to_jobs.job_id = yh_jobs.job_id','left');
            $this->db->where($conditions);
            $this->db->where('yh_jobs.job_id NOT IN (SELECT job_id FROM yh_user_to_jobs WHERE user_id= '.$user_id.')', NULL, FALSE);
            $this->db->where(array('yh_jobs.job_id'=>$job_id));
            return  $this->db->get()->row();      
	}
        
        public function count_get_user_applied_jobs($conditions){
            $this->db->select('yh_grades.*,yh_jobs.*,yh_user_to_jobs.*');
            $this->db->from(TBL_JOBS);
            $this->db->join('yh_grades', 'yh_grades.grade_id = yh_jobs.grade_id','left');
            $this->db->join('yh_user_to_jobs', 'yh_user_to_jobs.job_id = yh_jobs.job_id','left');
            $this->db->where($conditions);
            return $this->db->count_all_results();
	}
        
        public function get_user_applied_jobs($conditions,$limit, $start)
        {
            $this->db->limit($limit, $start);
            $this->db->select('yh_grades.*,yh_jobs.*,yh_user_to_jobs.*');
            $this->db->from(TBL_JOBS);
            $this->db->join('yh_grades', 'yh_grades.grade_id = yh_jobs.grade_id','left');
            $this->db->join('yh_user_to_jobs', 'yh_user_to_jobs.job_id = yh_jobs.job_id','left');
            $this->db->where($conditions);
            return $this->db->get()->result(); 
	}
        
        public function get_public_attachments($conditions)
	{
		$this->db->select('*');
		$this->db->from(TBL_JOBS_FILES);
                $this->db->where($conditions);
		return $this->db->get()->result();
	}
        
        public function get_single_job($conditions)
        {
            $this->db->select('yh_grades.*,yh_jobs.*,yh_user_to_jobs.approved');
            $this->db->from(TBL_JOBS);
            $this->db->join('yh_grades', 'yh_grades.grade_id = yh_jobs.grade_id','left');
            $this->db->join('yh_user_to_jobs', 'yh_user_to_jobs.job_id = yh_jobs.job_id','left');
            $this->db->where($conditions);
            return  $this->db->get()->row();      
	}
        
}// end of model
