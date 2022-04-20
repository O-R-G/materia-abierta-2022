<?
require_once('../../open-records-generator/config/config.php');
require_once('../../open-records-generator/config/url.php');
require_once('../../open-records-generator/lib/lib.php');

$db = db_connect("guest");
$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();

$data = json_decode(file_get_contents('php://input'), true);
$page = $data['page'];
$lang = $data['lang'];

$content = '';
if($page != 'home')
{
	$urls = array($lang, slug($page));
	$ids = $oo->urls_to_ids($urls);
	$id = end($ids);
	$content = '<div id="content">' . $oo->get($id)['body'] . '</div>';
    if( !empty( trim( $item['notes'] )))
        $content .= $item['notes'];
	
}
else
{
	$content_main = '<span id="content">';
    $content_intro = '<div id="intro">';
    $content_outro = '<div id="outro">';
	$temp = $oo->urls_to_ids( array('home') );
	$item = $oo->get( end($temp) );
    $temp = $oo->urls_to_ids( array($lang) );
    $children = $oo->children( end($temp) );

    // $menu_items = array();
    foreach($children as $child)
    {
        if(substr($child['name1'], 0, 1) !== '.')
        {
            if(substr($child['name1'], 0, 1) !== '_'){
                // $menu_items[] = $child;
                $content_main .= '<a class="section-title" onclick="requestPage(\''.$child['name1'].'\')">' . $child['name1'] . '</a> ' . $child['deck'] . ' ';
            }
            else if($child['url'] == 'intro')
                $content_intro .= $child['deck'] . '</div> ';
            else if($child['url'] == 'outro')
                $content_outro .= $child['deck'] . '</div> ';
        }
    }
    $content = $content_intro . $content_main . '</span>' . $content_outro;
    $logos = trim($item['notes']);
    if(!empty($logos))
        $content .= '<div id="institutions-logos-container">'.$logos.'</div>';
}

echo $content;
?>