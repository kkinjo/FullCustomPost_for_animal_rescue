<?php
/****************************************

		single.php

カスタム投稿「detail」を表示する single.php
 * 
 * created by kkinjo 2013/10/05

*****************************************/
include_once 'simple_html_dom.php';

//var_dump($wpd_instance);
$wpd_data_set = $wpd_instance->wpd_fetch_data($post->ID);

foreach($wpd_data_set as $colname => $value){
$$colname = isset($value) ? $value : null;
}

    /* 配列カウント */
    if(!empty($status_history)){
		$status_history_array =  json_decode($status_history,TRUE );
		$status_history_array = is_array($status_history_array) ? $status_history_array : array();
		$status_history_html = "";
		foreach ( $status_history_array as $sha_key => $sha_value ) {
			
			if( 
					!empty($sha_value["date"]) 
					&&
					!preg_match('/非公開/',$sha_value["status"])
			){
				$status_history_html .= $sha_value["date"]." - ";
				$status_history_html .= $sha_value["status"];
				$status_history_html .= "<BR>";
				
			}
			
		}
		
	}

    if(!empty($related_url)){
    $related_url = preg_replace('/,+\z/',"",$related_url);
    $related_url_array = explode(',', $related_url);
    $related_url_count = count($related_url_array) /2;
    
    $related_url = "";
    for ($i = 0; $i <= $related_url_count; $i++) {
        $related_url.= '<div><a href="'.$related_url_array[$i++].'" target="_blank" />'.$related_url_array[$i].'</a></div>';
        }
        
    }
        
    //年齢計算
    $wpd_age = str_replace("-", "",$birthyear);
    $wpd_age = (int) ((date('Ymd')-$wpd_age)/10000);
    
$wpd_instance->wpd_header(); 


?>

<!-- single.php -->
<div class="grid_9 push_3" id="main">
    <div class="box-top"></div>
    <article class="box-middle single-post post clearfix">
        <?php if (have_posts()) : /* ループ開始 */
               while (have_posts()) : the_post(); ?>

            <h3 name='pet_name'><?php echo $pet_name; /* 記事のタイトル */ ?></h3>
            <div class="metabox clearfix">
                <time class="post-date" datetime="<?php echo get_the_date("Y-m-j") ?>"><?php echo get_the_date(); /* 日付 */  ?></time>
                <div>
                    <span>カテゴリー：</span>
                    <?php the_category(''); /* カテゴリー */ ?>
		</div>
            </div>           
    <div >
        <?php if(!empty($photo)): /* アイキャッチ画像 */ ?>
        <img src="<?php echo $wpd_instance->wpd_plugin_url."thumbnail/".$post->ID.".jpg"; ?> " />
        <?php else: ?>
        <img src="<?php echo get_template_directory_uri(); ?>/images/no-image.jpg" alt="" class="grid_9"/>
        <?php endif; ?>
    </div>    
            
            <?php the_content(); /* コンテンツ */ ?>
<?php 
     
?>
            
<div>
    <div class="grid_3">
        <div class="level1_name">基本情報</div>
        <div class="level1_data">
            <div class="item"><div class="item_name">年齢</div><div class="item_data_s"><?php echo $birthyear_almost_flag." ".$wpd_age; ?> 歳</div></div>
            
            <div class="item"><div class="item_name">性別</div><div class="item_data_s"><?php echo $sex; ?></div></div>
            <div class="item"><div class="item_name">色</div><div class="item_data_s"><?php echo $color; ?></div></div>
            <div class="item"><div class="item_name">犬種/猫種</div><div class="item_data_s"><?php echo $breed; ?></div></div>
            
        </div>
        
    </div>
    
    <div class="grid_4 ">
        <div class="level1_name">健康状態</div>
        <div class="level1_data">
            <div class="item"><div class="item_name">避妊/去勢</div><div class="item_data_s"><?php echo $neutering; ?></div></div>
            <div class="item"><div class="item_name">ワクチン</div><div class="item_data_s"><?php echo $vaccine; ?></div></div>
            <div class="item"><div class="item_name">健康状態</div><div class="item_data_s"><?php echo $health_condition; ?></div></div>
            <div class="item"><div class="item_name">大きさ</div><div class="item_data_s"><?php echo $Breeds_size; ?></div></div>
            <div class="item"><div class="item_name">体重(おおよそ)</div><div class="item_data_s"><?php echo $weight; ?> kg</div></div>
        </div>
    </div>

    <div class="grid_7">
        <div class="level1_name">ワンズステータス</div>
        <div class="level1_data">
            <div class="item"><div class="item_name">ワンズ登録日</div><div class="item_data_s"><?php echo $wans_reg_date; ?></div></div>
            <div class="item"><div class="item_name">現在のステータス</div><div class="item_data_s"><?php echo $recent_status_change." - ".$now_status; ?></div></div>
				<?php
					if( $now_status == "他団体に移動" ){
				?>
			<div class="item">
				<div class="item_name">移動先団体</div>
				<div class="item_data_s">
				<?php 
					if( !empty( $Other_org_url ) ){
						echo "<a href='$Other_org_url' target='_blank'>$Other_org</a>";
					}else{
						echo $Other_org;
					}
				?>
				</div>
			</div>
				<?php
						
					}
				
					if(!empty($status_history)){
				?>
			<div class="item"><div class="item_name">ステータス履歴</div><div class="item_data_s"><?php echo $status_history_html; ?></div></div>
				<?php
					}
				?>
			
			
			
			        
        </div>
    </div>
    
    <div class="grid_7">
        <div class="level1_name">詳細情報</div>
        <div class="level1_data">
            <div class="item"><div class="item_name_l">経緯概要</div><div class="item_data_l"><?php echo $why_is_here; ?></div></div>
            <div class="item"><div class="item_name_l">性格/ストーリー</div><div class="item_data_l"><?php echo $story; ?></div></div>
        </div>
    </div>

    
    <div class="grid_7">
        <div class="level1_name">メディア</div>
     	<div class="level1_data">
            <div class="item"><div class="item_name">FACEBOOK</div><div class="item_data_s"><?php if(!empty($facebookurl)){echo '<a href='.$facebookurl.'" target="_blank">里親募集中！ワン\'sパートナーの会</a>';} ?></div></div>

            <?php 
                if (!empty($photo_url)) {
			?>
            <div class="item"><div class="item_name">ギャラリー</div></div>
            <div id="containerimg">
			<?php
                    $photo_url_dom = file_get_html($photo_url);
                    $img_src_array = array();
                    foreach($photo_url_dom->find('img') as $element) 
                    {
                        if(preg_match('/googleusercontent/',$element->src)){
							$img_src_array[] = $element->src;
                        }
                    }
                    
					//枚数カウント変数初期化。
					$fcpfar_gdphoto_view_counter = 0;
					foreach ( $img_src_array as $key => $value ) {
						// 8枚で表示は終了
						if( $fcpfar_gdphoto_view_counter == 8 ){
							echo '<BR><a href='.$photo_url.'  target="_blank">他の写真をもっと見る。</a>';
							break;
						}
						$o_img = str_replace("=s190","=s500" ,$value );
						echo '<a href="'.$o_img.'" rel="lightbox" ><img class="box" src='.$value.' style="height: 150px;" ></a>' ;
						//枚数カウントのためインクリメント
						$fcpfar_gdphoto_view_counter++;
					}
                    //for($a = 0; $a < 8; $a++){
                    //    echo '<img class="box" src='.$img_src_array[$a].' style="height: 150px;">' ;
                    //}    
			?>
			</div>
			<?php
               }
            ?>
            
			
			<?php 
			if(!empty($related_url)){
			?>
            <div class="item"><div class="item_name_l">その他のリンク</div>
                <div class="item_data_l"><?php echo $related_url; ?></div>
			</div>
			<?php				
			}
			?>
            

            
        </div>
    </div>
        
    <div class="grid_7">
    	<div class="level1_data">
            <div class="level1_name">検討されている方へ</div>
            <div class="level1_data">
                <div class="item"><div class="item_name_l">基本事項として『<a href="http://onesdog.net/family/" target="_blank">当会からイヌを家族として迎えるには</a>』をご確認ください。</div></div>
                <?php 
					if($supplement){
				?>
				<div class="item"><div class="item_name_l">譲渡についての補足事項</div><div class="item_data_l"><?php echo $supplement; ?></div></div>
				<?php
					}
					if($additional_condition){
				?>
				<div class="item"><div class="item_name_l">特別条件</div><div class="item_data_l"><?php echo $additional_condition; ?></div></div>
				<?php
					}
					if($additional_cost){
				?>
				<div class="item"><div class="item_name_l">特別費用</div><div class="item_data_l"><?php echo $additional_cost; ?></div></div>
				<?php
					}
				?>
				
            </div>
        </div>
    </div>
    
    <?php if ( is_user_logged_in() ) : ?>
    <div class="grid_7">
        <div class="level1_name">管理情報(非公開)</div>
        <div class="level1_data">
            <div class="item"><div class="item_name_l">管理メモ</div><div class="item_data_l"><?php echo $note; ?></div></div><hr>

            <div class="item"><div class="item_name">預かりさん</div><div class="item_data_s"><?php echo $depository; ?></div></div>
            
            <div class="item"><div class="item_name">保護依頼主</div><div class="item_data_s"><?php echo $rescuer; ?></div></div>
            
            <div class="item"><div class="item_name">譲渡先</div><div class="item_data_s"><?php echo $foster; ?></div></div>
            <hr>
            <div class="item"><div class="item_name">中西さん撮影(FB_URL)</div><div class="item_data_s"><?php echo $phote_fb_url; ?></div></div>
            <div class="item"><div class="item_name">チラシ</div><div class="item_data_s"><?php echo $detail_paper; ?></div></div>
            <BR>
            <div><?php edit_post_link('この記事を編集', '<p>', '</p>'); ?></div>
        </div>
    </div>
    <?php endif; ?>
	<div class="grid_7" style="margin-bottom: 25px;">
		<a href="http://onesdog.net/family/list/%E6%8E%B2%E8%BC%89%E3%83%AF%E3%83%B3%E3%82%B3%E3%81%AB%E9%96%A2%E3%81%99%E3%82%8B%E3%81%8A%E5%95%8F%E3%81%84%E5%90%88%E3%81%9B/?pet_name=<?php echo $pet_name; ?>&pet_detail_url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>" target="_blank" class="button button-rounded button-flat-primary a-clear" style="">この子についてのお問い合わせフォーム</a>
		
	</div>

</div>
<?php
	$np_data = $wpd_instance->wpd_get_np_data(
			 $wpd_instance->wpd_query_condtions['where']
			,$wpd_instance->wpd_query_condtions['order']
			,$post->ID
			);
	
 ?>     
            <nav class="post-navi" style="border-top: 4px double #dadada;">その他のワンコ<BR>
                <?php 
					echo $np_data; 
					/* 前後の記事 */ 
				?>
            </nav>
                <?php endwhile;
                else : ?>
            <h3>Not Found</h3>
            <p>Sorry, but you are looking for something that isn't here.</p>
                <?php 
					endif; /* ループ終了 */ 
					
					echo $wpd_instance->wpd_query_condtions['condistion_title']; 
 
					 $wpd_instance->wpd_footer();
					

				?>
			
    </article>
    
    
    <div class="box-bottom"></div>
</div>
<!-- main -->
<!-- /single.php -->
<?php 
				
	$wpd_instance->wpd_sidebar();
	get_footer(); 
?>