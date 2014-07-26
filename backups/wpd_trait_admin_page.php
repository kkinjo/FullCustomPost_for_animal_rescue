<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait wpd_trait_admin_page{
	
	function Af_add_petname_col_name( $columns ) {
        global $post_type;
        if ($post_type == 'pet_detail') {
        $columns1 = array_slice($columns, 0,2); 
        $columns2 = array_slice($columns, 3); 
        $add_columns1 =  array("wpd_col" => "ペット名");
        $add_columns2 =  array("thumbnail" => "サムネイル");
        
        $columns = $columns1 + $add_columns1 + $add_columns2 + $columns2;
        }
        
        $test_dbg = $columns;
	return $columns;
    }
    
	function Aa_add_petname_col_data( $column, $post_id ) {
        global $wpd_instance,$test_dbg;
	if( $column == 'wpd_col' ) {
		echo $this->wpd_get_col_data( $post_id );
	}
	if( $column == 'thumbnail' ) {
		echo '<img class="arch_thum_img" src='.$wpd_instance->wpd_plugin_url.'thumbnail/'.$post_id.'_thumb.jpg height=30px/>';
	}
    }

	function wpd_get_col_data($post_id){
        global $wpdb,$wpd_instance;
        $wpd_colname = $wpdb->get_results($wpdb->prepare( "SELECT pet_name FROM ".$this->table_name. " WHERE post_id = %d", $post_id),ARRAY_N );
        return $wpd_colname[0][0];
    }
    
	function Aa_add_style_to_admin() {
      //wp_enqueue_style('thickbox');
  }
   
	function Aa_add_jscript_to_admin() {
      //wp_enqueue_script('media-upload');
      //wp_enqueue_script('thickbox');
      wp_enqueue_script('jquery-ui-datepicker');
      wp_enqueue_script('jquery-ui-i18n', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/jquery-ui-i18n.js',array('jquery'));
      wp_enqueue_script('jcrop');
      wp_enqueue_script('functionsgg', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/wans_pet_detail_admin.js',array('jquery'));

  }
	
	function Aa_change_css_on_post_edit() {
		$pt = get_post_type();
		if ($pt == 'pet_detail') {
			$hide_postdiv_css = '<style type="text/css">#postdiv, #postdivrich,#titlediv, #titlewrap { display: none; }</style>';
			echo $hide_postdiv_css;
			
			$url = get_option('siteurl');
			echo '<link rel="stylesheet" type="text/css" href="' . $url . '/wp-content/plugins/'.$this->wpd_plugin_dirname.'css/wans_admin.css' . '" />';
			echo '<link rel="stylesheet" type="text/css" href="' . $url . '/wp-content/plugins/'.$this->wpd_plugin_dirname.'css/jquery.Jcrop.min.css' . '" />';
			echo '<link rel="stylesheet" type="text/css" href="' . $url . '/wp-content/plugins/'.$this->wpd_plugin_dirname.'css/cupertino/jquery-ui-1.10.3.custom.css' . '" />';
			echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
			//echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/jquery.Jcrop.min.js"></script>';
			//echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/wans_pet_detail_admin.js"></script>';
		}
	}
      	
	function Af_change_title_here_on_post_edit($title) {
      $screen = get_current_screen();
      if ($screen->post_type == 'pet_detail') {
          $title = 'ワン名を入力してください';
      }
      return $title;
  }
	

}