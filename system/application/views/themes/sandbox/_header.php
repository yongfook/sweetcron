<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
	
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta name="description" content="" />
		<title><?php echo $page_name?> &rsaquo; <?php echo $this->config->item('lifestream_title')?></title>
		<link rel="stylesheet" href="<?php echo $this->config->item('base_url')?>public/css/reset.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $this->config->item('theme_folder')?>main.css" type="text/css" media="screen" />
		<?php if ($page_type == 'index'): ?>
		<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="<?php echo $this->config->item('base_url')?>feed" />
		<?php else: ?>
		<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="<?php echo $this->config->item('base_url')?>feed/<?php echo $page_type?>/<?php echo $page_query?>" />
		<?php endif; ?>

	</head>
	
	<body>
	<div id="header">
		<div class="center_box">
	
			<ul id="navigation">
				<li<?php if (!$this->uri->segment(1) || $this->uri->segment(1) == 'items' || $this->uri->segment(1) == 'page'): ?> class="current"<?php endif; ?>><a href="<?php echo $this->config->item('base_url')?>" title="My Lifestream">Lifestream</a></li>
				<li<?php if ($this->uri->segment(2) == 'contact'): ?> class="current"<?php endif; ?>><a href="<?php echo $this->config->item('base_url')?>p/contact/" title="Contact Me">Contact</a></li>
			</ul>
			<h1><a href="<?php echo $this->config->item('base_url')?>"><?php echo $this->config->item('lifestream_title')?></a></h1>
		</div>
	</div>
	
	<div class="center_box">
