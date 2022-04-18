<?
$ids = $oo->urls_to_ids(array('home'));
$id = end($ids);
$items = $oo->children($id);
// $shuffled = array_splice($items, 2, count($items) - 2);
// shuffle($shuffled);
// array_splice($items, 2, 0, $shuffled);

?><main id='main'>
    <section id="home" class="main-container"><?
        foreach($items as $item) {
            if(substr($item['name1'], 0, 1) != '.' && substr($item['name1'], 0, 1) != '_') {
                $deck = isset($item['deck']) ? $item['deck'] : '';
                $class = "home-block";
                $class .= isset($item['notes']) ? ' ' . trim($item['notes']) : '';
                $style = NULL;
                if ($media = $oo->media($item['id'])) {
                    $m = $media[0];
                    $m_path = '../../media/' . m_pad($m['id']) . '.' . $m['type'];
                    $style = 'background-image: url(' . $m_path . ');';
                }
                if ($item['url'] == 'supports' || $item['url'] == 'weather') 
                    $url = '##';
                else
                    $url = '/' . $item['url'];
                ?><a class='<?= $class; ?>' style='<?= $style; ?>' href='<?= $url; ?>'>
                    <div class="square-wrapper">
                        <div><?= $deck; ?></div>
                    </div>
                </a><?
            }
        }
        require_once('views/weather.php');
        require_once('views/clock.php');
        require_once('views/logo.php');
    ?>
    </section>
</main>
<div id='speak'><?=  $name . '. '.$body; ?></div>
