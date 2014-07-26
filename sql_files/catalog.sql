CREATE TABLE wp_wpd_catalog
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
			,UNIQUE KEY wp_wpd_catalog_col_id (col_id)
			)
			CHARACTER SET 'utf8';";
			

insert into wp_wpd_catalog values(
"1",
"meta_id",
"bigint(20)",
"",
"hidden",
"自動",
"",
"",
"",
"",
"",
"",
"データID",
"登録情報を一意に識別するIDです。<BR>『WANS:数字』の形式で表示される場合があります。"
);


insert into wp_wpd_catalog values(
"2",
"post_id",
"bigint(20)",
"",
"hidden",
"自動",
"",
"",
"",
"",
"",
"",
"WordpressのPOST ID",
"WordpressのPOST ID<BR>本来は非表示です。<BR>Wordpressの管理IDとなります。"
);

insert into wp_wpd_catalog values(
"3",
"pet_name",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_unique_check",
"input_mandatory",
"",
"",
"",
"",
"ペットの名前",
"ペットの名前です。<BR>カーソルを外すと重複した値がないか自動的にチェックします。<BR>重複した値があるとメッセージが出力され、『公開』ボタンが無効化します。<BR>同じ名前が既に登録されている場合は、なにかニックネームや地域名を追加し、重複していない名前に変更して下さい。"
);

insert into wp_wpd_catalog values(
"4",
"birthyear_almost_flag",
"VARCHAR(10)",
"",
"checkbox",
"input",
"",
"",
"",
"",
"",
"",
"正確な誕生日が不明の場合にチェックする",
"正確な誕生日が不明の場合にチェックします。<BR>チェックを入れると、年齢に『だいたい』という文字が追加されます。"
);


insert into wp_wpd_catalog values(
"5",
"birthyear",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"誕生日(日付形式のみ)",
"誕生日を入力します。<BR>不明の場合はおおよその日付を入力して、『だいたい』にチェックを入れて下さい。<BR>未入力の場合は データを登録した後、0000-00-00 という値が自動的に挿入されます。"
);


insert into wp_wpd_catalog values(
"6",
"Deathyear",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"没年(日付形式のみ)",
"没年を入力します。<BR>未入力の場合は データを登録した後、0000-00-00 という値が自動的に挿入されます。<BR>その場合、公開ページにはからの状態で表示されます。"
);

insert into wp_wpd_catalog values(
"7",
"photo",
"text",
"",
"hidden",
"画像アップロード",
"media-upload",
"",
"",
"",
"",
"",
"プロフィール写真(アップロード or 選択)",
"プロフィール写真をアップロードします。<BR>また、メディアライブラリからアップロード済みの画像を選択することも出来ます。<BR>アップロード後は、オリジナル画像に表示されるので、プレビューの項目を見ながら表示される範囲を切り抜きで指定して下さい。<BR>尚、実際の画像は、メディアライブラリではなく 専用のディレクトリに、meta_id.jpg の形式で保存されています。"
);

insert into wp_wpd_catalog values(
"8",
"photo_coordinates",
"text",
"",
"hidden",
"Jcrop",
"Jcrop",
"",
"",
"",
"",
"",
"プロフィール写真プレビュー(切り取り座標設定)",
"写真のサイズ調整切り取り座標です。<BR>オリジナル画像を切り抜く際に自動的に算出されています。"
);

insert into wp_wpd_catalog values(
"9",
"sex",
"VARCHAR(10)",
"",
"text",
"input",
"ajax_autocomplete",
"",
"",
"",
"",
"",
"性別",
"性別です。<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい。<BR>基本的にはオス/メスのみですが 不明 など、自由に記入出来ます。"
);

insert into wp_wpd_catalog values(
"10",
"color",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"",
"",
"",
"",
"",
"色(スラッシュ(/)区切りで自動整理)",
"色です。<BR>複数の色をスラッシュ(/)で区切って指定することも出来ます。<BR>(例:白/茶色)<BR>尚、複数の値を入れる場合、カーソルを外すと自動的に整理されます。<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい。"
);

insert into wp_wpd_catalog values(
"11",
"breed",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"",
"",
"",
"",
"",
"犬種/種類(スラッシュ(/)区切りで自動整理)",
"犬種や種類です<BR>複数の色をスラッシュ(/)で区切って指定することも出来ます。<BR>(例:MIX/ダックス/プードル)<BR>尚、複数の値を入れる場合、カーソルを外すと自動的に整理されます<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい。"
);

insert into wp_wpd_catalog values(
"12",
"breeds_size",
"VARCHAR(30)",
"",
"select",
"input",
"",
"",
"",
"",
"",
"",
"だいたいの大きさ",
"大きさです<BR>0.不明/1.小型/2.中型/3.大型 の4種類から選べます<BR>おおよそで構いません<BR>追加した場合は管理者にご相談ください。"
);

insert into wp_wpd_catalog values(
"13",
"wans_reg_date",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"ワンズに登録された日(日付形式のみ)",
"ワンズに登録された日です<BR>不明の場合は、おおよその日付を入力して下さい<BR>未入力の場合は データを登録した後、0000-00-00 という値が自動的に挿入されます。"
);

insert into wp_wpd_catalog values(
"14",
"now_status",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"input_mandatory",
"",
"",
"",
"",
"現在のステータス(スラッシュ(/)区切りで自動整理)",
"現在のステータスを入力します<BR>『里親募集』『譲渡済み』『わんずぺ～すへ』等の文字を記入します<BR>基本的にはどのような文言でも構いません<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい<BR>"
);

insert into wp_wpd_catalog values(
"15",
"recent_status_change",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"直近のステータス変更日(日付形式のみ)",
"現在のステータスに変更された日付を入力して下さい<BR>通常は、最初の段階では登録された日付と一致すると思います<BR>譲渡されたりわんずぺ～すへ旅立った場合は、その日付を入力します<BR>不明の場合は、おおよその日付を入力して下さい<BR>未入力の場合は データを登録した後、0000-00-00 という値が自動的に挿入されます。"
);

insert into wp_wpd_catalog values(
"16",
"status_history",
"text",
"YES",
"text",
"input",
"",
"",
"",
"",
"",
"",
"ステータス履歴",
"これまでのステータスとそのステータスに変更された日付を入力します<BR>入力後カーソルを外すと自動的に時間順に整理されます<BR>"
);

insert into wp_wpd_catalog values(
"17",
"neutering",
"VARCHAR(10)",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"避妊去勢状況(日付 or 文字。未実施は空)",
"去勢避妊を行った日付を入力して下さい<BR>未実施の場合には、必ず空のままにして下さい<BR>基本的には日付形式で入力しますが、『2014/06/14予定』等フリーフォーマットも可能です<BR>参考値 をクリックすると登録済みのデータ(日付以外)が表示されるので、参考にして下さい。"
);

insert into wp_wpd_catalog values(
"18",
"vaccine",
"VARCHAR(10)",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"ワクチン接種(日付 or 文字。未実施は空)",
"ワクチン接種を接種した日付や、実施状況を入力して下さい<BR>未摂取の場合には、必ず空のままにして下さい<BR>『接種済み』など『3種混合のみ』等フリーフォーマットも可能です<BR>参考値 をクリックすると登録済みのデータ(日付以外)が表示されるので、参考にして下さい。"
);

insert into wp_wpd_catalog values(
"19",
"health_condition",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"",
"",
"",
"",
"",
"健康状態(スラッシュ(/)区切りで自動整理)",
"健康状態を入力して下さい<BR>複数の状態入力する時は、スラッシュ(/)で区切って指定することも出来ます。(例:フィラリア弱/左前足に骨折歴あり<BR>尚、複数の値を入れる場合、カーソルを外すと自動的に整理されます<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい。"
);

insert into wp_wpd_catalog values(
"20",
"why_is_here",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"input_mandatory",
"",
"",
"",
"",
"ワンズに登録された理由(保護、処分前等)",
"経緯に関する要約を入力して下さい<BR>複数の状態入力する時は、スラッシュ(/)で区切って指定することも出来ます。(例:保護/沖縄市多頭崩壊<BR>尚、複数の値を入れる場合、カーソルを外すと自動的に整理されます<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい。"
);

insert into wp_wpd_catalog values(
"21",
"story",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"性格/ストーリー",
"性格や経緯に関する情報をフリーフォーマットで入力します<BR>字数制限はほとんどありません<BR>");

insert into wp_wpd_catalog values(
"22",
"supplement",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"譲渡に際する補足事項",
"里親として迎え入れる、トライアルを希望している人への、追加のメッセージなどをフリーフォーマットで入力します<BR>(例:治療費がかかる事が予想されます<BR>字数制限はほとんどありません。");

insert into wp_wpd_catalog values(
"23",
"additional_condition",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"追加の譲渡条件",
"明確な追加の譲渡条件がある場合はこちらにフリーフォーマットで入力します<BR>(例:多頭飼育不可/供血犬不可<BR>字数制限はほとんどありません。");

insert into wp_wpd_catalog values(
"24",
"additional_cost",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"譲渡の際の追加費用",
"譲渡の際の追加費用をフリーフォーマットで入力します<BR>(例:かかった医療費折半として、追加で50000円頂きます。<BR>字数制限はほとんどありません。");

insert into wp_wpd_catalog values(
"25",
"note",
"text",
"",
"textarea",
"input",
"",
"",
"非公開",
"",
"",
"",
"非公開のメモ(何でも/フリーフォーマット)",
"管理用のメモです。一般には公開されません<BR>公開できない経緯や特記事項を記載します<BR>字数制限はほとんどありません。");

insert into wp_wpd_catalog values(
"26",
"facebookurl",
"text",
"",
"text",
"input",
"",
"",
"",
"",
"",
"",
"FacebookページのURL",
"Facebookページ『里親募集中！ワン'sパートナーの会』で掲載している場合は、そのURLを入力します。");

insert into wp_wpd_catalog values(
"27",
"photo_url",
"text",
"",
"text",
"input",
"",
"",
"",
"",
"",
"",
"WEB共有ドライブのURL(画像を自動取得)",
"複数の写真を掲載したい場合は、googleドライブ内に写真を配置します<BR>プロファイル2 - Google ドライブ<BR>https://drive.google.com/?usp=folder&authuser=0#folders/0BzpLZwemcKepRlpHX0dHRmdaMT<BR>ここの項目では、Googleドライブより『共有』の操作で表示される URL を入力します<BR>URL先の情報を自動的に取得して、公開ページに表示させています<BR>対応するフォルダがない場合や画像をアップロードしたい場合は、管理者にご相談下さい。"
);

insert into wp_wpd_catalog values(
"28",
"phote_fb_url",
"text",
"",
"text",
"input",
"",
"",
"非公開",
"",
"",
"",
"Facebookで管理している写真のURL(URLのみ表示)",
"メモとしてFacebook上の写真のURLを入力します<BR>尚、画像の自動取得は行われません。");

insert into wp_wpd_catalog values(
"29",
"detail_paper",
"VARCHAR(30)",
"",
"text",
"input",
"",
"",
"非公開",
"",
"",
"",
"チラシは作成済みかどうか",
"譲渡会場に配置するチラシが作成済みかどうかを入力します<BR>未作成の場合には、必ず空のままにして下さい<BR>作成済みの場合は『作成済み』などフリーフォーマットで入力します。"
);

insert into wp_wpd_catalog values(
"30",
"related_url",
"text",
"YES",
"text",
"input",
"",
"",
"",
"",
"",
"",
"関連リンク(URLとタイトルを入力)",
"関連するURL情報を入力します<BR>左側にURLを、右側に表示名を記載します<BR>BLOG記事や他サイトのURLでも問題ありません<BR>項目が足りない場合は『+』ボタンをクリックして下さい。"
);


insert into wp_wpd_catalog values(
"31",
"depository",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete",
"",
"非公開",
"",
"",
"",
"保護主氏名",
"保護主の氏名を、敬称略で入力します<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい。");


insert into wp_wpd_catalog values(
"32",
"rescuer",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete",
"",
"非公開",
"",
"",
"",
"預りさん氏名",
"預りさんの氏名を敬称略で入力します<BR>参考値 をクリックすると登録済みのデータが表示されるので、参考にして下さい。"
);


insert into wp_wpd_catalog values(
"33",
"foster",
"VARCHAR(30)",
"",
"text",
"input",
"",
"",
"非公開",
"",
"",
"",
"譲渡先氏名",
"譲渡先の氏名を敬称略で入力します。"
);

