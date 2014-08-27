<?php
/****************************************

	category-detail.php

	CHAPTER 23

	カスタム投稿「detail」を表示する category.php
 * 
 * created by kkinjo 2013/10/01

*****************************************/
/*
 * query_vars にフィルターして get_query_var で 引数を取得する方法、いろいろ考えましたが、
 * wpd_get_archives で クエリ自体を書き換えており、get_query_var でクエリ変数を取るのは難しそう(エラーがでる)です。
 * シンプルに $_GET を使います。
 */

$view_mode = $_GET['view_mode'];

/*
 * 
 */

if ($view_mode == "pet_detail_report"){
	require_once 'pet_detail_report.php';
}


$wpd_instance->wpd_header();

/* ここらは プラグイン『wans_pet_detail』の 記事一覧に関する処理
 * ***********************************************************************************************************
 * ***********************************************************************************************************
 * 【0-1】まずは、今現在、アーカイブページとして何番目になるのかを取得。
 * */
$page_counts = get_query_var('paged');
if(empty($page_counts)){$page_counts=1;}

/*
 * 【0-2】次は、表示モードの設定を取得。
 * 関係者の場合は、各ワンコの詳細を出来る限りすべて表示して 表として表示する一覧ページを
 * URL引数 view_mode=detail_listで表示させます。
 * それ以外は、通常(デフォルト)モードとしてサムネイルを 1枚あたり 6件から12件の範囲で
 * 一覧表示させます。
 */


/*
 * 【0-3】データの取得
 * URL引数は クラス$wpd_instance の関数wpd_get_archives 内で取得しています。
 * DB より取得したデータは配列として $wpd_fetched_archives に格納。
 */
$wpd_fetched_archives = $wpd_instance->wpd_get_archives( 
		  $page_counts
		, $wpd_instance->wpd_query_condtions['where']
		, $wpd_instance->wpd_query_condtions['order']
		, $view_mode
		);
$wpd_fetched_archives_count = $wpd_instance->wpd_get_archives_count( 
		  $wpd_instance->wpd_query_condtions['where']
		, $wpd_instance->wpd_query_condtions['order']
		);

/*
 * 【1-1】
 * メインの データを表示する領域枠の HTML タグ。
 * 表示モードにかかわらず、検索操作パネルを表示させるため、表示モードの分岐よりも前に実行 * 
 */
?>
<!-- archive-information.php -->
<div class="grid_12 push_0" id="main">

	<div class="detail_thumbnail_box-top"></div>
	<div class="detail_thumbnail_box-middle">
		<?php 
		/*
		 * 【1-2】検索操作パネルを表示
		 */
			echo $wpd_instance->wpd_query_condtions['query_box'];  
			
		/*
		 * 【1-3】view_mode=detail_list の場合の TABLEタグの出力
		 */

		if($view_mode == "detail_list"){
		?>
		<div class="table_ara" style="overflow-x: scroll">
		<table class="detail_list_table">
				<thead>
					<tr class="detail_list_table_td_t_tr">
						<th class="detail_list_table_td_t_th">サムネイル</th>
						<th class="detail_list_table_td_t_th">名前</th>
						<th class="detail_list_table_td_t_th">ワンズ登録日</th>
						<th class="detail_list_table_td_t_th">ステータス</th>
						<th class="detail_list_table_td_t_th">年齢</th>
						<th class="detail_list_table_td_t_th">性別</th>
						<th class="detail_list_table_td_t_th">種類</th>
						<th class="detail_list_table_td_t_th">大きさ</th>
						<th class="detail_list_table_td_t_th">体重</th>
						<th class="detail_list_table_td_t_th">去勢</th>
						<th class="detail_list_table_td_t_th">ワクチン</th>
						<th class="detail_list_table_td_t_th">健康状態</th>
						<?php if ( is_user_logged_in() ) : ?>
						<th class="detail_list_table_td_t_th">預かりさん</th>
						<th class="detail_list_table_td_t_th">保護依頼主</th>
						<th class="detail_list_table_td_t_th">譲渡先</th>
						<?php endif; ?>
						<th class="detail_list_table_td_t_th">Facebook</th>
					</tr>
				</thead>
				<tbody>
<?php 
		}
	/* ***********************************************************************************************************
	 * 【2-1】取得した配列データより一つずつデータを取り出し処理するループ開始
	 */
	foreach($wpd_fetched_archives as $wpd_fetched_archives_row => $value){
		
		/*
		 * $view_mode == "detail_list" 用の処理
		 */
		$loop_rowcounts_for_listtable++;
		if (($loop_rowcounts_for_listtable % 10) == 0) { 
			if($view_mode == "detail_list"){ 
				?>
				</tbody></table></div>
			<div class="table_ara" style="overflow-x: scroll">
			<table class="detail_list_table">
				<thead>
					<tr class="detail_list_table_td_t_tr">
						<th class="detail_list_table_td_t_th">サムネイル</th>
						<th class="detail_list_table_td_t_th">名前</th>
						<th class="detail_list_table_td_t_th">ワンズ登録日</th>
						<th class="detail_list_table_td_t_th">ステータス</th>
						<th class="detail_list_table_td_t_th">年齢</th>
						<th class="detail_list_table_td_t_th">性別</th>
						<th class="detail_list_table_td_t_th">種類</th>
						<th class="detail_list_table_td_t_th">大きさ</th>
						<th class="detail_list_table_td_t_th">体重</th>
						<th class="detail_list_table_td_t_th">去勢</th>
						<th class="detail_list_table_td_t_th">ワクチン</th>
						<th class="detail_list_table_td_t_th">健康状態</th>
						<?php if ( is_user_logged_in() ) : ?>
						<th class="detail_list_table_td_t_th">預かりさん</th>
						<th class="detail_list_table_td_t_th">保護依頼主</th>
						<th class="detail_list_table_td_t_th">譲渡先</th>
						<?php endif; ?>
						<th class="detail_list_table_td_t_th">Facebook</th>
					</tr>
				</thead>
				<tbody>
				<?php 
			}
		
		}
		$wpd_fetched_archives_row_data = isset($wpd_fetched_archives[$wpd_fetched_archives_row]) ? $wpd_fetched_archives[$wpd_fetched_archives_row] : null;
		
		foreach($wpd_fetched_archives_row_data as $colname => $value){
			${$colname} = isset($value) ? $value : null;
		}
		
		//年齢計算
		$wpd_age = str_replace("-", "",$birthyear);
		$wpd_age = (int) ((date('Ymd')-$wpd_age)/10000);
		if( $wpd_age  < 100 ){		
			$wpd_age = $birthyear_almost_flag." ".$wpd_age ." 歳";
		}		
		else {
			$wpd_age  = "未登録";
		}
		
	/*
	 * 【2-2】view_mode で処理を分岐。サムネイルモードとリストモードで分ける。
	 */
		if($view_mode == "detail_list"){
			/*
			 *まずは、関係者のみで表示する詳細テーブルリスト
			 */
  ?> 
				<tr class="detail_list_table_tr">
					<td class="detail_list_table_td">
						<a class="detail_thumbnail_link" href="<?php echo get_permalink($post_id); ?>" target="_blank"><img src="<?php echo $wpd_instance->wpd_plugin_url."thumbnail/".$post_id."_thumb.jpg"; ?> " width="75px"/></a>
					</td>
					<td class="detail_list_table_td"><?php echo $pet_name; ?>
					<?php if ( is_user_logged_in() ) : ?><BR><a href="<?php echo get_edit_post_link($post_id); ?>" target="_blank" >編集</a><?php endif; ?>
					</td>
					<td class="detail_list_table_td"><?php echo $wans_reg_date; ?></td>
					<td class="detail_list_table_td"><?php echo $now_status; ?></td>
					<td class="detail_list_table_td"><?php echo $wpd_age; ?></td>
					<td class="detail_list_table_td"><?php echo $sex  ; ?></td>
					<td class="detail_list_table_td"><?php echo $breed; ?></td>
					<td class="detail_list_table_td"><?php echo $Breeds_size; ?></td>
					<td class="detail_list_table_td"><?php echo $weight; ?></td>
					<td class="detail_list_table_td"><?php echo $neutering; ?></td>
					<td class="detail_list_table_td"><?php echo $vaccine; ?></td>
					<td class="detail_list_table_td"><?php echo $health_condition; ?></td>
					<?php if ( is_user_logged_in() ) : ?>
					<td class="detail_list_table_td"><?php echo $depository; ?></td>
					<td class="detail_list_table_td"><?php echo $rescuer; ?></td>
					<td class="detail_list_table_td"><?php echo $foster; ?></td>
					<?php endif; ?>
					<td class="detail_list_table_td"><?php if ( !empty( $facebookurl ) ) echo "<a target=_blank src='".$facebookurl."' >facebook</a>"; ?></td>


				</tr>
<?php 
		}
		else{
	/*
	 * ***********************************************************************************************************
	 * 【2-3】else はデフォルトで、通常用のサムネイルリスト
	 */			
  ?> 
    <article class="detail_thumbnail">
		<a class="detail_thumbnail_link" href="<?php echo get_permalink($post_id); ?>" target="_blank">
			<div class="arch_thum_p_div">
				
				<h3>
					<?php 
						/*
						 * 記事タイトル
						 */
						echo $pet_name; 
						
						/*
						 * 関係者としてログインしている場合は 編集リンクを表示
						 */
						if ( is_user_logged_in() ) : 
					?>
					<a href="<?php echo get_edit_post_link($post_id); ?>" target="_blank" >編集</a>
					<?php 
						endif; 
					?>
				</h3>
				<div class="arch_thum_isset">
					<img class="arch_thum_img" src="<?php echo $wpd_instance->wpd_plugin_url."thumbnail/".$post_id."_thumb.jpg"; ?> " width="270px"/>
					<div class="arch_thum_nstatus"><?php echo $now_status?></div>
				</div>
				<span><?php $wpd_instance->wpd_age($birthyear_almost_flag,$birthyear); ?></span>
				<span><?php echo $sex?></span>
				<span><?php echo $color?></span>
				<span><?php echo $breed?></span>
                        <?php if(!empty($neutering)){echo "<span>避妊/去勢:".$neutering."</span>";} ?>
                        <?php if(!empty($vaccine)){echo "<span>ワクチン:".$vaccine."</span>";} ?>
                        <?php if(!empty($health_condition)){echo "<span>健康状態:".$health_condition."</span>";} ?>
                        <?php if(!empty($Breeds_size)){echo "<span>大きさ:".$Breeds_size."</span>";} ?>
                        <?php if(!empty($weight)){echo "<span>体重:".$weight."</span>";} ?>
			</div>
		</a>
	</article>            
<?php
		/*
		* ココで 通常サムネイル出力終了
		* ***********************************************************************************************************
		*/
		}
	/*
	* 【2-1】配列からのデータ取得ループ終了
	* ***********************************************************************************************************
	*/
	}
	
	/*
	 * 【1-3】
	 */
	if($view_mode == "detail_list"){
		?>
				</tbody></table></div>
		<?php 
		}
		
		if ( $wpd_fetched_archives_count == "0" ){
			echo "一致する情報がありません。条件を変えて検索して下さい。";
		}
	
	
	/*
	 * 【3】
	 * ページネーション
	 * 
	 * 本来であれば $wp_query->max_num_pages を wpd_pagination に引き渡すだけでいいのですが、
	 * MYSQL 5.6.11 と Wordpress 3.5.2 では  $wp_query->max_num_pages が正常に動作しなかった為
	 * 独自で情報を引っ張り、算出しています。
	 * 
	 * 参考:
	 * http://wpdocs.sourceforge.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/WP_Query
	 * $max_num_pages 
	 *   ページの合計数。$found_posts を $posts_per_page で割った結果。
	 * 
	 */
	$wpd_instance->wpd_pagination( ceil ( $wpd_fetched_archives_count / $wpd_instance->wpd_archive_page_post_count ) );
	$wpd_instance->wpd_footer();
?>
	</div>
	<div class="detail_thumbnail_box-bottom"></div>
	
<?php 
	
	/* 記事一覧に関する プラグイン『wans_pet_detail』の処理はここまで。
	 * ***********************************************************************************************************
	 * ***********************************************************************************************************
	 * ココからはテンプレートプラグインののページャ処理
	 */
	if (function_exists('wp_pagenavi')): /* ページャープラグイン wp_pagenavi用 */
	wp_pagenavi();
	else:            
		?>
			<nav class="navigation">
				<div class="alignleft"><?php previous_posts_link('&laquo; PREV'); ?></div>
				<div class="alignright"><?php next_posts_link('NEXT &raquo;'); ?></div>
			</nav>
		<?php 
endif; ?>

</div>
<!-- main -->
<!-- / archive-information.php -->
<?php 

get_footer(); 
?>