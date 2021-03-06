<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reminder extends CI_Controller {

	/**
	* Index Page for this controller.
	*
	* Maps to the following URL
	* 		http://example.com/index.php/welcome
	*	- or -
	* 		http://example.com/index.php/welcome/index
	*	- or -
	* Since this controller is set as the default controller in
	* config/routes.php, it's displayed at http://example.com/
	*
	* So any other public methods not prefixed with an underscore will
	* map to /index.php/welcome/<method_name>
	* @see http://codeigniter.com/user_guide/general/urls.html
	*/

	public function __construct()
	{

		parent::__construct();

		$this->load->database();
		$this->load->library('email');
		$this->load->library('ion_auth');
		$this->load->library('ion_auth_acl');
		$this->load->library('tools');


	}

	public function index($task_id = null, $view = null)
	{

		$this->tools->keyLogin();
		$this->tools->changeBu();// GENERIC changement de Bu
		$this->load->library('rmd');
		$id_bu =  $this->session->userdata('id_bu');

		$msg = null;
		$form = $this->input->post();

		if(!empty($form)) {

			foreach ($form as $key => $val) {

				$ex = explode('_', $key);
				if($ex[0] == 'task') {
					$req_up 	= "UPDATE rmd_meta SET `start` = NOW() WHERE id_task = ". $ex[1];
					$req_ins	= "INSERT INTO rmd_log SET `id_user` = ".$form['user'].", `date` = NOW(), `id_task` = ".$ex[1];

					if(!$this->db->query($req_up)) {
						echo $this->db->error;
						return false;
					}

					if(!$this->db->query($req_ins)) {
						echo $this->db->error;
						return false;
					}

					$msg = "RECORDED ON: ".date('Y-m-d H:m');
				}
			}

		}

		$this->db->select('users.username, users.last_name, users.first_name, users.email, users.id');
		$this->db->distinct('users.username');
		$this->db->join('users_bus', 'users.id = users_bus.user_id', 'left');
		$this->db->where('users.active', 1);
		$this->db->where('users_bus.id_bu', $id_bu);
		$this->db->order_by('users.username', 'asc');
		$query = $this->db->get("users");
		$users = $query->result();

		$rmd = $this->rmd->getTasks($task_id, $view, $id_bu);

		if ($this->session->userdata('type') == false) {
			$rmdraw = $rmd;
			$rmd = array('service' => array(), 'kitchen' => array());
			foreach ($rmdraw as $key => $val) {
				if (isset($val->type)) {
					if ($val->type == 'service') {
						$rmd['service'][$key] = $val;
					} else if ($val->type == 'kitchen') {
						$rmd['kitchen'][$key] = $val;
					}
				}
			}
			if (empty($rmd['service']) && empty($rmd['kitchen'])) $rmd = array();
		}

		$data = array(
			'tasks'		=> $rmd,
			'users'		=> $users,
			'msg'		=> $msg,
			'keylogin'	=> $this->session->userdata('keylogin'),
			'view'		=> $view
			);

		$data['bu_name'] =  $this->session->userdata('bu_name');
		$data['username'] = $this->session->userdata('identity');

		$headers = $this->tools->headerVars(1, "/reminder/", "Reminder");
		$this->load->view('jq_header_pre', $headers['header_pre']);
		$this->load->view('reminder/jq_header_spe');
		$this->load->view('jq_header_post', $headers['header_post']);
		$this->load->view('reminder/index',$data);
		$this->load->view('jq_footer');
	}

	public function log()
	{
		$this->tools->keyLogin();

		$id_bu =  $this->session->userdata('id_bu');

		$req 	= "SELECT l.`date`,t.`task`,u.`username`  FROM rmd_log l JOIN `users` u ON u.id = l.`id_user` JOIN rmd_tasks t ON t.id = l.`id_task` WHERE t.id_bu = $id_bu ORDER BY l.`date` DESC LIMIT 100";

		$res 	= $this->db->query($req) or die($this->mysqli->error);
		$tasks 	= $res->result();
		$data = array(
			'tasks'		=> $tasks
			);

		$data['bu_name'] =  $this->session->userdata('bu_name');
		$data['username'] = $this->session->userdata('identity');

		$headers = $this->tools->headerVars(0, "/reminder/", "Reminder Log");
		$this->load->view('jq_header_pre', $headers['header_pre']);
		$this->load->view('reminder/jq_header_spe');
		$this->load->view('jq_header_post', $headers['header_post']);
		$this->load->view('reminder/logs',$data);
		$this->load->view('jq_footer');
	}

	public function adminSave()
	{
		$id_bu =  $this->session->userdata('id_bu');

		$data = $this->input->post();

    $now = date('Y-m-d');
    $defaults = [
      'mstart'    => $now,
      'nstart'    => '10:00:00',
      'nend'      => '17:00:00',
      'ninterval' => 20000,
      'nlast'     => $now
    ];

    foreach ($defaults as $field => $value)
    {
      if (!array_key_exists($field, $data) || empty($data[$field]))
        $data[$field] = $value;
    }

		$sqlt = "UPDATE ";
		$sqle = " WHERE `id` = $data[id]";
		$sqln = " WHERE id_task = $data[id]";
		$reponse = 'ok';


		if ($data['id'] == 'create') {
			if (empty($data['mstart']) || $data['mstart'] == '0000-00-00 00:00:00') {
				$data['mstart'] = date('Y-m-d H:i:s');
			}
		}

		if($data['id'] == 'create') {
			$sqlt = "INSERT INTO ";
			$sqle = "";
		}

		$sql_tasks = "$sqlt rmd_tasks SET `task` = '".addslashes($data['task'])."', comment = '".addslashes($data['comment'])."', active = $data[active], priority = $data[priority], notify_tablet = $data[notify_tablet], type = '".$data['type']."', id_bu = $id_bu $sqle ";
		$this->db->trans_start();
		if (!$this->db->query($sql_tasks)) {
			$response = "Can't place the insert sql request, error message: ".$this->db->_error_message();
		}

		if($data['id'] == 'create') {
			$data['id'] = $this->db->insert_id();
			$sqln = " , id_task = $data[id]";
		}

		$sql_notif = "$sqlt rmd_notif SET `start` = '".$data['nstart']."', `end` = '".$data['nend']."', `interval` = '".$data['ninterval']."', `last` = '" . $data['nlast'] . "' $sqln";

		$sql_meta = "$sqlt rmd_meta SET `start` = '".$data['mstart']."', repeat_interval = '".$data['repeat_interval']."' $sqln";

		if (!$this->db->query($sql_notif)) {
			$response = "Can't place the insert sql request, error message: ".$this->db->_error_message();
		}

		if (!$this->db->query($sql_meta)) {
			$response = "Can't place the insert sql request, error message: ".$this->db->_error_message();
		}
		$this->db->trans_complete();

		echo json_encode(['reponse' => $reponse]);
	}

	public function admin($create = null)
	{
		$this->tools->keyLogin();
		$this->tools->changeBu();// GENERIC changement de Bu
		$id_bu =  $this->session->userdata('id_bu');
		$this->load->library('rmd');
		$this->load->library('ion_auth');
		$this->load->library('ion_auth_acl');

		$rmd = $this->rmd->getAllTasks($id_bu);
		$data = array(
			'create'	=> $create,
			'tasks'		=> $rmd
			);

		$data['bu_name'] =  $this->session->userdata('bu_name');
		$data['username'] = $this->session->userdata('identity');

		if(!$create){
			$headers = $this->tools->headerVars(1, "/reminder/admin", "Reminder admin");
		}else{
			$headers = $this->tools->headerVars(0, "/reminder/admin", "Reminder admin");
		}
		$this->load->view('jq_header_pre', $headers['header_pre']);
		$this->load->view('reminder/jq_header_spe');
		$this->load->view('jq_header_post', $headers['header_post']);
		$this->load->view('reminder/reminder_admin',$data);
		$this->load->view('jq_footer');
	}

	//cd /var/www/hank/rms/rms && php index.php reminder cliNotify 1
	public function cliNotify($id_bu)
	{
		$this->load->library('rmd');
		if(is_cli()) {

			$tasks = $this->rmd->getTasks(null, null, $id_bu);
			foreach ($tasks as $row) {

				$now			= time();
				$notif			= $this->getNotif($row->id);
				$notif_start	= 0;
				$notif_end		= 999999999999;
				$interval		= 0;
				
				if(isset($notif->id)) {


					$notif_start	= strtotime(date('Y-m-d '.$notif->start));
					$notif_end		= strtotime(date('Y-m-d '.$notif->end));
					$notif_interval = $notif->interval;
					$notif_last		= strtotime($notif->last);
					$interval 		= $notif_last+$notif_interval;
				}

				if($notif_start <= $now && $notif_end > $now && $interval < $now && $row->notify_tablet) {
					$this->db->set('last', "NOW()", FALSE)->where('id_task', $row->id);
					if(!$this->db->update('rmd_notif')) {
						echo $this->db->error;
						return false;
					}

				$this->tools->sendNotif("Reminder: ".$row->task, $id_bu, $row->type);

				}
			}

			return;
		} else {
			echo "Access refused.";
			return;
		}


	}
	private function getNotif($id) {
		$req = "SELECT * FROM rmd_notif WHERE id_task = $id LIMIT 1";
		$res = $this->db->query($req);
		$r = $res->result();
		if(!empty($r[0])) return $r[0];
		return false;
	}
}
