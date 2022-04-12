// lang
let searchParams = new URLSearchParams(window.location.search);
var lang = searchParams.get('lang') !== null ? searchParams.get('lang') : 'es';
var filenames_all = {
	'es':[
		'Verano 2022',
		'The Rise of the Coyote',
		'Xochimilco y Milpa Alta',
		'Aplicación',
		'Calendario',
		'Contacto',
		'Costo',
		'Créditos',
		'Docentes',
		'Participantes',
		'Resumen curatorial',
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
