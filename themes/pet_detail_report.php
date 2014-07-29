<?php
/****************************************

	pet_detail_report.php
 * 
 * created by kkinjo 2014/05/26

*****************************************/

if ( is_user_logged_in() ){
	//auth_redirect();
}	
$wpd_instance->wpd_header();

?>
<!-- archive-information.php -->
<div class="grid_12 push_0" id="main">

	<div class="detail_thumbnail_box-top"></div>
	<div class="detail_thumbnail_box-middle">
		<?php 
		
		echo "<H2>全データのステータス</H2>";
		$sql_1 = "select ( case when now_status is null then 'ステータス不明' when now_status = '' then 'ステータス不明' else now_status end) AS 'ステータス',count(now_status) AS  '頭数' from wp1_wpd group by ( case when now_status is null then 'ステータス不明' when now_status = '' then 'ステータス不明' else now_status end)  order by '頭数'";
		$wpd_instance->wpd_get_rows_for_report( $sql_1 );
		//echo $sql_1;

		echo "<BR>";
		echo "<H2>月間ステータス変動状況</H2>";
		$sql_2_1 = "select distinct now_status from wp1_wpd";
		$distinct_now_status = $wpd_instance->wpd_get_rows_generic( $sql_2_1 );
		
		$sql_2  = "select IFNULL(DATE_FORMAT(LAST_DAY(case when recent_status_change = '0000-00-00' then wans_reg_date else recent_status_change end) , '%Y/%c' ) , '不明') AS 'ステータス変更日' ";
		
		foreach ( $distinct_now_status as $now_status_temp ) {
			
			if(empty($now_status_temp[now_status])) {
				$now_status_temp[now_status]="未登録";
			} 
			$sql_2 .= "      ,count(case when ( case when now_status is null then '未登録' when now_status = '' then '未登録' else now_status end)='".$now_status_temp[now_status]."' then 1 else null end) as '".$now_status_temp[now_status]."'";
		}
		
		$sql_2 .= "  from wp1_wpd ";
		$sql_2 .= " group by IFNULL(DATE_FORMAT(LAST_DAY(case when recent_status_change = '0000-00-00' then wans_reg_date else recent_status_change end) , '%Y/%c' ) , '未登録')";
		$sql_2 .= " order by 'ステータス変更日' ";
		$wpd_instance->wpd_get_rows_for_report( $sql_2 );
		
		
		
		

		
		?>
	</div>
	<div class="detail_thumbnail_box-bottom"></div>










</div>
<!-- / archive-information.php -->
<?php 
	get_footer(); 
	die ();
?>