<?php
/****************************************

	sidebar-pet_detail.php

*****************************************/
global $wpd_instance;
?>


<!-- sidebar.php -->
<div class="grid_3 pull_9" id="sidebar">

	<aside class="module" id="local-navi_2">
		<hgroup>

		<h2>保護犬情報</h2>
		<h3>PET INFORMATION</h3>

		

	</hgroup>

	<?php

	/**
	 * 見出し部分ここまで
	 */


/****************************************

	サイドバーのアイキャッチ画像

	CHAPTER22,23,24

*****************************************/

	?>

	<p class="eyecatch">

			<img src="<?php echo WP_PLUGIN_URL.'/'.$wpd_instance->wpd_plugin_dirname.'themes' ?>/img/pet_detail_log.jpg" width="214" style="height:158px" alt="">


	</p>
<nav class="sidebar-navi">

					<ul class="accordion  ui-accordion ui-widget ui-helper-reset" role="tablist">

						<li class="current first">

							<ul class="child">

							
	    						<li><a class="wpd_a" href="http://onesdog.net/pet_detail">保護犬一覧</a></li>

							
	    						<li><a class="wpd_a" href="http://onesdog.net/family/event/">譲渡会のお知らせ</a></li>

							
	    						<li><a class="wpd_a" href="http://onesdog.net/vola/">ボランティア募集</a></li>

							
	    						<li><a class="wpd_a" href="http://onesdog.net/support/">ご支援のお願い</a></li>

							
							</ul>
						</li>

					</ul>

				</nav>
	</aside>

</div>

<div class="grid_3 pull_9" id="sidebar" style="margin-top: 30px;">
	<div id="fb-root"></div>

	<aside class="module" id="local-navi_2">
		<nav class="sidebar-navi">
				<div class="fb-like-box" data-href="https://www.facebook.com/onespartner.satooya" data-width="200" data-height="800" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="true" data-show-border="false"></div>
		</nav>
	</aside>
</div>

<!-- sidebar -->

<!-- /sidebar.php -->