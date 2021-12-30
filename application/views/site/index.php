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
			
			<ul class="sets">
				
				<li class="text_box">
					<h2>About</h2>
					<p>View is my visual bookmarking system. Images are sorted by <a href="/sets/by-time">time</a> and <a href="/sets/by-tag">tag</a>.</p>
					<p>Use the keyboard and mouse to navigate and explore. All images are copyright of the original owner. Source code available on my main website.</p>
				</li>
				
				<?php if( isset($sets) && count($sets) > 0 ): ?>
					
					<?php $i = 1; ?>
					
					<?php foreach($sets as $set): ?>
						
						<li class="set <?php if( $i == count($sets) ){ echo 'last'; } ?>">
							
							<?php if( isset($set['time_slug']) ): ?>
								
								<a class="set_link" href="/set/<?php echo $set['time_slug']; ?>">
									<span class="set_title"><?php echo htmlentities($set['time_name']); ?></span>
									<span class="set_count"><?php echo $set['time_count']; ?> Image<?php echo ($set['time_count'] == 1) ? '' : 's'; ?></span>
								</a>
								
							<?php else: ?>
								
								<a class="set_link" href="/set/<?php echo $set['tag_slug']; ?>">
									<span class="set_title"><?php echo htmlentities($set['tag_name']); ?></span>
									<span class="set_count"><?php echo $set['tag_count']; ?> Image<?php echo ($set['tag_count'] == 1) ? '' : 's'; ?></span>
								</a>
								
							<?php endif; ?>
							
							<?php
								
								if(count($set['recent_images']) > 0){ 
									
									$recent_images = array();
									$total_height = 0;
									
									foreach($set['recent_images'] as $id){
										
										$image_path = DOCUMENT_ROOT.FILE_DIRECTORY.floor($id/100).'/'.$id.'_width_160.jpg';
										
										if( file_exists($image_path) && is_readable($image_path) ){
											
											list($image_width, $image_height, $image_type, $image_attr) = getimagesize($image_path);
											$total_height += ($image_height + 1); //+1 for bottom margin
											unset($image_width, $image_height, $image_type, $image_attr);
											
											if( $total_height <= 355 ){
												$recent_images[] = '<li class="set_preview">
																		<img alt="Recent images from set" src="'.FILE_DIRECTORY.floor($id/100).'/'.$id.'_width_160.jpg"/>
																	</li>';
											}
										}
									
									}unset($id, $image_path);
									
									if( count($recent_images) > 0 ){
										echo '<ul class="set_previews">'.implode($recent_images).'</ul>';
									}
									
								}
								
							?>
							
						</li>
						
						<?php $i++; ?>
					
					<?php endforeach; unset($set); ?>
					
				<?php endif; ?>
				
			</ul>
			
			<!-- End Page Content -->
			
			<!-- Start Page Script -->
			
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Set Variables
			
			//Init
				$(function(){
					
					//Set Variables
					var message_count = $('div.message').length;
					var message_width = $('div.message').width();
					var text_count = $('ul.sets li').length;
					var text_width = $('ul.sets li').width();
					contentWidth = Math.ceil( (text_count*text_width) + (message_count*message_width) );
					
					//Binding Functions
					bind_set_click();
					bind_set_hover();
					
					//Implementation Functions
					set_content_container_width( contentWidth );
					set_content_children( contentContainer.children('ul.sets').children('*') );
					
				});
				
			//Additional Functions
				function bind_set_click(){
					$('ul.sets li.set')
						.unbind('click')
						.bind('click', function(){
							var link = $(this).find('a.set_link').attr('href');
							window.location.href = link;
					});
				}
				
				function bind_set_hover(){
					$('ul.sets li').each(function(e){
						var anchor = $(this).find('a.set_link');
						$(this)
							.unbind('mouseover')
							.bind('mouseenter', function(){
								if( !anchor.hasClass('hover') ){
									anchor.addClass('hover');
								}
							})
							.unbind('mouseleave')
							.bind('mouseleave', function(){
								if( anchor.hasClass('hover') ){
									anchor.removeClass('hover');
								}
							});
					})
				}
				
				function image_delay_load(){
					$("ul.sets img").lazyload({ 
						placeholder : "",
						container: $(".content_container"),
						effect : "fadeIn"
					});
				}
			
			//]]>
			</script>
			
			<!-- End Page Script -->
			
		</div>
		
	</div>
	
	<?php $this->load->view('site/_partials/footer'); ?>
	
</div>

</body>
</html>
