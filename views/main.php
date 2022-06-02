<?
    $content = '';
    $link_pattern = '/(\<a\s.*?href\s*?=\s*?[\'"](.*?)[\'"].*?)\>/';
    $liveStreamId_pattern = '/\[liveStreamId\]\((.*)\)/';
    
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
        $bottom_links = trim($item['body']);
        
        preg_match($liveStreamId_pattern, $item['deck'], $temp);
        if(!empty($temp) && !empty($temp[0]))
            $liveStreamId = $temp[1];
    }
    else
    {
        $content .= '<div id="content">' . $item['body'] . '</div>';
        if( !empty( trim( $item['notes'] )))
            $content .= $item['notes'];
        
        $temp = $oo->urls_to_ids( array($lang) );
        $children = $oo->children( end($temp) );
        $menu_items = array();
        foreach($children as $child)
        {
            if(substr($child['name1'], 0, 1) !== '.' && substr($child['name1'], 0, 1) !== '_')
                $menu_items[] = $child;
        }

        $temp = $oo->urls_to_ids(array('home'));
        $home_item = $oo->get(end($temp));
        preg_match($liveStreamId_pattern, $home_item['deck'], $temp);
        if(!empty($temp) && !empty($temp[0]))
            $liveStreamId = $temp[1];
        $bottom_links = trim($home_item['body']);
    }

    // adding _blank to external links
    preg_match_all($link_pattern, $content, $temp);
    if( !empty($temp) ) {
        foreach($temp[2] as $key => $url) {   
            if(strpos($url, '2022.materiabierta.com') === false && substr($url, 0, 1) !== '/' && strpos($temp[1][$key], '_blank') === false ) {
                $external_tag = $temp[1][$key] . ' target="_blank" >';
                $content = str_replace($temp[0][$key], $external_tag, $content);
            }
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
<div id="geolocation" class="sans"><span class="en">Connecting . . .</span><span class="es">Cargando . . .</span></div>
<div id="lang-toggle" class="sans"><a class="<?= $lang == 'en' ? 'active' : ''; ?>" href="/?lang=en">EN</a> / <a class="<?= $lang == 'es' ? 'active' : ''; ?>" href="/?lang=es">ES</a></div>
<div id='clock'><canvas class='clock' datasrc="/static/pde/clock.pde"
    width="200" height="200" tabindex="0"
    style="image-rendering: optimizeQuality !important;">
</canvas></div>
<div id="menu-container">
    <div id="menu-btn" onclick="toggleMenu()" class="sans"><span class="en">MENU</span><span class="es">MENÚ</span></div>
    <div id="menu">
        <? foreach($menu_items as $item){
            ?><div class="menu-item sans"><a onclick="requestPage('<?= $item['name1']; ?>')"><?= $item['name1']; ?></a></div><?
        } ?>
    </div>
</div>
<div id="past-websites-container">
    <a href="https://2019.materiaabierta.com" class="past-website sans" target="_blank">2019</a> <a href="https://2020.materiaabierta.com" class="past-website sans" target="_blank">2020</a> <a href="https://2021.materiaabierta.com" class="past-website sans" target="_blank">2021</a>
</div>
<div id="bottom-fixed-container" class="sans">
    <?= $bottom_links; ?>
</div>
<script>
    var lang = '<?= $lang; ?>';
    var page = '<?= $page; ?>';
    var isTest = <?= json_encode($isTest); ?>;
    var body = document.body;
    var r = document.querySelector(':root');
    var latitude, longitude;
    var weather_isReady = false;
    var liveStream_isReady = false;
    var liveStreamId = '<?= isset($liveStreamId) ? $liveStreamId : false; ?>';
    var camera_coordinate = [19.4052191,-99.1833943];
    var showDistance = <?= json_encode($showDistance); ?>;
    var distance = false;
    var string_outro = '';
</script>
<script src="/static/js/weather.js"></script>
<script>
    // geolocation
    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2-lat1);  // deg2rad below
        var dLon = deg2rad(lon2-lon1); 
        var a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
        Math.sin(dLon/2) * Math.sin(dLon/2)
        ; 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        var d = R * c; // Distance in km
        return d;
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180)
    }
    function geoSuccess(position){
        // console.log('geoSuccess');
        latitude  = position.coords.latitude;
        longitude = position.coords.longitude;
        console.log(camera_coordinate);
        console.log(latitude, longitude);
        distance = Math.round(100 * getDistanceFromLatLonInKm(latitude, longitude, camera_coordinate[0], camera_coordinate[1])) / 100;
        if(lang == 'en')
            string_outro = '<div><i>The background of this web page is a live feed of the sky right above Materia Abierta’s offices approximately 45 kilometers from </i><i><i><a href="https://goo.gl/maps/PiwqMPf9Edfyp7Zm9">Milpa Alta</a></i>, Mexico, and '+distance+' kilometers from where *you* are.</i></div>';
        else
            string_outro = '<div><i>El fondo de esta página web es una transmisión en vivo del cielo justo arriba de las oficinas de Materia Abierta aproximadamente a 45 kilómetros de </i><i><a href="https://goo.gl/maps/PiwqMPf9Edfyp7Zm9">Milpa Alta</a>, México, y a '+distance+' kilómetros de donde *tú* estás.</i></div>';
        var sOutro = document.getElementById('outro');
        if(sOutro) sOutro.innerHTML = string_outro;
    }
    function geoError(err){
        console.log('geoError');
        console.log(err);
        // sGeolocation.innerText = '';
    }
    function initGeo(){
        console.log('initGeo');
        if(!navigator.geolocation) {
            console.log('no geolocation api');
            // sGeolocation.innerText = '';
        } else {
            // console.log('locating . . .');
            navigator.geolocation.getCurrentPosition(geoSuccess, geoError);
        }
    }
    // if(showDistance)
    initGeo();
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
    var string_geolocation = '19.4052191, -99.1833943';
    
    var match = [];

    var fadeInDuration = 500;
    var stageDelay = 500;
    var typingInterval = isTest ? 10 : 50;
    var typewriterIntervalObj = null;
    if(window.innerWidth > 500)
        var sizer = 220;
    else
        var sizer = 140;

    var loadedCount = 0;
    var request_content = [];

    // request page
    var requestPage_url = '/static/php/requestPage.php';
    function requestPage(target="home", hash=''){
        if (window.XMLHttpRequest || bActiveX) { // IE7+, FF and Chrome
            if(body.getAttribute('loadingstage') == 3)
            {
                let target_lowercased = target.toLowerCase();
                page = target_lowercased;
                var pathUrl = '/?lang='+lang+'&page='+page;
                var request_page = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                request_page.onreadystatechange = function(){
                    if (request_page.readyState === 4) {
                        if (request_page.status === 200) { 
                            try{
                                if(request_page.responseText)
                                {
                                    sContent_container.classList.add('transition');
                                    sContent_container.innerHTML = request_page.responseText;
                                    window.history.pushState({"html":request_page.responseText, "lang":lang, "page":page},"", pathUrl);
                                    setTimeout(function(){
                                        sContent_container.classList.remove('transition');
                                        
                                        if(page == 'home'){
                                            if(string_outro !== '')
                                                document.getElementById('outro').innerHTML = string_outro;
                                            if( !body.classList.contains('loading') )
                                                typewriter(string_weather, sWeather, typingInterval);
                                            body.classList.remove('subpage');
                                        }
                                        else{
                                            if( !body.classList.contains('loading') )
                                                typewriter(target, sWeather, typingInterval);
                                            body.classList.add('subpage');
                                        }
                                        body.classList.remove('viewing-menu')
                                        if(hash !== '')
                                            location.hash = "#" + hash;
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
    }
    function nextStage(){
        let currentStage = parseInt(document.body.getAttribute('loadingStage'));
        if(currentStage == 0) {
            document.body.setAttribute('loadingStage', currentStage +1);
            if(page === 'home')
                typewriter(string_weather, sWeather, typingInterval, nextStage);
            else
                typewriter(page, sWeather, typingInterval, nextStage);
        } else {
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
    request_weather.send();

</script>
<script src="/static/js/liveStream.js"></script>