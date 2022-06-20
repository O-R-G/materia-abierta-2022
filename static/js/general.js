// lang
console.log('general.js');
let searchParams = new URLSearchParams(window.location.search);
var lang = searchParams.get('lang') !== null ? searchParams.get('lang') : 'es';
var page = searchParams.get('page') !== null ? searchParams.get('page') : 'home';
var isTest = searchParams.get('test') !== null ?;

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

var bActiveX;

try {
  new ActiveXObject('Microsoft.XMLHTTP');
  bActiveX = true;
}
catch(e) {
  bActiveX = false;
}
