<?
// open-records-generator
require_once('open-records-generator/config/config.php');
require_once('open-records-generator/config/url.php');

// site
// require_once('static/php/config.php');

$db = db_connect("guest");
$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();


$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$page = isset($_GET['page']) ? urldecode($_GET['page']) : 'home';
$isHome = $page === 'home';

if($isHome){
	$temp = $oo->urls_to_ids(array('home'));
	$id = end($temp);
} else {
	$temp = $oo->urls_to_ids(array($lang, slug($page)));
	$id = end($temp);
}
try{
	$item = $oo->get($id);
} catch(Exception $err) {
	$item = $oo->get(0);
}


if(isset($item))
	$name = ltrim(strip_tags($item["name1"]), ".");
else 
	$name = '';



$isTest = isset($_GET['test']);

$body_class = 'loading loading-player-server';

if($isHome)
	$body_class .= ' home';
else
    $body_class .= ' subpage';

?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $site; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="/static/css/main.css">
		<script src="/static/pde/processing-1.4.1.min.js"></script>
		<!-- <script src="/static/js/ui.js"></script> -->
	</head>
	<body <?= !empty($body_class) ? 'class="' . $body_class . '"' : ''; ?> loadingStage="0">