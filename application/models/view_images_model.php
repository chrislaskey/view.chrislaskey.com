<?php

class View_images_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function is_image($id){
		if( !is_numeric($id) ){ return FALSE; }
		$sql = 'SELECT image_id FROM view_images WHERE image_id = ? LIMIT 1';
		return ( $this->db->query($sql, array($id))->num_rows() > 0 ) ? TRUE : FALSE;
	}
	
	function return_image($id){
		if( !is_numeric($id) ){ return FALSE; }
		$sql = 'SELECT * FROM view_images WHERE image_id = ? LIMIT 1';
		return @$this->db->query($sql, array($id))->row();
	}
	
	function add_image(){
		if( $this->db->insert('view_images', array('date_added'=>'')) ){
			return $this->db->insert_id();
		}else{ return FALSE; }
	}
	
	function update_image($image_id, $data){
		$this->db->set('date_added = NOW()', FALSE);
		$this->db->set('date_edited = NOW()', FALSE);
		$this->db->where('image_id', $image_id);
		return $this->db->update('view_images', $data);
	}
	
	function delete_image($id){
		$sql = 'DELETE FROM view_images WHERE image_id = ? LIMIT 1';
		return $this->db->query($sql, array($id));
	}
	
	function return_website_basenames(){
		$sql = 'SELECT DISTINCT image_website_basename as basename, COUNT(image_website_basename) as count
				FROM view_images
				WHERE image_active = 1
				GROUP BY image_website_basename
				ORDER BY count DESC';
		return $this->db->query($sql)->result();
	}
	
}

/* End of file view_images_model.php */