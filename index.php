<?php

if ($_SERVER['REQUEST_URI'] == "/") {
	$page['name'] = "homepage";
} else {
	$page['name'] = substr($_SERVER['REQUEST_URI'],1);
}

$page['name'] = str_replace(".","",$page['name']);

// see if the page exists!
if (!is_dir($page['name'])) {
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	$page['name'] = "404";
}


// $page['site_css'] = file_get_contents("site_css");
$page['site_js'] = file_get_contents("site.js");
$page['site_css'] = file_get_contents("site.css");
$page['site_nav'] = file_get_contents("site_nav");


$pageSections = array(
	"title",
	"body"
	);
foreach ($pageSections as $pageSection) {
	$page[$pageSection] = file_get_contents($page['name']."/".$pageSection);
}



?><!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=$page['title']?></title>
<style><?=$page['site_css']?></style>
<script><?=$page['site_js']?></script>
</head>
<body>
<?=$page['site_nav']?>
<?php
if (is_file($page['name']."/is_post_category")) {

	?><h1 class="entry-title">Posts in <?=$page['name']?></h1>
	<div class="entry-content"><?
	// find folders and give them as links
	$dh  = opendir($page['name']);
	$posts = array();
	while (false !== ($filename = readdir($dh))) {

		if ($filename[0] != "." && is_dir($page['name']."/".$filename)) {
	    	$posts[] = $filename;
	    }
	}
	sort($posts);

	foreach($posts as $post) {
		$postTitle = file_get_contents($page['name']."/".$post."/title");
		?><p><a href="<?=$page['name']?>/<?=$post?>"><?=$postTitle?></a></p><?php
	}

} else  if (is_file($page['name']."/is_post")) {

	$page['body'] = str_replace("\n","<br/>\n",$page['body']);

	?><h1 class="entry-title"><?=$page['title']?></h1>
	<div class="entry-content"><?
	print $page['body'];

} else {
	?><div class="entry-content"><?
	print $page['body'];
}

?>
</body>
</html>