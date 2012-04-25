//WAIT FOR PAGE TO LOAD
$(document).ready(function()
{
	//INITIALISE THE MAXIMUM HEIGHT
	max_height = Math.max($("#content").height(), $("#sidebar").height());
	
	//SET THE CONTENT HEIGHT TO THE MAXIMUM HEIGHT
	$("#content").height(max_height);
});
