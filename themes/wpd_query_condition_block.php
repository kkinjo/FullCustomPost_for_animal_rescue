<?php

function wpd_query_condtion_setting($sia){
	/* ********************************************************************
	* get valid set from DB and set URL_param to input_checkd
	* 
	* ***********************************
	* まずは URLパラメータ order_by の取得
	*  URLパラメータの order_by は スペース区切りで 列名 オプション値 で構成されているので、
	* これを 列名=>オプション値 の配列として、$wpd_requested_order_by_array に格納
	*/
	$wpd_url_order_by_array = explode(" ", $_GET["order_by"]);
	$wpd_url_order_by_count = count($wpd_url_order_by_array) ;
	$wpd_requested_order_by_array = array();
	for ($i = 0; $i < $wpd_url_order_by_count; $i++) {
		$wpd_requested_order_by_array[$wpd_url_order_by_array[$i]] = $wpd_url_order_by_array[++$i];
	}

	/* ***********************************
	* メイン処理
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
					" order by ".$sia_name." separator ',' ) AS ".lists." FROM wp_wpd where ".$sia_name." is not null";
		
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
			if( in_array($wpd_requested_order_by_array[$sia_name],$t_arry["value_set"])){
				$t_arry['checked']=$wpd_requested_order_by_array[$sia_name];
			}
			else{
				$t_arry['checked']=$t_arry['default'];
			}
			/*最後に SQLクエリに反映。 */
			$checked_for_query_order_array[]= $sia_name." ".$t_arry['checked'];
		}
		elseif( $t_arry['condition_type'] === "where" ){
			
			/* 列名($sia_name) に基づいて GETパラメータの値を取得し、スペースで分割して配列に格納 */
			$wpd_requested_where_array = explode(" ", $_GET[$sia_name]); 
			
			/* 取得GETパラメータの配列を foreach で value_set と比較して、元配列の checked 配列に一つづつ追加 */
			foreach ( $wpd_requested_where_array as $rwa_key => $rwa_arry ) {
				
				if(  in_array ( $rwa_arry, $t_arry["value_set"] )){
					
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
			/*最後に SQLクエリに反映。 */
			if(!empty($t_arry['checked'][0]) && is_array($t_arry['checked'])){
				$checked_for_query_where_array[]= $sia_name." in ('".implode("','",$t_arry['checked'])."')";
			}
			elseif(!empty($t_arry['checked']) 
					&& 
					!is_array($t_arry['checked'])
					&&
					$t_arry['checked'] !== "all"
					){
				$checked_for_query_where_array[]= $sia_name." = '".$t_arry['checked']."'";
			}
				
		}
	}
	unset($t_arry);


	/* ***********************************
	 * クエリ生成 
	 */
	if( !empty( $checked_for_query_where_array)){
		$checked_for_query_where = "where ".implode(' and ', $checked_for_query_where_array);
	}
	$checked_for_query_order = "order by ".implode(',', $checked_for_query_order_array);

	/* ********************************************************************
	 * genelate query box
	 *  
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
	$wpd_query_box ="<form action='.' method='get' name='wpd_query_condition_from'>";
	$wpd_query_box .="<div id='query_conditon_block'>";
	$wpd_query_box .=$order_condition_prefix.$order_by.$conditions_suffix; 
	$wpd_query_box .=$where_condition_prefix.$where.$conditions_suffix; 
	$wpd_query_box .=$wpd_condition_view.$conditions_suffix; 
	$wpd_query_box ."</div><div id='query_submit'>";
	$wpd_query_box ."<input id='wpd_query_condition_from_submit' class='button button-flat-action' type='button' onclick='wpd_query_js()' value='検索'>";
	$wpd_query_box ."</div><div id='query_url_debug_div'></div></form>";

	return array('where'=>$checked_for_query_where,'order'=>$checked_for_query_order,'query_box'=>$wpd_query_box);
}
	
		
	