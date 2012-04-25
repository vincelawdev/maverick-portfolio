//WAIT FOR PAGE TO LOAD
$(document).ready(function() 
{
	//INITIALISE IFRAME ARRAY
	iframes = document.getElementsByTagName("iframe");
	
	//SET IFRAME TO TRANSPARENT
	for(counter = 0; counter < iframes.length; counter ++)
	{
		iframes[counter].setAttribute("allowTransparency", "true");
	}
});