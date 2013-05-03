//WAIT FOR PAGE TO LOAD
jQuery(document).ready(function($)
{	
	//INTIALISES JQUERY TINYNAV
   	jQuery("ul.sf-menu").tinyNav(
	{
		header: 'Menu'
	});
	
	//INITILIASE HOME OPTION TEXT
	jQuery('select.tinynav option[value="/"]').text('Home');
});
