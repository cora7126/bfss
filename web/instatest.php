<?php
$username = 'gulshankumar8233';
$instaResult = file_get_contents('https://www.instagram.com/'.$username.'/?__a=1');
echo "<pre>";
print_r($instaResult);

/*$url = "https://www.instagram.com/".$username;
$html = file_get_contents($url);
$arr = explode('window._sharedData = ',$html);
$arr = explode(';</script>',$arr[1]);
$obj = json_decode($arr[0] , true);*/
die;

$insta = json_decode($instaResult);
$instagram_photos = $insta->graphql->user->edge_owner_to_timeline_media->edges;

foreach($instagram_photos as $instagram_photo){
echo $instagram_photo->node->display_url."<br>";
}


