$(document).ready(function(){
	'use strict';
	//Login Register Validation
	if($("form.form-horizontal").attr("novalidate")!=undefined){
		$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
	}

	// Remember checkbox
	if($('.chk-remember').length){
		$('.chk-remember').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
		});
	}
});



$(document).on('click', '[data-login-card]', function(e) {
	
	$this = $(this);
	
	card = $this.data('login-card');
	
	$('.card-login form').hide();
	$('.card-login form'+card).show();
});
