<div id="sidebar_container">

    <div id="explanation">
    Blogs are evolving.  You're looking at my <strong>Lifestream</strong>, a real-time flow of my activity across various websites, with the occasional blog post for nourishment.
    </div>
    
    <h3>Popular Tags</h3>
    <ul class="tag_list">
    	<?php foreach($popular_tags as $tag): ?>
    	<li><a href="<?php echo $this->config->item('base_url')?>items/tag/<?php echo $tag->slug?>"><?php echo $tag->name?></a></li>
    	<?php endforeach; ?>
    </ul>

    <h3>Search</h3>
    <form id="search_form" method="post" action="<?php echo $this->config->item('base_url')?>items/do_search">
    <p><input type="text" name="query" class="text_input" value="<?php if (isset($query)): echo $query; endif;?>" /></p>
    <p><input type="submit" /></p>
    </form>

</div>