/* ------------------------------ Required Functions */
function ajax(type, params, callback, error, complete, upload) {

	if (!error)
		error = function (msg, code) {
			if (code)
				console.log('Ajax Error Code: ' + code);
			alert(msg);
		};
	if (!complete)
		complete = $.noop;
	$.ajax({
		url: app.url.base + 'ajax-teacher',
		type: type,
		data: params,
		cache: !upload ? true : false,
		contentType: !upload ? 'application/x-www-form-urlencoded; charset=UTF-8' : false,
		processData: !upload ? true : false,
		success: function (response) {
			try {
				response = $.parseJSON(response);
			} catch (e) {
				error('Une erreur s\'est produite', 2);
				return false;
			}
			if (response.type != 'OK' && response.type != 'ERR') {
				error('Une erreur s\'est produite', 3);
				return false;
			}
			if (response.type == 'ERR') {
				error(response.msg, response.code);
				return false;
			}

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

if ($("#meet_vc_boti").length) {
	navigator.mediaDevices.getUserMedia({
			video: true,
			audio: true
		})
		.then(stream => video.srcObject = stream)
		.catch(e => console.log(e.name + ": " + e.message));

	var domain = "meet.jit.si";
	var options = {
		roomName: app.video_conference.roomName,
		width: '100%',
		height: 500,
		parentNode: document.querySelector('#meet_vc_boti'),
		interfaceConfigOverwrite: {
			MOBILE_APP_PROMO: false,
			DEFAULT_BACKGROUND: '#3c96ec'
		}
	}
	var api = new JitsiMeetExternalAPI(domain, options);
	api.executeCommand('displayName', app.video_conference.displayName);
	api.executeCommand('email', app.video_conference.email);
	api.executeCommand('avatarUrl', app.video_conference.avatarUrl)
	api.executeCommand('subject', 'BOTI SCHOOL');
	api.executeCommand('toggleTileView');
	api.executeCommand('muteEveryone');

	if (app.video_conference.prof){ 
		api.executeCommand('toggleShareScreen');
		api.executeCommand('toggleLobby', true);
	}
	if (!app.video_conference.prof){ 
		api.executeCommand('muteEveryone');
	}

}

$(document).on('change', '.teacher-add-homework', function (e) {

	$this = $(this);

	ajax('get', {
		op: 'teacher-add-homework',
		classe: $this.val()
	}, function (data) {

		$('.next-homework').html(data.html);

	});
});

$('.teacher-add-homework').trigger("change");

/* ------------------------------ Utilities */
// Show element(form-group/control/...) only if a select's value matches the given value(s)
$('.js-visible-select-value', document).each(function () {
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


