<div id="main_content">

<p id="breadcrumb"><a href="<?php echo $this->config->item('base_url')?>admin/feeds">Feeds Top</a> &rsaquo; Add Feed</p>

<?php if (isset($errors)): ?>
<div class="error"><?php echo $errors?></div>
<?php endif; ?>

<form action="" method="post" class="generic">

<p><label class="title" for="feed_url_input">Feed URL</label>
<input id="feed_url_input" type="text" class="text_input" name="url" value="http://" /></p>

<div class="buttons">
    <button type="submit" class="positive">
    <img src="<?php echo $this->config->item('base_url')?>public/images/system/icons/silk/add.png" alt="" />
    Add This Feed
    </button>
</div>

</form>

</div>

<div id="side_content">
<p class="tip"><strong>Not sure how to find a feed?</strong><br />Try simply putting the url of a webpage in the input box <br />(e.g. http://twitter.com/yongfook) &#8212; it works 90% of the time!</p>
</div>