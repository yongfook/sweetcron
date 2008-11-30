<rss version="2.0">
   <channel>
      <title><?php echo $this->config->item('lifestream_title')?> <?php echo $page_name?></title>
      <link><?php echo $this->config->item('base_url')?>feed</link>
      <description></description>
      <language>en-us</language>
      <docs>http://blogs.law.harvard.edu/tech/rss</docs>
      <generator>Sweetcron</generator>
      <webMaster><?php echo $this->config->item('admin_email')?></webMaster>
      <?php foreach ($items as $item): ?>
      <item>
         <title><?php echo htmlspecialchars(htmlspecialchars_decode($item->get_title()))?></title>
         <link><?php echo $item->get_permalink()?>/<?php echo $item->get_name()?></link>
         <description><![CDATA[<div><?php echo $item->get_content()?>
            	<?php if ($item->has_image() && !$item->has_video()): ?>
            	<img src="<?php echo $item->get_image()?>" alt="" />
            	<?php endif; ?>
            	<?php if ($item->has_video()): ?>
            	<?php echo $item->get_video()?>
            	<?php endif; ?>
         </div>]]></description>
         <pubDate><?php echo date('r', $item->get_date())?></pubDate>
         <guid><?php echo $item->get_permalink()?>/<?php echo $item->get_name()?></guid>
      </item>
      <?php endforeach; ?>
   </channel>
</rss>