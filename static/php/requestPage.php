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

$name = '';
$content = '';

function trim_prefixes($page_name) {
    $page_name = ltrim($page_name, '.');
    $page_name = ltrim($page_name, '-');
    $page_name = ltrim($page_name, '~');
    $page_name = ltrim($page_name, '+');
    $page_name = ltrim($page_name, '>');
    $page_name = ltrim($page_name, '!');
    return $page_name;
}

if($page != 'home')
{
	$urls = array($lang, slug($page));
	$ids = $oo->urls_to_ids($urls);
	$id = end($ids);
    $item = $oo->get($id);
	$content = '<div id="content">' . $item['body'] . '</div>';
    if( !empty( trim( $item['notes'] )))
        $content .= $item['notes'];
    $name = $item['name1'];
	
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
    $name = 'home';
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

// adding _blank to external links
$link_pattern = '/(\<a\s.*?href\s*?=\s*?[\'"](.*?)[\'"].*?)\>/';
preg_match_all($link_pattern, $content, $temp);
if( !empty($temp) )
{
    foreach($temp[2] as $key => $url)
    {   
        if(strpos($url, '2022.materiabierta.com') === false && substr($url, 0, 1) !== '/' && strpos($temp[1][$key], '_blank') === false )
        {
            $external_tag = $temp[1][$key] . ' target="_blank" >';
            $content = str_replace($temp[0][$key], $external_tag, $content);
        }
    }
}
$output = array(
    'name' => trim_prefixes($name),
    'body' => $content
);

echo json_encode($output, true);
?>