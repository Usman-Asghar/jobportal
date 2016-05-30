<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Jobs extends CI_Controller {

	/**
	 *  
	 *	Please do not remove this comment

	 *	@author : Yousaf Hassan
	 *	@Email : usafhassan@gmail.com
	 *	Date : 20/03/2016
	 */
	 
	function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('admin_logged_in')){
                redirect('admin-login');
                exit();
        }
        $this->load->model('admin','Admin_Model');
    }
	 
	public function index()
	{
            $data['page_title'] = 'User Jobs';
            $data['jobs'] = $this->Admin_Model->get_user_jobs( array('yh_jobs.job_status' => '1','yh_jobs.assigned_to' => '0', 'yh_jobs.admin_id'=>$this->session->userdata('admin_id')) );
            $data['grades'] = $this->Admin_Model->get_all_grades(array('grade_status' => '1'));
            $this->load->admin_template('user_jobs',$data);
	}
        
        public function approve_user_job()
        {
            $response = array('success'=>false,'message'=>'');
            $new_data1 = array(
                    'assigned_to'=> '1'
            );

            $updated1=$this->Common_Model->update(TBL_JOBS,
                    $new_data1,
                    array( 'job_id' => (int) $this->input->post('job_id'))
            );
            $new_data = array(
                    'approved'=> '1'
            );

            $updated=$this->Common_Model->update(TBL_USERS_TO_JOBS,
                    $new_data,
                    array( 'user_id' => (int) $this->input->post('user_id'),'job_id' => (int) $this->input->post('job_id') )
            );
            if($updated1 && $updated)
            {
                    $response['message'] = 'Job has been Approved Successfully!';
                    $response['success'] = true;
            }
            else $response['message'] = 'Job Approval has been failed!';
            
            echo json_encode($response);
            
        }
        
        public function reject_user_job()
        {
            $response = array('success'=>false,'message'=>'');
            $new_data = array(
                    'rejected'=> '1'
            );

            $updated=$this->Common_Model->update(TBL_USERS_TO_JOBS,
                    $new_data,
                    array( 'user_id' => (int) $this->input->post('user_id'),'job_id' => (int) $this->input->post('job_id') )
            );
            if($updated)
            {
                    $response['message'] = 'Job has been Rejected Successfully!';
                    $response['success'] = true;
            }
            else $response['message'] = 'Job Rejection has been failed!';
            
            echo json_encode($response);
            
        }
}