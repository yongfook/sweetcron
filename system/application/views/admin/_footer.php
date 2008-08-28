</div>
<?php if (isset($this->config)):?>
<p><input type="hidden" value="<?php echo $this->config->item('base_url')?>" name="base_url" /></p>
<?php endif; ?>
<div class="clear"></div>
<!-- bai bai -->
<div id="footer"><a href="http://www.sweetcron.com" rel="external">Sweetcron</a> v<?php echo $this->config->item('sweetcron_version')?> &#8212; Automated Lifestream Software &copy; 2008</div>
</body>
</html>