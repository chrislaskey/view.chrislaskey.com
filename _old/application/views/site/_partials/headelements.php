
<!-- Meta Data -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!-- Title -->
<title><?php echo (isset($page_title)) ? $page_title : create_page_title("View by Chris Laskey", " | ", FALSE); ?></title>

<!-- Styles -->
<link charset="utf-8" href="/assets/styles/main.css" media="all" rel="stylesheet" type="text/css"/>
<?php if( is_mobile() ): //This is required for pesky browsers like Safari for the iPhone which refuse to load @media handheld stylesheets by default ?>
	<link charset="utf-8" href="/assets/styles/mobile.css" media="all" rel="stylesheet" type="text/css"/>
<?php endif; ?>

<!-- Scripts -->
<script charset="utf-8" src="/assets/scripts/jquery.js" type="text/javascript"></script>
<script charset="utf-8" src="/assets/scripts/commonfunctions.js" type="text/javascript"></script>

<?php if( is_mobile() ): ?>
	
	<script type="text/javascript" charset="utf-8">
	//<![CDATA[
	
		$(document).ready(function(){
			set_mobile(true);
		});
	
	//]]>
	</script>
	
<?php endif; ?>

