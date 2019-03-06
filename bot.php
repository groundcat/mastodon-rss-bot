<?php

/**

PLEASE FOLLOW THE STEPS IN README.MD

**/

// Mastodon and RSS feed configuration:

$token="YOUR_TOKEN_HERE"; // Token of your Mastodon bot account
$base_url="https://example.com"; // URL of your instance (Do not include '/' at the end.)
$feed_url="https://example.com/feed.xml"; // URL of RSS or Atom feed
$visibility="public"; // The four tiers of visibility for toots are Public (default), Unlisted, Private, and Direct
$language="en"; // en for English, zh for Chinese, etc.

// End of configuration. You don't need to edit anything below.

// Retrieve the latest post from the RSS feed

header('Content-Type: text/html; charset=utf-8');
if (!ini_get('date.timezone')) {
	date_default_timezone_set('Europe/Prague');
}
require_once 'src/Feed.php';

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