<?php

class View_times_model extends Model {

	function View_times_model(){
		parent::Model();
		$this->load->database();
	}
	
	function is_time($id){
		if( !is_numeric($id) ){ $id = $this->return_time_id($id); }
		$sql = 'SELECT time_id FROM view_times WHERE time_active = 1 AND time_id = ?';
		return ( $this->db->query($sql, array($id))->num_rows() > 0 ) ? TRUE : FALSE;
	}
	
	function return_time_id($slug){
		$sql = 'SELECT time_id FROM view_times WHERE time_slug = ? LIMIT 1';
		return @$this->db->query($sql, array($slug))->row()->time_id;
	}
	
	function return_time_count(){
		
	}
	
	function increase_count($id){
		$sql = 'UPDATE view_times SET time_count = time_count+1 WHERE time_id = ?';
		return $this->db->query($sql, array($id));
	}
	
	function decrease_count($id){
		if( !is_numeric($id) ){ $id = $this->return_time_id($id); }
		$sql = 'UPDATE view_times SET time_count = time_count-1 WHERE time_id = ?';
		if( $this->db->query($sql, array($id)) ){
			$this->delete_empty_times();
			return TRUE;
		}else{ return FALSE; }
	}
	
	function delete_empty_times(){
		$sql = 'DELETE FROM view_times WHERE time_count <= 0';
		return $this->db->query($sql);
	}
	
	function delete_lookup($image_id){
		$sql = 'SELECT * FROM view_time_lookup WHERE image_id = ?';
		$resource = $this->db->query($sql, array($image_id));
		if( $resource->num_rows() > 0 ){
			foreach($resource->result() as $r){
				unset($sql);
				$sql = 'DELETE FROM view_time_lookup WHERE time_lookup_id = ?';
				$this->db->query($sql, array($r->time_lookup_id));
				$this->decrease_count($r->time_id);
			}unset($resource, $r);
			$this->delete_empty_times();
		}
		return TRUE;
	}
	
	function add_time($data){
		if( !is_array($data) && !is_object($data) ){ return FALSE; }
		return $this->db->insert('view_times', $data);
	}
	
	function add_image_to_time($image_id, $set_slug){
		$set_id = $this->return_time_id($set_slug);
		if( !is_numeric($set_id) ){ return FALSE; }
		$data = array(
			'image_id' => $image_id,
			'time_id' => $set_id
		);
		if( $this->db->insert('view_time_lookup', $data) ){
			return $this->increase_count($set_id);
		}else{ return FALSE; }
	}
	
	function return_set($id, $nsfw = FALSE){
		$nsfw = ($nsfw === TRUE) ? '' : ' AND vi.image_nsfw = "0"';
		if( !is_numeric($id) ){ $id = $this->return_time_id($id); }
		$sql = 'SELECT vi.*, GROUP_CONCAT(DISTINCT vtagl.tag_id, ",") as tag_ids
				FROM view_images as vi
				LEFT JOIN view_time_lookup as vtl ON vtl.image_id = vi.image_id
				LEFT JOIN view_times as vt ON vt.time_id = vtl.time_id
				LEFT JOIN view_tag_lookup as vtagl ON vtagl.image_id = vi.image_id
				WHERE vt.time_active = 1 
					AND vi.image_active = 1
					AND vtl.time_id = "'.$id.'"
					'.$nsfw.'
				GROUP BY vi.image_id
				ORDER BY vi.date_added DESC';
		return $this->db->query($sql)->result();
	}
	
	function return_sets(){
		$sql = 'SELECT * FROM view_times WHERE time_active = 1 AND time_count > 0 ORDER BY time_unix_time DESC';
		$sets = $this->db->query($sql)->result_array();
		foreach($sets as $k=>$v){
			$sets[$k]['recent_images'] = $this->return_most_recent_images($v['time_id'], 4, show_nsfw());
		}unset($k, $v);
		return $sets;
	}
	
	function return_most_recent_images($set_id, $limit = 3, $nsfw = FALSE){
		$nsfw = ($nsfw === TRUE) ? '' : ' AND vi.image_nsfw = "0"';
		if( !is_numeric($limit) ){ $limit = 3; }
		$sql = 'SELECT vtl.image_id
				FROM view_time_lookup as vtl
				LEFT JOIN view_images as vi ON vi.image_id = vtl.image_id
				WHERE vi.image_active = 1
					AND vtl.time_id = "'.$set_id.'"
					'.$nsfw.'
				GROUP BY vtl.image_id
				ORDER BY vi.date_added DESC
				LIMIT '.$limit;
		$images = $this->db->query($sql)->result();
		$recent_images = array();
		foreach($images as $image){
			$recent_images[] = $image->image_id;
		}
		return $recent_images;
	}
	
}

/* End of file view_times_model.php */