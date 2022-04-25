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
$body_class .= ' ' . $lang;
if($isHome)
	$body_class .= ' home';
else
    $body_class .= ' subpage';


$site = 'Materia Abierta';

?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $site; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="description" content="Materia Abierta es una escuela de verano sobre teoría, arte y tecnología establecida en la Ciudad de México.">
        <meta name="og:image" content="/media/jpg/og.jpg" />
        <meta name="og:type" content="website" />
        <meta name="og:title" content="Materia Abierta 2022" />
        <meta name="og:url" content="https://materiaabierta.com" />
        <meta name="og:description" content="Materia Abierta es una escuela de verano sobre teoría, arte y tecnología establecida en la Ciudad de México." />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="Materia Abierta 2022" />
        <meta name="twitter:site" content="materiaabierta.com" />
        <meta name="twitter:description" content="Materia Abierta es una escuela de verano sobre teoría, arte y tecnología establecida en la Ciudad de México." />
        <meta name="twitter:image" content="https://2022.materiaabierta.com/media/jpg/og.jpg" />
		<link rel="stylesheet" href="/static/css/main.css">
		<script src="/static/pde/processing-1.4.1.min.js"></script>
		<!-- <script src="/static/js/ui.js"></script> -->
		<!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138624239-1"></script>
        <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'UA-138624239-1');
        </script>
        <!-- <base target="_blank"> -->
	</head>
	<body <?= !empty($body_class) ? 'class="' . $body_class . '"' : ''; ?> loadingStage="0">
