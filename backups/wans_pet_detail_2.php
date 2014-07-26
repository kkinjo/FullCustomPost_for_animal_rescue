<?php
/*
Plugin Name: wan`s pet detail
Plugin URI: http://onesdog.punyu.jp/wans_pet_detail
Description: ワン’sパートナーの里親募集中ワンズの詳細ページ機能を詰め込んだプラグイン
Version: 0.1
Author: 金城 勝美
Author URI: http://catmeetsautumn.blogspot.jp/
*/
require 'wpd_trait_DMI.php';
require 'wpd_trait_admin_page.php';
require 'wpd_trait_theme.php';
require 'wpd_trait_archive.php';


class wpd_class {
	//プラグインのテーブル名
	var $table_name;
	var $wpd_category_name;
	var $wpd_plugin_dirname;
	var $wpd_plugin_url;
	var $wpd_archive_page_post_count;
	
	public function __construct() {
		global $wpdb,$wp_query;
		// 接頭辞（wp_）を付けてテーブル名を設定
		$this->table_name = $wpdb->prefix . 'wpd';
		$this->wpd_category_name = 'pet_detail';
		$this->wpd_plugin_dirname = str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
		$this->wpd_plugin_url = WP_PLUGIN_URL."/".$this->wpd_plugin_dirname;
		$this->wpd_archive_page_post_count = "6";
		
		// プラグイン有効かしたとき実行
		register_activation_hook (__FILE__, array($this, 'wpd_activate'));
		
		// カスタム投稿タイプの登録
		add_action('init'                             , array($this, 'Aa_create_post_type' ));
		add_action('add_meta_boxes'                   , array($this, 'Aa_add_meta_box'));    
		
		/**
		 * データ管理インターフェイス(DMI)の登録
		 * wpd_trait_DMI.php
		 */ 
		add_action('save_post'                        , array($this, 'Aa_DMI_insert_update'));
		add_action('delete_post'                      , array($this, 'Aa_DMI_delete'));
		
		/**
		 * 管理ページ & 投稿ページの最適化()
		 * wpd_trait_admin_page.ph
		 */
		// ポスト一覧に、WPDデータを追加
		add_filter('manage_posts_columns'             , array($this, 'Af_add_petname_col_name'));
		add_action('manage_posts_custom_column'       , array($this, 'Aa_add_petname_col_data'), 10, 2 );
		
		// CSS 及び Javascript の登録
		add_action('admin_print_scripts'              , array($this, 'Aa_add_jscript_to_admin'));
		add_action('admin_print_styles'               , array($this, 'Aa_add_style_to_admin'));
		
		// POSTページの表示形式の変更
		add_action('admin_head'                       , array($this, 'Aa_change_css_on_post_edit'));
		add_filter('enter_title_here'                 , array($this, 'Af_change_title_here_on_post_edit'));
		
		/**
		 * カスタム投稿タイプの表示をテーマエンジンに追加()
		 * wpd_trait_admin_page.ph
		 */
		add_action('wp_head'                          , array($this, 'Aa_add_css_to_theme'));
		add_filter('template_redirect'                , array($this, 'Af_switch_themes_file'));
		
		/**
		 * 追加する()
		 * wpd_trait_admin_page.ph
		 */
		add_action('pre_get_posts'                      , array($this, 'Aa_chage_posts_per_page'));
		
	}
	
	/* プラグイン『wans_@et_detail』を有効化した際に行う初期化処理 */
	function wpd_activate() {
		global $wpdb;
		
		$cmt_db_version = '2013/11/03';
		$installed_ver = get_option( 'cmt_meta_version' );
		// テーブルのバージョンが違ったら作成
		if( $installed_ver != $cmt_db_version ) {
			$wpd_table_create_sql = "CREATE TABLE " . $this->table_name . "
			(
			meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			post_id bigint(20) UNSIGNED DEFAULT '0' NOT NULL,
			pet_name                VARCHAR(30) NOT NULL ,
			birthyear_almost_flag   VARCHAR(10),
			birthyear               DATE,
			Deathyear               DATE,
			photo                   text,
			photo_coordinates       text,
			sex                     VARCHAR(10),
			color                   VARCHAR(30),
			breed                   VARCHAR(30),
			weight                  VARCHAR(30),
			height                  VARCHAR(30),
			wans_reg_date           DATE,
			now_status              VARCHAR(30),
			status_history          text,
			neutering               VARCHAR(10),
			vaccine                 VARCHAR(10),
			health_condition        VARCHAR(30),
			why_is_here             VARCHAR(30),
			story                   text,
			good_point              text,
			bad_point               text,
			supplement              text,
			additional_condition    text,
			additional_cost         text,
			note                    text,
			facebookurl             text,
			photo_url               text,
			phote_fb_url            text,
			detail_paper            VARCHAR(30),
			related_url             text,
			depository              VARCHAR(30),
			depository_tel          VARCHAR(30),
			rescuer                 VARCHAR(30),
			rescuer_tel             VARCHAR(30),
			foster                  VARCHAR(30),
			foster_tel              VARCHAR(30),
			UNIQUE KEY meta_id (meta_id)
			)
			CHARACTER SET 'utf8';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $wpd_table_create_sql );
			update_option( 'cmt_meta_version' , $cmt_db_version );
		}
	}
	
	/* カスタム投稿タイプの追加 */
	function Aa_create_post_type() {
		register_post_type( 'pet_detail'
				,array(
					'labels' => array(
						'name' => __( '保護ワン・ニャンデータ' )
						,'singular_name' => __( '保護ワン・ニャンデータ' )
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
		register_taxonomy( 'pet_detail-cat'
				,'pet_detail'
				,array( 'hierarchical' => true
					,'update_count_callback' => '_update_post_term_count'
					,'label' => '保護ワン・ニャンデータのカテゴリー'
					,'singular_label' => '保護ワン・ニャンデータのカテゴリー'
					,'public' => true
					,'show_ui' => true
					)
				);
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
	 *  trait: data management interface  
	 * source:wpd_trait_DMI.php
	 */
	use wpd_trait_DMI;

	/**
	 *  trait: optimize admin_page and post_page
	 * source: wpd_trait_admin_page.php
	 */
	use wpd_trait_admin_page;
	
    /**
	 *  trait: Add wpd_data to theme_engine
	 * source: wpd_trait_theme.php
	 */
	use wpd_trait_theme;
	
    /**
	 *  trait: Add action and fileter to archive_engine.
	 *         And mange select_query from wpd_table.
	 * source: wpd_trait_archive.php
	 */
	
	use wpd_trait_archive;
     
     
     
}
/**
 * クラスのインスタンス化
 */
$wpd_instance = new wpd_class;

define('SAVEQUERIES', 1);

add_action('shutdown', 'on_shutdown');

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

?>