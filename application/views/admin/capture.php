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
			
			<div class="capture_form_container">
				
				<form action="<?php echo $this->uri->uri_string(); ?>" class="capture_form" method="post">
					<table>
						<tbody>
							<tr>
								<td class="left">
									<label for="image_url">Image URL</label>
								</td>
								<td class="right">
									<input class="text" id="image_url" name="image_url" type="text" value="" />
								</td>
							</tr>
							<tr>
								<td class="left">&nbsp;</td>
								<td class="right">
									<input name="image_force_jpg" type="checkbox" value="1" />Force JPG
								</td>
							</tr>
							<tr>
								<td class="left">
									<label for="image_website">Website URL</label>
								</td>
								<td class="right">
									<input class="text" id="image_website" name="image_website" type="text" value="" />
								</td>
							</tr>
							<tr>
								<td class="left">
									<label>Tags</label>
								</td>
								<td class="right">
									<div class="image_tags"></div>
								</td>
							</tr>
							<tr>
								<td class="left">&nbsp;</td>
								<td class="right">
									<input checked="checked" name="image_active" type="checkbox" value="1" />Show Publicly
								</td>
							</tr>
							<tr>
								<td class="left">&nbsp;</td>
								<td class="right">
									<input name="image_nsfw" type="checkbox" value="1" />NSFW
								</td>
							</tr>
							<tr>
								<td class="left">&nbsp;</td>
								<td class="right">
									<input class="tags" name="tags" type="hidden" value="" />
									<input class="submit" name="submit" type="submit" value="save" />
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				
			</div>
			
			<div class="capture_tags_container">
				<ul class="tag_list">
					<li>
						<form action="/capture" class="capture_tag_form" method="post">
							<div>
								<input class="text" name="tag" type="text" value="" />
								<input class="submit" name="add" type="submit" value="add" />
							</div>
						</form>
					</li>
					<?php echo $tags; ?>
				</ul>
			</div>
			
			<!-- End Page Content -->
			
			<!-- Start Page Script -->
			
			<script type="text/javascript" charset="utf-8">
			//<![CDATA[
			
			//Set Variables
				var form_container = $('div.capture_form_container');
				var tags_container = $('div.capture_tags_container');
			
			//Init
				$(function(){
					
					//Set Variables
					var message_width = Math.ceil( $('div.message').length * $('div.message').width() );
					var tag_width = Math.ceil( contentWindow.width() - form_container.width() - message_width );
					contentWidth = contentWindow.width();
					
					//Implementation Calls
					set_content_container_width( contentWidth );
					set_content_children( contentContainer.children('*').not('script') );
					
					//Additional Calls
					set_tags_container_width( tag_width-25 );
					bind_set_tags_container_width();
					bind_tag_select();
					bind_new_tag();
					
				});
				
			//Binding Functions
				function bind_set_tags_container_width(){
					$(window).bind('resize', function(){
						var width = Math.ceil( $(this).width() - form_container.width() );
						set_tags_container_width( width );
					});
				}
				
				function bind_tag_select(){
					$('ul.tag_list a.tag').unbind('click').bind('click', function(){
						if( $(this).hasClass('selected') ){ $(this).removeClass('selected'); }
						else{ $(this).addClass('selected'); }
						update_tags();
						return false;
					});
				}
				
				function bind_new_tag(){
					$('form.capture_tag_form').bind('submit', function(){
						var new_tag = $('form.capture_tag_form input[name="tag"]').val();
						if( new_tag != null && new_tag != '' && new_tag != undefined ){
							$.post(
								'/admin/capture/ajax/add-tag',
								{'new_tag':new_tag, 'ajax':true},
								function(data, success){
									if( success ){
										$('form.capture_tag_form input[name="tag"]').val('');
										$('ul.tag_list li').not(':eq(0)').remove();
										$('ul.tag_list li').after(data);
										//$('ul.tag_list').html(data);
										bind_tag_select();
									}else{ console.log('Error adding tag'); }
								}
							);
						}
						return false;
					});
				}
				
			//Additional Functions
				function set_tags_container_width(raw_width){
					width = parseInt(raw_width);
					if( width != NaN ){
						tags_container.css('width', width+'px');
					}
				}
				
				function update_tags(){
					var selected_ids = new Array();
					var selected_names = new Array();
					$('ul.tag_list a.tag').each(function(e){
						if( $(this).hasClass('selected') ){
							selected_ids.push( $(this).attr('href').substring(1, $(this).attr('href').length) );
							selected_names.push( $(this).attr('rel') );
						}
					});
					$('input[name="tags"]').val( implode(',', selected_ids) );
					$('div.image_tags').html( implode(', ', selected_names) );
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