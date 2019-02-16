<?php
require_once 'rws-php-sdk/autoload.php';
 
$client = new RakutenRws_Client();
// アプリID (デベロッパーID) をセットします
$client->setApplicationId('1004837992476132601');
 
// 楽天市場商品検索API では operation として 'IchibaItemSearch' を指定してください。
// ここでは例として keyword に、うどんを指定しています。
$response = $client->execute('IchibaItemSearch', array(
  'keyword' => 'シャンプー'
));
 
// レスポンスが正常かどうかを isOk() で確認することができます
if ($response->isOk()) {
    // 配列アクセスで情報を取得することができます。
    echo $response['count']."件見つかりました。\n";
 
    // タイトル行
    file_put_contents("sample.csv", "\"post_id\",\"post_name\",\"post_author\",\"post_date\",\"post_type\",\"post_status\",\"post_title\",\"post_content\",\"post_category\",\"post_tags\",\"custom_field\"\n");

    // foreach で商品情報を順次取得することができます。
    foreach ($response as $item) {
        // echo $item['itemName']."\n";
        // post_id
        file_put_contents("sample.csv", ",",FILE_APPEND);
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
        file_put_contents("sample.csv", $item['itemName'].",",FILE_APPEND);
        // post_category
        file_put_contents("sample.csv", ",",FILE_APPEND);
        // post_tags
        file_put_contents("sample.csv", ",",FILE_APPEND);
        // custom_field
        file_put_contents("sample.csv", "\n",FILE_APPEND);
    }
} else {
    // getMessage() でレスポンスメッセージを取得することができます
    echo 'Error:'.$response->getMessage();

    file_put_contents("sample.csv", "ERROR");
}
