<?php
	
	$this->load->view('site/_partials/commonfunctions');
	
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
			
			<?php if(count($set) > 0): ?>
			
				<ul class="set">
					
					<?php $content_container_width = 0; ?>
				
					<?php foreach($set as $s): ?>
					
						<?php $image_path = FILE_DIRECTORY.floor($s->image_id/100).'/'.$s->image_id.'_height_400.jpg'; ?>
					
						<?php if( file_exists(DOCUMENT_ROOT.$image_path) ): ?>
							
							<?php
								
								list($image_width, $image_height, $image_type, $image_attr) = getimagesize(DOCUMENT_ROOT.$image_path);
								$content_container_width += ($image_width + 1); //+1 for right margin
								unset($image_width, $image_height, $image_type, $image_attr);
								
							?>
							
							<li class="image">
								<img alt="Set Image" height="400" src="<?php echo $image_path; ?>" width="<?php echo $s->image_width; ?>"/>
								<a class="image_hover_previous" href="#"></a>
								<a class="image_hover_next" href="#"></a>
								<div class="image_information" style="width:<?php echo $s->image_width; ?>px;">
									<?php
										
										$image_tags = (strpos($s->tag_ids, ',') !== FALSE) ? explode(',', $s->tag_ids) : array();
										if( count($image_tags) > 0 ){
											$taglist = array();
											foreach($image_tags as $k=>$v){
												if( is_numeric($v) && isset($tags[$v]) ){
													$taglist[] = '<a href="/set/'.$tags[$v]->tag_slug.'">'.htmlentities($tags[$v]->tag_name).'</a> ('.$tags[$v]->tag_count.')';
												}
											}unset($k, $v);
											echo '<div class="">Tags: '.implode($taglist, ', ').'</div>';
										}
										
									?>
									<div class="">Source: <a class="external" href="<?php echo htmlentities($s->image_website); ?>"><?php echo htmlentities($s->image_website_basename); ?></a></div>
									<?php
										
										if( $this->access->is_logged_in() ){
											echo '<div class="admin"><a class="external" href="/admin/remove/'.$s->image_id.'">Remove Image</a></div>';
										}
										
									?>
								</div>
							</li>
					
						<?php endif; ?>
					
					<?php endforeach; unset($s, $image_path); ?>
				
				</ul>
				
			<?php else: ?>
				
				<p>There are currently no images in the set.</p>
				
			<?php endif; ?>
			
			<!-- End Content -->
			
			<!-- Start Page Script -->
			
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Variables
				var preview_window;
			
			//Init Function
				$(function(){
					
					//Set Variables
					preview_window = $('div#overlay');
					contentWidth = <?php echo $content_container_width; ?>;
					
					//Page Load Function Calls
					//image_delay_load();
					set_content_container_width( contentWidth );
					set_content_children( contentContainer.children('ul.set').children('*') );
					set_content_scroll_time(500);
					
					//Function Calls
					bind_hover_links();
					bind_information();
					bind_preview_display();
					bind_preview_select();
					bind_preview_window_scroll($('ul.previews'));
					
				});
				
			//Binding Functions
				function bind_hover_links(){
					$('.image_hover_previous').unbind('click').bind('click', function(){
						var eq = $(this).parent().index();
						if( eq == 0 ){ eq = contentChildren.size()-1; }
						else{ eq = eq-1; }
						content_jump_to(eq);
						return false;
					});
					$('.image_hover_next').unbind('click').bind('click', function(){
						var eq = $(this).parent().index();
						if( eq == (contentChildren.size()-1) ){ eq = 0; }
						else{ eq = eq+1; }
						content_jump_to(eq);
						return false;
					});
				}
				
				function bind_information(){
					$('.image').bind('mouseenter', function(){
						$(this).find('div.image_information').slideDown(120);
					}).bind('mouseleave', function(){
						$(this).find('div.image_information').slideUp(100);
					});
				}
				
				function bind_preview_display(){
					$('a.set_preview').unbind('click').bind('click', function(){
						if( preview_window.is(':hidden') ){ preview_open(); }
						else{ preview_close(); }
						return false;
					});
				}
				
				function bind_preview_select(){
					$('li.preview a').unbind('click').bind('click', function(){
						preview_close();
						content_jump_to( $(this).parent().index() );
						return false;
					});
				}
				
				function bind_preview_window_scroll( content ){
					preview_window.unbind('mousemove').bind('mousemove', function(e){
						var window_height = parseInt( preview_window.height() );
						var content_height = parseInt( $('.previews').height() );
						var mouse_y = e.pageY - this.offsetTop;
						var mouse_y_percent = Math.round( ( mouse_y / window_height ) * 100 );
						var position = -parseInt((mouse_y_percent / 100) * (content_height - window_height));
						
						if( mouse_y_percent <= 5 ){ position = 0; }
						if( mouse_y_percent >= 95 ){ position = -(content_height - window_height); }
						
						if( content_height > window_height ){
							$('.preview_container').animate(
								{ 'top':position },
								{ queue:false, duration:500 }
							);
						}
					});
				}
			
			//Implementation Functions
				function image_delay_load(){
					$("ul.set img").lazyload({ 
						placeholder : "",
						container: $(".content_container"),
						effect : "fadeIn"
					});
				}
				
				function preview_open(){
					preview_window.fadeIn(180);
				}
				
				function preview_close(){
					preview_window.fadeOut(120);
				}
				
				function update_progress(eq){
					var progress = parseInt(eq)+1;
					var total = parseInt( $('.set_total_count').html() );
					if( progress <= total ){
						$('.set_current_progress').val(progress);
					}
				}
				
			//]]>
			</script>
			
			<!-- End Page Script -->
			
		</div>
		
	</div>
	
	<div id="overlay">
		
		<!-- Start Overlay Content -->
		
		<div class="preview_container">
			
			<?php if(count($set) > 0): ?>
				
				<ul class="previews">
					
					<?php $i = 0; ?>
					
					<?php foreach($set as $s): ?>
						
						<?php $image_preview_path = FILE_DIRECTORY.floor($s->image_id/100).'/'.$s->image_id.'_height_60.jpg'; ?>
						
						<?php if( file_exists(DOCUMENT_ROOT.$image_preview_path) ): ?>
							
							<li class="preview">
								<a href="#/<?php echo $i; ?>">
									<img alt="Preview Image" src="<?php echo $image_preview_path; ?>" />
								</a>
							</li>
							
						<?php endif; ?>
							
						<?php $i++; ?>
					
					<?php endforeach; unset($s, $image_preview_path); ?>
					
				</ul>
				
			<?php endif; ?>
			
		</div>
		
		<!-- End Overlay Content-->
			
	</div>
	
	<?php $this->load->view('site/_partials/footer'); ?>
	
</div>

</body>
</html>