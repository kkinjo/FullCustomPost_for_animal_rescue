<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait wpd_trait_theme{
	
	function Af_switch_themes_file($template) {

      global $wp_query,$wpdb,$post,$wpd_instance;
          if ( $this->wpd_category_name == $wp_query->post->post_type && is_archive())
              {
  		include(WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'/themes/'.'archive-' . $this->wpd_category_name . '.php');
                  exit;
              }
          elseif ($this->wpd_category_name == $wp_query->post->post_type && is_single())
              {
                  include(WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'/themes/'.'single-' . $this->wpd_category_name . '.php');
                  exit;
              }
  }
  
	function Aa_add_css_to_theme() {
  	global $wpdb,$post;

  	if ( $this->wpd_category_name == get_post_type( $post ))
              {
                  echo '<!-- プラグイン wans_pet_detail 用 CSS -->';
                  echo '<link type="text/css" rel="stylesheet" href="'.WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'css/wans_pet_detail.css">';
                  echo 'here is in '.$this->wpd_category_name;
              }
          else
              {
                  //var_dump($wpdb);
                  //echo "<BR><BR><BR><BR><BR>";
                  //print_r( get_defined_vars() );
              }
			  }

	function wpd_age($birthyear_almost_flag,$birthyear){
        $birthyear = str_replace("-", "",$birthyear); 
        if($birthyear > 19000000){
            $wpd_age = (int) ((date('Ymd')-$birthyear)/10000); 
            echo $birthyear_almost_flag.$wpd_age."歳"; 
        }
    }
	

}