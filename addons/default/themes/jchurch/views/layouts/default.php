<!doctype html>
<head>
	<meta charset="utf-8">
	<title><?php echo $this->settings->site_name.' | '.$template['title'];?></title>
	<?php echo Asset::css('jchurch.css'); ?>
	<?php echo Asset::css('main.css'); ?>
	<?php //echo Asset::css('workless/workless.css'); ?>
	<?php //echo Asset::css('workless/application.css'); ?>
	<?php //echo Asset::css('workless/responsive.css'); ?>
	<?php file_partial('metadata'); ?>
</head>

<body>
    <div id="container" class="clearfix">
        <div id="header">
	        <?php file_partial('header'); ?>
        </div>
        

        
        
        
        

        <div id="content" class="grid_8 suffix_1">
	        <?php file_partial('notices'); ?>
	        <?php echo $template['body']; ?>
        </div>
        <!-- #content -->

        <div id="sidebar" class="grid_3">
	
        </div>
        <!-- #sidebar -->

        <div id="footer" class="grid_12">
	        <?php file_partial('footer'); ?>
        </div>
        <!-- #footer -->

</div>
</body>
</html>