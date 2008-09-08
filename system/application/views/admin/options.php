<div id="main_content">

<?php if (isset($errors)): ?>
<div class="error"><?php echo $errors?></div>
<?php endif; ?>

<form action="" method="post" class="generic" id="options_form">

<p><label class="title" for="lifestream_title_input">Lifestream Title</label>
<input id="lifestream_title_input" type="text" class="text_input" name="lifestream_title" value="<?php echo $this->config->item('lifestream_title')?>" /></p>

<p><label class="title" for="admin_email_input">Admin Email</label>
<input id="admin_email_input" type="text" class="text_input" name="admin_email" value="<?php echo $this->config->item('admin_email')?>" /></p>

<p><label class="title" for="items_per_page_input">Items Per Page</label>
<span class="option_container">
<span class="option"><input id="items_per_page_input" type="text" name="per_page" value="<?php echo $this->config->item('per_page')?>" size="5" /></span>
</span>
</p>

<p><label class="title" for="radio_pseudo">Cron Type</label>
<span class="option_container">
<span class="option"><input <?php if ($this->config->item('cron_type') == 'pseudo'): ?>checked="checked" <?php endif;?>type="radio" name="cron_type" value="pseudo" id="radio_pseudo" /> <label for="radio_pseudo">Pseudo Cron</label> (updates every 30 mins)</span>
<span class="option"><input <?php if ($this->config->item('cron_type') == 'true'): ?>checked="checked" <?php endif;?>type="radio" name="cron_type" value="true" id="radio_true" /> <label for="radio_true">True Cron</label></span>
</span>
</p>
<span <?php if ($this->config->item('cron_type') == 'pseudo'): ?>style="display: none" <?php endif;?>id="cron_url" class="input_explain"><strong><?php echo $this->config->item('base_url')?>cron/fetch_items/<span id="cron_key"><?php echo $this->config->item('cron_key')?></span></strong><br />Point your cron job to the above url <a href="#reset" class="reset_cron_key confirm_first">Reset Key?</a></span>

<p><label class="title" for="theme_input">Theme</label>
<span class="option_container">
<span class="option"><select id="theme_input" name="theme">
	<?php foreach ($themes as $theme): ?>
    <option <?php if ($this->config->item('theme') == $theme->folder): ?>selected="selected" <?php endif;?>value="<?php echo $theme->folder?>"><?php echo $theme->name?></option>
    <?php endforeach; ?>
</select></span>
</span>
</p>

<p><a href="#change_password" class="change_password<?php if (isset($this->validation->new_password_error) || isset($this->validation->new_password_confirm_error)):?> toggle"<?php endif; ?>">Change Password</a></p>

<div id="change_password_container"<?php if (isset($this->validation->new_password_error) || isset($this->validation->new_password_confirm_error)):?> style="display: block;"<?php endif; ?>>
<p><label class="title" for="new_password_input">New Password</label>
<input id="new_password_input" type="password" class="text_input" name="new_password" value="<?php echo $this->input->post('new_password')?>" /></p>

<p><label class="title" for="new_password_confirm_input">New Password Confirm</label>
<input id="new_password_confirm_input" type="password" class="text_input" name="new_password_confirm" value="<?php echo $this->input->post('new_password_confirm')?>" /></p>
</div>

<div class="buttons">
    <button type="submit" class="positive">
    <img src="<?php echo $this->config->item('base_url')?>public/images/system/icons/silk/accept.png" alt="" />
    Save Options
    </button>
</div>

</form>

</div>

<div id="side_content">
<p class="tip"><strong>Remember!</strong><br />After you change any options, click the "Save Options" button at the bottom of the page.</p>

</div>