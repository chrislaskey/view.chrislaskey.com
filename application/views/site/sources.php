<?php
	
	$this->load->view('site/_partials/commonfunctions');
	
	$content_container_width = (isset($sources) && count($sources) > 0) ? ceil((count($sources)*140)-1) : 140;
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	
	<?php $this->load->view('site/_partials/head'); ?>
	
</head>

<body class="<?php echo (isset($body_class)) ? $body_class : create_body_class(); ?>">

<div id="wrapper">
	
	<?php $this->load->view('site/_partials/header'); ?>
	
	<div id="window">
		
		<div id="content_container">
			
			<!-- Start Page Content -->
			
			<?php
				
				if( isset($sources) && count($sources) > 0 ){
					
					$i = 1;
					$high = 0;
					$chart = array();
					
					foreach($sources as $s){
						
						$high = ($s->count > $high) ? $s->count : $high;
						$unit = (400 / $high); 
						$last = ( $i == count($sources) ) ? 'last' : '';
						$chart[] = '<li class="'.$last.'"><a class="external" href="http://'.htmlentities($s->basename).'" style="height:'.ceil($unit*$s->count).'px;">'.str_replace('www.', '', htmlentities($s->basename)).'</a></li>';
						
						$i++;
						
					}unset($s, $i);
					
					echo '<ul class="sources">'.implode($chart).'</ul>';
				
				}
			
			?>
			
			<!-- End Content -->
			
			<!-- Start Content Script -->
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Init Function
				$(function(){
					
					//Set Variables
					contentWidth = <?php echo $content_container_width; ?>;
					
					//Implementation Calls
					set_content_container_width( contentWidth );
					set_content_children( contentContainer.children('ul').children('li') );
					set_content_scroll_time(1);
					
				});
				
			//]]>
			</script>
			
			<!-- End Content Script -->

		</div>

	</div>

	<?php $this->load->view('site/_partials/footer'); ?>

</div>

</body>
</html>