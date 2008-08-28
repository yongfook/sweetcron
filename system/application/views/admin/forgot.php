<div id="main_content">

	<?php if (isset($errors)): ?>
	<div class="error">
	<?=$errors?>
	</div>
	<?php endif; ?>

    <?php if (isset($success)): ?>
	<div class="success">
	Instructions have been sent to your email acount.
	</div>
	<?php endif; ?>

    <form action="" method="post" class="generic">
    
        <p><label class="title">Email</label>
        <input <?php if (isset($success)): ?>disabled="disabled" <?php endif; ?>class="text_input" type="text" name="email" value="<?php echo $this->input->post('email')?>" /></p>
        <span class="input_explain">Input the administrator email address</span></p>
        
        <?php if (!isset($success)): ?>
        <div class="buttons">
        <button type="submit">
        <img src="<?php echo $this->config->item('base_url')?>public/images/system/icons/silk/key.png" alt=""/> 
        Reset Password
        </button>
        </div>
        <?php endif; ?>
    
    </form>

</div>