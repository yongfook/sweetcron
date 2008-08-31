$(document).ready(function(){
	
	var base_url = $('input[name=base_url]').val();
	
	$('a.change_password').click(function(){
		$('div#change_password_container').toggle();
		$('a.change_password').toggleClass('toggle');
		});
	
	//show all tags
	$('a.show_all_tags').click(function(){
		if ($('ul.tag_list.some').is(':visible')) {
			$('ul.tag_list.some').slideUp('normal', function(){
				$('ul.tag_list.all').slideDown('normal');
				$('a.show_all_tags').html('Show Some Tags');
				});
		} else {
			$('ul.tag_list.all').slideUp('normal', function(){
				$('ul.tag_list.some').slideDown('normal');
				$('a.show_all_tags').html('Show All Tags');
				});			
		}
		});
	
	//reset cron key
	$('a.reset_cron_key').click(function(){
		$.ajax({
		   type: "POST",
		   url: base_url + "admin/ajax/reset_cron_key",
		   success: function(msg){
		   	$('span#cron_key').html(msg);
		   }
		 });
		});
	
	//cron url display
	$('form#options_form').change(function(){
		if ($('input#radio_true').is(':checked')) {
			$('span#cron_url').show();
		} else {
			$('span#cron_url').hide();
		}
		});
    
    //save draft button
    $('a.draft_button').click(function(){
        $('input[name=draft]').val('true');
        $('form').submit();
        });

    //must confirm link
	$('a.confirm_first').click(function(){
		if (confirm('Sure?')) {
			return true;
		} else {
			return false;	
		}
		});	        

	//unpublish button
	$('ul.activity_list li.unpublish_this a').click(function(){
			var id = $(this).parents('li.item').attr('id').replace('item_','');
		$.ajax({
		   type: "POST",
		   url: base_url + "admin/ajax/unpublish_item",
		   data: "id=" + id,
		   success: function(){
		   	$('li#item_' + id).removeClass('publish');		   		
		   	$('li#item_' + id).addClass('draft');		   		
		   }
		 });
		});

	//publish button
	$('ul.activity_list li.publish_this a').click(function(){
			var id = $(this).parents('li.item').attr('id').replace('item_','');
		$.ajax({
		   type: "POST",
		   url: base_url + "admin/ajax/publish_item",
		   data: "id=" + id,
		   success: function(){
		   	$('li#item_' + id).removeClass('draft');
		   	$('li#item_' + id).addClass('publish');		   		
		   }
		 });
		});

    //external links, xhtml friendly
    $('a[rel=external]').click(function(){
        //open new window
        var myurl = $(this).attr('href');
        window.open(myurl, 'external');
        return false;
        });

	if ($('li.item div.hideshow:parent').length) {
		$('li.item div.hideshow:parent').each(function(){
			$(this).parents('li.item').children('ul.item_tools').children('li.expand').css({display: 'inline'});
			});
	}
	
	$('li.expand a').click(function(){
		$(this).parents('li.item').children('div.item_container').children('div.hideshow').slideToggle();
		if ($(this).parents('li').hasClass('expanded')) {
			$(this).parents('li').removeClass('expanded');
			$(this).html('Expand');
		} else {
			$(this).parents('li').addClass('expanded');
			$(this).html('Collapse');
		}
		});
    
});