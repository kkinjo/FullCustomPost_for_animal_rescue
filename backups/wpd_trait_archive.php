<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait wpd_trait_archive{
   
	function Aa_chage_posts_per_page( $query ){
         if (is_post_type_archive('pet_detail')) {
             $query->set( 'posts_per_page', $this->wpd_archive_page_post_count );
        return;
             }
     }
	 
	function wpd_get_category($wpd_cate_page,$wpd_cate_where=null,$wpd_cate_order=null) {
		global $wpdb;
		$wpd_cate_select_from = "SELECT * FROM ".$this->table_name;
		if( is_null ( $wpd_cate_where ) ) {
			$wpd_cate_where = "";
			}
			
		if( is_null ( $wpd_cate_order ) ) {
			$wpd_cate_order = "";
		}
		else{
			$wpd_cate_order = "order by ".implode(',', $wpd_cate_order);
		}
		
		$wpd_cate_page_limit = "limit ".(($wpd_cate_page-1)* $this->wpd_archive_page_post_count).",".$this->wpd_archive_page_post_count;
		
		$wpd_cate_sql = $wpd_cate_select_from." ".$wpd_cate_where." ".$wpd_cate_order." ".$wpd_cate_page_limit;
		$wpd_get_cate = $wpdb->get_results($wpd_cate_sql);
		return isset($wpd_get_cate) ? $wpd_get_cate : null;
	}
	
	function wpd_pagination( $pages = '', $range = 4 ) {
		$showitems = ( $range * 2 )+1;  
		global $paged;
		if( empty ( $paged ) ) $paged = 1;
		
		if( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( ! $pages ) {
				$pages = 1;
			}
        }  
		
		if(1 != $pages ) {
			echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
			if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
			
			for ($i=1; $i <= $pages; $i++) {
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ) ){
					echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
				}
			}
			
			if ($paged < $pages && $showitems < $pages) 
				echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
				echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
			echo "</div>\n";
		}
	}

}