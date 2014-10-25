<?php
/****************************************

	pet_detail_report.php
 * 
 * created by kkinjo 2014/05/26

*****************************************/




$wpd_report = 
		'<div class="grid_12 push_0" id="main">'
		. '<div class="detail_thumbnail_box-top"></div>'
		. '<div class="detail_thumbnail_box-middle">'
		. ''
		. '<B><H1>レポートサマリー</H1></B><BR>'
		. '<H2>全データのステータス</H2>';

		$sql_1 = "select ( case when now_status is null then 'ステータス不明' when now_status = '' then 'ステータス不明' else now_status end) AS 'ステータス',count(now_status) AS  '頭数' from wp1_wpd group by ( case when now_status is null then 'ステータス不明' when now_status = '' then 'ステータス不明' else now_status end)  order by '頭数'";
$wpd_report .= $wpd_instance->wpd_get_rows_for_report( $sql_1 );

$wpd_report 
		.='<BR>'
		. '<H2>月間ステータス変動状況</H2>';

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
		$sql_2 .= " order by 1 desc ";
$wpd_report .= $wpd_instance->wpd_get_rows_for_report( $sql_2 );
		
$wpd_report 
		.="<BR>"
		. "<H2>要調整</H2>";

		$sql_3  = "select '未避妊/去勢' as 調整が必要な状態"
				. "      ,count(*) as '頭数'"
				. "  from wp1_wpd"
				. " where neutering =''"
				. "union all "
				.  "select '未ワクチン' as 調整が必要な状態"
				. "      ,count(*) as '頭数'"
				. "  from wp1_wpd"
				. " where vaccine =''"
				. "union all "
				.  "select 'トライアル期間終了' as 調整が必要な状態"
				. "      ,count(*) as '頭数'"
				. "  from wp1_wpd"
				. " where date_add(recent_status_change, interval 14 day) < CURDATE()"
				. "   and now_status ='トライアル中です'"
				. "union all "
				.  "select '長期間調整中' as 調整が必要な状態"
				. "      ,count(*) as '頭数'"
				. "  from wp1_wpd"
				. " where date_add(recent_status_change, interval 14 day) < CURDATE()"
				. "   and now_status ='調整中'";
$wpd_report .= $wpd_instance->wpd_get_rows_for_report( $sql_3 );


$wpd_report 
		.="<BR>"
		. '<B><H1>レポート詳細(即時対応が必要なワンコ)</H1></B><BR>'
		. "<H2>避妊去勢/ワクチン未摂取</H2>";

		$sql_3  = "select CONCAT('<a href=wans',meta_id,' target=_blank>',pet_name,'</a>') as '名前'"
				. "      ,neutering as '避妊去勢'"
				. "      ,vaccine as 'ワクチン'"
				. "      ,now_status as '現在のステータス'"
				. "  from wp1_wpd"
				. " where neutering =''"
				. "    or vaccine =''";
$wpd_report .= $wpd_instance->wpd_get_rows_for_report( $sql_3 );
		
$wpd_report 
		.="<BR>"
		. "<H2>トライアル期間(2週間経過)終了</H2>";
		$sql_4  = "select CONCAT('<a href=wans',meta_id,' target=_blank>',pet_name,'</a>') as '名前'"
				. "      ,recent_status_change as 'トライアル開始日'"
				. "      ,date_add(recent_status_change, interval 14 day) as '終了予定日'"
				. "  from wp1_wpd"
				. " where date_add(recent_status_change, interval 14 day) < CURDATE()"
				. "   and now_status ='トライアル中です'";
$wpd_report .= $wpd_instance->wpd_get_rows_for_report( $sql_4 );
		
$wpd_report 
		.="<BR>"
		. "<H2>長期間調整中の保護犬</H2>";
		$sql_4  = "select CONCAT('<a href=wans',meta_id,' target=_blank>',pet_name,'</a>') as '名前'"
				. "      ,recent_status_change as '調整中開始日'"
				. "      ,date_add(recent_status_change, interval 14 day) as '2週間経過日'"
				. "  from wp1_wpd"
				. " where date_add(recent_status_change, interval 14 day) < CURDATE()"
				. "   and now_status ='調整中'";
$wpd_report .= $wpd_instance->wpd_get_rows_for_report( $sql_4 );

$wpd_report 
		.="<BR>"
		. '<B><H1>レポート詳細(譲渡活動改善が必要なワンコ)</H1></B><BR>'
		. "<H2>ワンズ歴の長いワンコ TOP 5</H2>";

		$sql_3  = "select CONCAT('<a href=wans',meta_id,' target=_blank>',pet_name,'</a>') as '名前'"
				. "      ,wans_reg_date as 'ワンズ登録日'"
				. "      ,vaccine as '経過日数'"
				. "  from wp1_wpd"
				. " where now_status ='里親、募集しています'"
				. " order by `wp1_wpd`.`wans_reg_date` ASC "
				. " LIMIT 0 , 5";
$wpd_report .= $wpd_instance->wpd_get_rows_for_report( $sql_3 );


$wpd_report 
		.="<BR>";
		//	echo "<H2>入力情報不足</H2>";
		$sql_5  = "select CONCAT('<a href=wans',meta_id,' target=_blank>',pet_name,'</a>') as '名前'"
				. "      ,neutering as '避妊去勢'"
				. "      ,vaccine as 'ワクチン'"
				. "      ,vaccine as 'ワクチン'"
				. "      ,vaccine as 'ワクチン'"
				. "  from wp1_wpd"
				. " where neutering =''"
				. "    or vaccine =''"
				. "    or vaccine =''"
				. "    or vaccine =''"
				. "";
		//$res = $wpd_instance->wpd_get_rows_for_report( $sql_5 );

$wpd_report 
			.="<H2>写真の数がすくないワンコ TOP5</H2>"
			. "<H2>アクセス数が少ないワンコTOP5</H2>"
			. "<H2>更新されていないワンコTOP5</H2>"
			. "<H2>項目が足りないワンコTOP5</H2>"
			. "	<H2>全体的入力項目が不足しているワンコ5</H2>"
			. "	<H2>性格/ストーリーの文字数が少ないワンコTOP5</H2>"
			. "<H1>関係者負荷状況<H1>"
			. "<H2>預かり先集計(3以上)</H2>"
			. "<H2>譲渡先ごと集計(3以上)</H2>"
			. "<H2>移動先団体ごと集計</H2>";

$wpd_report 
		.="</div>"
		. '<div class="detail_thumbnail_box-bottom"></div>'
		. "</div>";



