<?
    /*
        menu
    */

?><div id="menu-btn" class="btn"><div class='dot blue'></div><div class='dot red'></div></div>
<div id='menu'>
    <ul>
        <ul class="nav-level"><?
            $prevd = $nav[0]['depth'];
            foreach($nav as $n) {
                $d = $n['depth'];
                if($d > $prevd) {
                    ?><ul class="nav-level"><?
                } else {
                    for ($i = 0; $i < $prevd - $d; $i++) {
                        ?></ul><?
                    }
                }
                if(substr($n['url'], 0, 5) == 'apply')
                {
                    if(strpos($n['url'], '/') !== false )
                    {
                        $temp = explode('/', $n['url']);
                        $n['url'] = $temp[0] . '#' . $temp[1];
                        ?><li>
                            <span id="<?= $temp[1]; ?>-btn" class="apply-btn dummy-link" onclick="jumpTo('<?= $temp[1]; ?>')"><?= $n['o']['name1']; ?></span>
                            <span><?= $n['o']['name1']; ?></span>
                        </li><?
                    }
                    else
                    {
                        ?><li>
                            <a id="apply-btn" class="apply-btn " href="<? echo $host.$n['url']; ?>"><?= $n['o']['name1']; ?></a>
                            <span><?= $n['o']['name1']; ?></span>
                        </li><?
                    }
                }
                else
                {
                    ?><li><?
                        if($n['o']['id'] != $uu->id) {
                            ?><a href="<? echo $host.$n['url']; ?>"><?= $n['o']['name1']; ?></a><?
                        } else {
                            ?><span><?= $n['o']['name1']; ?></span><?
                        }
                    ?></li><?
                }
                $prevd = $d;
            }
        ?></ul>
    </ul>
</div>
<script>
    var sMenu_btn = document.getElementById('menu-btn');
    if(sMenu_btn)
    {
        sMenu_btn.addEventListener('click', function(){
            document.body.classList.toggle('viewing-menu');
        });
    }
</script>
