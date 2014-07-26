<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait wpd_trait_DMI{

	function Aa_DMI_insert_update($post_id) {
    if (!isset($_POST[$this->table_name])) return;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  return;
      if ( !wp_verify_nonce( $_POST[$this->table_name], plugin_basename( __FILE__ ) ) )  return;

    global $wpdb;
    global $post;

    //リビジョンを残さない
    if ($post->ID != $post_id) return;

    //カスタム投稿タイプ『pet_detail』でないと動作しない。
    if ($post->post_type != 'pet_detail') return;


    // $temp_列名を操作するために、はじめにレコードをとっておく
    $wpd_data_set = $wpdb->get_results(
      $wpdb->prepare( "SELECT * FROM
        ".$this->table_name. " WHERE
        post_id = %d", $post_id
      )
    );
    $wpd_data_set = isset($wpd_data_set[0]) ? $wpd_data_set[0] : null;
    $set_arr = array();
    
    if($wpd_data_set){
        // 保存するための配列 $set_arr に $temp_列名を入れるための処理
        
        foreach($wpd_data_set as $colname => $value){
            if($colname == "post_id"){}
            else{
                $set_arr[$colname]=$_POST[$colname];
                }
            $$colname = isset($value) ? $value : null; 
            }

    } else {
        $get_colname = $wpdb->get_results("show columns from ".$this->table_name ,ARRAY_A ); 
        foreach ($get_colname as $key => $value) {
            if($value["Field"] == "post_id"){}
            else{
                $set_arr[$value["Field"]]=$_POST[$value["Field"]];
            }
        }
    }
    
    //レコードがなかったら新規追加あったら更新
    if (empty($wpd_data_set)) {
        //$debug_word = var_export($set_arr,true);  
        $set_arr['post_id'] = $post_id;
        $wpdb->insert( $this->table_name, $set_arr);
    } else {
        $wpdb->update( $this->table_name, $set_arr, array('post_id' => $post_id));      
    }
    $wpdb->show_errors();
    
    if( $photo !== $set_arr['photo'] || $photo_coordinates !== $set_arr['photo_coordinates']){
          $this->wpd_update_photo($set_arr['photo_coordinates'],$set_arr['photo'],$post_id);
   }
  }
 
	function Aa_DMI_delete($post_id) {
    global $wpdb;
    $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE post_id = %d", $post_id) );
  } 	
  
	/**
	 * Aa_add_meta_box で使用されています。
	 */
	function wpd_html () {
    wp_nonce_field( plugin_basename( __FILE__ ), $this->table_name );
    global $post;
    global $wpdb;

    $wpd_data_set = $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM ".$this->table_name. " WHERE post_id = %d", $post->ID)
			);
    $wpd_data_set = isset($wpd_data_set[0]) ? $wpd_data_set[0] : null;
    if(!empty($wpd_data_set)){
        foreach($wpd_data_set as $colname => $value){
        $$colname = isset($value) ? $value : null;
    }
    

    
    /* 配列カウント */
    if(!is_null($status_history)){
    $status_history = preg_replace('/,+\z/',"",$status_history);
    $status_history_array = explode(',', $status_history);
    $status_history_count = count($status_history_array) ;
    }

    if(!is_null($related_url)){
    $related_url = preg_replace('/,+\z/',"",$related_url);
    $related_url_array = explode(',', $related_url);
    $related_url_count = count($related_url_array) ;
    }
    }else{
        $wpd_new_wansid_res = $wpdb->get_results( $wpdb->prepare( "SELECT MAX( meta_id ) +1 as value FROM ".$this->table_name,ARRAY_N ));
        $meta_id = $wpd_new_wansid_res[0]->value;
    }

    /* 画像ファイル */
    if(is_null($photo)){
        $photo = WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'thumbnail/noimage_o.jpg';
        $photo_coordinates = "0,55,410,260,410,205";
    }
    
    ?>
    <div>
        <input type="hidden" name="meta_id" value="<?php echo $meta_id ?>"> 
        <span>ワンID:</span><span>WANS:<?php echo $meta_id ?></span>
        <div>ワンの名前</div>
        <input id="pet_name" type="input" name="pet_name"  value="<?php echo $pet_name ?>" />
        <div id="previe_target"></div>  
        <div id="preview-pane">カバー画像プレビュー
            <div class="preview-container">
                <img id="wpd_cover_photo_preview" src="<?php echo $photo ?>" style="width: 600px"/>
            </div>
        </div>
        
        
        <div><BR>オリジナル画像<BR><img id="wpd_cover_photo_orginal" src="<?php echo $photo ?>" style="width: 410px;"/></div>
        <div>
            <input type="hidden" name="photo"  class="wpd_input_class" value="<?php echo $photo ?>" />
            <input type="hidden" name="photo_coordinates"  value="<?php echo $photo_coordinates ?>" />
            <a class="media-upload" href="JavaScript:void(0);" rel="wpd_cover_photo">Select File</a>
        </div>
       
       <h4 ><span>基本情報</span></h4> 
       <div class="wpd_coltitle_box">
           <div class="wpd_coltitle_row">
               <div class="wpd_coltitle">だいたい</div>
               <div class="wpd_coltitle">生まれ年</div>
               <div class="wpd_coltitle">没年</div>
           </div>
           <div class="wpd_coltitle_row">    
               <div class="wpd_col_data"><input class="wpd_input_class" name="birthyear_almost_flag" value="<?php echo $birthyear_almost_flag ?>" /></div>
               <div class="wpd_col_data"><input class="wpd_input_class wpd_tdp" name="birthyear"     value="<?php echo $birthyear ?>" /></div>
               <div class="wpd_col_data"><input class="wpd_input_class wpd_tdp" name="Deathyear"     value="<?php echo $Deathyear ?>" /></div>
           </div>
       </div>
       <div class="wpd_coltitle_box">
           <div class="wpd_coltitle_row">       
               <div class="wpd_coltitle">性別</div>
               <div class="wpd_coltitle">色</div>
               <div class="wpd_coltitle">犬種/猫種</div>
           </div> 
           <div class="wpd_coltitle_row">         
               <div class="wpd_col_data"><input class="wpd_input_class" name="sex"                   value="<?php echo $sex ?>" /></div>
               <div class="wpd_col_data"><input class="wpd_input_class" name="color"                 value="<?php echo $color ?>" /></div>
               <div class="wpd_col_data"><input class="wpd_input_class" name="breed"                 value="<?php echo $breed ?>" /></div>
           </div>
       </div>
       <div class="wpd_coltitle_box">
           <div class="wpd_coltitle_row"> 
               <div class="wpd_coltitle">体重</div>   
               <div class="wpd_coltitle">高さ</div>  
           </div>
           <div class="wpd_coltitle_row"> 
               <div class="wpd_col_data"><input class="wpd_input_class" name="weight"                value="<?php echo $weight ?>" /></div>
               <div class="wpd_col_data"><input class="wpd_input_class" name="height"                value="<?php echo $height ?>" /></div>
           </div>
       </div>
       
       <h4 ><span>状態</span></h4>
       <div class="wpd_coltitle_box">
           <div class="wpd_coltitle_row">
               <div class="wpd_coltitle">ワンズ登録日</div>
               <div class="wpd_coltitle">現在のステータス</div>
           </div>
           <div class="wpd_coltitle_row">    
               <div class="wpd_col_data"><input name="wans_reg_date" class="wpd_input_class wpd_tdp" value="<?php echo $wans_reg_date ?>" /></div>
               <div class="wpd_col_data"><input name="now_status"    class="wpd_input_class"         value="<?php echo $now_status ?>" /></div>
           </div>
       </div>
       <div>ステータス履歴</div>                           
            <?php 
            
            for ($i = 0; $i <= $status_history_count+1; $i+2) {
                
                echo '<div><input class="status_history_array"    name="status_history_array'.$i.'"    value="'.$status_history_array[$i].'" />';
                echo '<input class="status_history_array wpd_tdp" name="status_history_array'.$i++.'"  value="'.$status_history_array[$i++].'" /></div>';
                
                }
            ?><input type="hidden" name="status_history" value="<?php echo $status_history ?>">
            
       <div class="wpd_coltitle_box">
           <div class="wpd_coltitle_row">
               <div class="wpd_coltitle">去勢避妊 (日付 or 文字)</div>
               <div class="wpd_coltitle">ワクチン (日付 or 文字)</div>
               <div class="wpd_coltitle">健康状態</div>
           </div>
           <div class="wpd_coltitle_row">    
               <div class="wpd_col_data"><input name="neutering"         class="wpd_input_class wpd_tdp"        value="<?php echo $neutering ?>" /></div>
               <div class="wpd_col_data"><input name="vaccine"           class="wpd_input_class wpd_tdp"        value="<?php echo $vaccine ?>" /></div>
               <div class="wpd_col_data"><input name="health_condition"  class="wpd_input_class" value="<?php echo $health_condition ?>" /></div>
           </div>
       </div>
       
       <h4 ><span>詳細情報</span></h4> 
       <div>経緯</div>                                     <div><input name="why_is_here" value="<?php echo $why_is_here ?>"/></div>
       <div>性格/ストーリー</div>                          <div><textarea class="wpd_postedit_textarea" name="story"><?php echo $story ?></textarea></div>
       <div>長所</div>                                     <div><textarea class="wpd_postedit_textarea" name="good_point"><?php echo $good_point ?></textarea></div>
       <div>短所</div>                                     <div><textarea class="wpd_postedit_textarea" name="bad_point"><?php echo $bad_point ?></textarea></div>
       
       <h4 ><span>メディア</span></h4> 
       <div>FACEBOOK_URL</div>                             <div><input name="facebookurl" class="wpd_input_class" value="<?php echo $facebookurl ?>" /></div>
       <div>画像フォルダ(WEB共有ドライブ)</div>            <div><input name="photo_url"   class="wpd_input_class" value="<?php echo $photo_url ?>" /></div>
       <div>関連 URL</div>                         
            <?php 
            
            for ($i = 0; $i <= $related_url_count+1; $i+2) {
                
                echo '<div><input class="related_url_array" name="related_url_array'.$i.'"  value="'.$related_url_array[$i].'" />';
                echo '<input class="related_url_array" name="related_url_array'.$i++.'"  value="'.$related_url_array[$i++].'" /></div>';
                
                }
            ?><input type="hidden" name="related_url" value="<?php echo $related_url ?>">
            
       <h4 ><span>検討されている方へ</span></h4> 
       <div>譲渡条件等の補足事項</div>                     <div><textarea class="wpd_postedit_textarea" name="supplement"><?php echo $supplement ?></textarea></div>
       <div>追加条件</div>                                 <div><textarea class="wpd_postedit_textarea" name="additional_condition"><?php echo $additional_condition ?></textarea></div>
       <div>追加費用</div>                                 <div><textarea class="wpd_postedit_textarea" name="additional_cost"><?php echo $additional_cost ?></textarea></div>
       

       <h4 ><span>管理情報(非公開)</span></h4> 
       <div>ノート</div>                                 <div><?php wp_editor( $note, 'post-content', array( 'media_buttons'=>false, 'textarea_name'=>'note','textarea_rows'=>5 ) ); ?></div>
       <div>中西さん撮影画像(FB_URL)</div>                 <div><input name="phote_fb_url"  class="wpd_input_class" value="<?php echo $phote_fb_url ?>" /></div>
       <div>チラシ</div>                                   <div><input name="detail_paper"                         value="<?php echo $detail_paper ?>" /></div>
       
       <div class="wpd_coltitle_box">
           <div class="wpd_coltitle_row">
               <div class="wpd_coltitle"></div>
               <div class="wpd_coltitle">氏名</div>
               <div class="wpd_coltitle">連絡先</div>
           </div>
           <div class="wpd_coltitle_row">    
               <div class="wpd_col_data">保護主</div>
               <div class="wpd_col_data"><input name="rescuer"       class="wpd_input_class" value="<?php echo $rescuer ?>" />さん</div>
               <div class="wpd_col_data"><input name="rescuer_tel"                           value="<?php echo $rescuer_tel ?>" /></div>
           </div>
           <div class="wpd_coltitle_row">    
               <div class="wpd_col_data">預りさん</div>
               <div class="wpd_col_data"><input name="depository"    class="wpd_input_class" value="<?php echo $depository ?>" />さん</div>
               <div class="wpd_col_data"><input name="depository_tel"                        value="<?php echo $depository_tel ?>" /></div>
           </div>
           <div class="wpd_coltitle_row">    
               <div class="wpd_col_data">里親さん</div>
               <div class="wpd_col_data"><input name="foster"        class="wpd_input_class" value="<?php echo $foster ?>" />さん</div>
               <div class="wpd_col_data"><input name="foster_tel"                            value="<?php echo $foster_tel ?>" /></div>
           </div>
       </div>

    <?php
  }

	/**
	 * Aa_DMI_insert_update で使用されています。
	 */
	function wpd_update_photo($wpd_op_photo_coordinates,$wpd_op_photo,$post_id){
      $photo_coordinates_arry = explode(',',$wpd_op_photo_coordinates);
      $image_p = imagecreatetruecolor(600, 300);
      $image_thumb_p = imagecreatetruecolor(300, 150);
      $image = imagecreatefromjpeg($wpd_op_photo);
      list($original_width, $original_height) = getimagesize($wpd_op_photo);
      $preview_percent =  $original_width / 410;
      
      imagecopyresampled($image_p, $image, 
              0, 0, $photo_coordinates_arry[0] * $preview_percent, $photo_coordinates_arry[1] * $preview_percent, 
              600, 300, $photo_coordinates_arry[4] * $preview_percent, $photo_coordinates_arry[5] * $preview_percent);
      imagejpeg($image_p, WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'thumbnail/'.$post_id.'.jpg');
      imagedestroy($image_p);
      
      imagecopyresampled($image_thumb_p, $image, 
              0, 0, $photo_coordinates_arry[0] * $preview_percent, $photo_coordinates_arry[1] * $preview_percent, 
              300, 150, $photo_coordinates_arry[4] * $preview_percent, $photo_coordinates_arry[5] * $preview_percent);
      imagejpeg($image_thumb_p, WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'thumbnail/'.$post_id.'_thumb.jpg');
      imagedestroy($image_thumb_p);
  }


	function wpd_fetch_data($post_id) {
    if (!is_numeric($post_id)) return;
    global $wpdb;
    $wpd_data_set = $wpdb->get_results(
      $wpdb->prepare( "SELECT * FROM
        ".$this->table_name. " WHERE
        post_id = %d", $post_id
      )
    );
    return isset($wpd_data_set[0]) ? $wpd_data_set[0] : null;
    
  }

}
