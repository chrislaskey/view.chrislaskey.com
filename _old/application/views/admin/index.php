<?php
	
	$this->load->view('site/_partials/commonfunctions');
	
	header('location:/admin/capture'); exit();
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	
	<?php $this->load->view('site/_partials/headelements'); ?>
	
</head>

<body class="<?php echo (isset($body_class)) ? $body_class : create_body_class(); ?>">

<div id="wrapper">
	
	<?php $this->load->view('site/_partials/header'); ?>
	
	<div id="window">
		
		<div id="content_container">
			
			<!-- Start Page Content -->
			
			<div class="text_box">
				<h2>Admin</h2>
				<p>Welcome to the admin panel.</p>
				<ul>
					<li><a href="/admin/capture">Capture</a></li>
				</ul>
				<div class="clear"></div>
			</div>
			
			<div class="text_box">
				<h2>Logout</h2>
				<p><a href="/logout">Logout</a></p>
				<div class="clear"></div>
			</div>
			
			<!-- End Page Content -->
			
			<!-- Start Page Script -->
			
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Set Variables
			
			//Init
				$(function(){
					
					//Set Variables
					var length = $('div.text_box').length;
					contentWidth = Math.ceil( (length*181) );
					
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