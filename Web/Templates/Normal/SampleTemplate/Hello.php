{% parts_block(css) %}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{% resources(CSS/Welcome/style.css) %}">
{% endparts_block %}



{% parts_block(contents_leftup) %}
<h4>マイクロフレームワークを基調としたシステムデザイン</h4>
<p>
    ClearSkyフレームワークは、簡単なWebAPIやホームページを作成するための最低限のシステムを持ったマイクロフレームワークを基調としてシステムがデザインされています。<br>
    そのため、アプリの規模的にフルスタックフレームワークを必要としない場面での使用にも対応します。
</p>
{% endparts_block %}



{% parts_block(contents_leftbottom) %}
<h4>専用プラグイン追加による拡張性の高さ</h4>
<p>
    マイクロフレームワークを基調としながらも、フレームワークに専用のプラグイン（拡張機能）を導入することで、フルスタックフレームワークレベルの大規模開発にも対応します。<br>
    そのため、小規模なWebAPIから大規模なWebアプリケーション開発までを本フレームワークが全てサポートします。
</p>
{% endparts_block %}




{% parts_block(contents_rightup) %}
<h4>Composerによるシステム管理</h4>
<p>
    PHPのパッケージ管理システム「composer」でのシステム管理に対応しました。<br>
    そのため、他のフレームワーク同様composerでのプロジェクト作成や専用プラグインの追加が可能です。
</p>
{% endparts_block %}




{% parts_block(contents_rightdown) %}
<h4>初心者のPHPフレームワーク開発に最適</h4>
<p>
    ClearSkyフレームワークはエラー時の該当箇所の発見支援や検索エンジンでの検索支援のような、開発初心者でも安心して使用できる仕組みを導入しています。<br>
    そのため、初めてのWebアプリケーション開発に最適なフレームワークとなっています。
</p>
{% endparts_block %}



{% parts_block(JavaScript) %}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
{% endparts_block %}

{% assembleTo(SampleTemplate/HelloFrame.php) %}