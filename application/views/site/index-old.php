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
			
			<?php show_message(); ?>
			
			<div class="text_box">
				<h2>Welcome</h2>
				<p>View is a visual bookmarking system used to keep track of interesting photos I&rsquo;ve come across on the web. It utilizes a custom viewing system built to display images on my website.</p>
				<p>Unless otherwise noted, <strong>all images are copyright of the original owner</strong> &ndash; follow the link to the individual website for more information.</p>
				<div class="clear"></div>
			</div>
			
			<div class="text_box">
				<h2>Controls</h2>
				<p>Each page can be controlled through a combination of <strong>keyboard</strong> (arrow keys) and <strong>mouse</strong> (movement, clicks).</p>
				<p>You&rsquo;re encouraged to explore.</p>
			</div>
			
			<div class="text_box">
				<h2>Getting Started...</h2>
				<p>You can view the visual bookmarks by</p>
				<ul>
					<li>
						<a href="/sets/by-time">Time</a>
					</li>
					<li>
						<a href="/sets/by-tag">Tag</a>
					</li>
				</ul>
				<p>Select a kind above to get started!</p>
			</div>
			
			<div class="text_box">
				<h2>Notice!</h2>
				<p>Some pictures are not safe for work (<strong>NSFW</strong>). By default NSFW these pictures will not displayed &ndash; used the toggle above to turn them on.</p>
				<p>I&rsquo;ve done my best to mark these appropriately, but please view at your own risk!</p>
			</div>
			
			<!-- End Page Content -->
			
			<!-- Start Page Script -->
			
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Init
				$(function(){
					
					//Set Variables
					var message_count = $('div.message').length;
					var message_width = $('div.message').width();
					var text_count = $('div.text_box').length;
					var text_width = $('div.text_box').width();
					contentWidth = Math.ceil( (text_count*text_width) + (message_count*message_width) );
					
					//Implementation Calls
					set_content_container_width( contentWidth );
					set_content_children( contentContainer.children('*').not('script') );
					
				});
			
			//]]>
			</script>
			
			<!-- End Page Script -->
			
		</div>
		
	</div>
	
	<?php $this->load->view('site/_partials/footer'); ?>
	
</div>

</body>
</html>