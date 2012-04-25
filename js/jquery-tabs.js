//WAIT FOR PAGE TO LOAD
$(document).ready(function()
{
	//HIDE ALL TAB CONTENTS
	$(".tab_content").hide();
	
	//DISPLAY 1ST POST TABS & 1ST COMMENT TABS
	$("ul.post_tabs li:first").addClass("active").show();
	$("ul.comment_tabs li:first").addClass("active").show();
	
	//DISPLAY 1ST POST TAB & 1ST COMMENT TAB CONTENTS
	$("#tab1").show();
	$("#tab4").show();
	
	//POST TABS
	$("ul.post_tabs li").click(function()
	{
		//REMOVE ALL ACTIVE CLASSES FROM ALL TABS
		$("ul.post_tabs li").removeClass("active");
		
		//ADD ACTIVE CLASS TO SELECTED TAB
		$(this).addClass("active");
		
		//HIDE TAB CONTENTS
		$("#tab1").hide();
		$("#tab2").hide();
		$("#tab3").hide();
		
		//INITIALISE THE ACTIVE TAB + CONTENTS
		activeTab = $(this).find("a").attr("rel");
		
		//FADE IN TAB CONTENTS
		$(activeTab).fadeIn();
		
		return false;
	});
	
	//COMMENT TABS
	$("ul.comment_tabs li").click(function()
	{
		//REMOVE ALL ACTIVE CLASSES FROM ALL TABS
		$("ul.comment_tabs li").removeClass("active");
		
		//ADD ACTIVE CLASS TO SELECTED TAB
		$(this).addClass("active");
		
		//HIDE TAB CONTENTS
		$("#tab4").hide();
		$("#tab5").hide();
		
		//INITIALISE THE ACTIVE TAB + CONTENTS
		activeTab = $(this).find("a").attr("rel");
		
		//FADE IN TAB CONTENTS
		$(activeTab).fadeIn(); 
		
		return false;
	});
});