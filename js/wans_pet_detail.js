jQuery(window).load( function () {
    jQuery(function($){

      $("input.query_condition").change(function(){
          wpd_query_check_summary();
      });

      $("span.all_item").click(function(){
          wpd_query_all_item(this);
          wpd_query_check_summary();
      });

      $("[reset_target]").click(function(){
        reset_line(this);
        wpd_query_check_summary();
      });

      wpd_query_check_summary();

      /*
       * 一覧表(pet_detail/?view_mode=detail_list)のスクロール用
       * Jquery プラグイン:fixedTblHdrLftCol を使用
       * $('#detail_list_table').tablefix({width: 500, height: 500, fixRows: 2, fixCols: 2});
       * $('#detail_list_table').fixedTblHdrLftCol({scroll: {width: '750px',hight: '500px',leftCol: {fixedSpan: 2}}});
       * $('#detail_list_table').stickyTableHeaders();
       */
      //changeToGrid();
      $(function(){
          var migi = $(".table_ara");

          migi.scroll(function(){
              migi.scrollLeft($(this).scrollLeft())
          });
      });

      /*
       * 検索条件を引き継がせる為、リンクを無効にして、href を ターゲットにした
       * フォームを複製し、検索条件で POST する フォームを生成する処理。
       * single-pet_detail.php 側で POST データを処理する。
       */
      $("a.detail_thumbnail_link").live("click",
        function(){
            var target_url;
            var ob_list;
            var w_list_radio;
            var w_list_check_temp;
            var w_list_check;
            var view_mode;
            
            var new_form = document.createElement("form");
            new_form.name = "new_form";
            new_form.action = this.href;
            new_form.method = 'post';
            new_form.target = '_blank';
            
            /* ******************* order by句条件 *******************  */
            ob_list = jQuery('input.order_by_condition:checked').map(
                    function(){
                        return jQuery(this).attr("name") + ' ' + jQuery(this).val();
                        }
                      ).get().toString().split(",").join(" ");
            var temp_order_by_condition = document.createElement('input');
            temp_order_by_condition.setAttribute('name', 'order_by');
            temp_order_by_condition.setAttribute('value', ob_list);
            new_form.appendChild(temp_order_by_condition);


            /* ******************* Where句 条件 *******************  */
            w_list_radio = new Array;
            w_list_radio = jQuery('input.where_condition[type=radio]:checked').map(
                    function(){
                        var temp_radio_name = jQuery(this).attr("name");
                        w_list_radio[temp_radio_name] = document.createElement('input');
                        w_list_radio[temp_radio_name].setAttribute('name', temp_radio_name);
                        w_list_radio[temp_radio_name].setAttribute('value', jQuery(this).val());
                        new_form.appendChild(w_list_radio[temp_radio_name]);
                        }
                      );


            /* CHECKBOX式 */
            w_list_checkbox = new Array;
            jQuery('.view_line').map(function(){
                 temp_checkbox_name = jQuery(this).attr("line_name");
                 w_list_check_temp = jQuery("[name="+temp_checkbox_name+"]:checked").map(
                         function(){
                             return jQuery(this).val();
                         }
                                 ).get().toString().split(",").join(" ");
                                 w_list_checkbox[temp_checkbox_name] = document.createElement('input');
                                 w_list_checkbox[temp_checkbox_name].setAttribute('name', temp_checkbox_name);
                                 w_list_checkbox[temp_checkbox_name].setAttribute('value', w_list_check_temp);
                                 new_form.appendChild(w_list_checkbox[temp_checkbox_name]);
                                }
                      );

            document.body.appendChild(new_form);
            new_form.submit();
            return false;
        }
      );
      /*
       * 各項目ページで検索条件を引き継がせる処理。
       */
      $("a[name=np_link]").live("click",
        function(){
            
            document.np.action = this.href;
            console.log(document.np);
            document.np.submit();
            return false;
        }
      );


     /*
      * label を動作させる。
      */
     
     /*
      * ギャラリーモーダル
      */
     $('a[rel*=lightbox]').slimbox();

  });
});


function view_conditions(target_id){
    jQuery(".condition_data").children(".view_line").slideUp();
    if(jQuery("#condition_view").css('display') === 'none') {
        jQuery("#condition_view").slideDown();
    }
    jQuery("#"+target_id+"_items_view").slideToggle();
}


/* 検索フォームの URL 生成ファンクション */
function wpd_query_js(){

    var target_url;
    var ob_list;
    var w_list_radio;
    var w_list_check_temp;
    var w_list_check;
    var view_mode;

    /* ******************* order by句条件 *******************  */
    /*  order by 句の列条件を取得し ob_list に代入 */
    ob_list = jQuery('input.order_by_condition:checked').map(
            function(){
                return jQuery(this).attr("name") + '+' + jQuery(this).val();
                }
              ).get().toString().split(",").join("+");


    /* ******************* Where句 条件 *******************  */
    /* radio ボタン式 */
    w_list_radio = jQuery('input.where_condition[type=radio]:checked').map(
            function(){
                return jQuery(this).attr("name") + '=' + jQuery(this).val();
                }
              ).get().toString().split(",").join("&");


   /* CHECKBOX式 */
   /* CLASS view_line の DIV タグが各列に対応するので、CLASS を条件に line_name情報を
    * 取得し、その line_name が NAME になる input を取得して値を返す。
    * 配列ループ 内で 配列ループしている感じ。*/
    w_list_check = jQuery('.view_line').map(function(){
        value = jQuery(this).attr("line_name");
        w_list_check_temp = jQuery("[name="+value+"]:checked").map(
                function(){
                    return jQuery(this).val();
                }
                        ).get().toString().split(",").join("+");
                        return value + "=" + w_list_check_temp;
                       }
             ).get().toString().split(",").join("&");

     if(jQuery('#view_mode').is(':checked')){
         detail_list = "&view_mode=detail_list ";
     }else{
         detail_list = "";
     }

   /* 各情報を結合し URL を生成 */
   target_url = document.wpd_query_condition_from.action + "?order_by=" + ob_list + "&" + w_list_radio + "&" + w_list_check + detail_list;

   /* URL を出力 */
   location.href = target_url;
}

function wpd_query_check_summary(){
    jQuery('[wpd_q_id]').map(
            function(){
                var target_type = jQuery(this).attr("wpd_q_input_type");
                var target_name = jQuery(this).attr("wpd_q_id");
                if(target_type === "checkbox"){
                    summary_value = jQuery("[name="+target_name+"]:checked").map(
                                        function(){
                                            return jQuery(this).val();
                                        }
                                        ).get().toString();
                    if(summary_value === ""){
                        summary_value = "すべて";
                    }

                }
                else if(target_type === "radio"){
                    var summary_value = jQuery("[name="+target_name+"]:checked").parent().text();
                }
                jQuery("[wpd_q_id="+target_name+"] > span.now_conditionicon_preview").html(" :"+ summary_value);
            }
    );
}

function wpd_query_all_item(self){
    var check_all_target_name = jQuery(self).attr("target");
    jQuery("[name="+check_all_target_name+"]:checked").removeAttr("checked");
}


function reset_line(self){
    var target_line = jQuery(self).attr("reset_target");

    jQuery("[line_name="+target_line+"] .query_condition").removeAttr("checked");
    var target_checkboxes = jQuery('[line_name='+target_line+'] [wpd_q_input_type=checkbox]').map(
            function(){
                return jQuery(this).attr("wpd_q_id");
                }
              ).get();
    jQuery.map(target_checkboxes
               ,function(item, index){
                    jQuery(".query_condition[name="+item+"]").removeAttr("checked");
                }
    );
    jQuery("[line_name="+target_line+"] [default]").attr("checked", true );
}

