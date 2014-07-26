<?php
/*
Plugin Name: wan`s pet detail
Plugin URI: http://onesdog.punyu.jp/wans_pet_detail
Description: ワン’sパートナーの里親募集中ワンズの詳細ページ機能を詰め込んだプラグイン
Version: 0.1
Author: 金城 勝美
Author URI: http://catmeetsautumn.blogspot.jp/
*/
//require 'wpd_trait_DMI.php';
//require 'wpd_trait_admin_page.php';
//require 'wpd_trait_theme.php';
//require 'wpd_trait_archive.php';



class Wpd_class 
{
	//プラグインのテーブル名
	var $table_name;
	var $wpd_category_name;
	var $wpd_plugin_dirname;
	var $wpd_plugin_url;
	var $wpd_archive_page_post_count;
	var $wpd_query_condtions;
	var $wpd_init_query;
	var $sia;

	public function __construct() 
	{
		global $wpdb,$wp_query;
		// 接頭辞（wp_）を付けてテーブル名を設定
		$this->table_name = $wpdb->prefix . 'wpd';
		$this->catalog_name = $wpdb->prefix . 'wpd_catalog';
		$this->wpd_category_name = 'pet_detail';
		$this->wpd_plugin_dirname = str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) );
		$this->wpd_plugin_url = WP_PLUGIN_URL."/".$this->wpd_plugin_dirname;
		$this->wpd_archive_page_post_count = "12";

		// プラグイン有効化したとき実行
		register_activation_hook (__FILE__, array($this, 'wpd_activate'));

		// カスタム投稿タイプの登録
		add_action('init'							 , array($this, 'Aa_create_post_type' ));
		add_action('add_meta_boxes'				   , array($this, 'Aa_add_meta_box'));

		/**
		 * データ管理インターフェイス(DMI)の登録
		 * wpd_trait_DMI.php
		 */
		add_action('save_post'						, array($this, 'Aa_DMI_insert_update'));
		add_action('delete_post'					  , array($this, 'Aa_DMI_delete'));

		/**
		 * 管理ページ & 投稿ページの最適化()
		 * wpd_trait_admin_page.ph
		 */
		// ポスト一覧に、WPDデータを追加
		add_filter('manage_posts_columns'			 , array($this, 'Af_add_petname_col_name'));
		add_action('manage_posts_custom_column'	   , array($this, 'Aa_add_petname_col_data'), 10, 2 );

		// CSS 及び Javascript の登録
		add_action('admin_print_scripts'			  , array($this, 'Aa_add_jscript_to_admin'));
		add_action('admin_footer'					 , array($this, 'Aa_add_jscript_to_admin_footer'));
		add_action('admin_print_styles'			   , array($this, 'Aa_add_style_to_admin'));

		// POST編集ページの表示形式の変更
		add_action('admin_head'					   , array($this, 'Aa_change_css_on_post_edit'));
		add_filter('enter_title_here'				 , array($this, 'Af_change_title_here_on_post_edit'));
		
		// データのサジェスト機能とAJAXバリデーション用
		add_action('wp_ajax_wpd_ajax_validate'		 , array($this, '_wpd_ajax_validate'));
		add_action('wp_ajax_wpd_Autocomplete'		 , array($this, '_wpd_Autocomplete'));
		
		// データ入力時の画像投稿用
		add_filter('image_size_names_choose'		 , array($this, 'Af_limit_image_size'));
		add_filter('attachment_fields_to_edit'		 , array($this, 'Af_limit_image_edit_field'));

		/**
		 * カスタム投稿タイプの表示をテーマエンジンに追加()
		 * wpd_trait_admin_page.ph
		 */
		add_action('wp_head'						  , array($this, 'Aa_add_css_to_theme'));
		add_action('wp_head'						  , array($this, 'Aa_add_jscript_to_theme'));
		add_filter('template_redirect'				  , array($this, 'Af_switch_themes_file'));
		add_action('get_header '				      , array($this, 'Af_switch_themes_header'));
		add_action('get_sidebar '				      , array($this, 'Af_switch_themes_sidebar'));


		/**
		 * ページ送りに使用する表示数の設定()
		 * wpd_trait_admin_page.ph
		 */
		add_action('pre_get_posts'					  , array($this, 'Aa_chage_posts_per_page'));
		
		
		/* 検索配列設定 */
		$this->wpd_init_query = false;
		$wpd_REQUEST_URI = parse_url($_SERVER["REQUEST_URI"]);
		if( preg_match('/\//',$wpd_REQUEST_URI["path"]) ){
			$wpd_REQUEST_URI = explode('/', $wpd_REQUEST_URI["path"]);
		}
		if(is_array($wpd_REQUEST_URI) && in_array ( $this->wpd_category_name,$wpd_REQUEST_URI)){
			$this->wpd_init_query = true;
		}
		elseif( $this->wpd_category_name === $wpd_REQUEST_URI ){
			$this->wpd_init_query = true;
		}
		
		if( $this->wpd_init_query ){
			$this->sia["Breeds_size"]
					=array('condition_type'=>"order_by",'input_type'=>"radio"   ,'discription'=>"大きさ"
						,'checked'=>'','default'=> 'desc' ,'value_set' => array('asc'=>"小さい順",'desc'=>"大きい順")			  );
			$this->sia["wans_reg_date"]
					=array('condition_type'=>"order_by",'input_type'=>"radio"   ,'discription'=>"ワンズ歴"
						,'checked'=>'','default'=> 'asc'  ,'value_set' => array('asc'=>"浅い順",'desc'=>"長い順")			  );
			$this->sia["recent_status_change"]
					=array('condition_type'=>"order_by",'input_type'=>"radio"   ,'discription'=>"ステータス変更日"
						,'checked'=>'','default'=> 'desc' ,'value_set' => array('asc'=>"古い順",'desc'=>"新しい順")			);
			$this->sia["sex"]
					=array('condition_type'=>"where"   ,'input_type'=>"radio"	,'discription'=>"性別"
						,'checked'=>'','default'=> 'all'  ,'value_set' => array('all'=>"すべて",'オス'=>"オス",'メス'=>"メス") );
			$this->sia["color"]
					=array('condition_type'=>"where"   ,'input_type'=>"checkbox" ,'discription'=>"色"
						,'checked'=>'','default'=> array(),'value_set' => ""												   );
			$this->sia["breed"]
					=array('condition_type'=>"where"   ,'input_type'=>"checkbox" ,'discription'=>"犬種"
						,'checked'=>'','default'=> array(),'value_set' => ""												   );
			$this->sia["neutering" ]
					=array('condition_type'=>"where"   ,'input_type'=>"radio"	,'discription'=>"去勢/避妊"
						,'checked'=>'','default'=> 'all'  ,'value_set' => array('all'=>"すべて",'is_not_null'=>"済み",'is_null'=>"未")	 );
			$this->sia["vaccine" ]
					=array('condition_type'=>"where"   ,'input_type'=>"radio"	,'discription'=>"ワクチン"
						,'checked'=>'','default'=> 'all'  ,'value_set' => array('all'=>"すべて",'is_not_null'=>"済み",'is_null'=>"未")	 );
			$this->sia["now_status" ]
					=array('condition_type'=>"where"   ,'input_type'=>"checkbox" ,'discription'=>"ステータス"
						,'checked'=>'','default'=> array(),'value_set' => ""												   );
			
			$this->wpd_query_condtions = $this->wpd_query_condtion_setting($this->sia);
			
			add_filter('posts_join'		, array($this, 'Af_query_conditon_join'));
			add_filter('posts_where'	   , array($this, 'Af_query_conditon_where'));
			add_filter('posts_orderby'	 , array($this, 'Af_query_conditon_orderby'));
		}
		
		/*
		 * 汎用列情報
		 * いずれは DB に表を作り Fetch して代入したい。
		 */
		$this->wpd_col_info_array["meta_id"]				= array('colname'=>"meta_id"				, 'j_description'=>"データID"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["post_id"]				= array('colname'=>"post_id"				, 'j_description'=>"WordpressのブログポストID"	  , 'confidential_flag'=>false);
		$this->wpd_col_info_array["pet_name"]			   = array('colname'=>"pet_name"			   , 'j_description'=>"名前"						   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["birthyear_almost_flag"]  = array('colname'=>"birthyear_almost_flag"  , 'j_description'=>"だいたい"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["birthyear"]			  = array('colname'=>"birthyear"			  , 'j_description'=>"誕生年"						 , 'confidential_flag'=>false);
		$this->wpd_col_info_array["Deathyear"]			  = array('colname'=>"Deathyear"			  , 'j_description'=>"没年"						   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["photo"]				  = array('colname'=>"photo"				  , 'j_description'=>"写真番号"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["photo_coordinates"]	  = array('colname'=>"photo_coordinates"	  , 'j_description'=>"サムネイル画像メタデータ"	   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["sex"]					= array('colname'=>"sex"					, 'j_description'=>"性別"						   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["color"]				  = array('colname'=>"color"				  , 'j_description'=>"色"							 , 'confidential_flag'=>false);
		$this->wpd_col_info_array["breed"]				  = array('colname'=>"breed"				  , 'j_description'=>"犬種/種類"					  , 'confidential_flag'=>false);
		$this->wpd_col_info_array["Breeds_size"]			= array('colname'=>"Breeds_size"			 , 'j_description'=>"大きさ"						   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["wans_reg_date"]		  = array('colname'=>"wans_reg_date"		  , 'j_description'=>"ワンズ登録日"				   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["now_status"]			 = array('colname'=>"now_status"			 , 'j_description'=>"ステータス"					 , 'confidential_flag'=>false);
		$this->wpd_col_info_array["recent_status_change"]   = array('colname'=>"recent_status_change"   , 'j_description'=>"最近のステータス変更日"		 , 'confidential_flag'=>false);
		$this->wpd_col_info_array["status_history"]		 = array('colname'=>"status_history"		 , 'j_description'=>"ステータス履歴"				 , 'confidential_flag'=>false);
		$this->wpd_col_info_array["neutering"]			  = array('colname'=>"neutering"			  , 'j_description'=>"避妊/去勢"					  , 'confidential_flag'=>false);
		$this->wpd_col_info_array["vaccine"]				= array('colname'=>"vaccine"				, 'j_description'=>"ワクチン"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["health_condition"]	   = array('colname'=>"health_condition"	   , 'j_description'=>"健康状態"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["why_is_here"]			= array('colname'=>"why_is_here"			, 'j_description'=>"経緯概要"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["story"]				  = array('colname'=>"story"				  , 'j_description'=>"性格/ストーリー"				, 'confidential_flag'=>false);
		$this->wpd_col_info_array["supplement"]			 = array('colname'=>"supplement"			 , 'j_description'=>"譲渡条件等の補足事項"		   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["additional_condition"]   = array('colname'=>"additional_condition"   , 'j_description'=>"追加条件"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["additional_cost"]		= array('colname'=>"additional_cost"		, 'j_description'=>"追加費用"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["note"]				   = array('colname'=>"note"				   , 'j_description'=>"管理用ノート(非公開)"		   , 'confidential_flag'=>true);
		$this->wpd_col_info_array["facebookurl"]			= array('colname'=>"facebookurl"			, 'j_description'=>"Facebookページでの記事"		 , 'confidential_flag'=>false);
		$this->wpd_col_info_array["photo_url"]			  = array('colname'=>"photo_url"			  , 'j_description'=>"画像フォルダ(WEB共有ドライブ)"  , 'confidential_flag'=>false);
		$this->wpd_col_info_array["phote_fb_url"]		   = array('colname'=>"phote_fb_url"		   , 'j_description'=>"Facebook上での画像URL(非公開)"  , 'confidential_flag'=>true);
		$this->wpd_col_info_array["detail_paper"]		   = array('colname'=>"detail_paper"		   , 'j_description'=>"チラシ"						 , 'confidential_flag'=>false);
		$this->wpd_col_info_array["related_url"]			= array('colname'=>"related_url"			, 'j_description'=>"関連 URL"					   , 'confidential_flag'=>false);
		$this->wpd_col_info_array["depository"]			 = array('colname'=>"depository"			 , 'j_description'=>"預りさん(非公開)"			   , 'confidential_flag'=>true);
		$this->wpd_col_info_array["rescuer"]				= array('colname'=>"rescuer"				, 'j_description'=>"保護主(非公開)"				 , 'confidential_flag'=>true);
		$this->wpd_col_info_array["foster"]				 = array('colname'=>"foster"				 , 'j_description'=>"里親さん(非公開)"			   , 'confidential_flag'=>true);
		
	}

/* プラグイン『wans_pet_detail』を有効化した際に行う初期化処理 */
	function wpd_activate() 
	{
		global $wpdb;

		$cmt_db_version = '2014/06/02';
		$installed_ver = get_option( 'cmt_meta_version' );
		// テーブルのバージョンが違ったら作成
		//if( $installed_ver != $cmt_db_version ) {
			$wpd_table_create_sql = "CREATE TABLE " . $this->table_name . "
			(
			meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			post_id bigint(20) UNSIGNED DEFAULT '0' NOT NULL,
			pet_name				VARCHAR(30) NOT NULL ,
			birthyear_almost_flag   VARCHAR(10),
			birthyear			   DATE,
			Deathyear			   DATE,
			photo				   text,
			photo_coordinates	   text,
			sex					 VARCHAR(10),
			color				   VARCHAR(30),
			breed				   VARCHAR(30),
			Breeds_size            VARCHAR(30),
			wans_reg_date		   DATE,
			now_status			  VARCHAR(30),
			recent_status_change	DATE,
			status_history		  text,
			neutering			   VARCHAR(10),
			vaccine				 VARCHAR(10),
			health_condition		VARCHAR(30),
			why_is_here			 VARCHAR(30),
			story				   text,
			supplement			  text,
			additional_condition	text,
			additional_cost		 text,
			note					text,
			facebookurl			 text,
			photo_url			   text,
			phote_fb_url			text,
			detail_paper			VARCHAR(30),
			related_url			 text,
			depository			  VARCHAR(30),
			rescuer				 VARCHAR(30),
			foster				  VARCHAR(30),
			UNIQUE KEY meta_id (meta_id)
			)
			CHARACTER SET 'utf8';";
			
			$wpd_catalog_create_sql = "CREATE TABLE " . $this->catalog_name . "
			(
			col_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
			,col_name                       varchar(200)
			,data_type                      varchar(64)
			,wpd_extend_type                varchar(64)
			,input_type                     varchar(64)
			,edit_methoed                   varchar(64)
			,input_support                  varchar(1024)
			,validation                     varchar(1024)
			,individual_page_nonpublic      varchar(3)
			,list_page_show                 varchar(3)
			,table_page_show                varchar(3)
			,admin_list_show                varchar(3)
			,item_name                      text
			,item_info                      text
			,UNIQUE KEY ".$this->catalog_name."_col_id (col_id)
			)
			CHARACTER SET 'utf8';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $wpd_table_create_sql );
			dbDelta( $wpd_catalog_create_sql );
			update_option( 'cmt_meta_version' , $cmt_db_version );
		//}
	}

	/* カスタム投稿タイプの追加 */
	function Aa_create_post_type() 
	{
		register_post_type( 'pet_detail'
				,array(
					'labels' => array(
						'name' => __( '保護ワンコデータ' )
						,'singular_name' => __( '保護ワンコデータ' )
						)
					,'public' => true
					,'supports' => array( 'title', 'exmeta_sectionid', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ,'comments' )
					,'menu_position' =>5
					,'has_archive'=> true
					,'taxonomies'=>array( 'pet_detail' )
					,'rewrite' => true
					)
				);

		//カスタムタクソノミー、カテゴリタイプ
		//register_taxonomy( 'pet_detail-cat'
		//		,'pet_detail'
		//		,array( 'hierarchical' => true
		//			,'update_count_callback' => '_update_post_term_count'
		//			,'label' => '保護ワンコデータのカテゴリー'
		//			,'singular_label' => '保護ワンコデータのカテゴリー'
		//			,'public' => true
		//			,'show_ui' => true
		//			)
		//		);
	}

	/* 『表示する項目』に カスタム投稿タイプのエントリフォームを追加 */
	function Aa_add_meta_box( $post ) {
		add_meta_box(
				'exmeta_sectionid',
				'里親募集中ワンズデータ',
				array( $this , 'wpd_html' ),
				'pet_detail'
		);
	}

/**
 *  *****************************************************************************************************************************
 *  trait: data management interface
 * source:wpd_trait_DMI.php
 */

	function Aa_DMI_insert_update($post_id) 
	{
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
    //DEBUG用
	//exit( var_dump( $_POST[''] ) );
  }

	function Aa_DMI_delete($post_id) {
	global $wpdb;
	$wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE post_id = %d", $post_id) );
  }

	// Aa_add_meta_box で使用されています。
	function wpd_html () {
		wp_nonce_field( plugin_basename( __FILE__ ), $this->table_name );
		global $post;
		global $wpdb;

		//カタログから列情報を取得
		$wpd_col_info_set = $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM ".$this->catalog_name. " order by col_id", $post->ID)
				, ARRAY_A );
		
		//WPD表から列を取得
		$wpd_data_set = $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM ".$this->table_name. " WHERE post_id = %d", $post->ID)
				, ARRAY_A );
		
		$admin_col_html = array();
		//列名の変数に 行レコード を 列名_set変数に、行レコードとカタログ情報を追加
		if(!empty($wpd_data_set[0])){
			foreach($wpd_data_set[0] as $colname => $wds_value){
				//これまでのコード
				$$colname = isset($wds_value) ? $wds_value : null;
				
				//列名_SET 変数。まずはVALUE を入れる。
				$admin_col_html[$colname]["value"] = isset($wds_value) ? $wds_value : null;
				
				//カタログ情報を 列名_SET 変数[カタログ列] で配列として代入
				foreach ( $wpd_col_info_set as $col_info_key => $col_info_value ) {
					if( $colname === $col_info_value["col_name"]  ){						
						foreach ( $col_info_value as $civ_key => $civ_value ) {
							$admin_col_html[$colname][$civ_key] = $civ_value;
							if( preg_match('/ajax_autocomplete/',$civ_value) ) $admin_col_html[$colname]['ajax_autocomplete'] = "ajax_autocomplete";
							if( preg_match('/autocomplete_multiple/',$civ_value) ) $admin_col_html[$colname]['autocomplete_multiple'] = "autocomplete_multiple";
							if( preg_match('/datepicker/',$civ_value) ) $admin_col_html[$colname]['datepicker'] = "datepicker";
							
						}
					}
				}
			}
			
			/* 配列カウント */
			if( !is_null ( $status_history ) ){
				$status_history = preg_replace('/,+\z/',"",$status_history);
				$status_history_array = explode(',', $status_history);
				$status_history_count = count($status_history_array) ;
				}
				
			if( !is_null ( $related_url ) ){
				$related_url = preg_replace('/,+\z/',"",$related_url);
				$related_url_array = explode(',', $related_url);
				$related_url_count = count($related_url_array) ;
				}
				
			/* 画像ファイル */
			if( is_null ( $photo ) ){
				$photo = WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'thumbnail/noimage_o.jpg';
				$photo_coordinates = "0,55,410,260,410,205";
			}
		
			
			foreach ( $admin_col_html as $achkey => $achvalue ) {
				//項目ブロック開始
				$admin_col_html[$achkey]["html"]  = "<div class='wpd_admin_block_box'>";
				//入力項目タイトル(item_name)
				$admin_col_html[$achkey]["html"] .= "	<div class='wpd_coltitle'>".$achvalue["item_name"]."</div>";
				//説明
				$admin_col_html[$achkey]["html"] .= "	<div class='wpd_colhelp'><span>".$achvalue["item_info"]."	</span></div>";
				//入力要素
				$admin_col_html[$achkey]["html"] .= "	<div class='wpd_colinput'>";
				
				switch ( $achvalue["edit_methoed"] ) {
					case "media-upload":
						
						$admin_col_html[$achkey]["html"] .=""
							. "<div id='previe_target'></div><div id='preview-pane'>カバー画像プレビュー<div class='preview-container'>"
							. "		<img id='wpd_cover_photo_preview' src='".$achvalue["value"]."' style='width: 600px'/>"
							. "	</div></div><div><BR>"
							. "オリジナル画像<BR><img id='wpd_cover_photo_orginal' src='".$achvalue["value"]."' style='width: 410px;'/></div>"
							. "<div><a class='media-upload' href='JavaScript:void(0);' rel='wpd_cover_photo'>Select File</a>"
							. "<input type='hidden' name=".$achvalue["item_name"]."  value=".$achvalue["value"]." />";
						break;

					case "Jcrop":
						
						$admin_col_html[$achkey]["html"] .="<input type='hidden' name='photo_coordinates'  value='".$achvalue["value"]."' />";

						break;

					case "textarea":
						
						$admin_col_html[$achkey]["html"] .= "<textarea class='wpd_postedit_textarea' name='".$achvalue["col_name"]."'  type='".$achvalue["input_type"]."'  >";
						$admin_col_html[$achkey]["html"] .= $achvalue["value"];
						$admin_col_html[$achkey]["html"] .= "</textarea>";

						break;
					
					case "wp_editor":						
						//wp_editor( $achvalue["value"], 'post-content mceForceColors', array( 'media_buttons'=>false, 'textarea_name'=>$achvalue["col_name"],'textarea_rows'=>5 ) ); 
						break;
					
					case "wpd_auto":
						
						$admin_col_html[$achkey]["html"] .= "   <input class='wpd_input_class' name='".$achvalue["col_name"]."' "
							. " type='".$achvalue["input_type"]."'  value='".$achvalue["value"]."' " .$achvalue["ajax_autocomplete"]."  >";
						break;
						
					case "checkbox":
						
						$admin_col_html[$achkey]["html"] .= "		<input  name='".$achvalue["col_name"]."'  type='".$achvalue["input_type"]."'  value='".$achvalue["value"]."' ";
						if( $achvalue["value"] == "だいたい" ) $admin_col_html[$achkey]["html"] .= " checked ";
						$admin_col_html[$achkey]["html"] .= "		>";
						break;

					case "select":
						
						$admin_col_html[$achkey]["html"] .= "<select  class='wpd_input_class' name='".$achvalue["col_name"]."'>";
						$temp_json_array = json_decode($achvalue["value_set_json"], true);
						foreach ( $temp_json_array as $key => $value ) {
							$admin_col_html[$achkey]["html"] .= "<option value=".$key." ";
							if( $key == $achvalue["value"]) $admin_col_html[$achkey]["html"] .="selected";
							$admin_col_html[$achkey]["html"] .=">".$value."</option>";
						}
						$admin_col_html[$achkey]["html"] .= "</select>";
						break;
					
					default:
						
						$admin_col_html[$achkey]["html"] .= "		<input class='wpd_input_class' name='".$achvalue["col_name"]."'  type='".$achvalue["input_type"]."' "
							. " value='".$achvalue["value"]."' ".$achvalue["ajax_autocomplete"]." ".$achvalue["autocomplete_multiple"]." ".$achvalue["datepicker"]."  >";
						
						if( !empty( $achvalue["ajax_autocomplete"] ) ){
							$admin_col_html[$achkey]["html"] .= "<a class='kick_ajax_autocomplete' value='".$achvalue["col_name"]."' search='off' >参考値</a>";
						}
						break;
				}
				
				$admin_col_html[$achkey]["html"] .= "	</div>";
				$admin_col_html[$achkey]["html"] .= "</div>";
				
			}
				
		}else{
			$wpd_new_wansid_res = $wpdb->get_results( $wpdb->prepare( "SELECT MAX( meta_id ) +1 as value FROM ".$this->table_name,ARRAY_N ));
			$meta_id = $wpd_new_wansid_res[0]->value;
		}

	?>
	<div>
		<input type="hidden" name="meta_id" value="<?php echo $meta_id ?>">
		<span>ワンID:</span><span>WANS:<?php echo $meta_id ?></span>
		<div><a href="<?php echo get_permalink($post->ID); ?>">公開ページはこちら</a></div>
		<div>ペットの名前</div>
		<input id="pet_name" class="input_mandatory ajax_validate" type="text" name="pet_name"  value="<?php echo $pet_name ?>" />
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
			   <div class="wpd_col_data" align="center">
				   <?php 
						echo '<input type="checkbox" name="birthyear_almost_flag" value="だいたい"';
						if( !empty($birthyear_almost_flag) ){
							echo 'checked';
						}
						echo '/>';
				   ?>
				   </div>
			   <div class="wpd_col_data"><input class="wpd_input_class wpd_tdp" name="birthyear"	 value="<?php echo $birthyear ?>" /></div>
			   <div class="wpd_col_data"><input class="wpd_input_class wpd_tdp" name="Deathyear"	 value="<?php echo $Deathyear ?>" /></div>
		   </div>
	   </div>
	   <div class="wpd_coltitle_box">
		   <div class="wpd_coltitle_row">
			   <div class="wpd_coltitle">性別</div>
			   <div class="wpd_coltitle" >色(スラッシュ(/)区切りで自動整理)</div>
			   <div class="wpd_coltitle">犬種/猫種(スラッシュ(/)区切りで自動整理))</div>
		   </div>
		   <div class="wpd_coltitle_row">
			   <div class="wpd_col_data"><input class="wpd_input_class" name="sex"				   value="<?php echo $sex ?>" ajax_autocomplete /><a class="kick_ajax_autocomplete" value="sex" search="off" >参考値</a></div>
			   <div class="wpd_col_data"><input class="wpd_input_class" name="color"				 value="<?php echo $color ?>" ajax_autocomplete autocomplete_multiple /><a class="kick_ajax_autocomplete" value="color" search="off" >参考値</a></div>
			   <div class="wpd_col_data"><input class="wpd_input_class" name="breed"				 value="<?php echo $breed ?>" ajax_autocomplete autocomplete_multiple /><a class="kick_ajax_autocomplete" value="breed" search="off" >参考値</a></div>
		   </div>
	   </div>
	   <div class="wpd_coltitle_box">
		   <div class="wpd_coltitle_row">
			   <div class="wpd_coltitle">大きさ<?php  ?></div>
		   </div>
		   <div class="wpd_coltitle_row">
			   <div class="wpd_col_data">
				   <select  class="wpd_input_class" name="Breeds_size">
					   <?php 
							$Breeds_size_valuset = array(
								""=>"未測定",
								"0.不明"=>"0.不明",
								"1.小型"=>"1.小型",
								"2.中型"=>"2.中型",
								"3.大型"=>"3.大型"
								);
							
								foreach ( $Breeds_size_valuset as $key => $value ) {
									echo '<option value="'.$key.'"';
									if($key == $Breeds_size){
										echo "selected";
									}
									echo '>'.$value.'</option>';
								} 
					
						?>
				   </select>
			   </div>
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
			   <div class="wpd_col_data"><input name="now_status"	class="wpd_input_class input_mandatory"		 value="<?php echo $now_status ?>" ajax_autocomplete /><a class="kick_ajax_autocomplete" value="now_status" search="off" >参考値</a></div>
			   <div class="wpd_col_data"><input name="recent_status_change"  class="wpd_input_class wpd_tdp"  value="<?php echo $recent_status_change ?>" /></div>
		   </div>
	   </div>
	   <div>ステータス履歴</div>
			<?php

			for ($i = 0; $i <= $status_history_count+1; $i+2) {

				echo '<div><input class="status_history_array"	name="status_history_array'.$i.'"	value="'.$status_history_array[$i].'" />';
				echo '<input class="status_history_array wpd_tdp" name="status_history_array'.$i++.'"  value="'.$status_history_array[$i++].'" /></div>';

				}
				?>
	   <!--<input type="text" name="status_history" value="<?php echo $status_history ?>" size="2000">-->

	   <div class="wpd_coltitle_box">
		   <div class="wpd_coltitle_row">
			   <div class="wpd_coltitle">去勢避妊 (日付 or 文字。未実施は空)</div>
			   <div class="wpd_coltitle">ワクチン (日付 or 文字。未実施は空)</div>
			   <div class="wpd_coltitle">健康状態(スラッシュ(/)区切りで自動整理)</div>
		   </div>
		   <div class="wpd_coltitle_row">
			   <div class="wpd_col_data"><input name="neutering"		 class="wpd_input_class wpd_tdp"		value="<?php echo $neutering ?>" /></div>
			   <div class="wpd_col_data"><input name="vaccine"		   class="wpd_input_class wpd_tdp"		value="<?php echo $vaccine ?>" /></div>
			   <div class="wpd_col_data"><input name="health_condition"  class="wpd_input_class" value="<?php echo $health_condition ?>" ajax_autocomplete autocomplete_multiple /><a class="kick_ajax_autocomplete" value="health_condition" search="off" >参考値</a></div>
		   </div>
	   </div>

	   <h4 ><span>詳細情報</span></h4>
	   <div>経緯</div>									 <div><input name="why_is_here" class="wpd_input_class input_mandatory" value="<?php echo $why_is_here ?>" ajax_autocomplete autocomplete_multiple /><a class="kick_ajax_autocomplete" value="why_is_here" search="off" >参考値</a></div>
	   <div>性格/ストーリー</div>						  <div><textarea class="wpd_postedit_textarea" name="story" ><?php echo $story ?></textarea></div>

	   <h4 ><span>メディア</span></h4>
	   <div>FACEBOOK_URL</div>							 <div><input name="facebookurl" class="wpd_input_class" value="<?php echo $facebookurl ?>" /></div>
	   <div>画像フォルダ(WEB共有ドライブ)</div>			<div><input name="photo_url"   class="wpd_input_class" value="<?php echo $photo_url ?>" /></div>
	   <div>関連 URL</div>
			<?php

			for ($i = 0; $i <= $related_url_count+1; $i+2) {

				echo '<div><input class="related_url_array" name="related_url_array'.$i.'"  value="'.$related_url_array[$i].'" />';
				echo '<input class="related_url_array" name="related_url_array'.$i++.'"  value="'.$related_url_array[$i++].'" /></div>';

				}
			?><input type="hidden" name="related_url" value="<?php echo $related_url ?>">

	   <h4 ><span>検討されている方へ</span></h4>
	   <div>譲渡条件等の補足事項</div>					 <div><textarea class="wpd_postedit_textarea" name="supplement"><?php echo $supplement ?></textarea></div>
	   <div>追加条件</div>								 <div><textarea class="wpd_postedit_textarea" name="additional_condition"><?php echo $additional_condition ?></textarea></div>
	   <div>追加費用</div>								 <div><textarea class="wpd_postedit_textarea" name="additional_cost"><?php echo $additional_cost ?></textarea></div>


	   <h4 ><span>管理情報(非公開)</span></h4>
	   <div>ノート</div>								 <div><?php wp_editor( $note, 'post-content mceForceColors', array( 'media_buttons'=>false, 'textarea_name'=>'note','textarea_rows'=>5 ) ); ?></div>
	   <div>中西さん撮影画像(FB_URL)</div>				 <div><input name="phote_fb_url"  class="wpd_input_class" value="<?php echo $phote_fb_url ?>" /></div>
	   <div>チラシ</div>								   <div><input name="detail_paper"						 value="<?php echo $detail_paper ?>" /></div>

	   <div class="wpd_coltitle_box">
		   <div class="wpd_coltitle_row">
			   <div class="wpd_coltitle"></div>
			   <div class="wpd_coltitle">氏名(できるだけフルネームで)</div>
		   </div>
		   <div class="wpd_coltitle_row">
			   <div class="wpd_col_data">保護主</div>
			   <div class="wpd_col_data"><input name="rescuer"	   class="wpd_input_class" value="<?php echo $rescuer ?>" ajax_autocomplete/><a class="kick_ajax_autocomplete" value="rescuer" search="off" >参考値</a><span>さん</span></div>
		   </div>
		   <div class="wpd_coltitle_row">
			   <div class="wpd_col_data">預りさん</div>
			   <div class="wpd_col_data"><input name="depository"	class="wpd_input_class" value="<?php echo $depository ?>" ajax_autocomplete/><a class="kick_ajax_autocomplete" value="depository" search="off" >参考値</a>さん</div>
		   </div>
		   <div class="wpd_coltitle_row">
			   <div class="wpd_col_data">里親さん</div>
			   <div class="wpd_col_data"><input name="foster"		class="wpd_input_class" value="<?php echo $foster ?>" />さん</div>
		   </div>
	   </div>
	<?php
  }
  
  

	//Aa_DMI_insert_update で使用されています。
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
	/*
	 * 主に single-pet_detail.php で使用
	 */
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

	function wpd_fetch_colname() {
	/*
	 * とりあえず作ってみた。
	 */
	global $wpdb;
	$wpd_colneme_set = array();
	$wpd_data_set = $wpdb->get_results("SELECT * FROM
		".$this->table_name. " WHERE
		meta_id = 0",ARRAY_A
	);
	foreach ( $wpd_data_set[0] as $key => $value ) {
		array_push($wpd_colneme_set, $key);
	}
	return isset($wpd_colneme_set) ? $wpd_colneme_set : null;

  }

/**
 * *****************************************************************************************************************************
 *  trait: optimize admin_page and post_page
 * source: wpd_trait_admin_page.php
 */
//use wpd_trait_admin_page;
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
	  wp_enqueue_script('jquery-ui-autocomplete');
	  wp_enqueue_script('jquery-ui-datepicker');
	  wp_enqueue_script('jquery-ui-i18n', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/jquery-ui-i18n.js',array('jquery'));
	  wp_enqueue_script('jcrop');
	  wp_enqueue_script('functionsgg', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/wans_pet_detail_admin.js',array('jquery'));

  }

	function Aa_add_jscript_to_admin_footer() {
		echo '<script type="text/javascript">';
		echo 'wpd_form_validateion();';
		echo '</script>';
  }

	function Aa_change_css_on_post_edit() {
		$pt = get_post_type();
		if ($pt == 'pet_detail') {
			$hide_postdiv_css = '<style type="text/css">#postdiv, #postdivrich,#titlediv, #titlewrap { display: none; }</style>';
			echo $hide_postdiv_css;

			$url = get_option('siteurl');
			echo '<link rel="stylesheet" type="text/css" href="' . $url . '/wp-content/plugins/'.$this->wpd_plugin_dirname.'css/wans_admin.css' . '" />';
			echo '<link rel="stylesheet" type="text/css" href="' . $url . '/wp-content/plugins/'.$this->wpd_plugin_dirname.'css/exvalidation.css' . '" />';
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
	  
	function _wpd_ajax_validate() {
		//配列の初期値s
		
		//prepare が使えないので、SQLインジェクション対策を自前で行うための配列
		$wpd_columns_set = array('pet_name');
		
		$json_result = array();
		//ユーザーが管理者で、URLパラメータtarget_column の値が 事前定義配列と一致している場合
		if( current_user_can('manage_options') && in_array( $_GET['target_column'] , $wpd_columns_set )){
			//データベース操作クラスを読込み
			global $wpdb; //データベース接続オブジェクト
			//列名を取得して
			$target_column = $_GET['target_column'];
			$search_value = $_GET['search_value'];
			$now_post_id = $_GET['now_post_id'];
			//SQL文を生成し、
			$ajax_sql = $wpdb->prepare( "SELECT count(".$target_column.") FROM {$this->table_name} where ".$target_column."='%s' and post_id !=". $now_post_id .";" , $search_value );
			//結果を取得
			$result = $wpdb->get_results($ajax_sql, ARRAY_N);
			
			foreach ( $result as $key => $value ) {
				$json_result[$key] .= $result[$key][0] ;
			}
		//PHPの配列をJSONに変換して出力
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($json_result);
			
		}
		
		//die は必須!!
		die();
	}
	  
	function _wpd_Autocomplete() {
		//配列の初期値s
		
		//prepare が使えないので、SQLインジェクション対策を自前で行うための配列
		$wpd_columns_set = array('sex','color','breed','now_status','health_condition','why_is_here','rescuer','depository');
		
		$json_result = array();
		//ユーザーが管理者で、URLパラメータtarget_column の値が 事前定義配列と一致している場合
		if( current_user_can('manage_options') && in_array( $_GET['target_column'] , $wpd_columns_set )){
			//データベース操作クラスを読込み
			global $wpdb; //データベース接続オブジェクト
			//列名を取得して
			$target_column = $_GET['target_column'];
			//SQL文を生成し、
			$ajax_sql = "SELECT DISTINCT ".$target_column." FROM {$this->table_name} where LENGTH(".$target_column.") > 0 order by 1;";
			//結果を取得
			$result = $wpdb->get_results($ajax_sql, ARRAY_N);
			
			foreach ( $result as $key => $value ) {
				$json_result[$key] .= $result[$key][0] ;
			}
		}
		
		//PHPの配列をJSONに変換して出力
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($json_result);
		
		//die は必須!!
		die();
	}

	function Af_limit_image_size($size_names){
		
		// フルサイズのみ
		$size_names = array('full' => __('Full Size'));
 
    return $size_names;
	}

	function Af_limit_image_edit_field($form_fields){
		
		$wpdmode=$_GET["wpd"];
		// フルサイズのみ
		if($wpdmode=="yes"){
			foreach ( $form_fields as &$temp_form_fields ) {
				if (array_key_exists('input', $temp_form_fields)) {
					$temp_form_fields["input"]="hiden";
				}
			}
			echo "<pre>";
			//var_dump($form_fields);
			//var_dump($post);
			echo "</pre>";
			
		}
		
 
    return $form_fields;
	}
	
	

/**
 * *****************************************************************************************************************************
 *  trait: Add wpd_data to theme_engine
 * source: wpd_trait_theme.php
 */
//use wpd_trait_theme;

	function Af_switch_themes_file($template) {

		global $wp_query,$wpdb,$post,$wpd_instance;
		if ( $this->wpd_category_name == $wp_query->post->post_type && is_archive() )
			{
				include(WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'/themes/'.'archive-' . $this->wpd_category_name . '.php');
				exit;
			}
		elseif ( $this->wpd_category_name == $wp_query->post->post_type && is_single() )
			{
				include(WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'/themes/'.'single-' . $this->wpd_category_name . '.php');
				exit;
			}
		elseif ( $this->wpd_category_name == $wp_query->post->post_type && is_404()  )
			{
				include(WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'/themes/'.'pet_detail_report.php');
				exit;
			}
			  
  }

	function wpd_header() {

	 include(WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'/themes/'.'header-' . $this->wpd_category_name . '.php');
			  
  }

	function wpd_footer() {

	 echo "<div style='text-align: right;margin-top: 20px;'>plugged by <a target='_blank' href='https://github.com/kkinjo/FullCustomPost_for_animal_rescue' >FullCustomPost_for_animal_rescue</a>. created by <a target='_blank' href='http://about.me/katsumi.kinjo' >katsumi kinjo</a></div>";
			  
  }

	function wpd_sidebar() {

		include(WP_PLUGIN_DIR.'/'.$this->wpd_plugin_dirname.'/themes/'.'sidebar-' . $this->wpd_category_name . '.php');
			  
  }

	function Aa_add_css_to_theme() {
	  global $wpdb,$post;

	  if ( $this->wpd_category_name == get_post_type( $post ))
			  {
				  echo '<!-- プラグイン wans_pet_detail 用 CSS -->';
				  echo '<link type="text/css" rel="stylesheet" href="'.WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'css/wans_pet_detail.css">';
				  echo '<link type="text/css" rel="stylesheet" href="'.WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'css/buttons.css">';
				  //echo 'here is in '.$this->wpd_category_name;
			  }
		  else
			  {
				  //var_dump($wpdb);
				  //echo "<BR><BR><BR><BR><BR>";
				  //print_r( get_defined_vars() );
			  }
			  }

	function Aa_add_jscript_to_theme() {
		global $post;

		if ( $this->wpd_category_name == get_post_type( $post ) ){
			wp_enqueue_script('buttons', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/buttons.js',array('jquery'));
			wp_enqueue_script('wans_pet_detail', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/wans_pet_detail.js',array('jquery'));
			//wp_enqueue_script('jquery-ui-1.9.0', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/jquery-ui-1.9.0.custom.js',array('jquery'));
			//wp_enqueue_script('pqgrid', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/pqgrid.min.js',array('jquery'));
			//wp_enqueue_script('stickytableheaders', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/jquery.stickytableheaders.js',array('jquery'));
			//wp_enqueue_script('fixedTblHdrLftCol', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/jquery.fixedTblHdrLftCol.js',array('jquery'));
			//wp_enqueue_script('tablefix_1', WP_PLUGIN_URL.'/'.$this->wpd_plugin_dirname.'js/jquery.tablefix_1.0.1.js',array('jquery'));
		}
  }

	function wpd_age($birthyear_almost_flag,$birthyear){
		$birthyear = str_replace("-", "",$birthyear);
		if($birthyear > 19000000){
			$wpd_age = (int) ((date('Ymd')-$birthyear)/10000);
			echo $birthyear_almost_flag.$wpd_age."歳";
		}
	}


/**
 * *****************************************************************************************************************************
 *  trait: Add action and fileter to archive_engine.
 *		 And mange select_query from wpd_table.
 * source: wpd_trait_archive.php
 */

	//use wpd_trait_archive;
	  function Aa_chage_posts_per_page( $query ){
		 if (is_post_type_archive('pet_detail')) {
			 $query->set( 'posts_per_page', $this->wpd_archive_page_post_count );
		return;
			 }
	 }

	function wpd_get_archives($wpd_cate_page,$wpd_cate_where=null,$wpd_cate_order=null,$view_mode=null) {
		global $wpdb;
		$wpd_cate_select_from = "SELECT * FROM ".$this->table_name;
		if( is_null ( $wpd_cate_where ) ) {
			$wpd_cate_where = "";
		}
		else{
			
			$wpd_cate_where = "where ".$wpd_cate_where;
		}
		

		if( is_null ( $wpd_cate_order ) ) {
			$wpd_cate_order = "";
		}
		else{
			$wpd_cate_order = "order by ".$wpd_cate_order;
		}
		
		if( $view_mode == "detail_list" ) {
			$this->wpd_archive_page_post_count = 50;
		}

		$wpd_cate_page_limit = "limit ".(($wpd_cate_page-1)* $this->wpd_archive_page_post_count).",".$this->wpd_archive_page_post_count;

		$wpd_cate_sql = $wpd_cate_select_from." ".$wpd_cate_where." ".$wpd_cate_order." ".$wpd_cate_page_limit;
		$wpd_get_cate = $wpdb->get_results($wpd_cate_sql);
		return isset($wpd_get_cate) ? $wpd_get_cate : null;
	}

	function wpd_get_archives_count($wpd_cate_where=null,$wpd_cate_order=null) {
		global $wpdb;
		$wpd_cate_select_from = "SELECT count(*) FROM ".$this->table_name;
		if( is_null ( $wpd_cate_where ) ) {
			$wpd_cate_where = "";
		}
		else{
			
			$wpd_cate_where = "where ".$wpd_cate_where;
		}		

		if( is_null ( $wpd_cate_order ) ) {
			$wpd_cate_order = "";
		}
		else{
			$wpd_cate_order = "order by ".$wpd_cate_order;
		}

		$wpd_cate_sql = $wpd_cate_select_from." ".$wpd_cate_where." ".$wpd_cate_order;
		$wpd_get_cate = $wpdb->get_results($wpd_cate_sql,ARRAY_N );
		return $wpd_get_cate[0][0];
	}

	function wpd_get_rows_for_report($report_sql) {
		global $wpdb;
		
		$wpd_report_record = $wpdb->get_results($report_sql,ARRAY_A);
		
		$result_html = '<table>';
		foreach ( $wpd_report_record as $record_key => $record_value ) {
			if ( $record_key == '0' ){
				$result_html .= "<thead>";
				foreach ( $record_value as $temp_colname => $temp_value ) {
					$result_html .= "<th>{$temp_colname}</th>";
				}
				$result_html .= "</thead><tbody>";
			}
			$result_html .= "<tr>";
			foreach ( $record_value as $temp_colname => $temp_value  ) {
				$result_html .= "<td>{$temp_value}</td>";
				
			}
			$result_html .= "</tr>";
		}
		$result_html .= '</tbody></table>';
	
		echo $result_html;
	}

	function wpd_get_rows_generic($report_sql) {
		global $wpdb;
		$wpd_report_record = $wpdb->get_results($report_sql,ARRAY_A);

		return $wpd_report_record;
	}



	function wpd_pagination( $pages = '' ) {
		$range = 4;
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

	function wpd_query_condtion_setting($sia){		
		/* ********************************************************************
		 * get valid set from DB and set URL_param to input_checkd	
		 * 
		 * 概要 
		 * ---------------------------------------------------
		 * カスタムポストに紐づくカスタムテーブルのデータに対し、検索条件を生成し、
		 * URLパラメータと連動させつつ、WordPress のメインクエリを変更するための、
		 * SQL文を生成する関数。
		 * 
		 * 詳細
		 * ---------------------------------------------------
		 * URL_paramの引数情報を元に、以下の 3つの要素で構成される配列を生成する。
		 * 
		 * 　1. DB より実際に存在する値を基とした、URL_param で設定されたチェック
		 *	  済みの情報が反映されている 検索ボックス HTML ノード query_box
		 *   2. WordPress のメインクエリに Where 句を追加する関数 Af_query_conditon_where
		 *	  で使用 する WHERE句条件の SQLパーツ
		 *   3. WordPress のメインクエリに join 句を追加する関数 Af_query_conditon_join
		 *	  で使用 する JOIN句の SQLパーツ
		 * 
		 * この関数は、配列を生成し、返すだけの処理で クラスの コンストラクタで実行される。
		 * 1.については、query_box を表示する アーカイブページで直接 echo される。
		 * 2.と 3. は クラスコンストラクタで実行される 関数 Af_query_conditon_where/join
		 * 内に引き渡される
		 * 
		 * 
		 * 本体
		 * ---------------------------------------------------
		 * ***********************************
		 * ■まずは URLパラメータ order_by の取得
		 * URLパラメータの order_by は スペース区切りで 列名 オプション値 で構成されているので、
		 * これを 列名=>オプション値 の配列として、$wpd_requested_order_by_array に格納
		 */
		
		global $wpdb;
		
		$wpd_url_order_by_array = explode(" ", $_GET["order_by"]);
		$wpd_url_order_by_count = count($wpd_url_order_by_array) ;
		$wpd_requested_order_by_array = array();
		for ($i = 0; $i < $wpd_url_order_by_count; $i++) {
			$wpd_requested_order_by_array[$wpd_url_order_by_array[$i]] = $wpd_url_order_by_array[++$i];
		}

		/* ***********************************
		 * ■メイン処理
		 * 1.value_set の取得
		 * 2.input_checkd に反映
		 * 3.SQL生成用の変数を作成
		 */
		foreach($sia as $sia_name => &$t_arry){
			/* value_set を設定
			 * radio の場合はそのままで OK なので、checkbox のみ、DB より取得
			 */
			if( $t_arry["input_type"] ==="checkbox" ) {
				/* 列名($sia_name) を元に、実際に格納されている全値を集計して カンマ区切りで返す SQL */
				$wpd_get_query_conditon_items_sql = "SELECT group_concat(distinct ".$sia_name.
						" order by ".$sia_name." separator ',' ) AS ".lists." FROM ".$this->table_name." where ".$sia_name." is not null";

				/* SQL文を実行して、結果を配列で value_set に格納 */
				$wpd_get_query_conditon_items = $wpdb->get_results($wpd_get_query_conditon_items_sql,ARRAY_A );
				$t_arry["value_set"] = explode(",",$wpd_get_query_conditon_items[0]["lists"]);
				
			}

			/*
			 * input_checkd 用に checkd を設定し、SQL生成用の変数を作成
			 * SQLインジェクション対策で URLパラメータの値が 『検索設定配列』の
			 * value_set に含まれる 場合にのみ、checked にして,それ以外は default を反映。
			 */
			if( $t_arry['condition_type'] === "order_by" ){

				/* URLパラメータ は 1. の $wpd_requested_order_by_array を使用*/
				if( array_key_exists($wpd_requested_order_by_array[$sia_name],$t_arry["value_set"])){
					$t_arry['checked']=$wpd_requested_order_by_array[$sia_name];
				}
				else{
					$t_arry['checked']=$t_arry['default'];
				}
				/*最後に SQLクエリに反映。 */
				$checked_for_query_order_array[]= $this->table_name . "." . $sia_name . " ". $t_arry['checked'];
			}
			elseif( $t_arry['condition_type'] === "where" ){

				/* 列名($sia_name) に基づいて GETパラメータの値を取得し、スペースで分割して配列に格納 */
				$wpd_requested_where_array = explode(" ", $_GET[$sia_name]);
				
				/* is null と in not null を追加 */
				$value_set_plus_nulls = $t_arry["value_set"];
				$value_set_plus_nulls[] ="is_not_null";
				$value_set_plus_nulls[] ="is_null";
				
				/* 取得GETパラメータの配列を foreach で value_set と比較して、元配列の checked 配列に一つづつ追加 */
				foreach ( $wpd_requested_where_array as $rwa_key => $rwa_arry ) {

					if(  in_array ( $rwa_arry, $value_set_plus_nulls )){

						if( $t_arry["input_type"] ==="checkbox" ){
							$t_arry['checked'][]=$rwa_arry;
						}
						elseif( $t_arry["input_type"] ==="radio" ){
							$t_arry['checked']=$rwa_arry;
						}
					}
					else{
						$t_arry['checked']=$t_arry['default'];
					}
				}
				/* 最後に SQLクエリに反映。 */
				/* 複数項目選択(in条件)の場合は配列なので is_array でチェック */
				if(!empty($t_arry['checked'][0]) && is_array($t_arry['checked'])){
					$checked_for_query_where_array[]= $this->table_name.".".$sia_name." in ('".implode("','",$t_arry['checked'])."')";
				}
				/* 配列じゃない場合は = 条件 */
				elseif(!empty($t_arry['checked'])
						&&
						!is_array($t_arry['checked'])
						&&
						$t_arry['checked'] !== "all"
						){
					/*  */
					if( $t_arry['checked'] === "is_null" || $t_arry['checked'] === "is_not_null"  ){
						$t_arry_edit = str_replace("_"," ",$t_arry['checked']);
						$checked_for_query_where_array[]= $this->table_name.".".$sia_name." ".$t_arry_edit;
					}
					else{
						$checked_for_query_where_array[]= $this->table_name.".".$sia_name." = '".$t_arry['checked']."'";
					}
				}

			}
		}
		unset($t_arry);


		/* ***********************************
		 * ■クエリ生成
		 */
		if( !empty( $checked_for_query_where_array)){
			$checked_for_query_where = implode(' and ', $checked_for_query_where_array);
		}
		$checked_for_query_order = implode(',', $checked_for_query_order_array);
		
		/* ***********************************
		 * ■view_mode の取得
		 */
		$view_mode_setting = $_GET["view_mode"];
		if( $view_mode_setting == "detail_list" ){
			$detail_list_checked = "checked ";
		}

		/* ********************************************************************
		 * ■ 検索ボックスの HTMLノード query_box の生成
		 * genelate query box
		 * ***********************************
		 * line 先頭部分
		 */

		$wpd_ocp1 = "	<div class='query_conditon_line' line_name='";
		$wpd_ocp1_2="'><div class='condition_discription'>";
		$wpd_order_discript="並び替え";
		$wpd_where_discript="絞込";
		$wpd_ocp2= "</div>\n<div class='condition_data'><span class='button button-rounded ' reset_target='";
		$wpd_ocp3= "'>リセット</span>";

		$order_condition_prefix=$wpd_ocp1."order".$wpd_ocp1_2.$wpd_order_discript.$wpd_ocp2."order".$wpd_ocp3;
		$where_condition_prefix=$wpd_ocp1."where".$wpd_ocp1_2.$wpd_where_discript.$wpd_ocp2."where".$wpd_ocp3;
		$wpd_condition_view  ="<div id='condition_view' class='query_conditon_line'><div class='condition_discription'>条件</div><div class='condition_data'>";

		/* ***********************************
		 * 列ごとに要素生成
		 */
		foreach($sia as $sia_name => $t_arry){

			/* 各項目トップレベル + プレビュー領域 */
			$$t_arry['condition_type'] .= "<span class='button-dropdown' data-buttons='dropdown' type=".$t_arry[input_type]." id='". $sia_name ."'>";
			$$t_arry['condition_type'] .= "		<span wpd_q_id='".$sia_name."' wpd_q_input_type=". $t_arry[input_type] ." class='button button-rounded button-flat-primary'>".$t_arry['discription'];
			$$t_arry['condition_type'] .= "			<span class='now_conditionicon_preview'></span>";
			$$t_arry['condition_type'] .= "		</span>";

			/* radio ボタンの場合 */
			if( $t_arry[input_type] === "radio" ){
				$$t_arry['condition_type'] .= "<ul>";

				foreach($t_arry['value_set'] as $value => $text){
					$value_checked = "";
					$default_value = "";

					if( $value === $t_arry['checked'] ){$value_checked = "checked";}else{$value_checked = "";}
					if( $value === $t_arry['default'] ){$default_value = "default";}else{$default_value = "";}

					$$t_arry['condition_type'] .= "<li class='item_list'>".$text;
					$$t_arry['condition_type'] .= "	<input type='radio'  class='".$t_arry['condition_type']."_condition query_condition' name='".$sia_name."' value='".$value."' ".$value_checked." ".$default_value.">";
					$$t_arry['condition_type'] .= "</li>";
				}

				$$t_arry['condition_type'] .= "</ul>";
			}

			/* チェックボックスの場合 */
			elseif( $t_arry["input_type"] ==="checkbox" ) {

				foreach($t_arry["value_set"] as $colname => $value){
					if( $value != "") {

						if( in_array($value , $t_arry['checked'] )){
							$value_checked = "checked";
						}
						else{
							$value_checked = "";
						}

						if( in_array($value , $t_arry['default'] )){
							$default_value = "default";
						}
						else{
							$default_value = "";
						}
						${$sia_name._items} .= "<label class='button button-rounded button-flat-primary'>".$value;
						${$sia_name._items} .= "	<input type='checkbox'  class='".$t_arry['condition_type']."_condition query_condition' name='".$sia_name."' value='".$value."' ".$value_checked." ".$default_value.">";
						${$sia_name._items} .= "</label>";
					}
				}
				/* 別の領域に各項目を出力させる */
				$wpd_condition_view .="<div id='".$sia_name."_items_view' class='view_line' line_name=".$sia_name.">";
				$wpd_condition_view .="	<span class='button button-flat all_item' target='".$sia_name."'>すべて</span>";
				$wpd_condition_view .=	${$sia_name._items};
				$wpd_condition_view .="</div>";
			}

			$$t_arry['condition_type'] .= "</span>";

		}

		$conditions_suffix="	</div></div>";

		/* ***********************************
		 * 仕上げ
		 */
		$wpd_query_box ="<form action='".home_url($this->wpd_category_name)."' method='get' name='wpd_query_condition_from'>";
		$wpd_query_box .="<div id='query_conditon_block'>";
		$wpd_query_box .=$order_condition_prefix.$order_by.$conditions_suffix;
		$wpd_query_box .=$where_condition_prefix.$where.$conditions_suffix;
		$wpd_query_box .=$wpd_condition_view.$conditions_suffix;
		$wpd_query_box .="</div><div id='query_submit'>";
		$wpd_query_box .="<label for='view_mode' class='button button-flat-caution wpd_query_condition_from_submit' >一覧表示<input type=checkbox name=view_mode id=view_mode value='detail_list' ".$detail_list_checked."></label>";
		$wpd_query_box .="<input class='button button-flat-action wpd_query_condition_from_submit' type='button' onclick='wpd_query_js()' value='検索'>";
		$wpd_query_box .="</div><div id='query_url_debug_div'></div></form>";

		/*
		 * 最後に配列にして、返す。
		 */
		return array('where'=>$checked_for_query_where,'order'=>$checked_for_query_order,'query_box'=>$wpd_query_box);
	}

	function Af_query_conditon_join($join){
		global $wpdb;
		
		$join .= " LEFT JOIN $this->table_name ON " . 
		$wpdb->posts . ".ID = " . $this->table_name . 
		".post_id ";
		
		//echo $join."<BR>";
		return $join;
	}
	function Af_query_conditon_where($where){
		
		if(!empty( $this->wpd_query_condtions['where'])){
			$where .= " and " . $this->wpd_query_condtions['where'] . " ";
		}
		
		
		
		
		//echo $where."<BR>";
		return $where;
	}
	function Af_query_conditon_orderby($orderby){
		
		$orderby .= ", ".$this->wpd_query_condtions['order'];
		
		//echo $orderby."<BR>";
		return $orderby;
	}
	

}
/**
 * クラスのインスタンス化
 */
$wpd_instance = new Wpd_class;

define('SAVEQUERIES', 1);

//add_action('shutdown', 'on_shutdown');

function on_shutdown() {
	if (is_post_type_archive('pet_detail')) {
	global $wpdb;
	echo '<table class="wp-list-table widefat fixed posts">';
	echo '<thead><th>SQL</th><th>Time</th><th>Caller</th></thead>';
	echo '<tbody>';
	foreach ($wpdb->queries as $q) {
		list ($query, $time, $caller) = $q;
		echo sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>\n",
			$query, $time, str_replace(',', "<br>\n", $caller));
	}
	echo '</tbody>';
	echo '</table>';
		}
}

function kkdump($t_v){
	echo "<PRE>";
	var_dump($t_v);
	echo "</PRE>";
}


?>