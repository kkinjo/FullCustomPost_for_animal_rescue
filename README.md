FullCustomPost_for_animal_rescue
================================

wordpress plugin "FullCustomPost_for_animal_rescue"

        <h2>概要</h2> 
        <div>FullCustomPost_for_animal_rescue は、動物愛護活動を行っている個人 又は 団体 向けの Wordpress プラグインです。</div>
        <div>独自で WEBサイトを WordPress で構築されている場合、このプラグインを導入することで、保護した犬の情報を WordPress 内で管理しすることが出来ます。</div>
        <br>
        <div>本プラグインは、沖縄にある NPO法人ワンズパートナーの会 の WEBサイトへの導入を前提に作成されていますので、実際の機能の一部は以下の URL より確認できます。</div>
        <br>
        <a href="http://onesdog.net/pet_detail/" target="_blank" >保護ワンコデータ :: ワンズパートナーの会</a><br>
        <br>
        <div>上記ページのように『保護ワンコ』の年齢、性別、犬種/猫種、避妊/去勢の実施状況、ワクチン摂取状況、と言った基本情報や、里親募集中であるのかトライアル中であるのか、保護の経緯、関連するWEBリンク、また、預かりさん、里親さん 等、保護から譲渡活動に至るまでの情報をすべて WordPress内に保存することができます。</div>
        <br>
        <div>もちろん、一覧表示や、条件検索機能を用いて、一般に公開することもできます。</div>
        
        <h2>技術的な詳細</h2> 
        <div>FullCustomPost_for_animal_rescue は以下の機能により構成されています。</div>
        <br>
        <div><li>WordPress のカスタム投稿タイプ</li></div><div>カスタム投稿タイプ の機能を用いている為、WordPress の他のブログ記事などとは別に管理することができます。</div><BR>
        <BR>
        <div><li>自データベース表</li></div><div>本プラグインのメインの機能の一つとして、独自のデータベース表を作成し、データを投入しているという点があります。</div>
        <div>この実装により、大規模なデータを投入しても、通常のWordPressの記事よりも素早くデータを操作することが可能です。</div>
        <div>また、データベースに詳しく 独自で SQL文を操作出来る方であれば、独自の条件で情報を操作することが可能です。</div>
        <div>実際に、後述する『レポート機能』では、様々な条件、例えば、今月の保護頭数/譲渡頭数情報や、避妊去勢がまだのワンコ、トライアル中であり既に1周間を経過したワンコ と言った情報を表示させています。</div>
        <BR>
        <div><li>カスタムテンプレート</li></div>
        <div>プラグインのディレクトリ内には themes ディレクトリがあり、その中には archive-pet_detail.php や single-pet_detail.php が配置されています。</div>
        <div>つまり、これらのファイルで テンプレートを操作することが可能です。</div>

        <BR>
        <div><li>Jquery UI/ Ajax などを用いたデータ登録画面の補助</li></div><div>カスタム投稿タイプ の機能を用いている為、WordPress の他のブログ記事などとは別に管理することができます。</div><BR>
        <div>データ登録画面では、Jquery UI 及び AJAX 等を使用してデータ自動入力やバリデーションを行っています。</div>
        <div>これにより、入力データの一貫性を持たせ、データ登録を用意にしています。</div>
        <div>サムネイル画像も、データ登録画面で切り抜き等の操作が行えます。</div>

        <BR>
        <div><li>外部データとの連携</li></div>
        <div>Google ドライブより 画像を取得するデータ連携があります。</div>
        <div>Google ドライブで公開している画像がある場合、その ベース URL を入力することで、その画像をワンコデータの個別ページに表示させることが出来ます。</div>        
        <BR>
        
        <h3>機能の詳細</h3>
        <div><li>入力データの一覧</div>
        <table >
            <tr>
                <td>ペットの名前</td><td>プロフィール写真</td><td>誕生日</td><td>没年</td>
            </tr>
            <tr>
                <td>性別</td><td>色</td><td>犬種/種類</td><td>大きさ</td><td>体重</td>
            </tr>
            <tr>
                <td>避妊去勢状況</td><td>ワクチン接種状況</td><td>健康状態</td>
            </tr>
            <tr>
                <td>登録された日</td><td>登録された理由(保護、処分前等)</td><td>現在のステータス</td><td>直近のステータス変更日</td><td>ステータス履歴</td>
            </tr>
            <tr>
                <td>性格/ストーリー</td><td>追加の譲渡条件</td><td>譲渡に際する補足事項</td><td>譲渡の際の追加費用</td>
            </tr>
            <tr>
                <td>Facebookで管理している写真のURLtd><td>FacebookページのURL</td><td>GoogleドライブのURL</td><td>チラシは作成済みかどうか</td><td>関連リンク</td>
            </tr>
            <tr>
                <td>非公開のメモ</td><td>預かりさん氏名</td><td>保護依頼主氏名</td><td>譲渡先氏名</td>
            </tr>
        </table>
        <br>
        <br>
        <div><li>一覧ページ</div>
        <div><li>検索機能</div>
        <div><li>入力しやすい登録ページ</div>
        <div><li>レポート機能</div>
        <br>
        <br>

        <h3>導入方法</h3>
        
        <BR>
        <BR>
        <BR>
