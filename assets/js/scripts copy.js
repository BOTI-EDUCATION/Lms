/* ------------------------------ Required Functions */
function ajax(type, params, callback, error, complete, upload, link) {
  if (!error)
    error = function (msg, code) {
      if (code) console.log('Ajax Error Code: ' + code)
      alert(msg)
    }
  if (!complete) complete = $.noop
  $.ajax({
    url: link ? link : app.url.base + 'ajax',
    type: type,
    data: params,
    cache: !upload ? true : false,
    contentType: !upload
      ? 'application/x-www-form-urlencoded; charset=UTF-8'
      : false,
    processData: !upload ? true : false,
    success: function (response) {
      try {
        response = $.parseJSON(response)
      } catch (e) {
        error("Une erreur s'est produite", 2)
        return false
      }
      if (response.type != 'OK' && response.type != 'ERR') {
        error("Une erreur s'est produite", 3)
        return false
      }
      if (response.type == 'ERR') {
        error(response.msg, response.code)
        return false
      }

      callback.call(this, response.data)
    },
    error: function () {
      error.call(this, "Une erreur s'est produite", 1)
    },
    complete: function () {
      complete.call(this)
    },
  })
}

$(document).on('submit', '.send_as_ajax', function (e) {
  e.preventDefault()

  _this = $(this)
  const reload = _this.data('reload')
  const href = _this.data('href')
  const closest = _this.data('closest')
  const modal = _this.data('modal')
  const fnc = _this.data('fnc')
  var formData = new FormData(this)

  var submitBtn = _this.find('button[type="submit"]').length
    ? _this.find('button[type="submit"]')
    : _this.find('input[type="submit"]')
  const html_loading =
    '<i class="fa fa-spinner fa-spin" style="margin:3px"></i>  Loading'
  const old_html = submitBtn.html()
  submitBtn.html(html_loading)

  $.ajax({
    method: _this.attr('method'),
    url: _this.attr('action'),
    data: formData,
    cache: false,
    processData: false,
    contentType: false,
  })
    .done(function (_data) {
      submitBtn.html(old_html)
      const data = JSON.parse(_data)
      swal({
        title: data.msg,
        icon: 'success',
        timer: 2000,
      }).then(function () {
        if (reload) {
          location.reload()
        }
        if (href) {
          location.href = href
        }
        if (closest) {
          _this.closest(closest).remove()
        }
        if (modal) {
          $(modal).modal('hide')
        }
        if (fnc) {
          window[fnc](_this, data)
        }
      })
    })
    .fail(function (data) {
      submitBtn.html(old_html)
      data = JSON.parse(data.responseText)
      swal({
        icon: 'error',
        title: data.msg,
      })
    })
})
$(document).on('click', '.form_action', function (e) {

  e.preventDefault()
  closest_form = $(this).closest('form-action');
  const reload = closest_form.data('reload')
  const href = closest_form.data('href')
  const closest = closest_form.data('closest')
  const modal = closest_form.data('modal')
  const fnc = closest_form.data('fnc')


  // append input value data to fromData
  var formData = new FormData()
  closest_form.find('input').each(function (i, ele) {
    let input = $(ele);
    let type = input.attr('type');

    if ('checkbox' == type) {
      if (input.is(':checked')) {
        formData.append(input.attr('name'), input.val());
      }
    } else {
      formData.append(input.attr('name'), input.val());
    }

  });

  closest_form.find('textarea').each(function (i, ele) {
    console.log("hello world");
    formData.append($(ele).attr('name'), $(ele).val());
  });

  // add loading
  var submitBtn = closest_form.find('button[type="submit"]').length
    ? closest_form.find('button[type="submit"]').last()
    : closest_form.find('input[type="submit"]').last()
  const html_loading = '<i class="fa fa-spinner fa-spin" style="margin:3px"></i>  Loading';
  const old_html = submitBtn.html();
  submitBtn.html(html_loading);

  $.ajax({
    method: closest_form.attr('method') || 'GET',
    url: closest_form.attr('action') || '',
    data: formData,
    cache: false,
    processData: false,
    contentType: false,
  })
    .done(function (_data) {
      submitBtn.html(old_html)
      const data = JSON.parse(_data)
      swal({
        title: data.msg,
        icon: 'success',
        timer: 2000,
      }).then(function () {
        if (reload) {
          location.reload()
        }
        if (href) {
          location.href = href
        }
        if (closest) {
          closest_form.closest(closest).remove()
        }
        if (modal) {
          $(modal).modal('hide')
        }
        if (fnc) {
          window[fnc](closest_form, data)
        }
      })
    })
    .fail(function (data) {
      submitBtn.html(old_html)
      data = JSON.parse(data.responseText)
      swal({
        icon: 'error',
        title: data.msg,
      })
    })
});

$(document).on('click', '.click_ajax', function (e) {
  console.log('called')
  _this = $(this)
  const fnc = _this.data('fnc')
  var data = _this.data('data') ? _this.data('data') : {}
  $.ajax({
    method: 'GET',
    url: _this.data('action'),
    data: data,
  })
    .done(function (_data) {
      const data = JSON.parse(_data)
      if (fnc) {
        window[fnc](_this, data)
      }
    })
    .fail(function (data) {
      data = JSON.parse(data.responseText)
      swal({
        title: data.msg,
      })
    })
})

$(document).on('click', '.dropify', function () {
  $(this).closest('form').find('input[name="delete_image"]').remove()
})

$(document).on('click', '.dropify-clear', function () {
  $('<input />')
    .attr('name', 'delete_image')
    .attr('type', 'hidden')
    .val(1)
    .appendTo($(this).closest('form'))
})

$(document).on('submit', '.one_submit', function (e) {
  $(this).find('[type="submit"]').attr('disabled', true)
  return true
})

$(document).on('change', '.toggle-checkbox', function (e) {
  _this = $(this)
  $(_this.data('target')).prop('checked', _this.is(':checked'))
});

$(document).on('click', '.reste_input', function (e) {
  _this = $(this);
  $(_this.data('target')).val($(_this.data('input')).val())
});


function getQuery() {
  query = location.search.slice(1).split('&')
  return query.map(function (value) {
    return value.split('=')
  })
}

function getValueOfInput(input) {
  const type = input.attr('type')
  if ('checkbox' == type) {
    return input.is(':checked')
  }
  return input.val()
}

// Auto Update Translations
function ajaxUpdateConfig(url, id, value) {
  // Prevent closing the page before the ajax query ends
  window.ajaxUpdates = window.ajaxUpdates || 0
  // Increment for each ajax call
  window.ajaxUpdates++
  $.ajax({
    url: url,
    type: 'post',
    data: {
      op: 'config-update',
      id: id,
      value: value,
    },
    success: function (response) {
      // Decrement when ajax call ends
      window.ajaxUpdates--
      try {
        response = $.parseJSON(response)
      } catch (e) {
        alert("Une erreur s'est produite (2)")
        return false
      }
      if (response.type != 'OK' && response.type != 'ERR') {
        alert("Une erreur s'est produite (3)")
        return false
      }
      if (response.type == 'ERR') {
        alert(response.msg + ' (4)')
        return false
      }

      console.log('Operations left: ' + window.ajaxUpdates)
    },
    error: function () {
      // Decrement when ajax call ends
      window.ajaxUpdates--
      alert("Une erreur s'est produite (1)")
      return false
    },
  })
}

$(document).ready(function () {
  $('.colorpicker').each(function () {
    //
    // Dear reader, it's actually very easy to initialize MiniColors. For example:
    //
    //  $(selector).minicolors();
    //
    // The way I've done it below is just for the demo, so don't get confused
    // by it. Also, data- attributes aren't supported at this time...they're
    // only used for this demo.
    //
    $(this).minicolors({
      control: $(this).attr('data-control') || 'hue',
      defaultValue: $(this).attr('data-defaultValue') || '',
      format: $(this).attr('data-format') || 'hex',
      keywords: $(this).attr('data-keywords') || '',
      inline: $(this).attr('data-inline') === 'true',
      letterCase: $(this).attr('data-letterCase') || 'lowercase',
      opacity: $(this).attr('data-opacity'),
      position: $(this).attr('data-position') || 'bottom left',
      swatches: $(this).attr('data-swatches')
        ? $(this).attr('data-swatches').split('|')
        : [],
      change: function (value, opacity) {
        if (!value) return
        if (opacity) value += ', ' + opacity
        if (typeof console === 'object') {
          console.log(value)
        }
      },
    })
  })
})


function domTopPdf(el, name, format = 'a3') {
  var opt = {
    margin: 0.2,
    html2canvas: {
      scale: 1.2,
    },
    filename: name + '.pdf',
    jsPDF: { unit: 'in', format: format, orientation: 'p' },
    pagebreak: { before: '.breakpage' },
  }
  html2pdf().set(opt).from(el).save()
}

// params
$(function () {
  $('.params-auto-update').on('change', function (e) {
    $this = $(this)
    value = getValueOfInput($this)
    ajaxUpdateConfig('params', $this.data('item'), value)
  })
})

function linkParamToTab(tab, param) {
  console.log(tab)
  $.ajax({
    action: 'params',
    method: 'post',
    data: {
      op: 'link_params',
      tab: tab,
      param: param,
    },
  }).done(function () {
    console.log('ok')
  })
}

function paramsAllowDrop(ev) {
  ev.preventDefault()
}

function paramsDrag(ev) {
  console.log('drag :' + ev.target.id)
  ev.dataTransfer.setData('text', ev.target.id)
}
function paramsDrop(ev) {
  ev.preventDefault()
  var data = ev.dataTransfer.getData('text')
  console.log('drop :' + data)
  let to = $(ev.target.getAttribute('href'))
  let ele = $('#' + data)
  to.append(ele)
  linkParamToTab(to.data('tab'), ele.data('param'))
}
// end params

$(function () {
  $('.config-auto-update')
    .not('editor_html')
    .each(function (i, v) {
      $this = $(this)
      $this.data('val', $this.val())
    })
    .on('blur', function (e) {
      $this = $(this)
      if ($this.data('val') == $this.val()) return
      ajaxUpdateConfig('configs', $this.data('item'), $this.val())
      $this.data('val', $this.val())
    })
})

$(window).load(function () {
  for (i = 0; i < $.summernote.length; i++) {
    editor = $.summernote.get(i)
    $element = $(editor.getElement())

    if (!$element.is('.config-auto-update')) continue
    $element.data('val', $(this).summernote('code'))

    editor.on('blur', function (e) {
      editor = this

      $element = $(editor.getElement())
      if ($element.data('val') == $(this).summernote('code')) return

      ajaxUpdateConfig(
        'configs',
        $element.data('item'),
        $(this).summernote('code')
      )
      $element.data('val', $(this).summernote('code'))
    })
  }
})
window.onbeforeunload = function () {
  if (window.ajaxUpdates > 0) {
    return 'Une opération est en cours de traitement, veuillez patienter svp'
  }
}

/* ------------------------------ Plugin Defaults */
// Extend the default picker options for all instances.

var initPlugins = function (context) {
  /* ------------------------------ DataTable */
  var initDataTable = function (selector, options) {
    if (!$().dataTable) return false

    $table = $(selector)
    if (!$table.length) return false
    options = $.extend(
      true,
      {
        // set the initial value
        language: {
          sProcessing: 'Traitement en cours...',
          sSearch: 'Rechercher&nbsp;:',
          sLengthMenu: 'Afficher _MENU_ &eacute;l&eacute;ments',
          sInfo:
            "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          sInfoEmpty:
            "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
          sInfoFiltered:
            '(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)',
          sInfoPostFix: '',
          sLoadingRecords: 'Chargement en cours...',
          sZeroRecords: 'Aucun &eacute;l&eacute;ment &agrave; afficher',
          sEmptyTable: 'Aucune donn&eacute;e disponible dans le tableau',
          oPaginate: {
            sFirst: "<i class='fa fa-angle-double-left'></i>",
            sPrevious: "<i class='fa fa-angle-left'></i>",
            sNext: "<i class='fa fa-angle-right'></i>",
            sLast: "<i class='fa fa-angle-double-right'></i>",
          },
          oAria: {
            sSortAscending:
              ': activer pour trier la colonne par ordre croissant',
            sSortDescending:
              ': activer pour trier la colonne par ordre d&eacute;croissant',
          },
        },
        pageLength: 10,
        pagingType: 'full_numbers',
        columnDefs: [
          {
            // set default column settings
            orderable: false,
            targets: ['no-sort'],
          },
        ],
        order: [], // set first column as a default sort by asc
        lengthMenu: [
          [10, 25, 50, 100, -1],
          [10, 25, 50, 100, 'Tous'],
        ],
      },
      options
    )
    $table.dataTable(options)

    return $table
  }

  $.each($('.datatable', context), function (ind, element) {
    $table = initDataTable(element)

    $tableWrapper = '#' + $table.attr('id') + '_wrapper'

    if ($('.group-checkable', $table).length) {
      table.find('.group-checkable').change(function () {
        var set = jQuery(this).attr('data-set')
        var checked = jQuery(this).is(':checked')
        jQuery(set).each(function () {
          if (checked) {
            $(this).prop('checked', true)
            $(this).parents('tr').addClass('active')
          } else {
            $(this).prop('checked', false)
            $(this).parents('tr').removeClass('active')
          }
        })
        jQuery.uniform.update(set)
      })

      table.on('change', 'tbody tr .checkboxes', function () {
        $(this).parents('tr').toggleClass('active')
      })
    }
  })

  /* ------------------------------ Select2 */
  $('.select2', context).each(function () {
    var $this = $(this)
    $this.select2({
      placeholder: $this.data('placeholder'),
      allowClear: $this.data('allow-clear'),
      minimumResultsForSearch:
        $this.data('show-search') || $this.find('option').length > 6
          ? 0
          : Infinity,
    })
  })

  /* ------------------------------ multiSelect */
  $('.multiselect', context).multiSelect({
    selectableHeader:
      "<input type='text' class='form-control' autocomplete='off' placeholder='Rechercher'>",
    selectionHeader:
      "<input type='text' class='form-control' autocomplete='off' placeholder='Rechercher'>",
    afterInit: function (ms) {
      var that = this,
        $selectableSearch = that.$selectableUl.prev(),
        $selectionSearch = that.$selectionUl.prev(),
        selectableSearchString =
          '#' +
          that.$container.attr('id') +
          ' .ms-elem-selectable:not(.ms-selected)',
        selectionSearchString =
          '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected'

      that.qs1 = $selectableSearch
        .quicksearch(selectableSearchString)
        .on('keydown', function (e) {
          if (e.which === 40) {
            that.$selectableUl.focus()
            return false
          }
        })

      that.qs2 = $selectionSearch
        .quicksearch(selectionSearchString)
        .on('keydown', function (e) {
          if (e.which == 40) {
            that.$selectionUl.focus()
            return false
          }
        })
    },
    afterSelect: function (value) {
      $('.multiselect option[value="' + value + '"]').remove()
      $('.multiselect').append(
        $('<option></option>').attr('value', value).attr('selected', 'selected')
      )
      this.qs1.cache()
      this.qs2.cache()
    },
    afterDeselect: function () {
      this.qs1.cache()
      this.qs2.cache()
    },
  })

  /* ------------------------------ Date time picker */

  moment.locale('fr')
  var pickerLocale = {
    applyLabel: 'OK',
    cancelLabel: 'Annuler',
    fromLabel: 'Entre',
    toLabel: 'et',
    customRangeLabel: 'Période personnalisée',
    daysOfWeek: moment().localeData()._weekdaysMin,
    monthNames: moment().localeData()._months,
    firstDay: 0,
    format: 'YYYY-MM-DD',
  }
  var pickerRanges = {
    "Aujourd'hui": [moment(), moment()],
    Hier: [moment().subtract('days', 1), moment().subtract('days', 1)],
    '5 jours précédents': [
      moment().subtract('days', 4),
      moment().subtract('days', 1),
    ],
    'Ce mois': [moment().startOf('month'), moment().endOf('month')],
    'Mois précedent': [
      moment().subtract('month', 1).startOf('month'),
      moment().subtract('month', 1).endOf('month'),
    ],
  }

  $('#periode-range').daterangepicker({
    showDropdowns: false,
    ranges: pickerRanges,
    format: 'DD-MM-YYYY',
    separator: ' à ',
    locale: pickerLocale,
  })

  $('.datepicker').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'fr',
    weekStart: 1,
  })

  $('.timepicker-24,.timepicker', context).timepicker({
    autoclose: !0,
    minuteStep: 5,
    showSeconds: !1,
    showMeridian: !1,
  })

  /* ------------------------------ Full Calendar */
  var initialLangCode = 'fr'

  $('.calendar-cours', context).each(function () {
    var $this = $(this)

    $this.fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay',
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
      defaultTimedEventDuration: '00:10',
      allDaySlot: false,
      eventLimit: true, // allow "more" link when too many events
      events: $this.data('events'),
      eventClick: function (calEvent, jsEvent, view) {
        ajax(
          'get',
          {
            op: 'cours-infos',
            cours: calEvent.id,
          },
          function (data) {
            var dialog = bootbox.dialog({
              title: '',
              message: data.html,
              className: 'popup-infos-cours',
              buttons: {
                cancel: {
                  label: 'Fermer',
                  className: 'btn btn-boti btn-block',
                },
              },
            })
          }
        )
      },
      eventRender: function (event, element) {
        $(element).tooltip({
          title: event.title,
        })
      },
    })
  })

  /* ------------------------------ Full Calendar new EDY*/
  var initialLangCode = 'fr'
  $('.calendar-edt').each(function () {
    var $this = $(this)

    $this.fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay',
      },
      defaultDate: $this.fullCalendar('today'),
      displayEventTime: false,
      lang: $this.data('lang') ? $this.data('lang') : initialLangCode,
      locale: $this.data('lang') ? $this.data('lang') : initialLangCode,
      buttonIcons: false, // show the prev/next text
      weekNumbers: false,
      columnFormat: 'ddd',
      hiddenDays: [0],
      minTime: $this.data('mintime') ? $this.data('mintime') : '08:00',
      maxTime: $this.data('maxtime') ? $this.data('maxtime') : '19:00',
      editable: $this.data('editable'),
      defaultView: $this.data('view') ? $this.data('view') : 'agendaWeek',
      defaultTimedEventDuration: '00:10',
      allDaySlot: false,
      eventLimit: true, // allow "more" link when too many events
      events: $this.data('events'),
      eventClick: function (calEvent, jsEvent, view) {
        ajax(
          'get',
          {
            op: 'edt-infos',
            seance: calEvent.id,
          },
          function (data) {
            var dialog = bootbox.dialog({
              title: '',
              message: data.html,
              className: 'popup-infos-cours',
              buttons: {
                cancel: {
                  label: 'Fermer',
                  className: 'btn btn-boti btn-block',
                },
              },
            })
          }
        )
      },
      eventRender: function (event, element) {
        $(element).tooltip({
          title: event.title,
        })
      },
    })
  })

  /* ------------------------------ Full Calendar new EDY*/
  var initialLangCode = 'fr'
  $('.calendar-suivi').each(function () {
    var $this = $(this)

    $this.fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay',
      },
      defaultDate: $this.fullCalendar('today'),
      displayEventTime: false,
      lang: $this.data('lang') ? $this.data('lang') : initialLangCode,
      locale: $this.data('lang') ? $this.data('lang') : initialLangCode,
      buttonIcons: false, // show the prev/next text
      weekNumbers: false,
      columnFormat: 'ddd',
      hiddenDays: [0],
      minTime: $this.data('mintime') ? $this.data('mintime') : '08:00',
      maxTime: $this.data('maxtime') ? $this.data('maxtime') : '19:00',
      editable: $this.data('editable'),
      defaultView: $this.data('view') ? $this.data('view') : 'agendaWeek',
      slotDuration: '00:10:00',
      allDaySlot: false,
      eventLimit: true, // allow "more" link when too many events
      events: $this.data('events'),
      eventClick: function (calEvent, jsEvent, view) {
        ajax(
          'get',
          {
            op: 'edt-infos',
            seance: calEvent.id,
          },
          function (data) {
            console.log(data)
            var dialog = bootbox.dialog({
              title: '',
              message: data.html,
              className: 'popup-infos-cours',
              buttons: {
                cancel: {
                  label: 'Fermer',
                  className: 'btn btn-boti btn-block',
                },
              },
            })
          },
          null,
          null,
          null,
          $this.data('action') + calEvent.id
        )
      },
      eventRender: function (event, element) {
        $(element).tooltip({
          title: event.title,
        })
        $(element).html(event.html)
      },
    })
  })

  $('#timeline_pdf').on('click', function () {
    var source = window.document.getElementById('timeline')
    // before clone
    $('.calendar-cours').fullCalendar('option', {
      slotDuration: '00:10:00',
    })
    var el = source.cloneNode(true)

    var new_ele = document.createElement('div')
    $(new_ele).append(
      '<div class="header" style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"></div></div>'
    )
    $(
      "<div style='margin-top:-17px'> <img style='width: 145px;' src='" +
      $('.navbar-header').find('img').attr('src') +
      "'/></div>"
    ).appendTo($(new_ele).find('.header').find('.logo').html(''))
    if (
      $('#filtre-classe').find('option[selected]').length &&
      !$('#filtre-enseignant').find('option[selected]').length
    )
      $(new_ele).append(
        '<div class="header" style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"> Emploi de temps de la classe <b> ' +
        $('#filtre-classe').find('option[selected]').html() +
        '</b> </div></div>'
      )
    if (
      $('#filtre-enseignant').find('option[selected]').length &&
      !$('#filtre-classe').find('option[selected]').length
    )
      $(new_ele).append(
        '<div class="header" style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"> Emploi de temps de la Enseignant  <b>' +
        $('#filtre-enseignant').find('option[selected]').html() +
        '</b> </div></div>'
      )
    if (
      $('#filtre-enseignant').find('option[selected]').length &&
      $('#filtre-classe').find('option[selected]').length
    )
      $(new_ele).append(
        '<div class="header" style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"> Emploi de temps de la classe : <b>' +
        $('#filtre-classe').find('option[selected]').html() +
        '</b> , enseignant : <b>' +
        $('#filtre-enseignant').find('option[selected]').html() +
        '</b> </div></div>'
      )

    //after clone
    // $(el).find('.fc-toolbar').css("padding", "21px");
    // $("<div style='margin-top:-17px'> <img style='width: 145px;' src='" + $('.navbar-header').find('img').attr('src') + "'/></div>").appendTo($(el).find('.fc-left').html(""))
    // $(el).find('.fc-right').html("<b style='margin-top: 12px;'>" + ($('#filtre-classe').find('option[selected]').html()) + "</b>");

    $('.calendar-cours').fullCalendar('option', {
      slotDuration: '00:30:00',
    })

    // $(el).find(".fc-title").css('padding', '.55rem .55rem .55rem 2rem');
    // $(el).find(".fc-title").css('font-size', '17px');
    // $(el).find(".fc-title").css('color', '#000');
    // $(el).find(".fc-title").css('font-weight', 'bold');

    new_ele.append(el)
    var opt = {
      margin: 0.5,
      html2canvas: {
        scale: 1,
      },
      filename:
        'Emploi_temps_groupe_' +
        $('#filtre-classe').find('option[selected]').html() +
        '.pdf',
      jsPDF: { unit: 'in', format: 'a2', orientation: 'p' },
      //jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape', compressPDF: true }
    }
    html2pdf().set(opt).from(new_ele).save()
  })

  $('#etat_cheque_pdf').on('click', function () {
    $('.datatable').DataTable().page.len(-1).draw()

    var source = document.querySelector($(this).data('ele'))
    var el = source.cloneNode(true)
    var new_ele = document.createElement('div')
    $(new_ele).append(
      '<div class="header" style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"></div><div class="peroide" style="padding:5px;"></div><div class="banque" style="padding:5px;"></div></div>'
    )
    $(
      "<div style='margin-top:-17px'> <img style='width: 145px;' src='" +
      $('.navbar-header').find('img').attr('src') +
      "'/></div>"
    ).appendTo($(new_ele).find('.header').find('.logo').html(''))
    $(new_ele)
      .find('.banque')
      .html(
        "<b style='margin-top: 12px;'>" +
        $('select[name="banque"]').find('option[selected]').html() +
        '</b>'
      )
    $(new_ele)
      .find('.peroide')
      .html(
        "<b style='margin-top: 12px;'> Période :" +
        $('input[name="periode"]').val() +
        '</b>'
      )
    $(new_ele).append(el)

    domTopPdf(new_ele, $(this).data('pdf-name'))
  })

  $('#facture_gloabl_pdf').on('click', function () {
    var source = document.querySelector($(this).data('ele'))
    var el = source.cloneNode(true)
    _this = $(this)
    $(el).show()
    $(el)
      .find('.header')
      .append(
        '<div style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"></div> <div style="border-radius: 39px;padding: 33px;text-align: left;margin: 32px 0px 19px 0px;max-width: 300px;font-size: 17px;" class="details"> </div> <div class="nemuro_facture" style="padding:15px;text-align:center;display:flex;max-width:300px;margin:0 auto;font-size:15px;text-decoration: underline;"> </div>  <div class="promotion" style="float:right;padding:15px"> </div> </div>'
      )
    $(
      "<div style='margin-top:-17px'> <img style='width: 250px;' src='" +
      $('.navbar-header').find('img').attr('src') +
      "'/></div>"
    ).appendTo($(el).find('.header').find('.logo').html(''))
    $(el).find('.details').html($(this).data('details'))
    $(el).find('.promotion').html($(this).data('promotion'))
    $(el)
      .find('.nemuro_facture')
      .html('<b>' + $(this).data('nemuro_facture') + '</b>')

    if ($(el).find('.nemuro_facture').length > 1) {
      $(el)
        .find('.nemuro_facture')
        .last()
        .html('<b>' + $(this).data('nemuro_second_facture') + '</b>')
    }
    domTopPdf(el, _this.data('pdf-name'))
    $.ajax({
      method: 'post',
      url: _this.data('action'),
      data: { op: 'facture_global_increment_nemuro' },
    })
    _this.attr('disabled', true)
  })

  $('#etat_impayer_pdf').on('click', function () {
    $('.datatable').DataTable().page.len(-1).draw()
    var source = document.querySelector($(this).data('ele'))
    var el = source.cloneNode(true)
    var new_ele = document.createElement('div')

    $(new_ele).append(
      '<div class="header" style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"></div> <div style=" border-radius: 39px;border: 1px solid #626a68;padding: 33px;text-align: center;margin: 32px auto 19px auto;max-width: 621px;font-size: 17px;" class="details"> </div> <div class="total" style="float:left;padding:15px"> </div> <div class="promotion" style="float:right;padding:15px"> </div></div>'
    )
    $(
      "<div style='margin-top:-17px'> <img style='width: 145px;' src='" +
      $('.navbar-header').find('img').attr('src') +
      "'/></div>"
    ).appendTo($(new_ele).find('.header').find('.logo').html(''))
    $(new_ele).find('.total').html($(this).data('total'))
    $(new_ele).find('.details').html($(this).data('details'))
    $(new_ele).find('.promotion').html($(this).data('promotion'))
    $(new_ele).append(el)
    $(new_ele).find('table').css('border', '2px solid')
    $(new_ele).find('.hide-col').css('display', 'none')
    $(new_ele).append($('#total_payer'))
    domTopPdf(new_ele, $(this).data('pdf-name'))
  })

  $('#etat_inscription_service_pdf').on('click', function () {
    $('.datatable').DataTable().page.len(-1).draw()

    var source = document.querySelector($(this).data('ele'))
    var el = source.cloneNode(true)
    var new_ele = document.createElement('div')

    $(new_ele).append(
      '<div class="header" style="margin:20px"><div class="logo" style="text-align:center;padding:5px;"></div> <div style=" border-radius: 39px;border: 1px solid #626a68;padding: 33px;text-align: center;margin: 32px auto 19px auto;max-width: 621px;font-size: 17px;" class="details"> </div> <div class="total" style="float:left;padding:15px"> </div> <div class="promotion" style="float:right;padding:15px"> </div></div>'
    )
    $(
      "<div style='margin-top:-17px'> <img style='width: 145px;' src='" +
      $('.navbar-header').find('img').attr('src') +
      "'/></div>"
    ).appendTo($(new_ele).find('.header').find('.logo').html(''))
    $(new_ele).find('.total').html($(this).data('total'))
    $(new_ele).find('.details').html($(this).data('details'))
    $(new_ele).find('.promotion').html($(this).data('promotion'))
    $(new_ele).append(el)
    $(new_ele).find('table').css('border', '2px solid')
    domTopPdf(new_ele, $(this).data('pdf-name'))
  })

  // build the language selector's options
  $('.calendar-lang-selector', context)
    .each(function () {
      var $this = $(this)

      $.each($.fullCalendar.langs, function (langCode) {
        $this.append(
          $('<option/>')
            .attr('value', langCode)
            .prop('selected', langCode == initialLangCode)
            .text(langCode)
        )
      })
    })
    .on('change', function () {
      if (this.value) {
        $('.calendar').fullCalendar('option', 'lang', this.value)
      }
    })

  /* ------------------------------ Utilities */
  // Show element(form-group/control/...) only if a select's value matches the given value(s)
  $('.js-visible-select-value', context).each(function () {
    $control = $(this)
    $control.hide().removeClass('hidden')
    $context = $control.closest($control.data('vsv-context'))
    if ($context.length == 0) $context = undefined
    $select = $($control.data('vsv-select'), $context)
    attr = $control.data('vsv-attr')
    values = $control.data('vsv-values')
    if (!Array.isArray(values)) values = [values]
    if ('undefined' == typeof $select.data('vsv-targets')) {
      $select.data('vsv-targets', [])
      $select.change(function () {
        var $this = $(this)
        var targets = $this.data('vsv-targets')
        for (i = 0, ic = targets.length; i < ic; i++) {
          var target = targets[i]
          var val = parseFloat($this.val())
          if (isNaN(val) || val != $this.val()) val = $this.val()
          if ('undefined' != typeof target.attr)
            val = $this.find('option:selected').data(target.attr)
          if ($.inArray(val, target.values) > -1) target.control.show().focus()
          else target.control.hide()
        }
      })
    }
    $select.data('vsv-targets').push({
      control: $control,
      attr: attr,
      values: values,
    })
    $select.change()
  })

  $('.selector-eleves', context).each(function () {
    var $select = $(this)
    $select
      .select2({
        // dropdownParent: this.selectAssets.closest('.modal'),
        placeholder: $select.data('placeholder')
          ? $select.data('placeholder')
          : '',
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
            }
          },
          processResults: function (data, params) {
            params.page = data.page

            return {
              results: data.items,
              pagination: {
                more: params.page * data.resultsPerPage < data.totalCount,
              },
            }
          },
        },
      })
      .on('change', function (e) {
        if (!this.value) {
          e.preventDefault()
          return
        }
      })
  })
}
initPlugins()

$('.js-save-ordre').on('click', function () {
  $table = $($(this).data('target'))
  targetClass = $table.data('class')
  Redirect = $table.data('redirect')
  data = {}
  $table.find('tbody tr').each(function () {
    $tr = $(this)
    id = $tr.data('id')
    ordre = $('.js-ordre', $tr).text()
    data[id] = ordre
  })

  ajax(
    'post',
    {
      op: 'save-ordre-global',
      class: targetClass,
      ordres: data,
    },
    function (data) {
      window.location.href = Redirect
    }
  )
})

/* ------------------------------ Attachments */
$('.form-champ')
  .change(function (e) {
    $parentForm = $(this).parents('form')
    valSelect = false
    $selectedOption = $('option:selected', this)
    val = $selectedOption.val()
    if (val == 'select') valSelect = true
    $('.form-group-reponse', $parentForm).toggle(valSelect)
  })
  .change()

$('.form .event-date #datestart, .form .event-date #dateend')
  .change(function () {
    var startDate = $('.form .event-date #datestart').val()
    var endDate = $('.form .event-date #dateend').val()
    $('.form .event-time').toggle(
      !!startDate && !!endDate && startDate == endDate
    )
  })
  .change()

$('.form-post-access #accesstype')
  .change(function () {
    var val = $(this).val()
    $('.form-post-access .classes-selector').toggle(val == 'classes')
    $('.form-post-access .eleves-selector').toggle(val == 'eleves')
  })
  .change()

$('.classe-inscriptions').click(function (e) {
  $this = $(this)
  $selectInscription = $($this.data('classe-inscriptions'))

  if (!$this.data('classe')) {
    $selectInscription
      .empty()
      .append($(document.createElement('option')).attr('value', 'all').text(''))
      .change()
    return
  }

  ajax(
    'get',
    {
      op: 'classe-eleves',
      classe: $this.data('classe'),
    },
    function (data) {
      $selectInscription.empty()
      if (!data.inscriptions.length) {
        $selectInscription
          .append(
            $(document.createElement('option')).attr('value', 'all').text('')
          )
          .change()
        return
      }
      $selectInscription.append(
        $(document.createElement('option')).attr('value', 'all').text('')
      )
      for (i = 0; i < data.inscriptions.length; i++) {
        classeId = data.inscriptions[i].id
        classeLabel = data.inscriptions[i].label

        $option = $(document.createElement('option'))
          .attr('value', classeId)
          .text(classeLabel)
        $selectInscription.append($option)
      }

      $('.modal-inscription-respo-label').text(data.classe.label)
      $('.modal-inscription-respo-id').val(data.classe.id)

      // if($selectInscription.data('classe'))
      // $selectInscription.val($selectInscription.data('classe'));
      // $selectInscription.change();
    }
  )
})

$('.select-responsable').change(function (e) {
  $this = $(this)

  ajax(
    'get',
    {
      op: 'responsable-classe-infos',
      eleve: $(this).val(),
    },
    function (data) {
      $('.responsable-infos').html(data.html)
    }
  )
})

var drEvent = $('.dropify').dropify({
  defaultFile: '',
  maxFileSize: 0,
  minWidth: 0,
  maxWidth: 0,
  minHeight: 0,
  maxHeight: 0,
  showRemove: !0,
  showLoader: !0,
  showErrors: !0,
  errorsPosition: 'overlay',
  allowedFormats: ['portrait', 'square', 'landscape'],
  messages: {
    default: 'Glissez-déposez une image ici ou cliquez',
    replace: 'Glissez-déposez une image ici ou cliquez',
    remove: 'Supprimer',
    error:
      "Désolé, le fichier trop volumineux ou bien Le format n'est pas autorisé.",
  },
  error: {
    fileSize: 'Désolé, le fichier trop volumineux. ({{ value }} max).',
    minWidth: 'La largeur de l image est trop petite ({{ value }}}px min).',
    maxWidth: 'La largeur de l image est trop grande ({{ value }}}px max).',
    minHeight: 'La hauteur de l image est trop petite ({{ value }}}px min).',
    maxHeight: 'La taille de l image est trop grande ({{ value }}px max).',
    imageFormat:
      'Le format d image n est pas autorisé ({{ value }} seulement).',
  },
  tpl: {
    wrap: '<div class="dropify-wrapper"></div>',
    loader: '<div class="dropify-loader"></div>',
    message:
      '<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',
    preview:
      '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',
    filename:
      '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
    clearButton:
      '<button type="button" class="dropify-clear">{{ remove }}</button>',
    errorLine: '<p class="dropify-error">{{ error }}</p>',
    errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>',
  },
})

drEvent.on('dropify.afterClear', function (event, element) {
  // alert('File deleted');
})

$('.delete').click(function (e) {
  if (
    !confirm(
      'vous êtes sur le point de supprimer cet objet\r\nVoulez-vous continuer ?'
    )
  ) {
    e.preventDefault()
  }
})

$(document).ready(function () {
  $('.confirm-link').on('click', function (e, data) {
    if (!data) {
      handleDelete(e, 1, $(this), $(this).data('message'))
    } else {
      window.location = $(this).attr('href')
    }
  })
})

function handleDelete(e, stop, obj, message) {
  if (stop) {
    e.preventDefault()
    swal({
      title: 'Êtes-vous sûr ?',
      text: message,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'OK',
      cancelButtonText: 'Annuler !',
      closeOnConfirm: false,
    }).then((willDelete) => {
      if (willDelete) {
        obj.trigger('click', {})
      } else {
      }
    })
  }
}

$('.rest_form').on('click', function () {
  console.log($(this).closest('form'))
  $(this).closest('form').trigger('reset')
  $(this).closest('form').find('.select2').select2().val('').trigger('change')
})

$('.mode-paiement')
  .change(function (e) {
    $selectedOption = $('option:selected', this)
    dataOption = $selectedOption.data('alias')
    $('.paiementmode').slideUp()

    if (dataOption == 'virement') {
      $('.paiementmode.virement').slideDown()
    } else if (dataOption == 'cheque') {
      $('.paiementmode.cheque').slideDown()
    } else if (dataOption == 'tpe') {
      $('.paiementmode.tpe').slideDown()
    }
  })
  .change()

/* Promotion Classes */

$('.get-promotion-classes')
  .change(function (e) {
    $this = $(this)

    $selectClasse = $($this.data('selector'))
    if (!$this.val()) {
      $selectClasse
        .empty()
        .append(
          $(document.createElement('option')).attr('value', 'all').text('')
        )
        .change()
      return
    }

    ajax(
      'get',
      {
        op: 'get-promotion-classes',
        promotion: $this.val(),
      },
      function (data) {
        $selectClasse.empty()
        if (!data.classes.length) {
          // $selectClasse
          // .append($(document.createElement('option')).attr('value', 'all').text(''))
          // .change();
          return
        }
        // $selectClasse.append($(document.createElement('option')).attr('value', '').text(''));
        for (i = 0; i < data.classes.length; i++) {
          etudiantId = data.classes[i].id
          etudiantLabel = data.classes[i].label

          $option = $(document.createElement('option'))
            .attr('value', etudiantId)
            .text(etudiantLabel)
          $selectClasse.append($option)
        }

        if ($selectClasse.data('inscription'))
          $selectClasse.val($selectClasse.data('inscription'))
        $selectClasse.change()
      }
    )
  })
  .change()

/* Promotion Classes */

/* Generation Lignes (generique) START */

$(document).ready(function () {
  $('.gen-rows').each(function () {
    var $wrapper = $(this)

    var $tpl = $('.tpl-gen-row', $wrapper)
    var $container = $('.gen-rows-container', $wrapper)
    var $btnAdd = $('.js-btn-add-gen-row', $wrapper)

    $container.find('.js-btn-remove-gen-row').click(function (e) {
      var $this = $(this)
      $this.closest('.gen-row').remove()
      $wrapper.trigger('gen-rows-changed').trigger('gen-row-removed')
    })

    $wrapper.on('gen-rows-changed', function (e) {
      $rows = $container.find('.gen-row')
      $btnsRemove = $rows.find('.js-btn-remove-gen-row')
      removeEnabled = $rows.length > 1
      if (removeEnabled)
        $btnsRemove.removeAttr('disabled').removeClass('disabled')
      else $btnsRemove.attr('disabled', '').addClass('disabled')
    })

    $btnAdd
      .click(function (e) {
        $row = $($tpl.html())
        console.log($row)
        initPlugins($row)
        $row.find('.js-btn-remove-gen-row').click(function (e) {
          var $this = $(this)
          $this.closest('.gen-row').remove()
          $wrapper.trigger('gen-rows-changed').trigger('gen-row-removed')
        })
        $container
          .append($row)
          .trigger('gen-rows-changed')
          .trigger('gen-row-added', {
            row: $row,
          })
      })
      .trigger('click')
  })
})
/* Generation Lignes (generique) END */

/* Generation cours START old */
// var calcEncaissementTotalMontant = function () {
// 	$this = $('.gen-rows-encaissement')
// 	totalMontants = 0;
// 	$this.find('.montant').each(function () {
// 		val = $(this).val() * 1; // *1 to convert to number
// 		totalMontants += val;
// 	})
// 	$('.js-total-montants').text(totalMontants)
// }
// $('.gen-rows-encaissement').on('gen-rows-changed', function (e) {
// 	calcEncaissementTotalMontant();
// });
// $('.gen-rows-encaissement').on('change', '.montant', function (e) {
// 	calcEncaissementTotalMontant();
// });
/* Generation cours END */

/* Generation cours START old */
var calcEncaissementTotalMontant = function (ele) {
  totalMontants = 0
  $(ele).each(function (index, item) {
    if ($(item).is(':checked')) {
      val = parseInt($(item).data('price'))
      totalMontants += val
    }
  })
  $('.js-total-montants').text(totalMontants)
}

var calcEncaissementServices = function (ele) {
  $('.form-eleve-seleted-services').html('')
  console.log($('.form-eleve-seleted-servicse'))
  let count = 0
  $(ele).each(function (index, item) {
    if ($(item).is(':checked')) {
      const price = $(item).data('price')
      const label = $(item).data('label')
      $('.form-eleve-seleted-services').append(
        "<div style='padding:7px'>" + label + '(<b>' + price + '</b>)</div>'
      )
      count++
    }
  })
  if (count == 0)
    $('.form-eleve-seleted-services').html(
      '<div style="text-align:center;padding:15px">Aucun service sélectionné </div>'
    )
}
// click event
$('.montant').on('click', function (e) {
  calcEncaissementTotalMontant('.montant')
  calcEncaissementServices('.montant')
})

$('.on-change-append-data').on('change', function () {
  _this = $(this)
  const ele = _this.data('ele')
  const dataKey = _this.data('key')
  const val = _this.val()
  $(ele).data(dataKey, val)
  calcEncaissementTotalMontant('.montant')
  calcEncaissementServices('.montant')
})

calcEncaissementTotalMontant('.montant')
calcEncaissementServices('.montant')
/* Generation cours END */

/* Generation cours START */
var $genCoursTpl = $('.tpl-gen-cours')
var $genCoursContainer = $('.generate-cours')
var $genCoursBtnAdd = $('.js-btn-add-cours')
if ($genCoursContainer.length) {
  $genCoursContainer.on('cours-changed', function (e) {
    $cours = $genCoursContainer.find('.js-cours')
    $btnsRemove = $cours.find('.js-btn-remove-cours')
    removeEnabled = $cours.length > 1
    if (removeEnabled)
      $btnsRemove.removeAttr('disabled').removeClass('disabled')
    else $btnsRemove.attr('disabled', '').addClass('disabled')

    limit = 6

    if (limit > 0) {
      limitRow = $cours.length > limit
      if (limitRow) $genCoursBtnAdd.hide()
      else $genCoursBtnAdd.show()
    }
  })
  $genCoursBtnAdd
    .click(function (e) {
      $html = $($genCoursTpl.html())
      initPlugins($html)
      $html.find('.js-btn-remove-cours').click(function (e) {
        var $this = $(this)
        $this.closest('.js-cours').remove()
        $genCoursContainer.trigger('cours-changed')
      })
      $genCoursContainer.append($html).trigger('cours-changed')
    })
    .trigger('click')
}

$('.js-btn-remove-generated-cours').click(function () {
  var $this = $(this)
  var count = $this.closest('tbody').children().length
  $this.closest('tr').remove()
  $('.js-cours-count').text(count - 1)
})
/* Generation cours END */

$('.add-parrainage-choice').click(function (e) {
  e.preventDefault()
  $this = $(this)
  dataOption = $this.data('type')

  $('.parrainage-form').slideUp()

  $('.parrainage-form' + dataOption).slideDown()
})

/* Forms Validation */
if ($.validate) {
  $.validate({
    modules: 'location, date, security, file',
  })
}

$('.generate-alias').on('keyup', function (e) {
  label = $(this).val()

  ajax(
    'get',
    {
      op: 'generate-alias',
      label: label,
    },
    function (data) {
      $('.alias-input').val(data.alias)
    }
  )
})

if ($('#trigger-overlay-detail').length) {
  ; (function () {
    ' use strict '
    var triggerBttnDebouche = document.getElementById('trigger-overlay-detail'),
      overlayDeoubche = document.querySelector('#overlay-detail'),
      closeBttnDebouche = overlayDeoubche.querySelector('a.overlay-close')

      ; (transEndEventNames = {
        WebkitTransition: 'webkitTransitionEnd',
        MozTransition: 'transitionend',
        OTransition: 'oTransitionEnd',
        msTransition: 'MSTransitionEnd',
        transition: 'transitionend',
      }),
        (transEndEventName =
          transEndEventNames[Modernizr.prefixed('transition')]),
        (support = {
          transitions: Modernizr.csstransitions,
        })

    function toggleOverlay() {
      if (classie.has(overlayDeoubche, 'open')) {
        //ADD SCROLL TO BODY WHEN POPUP HIDDEN
        $('body').removeClass('no-scroll')
        classie.remove(overlayDeoubche, 'open')
        classie.add(overlayDeoubche, 'close')
        var onEndTransitionFn = function (ev) {
          if (support.transitions) {
            if (ev.propertyName !== 'visibility') return
            this.removeEventListener(transEndEventName, onEndTransitionFn)
          }
          classie.remove(overlayDeoubche, 'close')
        }
        if (support.transitions) {
          overlayDeoubche.addEventListener(transEndEventName, onEndTransitionFn)
        } else {
          onEndTransitionFn()
        }
      } else if (!classie.has(overlayDeoubche, 'close')) {
        // REMOVE SCROLL FROM BODY WHEN POPUP LOADED
        $('body').addClass('no-scroll')
        classie.add(overlayDeoubche, 'open')
      }
    }

    triggerBttnDebouche.addEventListener('click', toggleOverlay)
    closeBttnDebouche.addEventListener('click', toggleOverlay)
  })()
}

if ($.fn.slimScroll) {
  $('.scroller-content').slimScroll({
    size: '5px',
    position: 'right',
    color: '#E21D35',
    disableFadeOut: false,
    height: '400px',
  })
}

$('.post-detail').on('click', function (e) {
  var l = document.getElementById('trigger-overlay-detail')
  l.click()

  $this = $(this)
  $('.content-details').html('')
  ajax(
    'get',
    {
      op: 'timeline-details',
      post: $(this).data('post'),
    },
    function (data) {
      $('.content-details').html(data.html)
      $('.scroller-content').slimScroll({
        scrollTo: '0px',
      })
    }
  )
})

$(document).on('click', '[data-login-card]', function (e) {
  $this = $(this)

  card = $this.data('login-card')

  $('.card-login form').hide()
  $('.card-login form' + card).show()
})

// Absences Start
$('#table-absences .absence').change(function (e) {
  let $this = $(this)
  let $row = $this.closest('tr')
  let $table = $row.closest('#table-absences')

  let idClasse = $table.data('classe')
  let date = $table.data('date')
  let idInscription = $row.data('inscription')
  let periode = $this.data('periode')
  let absent = $this.prop('checked')

  let retardInput = $('#retard-' + idInscription + '-' + periode)
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
    absent: absent,
  })
})

$('#table-absences .retard').change(function (e) {
  $this = $(this)
  $row = $this.closest('tr')
  $table = $row.closest('#table-absences')

  idClasse = $table.data('classe')
  date = $table.data('date')
  idInscription = $row.data('inscription')
  periode = $this.data('periode')
  val = $this.val()

  ajax('POST', {
    op: 'absences-retard',
    classe: idClasse,
    date: date,
    inscription: idInscription,
    periode: periode,
    minutes: val,
  })
})

$('.js-discipline-btn').click(function () {
  idInscription = $(this).closest('tr').data('inscription')
  actions = $(this).closest('tr').data('actions')
  $modal = $('#discipline-modal')
  $modal.find('#inscription').val(idInscription)
  $modal.find('#cours').val('').change()
  $modal.find('#type').val('').change()
  $table = $modal.find('.table-actions')
  if (actions.length == 0) {
    $table.addClass('hidden')
  } else {
    $body = $table.find('tbody').empty()
    tplHtml = $table.find('template').html()
    totalValeurs = 0
    actions.forEach(function (action) {
      totalValeurs += action.valeur * 1
      $row = $(tplHtml)
      $row.data('id', action.id)
      $row.attr('class', 'action-' + action.id)
      $row.find('.cours').text(action.cours)
      $row.find('.type').text(action.type)
      $row.find('.valeur').text(action.valeur)
      $row.find('.js-btn-delete').click(function () {
        let $row = $(this).closest('tr')
        let idAction = $row.data('id')
        ajax(
          'POST',
          {
            op: 'absences-discipline-delete',
            action: idAction,
          },
          function (result) {
            $rowInscription = $(
              '#table-absences .inscription-' + result.idInscription
            )
            let totalValeurs = 0
            result.actions.forEach(function (action) {
              totalValeurs += action.valeur * 1
            })
            $rowAction = $('.action-' + result.idAction)
            $tableActions = $rowAction.closest('table')
            $tableActions.find('.total-valeurs').text(totalValeurs)
            if (totalValeurs == 0) $tableActions.hide()
            $rowAction.remove()
            refreshRowTotalDiscipline($rowInscription, result.actions)
            console.log($rowInscription, result.actions)
          }
        )
      })
      $body.append($row)
    })
    $table.find('.total-valeurs').text(totalValeurs)
    $table.removeClass('hidden')
  }
  $modal.modal()
})

$('.seance-js-discipline-btn').click(function () {
  idInscription = $(this).closest('tr').data('inscription')
  actions = $(this).closest('tr').data('actions')
  $modal = $('#discipline-modal')
  $modal.find('#inscription').val(idInscription)
  $modal.find('#type').val('').change()
  $table = $modal.find('.table-actions')
  if (actions.length == 0) {
    $table.addClass('hidden')
  } else {
    $body = $table.find('tbody').empty()
    tplHtml = $table.find('template').html()
    totalValeurs = 0
    actions.forEach(function (action) {
      totalValeurs += action.valeur * 1
      $row = $(tplHtml)
      $row.data('id', action.id)
      $row.attr('class', 'action-' + action.id)
      $row.find('.cours').text(action.cours)
      $row.find('.type').text(action.type)
      $row.find('.valeur').text(action.valeur)
      $row.find('.js-btn-delete').click(function () {
        let $row = $(this).closest('tr')
        let idAction = $row.data('id')
        ajax(
          'POST',
          {
            op: 'absences-discipline-delete',
            action: idAction,
          },
          function (result) {
            $rowInscription = $(
              '#table-absences .inscription-' + result.idInscription
            )
            let totalValeurs = 0
            result.actions.forEach(function (action) {
              totalValeurs += action.valeur * 1
            })
            $rowAction = $('.action-' + result.idAction)
            $tableActions = $rowAction.closest('table')
            $tableActions.find('.total-valeurs').text(totalValeurs)
            if (totalValeurs == 0) $tableActions.hide()
            $rowAction.remove()
            refreshRowTotalDiscipline($rowInscription, result.actions)
            console.log($rowInscription, result.actions)
          }
        )
      })
      $body.append($row)
    })
    $table.find('.total-valeurs').text(totalValeurs)
    $table.removeClass('hidden')
  }
  $modal.modal()
})

$('.form-discipline #type').change(function (e) {
  $this = $(this)
  score = $('option:selected', $this).data('score')
  $('.type-action-score')
    .text((score > 0 ? '+' : '') + score)
    .toggle(score != 0)
    .toggleClass('tag-success', score > 0)
    .toggleClass('tag-danger', score < 0)
    .removeClass('hidden')
})

$('.form-discipline').submit(function (e) {
  e.preventDefault()
  $form = $(this)
  idInscription = $('#inscription', $form).val()
  idCours = $('#cours', $form).val()
  idType = $('#type', $form).val()
  date = $('#date', $form).val()
  commentaire = $('#commentaire', $form).val()

  ajax(
    'POST',
    {
      op: 'absences-discipline-add',
      inscription: idInscription,
      cours: idCours,
      type: idType,
      date: date,
      commentaire: commentaire,
    },
    function (result) {
      $row = $('.inscription-' + result.idInscription)
      $row.data('actions', result.actionsData)
      refreshRowTotalDiscipline($row, result.actionsData)
      $('#discipline-modal').modal('hide')
    }
  )
})

refreshRowTotalDiscipline = function ($row, actions) {
  if ('undefined' != typeof actions) {
    $row.data('actions', actions)
  } else {
    actions = $row.data('actions')
  }
  // Fill actions in the html
  if (actions.length == 0) {
    $('.actions', $row).hide().removeClass('hidden')
    $('.noactions', $row).removeClass('hidden').show()
  } else {
    $('.noactions', $row).hide().removeClass('hidden')
    totalValeurs = 0
    actions.forEach((action) => {
      totalValeurs += action.valeur * 1
    })
    $('.actions .js-discipline-total-valeurs', $row)
      .text(totalValeurs)
      .closest('.btn')
      .toggleClass('btn-success', totalValeurs > 0)
      .toggleClass('btn-danger', totalValeurs < 0)
      .toggleClass('btn-info', totalValeurs == 0)
    $('.actions', $row).removeClass('hidden').show()
  }
}
// Absences End

if ($('#quiz').length > 0) {
  var all_questions = app.quiz.questions

  // Objet Quiz Contien Plusieur Objet Questions
  var Quiz = function (quiz_name) {
    // Nom de quiz (Lot)
    this.quiz_name = quiz_name

    // Array of Questions
    this.questions = []
  }

  // add Question to Quiz
  Quiz.prototype.add_question = function (question) {
    // Randomly choose where to add question
    var index_to_add_question = Math.floor(
      Math.random() * this.questions.length
    )
    this.questions.splice(index_to_add_question, 0, question)
  }

  Quiz.prototype.render = function (container) {
    var self = this

    $('#quiz-results').hide()

    $('#quiz-name').text(this.quiz_name)

    // Create a container for questions
    var question_container = $('<div>')
      .attr('id', 'question')
      .insertAfter('#quiz-name')

    function change_question() {
      self.questions[current_question_index].render(question_container)
      $('#prev-question-button').prop('disabled', current_question_index === 0)
      $('#next-question-button').prop(
        'disabled',
        current_question_index === self.questions.length - 1
      )

      var all_questions_answered = true
      for (var i = 0; i < self.questions.length; i++) {
        if (self.questions[i].user_choice_index === null) {
          all_questions_answered = false
          break
        }
      }
      $('#submit-button').prop('disabled', !all_questions_answered)
    }

    var current_question_index = 0
    change_question()

    $('#prev-question-button').click(function () {
      if (current_question_index > 0) {
        current_question_index--
        change_question()
      }
    })

    $('#submit-button').click(function () {
      var score = 0
      for (var i = 0; i < self.questions.length; i++) {
        if (
          self.questions[i].user_choice_index ===
          self.questions[i].correct_choice_index
        ) {
          score++
        }
      }

      var percentage = score / self.questions.length
      console.log(percentage)
      var message
      if (percentage === 1) {
        message = 'Bravo <i class="fa fa-smile-o" aria-hidden="true"></i>'
      } else if (percentage >= 0.75) {
        message = 'Bravo <i class="fa fa-smile-o" aria-hidden="true"></i>'
      } else if (percentage >= 0.5) {
        message =
          'Réessayer une autre fois. <i class="fa fa-star-half-o" aria-hidden="true"></i>'
      } else {
        message =
          'Réessayer une autre fois. <i class="fa fa-star-half-o" aria-hidden="true"></i>'
      }
      $('#quiz-results-message').html(message)
      $('#quiz-results-score').html(
        'Votre Score : <b>' + score + '/' + self.questions.length + '</b>'
      )
      $('#quiz-results').slideDown()
      $('#quiz a').slideDown()
    })

    // Add a listener on the questions container to listen for user select changes. This is for determining whether we can submit answers or not.
    question_container.bind('user-select-change', function () {
      var all_questions_answered = true
      for (var i = 0; i < self.questions.length; i++) {
        if (self.questions[i].user_choice_index === null) {
          all_questions_answered = false
          break
        }
      }

      if (
        self.questions[current_question_index].user_choice_index ===
        self.questions[current_question_index].correct_choice_index
      ) {
        $('input[name=choices]:checked + label').css({
          'background-color': 'green',
          color: '#fff',
        })
      } else {
        $('input[name=choices]:checked + label').css({
          'background-color': '#e13034',
        })
      }

      if (current_question_index < self.questions.length - 1) {
        current_question_index++

        setTimeout(function () {
          change_question()
        }, 2000)
      } else {
        setTimeout(function () {
          $('#submit-button').trigger('click')
        }, 2000)
      }
    })
  }

  var Question = function (
    question_string,
    img_src,
    correct_choice,
    wrong_choices
  ) {
    this.question_string = question_string
    this.img_src = img_src
    this.choices = []
    this.user_choice_index = null

    this.correct_choice_index = Math.floor(
      Math.random() * wrong_choices.length + 1
    )

    var number_of_choices = wrong_choices.length + 1
    for (var i = 0; i < number_of_choices; i++) {
      if (i === this.correct_choice_index) {
        this.choices[i] = correct_choice
      } else {
        var wrong_choice_index = Math.floor(
          Math.random(0, wrong_choices.length)
        )
        this.choices[i] = wrong_choices[wrong_choice_index]

        wrong_choices.splice(wrong_choice_index, 1)
      }
    }
  }

  Question.prototype.render = function (container) {
    var self = this

    var question_string_h2
    if (container.children('h2').length === 0) {
      question_string_h2 = $('<h2>').appendTo(container)
    } else {
      question_string_h2 = container.children('h2').first()
    }
    question_string_h2.html(this.question_string)

    if (!this.img_src)
      $('p', question_string_h2).css({
        position: 'absolute',
        top: '130px',
        top: '130px',
        left: '0',
        right: '130px',
        right: '0',
        'font-size': '50px',
      })

    var img_question
    if (container.children('img').length === 0) {
      img_question = $('<img>').appendTo(container)
    } else {
      img_question = container.children('img').first()
    }

    if (this.img_src)
      img_question.attr('src', this.img_src).attr('class', 'img-responsive')

    if (container.children('input[type=radio]').length > 0) {
      container.children('input[type=radio]').each(function () {
        var radio_button_id = $(this).attr('id')
        $(this).remove()
        container.children('label[for=' + radio_button_id + ']').remove()
      })
    }
    for (var i = 0; i < this.choices.length; i++) {
      var choice_radio_button = $('<input>')
        .attr('id', 'choices-' + i)
        .attr('type', 'radio')
        .attr('name', 'choices')
        .attr('value', 'choices-' + i)
        .attr('checked', i === this.user_choice_index)
        .appendTo(container)

      // Create the label
      var choice_label = $('<label>')
        .text(this.choices[i])
        .attr('for', 'choices-' + i)
        .appendTo(container)
    }

    $('input[name=choices]').change(function (index) {
      var selected_radio_button_value = $('input[name=choices]:checked').val()

      self.user_choice_index = parseInt(
        selected_radio_button_value.substr(
          selected_radio_button_value.length - 1,
          1
        )
      )

      container.trigger('user-select-change')
    })
  }

  $(document).ready(function () {
    var quiz = new Quiz(app.quiz.name)

    for (var i = 0; i < all_questions.length; i++) {
      var question = new Question(
        all_questions[i].question_string,
        all_questions[i].img_src,
        all_questions[i].choices.correct,
        all_questions[i].choices.wrong
      )

      quiz.add_question(question)
    }

    var quiz_container = $('#quiz')
    quiz.render(quiz_container)
  })
}

// POINTAGE //

$('.code-pointage').on('keyup', function (e) {
  code = $(this).val()
  if (code.substring(code.length - 1) == '$') {
    $(this).val('')
    ajax(
      'get',
      {
        op: 'pointage',
        idEleve: code,
      },
      function (data) {
        $('.loading').hide()
        swal({
          title: data.msg,
          icon: 'success',
          timer: 700,
        })
        $('.code-pointage').val('')
        $('.code-pointage').focus()
      },
      function (msg, code) {
        $('.error').show()
      },
      function () {
        $('.fields').show()
      }
    )
  }
})

$('.code-pointage-collaborateur').on('keyup', function (e) {
  code = $(this).val()
  if (code.substring(code.length - 1) == '$') {
    $(this).val('')
    ajax(
      'get',
      {
        op: 'pointage-collaborateur',
        idUser: code,
      },
      function (data) {
        $('.loading').hide()
        swal({
          title: data.msg,
          icon: 'success',
          timer: 1500,
        })
        $('.code-pointage-collaborateur').val('')
        $('.code-pointage-collaborateur').focus()
      },
      function (msg, code) {
        $('.error').show()
      },
      function () {
        $('.fields').show()
      }
    )
  }
})

// $('.pointage').click(function(e) {

// $('.fields').hide();
// $('.loading').show();

// $this = $(this);

// idEleve = $('.code-pointage').val();

// });

$(document).scroll(function () {
  if ($(document).scrollTop() > 10) {
    if (!$('.navbar-container').hasClass('bg-white')) {
      $('.navbar-container').addClass('bg-white')
    }
  } else {
    if ($('.navbar-container').hasClass('bg-white')) {
      $('.navbar-container').removeClass('bg-white')
    }
  }
})

$('.editable').each(function (i, v) {
  $this = $(this)


  editor = new MediumEditor($this, {
    buttonLabels: 'fontawesome',
    toolbar: {
      buttons: [
        'bold',
        'italic',
        'underline',
        'justifyLeft',
        'justifyCenter',
        'justifyRight',
        'quote',
        'anchor',
        'image',
        'orderedlist',
        'unorderedlist',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'html',
      ],
      static: true,
      sticky: true,
    },
    placeholder: {
      /* This example includes the default options for placeholder,
         if nothing is passed this is what it used */
      text: 'Tapez votre texte',
      hideOnClick: true,
    },
    paste: {
      /* This example includes the default options for paste,
         if nothing is passed this is what it used */
      forcePlainText: true,
      cleanPastedHTML: true,
      cleanReplacements: [],
      cleanAttrs: ['class', 'style', 'dir'],
      cleanTags: ['meta'],
      unwrapTags: [],
    },
  })

  editor.subscribe('editableBlur', function (event, editorElement) {
    let allContents = editor.serialize()
    let content = allContents[event.srcElement.id].value
    $('#' + event.srcElement.id + ' + .medium-editor-hidden').val(content)
  })

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
    addons: {
      // (object) Addons configuration
      images: {
        // (object) Image addon configuration
        label: '<span class="fa fa-camera"></span>', // (string) A label for an image addon
        deleteScript: 'delete.php', // (string) A relative path to a delete script
        deleteMethod: 'POST',
        fileDeleteOptions: {}, // (object) extra parameters send on the delete ajax request, see http://api.jquery.com/jquery.ajax/
        preview: true, // (boolean) Show an image before it is uploaded (only in browsers that support this feature)
        captions: true, // (boolean) Enable captions
        captionPlaceholder: 'Type caption for image (optional)', // (string) Caption placeholder
        autoGrid: 3, // (integer) Min number of images that automatically form a grid
        fileUploadOptions: {
          // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
          url: app.url.base + 'upload-editor.php',
          acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i, // (regexp) Regexp of accepted file types
        },
        styles: {
          // (object) Available image styles configuration
          wide: {
            // (object) Image style configuration. Key is used as a class name added to an image, when the style is selected (.medium-insert-images-wide)
            label: '<span class="fa fa-align-justify"></span>', // (string) A label for a style
            added: function ($el) { }, // (function) Callback function called after the style was selected. A parameter $el is a current active paragraph (.medium-insert-active)
            removed: function ($el) { }, // (function) Callback function called after a different style was selected and this one was removed. A parameter $el is a current active paragraph (.medium-insert-active)
          },
          left: {
            label: '<span class="fa fa-align-left"></span>',
          },
          right: {
            label: '<span class="fa fa-align-right"></span>',
          },
          grid: {
            label: '<span class="fa fa-th"></span>',
          },
        },
        actions: {
          // (object) Actions for an optional second toolbar
          remove: {
            // (object) Remove action configuration
            label: '<span class="fa fa-times"></span>', // (string) Label for an action
            clicked: function ($el) {
              // (function) Callback function called when an action is selected
              var $event = $.Event('keydown')

              $event.which = 8
              $(document).trigger($event)
            },
          },
        },
        messages: {
          acceptFileTypesError: 'This file is not in a supported format: ',
          maxFileSizeError: 'This file is too big: ',
        },
        uploadError: function ($el, data) { },
        uploadCompleted: function ($el, data) { },
      },
    },
  });


  $this.on('keyup', (function () {
    _this = $(this)
    p = _this.find('p');
    if (!p.length) _this.append("<br>")
  }));

})

$('.get-eleve-promotion').change(function (e) {
  $this = $(this)

  $selectPromotion = $($this.data('selector'))

  ajax(
    'get',
    {
      op: 'get-eleve-promotions',
      eleve: $this.val(),
    },
    function (data) {
      $('.form-eleve-infos').html(data.html)

      $selectPromotion.empty()
      if (!data.promotions.length) {
        return
      }

      for (i = 0; i < data.promotions.length; i++) {
        etudiantId = data.promotions[i].id
        etudiantLabel = data.promotions[i].label

        $option = $(document.createElement('option'))
          .attr('value', etudiantId)
          .text(etudiantLabel)
        $selectPromotion.append($option)
      }

      if ($selectPromotion.data('promotion'))
        $selectPromotion.val($selectPromotion.data('promotion'))

      $selectPromotion.change()
    }
  )
})

if ($('.get-eleve-promotion').length > 0 && $('.get-eleve-promotion').val())
  $('.get-eleve-promotion').change()

$(document).on('change', '.eleve-promotion-recu', function (e) {
  $this = $(this)

  ajax(
    'get',
    {
      op: 'recu-promotion',
      promotion: $this.val(),
    },
    function (data) {
      $('.num-recu-prom').val(data.recu)
    }
  )
})

$('.iconpicker-input').iconpicker({
  fullClassFormatter: function (val) {
    return 'fa ' + val
  },
})

$(document).on('change', '.file-upload-copie-examen', function (e) {
  $this = $(this)
  var file = $this[0].files[0]

  var form = new FormData()
  form.append('file', file)
  form.append('op', 'copie-examen')
  form.append('note', $this.data('note'))

  ajax(
    'post',
    form,
    function (data) {
      $('input[name="cf_token"]').val(data.cf_token)
      $(data.selector_add).html(data.html)
      $(data.selector_delete).remove()
    },
    null,
    null,
    true
  )
})

$(document).on('change', '.file-upload-support-cours', function (e) {
  $this = $(this)
  var file = $this[0].files[0]

  var form = new FormData()
  form.append('file', file)
  form.append('op', 'support-cours')
  form.append('cours', $this.data('cours'))

  ajax(
    'post',
    form,
    function (data) {
      window.location.href = data.redirect
    },
    null,
    null,
    true
  )
})

$(document).on('click', '.loadcontent', function () {
  _this = $(this)
  const action = _this.data('action')
  const laodto = _this.data('loadto')
  $(laodto).html('')
  $(laodto).load(action)
})

$(document).on('click', '.file-delete-copie-examen', function (e) {
  $this = $(this)

  var form = new FormData()
  form.append('op', 'delete-copie-examen')
  form.append('note', $this.data('note'))

  ajax(
    'post',
    form,
    function (data) {
      $('input[name="cf_token"]').val(data.cf_token)
      $(data.selector_add).html(data.html)
    },
    null,
    null,
    true
  )
})

$(
  '.form-retards-paiement-classe, .form-retards-paiement-mois, .form-retards-paiement-service'
).on('change', function (e) {
  $this = $(this)

  classe = $('.form-retards-paiement-classe').val()
  mois = $('.form-retards-paiement-mois').val()
  service = $('.form-retards-paiement-service').val()

  if (mois) $('.form-retards-paiement').submit()
})
$('.form-etat-global-paiement-mois').on('change', function (e) {
  $this = $(this)

  mois = $('.form-etat-global-paiement-mois').val()

  if (mois) $('.form-etat-global-paiement').submit()
})

$(
  '.form-cours-classe, .form-cours-enseignant, .form-cours-salle, .form-cours-seance, .form-cours-date'
).on('change', function (e) {
  $this = $(this)
  collaborateur = $this.data('collaborateur')

  classe = $('.form-cours-classe').val()
  enseignant = $('.form-cours-enseignant').val()
  salle = $('.form-cours-salle').val()

  seance = $('.form-cours-seance').val()
  date = $('.form-cours-date').val()

  id = $('.form-cours-id').val()

  if (!seance || !date) return

  ajax(
    'get',
    {
      op: 'form-check-cours',
      classe: classe,
      enseignant: enseignant,
      salle: salle,
      seance: seance,
      date: date,
      id: id,
    },
    function (data) {
      $('.form-cours-classe-error').hide()
      $('.form-cours-salle-error').hide()
      $('.form-cours-enseignant-error').hide()
      $('.form-cours-date-error').hide()

      if (data.checkSalle == true) {
        $('.form-cours-salle-error').show()
      }
      if (data.checkEnseignant == true) {
        $('.form-cours-enseignant-error').show()
      }
      if (data.checkClasse == true) {
        $('.form-cours-classe-error').show()
      }
      if (data.checkHoliday == true) {
        $('.form-cours-date-error').show()
      }
    }
  )
})

$('.enable-input-range').change(function () {
  var checked = $(this).is(':checked')
  if (checked) $('#periode-range').prop('disabled', false)
  else $('#periode-range').prop('disabled', true)
})

$('.ti_tache_check_allday').change(function () {
  var checked = $(this).is(':checked')
  if (checked) {
    $('.ti_tache_datepicker').removeClass('col-md-7')
    $('.ti_tache_datepicker').addClass('col-md-12')
    $('.ti_tache_timepicker').hide()
  } else {
    $('.ti_tache_datepicker').removeClass('col-md-12')
    $('.ti_tache_datepicker').addClass('col-md-7')
    $('.ti_tache_timepicker').show()
  }
})

if ($('#encaissements-depenses-chart').length > 0) {
  // Column chart
  // ------------------------------
  $(window).on('load', function () {
    //Get the context of the Chart canvas element we want to select
    var ctx = $('#encaissements-depenses-chart')

    // Chart Options
    var chartOptions = {
      // Elements options apply to all of the options unless overridden in a dataset
      // In this case, we are setting the border of each bar to be 2px wide and green
      elements: {
        rectangle: {
          borderWidth: 2,
          borderColor: 'rgb(0, 255, 0)',
          borderSkipped: 'bottom',
        },
      },
      responsive: true,
      maintainAspectRatio: false,
      responsiveAnimationDuration: 500,
      legend: {
        position: 'top',
      },
      scales: {
        xAxes: [
          {
            display: true,
            gridLines: {
              color: '#f3f3f3',
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            },
          },
        ],
        yAxes: [
          {
            display: true,
            gridLines: {
              color: '#f3f3f3',
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            },
          },
        ],
      },
      title: {
        display: true,
        text: '',
      },
    }

    // Chart Data
    var chartData = {
      labels: app.months.labels,
      datasets: app.datasets,
    }

    var config = {
      type: 'bar',

      // Chart Options
      options: chartOptions,

      data: chartData,
    }

    // Create the chart
    var lineChart = new Chart(ctx, config)
  })
}

$('.eleve-search-input').on('keyup', function (e) {
  label = $(this).val()

  if (label) $('.eleve-search-classe').prop('disabled', true)
  else $('.eleve-search-classe').prop('disabled', false)
  console.log(label)
})

$('.tarification-check').change(function () {
  var checked = $(this).is(':checked')

  if (checked) {
    $('.tarification-montant-' + $(this).data('rubrique')).prop(
      'disabled',
      false
    )
    if ($(this).data('prix')) {
      $('.tarification-montant-' + $(this).data('rubrique')).val(
        $(this).data('prix')
      )
    }
  } else $('.tarification-montant-' + $(this).data('rubrique')).prop('disabled', true)
})

$('.tarification-montant').on('keyup', function (e) {
  prix = $(this).val()

  if (prix != $(this).data('prix'))
    $('.affecter-tarif-' + $(this).data('rubrique')).show()
  else $('.affecter-tarif-' + $(this).data('rubrique')).hide()
})

$('.form-etat-encaissement-promotion').on('change', function (e) {
  $('.form-etat-encaissement').submit()
})

$('.scroll-content').slimscroll({
  height: 'auto',
})

$('.chart-point-discipline').easyPieChart({
  //your options goes here
  barColor: $('.chart-point-discipline').data('color'),
  trackColor: '#f6f6f6',
  lineWidth: 10,
  lineCap: 'circle',
  scaleColor: false,
  size: 170,
  animate: 2000,
})

if ($('#evolution-absences-chart').length > 0) {
  // Column chart
  // ------------------------------
  $(window).on('load', function () {
    //Get the context of the Chart canvas element we want to select
    var ctx = $('#evolution-absences-chart')

    // Chart Options
    var chartOptions = {
      // Elements options apply to all of the options unless overridden in a dataset
      // In this case, we are setting the border of each bar to be 2px wide and green
      elements: {
        rectangle: {
          borderWidth: 2,
          borderColor: 'rgb(0, 255, 0)',
          borderSkipped: 'bottom',
        },
      },
      responsive: true,
      maintainAspectRatio: false,
      responsiveAnimationDuration: 500,
      legend: {
        position: 'top',
      },
      scales: {
        xAxes: [
          {
            display: true,
            gridLines: {
              color: '#f3f3f3',
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            },
          },
        ],
        yAxes: [
          {
            display: true,
            gridLines: {
              color: '#f3f3f3',
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            },
          },
        ],
      },
      title: {
        display: false,
        text: 'Evolution des Absences',
      },
    }

    // Chart Data
    var chartData = {
      labels: app.evolutionabsences.labels,
      datasets: app.evolutionabsences.datasets,
    }

    var config = {
      type: 'bar',

      // Chart Options
      options: chartOptions,

      data: chartData,
    }

    // Create the chart
    var lineChart = new Chart(ctx, config)
  })
}

if ($('#dashboard-connexion-appmobile').length > 0) {
  // Column chart
  // ------------------------------
  $(window).on('load', function () {
    //Get the context of the Chart canvas element we want to select
    var ctx = $('#dashboard-connexion-appmobile')

    // Chart Options
    var chartOptions = {
      // Elements options apply to all of the options unless overridden in a dataset
      // In this case, we are setting the border of each bar to be 2px wide and green
      elements: {
        rectangle: {
          borderWidth: 2,
          borderColor: 'rgb(0, 255, 0)',
          borderSkipped: 'bottom',
        },
      },
      responsive: false,
      maintainAspectRatio: false,
      responsiveAnimationDuration: 500,
      legend: {
        display: false,
      },
      scales: {
        xAxes: [
          {
            display: true,
            gridLines: {
              color: '#f3f3f3',
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            },
          },
        ],
        yAxes: [
          {
            display: true,
            gridLines: {
              color: '#f3f3f3',
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            },
            ticks: {
              beginAtZero: true,
            },
          },
        ],
      },
      title: {
        display: true,
        text: '',
      },
    }

    // Chart Data
    var chartData = {
      labels: app.days.labels,
      datasets: app.datasets,
    }

    var config = {
      type: 'bar',

      // Chart Options
      options: chartOptions,

      data: chartData,
    }

    // Create the chart
    var lineChart = new Chart(ctx, config)
  })
}

var oldContainer
$('ol.nested_with_switch').sortable({
  group: 'nested',
  afterMove: function (placeholder, container) {
    if (oldContainer != container) {
      if (oldContainer) oldContainer.el.removeClass('active')
      container.el.addClass('active')

      oldContainer = container
    }
  },
  onDrop: function ($item, container, _super) {
    container.el.removeClass('active')
    _super($item, container)
  },
})

$(document).ready(function () {
  if ($('#taches_calendar').length > 0) {
    $('#taches_calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek,month',
      },

      // customize the button names,
      // otherwise they'd all just say "list"
      views: {
        listDay: {
          buttonText: 'list day',
        },
        listWeek: {
          buttonText: 'Calendrier hebdomadaire',
        },
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
    })
  }
})

updateTimeRelative = function () {
  $('time').each(function () {
    var $this = $(this)
    if ($this.data('time-rel-ignore')) return
    var time = $this.attr('datetime')
    var timeM = moment(time)
    if (moment().diff(timeM, 'days') < 7) {
      var originalText = $this.data('time-rel-text') || $this.html()
      $this.data('time-rel-text', originalText)
      $this.attr('title', originalText)
      $this.html(timeM.fromNow())
    } else {
      $this.data('time-rel-ignore', true)
    }
  })
  setTimeout(updateTimeRelative, 15e3)
}
setTimeout(updateTimeRelative, 0)

$(function () {
  var start = moment().subtract(29, 'days')
  var end = moment()

  moment.locale('fr')
  var pickerLocale = {
    applyLabel: 'OK',
    cancelLabel: 'Annuler',
    fromLabel: 'Entre',
    toLabel: 'et',
    customRangeLabel: 'Période personnalisée',
    daysOfWeek: moment().localeData()._weekdaysMin,
    monthNames: moment().localeData()._months,
    firstDay: 0,
    format: 'YYYY-MM-DD',
  }
  var pickerRanges = {
    "Aujourd'hui": [moment(), moment()],
    Hier: [moment().subtract('days', 1), moment().subtract('days', 1)],
    '5 jours précédents': [
      moment().subtract('days', 4),
      moment().subtract('days', 1),
    ],
    'Ce mois': [moment().startOf('month'), moment().endOf('month')],
    'Mois précedent': [
      moment().subtract('month', 1).startOf('month'),
      moment().subtract('month', 1).endOf('month'),
    ],
  }

  function cb(start, end) {
    $('#reportrange span').html(
      start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY')
    )
    $('.dash-periode').val(
      start.format('YYYY-M-DD') + ' - ' + end.format('YYYY-M-DD')
    )

    $periode = $('.dash-periode').val()
    $niveau = $('.current-niveau').val()

    ajax(
      'get',
      {
        op: 'dashboard-pedagogique',
        niveau: $niveau,
        periode: $periode,
      },
      function (data) {
        $('.analytics-item.dash-absences .analy-chiffre').html(
          data.absences.total
        )
        $('.analytics-item.dash-absences .analy-pct').removeClass(
          'warning success'
        )
        $('.analytics-item.dash-absences .analy-pct').addClass(
          data.absences.classe
        )
        $('.analytics-item.dash-absences .analy-pct').html(
          data.absences.variation
        )

        $('.analytics-item.dash-retards .analy-chiffre').html(
          data.retards.total
        )
        $('.analytics-item.dash-retards .analy-pct').removeClass(
          'warning success'
        )
        $('.analytics-item.dash-retards .analy-pct').addClass(
          data.retards.classe
        )
        $('.analytics-item.dash-retards .analy-pct').html(
          data.retards.variation
        )

        $('.analy-warning').html(data.warningHtml)

        $(data.selectorNiveau).html(data.niveauHtml)
        $('.inlinesparkline').sparkline('html', {
          width: '100%',
          height: '70px',
        })
        setTimeout(function () {
          $.sparkline_display_visible()
        }, 200)
      }
    )
  }

  if ($('.dashboard-pedagogique').length > 0) {
    $('#reportrange').daterangepicker(
      {
        startDate: start,
        endDate: end,
        locale: pickerLocale,
        ranges: pickerRanges,
      },
      cb
    )

    cb(start, end)
  }
})

$('.dashboard-pedagogique .home-tabs .nav-link').click(function (e) {
  $this = $(this)
  $niveau = $this.data('change-niveau')
  $cycle = $this.data('cycle')

  $(".cycle-toggle [data-cycle='" + $cycle + "']").data(
    'change-niveau',
    $niveau
  )

  $('.current-niveau').val($niveau)
  $periode = $('.dash-periode').val()
  $('.tab-niveau').html('')
  ajax(
    'get',
    {
      op: 'dashboard-pedagogique',
      periode: $periode,
      niveau: $niveau,
      samePeriode: true,
    },
    function (data) {
      $(data.selectorNiveau).html(data.niveauHtml)
      $('.inlinesparkline').sparkline('html', {
        width: '100%',
        height: '70px',
      })
      setTimeout(function () {
        $.sparkline_display_visible()
      }, 200)
    }
  )
})

$(function () {
  $('.lazy-img').lazy()
})

// To style only selects with the my-select class
// $('.select-pointage').selectpicker();

if ($('.select-pointage-eleves').length > 0) {
  $('.loading').show()
  $selectInscription = $('.select-pointage-eleves')

  $(document).ready(function () {
    $('.select-pointage-eleves').on('change', function () {
      $this = $(this)

      ajax(
        'get',
        {
          op: 'select-pointage-eleves',
          eleve: $this.val(),
        },
        function (data) {
          $('.eleve-infos').html(data.html)
        }
      )
    })
  })

  $(document).on('click', '[data-pointage-eleve]', function (e) {
    $this = $(this)

    eleve = $this.data('pointage-eleve')

    ajax(
      'get',
      {
        op: 'pointage-eleve',
        eleve: eleve,
      },
      function (data) {
        // $('.select-pointage-eleves').val('');
        // $('.select-pointage-eleves').focus();

        $('.select-pointage-eleves').select2('open')

        $('.eleve-infos').html('')
        swal({
          title: data.msg,
          icon: 'success',
          timer: 700,
        })
      },
      function (msg, code) { },
      function () { }
    )
  })
}

$('.off-horaire-user').change(function () {
  $this = $(this)

  $horaireKey = $($this.data('horaire-key'))

  if ($this.is(':checked')) {
    $($horaireKey).prop('disabled', 'disabled')
  } else {
    $($horaireKey).removeAttr('disabled', 'disabled')
  }
})

$('.eleve-key-search-input-top').on('keyup', function (e) {
  nom = $(this).val()

  ajax(
    'get',
    {
      op: 'eleve-key-search-input',
      nom: nom,
    },
    function (data) {
      if (data.html) {
        $('.eleves_result-top').show()
        $('.eleves_result-top').html(data.html)
      } else {
        $('.eleves_result-top').hide()
      }
    }
  )
})

$('.eleve-key-search-input').on('keyup', function (e) {
  nom = $(this).val()

  ajax(
    'get',
    {
      op: 'eleve-key-search-input',
      nom: nom,
    },
    function (data) {
      if (data.html) {
        $('.eleves_result').show()
        $('.eleves_result').html(data.html)
      } else {
        $('.eleves_result').hide()
      }
    }
  )
})

$(document).on('click', '.eleve-modal-search', function (e) {
  e.preventDefault()
  eleve = $(this).data('id')

  ajax(
    'get',
    {
      op: 'eleve-modal-infos',
      eleve: eleve,
    },
    function (data) {
      var dialog = bootbox.dialog({
        title: '',
        size: 'large',
        message: data.html,
        className: 'popup-infos-eleve',
        buttons: {
          cancel: {
            label: 'Fermer',
            className: 'btn btn-boti btn-block',
          },
        },
      })
    }
  )
})

$('.btn-add-question').click(function () {
  $formIndex = parseInt($(this).data('index'))
  $reponses = []
  $question = $('.question-form .question-label').val()
  $image = $('.question-form .image-label').val()
  $temps_reponse = $('.question-form .temps-reponse').val()
  $reponse_correct = $('.question-form')
    .find('input[name="reponse_correct"]:checked')
    .val()

  $('.reponse-label').each(function () {
    $reponse = $(this).val()
    if ($reponse) {
      $reponses.push($reponse)
    }
  })
  if (!$question) {
    swal({
      title: 'Question obligatoire',
      icon: 'warning',
      timer: 2000,
    })
    return false
  }
  if (!$temps_reponse) {
    swal({
      title: 'Temps de réponse est obligatoire.',
      icon: 'warning',
      timer: 2000,
    })
    return false
  }
  if ($reponses.length < 2) {
    swal({
      title: 'Vous devez au moins ajouter deux réponses',
      icon: 'warning',
      timer: 2000,
    })
    return false
  }

  var file = $('#question-image')[0].files[0]

  var form = new FormData()
  form.append('file', file)
  form.append('op', 'get-question-html')
  form.append('question', $question)
  form.append('image', $image)
  form.append('temps_reponse', $temps_reponse)
  form.append(
    'formIndex',
    $formIndex >= 0
      ? $formIndex
      : $('#questions-container table tbody tr').length
  )
  form.append('reponse_correct', $reponse_correct)
  form.append('reponses', $reponses)

  ajax(
    'POST',
    form,
    function (data) {
      $('.datatable-order').dataTable().fnDestroy()

      if ($formIndex >= 0) {
        swal({
          title: 'Question modifiée avec succés',
          icon: 'success',
          timer: 1000,
        })
        $('#questions-container table tbody tr')
          .eq($formIndex)
          .replaceWith(data.html)
      } else {
        swal({
          title: 'Question ajoutée avec succés',
          icon: 'success',
          timer: 1000,
        })
        $('#questions-container table tbody').append(data.html)
      }

      $('.delete-quiz-question').hide()

      $('.datatable-order').DataTable({
        rowReorder: {
          selector: 'tr',
        },
        paging: false,
        searching: false,
      })

      setTimeout(function () {
        $('.save-ressource').trigger('click')
      }, 800)

      $('.question-form .question-label').val('')
      $('.question-form .image-label').val('')
      $('.question-form .temps-reponse').val(10)
      $('input:radio[name="reponse_correct"]')
        .filter('[value="1"]')
        .attr('checked', true)
        .trigger('click')
      $('.reponse-label').each(function () {
        $(this).val('')
      })
    },
    null,
    null,
    true
  )
})

$(document).on('click	', '.delete-quiz-question', function (e) {
  var containar = $(this).closest('tr')

  if (
    confirm(
      'vous êtes sur le point de supprimer cette question\r\nVoulez-vous continuer ?'
    )
  ) {
    swal({
      title: 'La Question a été supprimée avec succés',
      icon: 'success',
      timer: 1000,
    })

    $('.delete-quiz-question').hide()
    $('.btn-add-question').data('index', '')

    $('#questions-container table tbody tr').eq($(this).data('index')).remove()

    setTimeout(function () {
      $('.save-ressource').trigger('click')
    }, 800)

    $('.question-form .question-label').val('')
    $('.question-form .temps-reponse').val(10)
    $('.question-form .image-label').val('')
    $('input:radio[name="reponse_correct"]')
      .filter('[value="1"]')
      .attr('checked', true)
    $('.reponse-label').each(function () {
      $(this).val('')
    })
  }
})

$(document).on('click	', '.edit-quiz-question', function (e) {
  var containar = $(this).closest('tr')

  $('.delete-quiz-question').show()
  $('.btn-add-question').data('index', $(this).data('index'))
  $('.delete-quiz-question').data('index', $(this).data('index'))

  $('.question-form .question-label').val(
    containar.find('input[name="questions[]"]').val()
  )
  $('.question-form .image-label').val(
    containar.find('input[name="images[]"]').val()
  )
  $('.question-form .temps-reponse').val(
    containar.find('input[name="temps_reponse[]"]').val()
  )
  $('.question-form .dropify').attr(
    'data-default-file',
    containar.find('input[name="images_path[]"]').val()
  )

  var drEvent = $('#question-image').dropify()
  drEvent = drEvent.data('dropify')
  drEvent.resetPreview()
  drEvent.clearElement()
  drEvent.settings.defaultFile = containar
    .find('input[name="images_path[]"]')
    .val()
  drEvent.destroy()
  drEvent.init()
  $('.dropify#question-image').dropify({
    defaultFile: containar.find('input[name="images_path[]"]').val(),
  })

  $('input:radio[name="reponse_correct"]').attr('checked', false)
  $(
    'input:radio[name="reponse_correct"][value="' +
    containar.find('input[name="reponses_correct[]"]').val() +
    '"]'
  )
    .attr('checked', true)
    .trigger('click')

  var reponses = containar.find('input[name="reponses[]"]').val().split('##')
  $('.reponse-label').each(function (index) {
    $(this).val(reponses[index] ? reponses[index] : '')
  })
})

$(document).ready(function () {

  if ($('.borne-page').length > 0) {
    link = app.url.link + 'admin/picks/pull_borne?';
    getQuery().forEach(function (val) {
      link += val[0] + '=' + val[1] + "&";
    });
    (function refreshBorne() {
      ajax(
        'get',
        {},
        function (data) {
          $('.borne-page').html(data.html)
          setTimeout(refreshBorne, 2000) // function refers to itself
        },
        null,
        null,
        false,
        link
      )
    })()
  }
})

if ($('.fiche-eleve').lenght > 0) {
  $('.fiche-eleve .fiche-eleve-tabs a').click(function () {
    var position = $(this).parent().position()
    var width = $(this).parent().width()
    $('.fiche-eleve .slider_tab').css({
      left: +position.left,
      width: width,
    })
  })
  var actWidth = $('.fiche-eleve .fiche-eleve-tabs')
    .find('.active')
    .parent('li')
    .width()
  var actPosition = $('.fiche-eleve .fiche-eleve-tabs .active').position()
  $('.fiche-eleve .slider_tab').css({
    left: +actPosition.left,
    width: actWidth,
  })
}

if ($('#meet_vc_boti').length) {
  navigator.mediaDevices
    .getUserMedia({
      video: true,
      audio: true,
    })
    .then((stream) => (video.srcObject = stream))
    .catch((e) => console.log(e.name + ': ' + e.message))

  var domain = app.video_conference.serveur
  var options = {
    roomName: app.video_conference.roomName,
    width: '100%',
    height: 500,
    parentNode: document.querySelector('#meet_vc_boti'),
    interfaceConfigOverwrite: {
      MOBILE_APP_PROMO: false,
      disableDeepLinking: true,
      DEFAULT_BACKGROUND: '#3c96ec',
      SHOW_CHROME_EXTENSION_BANNER: false,
    },
    configOverwrite: {
      disableDeepLinking: true,
    },
  }
  var api = new JitsiMeetExternalAPI(domain, options)
  api.executeCommand('displayName', app.video_conference.displayName)
  api.executeCommand('email', app.video_conference.email)
  api.executeCommand('avatarUrl', app.video_conference.avatarUrl)
}

var KTCalendarExternalEvents = {
  init: function () {
    var e, t, i, n, r, a, o
    $('#kt_calendar_external_events .fc-draggable-handle').each(function () {
      $(this).data('event', {
        title: $.trim($(this).text()),
        stick: !0,
        classNames: [$(this).data('color')],
        description: '',
      })
    }),
      (e = moment().startOf('day')),
      (t = e.format('YYYY-MM')),
      (i = e.clone().subtract(1, 'day').format('YYYY-MM-DD')),
      (n = e.format('YYYY-MM-DD')),
      (r = e.clone().add(1, 'day').format('YYYY-MM-DD')),
      (a = document.getElementById('kt_calendar')),
      (o = document.getElementById('kt_calendar_external_events')),
      new (0, FullCalendarInteraction.Draggable)(o, {
        itemSelector: '.fc-draggable-handle',
        eventData: function (e) {
          return $(e).data('event')
        },
      }),
      new FullCalendar.Calendar(a, {
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
        isRTL: false,
        header: false,
        // height: 800,
        // contentHeight: 780,
        aspectRatio: 3,
        hiddenDays: [0],
        now: n + 'T09:25:00',
        views: {
          dayGridMonth: {
            buttonText: 'month',
          },
          timeGridWeek: {
            buttonText: 'week',
            columnFormat: 'ddd',
          },
          timeGridDay: {
            buttonText: 'day',
          },
        },
        defaultView: 'timeGridWeek',
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
          $('#kt_calendar_external_events_remove').is(':checked') &&
            $(e.draggedEl).remove()
        },
        eventRender: function (e) {
          var t = $(e.el)
          e.event.extendedProps &&
            e.event.extendedProps.description &&
            (t.hasClass('fc-day-grid-event')
              ? (t.data('content', e.event.extendedProps.description),
                t.data('placement', 'top'),
                false)
              : t.hasClass('fc-time-grid-event')
                ? t
                  .find('.fc-title')
                  .append(
                    '<div class="fc-description">' +
                    e.event.extendedProps.description +
                    '</div>'
                  )
                : 0 !== t.find('.fc-list-item-title').lenght &&
                t
                  .find('.fc-list-item-title')
                  .append(
                    '<div class="fc-description">' +
                    e.event.extendedProps.description +
                    '</div>'
                  ))
        },
      }).render()
  },
}
jQuery(document).ready(function () {
  KTCalendarExternalEvents.init()
})

if ($('#use_stats_connexion').length > 0) {
  $(window).on('load', function () {
    var data = {
      labels: app.use_stats_connexion.labels,
      datasets: app.use_stats_connexion.datasets,
    }

    var options = {
      maintainAspectRatio: false,
      spanGaps: false,
      elements: {
        line: {
          tension: 0.000001,
        },
      },
      plugins: {
        filler: {
          propagate: false,
        },
        'samples-filler-analyser': {
          target: 'chart-analyser',
        },
      },
    }

    var ctx = $('#use_stats_connexion')

    var chart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: options,
    })
  })
}

if ($('#use_stats_contenu').length > 0) {
  $(window).on('load', function () {
    var data = {
      labels: app.use_stats_contenu.labels,
      datasets: app.use_stats_contenu.datasets,
    }

    var options = {
      maintainAspectRatio: false,
      spanGaps: false,
      elements: {
        line: {
          tension: 0.000001,
        },
      },
      plugins: {
        filler: {
          propagate: false,
        },
        'samples-filler-analyser': {
          target: 'chart-analyser',
        },
      },
    }

    var ctx = $('#use_stats_contenu')

    var chart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: options,
    })
  })
}

if ($('#use_stats_absences').length > 0) {
  $(window).on('load', function () {
    var data = {
      labels: app.use_stats_absences.labels,
      datasets: app.use_stats_absences.datasets,
    }

    var options = {
      maintainAspectRatio: false,
      spanGaps: false,
      elements: {
        line: {
          tension: 0.000001,
        },
      },
      plugins: {
        filler: {
          propagate: false,
        },
        'samples-filler-analyser': {
          target: 'chart-analyser',
        },
      },
    }

    var ctx = $('#use_stats_absences')

    var chart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: options,
    })
  })
}

if ($('#sessions-browser-donut-chart').length > 0) {
  Morris.Donut({
    element: 'sessions-browser-donut-chart',

    data: app.repartition.data,
    resize: !0,
    colors: app.repartition.colors,
  })
}

$(document).on('submit', '.programmation_unite_coef', function (e) {
  e.preventDefault()
  params = $(this).serializeArray()
  ajax('post', params, function (data) {
    swal({
      title: data.msg,
      icon: 'success',
      timer: 1000,
    })
  })
})

$(document).on('submit', '.programmation_unite_matiere', function (e) {
  e.preventDefault()
  params = $(this).serializeArray()
  ajax('post', params, function (data) {
    swal({
      title: data.msg,
      icon: 'success',
      timer: 1000,
    })

    responseText
  })
})

$('.enable_unite_matiere').change(function () {
  var checked = $(this).is(':checked')
  var element = $($(this).data('element'), $(this).closest('tr'))

  if (checked) {
    element.prop('disabled', false)
  } else {
    element.prop('disabled', true)
  }
})

$('.btn_add_exam').on('click', function (e) {
  $this = $(this)

  $('.modal_examen_label').html($this.data('label'))
  $('#modal_examen_matiere').val($this.data('matiere'))
  $('#modal_examen_type').val($this.data('type'))
  $('#modal_examen_unite').val($this.data('unite'))

  // ensignant
  $('.enseignants_modal').html(
    $('#enseignants_unite_' + $this.data('unite')).html()
  )

  $('.btn_add_exam').removeClass('examen_added')
  $(this).addClass('examen_added')
})

$(document).on('submit', '.form_add_examen', function (e) {
  e.preventDefault()
  params = $(this).serializeArray()
  var isValid = $(e.target).parents('form').isValid()
  if (!isValid) {
    swal({
      title: 'Note',
      text: 'Le date est obligatoire',
      type: 'success',
    })
  }
  ajax('post', params, function (data) {
    $('#add-exam').modal('toggle')
    //$('.examen_added').closest('td').html(data.html);
    swal({
      title: data.msg,
      icon: 'success',
      timer: 1000,
    }).then(function () {
      location.reload()
    })
  })
})

$('.saisie_notes_classe').change(function (e) {
  $('.saisie_notes_unite').val(null)
  $('.saisie_notes_eval').val(null)
  $(this).closest('form').submit()
})

$('.saisie_notes_semestre').change(function (e) {
  $('.saisie_notes_unite').val(null)
  $('.saisie_notes_eval').val(null)
  $(this).closest('form').submit()
})

$('.saisie_notes_unite')
  .change(function (e) {
    $('.saisie_notes_eval option').hide()
    $('.saisie_notes_eval option[value="0"]').show()
    $(".saisie_notes_eval option[data-unite='" + $(this).val() + "']").show()
  })
  .change()

// multiple exam
$('.add_multiple_exams').on('click', function () {
  $this = $(this)
  var unite = $this.data('unite')
  $('.modal_examen_multiple_label').html($this.data('label'))
  $('#modal_examen_multiple_unite').val(unite)

  // thead

  var thead = $('#thead_unite_' + unite).clone()
  thead.show()
  $('#table_add_muliple_exam').find('thead').html('')
  $('#table_add_muliple_exam').find('thead').append(thead)
  // tbody

  $('#table_add_muliple_exam').find('tbody').html('')
  $('.matieries_of_unite_' + unite).each(function (index, el) {
    cel = $(el).clone()
    cel.show()
    $('#table_add_muliple_exam').find('tbody').append(cel)
  })

  // ensignant
  $('.enseignants_modal').html($('#enseignants_unite_' + unite).html())
})

$(document).on('submit', '#form_multiple_exam', function (e) {
  e.preventDefault()
  const data = $(this).serializeArray()
  $.ajax({
    method: 'post',
    url: $(this).attr('action'),
    data: data,
  }).done(function (data) {
    data = JSON.parse(data)
    swal({
      title: data.data.msg,
      icon: data.data.status,
      timer: 1000,
    }).then(function () {
      location.reload()
    })
  })
})

// check if CNE IS valide
$(document).on('change', '.massar', function (e) {
  _this = $(this)
  _this.css('border', '')
  $.ajax({
    method: 'get',
    url: _this.data('action'),
    data: {
      op: 'check_massar',
      massar: _this.val(),
    },
  })
    .done(function () {
      _this.data('valid', true)
    })
    .fail(function (data) {
      data = JSON.parse(data.responseText)
      swal({
        title: data.msg,
      })
      _this.data('valid', false)
      _this.css('border', '1px solid red')
    })
})

// validate before send form inscriptions
$(document).on('submit', '#inscription', function (e) {
  massar = $(this).find('.massar')
  mois = $(this).find('select[name="mois"]')
  jour = $(this).find('select[name="jour"]')
  annee = $(this).find('select[name="annee"]')
  mois.css('border', '')
  jour.css('border', '')
  annee.css('border', '')

  if (!mois.val()) {
    e.preventDefault()
    mois.css('border', '1px solid red')
  }

  if (!jour.val()) {
    e.preventDefault()
    jour.css('border', '1px solid red')
  }

  if (!annee.val()) {
    e.preventDefault()
    annee.css('border', '1px solid red')
  }

  if (!massar.data('valid')) {
    e.preventDefault()
  }
})

$('.mutiple_grille_note_max').on('keyup', function () {
  console.log('ok')
  const _this = $(this)
  const value = Number(_this.val())
  const max = Number(_this.attr('max'))
  if (value > max) {
    _this.val(max)
    //_this.css('border', '1px solid red');
  }
  //else {
  // 	_this.css('border', 'none');
  // }
})

$('.tab_key').keypress(function (e) {
  if (e.which == 13) {
    e.preventDefault()
    $this = $(this)
    $td_index = $this.closest('td').index()
    $tr = $this.closest('tr')
    $nex_tr = $tr.next('tr')
    $nex_tr.find('td').eq($td_index).find('input').focus()
  }
})

///////////// Fiche eleve //////////////////
$('.fiche-perso-new').on('click', function () {
  const container = $('#fiche-perso-form')
  container.find('#fiche_perso_pk').attr('name', 'id').val('')
  container.find('textarea[name=details]').val('')
})

$('.fiche-perso-edit').on('click', function () {
  _this = $(this)
  const container = $('#fiche-perso-form')
  container.find('#fiche_perso_pk').attr('name', 'id').val(_this.data('id'))
  container.find('select[name=type]').val(_this.data('type')).trigger('change')
  container.find('textarea[name=details]').val(_this.data('details'))
})

$('.fiche-suivi-new').on('click', function () {
  _this = $(this)
  const container = $('#fiche-suivi-form')
  container.find('#fiche_suivi_pk').attr('name', 'id').val('')
  container.find('textarea[name=details]').val('')
})

$('.fiche-suivi-edit').on('click', function () {
  _this = $(this)
  const container = $('#fiche-suivi-form')
  container.find('#fiche_suivi_pk').attr('name', 'id').val(_this.data('id'))
  container.find('select[name=type]').val(_this.data('type')).trigger('change')
  container.find('textarea[name=details]').val(_this.data('details'))
  container
    .find('input[name=flag][value=' + _this.data('flag') + ']')
    .attr('checked', 'checked')
})

$('.edit-encaissement').on('click', function () {
  _this = $(this)
  const container = $('#modal-edit-encasissement')
  const paiementModeAlias = _this.data('mode_paiement_alias')
  container.find('input[name="id"]').val(_this.data('id'))
  container.find('textarea[name="remarque"]').val(_this.data('remarque'))
  container.find('#nemuro_recu').html('<b> ' + _this.data('nemuro') + ' </b>')
  container
    .find('#date_paiement')
    .html('<b> ' + _this.data('date_paiement') + ' </b>')
  container
    .find('select[name="paiementmode"]')
    .val(_this.data('mode_paiement'))
    .trigger('change')
  container
    .find('select[name="partenaire"]')
    .val(_this.data('partenaire'))
    .trigger('change')

  if (paiementModeAlias == 'cheque') {
    container
      .find('select[name="banque"]')
      .val(_this.data('banque'))
      .trigger('change')
    container.find('input[name="numcheque"]').val(_this.data('numcheque'))
    container.find('input[name="tireur"]').val(_this.data('tireur'))
    container
      .find('input[name="date_echeance"]')
      .val(_this.data('date_echeance'))
      .trigger('change')
  }
  if (paiementModeAlias == 'virement') {
    container.find('input[name="numcompte"]').val(_this.data('numcompte'))
    container.find('input[name="numpiece"]').val(_this.data('numpiece'))
  }
})

$(document).on('click', '.delete-action', function () {
  _this = $(this)
  _action = _this.data('action')
  _closest = _this.data('closest')
  _refresh = _this.data('refresh')
  swal({
    title: 'Êtes-vous sûr?',
    text: 'Vous ne pourrez pas récupérer!',
    icon: 'warning',
    buttons: ['Non, annulez-le!', 'Oui je suis sûr!'],
    dangerMode: true,
  }).then(function (isConfirm) {
    if (isConfirm) {
      $.ajax({
        method: 'post',
        url: _action,
      })
        .done(function (data) {
          _this.closest(_closest).remove()
          if (_refresh) {
            location.reload()
          }
        })
        .fail(function (data) {
          swal({
            title: data.responseText,
            icon: 'warning',
            dangerMode: true,
          })
        })
    } else {
      swal('Annulé', '', 'error')
    }
  })
})

$(document).on('click', '.href-action', function (e) {
  _this = $(this)
  e.preventDefault()
  _action = _this.data('action')
  _closest = _this.data('closest')
  _refresh = _this.data('refresh')
  _text = _this.data('text')
  swal({
    title: 'Êtes-vous sûr?',
    text: _text,
    icon: 'warning',
    buttons: ['Non, annulez-le!', 'Oui je suis sûr!'],
  }).then(function (isConfirm) {
    if (isConfirm) {
      $.ajax({
        method: 'post',
        url: _action,
      })
        .done(function (data) {
          _this.closest(_closest).remove()
          if (_refresh) {
            location.reload()
          }
        })
        .fail(function (data) {
          swal({
            title: data.responseText,
            icon: 'warning',
          })
        })
    } else {
      swal('Annulé', '', 'error')
    }
  })
})

////////////////// Export Notes Massar ////////////////

$('#export-massar-notes')
  .find('button:first')
  .on('click', function () {
    $('#massar_file_container').show()
    $('.progress-bar-export').hide()
    $('.progress-bar-export-finish').hide()
  })

$('.toggle-export-massage').on('click', function () {
  $('#massar_file_container').show()
  $('.progress-bar-export').hide()
  $('.progress-bar-export-finish').hide()
})

$('#export-notes-massar-form').on('submit', function (e) {
  e.preventDefault()
  _this = $(this)
  var formData = new FormData(_this[0])
  getQuery().forEach(function (val) {
    formData.append(val[0], val[1])
  })
  //animation
  var spinner = document.getElementById('percent')
  var lshc = document.getElementById('lshc')
  var val = document.getElementById('spinnervalue')
  var rside = document.getElementById('rs')
  var pwrap = document.getElementById('pie')
  $('#massar_file_container').hide()
  $('.progress-bar-export').show()
  $.ajax({
    xhr: function () {
      var xhr = new window.XMLHttpRequest()
      xhr.upload.addEventListener(
        'progress',
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total
            console.log(percentComplete)
            var p = percentComplete
            var pf = percentComplete * 100
            var PtoD = p * 360
            val.innerHTML = pf + '<span class="smaller">%</span>'
            if (PtoD < 180) {
              spinner.style.webkitTransform = 'rotate(' + PtoD + 'deg)'
            } else {
              pwrap.className = 'pie over50p'
              spinner.style.webkitTransform = 'rotate(' + PtoD + 'deg)'
              lshc.style.webkitTransform = 'rotate(' + PtoD + 'deg)'
              rside.style.webkitTransform = 'rotate(180deg)'
            }
          }
        },
        false
      )
      xhr.addEventListener(
        'progress',
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total
            var p = percentComplete
            var pf = percentComplete * 100
            var PtoD = p * 360
            val.innerHTML = pf + '<span class="smaller">%</span>'
            if (PtoD < 180) {
              spinner.style.webkitTransform = 'rotate(' + PtoD + 'deg)'
            } else {
              pwrap.className = 'pie over50p'
              spinner.style.webkitTransform = 'rotate(' + PtoD + 'deg)'
              lshc.style.webkitTransform = 'rotate(' + PtoD + 'deg)'
              rside.style.webkitTransform = 'rotate(180deg)'
            }
          }
        },
        false
      )

      return xhr
    },
    type: 'post',
    url: _this.attr('action'),
    data: formData,
    cache: false,
    processData: false,
    contentType: false,
  })
    .done(function (_data) {
      const data = JSON.parse(_data)
      $('.progress-bar-export').hide()

      $('.progress-bar-export-finish')
        .find('.download_link')
        .attr('href', data['link'])
      $('.progress-bar-export-finish')
        .find('.download_link')
        .html(data['download_label'])
      $('.progress-bar-export-finish').find('.message').html(data['message'])

      $('.progress-bar-export-finish-success').show()
      $('.progress-bar-export-finish').show()
    })
    .fail(function (res) {
      const data = JSON.parse(res.responseText)
      console.log(data)
      $('.progress-bar-export').hide()

      $('.progress-bar-export-finish')
        .find('.download_link')
        .attr('href', data['link'])
      $('.progress-bar-export-finish')
        .find('.download_link')
        .html(data['download_label'])
      $('.progress-bar-export-finish').find('.message').html(data['message'])

      $('.progress-bar-export-finish').show()
    })
})

//////////// //////////////////////

$('#form-niveau').on('change', function () {
  console.log('ok')
  $this = $(this)
  $.ajax({
    url: $this.data('api') + $this.val(),
  }).done(function (res) {
    $($this.data('ele')).html(res)
  })
})

$('#form-matiere').on('change', function () {
  console.log('ok')
  $this = $(this)
  $.ajax({
    url: $this.data('api') + $this.val(),
    data: {
      classes: $('#classe').val(),
    },
  }).done(function (res) {
    $($this.data('ele')).html(res)
  })
})

$('#classe').on('change', function () {
  console.log('ok')
  $this = $(this)
  $.ajax({
    url: $this.data('api'),
    data: {
      classes: $this.val(),
    },
  }).done(function (res) {
    $($this.data('ele')).html(res)
  })
})

//////////// inscription ////////////////////////

$(document).on('click', '.toggle_elements', function (e) {
  e.stopPropagation();
  _this = $(this)
  const hide_classe = _this.data('to_hide')
  const show_classe = _this.data('to_show')
  $(hide_classe).hide()
  $(show_classe).show()
})

$(document).on('click', '.toggle_element', function (e) {
  e.stopPropagation();
  _this = $(this)
  const togle_classe = _this.data('toggle')
  $(togle_classe).toggle()
})

$('.open_if_checked').on('change', function () {
  _this = $(this)
  $(_this.data('ele-hide-if-checked')).hide()
  const toggle_classe = _this.data('ele-open-if-checked')
  if (_this.is(':checked')) {
    $(toggle_classe).show()
  } else {
    $(toggle_classe).hide()
  }
})

$('.max-value').keyup(function () {
  // Check correct, else revert back to old value.
  _this = $(this)
  if (parseInt(_this.val()) > parseInt(_this.attr('max'))) {
    _this.val(parseInt(_this.attr('max')))
  }
})

$('.new_inscription').on('click', function () {
  const _this = $(this)
  console.log('ok ')
  const container = $(_this.data('target'))
  container.find('input[name="nom"]').val(_this.data('nom'))
  container.find('input[name="prenom"]').val(_this.data('prenom'))
  container
    .find('select[name="niveau"]')
    .val(_this.data('niveau'))
    .trigger('change')
  container.find('input[name="email"]').val(_this.data('email'))
  container.find('input[name="tel"]').val(_this.data('gsm'))
  container.find('textarea[name="adresse"]').val(_this.data('adresse'))
  container
    .find('input[name="request_inscription"]')
    .val(_this.data('inscription'))
  console.log(container.find('.delete-action'))
  container
    .find('.delete-action')
    .attr('data-action', _this.data('action-delete'))
})

$('.massar-value').keyup(function () {
  // Check correct, else revert back to old value.
  _this = $(this)
  _this.val(_this.val().toUpperCase())
  if (
    parseInt(_this.val()) ||
    /\d/.test(_this.val()) ||
    _this.val().length > 2
  ) {
    _this.val(_this.val().slice(0, -1))
  }
})

$(document).on('change', '#filtre-classe', function (e) {
  _this = $(this)
  console.log('ok')
  $.ajax({
    method: 'get',
    url: _this.data('action') + '/' + _this.val(),
  }).done(function (res) {
    const data = JSON.parse(res)
    data.forEach(function (value, index) { })
  })
})

///////  picks ///////////////
$('.iti-add').on('click', function () {
  _this = $(this)
  const v = _this.data('v')
  const c = _this.data('c')
  const a = _this.data('a')
  const t = _this.data('t')
  const action = _this.data('action')

  const container = $(_this.data('target'))
  container.find('form').attr('action', action)
  container.find('input[name="titre"]').val('')
})

$('.iti-edit').on('click', function () {
  _this = $(this)
  const v = _this.data('v')
  const c = _this.data('c')
  const a = _this.data('a')
  const t = _this.data('t')
  const action = _this.data('action')

  const container = $(_this.data('target'))
  container.find('form').attr('action', action)
  container.find('input[name="titre"]').val(t)
  container.find('select[name="vehicule"]').val(v).trigger('change')
  container.find('select[name="chauffeur"]').val(c).trigger('change')
  container.find('select[name="aide_chauffeur"]').val(a).trigger('change')
})

$('.edit-vehicule').on('click', function () {
  _this = $(this)
  console.log(_this.data('target'))

  const container = $(_this.data('target'))
  container.find('form').attr('action', _this.data('action'))
  container
    .find('select[name="modele"]')
    .val(_this.data('modele'))
    .trigger('change')
  container.find('input[name="matricule"]').val(_this.data('matricule'))
  container.find('input[name="numeroInterne"]').val(_this.data('numerointerne'))
  container
    .find('input[name="dateMiseCirculation"]')
    .val(_this.data('datemisecirculation'))
  container.find('input[name="km"]').val(_this.data('km'))
  container.find('input[name="remarques"]').val(_this.data('remarques'))
})

$('.edit-modele').on('click', function () {
  _this = $(this)

  const container = $(_this.data('target'))
  container.find('form').attr('action', _this.data('action'))

  container
    .find('select[name="marque"]')
    .val(_this.data('marque'))
    .trigger('change')
  container.find('input[name="marqueLabel"]').val(_this.data('marquelabel'))
  container.find('input[name="label"]').val(_this.data('label'))
})

$('.edit-marque').on('click', function () {
  _this = $(this)
  const container = $(_this.data('target'))
  container.find('form').attr('action', _this.data('action'))
  container.find('input[name="label"]').val(_this.data('label'))
})

$(document).on('change', '.filtre_classe', function (e) {
  console.log('ok worked')
  _this = $(this)
  $.ajax({
    method: 'get',
    url: _this.data('action'),
    data: {
      promotion: $(_this.data('promotion')).val(),
      niveau: $(_this.data('niveau')).val(),
    },
  }).done(function (res) {
    $(_this.data('classe')).html('')
    const data = JSON.parse(res)
    data.forEach(function (value, index) {
      $(_this.data('classe')).append(
        $('<option />').val(value.id).html(value.label)
      )
    })
  })
})

$(document).on('change', '.filtre', function (e) {
  _this = $(this)
  //console.log("ok worked",_this.data("resource"));
  $.ajax({
    method: 'get',
    url: _this.data('action'),
    data: {
      resource: _this.val(),
    },
  }).done(function (res) {
    $(_this.data('select')).html('')
    const data = JSON.parse(res)
    data.forEach(function (value, index) {
      $(_this.data('select')).append(
        $('<option />').val(value.id).html(value.label)
      )
    })
  })
})

window.onload = function () {
  $('.slider-notes-infos').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    prevArrow: $('.slider-notes-infos-navs .prev'),
    nextArrow: $('.slider-notes-infos-navs .next'),
  })
  var hash = window.location.hash
  hash && $('ul.nav a[href="' + hash + '"]').tab('show')
  $('.nav-tabs a').click(function (e) {
    $(this).tab('show')
    var scrollmem = $('body').scrollTop() || $('html').scrollTop()
    window.location.hash = this.hash
    $('html,body').scrollTop(scrollmem)
  })
}

$('.niveauxmaitrise_colorpicker').on('change', function (event) {
  _this = $(this)
  $(_this.data('span')).css('background-color', $(this).val())
})
$(document).on('change', '.fill_another_select', function (event) {
  _this = $(this)
  const targetSelect = _this.data('target')
  const url = _this.data('action')
  const optional = _this.data('optional')
  const dataName = _this.data('name')
  const dataVal = _this.val()
  const dataNiveau = _this.data('niveau') ? $(_this.data('niveau')).val() : null
  const classes = _this.data('classes')
  $.ajax({
    method: 'get',
    url,
    data: {
      name: dataName,
      val: dataVal,
      niveau: dataNiveau,
    },
  }).done(function (res) {
    $(targetSelect).html('')
    const data = JSON.parse(res)
    if (optional) {
      $(targetSelect).append($('<option />').val('').html('Aucune'))
    }
    if (classes) {
      const classesArray = classes.toString().split(',')
      data.forEach(function (value, index) {
        if (classesArray.includes(value.id)) {
          $(targetSelect).append(
            $('<option />')
              .val(value.id)
              .html(value.label)
              .attr('selected', 'selected')
          )
        } else $(targetSelect).append($('<option />').val(value.id).html(value.label))
      })
    } else {
      data.forEach(function (value, index) {
        $(targetSelect).append($('<option />').val(value.id).html(value.label))
      })
    }
    $(targetSelect).trigger('change')
  })
})
$('.get_competences_list').on('change', function (event) {
  $($(this).data('target')).trigger('submit')
})
$('.update_competence').on('click', function (event) {
  _this = $(this)
  const id = _this.data('id')
  const title = _this.data('title')
  const desc = _this.data('desc')
  const compSuper = '#update_comp_super'
  const idInput = '#update_comp_id'
  const titleInput = '#update_title'
  const descInput = '#update_desc'
  $(idInput).val(id)
  $(titleInput).val(title)
  $(descInput).val(desc)
  if (_this.data('super')) {
    $(compSuper).hide()
  } else {
    $(compSuper).show()
    $('#update_competences_select').val(_this.data('superid')).change()
    console.log(_this.data('superid'))
  }
})
$(document).on('click', '.get_modal_form', function (event) {
  _this = $(this)
  const url = _this.data('url')
  const id = _this.data('id')
  const respanel = _this.data('respanel')
  const isDuplicate = _this.data('duplicate')
  $('.modal_form_container').html('')
  data = {}
  if (id) data['id'] = id
  if (isDuplicate) data['is_duplicate'] = true
  $.ajax({
    method: 'get',
    url,
    data,
  }).done(function (res) {
    $(respanel).html(res)
    $(respanel)
      .find('.select2')
      .each(function () {
        var $this = $(this)
        $this.select2({
          placeholder: $this.data('placeholder'),
          allowClear: $this.data('allow-clear'),
          minimumResultsForSearch:
            $this.data('show-search') || $this.find('option').length > 6
              ? 0
              : Infinity,
        })
      })
    $(respanel).find('.select2').trigger('change')
  })
})
$('#new_niveau_select').on('change', function (event) {
  _this = $(this)
  const url = _this.data('url')
  const niveauID = _this.val()
  const respanel = _this.data('respanel')
  $.ajax({
    method: 'get',
    url,
    data: { niveauID },
  }).done(function (res) {
    $(respanel).html(res)
  })
})
$(document).on('change', '#update_niveau_select', function (event) {
  _this = $(this)
  const url = _this.data('url')
  const niveauID = _this.val()
  const comps = _this.data('comps')
  const respanel = _this.data('respanel')
  $.ajax({
    method: 'get',
    url,
    data: { niveauID, comps },
  }).done(function (res) {
    $(respanel).html(res)
  })
})
$(document).on('click', '.trigger-change', function (event) {
  $($(this).data('trigger')).trigger('change')
})

$(document)
  .find('.select3')
  .each(function () {
    var $this = $(this)
    $this.select2({
      escapeMarkup: function (markup) {
        return markup
      },
      templateResult: function (data) {
        return $(data.element).data('html')
      },
      templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        // $(data.element).attr('data-custom-attribute', data.customValue);
        console.log(data, container)
        if (container) {
          container.css('background', $(data.element).data('color'))
        }
        return $(data.element).data('code')
      },
    })
  })

$(document).on('click', '.trigger-change', function (event) {
  $($(this).data('trigger')).trigger('change')
})

$(document).on('change', '.submit_form', function (event) {
  _this = $(this)
  _this.closest('form').trigger('submit');
})

$(document).on('click', '.nav.nav-tabs li a[role="tab"]', function () {
  let anchor = $(this);
  let tab_target = $(anchor.data('target'));
  anchor.parent().siblings().each((index, element) => {
    console.log(element);
    element.getElementsByTagName('a')[0].classList.remove("active");
  });

  tab_target.siblings().each((index, element) => {
    element.classList.remove("active");
  });
  anchor.addClass('active');
  tab_target.addClass('active');

});

$(document).on('change', '.date_trajet_input', function (e) {
  $(this).parent('form').submit();
});

/* start of form discipline */
$(document).on('change', '.checkbox_hidden_post', function (e) {
  _this = $(this)
  const target = _this.data('target')
  if (_this.is(':checked')) {
    $(target).val('1')
  } else {
    $(target).val('0')
  }
})

$('.discipline-modal-btn').click(function () {
  $_this = $(this)
  eleveID = $_this.data('eleve')
  courseID = $_this.data('course')
  courseLabel = $_this.data('courselabel')
  actions = $_this.data('action')

  console.log(actions.length)
  $modal = $('#discipline-modal')
  $modal.find('#inscription').val(eleveID)
  $modal.find('#course').val(courseID)
  $modal.find('#course-label').html(courseLabel)
  $modal.find('#type').val('').change()

  $table = $modal.find('.table-actions')
  if (actions.length == 0) {
    $table.addClass('hidden')
  } else {
    console.log('hello');
    $body = $table.find('tbody').empty()
    tplHtml = $table.find('template').html()
    totalValeurs = 0
    actions.forEach(function (action) {
      console.log(action)
      console.log(action.id)
      totalValeurs += action.valeur * 1
      $row = $(tplHtml)
      $row.data('id', action.id)
      $row.attr('class', 'action-' + action.id)
      $row.find('.cours').text(action.cours)
      $row.find('.type').text(action.type)
      $row.find('.valeur').text(action.valeur)
      $row.find('.js-btn-delete').click(function () {
        let $row = $(this).closest('tr')
        let idAction = $row.data('id')
        ajax(
          'POST',
          {
            op: 'seance-absences-discipline-delete',
            action: idAction,
          },
          function (result) {
            $rowInscription = $(
              '#table-absences .inscription-' + result.eleveID
            )
            let totalValeurs = 0
            result.actions.forEach(function (action) {
              totalValeurs += action.valeur * 1
            })
            $rowAction = $('.action-' + result.idAction)
            $tableActions = $rowAction.closest('table')
            $tableActions.find('.total-valeurs').text(totalValeurs)
            if (result.actions.length == 0) $tableActions.addClass('hidden')

            actionsDiv = '#actions-div-' + eleveID + '-' + courseID
            noActionsDiv = '#noactions-div-' + eleveID + '-' + courseID

            if (result.actions.length > 0) {
              updateActionDiv(actionsDiv, totalValeurs)

            } else {
              $(actionsDiv).addClass('hidden')
              $(noActionsDiv).removeClass('hidden')
            }
            $rowAction.remove()
            refreshRowTotalDiscipline($rowInscription, result.actions)
            console.log($rowInscription, result.actions)
          }
        )
      })
      $body.append($row)
    })
    $table.find('.total-valeurs').text(totalValeurs)
    $table.removeClass('hidden')
  }
  $modal.modal()
})

function updateActionDiv(actionsDiv, totalValeurs) {
  disButton = $(actionsDiv).find("button")
  disStrong = $(actionsDiv).find('strong')

  disButton.removeClass('btn-success')
  disButton.removeClass('btn-danger')
  disButton.removeClass('btn-info')

  if (totalValeurs < 0) {
    disButton.addClass('btn-danger')
    disStrong.html(totalValeurs)
  }
  else if (totalValeurs > 0) {
    disButton.addClass('btn-success')
    disStrong.html('+' + totalValeurs)
  }
  else {
    disButton.addClass('btn-info')
    disStrong.html(totalValeurs)
  }
}

$('.seance-form-discipline').submit(function (e) {
  e.preventDefault()
  $form = $(this)
  idInscription = $('#inscription', $form).val()
  idCours = $('#course', $form).val()
  idType = $('#type', $form).val()
  date = $('#date', $form).val()
  commentaire = $('#commentaire', $form).val()

  ajax(
    'POST',
    {
      op: 'seance-absences-discipline-add',
      inscription: idInscription,
      cours: idCours,
      type: idType,
      date: date,
      commentaire: commentaire,
    },
    function (result) {

      actionsDiv = '#actions-div-' + result.idInscription + '-' + courseID
      noActionsDiv = '#noactions-div-' + result.idInscription + '-' + courseID

      $(noActionsDiv).addClass('hidden')
      $(actionsDiv).removeClass('hidden')

      let totalValeurs = 0
      result.actionsData.forEach(function (action) {
        totalValeurs += action.valeur * 1
      })
      updateActionDiv(actionsDiv, totalValeurs)

      disButton = $(actionsDiv).find("button")
      disButton.data('action', result.actionsData)
      disButton.trigger('click')

      // $row = $('.inscription-' + result.idInscription)
      // $row.data('actions', result.actionsData)
      // refreshRowTotalDiscipline($row, result.actionsData)
      swal({
        title: "L'evaluation a été bien mise à jour.",
        icon: 'success',
        timer: 2000,
      })
      // $('#discipline-modal').modal('hide')
      // location.reload()
    }
  )
})

$('.save_eleve_absence').click(function () {
  $_this = $(this)
  url = $_this.data('url')
  eleveID = $_this.val()

  absence = 'eleve' + eleveID + '_absence'
  absence = $('.' + absence).map(function (i, ele) {
    return $(ele).val();
  }).get().join()

  retard = 'eleve' + eleveID + '_retard';
  retard = $('.' + retard).map(function (i, ele) {
    return $(ele).val();
  }).get().join()

  cours = $('.courses-input').map(function (i, ele) {
    return $(ele).val();
  }).get().join()

  formData = new FormData();
  formData.append('op', 'seance_absences');
  formData.append('save_eleve_absence', eleveID);
  formData.append('date', $_this.data('date'));
  formData.append('cours', cours);
  formData.append('eleve' + eleveID + '_absence', absence);
  formData.append('eleve' + eleveID + '_retard', retard);

  $.ajax({
    method: 'post',
    url: url,
    data: formData,
    cache: false,
    processData: false,
    contentType: false,
  }).done(function (res) {
    swal({
      title: "L'evaluation a été bien mise à jour.",
      icon: 'success',
      timer: 4000,
    })
  })
})
/* end of form discipline */
