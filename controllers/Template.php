<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Template extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Custom_email_and_sms_notifications_model','custom_model');
        if (!has_permission('custom_email_and_sms_notifications', '', 'create')) {
             access_denied(_l('sms_title'));
        }
    }

    public function index()
    {
    
    if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME, 'tables/custom_templates'));
        }
        $this->load->view(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'/add_edit_templates',[]);
    }
    
    
        public function template_group(){
                
        if ($this->input->is_ajax_request()) {
        $this->app->get_table_data(module_views_path(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME, 'tables/table_template_group'));
        }
        
        $this->db->select('*')
        ->from(db_prefix().'custom_templates');

        $data['templates'] = $this->db->get()->result_array();
        $data['title'] = 'Template Group';
        $this->load->view('template_group',$data);
        
        }
        public function add_template_group(){
        
        $template_group = $this->input->post('template_group');

        $data['name'] = $this->input->post('group_name');
        $data['created_at'] = date("Y/m/d H:i:s");
        $data['template_id'] = implode(',',$template_group);
        
        $table = db_prefix().'emailtemplate_group';
        $this->db->insert($table,$data);
        
        redirect($_SERVER['HTTP_REFERER']);
        }
        public function edit_template(){
        
        $id = $this->input->post('id');
        $template_group = $this->input->post('template_group');
        $data['name'] = $this->input->post('group_name');
        
        $data['template_id'] = implode(',',$template_group);
        
        $table = db_prefix().'emailtemplate_group';
        $this->db->where('id',$id);
        $this->db->update($table,$data);
        
        redirect(admin_url()."custom_email_and_sms_notifications/template/template_group");
        }
        public function delete_template_group($id){
            
            $table = db_prefix().'emailtemplate_group';
            $this->db->where('id', $id);
            $this->db->delete($table);
            
            redirect($_SERVER['HTTP_REFERER']);
        }
        
    public function edit_template_group($id){
        
        $data['id'] = $id;
        $this->db->select('*')
        ->from(db_prefix().'custom_templates');
        $data['templates'] = $this->db->get()->result_array();
        
        $this->db->select('*');
        $this->db->where('id', $id);
        $data['records'] =    $this->db->get(db_prefix().'emailtemplate_group')->row_array();
        
        $data['title'] = 'Edit Template Group';
        $this->load->view('edit_template_group',$data);
            
        }

    public function save($id=''){
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $data['staff_id']=$this->session->userdata('staff_user_id');
            if ('' == $data['id']) {
                $id      = $this->custom_model->add($data);
                $message = $id ? _l('added_successfully', _l('template')) : '';
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $message,
                    'id'      => $id,
                    'name'    => $data['template_name'],
                ]);
            } else {
                $success = $this->custom_model->update($data['id'], $data);
                $message = '';
                if (true == $success) {
                    $message = _l('updated_successfully', _l('template'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function delete($id=''){

        if (!$id) {
            redirect(admin_url('custom_email_and_sms_notifications/template'));
        }
        $response = $this->custom_model->delete($id);
        if (true == $response) {
            set_alert('success', _l('deleted', _l('template')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('template')));
        }
        redirect(admin_url('custom_email_and_sms_notifications/template'));
    }

    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->custom_model->get($id);
            $item->template_content   = nl2br($item->template_content);

            echo json_encode($item);
        }
    }

    public function get_template_data(){
    	if ($this->input->is_ajax_request()) {
	        $post = $this->input->post();
	        $where = ['id'=>$post['template_id']];
	        $template_content = $this->custom_model->get('id',$where);
	        echo json_encode($template_content);
    	}
    }
}
?>
