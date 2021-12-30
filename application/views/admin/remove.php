<?php
	
	$this->load->view('site/_partials/commonfunctions');
	
	$content_container_width = 0;
	
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
			
			<?php if( $this->uri->segment(3) == 'success' ): ?>
				
				<div class="text_box">
					
					<h2>Image Removed</h2>
					
					<p>The image has been successfully removed.</p>
					
				</div>
				
			<?php else: ?>
				
				<?php $image_path = FILE_DIRECTORY.floor($this->uri->segment(3)/100).'/'.$this->uri->segment(3).'_height_400.jpg'; ?>
				
				<?php if( file_exists(DOCUMENT_ROOT.$image_path) ): ?>
					
					<?php
						
						list($image_width, $image_height, $image_type, $image_attr) = getimagesize(DOCUMENT_ROOT.$image_path);
						$content_container_width += ($image_width + 1); //+1 for right margin
						unset($image_width, $image_height, $image_type, $image_attr);
						
					?>
					
					<div class="image">
						<img alt="Image to be removed" src="<?php echo $image_path; ?>" />
					</div>
					
				<?php endif; ?>
				
				<div class="text_box">
					
					<h2>Confirm Remove</h2>
					
					<?php show_message(); ?>
					
					<p>Confirm the permanent removal of the image below.</p>
					
					<form action="<?php echo $this->uri->uri_string().'/confirmed'; ?>" class="login" method="post">
						<div>
							<input class="submit" name="submit" type="submit" value="Remove"/>
						</div>
					</form>
					
				</div>
				
			<?php endif; ?>
			
			<!-- End Page Content -->
			
			<!-- Start Page Script -->
			
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Set Variables
			
			//Init
				$(function(){
					
					//Set Variables
					contentWidth = parseInt( <?php echo $content_container_width; ?> + $('div.text_box').width() ) + Math.ceil( $('div.message').length * $('div.message').width() );
					
					//Implementation Calls
					set_content_container_width( contentWidth );
					set_content_children( contentContainer.children('*').not('script') );
					
				});
				
			//Additional Functions
			
			//]]>
			</script>
			
			<!-- End Page Script -->
			
		</div>
		
	</div>
	
	<?php $this->load->view('site/_partials/footer'); ?>
	
</div>

</body>
</html>