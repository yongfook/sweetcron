<div id="main_content">

	<?php if (isset($errors)): ?>
	<div class="error">
	<?php echo $errors?>
	</div>
	<?php endif; ?>

    <form action="" method="post" class="generic">
    
        <p><label class="title">Username</label>
        <input class="text_input" type="text" name="username" value="<?php echo $this->input->post('username')?>" /></p>
    
        <p><label class="title">Password</label>
        <input class="text_input" type="password" name="password" value="<?php echo $this->input->post('password')?>" /></p>
        
        <div class="buttons">
        <button type="submit">
        <img src="<?php echo $this->config->item('base_url')?>public/images/system/icons/silk/key.png" alt=""/> 
        Login
        </button>
        </div>
    
    </form>

</div>

<div id="side_content">
<p class="tip"><strong>Forgot Password?</strong><br /><a href="<?php echo $this->config->item('base_url')?>admin/login/forgot">Reset my password &raquo;</a></p>

</div>