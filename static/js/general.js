// lang
let searchParams = new URLSearchParams(window.location.search);
var lang = searchParams.get('lang') !== null ? searchParams.get('lang') : 'es';
console.log(lang);
var filenames_all = {
	'es':[
		'convocatoria 2022',
		'la rebelión del coyote',
		'milpa alta y xochimilco',
		'resumen curatorial',
		'programa',
		'docentes',
		'participantes',
		'costo',
		'calendario',
		'aplicación',
		'créditos',
		'contacto',
		'semblanzas'
	],
	'en':[
		'open call 2022',
		'the rise of the coyote',
		'milpa alta and xochimilco',
		'curatorial text',
		'program',
		'tutors',
		'participants',
		'cost',
		'calendar',
		'application',
		'credits',
		'contact',
		'bios'
	]
};
var filenames = filenames_all[lang];

// menu
function toggleMenu(){
	document.body.classList.toggle('viewing-menu');
}
var sMenu = document.getElementById('menu');
if(sMenu)
{
	filenames.forEach((el, i) => {
		let menuItem = document.createElement('DIV');
		menuItem.classList = 'menu-item sans';
		let menuItemLink = document.createElement('A');
		menuItemLink.innerText = el;
		menuItemLink.href = '/' + lang + '/' + el + '.html';
		menuItem.appendChild(menuItemLink);
		sMenu.appendChild(menuItem);
	});
}
else console.log('#menu not found');
