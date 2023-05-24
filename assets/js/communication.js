
if ($('.phone .content').length) {
	
	var content = document.querySelector('.phone .content');
	var duplicate = content.cloneNode(true);
	var contentBlurred = document.createElement('div');
	contentBlurred.className = 'content-blurred';
	contentBlurred.appendChild(duplicate);

	var header = document.querySelector('header');
	header.appendChild(contentBlurred);

	var contentWrapper = document.querySelector('.content-phone'),
	translation;

	contentWrapper.addEventListener('scroll',function(){
	  translation = 'translate3d(0,' + (-this.scrollTop + 'px') + ',0)';
	  duplicate.style['-webkit-transform'] = translation;
	  duplicate.style['-moz-transform'] = translation;
	  duplicate.style['-ms-transform'] = translation;
	  duplicate.style['transform'] = translation;
	  
	  console.log(duplicate);
	});

	// offset to demo blurring
	contentWrapper.scrollTop = 140;
}
			
			
$('.espace-communication .communication-item input[type=checkbox]').change(function () {
	$('.espace-communication .communication-item button[type=submit]').prop('disabled', false);
	$('.espace-communication .communication-item button[type=submit]').removeClass("btn-opacity-03");
});

document.getElementById("FileAttachment").onchange = function () {
    $("#file_name").html(this.value.replace(/C:\\fakepath\\/i, ''));
};

$("#programmer_pub").change(function(){
	
    if($(this).is(':checked')){
		$('.share_after').prop('disabled', false);
        $(".share_now").prop('disabled', true);
		$(".programmer_pub_date").prop('required',true);
		$(".programmer_pub_date").prop('disabled', false);
		
    }else{
        $(".programmer_pub_date").val('');
		$(".programmer_pub_date").prop('disabled', true);
		$(".programmer_pub_date").prop('required', false);
		$('.share_after').prop('disabled', true);
        $(".share_now").prop('disabled', false);
     }
});