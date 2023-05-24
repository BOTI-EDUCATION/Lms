if ($(".badge-user-right").length)  {
	
	var ELEMENT = $(".badge-user-right");

	//get element width and height
	var w = ELEMENT.width()+ 2;
	var h = ELEMENT.height()+ 2;
	console.log(h);

	//run html2canvas
	html2canvas(ELEMENT, {
	onrendered: function(canvas) {
		
	$("<img/>", {
		id: "image",
		src: canvas.toDataURL("image/png", 1.0),
		width: w,
		height: h
		}).appendTo($("#img-out-right").empty());
		
		$("<a>", {
				href: canvas.toDataURL("image/png", 1.0),
				download: ELEMENT.data('name')
			  })
			  .on("click", function() {$(this).remove()})
			  .appendTo("body")[0].click();
			  
		$(".badge-user-right").hide();
		$("#image").hide();

	},
	//set proper canvas width and height
	width: w,
	height: h
	});
	
	// ELEMENT.hide(); 
}


	
