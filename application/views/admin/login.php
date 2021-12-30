<?php
	
	$this->load->view('site/_partials/commonfunctions');
	
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
			
			<div class="login_container">
				
				<form action="<?php echo $this->uri->uri_string(); ?>" class="login" method="post">
					<table cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td class="left"><label for="username">Username</label></td>
								<td class="right"><input class="text" id="username" name="username" type="text" value=""/></td>
							</tr>
							<tr>
								<td class="left"><label for="password">Password</label></td>
								<td class="right"><input class="text" id="password" name="password" type="password" value=""/></td>
							</tr>
							<tr>
								<td class="left">&nbsp;</td>
								<td class="right">
									<input class="submit" name="login" type="submit" value="Login"/>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				
			</div>
			
			<?php show_message(); ?>
			
			<!-- End Page Content -->
			
			<!-- Start Page Script -->
			
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Set Variables
			
			//Init
				$(function(){
					
					//Set Variables
					var length = $('div.text_box').length;
					contentWidth = Math.ceil( (length*181) ) + Math.ceil( $('div.message').length * $('div.message').width() );
					
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