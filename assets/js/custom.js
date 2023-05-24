'use strict';

$(document).ready(function () {
  var findNumberField = function findNumberField($btn) {
    return $btn.closest('.input-container').find('input[type=number]');
  };

  $('.js-form-number-dec').on('click', function (e) {
    var $current = $(e.currentTarget),
      $input = findNumberField($current);

    var newValue = parseInt($input.val()) - 1;
    if (newValue >= $input.attr('min')) {
      $input.val(newValue);
    }
  });

  $('.js-form-number-inc').on('click', function (e) {
    var $current = $(e.currentTarget),
      $input = findNumberField($current);

    var newValue = parseInt($input.val()) + 1;

    $input.val(newValue);
  });

  $('form[name=export_exams]').submit(function (event) {
    event.preventDefault();
    var choices = $('#export_table .choice-table:checked');
    var data = [];
    var _class = $('#filtre-classe').val();

    console.log(choices);
    for (var i = 0; i < choices.length; i++) {
      var item = $(choices[i]).attr('data-id');
      data.push(item);
    }

    if (_class == "") {
      alert('Veuillez choisir une classe avant d\'exporter les examens !');
      return false;
    }


    var exams = data.join('|');
    if (data.length == 0) {
      exams = 0;
    }

    var url = $(this).attr('action') + '&exams=' + exams + '&class_id=' + _class;

    // $.post(url, $(this).serialize()).done(function(response){
    // document.location = 'data:application/vnd.ms-excel;base64,'+ response;
    // });

    document.location = url + "&" + $(this).serialize();
    // return false;
  });

  $('#export_exams_modal').click(function (event) {
    event.preventDefault();
    var _class = $('#filtre-classe').val();
    if (_class == "") {
      alert('Veuillez choisir une classe avant d\'exporter les examens !');
      return false;
    }

    $('#modal_export_exams .birthCount').parent().hide();

    var url = $('form[name=export_exams]').attr('action') + '&stat&class_id=' + _class;
    $.get(url, "JSON").done(function (response) {
      try {
        response = JSON.parse(response);
        var count = response.birthday;
        if (count > 0) {
          $('#modal_export_exams .birthCount').html(count);
          $('#modal_export_exams .birthCount').parent().show();
        }

      } catch (e) { }
    });

    $("#modal_export_exams").modal('show');
  });

});

jQuery('img.svg').each(function () {
  var $img = jQuery(this);
  var imgID = $img.attr('id');
  var imgStyle= $img.attr('style');
  var imgClass = $img.attr('class');
  var imgURL = $img.attr('src');

  jQuery.get(imgURL, function (data) {
    // Get the SVG tag, ignore the rest
    var $svg = jQuery(data).find('svg');

    // Add replaced image's ID to the new SVG
    if (typeof imgID !== 'undefined') {
      $svg = $svg.attr('id', imgID);
    }
    // Add replaced image's classes to the new SVG
    if (typeof imgClass !== 'undefined') {
      $svg = $svg.attr('class', imgClass + ' replaced-svg');
    }

     // Add replaced image's style to the new SVG
     if (typeof imgStyle !== 'undefined') {
      $svg = $svg.attr('style', imgStyle);
    }

    // Remove any invalid XML tags as per http://validator.w3.org
    $svg = $svg.removeAttr('xmlns:a');

    // Check if the viewport is set, else we gonna set it if we can.
    if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
      $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
    }

    // Replace image with new SVG
    $img.replaceWith($svg);

  }, 'xml');

});

if ($('#FileAttachment').length > 0) {

  document.getElementById("FileAttachment").onchange = function () {
    $("#file_name").html(this.value.replace(/C:\\fakepath\\/i, ''));
  };
}

(function (document, window, index) {
  var inputs = document.querySelectorAll('.inputfile');
  Array.prototype.forEach.call(inputs, function (input) {
    var label = input.nextElementSibling,
      labelVal = label.innerHTML;

    input.addEventListener('change', function (e) {
      var fileName = '';
      if (this.files && this.files.length > 1)
        fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
      else
        fileName = e.target.value.split('\\').pop();

      if (fileName)
        label.querySelector('span').innerHTML = fileName;
      else
        label.innerHTML = labelVal;
    });

    // Firefox bug fix
    input.addEventListener('focus', function () { input.classList.add('has-focus'); });
    input.addEventListener('blur', function () { input.classList.remove('has-focus'); });
  });
}(document, window, 0));



/********
 * 
 *  START MATRICES SCRITPS
 * 
 * **** */

$('#treetable').simpleTreeTable({
  expander: $('#expander'),
  collapser: $('#collapser'),
  store: 'session',
  storeKey: 'simple-tree-table-basic'
});
$('.datepicker-inline').datepicker({
  calendarWeeks: true,
  todayHighlight: true
});

var today = new Date();
var initialLangCode = 'fr';
$('#fc-views').fullCalendar({
  header: {
    left: 'prev,next today',
    center: 'title',
    right: 'month,agendaWeek,agendaDay'
  },
  eventClick: function (calEvent, jsEvent, view) {
    $('#evaluation-modal').modal('show');
  },
  lang: initialLangCode,
  locale: initialLangCode,
  allDaySlot: false,
  defaultDate: today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate(),
  buttonIcons: false, // show the prev/next text
  events: [{
    title: '+ Nommer, lire, écrire, représenter des nombres entiers',
    start: '2019-09-01',
    end: '2019-09-05',
    color: '#3B86FF'
  },
  {
    title: '+ Utiliser diverses représentations des nombres (écritures en chiffres et en lettres, noms à l’oral, graduations sur une demi-droite, constellations sur des dés, doigts de la main…).',
    start: '2019-09-01',
    end: '2019-09-04',
    color: '#3B86FF'
  },
  {
    title: '+ Nommer, lire, écrire, représenter des nombres entiers',
    start: '2019-09-07',
    end: '2019-09-11',
    color: '#3B86FF'
  },
  {
    title: '+ Nommer, lire, écrire, représenter des nombres entiers',
    start: '2019-09-15',
    end: '2019-09-18',
    color: '#3B86FF'
  },
  {
    title: '+ Résoudre des problèmes en utilisant des nombres entiers et le calcul',
    start: '2019-09-18',
    end: '2019-09-20',
    color: '#CE60CE'
  },
  {
    title: '+ Résoudre des problèmes en utilisant des nombres entiers et le calcul',
    start: '2019-09-22',
    end: '2019-09-27',
    color: '#CE60CE'
  },
  {
    title: '+ Résoudre des problèmes en utilisant des nombres entiers et le calcul',
    start: '2019-09-29',
    end: '2019-09-30',
    color: '#CE60CE'
  },
  ]
});

var accordion = (function () {

  var $accordion = $('.js-accordion');
  var $accordion_header = $accordion.find('.js-accordion-header');
  var $accordion_item = $('.js-accordion-item');

  // default settings 
  var settings = {
    // animation speed
    speed: 400,

    // close all other accordion items if true
    oneOpen: false
  };

  return {
    // pass configurable object literal
    init: function ($settings) {
      $accordion_header.on('click', function () {
        accordion.toggle($(this));
      });

      $.extend(settings, $settings);

      // ensure only one accordion is active if oneOpen is true
      if (settings.oneOpen && $('.js-accordion-item.active').length > 1) {
        $('.js-accordion-item.active:not(:first)').removeClass('active');
      }

      // reveal the active accordion bodies
      $('.js-accordion-item.active').find('> .js-accordion-body').show();
    },
    toggle: function ($this) {

      if (settings.oneOpen && $this[0] != $this.closest('.js-accordion').find('> .js-accordion-item.active > .js-accordion-header')[0]) {
        $this.closest('.js-accordion')
          .find('> .js-accordion-item')
          .removeClass('active')
          .find('.js-accordion-body')
          .slideUp()
      }

      // show/hide the clicked accordion item
      $this.closest('.js-accordion-item').toggleClass('active');
      $this.next().stop().slideToggle(settings.speed);
    }
  }
})();

$(document).ready(function () {
  accordion.init({ speed: 300, oneOpen: true });
});

$(document).ready(function () {

  $('.competences-item-page-container ul.tabs li').click(function () {
    var tab_id = $(this).attr('data-tab');

    $('.competences-item-page-container ul.tabs li').removeClass('current');
    $('.competences-item-page-container .tab-content').removeClass('current');

    $(this).addClass('current');
    $("#" + tab_id).addClass('current');
  })

});

var $idAction = 0;
$('.table-note-action li').click(function () {
  var posX = $(this).offset().left,
    posY = $(this).offset().top;
  $('#action-coleurs').css({
    top: posY - $(this).height(),
    left: posX - ($(this).width() * 2),
    display: 'block',
  });
  $idAction = $(this).attr('id');
});
$('#action-coleurs div').click(function () {
  $('#action-coleurs').css('display', 'none')
  $('#' + $idAction).find('input').val($(this).attr('data-id'));
  $('#' + $idAction).css('backgroundColor', $(this).attr('data-color'));
});

$('.table-reporting-container td').click(function () {
  var posX = $(this).offset().left,
    posY = $(this).offset().top;
  $('.table-reporting-container td').removeClass('active');
  $(this).addClass('active');
  $('#eleve-evaluation-details').appendTo($(this));
  $('#eleve-evaluation-details').css({
    display: 'block',
  });
});
$(document).on('click', '#eleve-evaluation-details .footer-eleve-evaluation span', function () {
  $('.table-reporting-container td').removeClass('active');
  $('#eleve-evaluation-details').css({
    display: 'none',
  });
});

$('.treetable-table tr[data-node-id]').each(function () {
  if ($(this).hasClass('tree-closed')) {
    $('.table-eleve-tree [data-parent="' + $(this).attr('data-node-id') + '"]').css({
      display: 'none',
    });
  }
});
$('.treetable-table tr span').click(function () {
  var dataNodeId = $(this).parents('tr').attr("data-node-id");
  var dataNodeIdChild = $('.table-eleve-tree [data-parent="' + dataNodeId + '"]').attr("data-id");
  if ($(this).parents('tr').hasClass('tree-opened')) {
    if (!$('.treetable-table tr [data-node-id="' + dataNodeIdChild + '"]').hasClass('tree-opened')) {
      $('.table-eleve-tree [data-parent="' + dataNodeIdChild + '"]').css('display', 'none');
    }
    $('.table-eleve-tree [data-parent="' + dataNodeId + '"]').css('display', 'none');
  } else {
    if ($('.treetable-table tr[data-node-id="' + dataNodeIdChild + '"]').hasClass('tree-opened')) {
      $('.table-eleve-tree [data-parent="' + dataNodeIdChild + '"]').css(
        {
          display: 'table-row',
          width: '100%'
        }
      );
    }
    $('.table-eleve-tree [data-parent="' + dataNodeId + '"]').css({
      display: 'table-row',
      width: '100%'
    });
  }
});

// menu 
// init the menu 
// toggle menu 

$('.toggle_menu').on('click', function (e) {
  if ($('body').hasClass('menu-expanded')) {
    localStorage.setItem('collapsed', true);
    $('body').addClass('menu-collapsed').removeClass('menu-expanded');
  } else {
    localStorage.setItem('collapsed', false);
    $('body').removeClass('menu-collapsed').addClass('menu-expanded');
  }
});



/********
 *
 *  END MATRICES SCRITPS
 *
 * **** */
