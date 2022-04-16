function makeMenu(container, itemNames){
	itemNames.forEach((el, i) => {
		let menuItem = document.createElement('DIV');
		menuItem.classList = 'menu-item sans';
		let menuItemLink = document.createElement('A');
		menuItemLink.innerText = el;
		menuItemLink.addEventListener('click', function(){
			requestPage();
		});
		menuItem.appendChild(menuItemLink);
		container.appendChild(menuItem);
	});
}