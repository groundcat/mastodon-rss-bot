<?php

/**

PLEASE FOLLOW THE STEPS IN README.MD

**/

// Mastodon and RSS feed configuration:

$token="YOUR_TOKEN_HERE"; 
// Token of your Mastodon bot account

$base_url="https://mastodon.social"; 
// URL of your instance (Do not include '/' at the end.)

$feed_url="https://example.com/feed.xml"; 
// URL of RSS or Atom feed

$visibility="public"; 
// The four tiers of visibility for toots are public (by default), unlisted, private, and direct.

$public_frequency=1; 
// If the value here is 3 and your $visibility value above is "public", then the toot's visibility will be made "public" every 3 toots. Other toots will be "unlisted" by default. If the value here is 1, it means that the visibility is always your $visibility value above.

$language="en"; 
// en for English, zh for Chinese, etc.

header('Content-Type: text/html; charset=utf-8');
if (!ini_get('date.timezone')) {
	date_default_timezone_set('Europe/Prague');
}
// Configure the timezone.

// End of configuration. You don't need to edit anything below.

// Retrieve the latest post from the RSS feed

require_once 'rss-php/src/Feed.php';

$rss = Feed::loadRss($feed_url);
$post = htmlSpecialChars($rss->item->title) . " " . htmlSpecialChars($rss->item->link);

echo $post;

// Read the last post content from the local file storage.txt

$readstorage = fopen("storage.txt", "r") or die("Unable to open file!");
$lastpost = fread($readstorage,filesize("storage.txt"));
echo "<br>Last post: " . $lastpost;
fclose($readstorage);

if ($lastpost == $post) {
  
  echo "<br>Last post: Post not changed";
  
} else {

    // Read the last count from the local file count.txt

    $readcount = fopen("count.txt", "r") or die("Unable to open file!");
    $lastcount = fread($readcount,filesize("count.txt"));
    echo "<br>Last count: " . $lastcount;
    fclose($readcount);
    $newcount=$lastcount+1;
    echo "<br>New count: " . $newcount;

    // Write the new count to the local file count.txt

    $writecount = fopen("count.txt", "w") or die("Unable to open file!");
    fwrite($writecount, $newcount);
    fclose($writecount);

    // Set visibility based on count

    $count_factor=$newcount/$public_frequency;
    if(is_int($count_factor)){
            echo "<br>Visibility: " . $visibility ;
      }else{
            $visibility="unlisted"; 
            echo "<br>Visibility: " . $visibility ;
           }

    // Write the new post content to the local file storage.txt

    $writestorage = fopen("storage.txt", "w") or die("Unable to open file!");
    fwrite($writestorage, $post);
    fclose($writestorage);

    // Post to Mastodon

    $headers = [
      'Authorization: Bearer ' . $token
    ];

    $status_data = array(
      "status" => $post,
      "language" => $language,
      "visibility" => $visibility
    );

    $ch_status = curl_init();
    curl_setopt($ch_status, CURLOPT_URL, $base_url . "/api/v1/statuses");
    curl_setopt($ch_status, CURLOPT_POST, 1);
    curl_setopt($ch_status, CURLOPT_POSTFIELDS, $status_data);
    curl_setopt($ch_status, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_status, CURLOPT_HTTPHEADER, $headers);
    $output_status = json_decode(curl_exec($ch_status));
    curl_close ($ch_status);
}

?>