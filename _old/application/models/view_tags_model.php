<?php

class View_tags_model extends Model {

	function View_tags_model(){
		parent::Model();
		$this->load->database();
	}
	
	function is_tag($id){
		if( !is_numeric($id) ){ $id = $this->return_tag_id($id); }
		$sql = 'SELECT tag_id FROM view_tags WHERE tag_active = 1 AND tag_id = ?';
		return ( $this->db->query($sql, array($id))->num_rows() > 0 ) ? TRUE : FALSE;
	}
	
	function return_tag_id($slug){
		$sql = 'SELECT tag_id FROM view_tags WHERE tag_slug = ?';
		return @$this->db->query($sql, array($slug))->row()->tag_id;
	}
	
	function return_tags(){
		$sql = 'SELECT * FROM view_tags WHERE tag_active = 1 ORDER BY tag_name ASC';
		$resource = $this->db->query($sql);
		if( $resource->num_rows() > 0 ){
			$tags = array();
			foreach( $resource->result() as $key => $val ){
				$tags[$val->tag_id] = $val;
			}unset($key, $val);
			return $tags;
		}else{ return FALSE; }
	}
	
	function add_tag($name){
		$slug = create_slug( $name );
		if( $slug == NULL || $this->is_tag($slug) ){ return FALSE; }
		$data = array(
			'tag_name' => mysql_real_escape_string($name),
			'tag_slug' => mysql_real_escape_string($slug),
			'tag_count' => 0
		);
		return $this->db->insert('view_tags', $data);
	}
	
	function add_image_to_tag($image_id, $tag_id){
		$data = array(
			'image_id' => $image_id,
			'tag_id' => $tag_id
		);
		if( $this->db->insert('view_tag_lookup', $data) ){
			return $this->increase_count($tag_id);
		}else{ return FALSE; }
	}
	
	function increase_count($id){
		if( !is_numeric($id) ){ $id = $this->return_tag_id($id); }
		$sql = 'UPDATE view_tags SET tag_count = tag_count+1 WHERE tag_id = ?';
		return $this->db->query($sql, array($id));
	}
	
	function decrease_count($id){
		if( !is_numeric($id) ){ $id = $this->return_tag_id($id); }
		$sql = 'UPDATE view_tags SET tag_count = tag_count-1 WHERE tag_id = ?';
		if( $this->db->query($sql, array($id)) ){
			$this->delete_empty_tags();
			return TRUE;
		}else{ return FALSE; }
	}
	
	function delete_empty_tags(){
		$sql = 'DELETE FROM view_tags WHERE tag_count <= 0';
		return $this->db->query($sql);
	}
	
	function delete_lookup($image_id){
		$sql = 'SELECT * FROM view_tag_lookup WHERE image_id = ?';
		$resource = $this->db->query($sql, array($image_id));
		if( $resource->num_rows() > 0 ){
			foreach($resource->result() as $r){
				unset($sql);
				$sql = 'DELETE FROM view_tag_lookup WHERE tag_lookup_id = ?';
				$this->db->query($sql, array($r->tag_lookup_id));
				$this->decrease_count($r->tag_id);
			}unset($resource, $r);
			$this->delete_empty_tags();
		}
		return TRUE;
	}
	
	function return_set($id, $nsfw = FALSE){
		$nsfw = ($nsfw === TRUE) ? '' : ' AND vi.image_nsfw = "0"';
		if( !is_numeric($id) ){ $id = $this->return_tag_id($id); }
		$sql = 'SELECT vi.*, GROUP_CONCAT(DISTINCT vtl.tag_id, ",") as tag_ids
				FROM view_images as vi
				LEFT JOIN view_tag_lookup as vtl ON vtl.image_id = vi.image_id
				LEFT JOIN view_tags as vt ON vt.tag_id = vtl.tag_id
				WHERE vt.tag_active = 1 
					AND vi.image_active = 1
					AND vtl.tag_id = "'.$id.'"
					'.$nsfw.'
				GROUP BY vi.image_id
				ORDER BY vi.date_added DESC';
		return $this->db->query($sql)->result();
	}
	
	function return_sets(){
		$sql = 'SELECT * FROM view_tags WHERE tag_active = 1 AND tag_count > 0 ORDER BY tag_name ASC';
		$sets = $this->db->query($sql)->result_array();
		foreach($sets as $k=>$v){
			$sets[$k]['recent_images'] = $this->return_most_recent_images($v['tag_id']);
		}unset($k, $v);
		return $sets;
	}
	
	function return_most_recent_images($tag_id, $limit = 3){
		if( !is_numeric($limit) ){ $limit = 3; }
		$sql = 'SELECT vtl.image_id
				FROM view_tag_lookup as vtl
				LEFT JOIN view_images as vi ON vi.image_id = vtl.image_id
				WHERE vi.image_active = 1
					AND vtl.tag_id = "'.$tag_id.'"
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

/* End of file image_tags_model.php */