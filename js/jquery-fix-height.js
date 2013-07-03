//WAIT FOR PAGE TO LOAD
$(window).load(function()
{	
	//INITIALISE THE CONTENT & SIDEBAR HEIGHTS
	content_height = $('#content').outerHeight(true);
	sidebar_height = $('#sidebar').height();
	
	//INITIALISE THE MAXIMUM HEIGHT
	max_height = Math.max(content_height, sidebar_height);
	
	/*
	console.log('Content: ' + content_height);
	console.log('Sidebar: ' + sidebar_height);
	console.log('Max: ' + max_height);
	*/
	
	//CONTENT HEIGHT IS SHORTER THAN SIDEBAR HEIGHT
	if(content_height < sidebar_height)
	{
		//SET THE CONTENT HEIGHT TO THE MAXIMUM HEIGHT
		$('#content').height(max_height);
	}
});