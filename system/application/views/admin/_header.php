<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<title><?php echo $page_name?> &rsaquo; Sweetcron Admin Panel</title>
	<link rel="shortcut icon" href="<?php echo $this->config->item('base_url')?>favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo $this->config->item('base_url')?>public/css/admin.css" type="text/css" media="all" />
	<script type="text/javascript" src="<?php echo $this->config->item('base_url')?>public/scripts/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $this->config->item('base_url')?>public/scripts/admin.js"></script>
</head>

<body>

<div id="header">
    <p id="logo"><a href="<?php echo $this->config->item('base_url')?>admin"></a></p>
</div>

<div id="user_info">
	<?php if (!isset($this->auth)): ?>
	<h1>Welcome to Sweetcron</h1>
    <p>Installation - Step 1 of 1</a></p>	
    <?php elseif ($this->auth->is_logged()): ?>
	<h1><?php echo $this->config->item('lifestream_title')?></h1>
    <p>Logged in as <?php echo $this->data->user->user_login?> | <a href="<?php echo $this->config->item('base_url')?>admin/login/bye">logout?</a></p>
    <?php else: ?>
	<h1>Welcome to Sweetcron</h1>
    <p>Please log in</p>
    <?php endif; ?>
</div>

<?php if (isset($this->auth)): ?>
<div id="nav_container"<?php if (!$this->auth->is_logged()): ?> class="login"<?php endif; ?>>
    <?php if ($this->auth->is_logged()): ?>
    <ul id="nav">
	    <li id="nav_dashboard"<?php if ($this->sweetcron->is_current_page('')): ?> class="current_item"<?php endif; ?>><a href="<?php echo $this->config->item('base_url')?>admin">Dashboard</a></li>
	    <li<?php if ($this->sweetcron->is_current_page('Write')): ?> class="current_item"<?php endif; ?>><a href="<?php echo $this->config->item('base_url')?>admin/write">Write</a></li>
	    <li<?php if ($this->sweetcron->is_current_page('Items')): ?> class="current_item"<?php endif; ?>><a href="<?php echo $this->config->item('base_url')?>admin/items">Items</a></li>
	    <li<?php if ($this->sweetcron->is_current_page('Feeds')): ?> class="current_item"<?php endif; ?>><a href="<?php echo $this->config->item('base_url')?>admin/feeds">Feeds</a></li>
	    <li<?php if ($this->sweetcron->is_current_page('Options')): ?> class="current_item"<?php endif; ?>><a href="<?php echo $this->config->item('base_url')?>admin/options">Options</a></li>
    </ul>
    <?php endif; ?>
</div>
<?php else: ?>
<div id="nav_container" class="login"></div>
<?php endif;?>