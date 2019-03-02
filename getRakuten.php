<?php
require_once 'rws-php-sdk/autoload.php';
 
$client = new RakutenRws_Client();
// アプリID (デベロッパーID) をセットします
$client->setApplicationId('1004837992476132601');
 
// 楽天市場商品検索API では operation として 'IchibaItemSearch' を指定してください。
$response = $client->execute('IchibaItemSearch', array(
  'keyword' => 'シャンプー'
));

function getContent($item){
 
  //説明を追加
  $content = $item['itemCaption'];
 
  //本文をreturnで返す
  return $content;
}


// レスポンスが正常かどうかを isOk() で確認することができます
if ($response->isOk()) {
    // 配列アクセスで情報を取得することができます。
    $count = $response['count'];
    echo $count."件見つかりました。\n";
    
    // タイトル行
    file_put_contents("sample.csv", "\"post_id\",\"post_name\",\"post_author\",\"post_date\",\"post_type\",\"post_status\",\"post_title\",\"post_content\",\"post_category\",\"post_tags\",\"custom_field\"\n");

    //ページ数を取得
    $page = ceil($count/30);
    echo $page."ページの情報があります。\n";
    
    //最大100ページまでなので、それ以上の場合は100を設定
    if($page>100){
    	echo "最大100ページまでなので、100ページに制限します。\n";
	$page = 100;
    }
    
    //testなのでページ数を制限する
    $page=2;
    echo "testなので".$page."ページのみ検索します。\n";
 
    //ページの数だけループする
    for ($i=1; $i<=$page; $i++) {
        //ページ指定で再検索します
        $response = $client->execute('IchibaItemSearch', array(
            'keyword' => 'シャンプー',
            'page' => $i
        ));
        
        // foreach で商品情報を順次取得することができます。
        foreach ($response as $item) {
            // post_id
            // really simple csv importerでは、投稿IDが一致する場合は重複登録されないが、数字限定なので数字に変換する
            // 楽天APIのitemCodeは:以降が数字なので、:以降に編集する
            file_put_contents("sample.csv", substr($item['itemCode'],strpos($item['itemCode'],":")+1).",",FILE_APPEND);
            // post_name
            file_put_contents("sample.csv", $item['itemName'].",",FILE_APPEND);
            // post_author
            file_put_contents("sample.csv", "admin,",FILE_APPEND);
            // post_date
            file_put_contents("sample.csv", ",",FILE_APPEND);
            // post_type
            file_put_contents("sample.csv", "post,",FILE_APPEND);
            // post_status
            file_put_contents("sample.csv", "publish,",FILE_APPEND);
            // post_title
            file_put_contents("sample.csv", $item['itemName'].",",FILE_APPEND);
            // post_content
            file_put_contents("sample.csv", getContent($item).",",FILE_APPEND);
            // post_category
            file_put_contents("sample.csv", ",",FILE_APPEND);
            // post_tags
            file_put_contents("sample.csv", ",",FILE_APPEND);
            // custom_field
            file_put_contents("sample.csv", "\n",FILE_APPEND);
        }
    }
} else {
    // getMessage() でレスポンスメッセージを取得することができます
    echo 'Error:'.$response->getMessage();

    file_put_contents("sample.csv", "ERROR");
}

