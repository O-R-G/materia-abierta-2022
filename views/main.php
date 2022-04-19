<?
// die();
    $content = '';
    if($isHome)
    {
        $content_main = '<span id="content">';
        $content_intro = '<div id="intro">';
        $content_outro = '<div id="outro">';
        $temp = $oo->urls_to_ids( array($lang) );
        $children = $oo->children( end($temp) );

        $menu_items = array();
        foreach($children as $child)
        {
            if(substr($child['name1'], 0, 1) !== '.')
            {
                if(substr($child['name1'], 0, 1) !== '_'){
                    $menu_items[] = $child;
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
        $opencall_links = trim($item['body']);
    }
    else
    {
        $content .= '<span id="content">' . $item['body'] . '</span>';
        $temp = $oo->urls_to_ids( array($lang) );
        $children = $oo->children( end($temp) );
        $menu_items = array();
        foreach($children as $child)
        {
            if(substr($child['name1'], 0, 1) !== '.' && substr($child['name1'], 0, 1) !== '_')
                $menu_items[] = $child;
        }
    }
    
?>
<header>
    <a id="title" onclick="requestPage('home')">Materia Abierta</a> / <span id="weather" class="sans"></span>
</header><br><br>
<div id="live-background">
    <div id="player-sky"></div>
</div>
<main id='content-container' class="transition"><?= $content; ?></main>
<div id="geolocation" class="sans">Connecting . . .</div>
<div id="lang-toggle" class="sans"><a class="<?= $lang == 'en' ? 'active' : ''; ?>" href="/?lang=en">EN</a> / <a class="<?= $lang == 'es' ? 'active' : ''; ?>" href="/?lang=es">ES</a></div>
<div id='clock'><canvas class='clock' datasrc="/static/pde/clock.pde"
    width="200" height="200" tabindex="0"
    style="image-rendering: optimizeQuality !important;">
</canvas></div>
<div id="menu-container">
    <div id="menu-btn" onclick="toggleMenu()" class="sans">MENU</div>
    <div id="menu">
        <? foreach($menu_items as $item){
            ?><div class="menu-item sans"><a onclick="requestPage('<?= $item['name1']; ?>')"><?= $item['name1']; ?></a></div><?
        } ?>
    </div>
</div>
<div id="past-websites-container">
    <a href="https://2019.materiaabierta.com" class="past-website sans">2019</a> <a href="https://2020.materiaabierta.com" class="past-website sans">2020</a> <a href="https://2021.materiaabierta.com" class="past-website sans">2021</a>
</div>
<div id="opencall-container" class="sans">
    <?= $opencall_links; ?>
</div>
<script src="/static/js/weather.js"></script>
<script>
    var searchParams = new URLSearchParams(window.location.search);
    var lang = '<?= $lang; ?>';
    var page = '<?= $page; ?>';
    var isTest = <?= json_encode($isTest); ?>;
    var body = document.body;

    var filenames_all = {
        'en':[
            'Open Call 2022',
            'The Rise of the Coyote',
            'Milpa Alta and Xochimilco',
            'Curatorial text',
            'Program',
            'Tutors',
            'Participants',
            'Cost',
            'Calendar',
            'Application',
            'Credits',
            'Contact',
            'Bios'
        ],
        'es':[
            'Convocatoria 2022',
            'La rebelión del coyote',
            'Milpa Alta y Xochimilco',
            'Resumen curatorial',
            'Programa',
            'Docentes',
            'Participantes',
            'Costo',
            'Calendario',
            'Aplicación',
            'Créditos',
            'Contacto',
            'Semblanzas'
        ]
    };
    var filenames = filenames_all[lang];

    // menu
    function toggleMenu(){
       body.classList.toggle('viewing-menu');
    }
    var sMenu = document.getElementById('menu');

    // support of xmlhttprequest
    var bActiveX;
    try {
      new ActiveXObject('Microsoft.XMLHTTP');
      bActiveX = true;
    }
    catch(e) {
      bActiveX = false;
    }

    var sContent_container = document.getElementById('content-container');
    var sContent = document.getElementById('content');
    var sBackground = document.getElementById('background');
    var sWeather = document.getElementById('weather');
    var sGeolocation = document.getElementById('geolocation');

    var homeContent_full = '';
    var homeContent_sliced = {};
    var subpageContent = {};
    var string_geolocation = '';
    
    var match = [];

    var fadeInDuration = 500;
    var stageDelay = 1000;
    var typingInterval = isTest ? 10 : 100;
    var typewriterIntervalObj = null;
    if(window.innerWidth > 500)
        var sizer = 220;
    else
        var sizer = 140;

    var loadedCount = 0;
    var request_content = [];

    // geolocation
    function geoSuccess(position){
        // console.log('geoSuccess');
        let latitude  = position.coords.latitude;
        let longitude = position.coords.longitude;
        string_geolocation = latitude + ', '+longitude;
        console.log(string_geolocation);
    }
    function geoError(err){
        // console.log('geoError');
        // console.log(err);
        sGeolocation.innerText = '';
    }
    function initGeo(){
        if(!navigator.geolocation) {
            // console.log('no geolocation api');
            sGeolocation.innerText = '';
        } else {
            // console.log('locating . . .');
            navigator.geolocation.getCurrentPosition(geoSuccess, geoError);
        }
    }
    sGeolocation.addEventListener('click', function(){
        initGeo();
    });
    

    // request page
    var requestPage_url = '/static/php/requestPage.php';
    function requestPage(target="home"){

        if (window.XMLHttpRequest || bActiveX) { // IE7+, FF and Chrome

            let target_lowercased = target.toLowerCase();
            page = target_lowercased;
            var pathUrl = '/?lang='+lang+'&page='+page;
            // window.history.pushState({"html":homeContent_full, "lang":lang, "page":target},"", '/?lang='+lang);
            // window.history.pushState({"html":request_page.responseText, "lang":lang, "page":page},"", pathUrl);
            var request_page = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            request_page.onreadystatechange = function(){
                if (request_page.readyState === 4) {
                    if (request_page.status === 200) { 
                        try{
                            if(request_page.responseText)
                            {
                                sContent_container.classList.add('transition');
                                sContent_container.innerHTML = request_page.responseText;
                                // console.log(request_page.responseText);
                                window.history.pushState({"html":request_page.responseText, "lang":lang, "page":page},"", pathUrl);
                                setTimeout(function(){
                                    sContent_container.classList.remove('transition');
                                    if( !body.classList.contains('loading') )
                                        typewriter(target, sWeather, typingInterval);
                                    if(page == 'home')
                                        body.classList.remove('subpage');
                                    else
                                        body.classList.add('subpage');
                                    body.classList.remove('viewing-menu')

                                }, 0);
                            }
                        }
                        catch(err){
                            console.log(err);
                        }
                    }
                    // else console.log('request_page.status !== 200');
                }
                // else console.log('request_page.readyState !== 4');
            };
            let request_page_body = {"page":page, "lang":lang};
            request_page.open('POST', requestPage_url);
            request_page.send(JSON.stringify(request_page_body));
            
        }
    }
    function nextStage(){
        let currentStage = parseInt(document.body.getAttribute('loadingStage'));
        if(currentStage == 0){
            document.body.setAttribute('loadingStage', currentStage +1);
            if(page === 'home')
                typewriter(string_weather, sWeather, typingInterval, nextStage);
            else
                typewriter(page, sWeather, typingInterval, nextStage);
        }
        else
        {
            setTimeout(function(){
                if(currentStage == 1)
                {
                    document.body.setAttribute('loadingStage', currentStage +1);
                    setTimeout(function(){
                        typewriter(string_geolocation, sGeolocation, typingInterval, nextStage);
                    }, fadeInDuration);
                }
                else if(currentStage == 2)
                {
                    sContent_container.classList.remove('transition');
                    document.body.setAttribute('loadingStage', currentStage +1);
                }
            }, stageDelay);     
        }
    }
    function typewriter(content, target, interval=100, callback=false){
        if(content.length != 0){
            if(typewriterIntervalObj != null)
            {
                clearInterval(typewriterIntervalObj);
                typewriterIntervalObj = null;
            }
            let content_arr = content.split('');
            let index = 0;
            target.innerHTML = '';
            typewriterIntervalObj = setInterval(function(){
                if(content_arr[index] == ' ')
                    target.innerHTML += '&nbsp;';
                else
                    target.innerHTML += content_arr[index];
                index++;
                if(index == content_arr.length){
                    clearInterval(typewriterIntervalObj);
                    if(callback)
                        callback();
                }
            }, interval);
        }
        else{
            target.innerHTML = '';
            if(callback)
                callback();
        }
    }

    // for ajax previous pages
    window.addEventListener('popstate', function(e){
        console.log('onpopstate');
        // console.log(e);
        if(e.state){
            sContent = document.getElementById('content');
            sContent.innerHTML = e.state.html;
            lang = e.state.lang;
            page = e.state.page;
            if(page !== 'home'){
                body.classList.add('subpage');
                typewriter(page, sWeather, typingInterval);
            }
            else{
                body.classList.remove('subpage');
                typewriter(string_weather, sWeather, typingInterval);
            }
        }
    });
    var r = document.querySelector(':root');
    request_weather.send();

</script>
<script src="/static/js/liveStream.js"></script>