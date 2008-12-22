<?php if (!defined('BASEPATH')) exit('No direct access allowed.');
/**
 * Sweetcron
 *
 * Self-hosted lifestream software based on the CodeIgniter framework.
 *
 * Copyright (c) 2008, Yongfook and Egg & Co.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * 	* Redistributions of source code must retain the above copyright notice, this list of
 * 	  conditions and the following disclaimer.
 *
 * 	* Redistributions in binary form must reproduce the above copyright notice, this list
 * 	  of conditions and the following disclaimer in the documentation and/or other materials
 * 	  provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package Sweetcron
 * @copyright 2008, Yongfook and Egg & Co.
 * @author Yongfook
 * @link http://sweetcron/ Sweetcron
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

class Sweetcron {
	
    function __construct() {
    	$this->CI =& get_instance();
    	$this->CI->config->set_item('sweetcron_version', '1.08e');
    }
	
	function fetch_items()
	{
		if(!ini_get('safe_mode')){
			//requires php to not be running in "safe mode"
			set_time_limit(0);
		}
		//soz
		$option->option_name = 'last_fetch';
		$option->option_value = time();
		$this->CI->option_model->add_option($option);
						
        $feeds = $this->CI->feed_model->get_active_feeds();
        if ($feeds) {
		    foreach ($feeds as $feed) {
	   			$this->CI->simplepie->set_feed_url($feed->feed_url);
				$this->CI->simplepie->enable_cache(FALSE);
				$this->CI->simplepie->init();
				$items = $this->CI->simplepie->get_items();
				$this->add_new_items($items, $feed);
		    }
        }
            
	}

	function add_new_items($items, $feed)
	{
		foreach ($items as $item) {
		
			$new->item_data = array();
			$new->item_data['title'] = $item->get_title();
			$new->item_data['permalink'] = $item->get_permalink();
			$new->item_data['content'] = $item->get_content();
			$new->item_data['enclosures'] = $item->get_enclosures();
			$new->item_data['categories'] = $item->get_categories();							
			$new->item_data['tags'] = $this->get_tags($new->item_data);							
			$new->item_data['image'] = $this->get_image($item->get_content());
			
			//build out clean item
			$new->item_status = 'publish';
			$new->item_date = strtotime($item->get_date('D M j G:i:s Y'));
			$new->item_title = $this->CI->input->xss_clean(trim(strip_tags($item->get_title())));
			$new->item_permalink = $item->get_permalink();
			$new->item_content = $this->CI->input->xss_clean(trim(strip_tags($item->get_content())));
			$new->item_name = url_title($new->item_title);
			$new->item_feed_id = $feed->feed_id;
						
			$new = $this->extend('pre_db', $feed->feed_domain, $new, $item);
		
			//and add
			$this->CI->item_model->add_item($new);
		
		}		
	}
	
	function pseudo_cron()
	{
		if ($this->CI->config->item('cron_type') == 'pseudo') {
	    	//time in seconds between each pseudo cron
	    	//if you want more frequent cron updates it's better to rely on "true cron" and to turn off pseudo cron
	    	$interval = 1800; //1800 = 30 minutes
	    	if (($this->CI->config->item('last_access') - $this->CI->config->item('last_fetch')) > $interval) {
	    		$this->fetch_items();
	    	}
	    }		
	}
	
	function extend($method = 'pre_db', $feed_domain = NULL, $item = NULL, $simplepie_object = NULL)
	{
		//we can extend what sweetcron does at various points in the import / output process by using plugin architecture
		//see system/applications/plugins for example plugins
		if ($feed_domain && $item) {
			$class = str_replace('.', '_', $feed_domain);
			$plugin = BASEPATH.'application/plugins/'.$class.'.php';
			//support subdomain with base domain plugin if no subdomain-specific plugin exists
			$domain = explode('.', $feed_domain);
			if (isset($domain[2])) {
				//this is a subdomain of a base domain
				$is_subdomain = TRUE;
				$base_class = str_replace($domain[0].'_', '', $class);
				$base_plugin = BASEPATH.'application/plugins/'.$base_class.'.php';
			} else {
				$is_subdomain = FALSE;	
			}
			if (file_exists($plugin) || ($is_subdomain && file_exists($base_plugin))) {
				//check if already loaded
				if ($is_subdomain && file_exists($base_plugin)) {
					if (!method_exists($base_class, $method)) {
						include(BASEPATH.'application/plugins/'.$base_class.'.php');
					}
					$plugin = new $base_class;
				} else {
					if (!method_exists($class, $method)) {
						include(BASEPATH.'application/plugins/'.$class.'.php');
					}					
					$plugin = new $class;
				}
				return $plugin->$method($item, $simplepie_object);
			} else {
				return $item;	
			}
		}
	}
	
	function get_tags($data)
	{
		$tags = '';
		$other_tags = '';
		//attempt to pull from enclosures
		if (isset($data['enclosures'][0]->categories[0]->term)) {
			$tags = html_entity_decode($data['enclosures'][0]->categories[0]->term);
			$tags = explode(' ', $tags);
		}		
		
		//attempt to pull from categories
		if (isset($data['categories'][0]->term)) {
			foreach ($data['categories'] as $category) {
				//sometimes a tag is an ugly url that I don't think we want...
				if (substr($category->term, 0, 7) != 'http://') {
					$other_tags[] = html_entity_decode($category->term);						
				}
			}
		}
		
		$tags_count = count($tags);
		$other_tags_count = count($other_tags);
		
		//lets go with whichever has the most...
		if ($tags_count > $other_tags_count) {
			$tags = $tags;	
		} else {
			$tags = $other_tags;	
		}
		
		//clean before returning...
		
		return $tags;
	}


	function get_image($html) {
		//kudos to:
		//http://zytzagoo.net/blog/2008/01/23/extracting-images-from-html-using-regular-expressions/
        if (stripos($html, '<img') !== false) {
            $imgsrc_regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
            preg_match($imgsrc_regex, $html, $matches);
            unset($imgsrc_regex);
            unset($html);
            if (is_array($matches) && !empty($matches)) {
                return $matches[2];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

	function is_current_page($page_name)
	{
		//just a simple tab highlighter for the admin panel
		if ($this->CI->uri->segment(2) == strtolower($page_name)) {
			return true;
		}
	}
	
	function integrity_check()
	{
		if (!$this->CI->db->table_exists('feeds') || !$this->CI->db->table_exists('items') || !$this->CI->db->table_exists('options') || !$this->CI->db->table_exists('tags') || !$this->CI->db->table_exists('tag_relationships') || !$this->CI->db->table_exists('users')) {
			if (file_exists(BASEPATH.'../.htaccess')) {
			die('Whoo Hoo!  Almost there - now just run the <a href="'.$this->CI->config->item('base_url').'admin/install'.'">install script</a>.');
			} else {
			die('Looks like you are missing an .htaccess file...<br />For instructions on creating one, please see <a href="http://code.google.com/p/sweetcron/wiki/Installation">the installation documentation</a>');
			}
		}		
	}
	
	function install_check()
	{
		if ($this->CI->db->table_exists('feeds') || $this->CI->db->table_exists('items') || $this->CI->db->table_exists('options') || $this->CI->db->table_exists('tags') || $this->CI->db->table_exists('tag_relationships') || $this->CI->db->table_exists('users')) {
			die('Sweetcron is already (or partially) installed.  If you wish to reinstall, please clear your database first.');
		}
	}
	
	function compatibility_check()
	{
		//checks php version
		if (version_compare(PHP_VERSION, '5.0.0', '<')) {
			die('Sorry, Sweetcron is for PHP5 and above.  Your version of PHP is lower than that.  Time to upgrade?');
		}
		if (function_exists('apache_get_modules')) {
			$modules = apache_get_modules();
	        if (!in_array("mod_rewrite", $modules)) {
				die('Sorry, it looks like your server does not have mod_rewrite installed.  Please contact your webhost to get it enabled.');
	        }
		}
	}
	
	function do_search()
	{
		if ($this->CI->input->post('query')) {
			//strip out stuff and send to page
			$query = urlencode($this->CI->input->post('query'));
			header('Location: '.$this->CI->config->item('base_url').'items/search/'.$query);
		} else {
		    show_error('You must type some keywords to search');   
        }
	}
	
	function get_single_item_page($item_id = NULL)
	{
		if ($item_id) {
		    //remove query string (some 3rd party commenting services refer back after commenting)
		    $item_id = explode('?', $item_id);
		    $item_id = $item_id[0];
			$data->item = $this->CI->item_model->get_public_item_by_id($item_id);	
			$data->page_name = $data->item->get_title();
			$data->popular_tags = $this->CI->tag_model->get_all_tags('count', 50);
			$data->all_tags = $this->CI->tag_model->get_all_tags('count');
	 		$this->CI->load->view('themes/'.$this->CI->config->item('theme').'/_header', $data);
		    $this->CI->load->view('themes/'.$this->CI->config->item('theme').'/single', $data);
		    $this->CI->load->view('themes/'.$this->CI->config->item('theme').'/_footer', $data);
		}
	}
	
	function query_items($type = 'site', $query = NULL, $offset = 0, $limit = 10)
	{
		return $this->get_items_page($type, NULL, TRUE, $query, NULL, TRUE, $offset, $limit);
	}
	
	function get_items_page($type = 'index', $current_page_num = 1, $public = FALSE, $query = NULL, $rss_filter = NULL, $query_items = NULL, $offset = NULL, $limit = NULL)
	{
		
		//return raw items for query_items()
		if ($query_items) {
			if ($type == 'site') {
				return $this->CI->item_model->get_items_by_feed_domain($offset, $limit, $query, $public);
			} elseif ($type == 'tag') {
				return $this->CI->item_model->get_items_by_tag($offset, $limit, $query, $public);       				
			} elseif ($type == 'search') {
				return $this->CI->item_model->get_items_by_search($offset, $limit, $query, $public);       				
			}
			exit();
		}

        $data->blog_posts = $this->CI->item_model->get_items_by_feed_domain(0, 10, 'sweetcron', $public);
        $data->active_feeds = $this->CI->feed_model->get_active_feeds(TRUE);
		$data->popular_tags = $this->CI->tag_model->get_all_tags('count', 50);
		$data->all_tags = $this->CI->tag_model->get_all_tags('count');
		$data->page_type = $type;
		
		if ($type == 'rss_feed') {
			if (!$rss_filter) {
				$data->page_name = '';
				$data->items = $this->CI->item_model->get_all_items(0, 20, $public);
			} elseif ($rss_filter == 'tag') {
				$data->page_name = '- tagged with '.$query;
				$data->items = $this->CI->item_model->get_items_by_tag(0, 20, $query, $public);
			} elseif ($rss_filter == 'search') {
				$data->page_name = '- search for '.$query;
				$data->items = $this->CI->item_model->get_items_by_search(0, 20, $query, $public);
			} elseif ($rss_filter == 'site') {
				$data->page_name = '- imported from '.$query;
				$data->items = $this->CI->item_model->get_items_by_feed_domain(0, 20, $query, $public);
			}
			$this->CI->load->view('themes/'.$this->CI->config->item('theme').'/rss_feed', $data);
		} elseif ($type == 'static_page') {
			
			$page = $this->CI->uri->segment(2);
			$data->page_name = ucwords($page);
			if (file_exists(BASEPATH.'application/views/themes/'.$this->CI->config->item('theme').'/'.$page.'.php')) {
		 		$this->CI->load->view('themes/'.$this->CI->config->item('theme').'/_header', $data);
			    $this->CI->load->view('themes/'.$this->CI->config->item('theme').'/'.$page, $data);
			    $this->CI->load->view('themes/'.$this->CI->config->item('theme').'/_footer', $data);
			} else {
				show_404();
			}	
		} else {

	        $this->CI->page->SetItemsPerPage($this->CI->config->item('per_page'));
	        $this->CI->page->SetQueryStringVar('page');
	        $this->CI->page->SetLinksFormat('&lsaquo;',' ','&rsaquo;');		
	        $this->CI->page->SetLinksToDisplay(10);
	        $this->CI->page->SetCurrentPage($current_page_num);
	        
	        if ($public) {
	            $admin = '';
            } else {
                $admin = 'admin/';
            }
	        
	        //conditionals depending on page type
	        if ($type == 'index') {
				$data->page_name = 'Home';
		        $this->CI->page->SetItemCount($this->CI->item_model->count_all_items($public));     
		        if ($public) {   
		        	$this->CI->page->SetLinksHref($this->CI->config->item('base_url').$admin);
		        } else {
		        	$this->CI->page->SetLinksHref($this->CI->config->item('base_url').$admin.'items/');
		        }
				$data->items = $this->CI->item_model->get_all_items($this->CI->page->GetOffset(), $this->CI->page->GetSqlLimit(), $public);
	        } elseif ($type == 'search') {
				$data->page_name = 'Items Search';
		        $this->CI->page->SetItemCount($this->CI->item_model->count_items_by_search($query, $public));        
		        $this->CI->page->SetLinksHref($this->CI->config->item('base_url').$admin.'items/search/'.$query.'/');
				$data->items = $this->CI->item_model->get_items_by_search($this->CI->page->GetOffset(), $this->CI->page->GetSqlLimit(), $query, $public);       
	        } elseif ($type == 'tag') {
				$data->page_name = 'Items Tag';
		        $this->CI->page->SetItemCount($this->CI->item_model->count_items_by_tag($query, $public));        
		        $this->CI->page->SetLinksHref($this->CI->config->item('base_url').$admin.'items/tag/'.$query.'/');
				$data->items = $this->CI->item_model->get_items_by_tag($this->CI->page->GetOffset(), $this->CI->page->GetSqlLimit(), $query, $public);        
	        } elseif ($type == 'site') {
				$data->page_name = 'Items Site';
		        $this->CI->page->SetItemCount($this->CI->item_model->count_items_by_feed_domain($query, $public));        
		        $this->CI->page->SetLinksHref($this->CI->config->item('base_url').$admin.'items/site/'.$query.'/');
				$data->items = $this->CI->item_model->get_items_by_feed_domain($this->CI->page->GetOffset(), $this->CI->page->GetSqlLimit(), $query, $public);        
	        }

			$data->page_query = $query;
	
			if ($query && $type == 'search') {
				$data->query = $query;
			}
	
			if ($query && $type == 'tag') {
				$data->tag = urldecode($query);
			}

			if ($query && $type == 'site') {
				$data->site = $query;
			}			
	
			//load view
			if ($public) {
	            $data->pages = $this->CI->page->GetPageLinks();
		 		$this->CI->load->view('themes/'.$this->CI->config->item('theme').'/_header', $data);
			    $this->CI->load->view('themes/'.$this->CI->config->item('theme').'/items', $data);
			    $this->CI->load->view('themes/'.$this->CI->config->item('theme').'/_footer', $data);	
			} else {
	            $data->pages = $this->CI->page->GetPageLinks();
			    $this->CI->load->view('admin/_header', $data);
			    $this->CI->load->view('admin/items', $data);
			    $this->CI->load->view('admin/_footer');			
			}

		}
	}

}
?>