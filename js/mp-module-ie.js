//MAVERICK PORTFOLIO INTERNET EXPLORER 8 MODULE
var mp_module_ie = function()
{
	//IFRAMES
	var iframes =
	{
		//THIS METHOD SETS IFRAMES TO TRANSPARENT
		init : function()
		{
			//INITIALISE IFRAME ARRAY
			var iframes = document.getElementsByTagName('iframe');
			
			//SET IFRAME TO TRANSPARENT
			for(var iframe_counter = 0, max_iframe_counter = iframes.length; iframe_counter < max_iframe_counter; iframe_counter ++)
			{
				iframes[iframe_counter].setAttribute('allowTransparency', 'true');
			}
		}
	}
		
	//PUBLIC METHODS
	return{
		
		//LAUNCH ALL THE FOLLOWING METHODS AT PAGE LOAD
		run_at_load : function()
		{
			iframes.init();
		}
	};
}();

//RUN MAVERICK PORTFOLIO INTERNET EXPLORER 8 MODULE AT PAGE LOAD
$(document).ready(function()
{
	mp_module_ie.run_at_load();
});