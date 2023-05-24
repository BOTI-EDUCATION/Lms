/* ------------------------------ Required Functions */
function ajax(type, params, url, callback, error, complete, upload) {
	
	if (!error)
		error = function(msg, code) {
			if (code)
				console.log('Ajax Error Code: '+code);
			//alert(msg);
		};
	if (!complete)
		complete = $.noop;
	$.ajax({
		url: url,
		type: type,
		data: params,
		cache:	!upload?true:false,
		contentType: !upload?'application/x-www-form-urlencoded; charset=UTF-8':false,
		processData: !upload?true:false,
		success: function(response) {
			try {
				response = $.parseJSON(response);
			} catch(e) { error('Une erreur s\'est produite', 2); return false; }
			if (response.type != 'OK' && response.type != 'ERR') { error('Une erreur s\'est produite', 3); return false; }
			if (response.type == 'ERR') { error(response.msg, response.code); return false; }

			callback.call(this, response.data);
		},
		error: function () {
			error.call(this, 'Une erreur s\'est produite', 1);
		},
		complete: function () {
			complete.call(this);
		}
	});
}

$(document).on('change', '.btn-check input', function(){
    $('.cycle_container').removeClass('d-none');
    if($(this).attr('id') == 'enseignement-superieur'){
        $('.cycle_container').addClass('d-none');
    }       
});


/* ------------------------------ Utilities */
// Show element(form-group/control/...) only if a select's value matches the given value(s)
$('.js-visible-select-value').each(function () {
	$control = $(this);
	$control.hide().removeClass('hidden');
	$context = $control.closest($control.data('vsv-context'));
	if ($context.length == 0)
		$context = undefined;
	$select = $($control.data('vsv-select'), $context);
	attr = $control.data('vsv-attr');
	values = $control.data('vsv-values');
	if (!Array.isArray(values))
		values = [values];
	if ('undefined' == typeof $select.data('vsv-targets')) {
		$select.data('vsv-targets', []);
		$select.change(function () {
			var $this = $(this);
			var targets = $this.data('vsv-targets');
			for (i = 0, ic = targets.length; i < ic; i++) {
				var target = targets[i];
				var val = parseFloat($this.val());
				if (isNaN(val) || val != $this.val())
					val = $this.val();
				if ('undefined' != typeof target.attr)
					val = $this.find('option:selected').data(target.attr);
				if ($.inArray(val, target.values) > -1)
					target.control.show().focus();
				else
					target.control.hide();
			}
		});
	}
	$select.data('vsv-targets').push({
		control: $control,
		attr: attr,
		values: values,
	});
	$select.change();
});


$('#inscription-ecole').submit(function(e) {
    e.preventDefault();
    $form = $(this);

    $('.lds-ripple-container', $form).show();
	params = $(this).serializeArray();
	ajax('post', params, $form.attr('action'), function(data) {
		
        $('.lds-ripple-container', $form).hide();
        $form.remove();
        $('.inscription-ecole-success').show();
		

    }, function(msg, code) {
			
        $('.lds-ripple-container', $form).hide();
        
    });
    
});


if ($("#meet_vc").length) 
	{
	navigator.mediaDevices.getUserMedia({ video: true, audio: true })
	  .then(stream => video.srcObject = stream)
	  .catch(e => console.log(e.name + ": "+ e.message));
  
	var domain = "meet.jit.si";
	var options = {
		roomName: app.video_conference.roomName,
		width: '100%',
		height: 500,
		parentNode: document.querySelector('#meet_vc'),
		interfaceConfigOverwrite: {
			MOBILE_APP_PROMO: false,
			DEFAULT_BACKGROUND: '#3c96ec'
		}
	}
	var api = new JitsiMeetExternalAPI(domain, options);
	api.executeCommand('displayName', app.video_conference.displayName);
	api.executeCommand('email', app.video_conference.email);
	api.executeCommand('avatarUrl', app.video_conference.avatarUrl)
}

window.H5PIntegration = {
  "baseUrl": "https://boti.h5p.com/lti", // No trailing slash
  "url": "/path/to/h5p-dir",          // Relative to web root
  "postUserStatistics": true,         // Only if user is logged in 
  "ajaxPath": "/path/to/h5p-ajax",     // Only used by older Content Types
  "ajax": {
    // Where to post user results
    "setFinished": "/interactive-contents/123/results/new", 
    // Words beginning with : are placeholders
    "contentUserData": "/interactive-contents/:contentId/user-data?data_type=:dataType&subContentId=:subContentId"
  },
  "saveFreq": 30, // How often current content state should be saved. false to disable.
  "user": { // Only if logged in !
    "name": "User Name",
    "mail": "user@mysite.com"
  },
  "siteUrl": "https://boti.h5p.com/lti", // Only if NOT logged in!
  "l10n": { // Text string translations
    "H5P": { 
      "fullscreen": "Fullscreen",
      "disableFullscreen": "Disable fullscreen",
      "download": "Download",
      "copyrights": "Rights of use",
      "embed": "Embedss",
      "size": "Size",
      "showAdvanced": "Show advanced",
      "hideAdvanced": "Hide advanced",
      "advancedHelp": "Include this script on your website if you want dynamic sizing of the embedded content:",
      "copyrightInformation": "Rights of use",
      "close": "Close",
      "title": "Title",
      "author": "Author",
      "year": "Year",
      "source": "Source",
      "license": "License",
      "thumbnail": "Thumbnail",
      "noCopyrights": "No copyright information available for this content.",
      "downloadDescription": "Download this content as a H5P file.",
      "copyrightsDescription": "View copyright information for this content.",
      "embedDescription": "View the embed code for this content.",
      "h5pDescription": "Visit H5P.org to check out more cool content.",
      "contentChanged": "This content has changed since you last used it.",
      "startingOver": "You'll be starting over.",
      "by": "by",
      "showMore": "Show more",
      "showLess": "Show less",
      "subLevel": "Sublevel"
    } 
  }
};

