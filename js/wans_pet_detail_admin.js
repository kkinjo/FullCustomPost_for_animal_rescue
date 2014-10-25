jQuery('document').ready(function(){

});

//ウィンドウ初回読み込み時
jQuery(window).load( function () {

  jQuery(function($){

    /* *******************************************
     * 画像編集ステップ 
     */
    // Create variables (in this scope) to hold the API and image size
    var jcrop_api,
          boundx,
          boundy,

        // Grab some information about the preview pane
          $preview_target = $('#previe_target'),
          $preview = $('#preview-pane'),
          $pcnt = $('#preview-pane .preview-container'),
          $pimg = $('#preview-pane .preview-container img'),

          init_Coords = $('[name=photo_coordinates]').val().split(","),
          xsize = $pcnt.width(),
          ysize = $pcnt.height();

    $('img#wpd_cover_photo_orginal').Jcrop({
            onChange: updatePreview,
            onSelect: updatePreview,
            setSelect: init_Coords,
            aspectRatio: 6 / 3
    },function(){
        // Use the API to get the real image size
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];
        

        // Store the API in the jcrop_api variable
        jcrop_api = this;

        // Move the preview into the jcrop container for css positioning
        $preview_target.appendTo(jcrop_api.ui.holder);

    });

   $pimg.css({
        width: Math.round((xsize / init_Coords[4]) * boundx) + 'px',
        height: Math.round((ysize / init_Coords[5]) * boundy) + 'px',
        marginLeft: '-' + Math.round((xsize / init_Coords[4]) * init_Coords[0]) + 'px',
        marginTop: '-' + Math.round((ysize / init_Coords[5]) * init_Coords[1]) + 'px'
    });

    function updatePreview(c)
    {
      jQuery('[name=photo_coordinates]').val(c.x+','+c.y+','+c.x2+','+c.y2+','+c.w+','+c.h);
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;

        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
      }
    };


    jQuery('.media-upload').each(function(){
        var rel = jQuery(this).attr("rel");
        jQuery(this).click(function(){
            
            jcrop_api.release();
            window.send_to_editor = function(html) {
                jcrop_api.destroy();
                imgurl = jQuery('img', html).attr('src');
                
                jQuery('[name=photo]').val(imgurl);
                jQuery('#wpd_cover_photo_preview').attr('src',imgurl);
                jQuery('#wpd_cover_photo_orginal').attr('src',imgurl).css('visibility','visible').css('height','');
                tb_remove();
                
                
                $('img#wpd_cover_photo_orginal').Jcrop({
                    onChange: updatePreview,
                    onSelect: updatePreview,
                    setSelect: init_Coords,
                    aspectRatio: 6 / 3
                },function(){
                    var bounds = this.getBounds();
                    boundx = bounds[0];
                    boundy = bounds[1];
                    
                    jcrop_api = this;
                });
                
            }
            formfield = jQuery('#'+rel).attr('name');
            tb_show(null, 'media-upload.php?post_id=0&type=image&TB_iframe=true&wpd=yes');
            return false;
        }); 
    }); 


    /* ********************************************************
     * status_history 関連の処理
     * 配列を JSON にする。
     */
    // JSON文字列に変換して、status_history に入力
    function make_json(){
        var status_history_array = $('div.status_history_array').map(
                function(){
                    var value2 = $(this).children("input.status_history_array").map(
                            function(){
                                var input_value = this.value;
                                var input_name= $(this).attr('status_history');
                                return '"'+input_name+'":"'+input_value+'"';
                            }).get().join(',');
                    return '{'+value2+'}';
                }
            ).get().join(',');
        var sh_json = '['+status_history_array+']';
        $('input[name=status_history]').val(sh_json);
        return sh_json;    
    }
    
    // JSONから配列に変換して、ソートする
    function sh_sort(target){
        
        //編集中の要素の値を取得
        
        var editing_values = Array(2);
        $(target).parent().children("input.status_history_array").map(
                function(){
                    var input_name= $(this).attr('status_history');
                    editing_values[input_name]=this.value;
                });
        var editing_obj = "{"+editing_values+"}";


                console.log(editing_values["date"]);
                console.log(editing_values["status"]);
                
        //編集中の要素が date と status が記入されていないと発動しない。
        if( 
                editing_values["date"] != ""
                &&
                editing_values["status"] != ""
                )
        {
            //JSONを作成
            var sh_json = make_json();
        
            //配列に変換
            var sh_array = JSON.parse(sh_json);
            sh_array.sort(function(a, b){
                var x = a.date;
                var y = b.date;
                if (x === "") return 1;
                if (y === "") return -1;
                if (x > y) return 1;
                if (x < y) return -1;
                return 0;});
        
            $('div.status_history_array').map(function(index, dom){
                $(this).children("input[status_history=date]").val(sh_array[index]["date"]).attr("value",sh_array[index]["date"]);
                $(this).children("input[status_history=status]").val(sh_array[index]["status"]).attr("value",sh_array[index]["status"]);
                
                //ワンズ登録日 はグレーアウト
                if( sh_array[index]["status"] == "ワンズ登録日" ){
                    $(this).children("input").attr("readonly","readonly");
                    $(this).children("input[status_history=date]").removeClass("wpd_tdp");
                }
                else{
                    $(this).children("input").removeAttr("readonly","readonly");
                    $(this).children("input[status_history=date]").addClass("wpd_tdp");
                }

                //編集中の要素は、sh_now_here を追加
                if( 
                        editing_values["date"] == sh_array[index]["date"]
                        &&
                        editing_values["status"] == sh_array[index]["status"]
                        )
                {
                    $(this).addClass("sh_now_here");
                }
                else{
                    $(this).removeClass("sh_now_here");
                }
            });
        
            //最新の JSON を反映(非表示)
            sh_json = JSON.stringify(sh_array);
            $('input[name=status_history]').val(sh_json);
        }
    }
    
    //初回実行
    make_json();
    //ワンズ登録日をグレーアウト
    $("input.status_history_array[status_history=status][value='ワンズ登録日']").attr("readonly","readonly");
    $("input.status_history_array[status_history=status][value='ワンズ登録日']")
            .parent().children("input[status_history=date]").attr("readonly","readonly").removeClass("wpd_tdp");

    //ソート関数をひもづける
    $('input.status_history_array').live("change",function () {
        sh_sort(this);
    });
    // 色付け
    $('input.status_history_array').live("focus ",function () {
        $("div.status_history_array").removeClass("sh_now_here");
        $(this).parent().addClass("sh_now_here");
    });

    //履歴入力項目を追加
    $("a[name=fcpfar_add_status_history]").live("click dblclick", function(){
        var shat_html = $("#status_history_array_template").html();
        $(this).parent().after("<div class='status_history_array' >"+shat_html+"</div>");
    });
    
    //履歴入力項目を削除
    $("a[name=fcpfar_del_status_history]").live("click dblclick", function(){
        $(this).parent().remove();
        sh_sort();
    });
    
    //現在のステータスを履歴に移動する。
    $("a[name=fcpfar_push_to_history]").live("click dblclick", function(){
        var now_status = $("option[fcpar_s_value=now_status]:selected").val();
        var recent_status_change = $("input[name=recent_status_change]").val();
        $("option[fcpar_s_value=now_status][value=]").attr('selected');
        $("input[name=recent_status_change]").val('');
        
        var shat_html = $("#status_history_array_template").html();
        $('#status_history').append("<div class='status_history_array' new>"+shat_html+"</div>");
        var new_target = $('.status_history_array[new]').children('[status_history=date]').val(recent_status_change);
        $('.status_history_array[new]').children('[status_history=status]').val(now_status );
        $('.status_history_array[new]').removeAttr('new');
        console.log(new_target);
        sh_sort(new_target);
    });
    /* ********************************************************
     * status_history 関連の処理 ここまで
     * ********************************************************
     */


    $(".related_url_array").change( function(){
      var related_url_array = $(".related_url_array").map(function () {
        return this.value;
      }).get().join(",");
        var srelated_url_array_edit =  related_url_array.replace("/,+/",",")
      jQuery('[name=related_url]').val(srelated_url_array_edit);
    });

     $.datepicker.setDefaults($.datepicker.regional['ja']);
     $('.wpd_tdp').live('focusin',function(){$(this).datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        changeMonth: true,
        showOtherMonths: true,
        showButtonPanel: true
    })});

    /* query_block *** start ******************************************** */
    $('[name=post_title]').val("WANS:"+ $('[name=meta_id]').val());
   /* query_block *** end ********************************************** */

/* *******************************************
 * AJAX オートコンプリート
 */

    //複数の値を並び替え、重複値をまとめる関数
    function wpdSortUniqueForAW( inputarray ) {
        
        //スラッシュもしくは空白が、文頭もしくは文末に来た場合削除して、 スラッシュで
        //単語を分割し、ソートして配列にする。
        var regPtr = /(^(\s|\/)+)|((\s|\/)+$)/;
        var temparray = inputarray.replace(regPtr,"").split("/").sort();
        
        //この処理で重複値を削除
        var temp_obj = {};
        for (var i = 0, len = temparray.length; i < len; i++) {  
            temp_obj[temparray[i]] = temparray[i].replace(/\s+$/g, "");
        }
        temparray = [];
        for (key in temp_obj) {
            temparray.push(key);
        }
        
        //配列を スラッシュをキーワードに結合し、文頭/文末の整理
        return temparray.join("/").replace(regPtr,"");
    }
    
    //オートコンプリートメインの処理
    $( ".wpd_input_class[ajax_autocomplete]" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
          if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
              event.preventDefault();
            }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          var request_array = request['term'];
          $.getJSON( "admin-ajax.php?action=wpd_Autocomplete"
          , { 
              target_column:request_array[0]
              ,seted_value:request_array[1] //ここは未実装
            }
          , response );
        },
        search: function() {
          if( $(".kick_ajax_autocomplete[value="+this.name+"]").attr("search") !== "on" ){
            return false;
          }
          
           // 『参考値』をクリックした時だけ autocomplete させるため、keydown 時には search属性を OFF にする。
          $(".kick_ajax_autocomplete[value="+this.name+"]").attr("search","off");
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          
          // autocomplete_multiple クラスの場合は、/ で区切って追加していく
          if ( $(".wpd_input_class[name="+this.name+"]").is("[autocomplete_multiple]") ){
              //文末が / であれば、そのまま追加。そうでない場合は / を付けて追加。
              if ( this.value.match(/\/$/) ) {
                  this.value = this.value+ui.item.value;
              }
              else{
                  this.value = this.value+"/"+ui.item.value;
              }
              this.value = wpdSortUniqueForAW(this.value);
          } 
          else{
              this.value = ui.item.value;
          }
          
          // 『参考値』をクリックした時だけ autocomplete させるため、選択後には search属性を OFF にする。
          $(".kick_ajax_autocomplete[value="+this.name+"]").attr("search","off");
          wpd_form_validateion();
          return false;
        }
      }).focus(function(){
          if ( $(".wpd_input_class[name="+this.name+"]").is("[autocomplete_multiple]") ){
              this.value = this.value.replace(/(^(\s|\/)+)|((\s|\/)+$)/,"")+"/";
          }
          
      });
    
    // オートコンプリートを、INPUT への入力ではなく、『参考値』リンクをリックした時にだけ
    // 起動するようにする処理
      $(".kick_ajax_autocomplete").click(function(){
        
        //どの項目でオートコンプリートするか
        var target_autocomplete = $(this).attr("value");
        //現在の値を取得
        var now_values = $(".wpd_input_class[ajax_autocomplete][name="+target_autocomplete+"]").val();
        //動作項目情報と、現在入力値を配列にして
        var request_atter = Array(target_autocomplete,now_values);
        //search=on を設定する。この設定は、autocomplete関数内で判定させている。
        $(".kick_ajax_autocomplete[value="+target_autocomplete+"]").attr("search","on");
        //この配列情報を基にオートコンプリートをキック
        $(".wpd_input_class[ajax_autocomplete][name="+target_autocomplete+"]").autocomplete("search", request_atter).focus();
    });
    
    //INPUTカーソルが外れた場合、中の値を整理(ソートして重複情報を排除)
    $("[autocomplete_multiple]").blur(function(){
        this.value = wpdSortUniqueForAW(this.value);
    });

    /* *******************************************
     * フォームバリデーション
     */
    $("input").change(function(){
        wpd_form_validateion();
    });
    
    $(".ajax_validate").change(function(){
        wpd_ajax_validate();
    });
    
    wpd_form_validateion();

  });

});

function wpd_ajax_validate(){
    var temp_post_id = jQuery("[name=post_ID]").val();
    jQuery(".ajax_validate").map(
             function(index, dom){
                 
                 jQuery(".err_dup").remove();
                 var temp_name = jQuery(dom).attr("name");
                 var temp_value = jQuery(dom).val();
                 if( temp_value != ""){
                     
                     jQuery.get(
                             "admin-ajax.php?action=wpd_ajax_validate", 
                             { 
                                 target_column:temp_name
                                 ,search_value:temp_value 
                                 ,now_post_id:temp_post_id
                             }, 
                             function(data, status){
                                 
                                 jQuery(dom).removeClass("err").removeClass("dup");
                                 if(data[0] == 0){
                                 }
                                 else if(data[0] > 0){
                                     jQuery(dom).addClass("err dup").after('<p class="err_dup err_dup_'+ temp_name +'">※※重複している値があります。別の値で登録して下さい。※※</p>');
                                     
                                     //wpd_set_err(dom);
                                 }
                                 else{
                                     jQuery(dom).after('<p class="err_dup err_dup_'+ temp_name +'">※※内部エラーが発生しています。登録出来る可能性がありますが別の名前を使用してください。また、管理者にご連絡ください。※※</p>');
                                 }
                             wpd_form_validateion();
                             }, 
                             "json"
                     );
                 }
                   
    });
}


function wpd_set_err(dom){
    console.log(dom);
    wpd_set_err.addClass("err");
}

function wpd_form_validateion(){
        
    jQuery(".input_mandatory").map(
        function(index, dom){
            var temp_value = jQuery(dom).val();
            if(temp_value == ""){
                jQuery(dom).addClass("err");
            }else{
                if(!jQuery(dom).hasClass("dup")){
                    jQuery(dom).removeClass("err");
                }
                
            }
        });
        
        
        if( jQuery("input").hasClass("err") ){
            jQuery("input[type=submit]").attr({"disabled":"disabled","title":"未入力の必須項目があります。"});
        }
        else{
            jQuery("input[type=submit]").removeAttr("disabled").removeAttr("title");
        }
    }
