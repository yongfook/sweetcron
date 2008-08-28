<div id="main_content">

<?php if ($items):?>

<?php $this->load->view('admin/_activity_list')?>

<?php else: ?>

<div class="error">You have no items :/</div>

<?php endif; ?>

</div>

<div id="side_content">

<?php if (!isset($items[0])): ?>
<p class="tip"><strong>Hello there!</strong><br />Looks like it's your first time here.  Why not start by <a href="<?php echo $this->config->item('base_url')?>admin/feeds">adding some feeds</a>?</p>

<?php else: ?>

<p class="tip"><strong>Greetings!</strong><br />This page shows the last 5 public items in your lifestream.</p>

<h4 class="side_title">Your Sweetcron Stats</h4>
<ul class="generic">
	<li>Public Items: <span class="highlight"><?php echo $item_count?></span></li>
	<li>Feeds: <span class="highlight"><?php echo $feed_count?></span></li>
	<li>Last Fetch: <span class="highlight"><?php echo timespan($this->config->item('last_fetch'))?> ago</span></li>
</ul>

<h4 class="side_title">Useful Links</h4>
<ul class="generic">
	<li><a rel="external" href="http://www.sweetcron.com">Sweetcron Homepage &raquo;</a></li>
	<li><a rel="external" href="http://code.google.com/p/sweetcron">Google Project Page &raquo;</a></li>
	<li><a rel="external" href="http://groups.google.com/group/sweetcron">Google Group &raquo;</a></li>
</ul>

<?php endif; ?>

</div>