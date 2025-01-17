<?php
/**
 * @package Codeigniter
 * @subpackage Freelancer_project
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Freelancer_project extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->set_table('freelancer_project');
	}

	public function freelance($user_id) {
		return $this->db->get_where($this->table, array('user_id' => $user_id));
	}

	public function project($project_id) {
		return $this->db->get_where($this->table, array('project_id' => $project_id));
	}

	public function on_going($user_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('rating', NULL);
		return $this->db->get($this->table);
	}

	public function has_rating($user_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('rating !=', NULL);
		return $this->db->get($this->table);
	}
}

/* End of file Freelancer_project.php */
/* Location : ./application/models/Freelancer_project.php */