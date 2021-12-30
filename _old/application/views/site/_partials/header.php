
<!-- Start Header -->

<div id="header">

	<a id="logo" href="/">View by Chris Laskey</a>
	
		<div id="nav">
			<a class="content_filter" href="#" rel="<?php echo (show_nsfw() === TRUE) ? 1 : 0; ?>"><?php echo (show_nsfw() === TRUE) ? 'nsfw': 'sfw'; ?></a>
			<?php if($this->uri->segment(1) == 'set'): ?>
			<a class="set_preview" href="#">Preview</a>
			<?php endif; ?>
			<a class="content_previous" href="#">&laquo;</a>
			<div class="content_progress_container">
				<input class="content_progress" name="content_progress" type="text" value="1"/>
				<div class="content_total">/ <span>1</span></div>
			</div>
			<a class="content_next" href="#">&raquo;</a>
		</div>
		
	<?php if($this->uri->segment(1) == 'sets'): ?>
		
		<!--
		//* Unfinished
		<div class="set_nav" style="float:right;">
			<a href="#">by time</a>&nbsp;&nbsp;|&nbsp;
			<a href="#">by tag</a>
		</div>
		-->
	
	<?php endif; ?>
	
</div>

<!-- End Header -->

