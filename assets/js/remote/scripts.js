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
		url: app.url.base + 'ajax',
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

// Auto Update Translations
function ajaxUpdateConfig(url, id, value) {

	// Prevent closing the page before the ajax query ends
	window.ajaxUpdates = window.ajaxUpdates || 0;
	// Increment for each ajax call
	window.ajaxUpdates++;
	$.ajax({
		url: url,
		type: 'post',
		data: {
			op: 'config-update',
			id: id,
			value: value
		},
		success: function (response) {
			// Decrement when ajax call ends
			window.ajaxUpdates--;
			try {
				response = $.parseJSON(response);
			} catch (e) {
				alert('Une erreur s\'est produite (2)');
				return false;
			}
			if (response.type != 'OK' && response.type != 'ERR') {
				alert('Une erreur s\'est produite (3)');
				return false;
			}
			if (response.type == 'ERR') {
				alert(response.msg + ' (4)');
				return false;
			}

			console.log('Operations left: ' + window.ajaxUpdates);
		},
		error: function () {
			// Decrement when ajax call ends
			window.ajaxUpdates--;
			alert('Une erreur s\'est produite (1)');
			return false;
		}
	});
}

function getValueOfInput(input) {
	const type = input.attr('type');
	if ('checkbox' == type) {
		return input.is(":checked");
	}
	return input.val();
}

$(function () {
	$('.params-auto-update').on('change', function (e) {
		$this = $(this);
		value = getValueOfInput($this);
		ajaxUpdateConfig('params', $this.data('item'), value);
	});
});



$(function () {
	$('.config-auto-update').not('editor_html').each(function (i, v) {
		$this = $(this);
		$this.data('val', $this.val());
	}).on('blur', function (e) {
		$this = $(this);
		if ($this.data('val') == $this.val())
			return;
		ajaxUpdateConfig('configs', $this.data('item'), $this.val());
		$this.data('val', $this.val());
	});


});


$(window).load(function () {
	for (i = 0; i < $.summernote.length; i++) {
		editor = $.summernote.get(i);
		$element = $(editor.getElement());

		if (!$element.is('.config-auto-update'))
			continue;
		$element.data('val', $(this).summernote('code'));

		editor.on('blur', function (e) {
			editor = this;

			$element = $(editor.getElement());
			if ($element.data('val') == $(this).summernote('code'))
				return;

			ajaxUpdateConfig('configs', $element.data('item'), $(this).summernote('code'));
			$element.data('val', $(this).summernote('code'));
		});
	}
});
window.onbeforeunload = function () {
	if (window.ajaxUpdates > 0) {
		return 'Une opération est en cours de traitement, veuillez patienter svp';
	}
};

/* ------------------------------ Plugin Defaults */
// Extend the default picker options for all instances.


var initPlugins = function (context) {
	/* ------------------------------ DataTable */
	var initDataTable = function (selector, options) {
		if (!$().dataTable)
			return false;

		$table = $(selector);
		if (!$table.length)
			return false;
		options = $.extend(true, {
			// set the initial value
			"language": {
				"sProcessing": "Traitement en cours...",
				"sSearch": "Rechercher&nbsp;:",
				"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
				"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix": "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst": "<i class='fa fa-angle-double-left'></i>",
					"sPrevious": "<i class='fa fa-angle-left'></i>",
					"sNext": "<i class='fa fa-angle-right'></i>",
					"sLast": "<i class='fa fa-angle-double-right'></i>"
				},
				"oAria": {
					"sSortAscending": ": activer pour trier la colonne par ordre croissant",
					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				}
			},
			"pageLength": 10,
			"pagingType": "full_numbers",
			"columnDefs": [{ // set default column settings
				'orderable': false,
				'targets': ['no-sort']
			}],
			"order": [] // set first column as a default sort by asc
		}, options);
		$table.dataTable(options);

		return $table;
	};

	$.each($('.datatable', context), function (ind, element) {
		$table = initDataTable(element);

		$tableWrapper = ('#' + $table.attr('id') + '_wrapper');

		if ($('.group-checkable', $table).length) {
			table.find('.group-checkable').change(function () {
				var set = jQuery(this).attr("data-set");
				var checked = jQuery(this).is(":checked");
				jQuery(set).each(function () {
					if (checked) {
						$(this).prop("checked", true);
						$(this).parents('tr').addClass("active");
					} else {
						$(this).prop("checked", false);
						$(this).parents('tr').removeClass("active");
					}
				});
				jQuery.uniform.update(set);
			});

			table.on('change', 'tbody tr .checkboxes', function () {
				$(this).parents('tr').toggleClass("active");
			});
		}
	});

	$('.datatable-order', context).DataTable({
		rowReorder: {
			selector: 'tr'
		},
		paging: false,
		searching: false
	});

	/* ------------------------------ Select2 */
	$('.select2', context).each(function () {
		var $this = $(this);
		$this.select2({
			placeholder: $this.data('placeholder'),
			allowClear: $this.data('allow-clear'),
			minimumResultsForSearch: $this.data('show-search') || $this.find('option').length > 6 ? 0 : Infinity,
		});
	});

	/* ------------------------------ multiSelect */
	$('.multiselect', context).multiSelect({
		selectableHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Rechercher'>",
		selectionHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Rechercher'>",
		afterInit: function (ms) {
			var that = this,
				$selectableSearch = that.$selectableUl.prev(),
				$selectionSearch = that.$selectionUl.prev(),
				selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
				selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

			that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
				.on('keydown', function (e) {
					if (e.which === 40) {
						that.$selectableUl.focus();
						return false;
					}
				});

			that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
				.on('keydown', function (e) {
					if (e.which == 40) {
						that.$selectionUl.focus();
						return false;
					}
				});
		},
		afterSelect: function (value) {
			$('.multiselect option[value="' + value + '"]').remove();
			$('.multiselect').append($("<option></option>").attr("value", value).attr('selected', 'selected'));
			this.qs1.cache();
			this.qs2.cache();
		},
		afterDeselect: function () {
			this.qs1.cache();
			this.qs2.cache();
		}
	});

	/* ------------------------------ Date time picker */

	moment.locale('fr');
	var pickerLocale = {
		applyLabel: 'OK',
		cancelLabel: 'Annuler',
		fromLabel: 'Entre',
		toLabel: 'et',
		customRangeLabel: 'Période personnalisée',
		daysOfWeek: moment().localeData()._weekdaysMin,
		monthNames: moment().localeData()._months,
		firstDay: 0,
		format: 'YYYY-MM-DD'
	};
	var pickerRanges = {
		'Aujourd\'hui': [moment(), moment()],
		'Hier': [moment().subtract('days', 1), moment().subtract('days', 1)],
		'5 jours précédents': [moment().subtract('days', 4), moment().subtract('days', 1)],
		'Ce mois': [moment().startOf('month'), moment().endOf('month')],
		'Mois précedent': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
	};

	$('#periode-range').daterangepicker({
		showDropdowns: false,
		ranges: pickerRanges,
		format: 'DD-MM-YYYY',
		separator: ' à ',
		locale: pickerLocale
	});

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		language: 'fr',
		weekStart: 1
	});

	$('.timepicker-24,.timepicker', context).timepicker({
		autoclose: !0,
		minuteStep: 5,
		showSeconds: !1,
		showMeridian: !1
	});

	/* ------------------------------ Full Calendar */
	var initialLangCode = 'fr';
	$('.calendar-cours', context).each(function () {
		var $this = $(this);

		$this.fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: $this.fullCalendar('today'),
			displayEventTime: false,
			lang: $this.data('lang') ? $this.data('lang') : initialLangCode,
			locale: $this.data('lang') ? $this.data('lang') : initialLangCode,
			buttonIcons: false, // show the prev/next text
			weekNumbers: true,
			hiddenDays: [0],
			minTime: $this.data('mintime') ? $this.data('mintime') : '08:00',
			maxTime: $this.data('maxtime') ? $this.data('maxtime') : '19:00',
			editable: $this.data('editable'),
			defaultView: $this.data('view') ? $this.data('view') : 'agendaWeek',
			allDaySlot: false,
			eventLimit: true, // allow "more" link when too many events
			events: $this.data('events') ? this.data('events') ? function (start, end, timezone, callback) {
				$.ajax({
					url: 'myxmlfeed.php',
					dataType: 'xml',
					data: {
						// our hypothetical feed requires UNIX timestamps
						start: start.unix(),
						end: end.unix()
					},
					success: function (doc) {
						var events = [];
						$(doc).find('event').each(function () {
							events.push({
								title: $(this).attr('title'),
								start: $(this).attr('start') // will be parsed
							});
						});
						callback(events);
					}
				});
			},
			eventClick: function (calEvent, jsEvent, view) {

				ajax('get', {
					op: 'cours-infos',
					cours: calEvent.id
				}, function (data) {

					var dialog = bootbox.dialog({
						title: '',
						message: data.html,
						className: "popup-infos-cours",
						buttons: {
							cancel: {
								label: "Fermer",
								className: 'btn btn-boti btn-block'
							}
						}
					});

				});


			},
			eventRender: function (event, element) {
				$(element).tooltip({
					title: event.title
				});
			},
		});
	});
	$('#timeline_pdf').on('click', function () {
		var source = window.document.getElementById("timeline");
		// before clone
		$('.calendar-cours').fullCalendar('option', {
			slotDuration: "00:10:00"
		});
		var el = source.cloneNode(true);
		//after clone
		$(el).find('.fc-toolbar').css("padding", "21px");
		$("<div style='margin-top:-17px'> <img style='width: 145px;' src='" + $('.navbar-header').find('img').attr('src') + "'/></div>").appendTo($(el).find('.fc-left').html(""))
		$(el).find('.fc-right').html("<b style='margin-top: 12px;'>" + ($('#filtre-classe').find('option[selected]').html()) + "</b>");
		$('.calendar-cours').fullCalendar('option', {
			slotDuration: "00:30:00"
		});
		$(el).find(".fc-title").css('padding', '.55rem .55rem .55rem 2rem');
		$(el).find(".fc-title").css('font-size', '17px');
		$(el).find(".fc-title").css('color', '#000');
		$(el).find(".fc-title").css('font-weight', 'bold');
		var opt = {
			margin: 0.8,
			html2canvas: {
				scale: 1.2,
			},
			filename: "Emploi_temps_groupe_" + $('#filtre-classe').find('option[selected]').html() + '.pdf',
			jsPDF: { unit: 'in', format: 'a2', orientation: 'p' }
			//jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape', compressPDF: true }
		};
		html2pdf().set(opt).from(el).save();
	});
	// build the language selector's options
	$('.calendar-lang-selector', context).each(function () {
		var $this = $(this);

		$.each($.fullCalendar.langs, function (langCode) {
			$this.append(
				$('<option/>')
					.attr('value', langCode)
					.prop('selected', langCode == initialLangCode)
					.text(langCode)
			);
		});
	}).on('change', function () {
		if (this.value) {
			$('.calendar').fullCalendar('option', 'lang', this.value);
		}
	});

	/* ------------------------------ Utilities */
	// Show element(form-group/control/...) only if a select's value matches the given value(s)
	$('.js-visible-select-value', context).each(function () {
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

	$('.selector-eleves', context).each(function () {
		var $select = $(this);
		$select.select2({
			// dropdownParent: this.selectAssets.closest('.modal'),
			placeholder: $select.data('placeholder') ? $select.data('placeholder') : '',
			allowClear: false, // the clear button gives an error
			minimumInputLength: 2,
			ajax: {
				url: app.url.base + 'ajax',
				delay: 250,
				cache: true,
				dataType: 'json',
				data: function (params) {
					return {
						op: 'selector-eleves',
						keywords: params.term, // search term
						page: params.page,
					};
				},
				processResults: function (data, params) {
					params.page = data.page;

					return {
						results: data.items,
						pagination: {
							more: (params.page * data.resultsPerPage) < data.totalCount
						}
					};
				},
			}
		}).on('change', function (e) {
			if (!this.value) {
				e.preventDefault();
				return;
			}
		});
	});
}
initPlugins();

$('.js-save-ordre').on("click", function () {
	$table = $($(this).data('target'));
	targetClass = $table.data('class');
	Redirect = $table.data('redirect');
	data = {};
	$table.find('tbody tr').each(function () {
		$tr = $(this);
		id = $tr.data('id');
		ordre = $('.js-ordre', $tr).text();
		data[id] = ordre;
	});

	ajax('post', {
		op: 'save-ordre-global',
		'class': targetClass,
		ordres: data
	}, function (data) {
		window.location.href = Redirect;
	});

});

/* ------------------------------ Attachments */
$('.form-champ').change(function (e) {
	$parentForm = $(this).parents('form');
	valSelect = false;
	$selectedOption = $('option:selected', this);
	val = $selectedOption.val();
	if (val == 'select')
		valSelect = true;
	$('.form-group-reponse', $parentForm).toggle(valSelect);
}).change();

$('.form .event-date #datestart, .form .event-date #dateend').change(function () {
	var startDate = $('.form .event-date #datestart').val();
	var endDate = $('.form .event-date #dateend').val();
	$('.form .event-time').toggle(!!startDate && !!endDate && startDate == endDate);
}).change();

$('.form-post-access #accesstype').change(function () {
	var val = $(this).val();
	$('.form-post-access .classes-selector').toggle(val == 'classes');
	$('.form-post-access .eleves-selector').toggle(val == 'eleves');
}).change();

$('.classe-inscriptions').click(function (e) {
	$this = $(this);
	$selectInscription = $($this.data('classe-inscriptions'));

	if (!$this.data('classe')) {
		$selectInscription
			.empty()
			.append($(document.createElement('option')).attr('value', 'all').text(''))
			.change();
		return;
	}

	ajax('get', {
		op: 'classe-eleves',
		classe: $this.data('classe')
	}, function (data) {
		$selectInscription.empty()
		if (!data.inscriptions.length) {
			$selectInscription
				.append($(document.createElement('option')).attr('value', 'all').text(''))
				.change();
			return;
		}
		$selectInscription.append($(document.createElement('option')).attr('value', 'all').text(''));
		for (i = 0; i < data.inscriptions.length; i++) {
			classeId = data.inscriptions[i].id;
			classeLabel = data.inscriptions[i].label;

			$option = $(document.createElement('option')).attr('value', classeId).text(classeLabel);
			$selectInscription.append($option);
		}

		$('.modal-inscription-respo-label').text(data.classe.label);
		$('.modal-inscription-respo-id').val(data.classe.id);

		// if($selectInscription.data('classe'))
		// $selectInscription.val($selectInscription.data('classe'));
		// $selectInscription.change();
	});
});

$('.select-responsable').change(function (e) {
	$this = $(this);

	ajax('get', {
		op: 'responsable-classe-infos',
		eleve: $(this).val()
	}, function (data) {

		$('.responsable-infos').html(data.html);

	});
});


var drEvent = $('.dropify').dropify({
	defaultFile: "",
	maxFileSize: 0,
	minWidth: 0,
	maxWidth: 0,
	minHeight: 0,
	maxHeight: 0,
	showRemove: !0,
	showLoader: !0,
	showErrors: !0,
	errorsPosition: "overlay",
	allowedFormats: ["portrait", "square", "landscape"],
	messages: {
		"default": "Glissez-déposez un fichier ici ou cliquez",
		replace: "Glissez-déposez un fichier ici ou cliquez",
		remove: "Supprimer",
		error: "Désolé, le fichier trop volumineux ou bien Le format n'est pas autorisé."
	},
	error: {
		'fileSize': 'Désolé, le fichier trop volumineux. ({{ value }} max).',
		'minWidth': 'La largeur de l image est trop petite ({{ value }}}px min).',
		'maxWidth': 'La largeur de l image est trop grande ({{ value }}}px max).',
		'minHeight': 'La hauteur de l image est trop petite ({{ value }}}px min).',
		'maxHeight': 'La taille de l image est trop grande ({{ value }}px max).',
		'imageFormat': 'Le format d image n est pas autorisé ({{ value }} seulement).'
	},
	tpl: {
		wrap: '<div class="dropify-wrapper"></div>',
		loader: '<div class="dropify-loader"></div>',
		message: '<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',
		preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',
		filename: '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
		clearButton: '<button type="button" class="dropify-clear">{{ remove }}</button>',
		errorLine: '<p class="dropify-error">{{ error }}</p>',
		errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
	}
});

drEvent.on('dropify.afterClear', function (event, element) {
	// alert('File deleted');
});


$('.delete').click(function (e) {
	if (!confirm('vous êtes sur le point de supprimer cet objet\r\nVoulez-vous continuer ?')) {
		e.preventDefault();
	}
});

$(document).ready(function () {
	$('.confirm-link').on('click', function (e, data) {
		if (!data) {
			handleDelete(e, 1, $(this), $(this).data('message'));
		} else {
			window.location = $(this).attr('href');
		}
	});
});

function handleDelete(e, stop, obj, message) {
	if (stop) {
		e.preventDefault();
		swal({
			title: "Êtes-vous sûr ?",
			text: message,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "OK",
			cancelButtonText: "Annuler !",
			closeOnConfirm: false
		}).then((willDelete) => {
			if (willDelete) {

				obj.trigger('click', {});
			} else { }
		});
	}
};


$('.mode-paiement').change(function (e) {

	$selectedOption = $('option:selected', this);
	dataOption = $selectedOption.data('alias');
	$('.paiementmode').slideUp();

	if (dataOption == 'virement') {

		$('.paiementmode.virement').slideDown();


	} else if (dataOption == 'cheque') {

		$('.paiementmode.cheque').slideDown();

	}

}).change();

/* Promotion Classes */

$('.get-promotion-classes').change(function (e) {
	$this = $(this);

	$selectClasse = $($this.data('selector'));
	if (!$this.val()) {
		$selectClasse
			.empty()
			.append($(document.createElement('option')).attr('value', 'all').text(''))
			.change();
		return;
	}

	ajax('get', {
		op: 'get-promotion-classes',
		promotion: $this.val()
	}, function (data) {
		$selectClasse.empty()
		if (!data.classes.length) {
			// $selectClasse
			// .append($(document.createElement('option')).attr('value', 'all').text(''))
			// .change();
			return;
		}
		// $selectClasse.append($(document.createElement('option')).attr('value', '').text(''));
		for (i = 0; i < data.classes.length; i++) {
			etudiantId = data.classes[i].id;
			etudiantLabel = data.classes[i].label;

			$option = $(document.createElement('option')).attr('value', etudiantId).text(etudiantLabel);
			$selectClasse.append($option);
		}

		if ($selectClasse.data('inscription'))
			$selectClasse.val($selectClasse.data('inscription'));
		$selectClasse.change();
	});
}).change();

/* Promotion Classes */

/* Generation Lignes (generique) START */

$(document).ready(function () {
	$('.gen-rows').each(function () {
		var $wrapper = $(this);

		var $tpl = $('.tpl-gen-row', $wrapper);
		var $container = $('.gen-rows-container', $wrapper);
		var $btnAdd = $('.js-btn-add-gen-row', $wrapper);

		$container.find('.js-btn-remove-gen-row').click(function (e) {
			var $this = $(this);
			$this.closest('.gen-row').remove();
			$wrapper.trigger('gen-rows-changed').trigger('gen-row-removed');
		});

		$wrapper.on('gen-rows-changed', function (e) {
			$rows = $container.find('.gen-row');
			$btnsRemove = $rows.find('.js-btn-remove-gen-row');
			removeEnabled = $rows.length > 1;
			if (removeEnabled)
				$btnsRemove.removeAttr('disabled').removeClass('disabled');
			else
				$btnsRemove.attr('disabled', '').addClass('disabled');
		});

		$btnAdd.click(function (e) {
			$row = $($tpl.html());
			console.log($row);
			initPlugins($row);
			$row.find('.js-btn-remove-gen-row').click(function (e) {
				var $this = $(this);
				$this.closest('.gen-row').remove();
				$wrapper.trigger('gen-rows-changed').trigger('gen-row-removed');
			});
			$container.append($row).trigger('gen-rows-changed').trigger('gen-row-added', {
				row: $row
			});
		}).trigger('click');
	});
});
/* Generation Lignes (generique) END */

/* Generation cours START */
var calcEncaissementTotalMontant = function () {
	$this = $('.gen-rows-encaissement')
	totalMontants = 0;
	$this.find('.montant').each(function () {
		val = $(this).val() * 1; // *1 to convert to number
		totalMontants += val;
	})
	$('.js-total-montants').text(totalMontants)
}
$('.gen-rows-encaissement').on('gen-rows-changed', function (e) {
	calcEncaissementTotalMontant();
});
$('.gen-rows-encaissement').on('change', '.montant', function (e) {
	calcEncaissementTotalMontant();
});
/* Generation cours END */

/* Generation cours START */
var $genCoursTpl = $('.tpl-gen-cours');
var $genCoursContainer = $('.generate-cours');
var $genCoursBtnAdd = $('.js-btn-add-cours');
if ($genCoursContainer.length) {
	$genCoursContainer.on('cours-changed', function (e) {
		$cours = $genCoursContainer.find('.js-cours');
		$btnsRemove = $cours.find('.js-btn-remove-cours');
		removeEnabled = $cours.length > 1;
		if (removeEnabled)
			$btnsRemove.removeAttr('disabled').removeClass('disabled');
		else
			$btnsRemove.attr('disabled', '').addClass('disabled');

		limit = 6;

		if (limit > 0) {

			limitRow = $cours.length > limit;
			if (limitRow)
				$genCoursBtnAdd.hide();
			else
				$genCoursBtnAdd.show();
		}
	});
	$genCoursBtnAdd.click(function (e) {
		$html = $($genCoursTpl.html());
		initPlugins($html);
		$html.find('.js-btn-remove-cours').click(function (e) {
			var $this = $(this);
			$this.closest('.js-cours').remove();
			$genCoursContainer.trigger('cours-changed');
		});
		$genCoursContainer.append($html).trigger('cours-changed');
	}).trigger('click');
}

$('.js-btn-remove-generated-cours').click(function () {
	var $this = $(this);
	var count = $this.closest('tbody').children().length;
	$this.closest('tr').remove();
	$('.js-cours-count').text(count - 1);
})
/* Generation cours END */



$('.add-parrainage-choice').click(function (e) {
	e.preventDefault();
	$this = $(this);
	dataOption = $this.data('type');

	$('.parrainage-form').slideUp();

	$('.parrainage-form' + dataOption).slideDown();

});


/* Forms Validation */
if ($.validate) {
	$.validate({
		modules: 'location, date, security, file'
	});
}


$('.generate-alias').on('keyup', function (e) {

	label = $(this).val();

	ajax('get', {
		op: 'generate-alias',
		'label': label
	}, function (data) {
		$('.alias-input').val(data.alias);
	});
});

if ($('#trigger-overlay-detail').length) {
	(function () {
		' use strict ';
		var triggerBttnDebouche = document.getElementById('trigger-overlay-detail'),
			overlayDeoubche = document.querySelector('#overlay-detail'),
			closeBttnDebouche = overlayDeoubche.querySelector('a.overlay-close');

		transEndEventNames = {
			'WebkitTransition': 'webkitTransitionEnd',
			'MozTransition': 'transitionend',
			'OTransition': 'oTransitionEnd',
			'msTransition': 'MSTransitionEnd',
			'transition': 'transitionend'
		},
			transEndEventName = transEndEventNames[Modernizr.prefixed('transition')],
			support = {
				transitions: Modernizr.csstransitions
			};

		function toggleOverlay() {
			if (classie.has(overlayDeoubche, 'open')) {
				//ADD SCROLL TO BODY WHEN POPUP HIDDEN
				$('body').removeClass('no-scroll');
				classie.remove(overlayDeoubche, 'open');
				classie.add(overlayDeoubche, 'close');
				var onEndTransitionFn = function (ev) {
					if (support.transitions) {
						if (ev.propertyName !== 'visibility') return;
						this.removeEventListener(transEndEventName, onEndTransitionFn);

					}
					classie.remove(overlayDeoubche, 'close');
				};
				if (support.transitions) {

					overlayDeoubche.addEventListener(transEndEventName, onEndTransitionFn);
				} else {
					onEndTransitionFn();
				}
			} else if (!classie.has(overlayDeoubche, 'close')) {
				// REMOVE SCROLL FROM BODY WHEN POPUP LOADED
				$('body').addClass('no-scroll');
				classie.add(overlayDeoubche, 'open');
			}
		}

		triggerBttnDebouche.addEventListener('click', toggleOverlay);
		closeBttnDebouche.addEventListener('click', toggleOverlay);
	})();
}

if ($.fn.slimScroll) {
	$('.scroller-content').slimScroll({
		size: '5px',
		position: 'right',
		color: '#E21D35',
		disableFadeOut: false,
		height: '400px',
	});
}


$('.post-detail').on('click', function (e) {
	var l = document.getElementById('trigger-overlay-detail');
	l.click();

	$this = $(this);
	$('.content-details').html('');
	ajax('get', {
		op: 'timeline-details',
		post: $(this).data('post')
	}, function (data) {

		$('.content-details').html(data.html);
		$('.scroller-content').slimScroll({
			scrollTo: '0px'
		});
	});
});

$(document).on('click', '[data-login-card]', function (e) {

	$this = $(this);

	card = $this.data('login-card');

	$('.card-login form').hide();
	$('.card-login form' + card).show();
});

// Absences Start
$('#table-absences .absence').change(function (e) {
	let $this = $(this);
	let $row = $this.closest('tr');
	let $table = $row.closest('#table-absences');

	let idClasse = $table.data('classe');
	let date = $table.data('date');
	let idInscription = $row.data('inscription');
	let periode = $this.data('periode');
	let absent = $this.prop('checked');

	let retardInput = $('#retard-' + idInscription + '-' + periode);
	if (absent) {
		retardInput.val(0).prop('disabled', true)
	} else {
		retardInput.val(0).prop('disabled', false)
	}

	ajax('POST', {
		op: 'absences-absence',
		classe: idClasse,
		date: date,
		inscription: idInscription,
		periode: periode,
		absent: absent
	});
})

$('#table-absences .retard').change(function (e) {
	$this = $(this);
	$row = $this.closest('tr');
	$table = $row.closest('#table-absences');

	idClasse = $table.data('classe');
	date = $table.data('date');
	idInscription = $row.data('inscription');
	periode = $this.data('periode');
	val = $this.val();

	ajax('POST', {
		op: 'absences-retard',
		classe: idClasse,
		date: date,
		inscription: idInscription,
		periode: periode,
		minutes: val
	});
})

$('.js-discipline-btn').click(function () {
	idInscription = $(this).closest('tr').data('inscription');
	actions = $(this).closest('tr').data('actions');
	$modal = $('#discipline-modal');
	$modal.find('#inscription').val(idInscription);
	$modal.find('#cours').val('').change();
	$modal.find('#type').val('').change();
	$table = $modal.find('.table-actions');
	if (actions.length == 0) {
		$table.addClass('hidden');
	} else {
		$body = $table.find('tbody').empty();
		tplHtml = $table.find('template').html();
		totalValeurs = 0;
		actions.forEach(function (action) {
			totalValeurs += action.valeur * 1;
			$row = $(tplHtml);
			$row.data('id', action.id);
			$row.attr('class', 'action-' + action.id);
			$row.find('.cours').text(action.cours);
			$row.find('.type').text(action.type);
			$row.find('.valeur').text(action.valeur);
			$row.find('.js-btn-delete').click(function () {
				let $row = $(this).closest('tr');
				let idAction = $row.data('id');
				ajax('POST', {
					op: 'absences-discipline-delete',
					action: idAction,
				}, function (result) {
					$rowInscription = $('#table-absences .inscription-' + result.idInscription);
					let totalValeurs = 0;
					result.actions.forEach(function (action) {
						totalValeurs += action.valeur * 1;
					})
					$rowAction = $('.action-' + result.idAction);
					$tableActions = $rowAction.closest('table');
					$tableActions.find('.total-valeurs').text(totalValeurs);
					if (totalValeurs == 0)
						$tableActions.hide()
					$rowAction.remove();
					refreshRowTotalDiscipline($rowInscription, result.actions);
					console.log($rowInscription, result.actions)
				})
			})
			$body.append($row);
		})
		$table.find('.total-valeurs').text(totalValeurs)
		$table.removeClass('hidden');
	}
	$modal.modal();
});

$('.form-discipline #type').change(function (e) {
	$this = $(this);
	score = $('option:selected', $this).data('score');
	$('.type-action-score')
		.text(((score > 0) ? '+' : '') + score)
		.toggle(score != 0)
		.toggleClass('tag-success', score > 0)
		.toggleClass('tag-danger', score < 0)
		.removeClass('hidden');
});


$('.form-discipline').submit(function (e) {
	e.preventDefault();
	$form = $(this);
	idInscription = $('#inscription', $form).val();
	idCours = $('#cours', $form).val();
	idType = $('#type', $form).val();
	date = $('#date', $form).val();
	commentaire = $('#commentaire', $form).val();

	ajax('POST', {
		op: 'absences-discipline-add',
		inscription: idInscription,
		cours: idCours,
		type: idType,
		date: date,
		commentaire: commentaire,
	}, function (result) {
		$row = $('.inscription-' + result.idInscription);
		$row.data('actions', result.actionsData);
		refreshRowTotalDiscipline($row, result.actionsData);
		$('#discipline-modal').modal('hide');
	})
});



refreshRowTotalDiscipline = function ($row, actions) {
	if ('undefined' != typeof actions) {
		$row.data('actions', actions);
	} else {
		actions = $row.data('actions');
	}
	// Fill actions in the html
	if (actions.length == 0) {
		$('.actions', $row).hide().removeClass('hidden');
		$('.noactions', $row).removeClass('hidden').show();
	} else {
		$('.noactions', $row).hide().removeClass('hidden');
		totalValeurs = 0;
		actions.forEach(action => {
			totalValeurs += action.valeur * 1;
		});
		$('.actions .js-discipline-total-valeurs', $row).text(totalValeurs)
			.closest('.btn')
			.toggleClass('btn-success', totalValeurs > 0)
			.toggleClass('btn-danger', totalValeurs < 0)
			.toggleClass('btn-info', totalValeurs == 0);
		$('.actions', $row).removeClass('hidden').show();
	}
}
// Absences End

if ($('#quiz').length > 0) {


	var all_questions = app.quiz.questions;

	// Objet Quiz Contien Plusieur Objet Questions
	var Quiz = function (quiz_name) {
		// Nom de quiz (Lot)
		this.quiz_name = quiz_name;

		// Array of Questions
		this.questions = [];
	}

	// add Question to Quiz
	Quiz.prototype.add_question = function (question) {
		// Randomly choose where to add question
		var index_to_add_question = Math.floor(Math.random() * this.questions.length);
		this.questions.splice(index_to_add_question, 0, question);
	}


	Quiz.prototype.render = function (container) {

		var self = this;


		$('#quiz-results').hide();


		$('#quiz-name').text(this.quiz_name);

		// Create a container for questions
		var question_container = $('<div>').attr('id', 'question').insertAfter('#quiz-name');


		function change_question() {
			self.questions[current_question_index].render(question_container);
			$('#prev-question-button').prop('disabled', current_question_index === 0);
			$('#next-question-button').prop('disabled', current_question_index === self.questions.length - 1);


			var all_questions_answered = true;
			for (var i = 0; i < self.questions.length; i++) {
				if (self.questions[i].user_choice_index === null) {
					all_questions_answered = false;
					break;
				}
			}
			$('#submit-button').prop('disabled', !all_questions_answered);
		}


		var current_question_index = 0;
		change_question();


		$('#prev-question-button').click(function () {
			if (current_question_index > 0) {
				current_question_index--;
				change_question();
			}
		});




		$('#submit-button').click(function () {

			var score = 0;
			for (var i = 0; i < self.questions.length; i++) {
				if (self.questions[i].user_choice_index === self.questions[i].correct_choice_index) {
					score++;
				}
			}


			var percentage = score / self.questions.length;
			console.log(percentage);
			var message;
			if (percentage === 1) {
				message = 'Bravo <i class="fa fa-smile-o" aria-hidden="true"></i>'
			} else if (percentage >= .75) {
				message = 'Bravo <i class="fa fa-smile-o" aria-hidden="true"></i>'
			} else if (percentage >= .5) {
				message = 'Réessayer une autre fois. <i class="fa fa-star-half-o" aria-hidden="true"></i>'
			} else {
				message = 'Réessayer une autre fois. <i class="fa fa-star-half-o" aria-hidden="true"></i>'
			}
			$('#quiz-results-message').html(message);
			$('#quiz-results-score').html('Votre Score : <b>' + score + '/' + self.questions.length + '</b>');
			$('#quiz-results').slideDown();
			$('#quiz a').slideDown();
		});

		// Add a listener on the questions container to listen for user select changes. This is for determining whether we can submit answers or not.
		question_container.bind('user-select-change', function () {
			var all_questions_answered = true;
			for (var i = 0; i < self.questions.length; i++) {
				if (self.questions[i].user_choice_index === null) {
					all_questions_answered = false;
					break;
				}
			}




			if (self.questions[current_question_index].user_choice_index === self.questions[current_question_index].correct_choice_index) {
				$('input[name=choices]:checked + label').css({
					"background-color": "green",
					"color": "#fff",
				});
			} else {
				$('input[name=choices]:checked + label').css({
					"background-color": "#e13034"
				});
			}

			if (current_question_index < self.questions.length - 1) {
				current_question_index++;

				setTimeout(function () {
					change_question();
				}, 2000);

			} else {

				setTimeout(function () {
					$("#submit-button").trigger("click");
				}, 2000);

			}


		});
	}


	var Question = function (question_string, img_src, correct_choice, wrong_choices) {

		this.question_string = question_string;
		this.img_src = img_src;
		this.choices = [];
		this.user_choice_index = null;

		this.correct_choice_index = Math.floor(Math.random() * wrong_choices.length + 1);


		var number_of_choices = wrong_choices.length + 1;
		for (var i = 0; i < number_of_choices; i++) {
			if (i === this.correct_choice_index) {
				this.choices[i] = correct_choice;
			} else {

				var wrong_choice_index = Math.floor(Math.random(0, wrong_choices.length));
				this.choices[i] = wrong_choices[wrong_choice_index];


				wrong_choices.splice(wrong_choice_index, 1);
			}
		}
	}

	Question.prototype.render = function (container) {
		var self = this;

		var question_string_h2;
		if (container.children('h2').length === 0) {
			question_string_h2 = $('<h2>').appendTo(container);
		} else {
			question_string_h2 = container.children('h2').first();
		}
		question_string_h2.html(this.question_string);

		if (!this.img_src)
			$('p', question_string_h2).css({
				"position": "absolute",
				"top": "130px",
				"top": "130px",
				"left": "0",
				"right": "130px",
				"right": "0",
				"font-size": "50px",
			});

		var img_question;
		if (container.children('img').length === 0) {
			img_question = $('<img>').appendTo(container);
		} else {
			img_question = container.children('img').first();
		}

		if (this.img_src)
			img_question.attr('src', this.img_src).attr('class', 'img-responsive');

		if (container.children('input[type=radio]').length > 0) {
			container.children('input[type=radio]').each(function () {
				var radio_button_id = $(this).attr('id');
				$(this).remove();
				container.children('label[for=' + radio_button_id + ']').remove();
			});
		}
		for (var i = 0; i < this.choices.length; i++) {

			var choice_radio_button = $('<input>')
				.attr('id', 'choices-' + i)
				.attr('type', 'radio')
				.attr('name', 'choices')
				.attr('value', 'choices-' + i)
				.attr('checked', i === this.user_choice_index)
				.appendTo(container);

			// Create the label
			var choice_label = $('<label>')
				.text(this.choices[i])
				.attr('for', 'choices-' + i)
				.appendTo(container);
		}

		$('input[name=choices]').change(function (index) {
			var selected_radio_button_value = $('input[name=choices]:checked').val();

			self.user_choice_index = parseInt(selected_radio_button_value.substr(selected_radio_button_value.length - 1, 1));

			container.trigger('user-select-change');
		});
	}


	$(document).ready(function () {

		var quiz = new Quiz(app.quiz.name);


		for (var i = 0; i < all_questions.length; i++) {

			var question = new Question(all_questions[i].question_string, all_questions[i].img_src, all_questions[i].choices.correct, all_questions[i].choices.wrong);


			quiz.add_question(question);
		}


		var quiz_container = $('#quiz');
		quiz.render(quiz_container);
	});


}




// POINTAGE //

$('.code-pointage').on('keyup', function (e) {

	code = $(this).val();
	if (code.substring(code.length - 1) == "$") {
		$(this).val('');
		ajax('get', {
			op: 'pointage',
			idEleve: code
		}, function (data) {

			$('.loading').hide();
			swal({
				title: data.msg,
				icon: "success",
				timer: 700
			});
			$('.code-pointage').val('');
			$('.code-pointage').focus();

		}, function (msg, code) {

			$('.error').show();

		}, function () {

			$('.fields').show();

		});
	}
});

$('.code-pointage-collaborateur').on('keyup', function (e) {

	code = $(this).val();
	if (code.substring(code.length - 1) == "$") {
		$(this).val('');
		ajax('get', {
			op: 'pointage-collaborateur',
			idUser: code
		}, function (data) {

			$('.loading').hide();
			swal({
				title: data.msg,
				icon: "success",
				timer: 1500
			});
			$('.code-pointage-collaborateur').val('');
			$('.code-pointage-collaborateur').focus();

		}, function (msg, code) {

			$('.error').show();

		}, function () {

			$('.fields').show();

		});
	}
});

// $('.pointage').click(function(e) {

// $('.fields').hide();
// $('.loading').show();

// $this = $(this);

// idEleve = $('.code-pointage').val();

// });

$(document).scroll(function () {
	if ($(document).scrollTop() > 10) {
		if (!$('.navbar-container').hasClass('bg-white')) {
			$('.navbar-container').addClass("bg-white");
		}
	} else {
		if ($('.navbar-container').hasClass('bg-white')) {
			$('.navbar-container').removeClass("bg-white");
		}
	}
});


$('.editable').each(function (i, v) {
	$this = $(this);

	editor = new MediumEditor($this, {
		buttonLabels: 'fontawesome',
		toolbar: {
			buttons: ['bold', 'italic', 'underline', 'justifyLeft', 'justifyCenter', 'justifyRight', 'quote', 'anchor', 'image', 'orderedlist', 'unorderedlist', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'html'],
			static: true,
			sticky: true
		},
		placeholder: {
			/* This example includes the default options for placeholder,
			   if nothing is passed this is what it used */
			text: 'Tapez votre texte',
			hideOnClick: true
		},
		paste: {
			/* This example includes the default options for paste,
			   if nothing is passed this is what it used */
			forcePlainText: true,
			cleanPastedHTML: true,
			cleanReplacements: [],
			cleanAttrs: ['class', 'style', 'dir'],
			cleanTags: ['meta'],
			unwrapTags: []
		}
	});


	editor.subscribe('editableBlur', function (event, editorElement) {
		let allContents = editor.serialize();
		let content = allContents[event.srcElement.id].value;
		$('#' + event.srcElement.id + ' + .medium-editor-hidden').val(content);
	});

	$this.mediumInsert({
		editor: editor,
		// addons: {
		// images: {
		// fileUploadOptions: {
		// url: app.url.base + 'upload-editor.php'
		// },
		// uploadCompleted: function ($el, data) {
		// console.log(data.result);
		// },
		// uploadFailed: function (uploadErrors, data) {
		// console.log(data);
		// },
		// }
		// }
		addons: { // (object) Addons configuration
			images: { // (object) Image addon configuration
				label: '<span class="fa fa-camera"></span>', // (string) A label for an image addon
				deleteScript: 'delete.php', // (string) A relative path to a delete script
				deleteMethod: 'POST',
				fileDeleteOptions: {}, // (object) extra parameters send on the delete ajax request, see http://api.jquery.com/jquery.ajax/
				preview: true, // (boolean) Show an image before it is uploaded (only in browsers that support this feature)
				captions: true, // (boolean) Enable captions
				captionPlaceholder: 'Type caption for image (optional)', // (string) Caption placeholder
				autoGrid: 3, // (integer) Min number of images that automatically form a grid
				fileUploadOptions: { // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
					url: app.url.base + 'upload-editor.php',
					acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i // (regexp) Regexp of accepted file types
				},
				styles: { // (object) Available image styles configuration
					wide: { // (object) Image style configuration. Key is used as a class name added to an image, when the style is selected (.medium-insert-images-wide)
						label: '<span class="fa fa-align-justify"></span>', // (string) A label for a style
						added: function ($el) { }, // (function) Callback function called after the style was selected. A parameter $el is a current active paragraph (.medium-insert-active)
						removed: function ($el) { } // (function) Callback function called after a different style was selected and this one was removed. A parameter $el is a current active paragraph (.medium-insert-active)
					},
					left: {
						label: '<span class="fa fa-align-left"></span>'
					},
					right: {
						label: '<span class="fa fa-align-right"></span>'
					},
					grid: {
						label: '<span class="fa fa-th"></span>'
					}
				},
				actions: { // (object) Actions for an optional second toolbar
					remove: { // (object) Remove action configuration
						label: '<span class="fa fa-times"></span>', // (string) Label for an action
						clicked: function ($el) { // (function) Callback function called when an action is selected
							var $event = $.Event('keydown');

							$event.which = 8;
							$(document).trigger($event);
						}
					}
				},
				messages: {
					acceptFileTypesError: 'This file is not in a supported format: ',
					maxFileSizeError: 'This file is too big: '
				},
				uploadError: function ($el, data) { },
				uploadCompleted: function ($el, data) { }
			}
		}
	});
});


$('.get-eleve-promotion').change(function (e) {
	$this = $(this);

	$selectPromotion = $($this.data('selector'));

	ajax('get', {
		op: 'get-eleve-promotions',
		eleve: $this.val()
	}, function (data) {

		$('.form-eleve-infos').html(data.html);

		$selectPromotion.empty()
		if (!data.promotions.length) {
			return;
		}

		for (i = 0; i < data.promotions.length; i++) {
			etudiantId = data.promotions[i].id;
			etudiantLabel = data.promotions[i].label;

			$option = $(document.createElement('option')).attr('value', etudiantId).text(etudiantLabel);
			$selectPromotion.append($option);
		}


		if ($selectPromotion.data('promotion'))
			$selectPromotion.val($selectPromotion.data('promotion'));

		$selectPromotion.change();

	});

});

if ($('.get-eleve-promotion').length > 0 && $('.get-eleve-promotion').val())
	$('.get-eleve-promotion').change();



$(document).on('change', '.eleve-promotion-recu', function (e) {

	$this = $(this);

	ajax('get', {
		op: 'recu-promotion',
		promotion: $this.val()
	}, function (data) {

		$('.num-recu-prom').val(data.recu);

	});
});

$('.iconpicker-input').iconpicker({
	fullClassFormatter: function (val) {
		return 'fa ' + val;
	}
});



$(document).on('change', '.file-upload-copie-examen', function (e) {

	$this = $(this);
	var file = $this[0].files[0];

	var form = new FormData();
	form.append('file', file);
	form.append('op', 'copie-examen');
	form.append('note', $this.data('note'));

	ajax('post', form, function (data) {

		$('input[name="cf_token"]').val(data.cf_token);
		$(data.selector_add).html(data.html);
		$(data.selector_delete).remove();

	}, null, null, true);
});


$(document).on('change', '.file-upload-support-cours', function (e) {

	$this = $(this);
	var file = $this[0].files[0];

	var form = new FormData();
	form.append('file', file);
	form.append('op', 'support-cours');
	form.append('cours', $this.data('cours'));

	ajax('post', form, function (data) {

		window.location.href = data.redirect;

	}, null, null, true);
});

$(document).on('click', '.file-delete-copie-examen', function (e) {
	$this = $(this);

	var form = new FormData();
	form.append('op', 'delete-copie-examen');
	form.append('note', $this.data('note'));

	ajax('post', form, function (data) {

		$('input[name="cf_token"]').val(data.cf_token);
		$(data.selector_add).html(data.html);

	}, null, null, true);
});

$('.form-retards-paiement-classe, .form-retards-paiement-mois, .form-retards-paiement-service').on('change', function (e) {

	$this = $(this);

	classe = $('.form-retards-paiement-classe').val();
	mois = $('.form-retards-paiement-mois').val();
	service = $('.form-retards-paiement-service').val();

	if (mois)
		$('.form-retards-paiement').submit();



});
$('.form-etat-global-paiement-mois').on('change', function (e) {

	$this = $(this);

	mois = $('.form-etat-global-paiement-mois').val();

	if (mois)
		$('.form-etat-global-paiement').submit();


});

$('.form-cours-classe, .form-cours-enseignant, .form-cours-salle, .form-cours-seance, .form-cours-date').on('change', function (e) {

	$this = $(this);
	collaborateur = $this.data('collaborateur');

	classe = $('.form-cours-classe').val();
	enseignant = $('.form-cours-enseignant').val();
	salle = $('.form-cours-salle').val();

	seance = $('.form-cours-seance').val();
	date = $('.form-cours-date').val();

	id = $('.form-cours-id').val();

	if (!seance || !date)
		return;

	ajax('get', {
		op: 'form-check-cours',
		classe: classe,
		enseignant: enseignant,
		salle: salle,
		seance: seance,
		date: date,
		id: id
	}, function (data) {

		$('.form-cours-classe-error').hide();
		$('.form-cours-salle-error').hide();
		$('.form-cours-enseignant-error').hide();
		$('.form-cours-date-error').hide();

		if (data.checkSalle == true) {
			$('.form-cours-salle-error').show();
		}
		if (data.checkEnseignant == true) {
			$('.form-cours-enseignant-error').show();
		}
		if (data.checkClasse == true) {
			$('.form-cours-classe-error').show();
		}
		if (data.checkHoliday == true) {
			$('.form-cours-date-error').show();
		}
	});

});


$('.enable-input-range').change(function () {

	var checked = $(this).is(":checked");
	if (checked)
		$('#periode-range').prop('disabled', false);
	else
		$('#periode-range').prop('disabled', true);

});

$('.ti_tache_check_allday').change(function () {

	var checked = $(this).is(":checked");
	if (checked) {

		$('.ti_tache_datepicker').removeClass('col-md-7');
		$('.ti_tache_datepicker').addClass('col-md-12');
		$('.ti_tache_timepicker').hide();
	} else {

		$('.ti_tache_datepicker').removeClass('col-md-12');
		$('.ti_tache_datepicker').addClass('col-md-7');
		$('.ti_tache_timepicker').show();

	}

});



if ($("#encaissements-depenses-chart").length > 0) {

	// Column chart
	// ------------------------------
	$(window).on("load", function () {

		//Get the context of the Chart canvas element we want to select
		var ctx = $("#encaissements-depenses-chart");

		// Chart Options
		var chartOptions = {
			// Elements options apply to all of the options unless overridden in a dataset
			// In this case, we are setting the border of each bar to be 2px wide and green
			elements: {
				rectangle: {
					borderWidth: 2,
					borderColor: 'rgb(0, 255, 0)',
					borderSkipped: 'bottom'
				}
			},
			responsive: true,
			maintainAspectRatio: false,
			responsiveAnimationDuration: 500,
			legend: {
				position: 'top',
			},
			scales: {
				xAxes: [{
					display: true,
					gridLines: {
						color: "#f3f3f3",
						drawTicks: false,
					},
					scaleLabel: {
						display: true,
					}
				}],
				yAxes: [{
					display: true,
					gridLines: {
						color: "#f3f3f3",
						drawTicks: false,
					},
					scaleLabel: {
						display: true,
					}
				}]
			},
			title: {
				display: true,
				text: ''
			}
		};

		// Chart Data
		var chartData = {
			labels: app.months.labels,
			datasets: app.datasets
		};

		var config = {
			type: 'bar',

			// Chart Options
			options: chartOptions,

			data: chartData
		};

		// Create the chart
		var lineChart = new Chart(ctx, config);
	});
}



$('.eleve-search-input').on('keyup', function (e) {

	label = $(this).val();

	if (label)
		$('.eleve-search-classe').prop('disabled', true);
	else
		$('.eleve-search-classe').prop('disabled', false);
	console.log(label);

});




$('.tarification-check').change(function () {

	var checked = $(this).is(":checked");

	if (checked) {

		$('.tarification-montant-' + $(this).data('rubrique')).prop('disabled', false);
		if ($(this).data('prix')) {
			$('.tarification-montant-' + $(this).data('rubrique')).val($(this).data('prix'));
		}
	} else
		$('.tarification-montant-' + $(this).data('rubrique')).prop('disabled', true);

});


$('.tarification-montant').on('keyup', function (e) {

	prix = $(this).val();

	if (prix != $(this).data('prix'))
		$('.affecter-tarif-' + $(this).data('rubrique')).show();
	else
		$('.affecter-tarif-' + $(this).data('rubrique')).hide();

});


$('.form-etat-encaissement-promotion').on('change', function (e) {
	$('.form-etat-encaissement').submit();
});

$('.scroll-content').slimscroll({
	height: 'auto'
});

$('.chart-point-discipline').easyPieChart({
	//your options goes here
	barColor: $('.chart-point-discipline').data('color'),
	trackColor: "#f6f6f6",
	lineWidth: 10,
	lineCap: 'circle',
	scaleColor: false,
	size: 170,
	animate: 2000
});




if ($("#evolution-absences-chart").length > 0) {

	// Column chart
	// ------------------------------
	$(window).on("load", function () {

		//Get the context of the Chart canvas element we want to select
		var ctx = $("#evolution-absences-chart");

		// Chart Options
		var chartOptions = {
			// Elements options apply to all of the options unless overridden in a dataset
			// In this case, we are setting the border of each bar to be 2px wide and green
			elements: {
				rectangle: {
					borderWidth: 2,
					borderColor: 'rgb(0, 255, 0)',
					borderSkipped: 'bottom'
				}
			},
			responsive: true,
			maintainAspectRatio: false,
			responsiveAnimationDuration: 500,
			legend: {
				position: 'top',
			},
			scales: {
				xAxes: [{
					display: true,
					gridLines: {
						color: "#f3f3f3",
						drawTicks: false,
					},
					scaleLabel: {
						display: true,
					}
				}],
				yAxes: [{
					display: true,
					gridLines: {
						color: "#f3f3f3",
						drawTicks: false,
					},
					scaleLabel: {
						display: true,
					}
				}]
			},
			title: {
				display: false,
				text: 'Evolution des Absences'
			}
		};

		// Chart Data
		var chartData = {
			labels: app.evolutionabsences.labels,
			datasets: app.evolutionabsences.datasets
		};

		var config = {
			type: 'bar',

			// Chart Options
			options: chartOptions,

			data: chartData
		};

		// Create the chart
		var lineChart = new Chart(ctx, config);
	});
}



if ($("#dashboard-connexion-appmobile").length > 0) {

	// Column chart
	// ------------------------------
	$(window).on("load", function () {

		//Get the context of the Chart canvas element we want to select
		var ctx = $("#dashboard-connexion-appmobile");

		// Chart Options
		var chartOptions = {
			// Elements options apply to all of the options unless overridden in a dataset
			// In this case, we are setting the border of each bar to be 2px wide and green
			elements: {
				rectangle: {
					borderWidth: 2,
					borderColor: 'rgb(0, 255, 0)',
					borderSkipped: 'bottom'
				}
			},
			responsive: true,
			maintainAspectRatio: false,
			responsiveAnimationDuration: 500,
			legend: {
				display: false
			},
			scales: {
				xAxes: [{
					display: true,
					gridLines: {
						color: "#f3f3f3",
						drawTicks: false,
					},
					scaleLabel: {
						display: true,
					}
				}],
				yAxes: [{
					display: true,
					gridLines: {
						color: "#f3f3f3",
						drawTicks: false,
					},
					scaleLabel: {
						display: true,
					},
					ticks: {
						beginAtZero: true
					}
				}]
			},
			title: {
				display: true,
				text: ''
			}
		};

		// Chart Data
		var chartData = {
			labels: app.days.labels,
			datasets: app.datasets
		};

		var config = {
			type: 'bar',

			// Chart Options
			options: chartOptions,

			data: chartData
		};

		// Create the chart
		var lineChart = new Chart(ctx, config);
	});
}


var oldContainer;
$("ol.nested_with_switch").sortable({
	group: 'nested',
	afterMove: function (placeholder, container) {
		if (oldContainer != container) {
			if (oldContainer)
				oldContainer.el.removeClass("active");
			container.el.addClass("active");

			oldContainer = container;
		}
	},
	onDrop: function ($item, container, _super) {
		container.el.removeClass("active");
		_super($item, container);
	}
});


$(document).ready(function () {

	if ($('#taches_calendar').length > 0) {

		$('#taches_calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'listWeek,month'
			},

			// customize the button names,
			// otherwise they'd all just say "list"
			views: {
				listDay: {
					buttonText: 'list day'
				},
				listWeek: {
					buttonText: 'Calendrier hebdomadaire'
				}
			},

			defaultView: 'listWeek',
			locale: 'fr',
			// defaultDate: '2018-03-12',
			navLinks: true, // can click day/week names to navigate views
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: app.graphs.ti_taches,
			// events: [
			// {
			// title: 'All Day Event',
			// start: '2018-03-01'
			// },
			// {
			// title: 'Long Event',
			// start: '2018-03-07',
			// end: '2018-03-10'
			// },
			// {
			// id: 999,
			// title: 'Repeating Event',
			// start: '2018-03-09T16:00:00'
			// },
			// {
			// id: 999,
			// title: 'Repeating Event',
			// start: '2018-03-16T16:00:00'
			// },
			// {
			// title: 'Conference',
			// start: '2018-03-11',
			// end: '2018-03-13'
			// },
			// {
			// title: 'Meeting',
			// start: '2018-03-12T10:30:00',
			// end: '2018-03-12T12:30:00'
			// },
			// {
			// title: 'Lunch',
			// start: '2018-03-12T12:00:00'
			// },
			// {
			// title: 'Meeting',
			// start: '2018-03-12T14:30:00'
			// },
			// {
			// title: 'Happy Hour',
			// start: '2018-03-12T17:30:00'
			// },
			// {
			// title: 'Dinner',
			// start: '2018-03-12T20:00:00'
			// },
			// {
			// title: 'Birthday Party',
			// start: '2018-03-13T07:00:00'
			// },
			// {
			// title: 'Click for Google',
			// url: 'http://google.com/',
			// start: '2018-03-28'
			// }
			// ]
		});
	}

});



updateTimeRelative = function () {
	$('time').each(function () {
		var $this = $(this);
		if ($this.data('time-rel-ignore'))
			return;
		var time = $this.attr('datetime');
		var timeM = moment(time);
		if (moment().diff(timeM, 'days') < 7) {
			var originalText = $this.data('time-rel-text') || $this.html();
			$this.data('time-rel-text', originalText);
			$this.attr('title', originalText)
			$this.html(timeM.fromNow());
		} else {
			$this.data('time-rel-ignore', true)
		}
	})
	setTimeout(updateTimeRelative, 15E3)
}
setTimeout(updateTimeRelative, 0);

$(function () {

	var start = moment().subtract(29, 'days');
	var end = moment();

	moment.locale('fr');
	var pickerLocale = {
		applyLabel: 'OK',
		cancelLabel: 'Annuler',
		fromLabel: 'Entre',
		toLabel: 'et',
		customRangeLabel: 'Période personnalisée',
		daysOfWeek: moment().localeData()._weekdaysMin,
		monthNames: moment().localeData()._months,
		firstDay: 0,
		format: 'YYYY-MM-DD'
	};
	var pickerRanges = {
		'Aujourd\'hui': [moment(), moment()],
		'Hier': [moment().subtract('days', 1), moment().subtract('days', 1)],
		'5 jours précédents': [moment().subtract('days', 4), moment().subtract('days', 1)],
		'Ce mois': [moment().startOf('month'), moment().endOf('month')],
		'Mois précedent': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
	};

	function cb(start, end) {
		$('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
		$('.dash-periode').val(start.format('YYYY-M-DD') + ' - ' + end.format('YYYY-M-DD'));


		$periode = $('.dash-periode').val();
		$niveau = $('.current-niveau').val();

		ajax('get', {
			op: 'dashboard-pedagogique',
			'niveau': $niveau,
			'periode': $periode
		}, function (data) {
			$('.analytics-item.dash-absences .analy-chiffre').html(data.absences.total);
			$('.analytics-item.dash-absences .analy-pct').removeClass('warning success');
			$('.analytics-item.dash-absences .analy-pct').addClass(data.absences.classe);
			$('.analytics-item.dash-absences .analy-pct').html(data.absences.variation);

			$('.analytics-item.dash-retards .analy-chiffre').html(data.retards.total);
			$('.analytics-item.dash-retards .analy-pct').removeClass('warning success');
			$('.analytics-item.dash-retards .analy-pct').addClass(data.retards.classe);
			$('.analytics-item.dash-retards .analy-pct').html(data.retards.variation);

			$('.analy-warning').html(data.warningHtml);


			$(data.selectorNiveau).html(data.niveauHtml);
			$('.inlinesparkline').sparkline('html', {
				width: '100%',
				height: '70px'
			});
			setTimeout(function () {
				$.sparkline_display_visible();
			}, 200);
		});
	}


	if ($('.dashboard-pedagogique').length > 0) {
		$('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			locale: pickerLocale,
			ranges: pickerRanges
		}, cb);

		cb(start, end);
	}

});




$('.dashboard-pedagogique .home-tabs .nav-link').click(function (e) {

	$this = $(this);
	$niveau = $this.data('change-niveau');
	$cycle = $this.data('cycle');

	$(".cycle-toggle [data-cycle='" + $cycle + "']").data('change-niveau', $niveau);

	$('.current-niveau').val($niveau);
	$periode = $('.dash-periode').val();
	$('.tab-niveau').html('');
	ajax('get', {
		op: 'dashboard-pedagogique',
		'periode': $periode,
		'niveau': $niveau,
		'samePeriode': true
	}, function (data) {

		$(data.selectorNiveau).html(data.niveauHtml);
		$('.inlinesparkline').sparkline('html', {
			width: '100%',
			height: '70px'
		});
		setTimeout(function () {
			$.sparkline_display_visible();
		}, 200);

	});
});

$(function () {
	$('.lazy-img').lazy();
});

// To style only selects with the my-select class
// $('.select-pointage').selectpicker();


if ($('.select-pointage-eleves').length > 0) {

	$('.loading').show();
	$selectInscription = $('.select-pointage-eleves');


	$(document).ready(function () {
		$('.select-pointage-eleves').on("change", function () {

			$this = $(this);

			ajax('get', {
				op: 'select-pointage-eleves',
				eleve: $this.val()
			}, function (data) {

				$('.eleve-infos').html(data.html);
			});

		});
	});

	$(document).on('click', '[data-pointage-eleve]', function (e) {

		$this = $(this);

		eleve = $this.data('pointage-eleve');

		ajax('get', {
			op: 'pointage-eleve',
			eleve: eleve

		}, function (data) {

			// $('.select-pointage-eleves').val('');
			// $('.select-pointage-eleves').focus();

			$('.select-pointage-eleves').select2('open');

			$('.eleve-infos').html('');
			swal({
				title: data.msg,
				icon: "success",
				timer: 700
			});

		}, function (msg, code) {

		}, function () {

		});
	});

}



$(".off-horaire-user").change(function () {
	$this = $(this);

	$horaireKey = $($this.data('horaire-key'));

	if ($this.is(':checked')) {
		$($horaireKey).prop("disabled", "disabled");
	} else {
		$($horaireKey).removeAttr("disabled", "disabled");
	}
});

$('.eleve-key-search-input').on('keyup', function (e) {

	nom = $(this).val();

	ajax('get', {
		op: 'eleve-key-search-input',
		'nom': nom
	}, function (data) {
		if (data.html) {
			$('.eleves_result').show();
			$('.eleves_result').html(data.html);
		} else {

			$('.eleves_result').hide();
		}
	});
});

$(document).on('click', '.eleve-modal-search', function (e) {

	e.preventDefault();
	eleve = $(this).data('id');

	ajax('get', {
		op: 'eleve-modal-infos',
		eleve: eleve
	}, function (data) {

		var dialog = bootbox.dialog({
			title: '',
			size: 'large',
			message: data.html,
			className: "popup-infos-eleve",
			buttons: {
				cancel: {
					label: "Fermer",
					className: 'btn btn-boti btn-block'
				}
			}
		});

	});
});


if ($(".fiche-eleve").lenght > 0) {

	$(".fiche-eleve .fiche-eleve-tabs a").click(function () {
		var position = $(this).parent().position();
		var width = $(this).parent().width();
		$(".fiche-eleve .slider_tab").css({
			"left": +position.left,
			"width": width
		});
	});
	var actWidth = $(".fiche-eleve .fiche-eleve-tabs").find(".active").parent("li").width();
	var actPosition = $(".fiche-eleve .fiche-eleve-tabs .active").position();
	$(".fiche-eleve .slider_tab").css({
		"left": +actPosition.left,
		"width": actWidth
	});
}


if ($("#meet_vc_boti").length) {
	navigator.mediaDevices.getUserMedia({
		video: true,
		audio: true
	})
		.then(stream => video.srcObject = stream)
		.catch(e => console.log(e.name + ": " + e.message));

	var domain = app.video_conference.serveur;
	var options = {
		roomName: app.video_conference.roomName,
		width: '100%',
		height: 500,
		parentNode: document.querySelector('#meet_vc_boti'),
		interfaceConfigOverwrite: {
			MOBILE_APP_PROMO: false,
			disableDeepLinking: true,
			DEFAULT_BACKGROUND: '#3c96ec',
			SHOW_CHROME_EXTENSION_BANNER: false
		},
		configOverwrite: {
			disableDeepLinking: true
		}
	}
	var api = new JitsiMeetExternalAPI(domain, options);
	api.executeCommand('displayName', app.video_conference.displayName);
	api.executeCommand('email', app.video_conference.email);
	api.executeCommand('avatarUrl', app.video_conference.avatarUrl)
}


var KTCalendarExternalEvents = {
	init: function () {
		var e, t, i, n, r, a, o;
		$("#kt_calendar_external_events .fc-draggable-handle").each(function () {
			$(this).data("event", {
				title: $.trim($(this).text()),
				stick: !0,
				classNames: [$(this).data("color")],
				description: ""
			})
		}), e = moment().startOf("day"), t = e.format("YYYY-MM"), i = e.clone().subtract(1, "day").format("YYYY-MM-DD"), n = e.format("YYYY-MM-DD"), r = e.clone().add(1, "day").format("YYYY-MM-DD"), a = document.getElementById("kt_calendar"), o = document.getElementById("kt_calendar_external_events"), new (0, FullCalendarInteraction.Draggable)(o, {
			itemSelector: ".fc-draggable-handle",
			eventData: function (e) {
				return $(e).data("event")
			}
		}), new FullCalendar.Calendar(a, {
			plugins: ["interaction", "dayGrid", "timeGrid", "list"],
			isRTL: false,
			header: false,
			// height: 800,
			// contentHeight: 780,
			aspectRatio: 3,
			hiddenDays: [0],
			now: n + "T09:25:00",
			views: {
				dayGridMonth: {
					buttonText: "month"
				},
				timeGridWeek: {
					buttonText: "week",
					columnFormat: 'ddd'
				},
				timeGridDay: {
					buttonText: "day"
				}
			},
			defaultView: "timeGridWeek",
			// defaultView: 'agendaWeek',
			defaultDate: n,
			droppable: !0,
			slotDuration: '00:15:00',
			lang: 'fr',
			locale: 'fr',
			// weekNumbers: true,
			allDaySlot: false,
			minTime: '07:00',
			maxTime: '19:00',
			editable: !0,
			defaultTimedEventDuration: '01:00',
			forceEventDuration: true,
			eventLimit: !0,
			// navLinks: !0,
			events: [],
			drop: function (e) {
				$("#kt_calendar_external_events_remove").is(":checked") && $(e.draggedEl).remove();
			},
			eventRender: function (e) {
				var t = $(e.el);
				e.event.extendedProps && e.event.extendedProps.description && (t.hasClass("fc-day-grid-event") ? (t.data("content", e.event.extendedProps.description), t.data("placement", "top"), false) : t.hasClass("fc-time-grid-event") ? t.find(".fc-title").append('<div class="fc-description">' + e.event.extendedProps.description + "</div>") : 0 !== t.find(".fc-list-item-title").lenght && t.find(".fc-list-item-title").append('<div class="fc-description">' + e.event.extendedProps.description + "</div>"))
			}
		}).render()
	}
};
jQuery(document).ready(function () {
	KTCalendarExternalEvents.init()
});


if ($("#use_stats_connexion").length > 0) {


	$(window).on("load", function () {

		var data = {
			labels: app.use_stats_connexion.labels,
			datasets: app.use_stats_connexion.datasets
		};

		var options = {
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			plugins: {
				filler: {
					propagate: false
				},
				'samples-filler-analyser': {
					target: 'chart-analyser'
				}
			}
		};


		var ctx = $("#use_stats_connexion");

		var chart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: options
		});
	});



}

if ($("#use_stats_contenu").length > 0) {


	$(window).on("load", function () {

		var data = {
			labels: app.use_stats_contenu.labels,
			datasets: app.use_stats_contenu.datasets
		};

		var options = {
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			plugins: {
				filler: {
					propagate: false
				},
				'samples-filler-analyser': {
					target: 'chart-analyser'
				}
			}
		};


		var ctx = $("#use_stats_contenu");

		var chart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: options
		});
	});



}

if ($("#use_stats_absences").length > 0) {


	$(window).on("load", function () {

		var data = {
			labels: app.use_stats_absences.labels,
			datasets: app.use_stats_absences.datasets
		};

		var options = {
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			plugins: {
				filler: {
					propagate: false
				},
				'samples-filler-analyser': {
					target: 'chart-analyser'
				}
			}
		};


		var ctx = $("#use_stats_absences");

		var chart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: options
		});
	});

}


if ($("#sessions-browser-donut-chart").length > 0) {
	Morris.Donut({
		element: "sessions-browser-donut-chart",

		data: app.repartition.data,
		resize: !0,
		colors: app.repartition.colors,
	})
}

$(document).on('submit', '.programmation_unite_coef', function (e) {

	e.preventDefault();
	params = $(this).serializeArray();
	ajax('post', params, function (data) {

		swal({
			title: data.msg,
			icon: "success",
			timer: 1000
		});

	});

});

$(document).on('submit', '.programmation_unite_matiere', function (e) {

	e.preventDefault();
	params = $(this).serializeArray();
	ajax('post', params, function (data) {

		swal({
			title: data.msg,
			icon: "success",
			timer: 1000
		});

		responseText

	});

});

$('.enable_unite_matiere').change(function () {
	var checked = $(this).is(":checked");
	var element = $($(this).data("element"), $(this).closest("tr"));

	if (checked) {
		element.prop('disabled', false);
	} else {
		element.prop('disabled', true);
	}

});

$('.btn_add_exam').on('click', function (e) {
	$this = $(this);

	$('.modal_examen_label').html($this.data('label'));
	$('#modal_examen_matiere').val($this.data('matiere'));
	$('#modal_examen_type').val($this.data('type'));
	$('#modal_examen_unite').val($this.data('unite'));

	// ensignant
	$('.enseignants_modal').html($('#enseignants_unite_' + $this.data('unite')).html());

	$('.btn_add_exam').removeClass("examen_added");
	$(this).addClass("examen_added");
});

$(document).on('submit', '.form_add_examen', function (e) {

	e.preventDefault();
	params = $(this).serializeArray();
	ajax('post', params, function (data) {

		$('#add-exam').modal('toggle');

		$('.examen_added').closest('td').html(data.html);

		swal({
			title: data.msg,
			icon: "success",
			timer: 1000
		});

	});

});


$('.saisie_notes_classe').change(function (e) {
	$('.saisie_notes_unite').val(null);
	$(".saisie_notes_eval").val(null);
	$(this).closest('form').submit();
});

$('.saisie_notes_semestre').change(function (e) {
	$('.saisie_notes_unite').val(null);
	$(".saisie_notes_eval").val(null);
	$(this).closest('form').submit();
});

$('.saisie_notes_unite').change(function (e) {
	$('.saisie_notes_eval option').hide();
	$(".saisie_notes_eval option[data-unite='" + $(this).val() + "']").show();

}).change();


// multiple exam
$('.add_multiple_exams').on('click', function () {

	$this = $(this);
	var unite = $this.data('unite');
	$('.modal_examen_multiple_label').html($this.data('label'));
	$('#modal_examen_multiple_unite').val(unite);

	// thead

	var thead = $('#thead_unite_' + unite).clone();
	thead.show();
	$('#table_add_muliple_exam').find('thead').html('');
	$('#table_add_muliple_exam').find('thead').append(thead);
	// tbody

	$('#table_add_muliple_exam').find('tbody').html('');
	$('.matieries_of_unite_' + unite).each(function (index, el) {
		cel = $(el).clone();
		cel.show();
		$('#table_add_muliple_exam').find('tbody').append(cel)
	});

	// ensignant
	$('.enseignants_modal').html($('#enseignants_unite_' + unite).html());


});


$(document).on('submit', '#form_multiple_exam', function (e) {
	e.preventDefault();
	const data = $(this).serializeArray();
	$.ajax(
		{
			method: 'post',
			url: $(this).attr('action'),
			data: data,
		}
	).done(function (data) {
		data = JSON.parse(data);
		swal({
			title: data.data.msg,
			icon: data.data.status,
			timer: 1000
		}).then(function () {
			location.reload();
		});
	});
});


$('.mutiple_grille_note_max').on('change', function () {
	console.log('ok');
	const _this = $(this);
	const value = Number(_this.val())
	const max = Number(_this.attr('max'));
	console.log(value);
	if (value > max) {
		_this.css('border', '1px solid red');
	} else {
		_this.css('border', 'none');

	}
});


$('.tab_key').keypress(function (e) {
	if (e.which == 13) {
		e.preventDefault();
		$this = $(this);
		$td_index = $this.closest('td').index();
		$tr = $this.closest('tr');
		$nex_tr = $tr.next('tr');
		$nex_tr.find('td').eq($td_index).find('input').focus();
	}
});


///////////// Fiche eleve //////////////////
$('.fiche-perso-new').on('click', function () {
	const container = $('#fiche-perso-form');
	container.find('#fiche_perso_pk').attr('name', 'id').val('');
	container.find('textarea[name=details]').val('');
});

$('.fiche-perso-edit').on('click', function () {
	_this = $(this);
	const container = $('#fiche-perso-form');
	container.find('#fiche_perso_pk').attr('name', 'id').val(_this.data('id'));
	container.find('select[name=type]').val(_this.data('type')).trigger('change');
	container.find('textarea[name=details]').val(_this.data('details'));

});

$('.fiche-suivi-new').on('click', function () {
	_this = $(this);
	const container = $('#fiche-suivi-form');
	container.find('#fiche_suivi_pk').attr('name', 'id').val('');
	container.find('textarea[name=details]').val('');
});


$('.fiche-suivi-edit').on('click', function () {
	_this = $(this);
	const container = $('#fiche-suivi-form');
	container.find('#fiche_suivi_pk').attr('name', 'id').val('');
	container.find('select[name=type]').val(_this.data('type')).trigger('change');
	container.find('textarea[name=details]').val(_this.data('details'));
	container.find("input[name=flag][value=" + _this.data('flag') + "]").attr('checked', 'checked');
});





$('.docs-edit').on('click', function () {
	_this = $(this);

	console.log("hello world");

	const container = $('#docs-form');
	container.find('#docs_pk').attr('name', 'id').val('');
	container.find('select[name=type]').val(_this.data('type')).trigger('change');
	container.find('textarea[name=details]').val(_this.data('details'));
	container.find("input[name=flag][value=" + _this.data('flag') + "]").attr('checked', 'checked');
});



$('.delete-action').on('click', function () {
	_this = $(this);
	_action = _this.data('action');
	_closest = _this.data('closest');
	swal({
		title: "Êtes-vous sûr?",
		text: "Vous ne pourrez pas récupérer!",
		icon: "warning",
		buttons: [
			'Non, annulez-le!',
			'Oui je suis sûr!'
		],
		dangerMode: true,
	}).then(function (isConfirm) {
		if (isConfirm) {
			$.ajax(
				{
					method: 'post',
					url: _action,
				}
			).done(function (data) {
				_this.closest(_closest).remove();
			}).fail(function (data) {
				swal({
					title: data.responseText,
					icon: "warning",
					dangerMode: true,
				});
			});
		} else {
			swal("Annulé", "", "error");
		}
	});

});




////////////////// Export Notes Massar ////////////////

function getQuery() {
	query = location.search.slice(1).split("&");
	return query.map(function (value) {
		return value.split("=");
	});
}
$('#export-massar-notes').find('button:first').on('click', function () {
	$('#massar_file_container').show();
	$('.progress-bar-export').hide();
});

$('#export-notes-massar-form').on('submit', function (e) {
	e.preventDefault(); _this = $(this);
	var formData = new FormData(_this[0]);
	getQuery().forEach(function (val) {
		formData.append(val[0], val[1])
	});
	//animation 
	var dynelmts = 8;
	var scntr = 8;
	var spinner = document.getElementById("percent");
	var lshc = document.getElementById("lshc");
	var val = document.getElementById("spinnervalue");
	var rside = document.getElementById("rs");
	var pwrap = document.getElementById("pie");
	$('#massar_file_container').hide();
	$('.progress-bar-export').show();

	$.ajax({
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					console.log(percentComplete);
					var p = percentComplete;
					var pf = percentComplete * 100;
					var PtoD = (p * 360);
					val.innerHTML = pf + '<span class="smaller">%</span>';
					if (PtoD < 180) {
						spinner.style.webkitTransform = "rotate(" + PtoD + "deg)";
					}
					else {
						pwrap.className = "pie over50p";
						spinner.style.webkitTransform = "rotate(" + PtoD + "deg)";
						lshc.style.webkitTransform = "rotate(" + PtoD + "deg)";
						rside.style.webkitTransform = "rotate(180deg)";
					}
				}
			}, false);
			xhr.addEventListener("progress", function (evt) {

				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					var p = percentComplete;
					var pf = percentComplete * 100;
					var PtoD = (p * 360);
					val.innerHTML = pf + '<span class="smaller">%</span>';
					if (PtoD < 180) {
						spinner.style.webkitTransform = "rotate(" + PtoD + "deg)";
					}
					else {
						pwrap.className = "pie over50p";
						spinner.style.webkitTransform = "rotate(" + PtoD + "deg)";
						lshc.style.webkitTransform = "rotate(" + PtoD + "deg)";
						rside.style.webkitTransform = "rotate(180deg)";
					}
				}

			}, false);

			return xhr;
		},
		type: 'post',
		url: _this.attr('action'),
		data: formData,
		cache: false,
		processData: false,
		contentType: false,

	}).done(function (_data) {
		const data = JSON.parse(_data);
		$('.progress-bar-export-finish').find('a').attr('href', data['link']);
		$('.progress-bar-export').hide();
		$('.progress-bar-export-finish').show();
	});

});


//////////// //////////////////////
$('#form-niveau').on('change', function () {
	console.log("ok");
	$this = $(this);
	$.ajax({
		url: $this.data('api') + $this.val()
	}).done(function (res) {
		$($this.data('ele')).html(res);
	})
});


$('#form-matiere').on('change', function () {
	console.log("ok");
	$this = $(this);
	$.ajax({
		url: $this.data('api') + $this.val(),
		data: {
			classes: $('#classe').val()
		}
	}).done(function (res) {
		$($this.data('ele')).html(res);
	})
});


$('#classe').on('change', function () {
	console.log("ok");
	$this = $(this);
	$.ajax({
		url: $this.data('api'),
		data: {
			classes: $this.val()
		}
	}).done(function (res) {
		$($this.data('ele')).html(res);
	})
});



