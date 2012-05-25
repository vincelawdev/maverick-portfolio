//WAIT FOR PAGE TO LOAD
$(document).ready(function()
{
	//INITIALISE THE CONTENT & SIDEBAR HEIGHTS
	content_height = $('#content').height();
	sidebar_height = $('#sidebar').height();
	
	//INITIALISE THE MAXIMUM HEIGHT
	max_height = Math.max(content_height, sidebar_height);
	
	//CONTENT HEIGHT IS SHORTER THAN SIDEBAR HEIGHT
	if(content_height < sidebar_height)
	{
		//SET THE CONTENT HEIGHT TO THE MAXIMUM HEIGHT
		$('#content').height(max_height);
	}
});