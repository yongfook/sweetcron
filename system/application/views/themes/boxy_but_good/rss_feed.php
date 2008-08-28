<rss version="2.0">
   <channel>
      <title><?php echo $this->config->item('lifestream_title')?></title>
      <link><?php echo $this->config->item('base_url')?>feed</link>
      <description></description>
      <language>en-us</language>
      <docs>http://blogs.law.harvard.edu/tech/rss</docs>
      <generator>Sweetcron</generator>
      <webMaster><?php echo $this->config->item('admin_email')?></webMaster>
      <?php foreach ($items as $item): ?>
      <item>
         <title><?php echo $item->get_title()?></title>
         <link><?php echo $item->get_permalink()?></link>
         <description><![CDATA[<?php echo $item->get_content()?>]]></description>
         <pubDate><?php echo date('r', $item->get_date())?></pubDate>
         <guid><?php echo $item->get_original_permalink()?></guid>
      </item>
      <?php endforeach; ?>
   </channel>
</rss>