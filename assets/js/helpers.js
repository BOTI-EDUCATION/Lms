

function getQuery() {
  query = location.search.slice(1).split("&");
  return query.map(function (value) {
    return value.split("=");
  });
}

function getValueOfInput(input) {
  const type = input.attr("type");
  if ("checkbox" == type) {
    return input.is(":checked");
  }
  return input.val();
}

function domTopPdf(el, name, format = "a3") {
  var opt = {
    margin: 0.2,
    html2canvas: {
      scale: 1.2,
    },
    filename: name + ".pdf",
    jsPDF: { unit: "in", format: format, orientation: "p" },
    pagebreak: { before: ".breakpage" },
  };
  html2pdf().set(opt).from(el).save();
}


// Auto Update Translations
function ajaxUpdateConfig(url, id, value) {
  // Prevent closing the page before the ajax query ends
  window.ajaxUpdates = window.ajaxUpdates || 0;
  // Increment for each ajax call
  window.ajaxUpdates++;
  $.ajax({
    url: url,
    type: "post",
    data: {
      op: "config-update",
      id: id,
      value: value,
    },
    success: function (response) {
      // Decrement when ajax call ends
      window.ajaxUpdates--;
      try {
        response = $.parseJSON(response);
      } catch (e) {
        alert("Une erreur s'est produite (2)");
        return false;
      }
      if (response.type != "OK" && response.type != "ERR") {
        alert("Une erreur s'est produite (3)");
        return false;
      }
      if (response.type == "ERR") {
        alert(response.msg + " (4)");
        return false;
      }

      console.log("Operations left: " + window.ajaxUpdates);
    },
    error: function () {
      // Decrement when ajax call ends
      window.ajaxUpdates--;
      alert("Une erreur s'est produite (1)");
      return false;
    },
  });
}

function paramsAllowDrop(ev) {
  ev.preventDefault();
}


function linkParamToTab(tab, param) {
  console.log(tab);
  $.ajax({
    action: "params",
    method: "post",
    data: {
      op: "link_params",
      tab: tab,
      param: param,
    },
  }).done(function () {
    console.log("ok");
  });
}

function paramsDrag(ev) {
  console.log("drag :" + ev.target.id);
  ev.dataTransfer.setData("text", ev.target.id);
}

function paramsDrop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  console.log("drop :" + data);
  let to = $(ev.target.getAttribute("href"));
  let ele = $("#" + data);
  to.append(ele);
  linkParamToTab(to.data("tab"), ele.data("param"));
}

/* ------------------------------ Required Functions */
function ajax(type, params, callback, error, complete, upload, link) {
  if (!error)
    error = function (msg, code) {
      if (code) console.log("Ajax Error Code: " + code);
      alert(msg);
    };
  if (!complete) complete = $.noop;
  $.ajax({
    url: link ? link : app.url.base + "ajax",
    type: type,
    data: params,
    cache: !upload ? true : false,
    contentType: !upload
      ? "application/x-www-form-urlencoded; charset=UTF-8"
      : false,
    processData: !upload ? true : false,
    success: function (response) {
      try {
        response = $.parseJSON(response);
      } catch (e) {
        error("Une erreur s'est produite", 2);
        return false;
      }
      if (response.type != "OK" && response.type != "ERR") {
        error("Une erreur s'est produite", 3);
        return false;
      }
      if (response.type == "ERR") {
        error(response.msg, response.code);
        return false;
      }

      callback.call(this, response.data);
    },
    error: function () {
      error.call(this, "Une erreur s'est produite", 1);
    },
    complete: function () {
      complete.call(this);
    },
  });
}

$(document).on("submit", ".send_as_ajax_last", function (e) {
  e.preventDefault();
  console.log("called");
  _this = $(this);
  const reload = _this.data("reload");
  const href = _this.data("href");
  const closest = _this.data("closest");
  const fillmodal = _this.data("fillmodal");
  const openmodal = _this.data("openmodal");
  const modal = _this.data("modal");
  const fnc = _this.data("fnc");
  const load = _this.data("noload") ? false : true;
  const loading = _this.data("loading");
  const showSwal = _this.data("noswal") ? false : true;
  const confirm = _this.data("confirm");
  type = loading && _this.data("type") ? _this.data("type") : "";
  responsePanel = _this.data("response") ? _this.data("response") : "";

  var formData = new FormData(this);

  var submitBtn, html_loading, old_html;

  if (load) {
    submitBtn = _this.find('button[type="submit"]:focus').length
      ? _this.find('button[type="submit"]:focus')
      : _this.find('input[type="submit"]');
    html_loading =
      '<i class="fa fa-spinner fa-spin" style="margin:3px"></i>  Loading';
    old_html = submitBtn.html();
    submitBtn.html(html_loading);
  }

  //if (responsePanel) $(responsePanel).html('');

  if (type) {
    const loadingPanel = "#" + type + "-loading";
    const loadingHTML = '<i class="fa fa-spinner fa-pulse"></i>';
    $(loadingPanel).html(loadingHTML);
  }

  const requestFinish = (data) => {
    if (reload) {
      location.reload();
    }
    if (href) {
      location.href = href;
    }
    if (closest) {
      _this.closest(closest).remove();
    }
    if (fillmodal) {
      $(fillmodal).html(data.html);
      $(modal).modal("show");
    } else if (openmodal) {
      $(modal).modal("show");
    } else if (modal) {
      $(modal).modal("hide");
    }
    if (fnc) {
      window[fnc](_this, data);
    }
  };

  const ajaxRequest = () => {
    $.ajax({
      method: _this.attr("method"),
      url: _this.attr("action"),
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
    })
      .done(function (_data) {
        if (load) submitBtn.html(old_html);
        const data = JSON.parse(_data);
        if (type) $("#" + type + "-loading").html("");
        if (responsePanel) $(responsePanel).html(data.html);
        $(".data-table").DataTable();
        if (showSwal) {
          swal({
            title: data.msg,
            icon: "success",
            timer: 2000,
          }).then(function () {
            requestFinish(data);
          });
        } else {
          requestFinish(data);
        }
      })
      .fail(function (data) {
        if (load) submitBtn.html(old_html);
        if (type) $("#" + type + "-loading").html("");
        if (responsePanel) $(responsePanel).html("");
        data = JSON.parse(data.responseText);
        if (showSwal) {
          swal({
            icon: "error",
            title: data.msg,
          });
        }
      });
  };

  if (confirm) {
    swal({
      title: "Êtes-vous sûr?",
      text: "Vous ne pourrez pas récupérer!",
      icon: "warning",
      buttons: ["Non, annulez-le!", "Oui je suis sûr!"],
      dangerMode: true,
    }).then(function (isConfirm) {
      if (isConfirm) {
        ajaxRequest();
      } else {
        swal("Annulé", "", "error");
      }
    });
  } else {
    ajaxRequest();
  }
});

$(document).on("submit", ".send_as_ajax", function (e) {
  e.preventDefault();
  console.log("called");
  _this = $(this);
  const reload = _this.data("reload");
  const href = _this.data("href");
  const closest = _this.data("closest");
  const fillmodal = _this.data("fillmodal");
  const openmodal = _this.data("openmodal");
  const modal = _this.data("modal");
  const fnc = _this.data("fnc");
  const load = _this.data("noload") ? false : true;
  const loading = _this.data("loading");
  const showSwal = _this.data("noswal") ? false : true;
  const confirm = _this.data("confirm");
  type = loading && _this.data("type") ? _this.data("type") : "";
  responsePanel = _this.data("response") ? _this.data("response") : "";

  var formData = new FormData(this);

  const requestFinish = function (data) {
    if (reload) {
      location.reload();
    }
    if (href) {
      location.href = href;
    }
    if (closest) {
      _this.closest(closest).remove();
    }
    if (fillmodal) {
      $(fillmodal).html(data.html);
      $(modal).modal("show");
    } else if (openmodal) {
      $(modal).modal("show");
    } else if (modal) {
      $(modal).modal("hide");
    }
    if (fnc) {
      window[fnc](_this, data);
    }
  };

  const ajaxRequest = function () {
    var submitBtn, html_loading, old_html;

    // if (load) {
    submitBtn = _this.find('button[type="submit"]').length
      ? _this.find('button[type="submit"]')
      : _this.find('input[type="submit"]');
    html_loading =
      '<i class="fa fa-spinner fa-spin" style="margin:3px"></i>  Loading';
    old_html = submitBtn.html();
    submitBtn.html(html_loading);
    // }

    if (type) {
      const loadingPanel = "#" + type + "-loading";
      const loadingHTML = '<i class="fa fa-spinner fa-pulse"></i>';
      $(loadingPanel).html(loadingHTML);
    }

    // if (responsePanel) $(responsePanel).html('');

    $.ajax({
      method: _this.attr("method"),
      url: _this.attr("action"),
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
    })
      .done(function (_data) {
        if (load) submitBtn.html(old_html);
        const data = JSON.parse(_data);
        if (type) $("#" + type + "-loading").html("");
        if (responsePanel) $(responsePanel).html(data.html);
        $(".data-table").DataTable();
        if (showSwal) {
          swal({
            title: data.msg,
            icon: "success",
            timer: 2000,
          }).then(function () {
            requestFinish(data);
          });
        } else {
          requestFinish(data);
        }
      })
      .fail(function (data) {
        if (load) submitBtn.html(old_html);
        if (type) $("#" + type + "-loading").html("");
        if (responsePanel) $(responsePanel).html("");
        data = JSON.parse(data.responseText);
        if (showSwal) {
          swal({
            icon: "error",
            title: data.msg,
          });
        }
      });
  };

  if (confirm) {
    swal({
      title: _this.data("title"),
      text: _this.data("body"),
      icon: "warning",
      buttons: ["Non, annulez-le!", "Oui je suis sûr!"],
      dangerMode: true,
    }).then(function (isConfirm) {
      if (isConfirm) {
        ajaxRequest();
      } else {
        swal("Annulé", "", "error");
      }
    });
  } else {
    ajaxRequest();
  }
});

$(document).on("click", ".form_action", function (e) {
  e.preventDefault();
  closest_form = $(this).closest("form-action");
  const reload = closest_form.data("reload");
  const href = closest_form.data("href");
  const closest = closest_form.data("closest");
  const modal = closest_form.data("modal");
  const fnc = closest_form.data("fnc");

  // append input value data to fro
  var formData = new FormData();
  closest_form.find("input").each(function (i, ele) {
    let input = $(ele);
    let type = input.attr("type");

    if ("checkbox" == type) {
      if (input.is(":checked") && !input.prop("disabled")) {
        formData.append(input.attr("name"), input.val());
      }
    } else {
      formData.append(input.attr("name"), input.val());
    }
  });

  closest_form.find("textarea").each(function (i, ele) {
    console.log("hello world");
    formData.append($(ele).attr("name"), $(ele).val());
  });

  // add loading
  var submitBtn = closest_form.find('button[type="submit"]').length
    ? closest_form.find('button[type="submit"]').last()
    : closest_form.find('input[type="submit"]').last();
  const html_loading =
    '<i class="fa fa-spinner fa-spin" style="margin:3px"></i>  Loading';
  const old_html = submitBtn.html();
  submitBtn.html(html_loading);

  $.ajax({
    method: closest_form.attr("method") || "GET",
    url: closest_form.attr("action") || "",
    data: formData,
    cache: false,
    processData: false,
    contentType: false,
  })
    .done(function (_data) {
      submitBtn.html(old_html);
      const data = JSON.parse(_data);
      swal({
        title: data.msg,
        icon: "success",
        timer: 2000,
      }).then(function () {
        if (reload) {
          location.reload();
        }
        if (href) {
          location.href = href;
        }
        if (closest) {
          closest_form.closest(closest).remove();
        }
        if (modal) {
          $(modal).modal("hide");
        }
        if (fnc) {
          window[fnc](closest_form, data);
        }
      });
    })
    .fail(function (data) {
      submitBtn.html(old_html);
      data = JSON.parse(data.responseText);
      swal({
        icon: "error",
        title: data.msg,
      });
    });
});

$(document).on("click", ".click_ajax", function (e) {
  console.log("called");
  _this = $(this);
  const fnc = _this.data("fnc");
  var data = _this.data("data") ? _this.data("data") : {};
  $.ajax({
    method: "GET",
    url: _this.data("action"),
    data: data,
  })
    .done(function (_data) {
      const data = JSON.parse(_data);
      if (fnc) {
        window[fnc](_this, data);
      }
    })
    .fail(function (data) {
      data = JSON.parse(data.responseText);
      swal({
        title: data.msg,
      });
    });
});

$(document).on("click", ".dropify", function () {
  $(this).closest("form").find('input[name="delete_image"]').remove();
});

$(document).on("click", ".dropify-clear", function () {
  $("<input />")
    .attr("name", "delete_image")
    .attr("type", "hidden")
    .val(1)
    .appendTo($(this).closest("form"));
});

$(document).on("submit", ".one_submit", function (e) {
  $(this).find('[type="submit"]').attr("disabled", true);
  return true;
});

$(document).on("change", ".toggle-checkbox", function (e) {
  _this = $(this);
  $(_this.data("target")).prop("checked", _this.is(":checked"));
});

$(document).on("click", ".reste_input", function (e) {
  _this = $(this);
  $(_this.data("target")).val($(_this.data("input")).val());
});

$(document).on("change", ".fill_select", function (event) {
  _this = $(this);
  const targetSelect = _this.data("target");
  const url = _this.data("action");
  const optional = _this.data("optional");
  const dataName = _this.data("name");
  const dataVal = _this.val();

  $.ajax({
    method: "get",
    url: url,
    data: {
      name: dataName,
      val: dataVal,
    },
  }).done(function (res) {
    $(targetSelect).html("");
    const data = JSON.parse(res);
    if (optional)
      $(targetSelect).append($("<option />").val("all").html(optional));

    data.forEach(function (value, index) {
      $(targetSelect).append($("<option />").val(value.id).html(value.label));
    });

    $(targetSelect).trigger("change");
  });

});

$(document).on("change", ".fill_selects", function (event) {
  _this = $(this);
  const targetSelects = _this.data("targets").split(',');
  const url = _this.data("action");
  const dataVal = _this.val();

  $.ajax({
    method: "get",
    url,
    data: {
      id: dataVal,
    },
  }).done(function (res) {
    const data = JSON.parse(res);
    targetSelects.forEach(function (target, index) {
      $(target).html("");
      data.selects[index].forEach(value => {
        console.log(index);
        $(target).append(
          $("<option />")
            .val(value.id)
            .html(value.label)
        );
      });
      $(target).trigger("change");
    });
  });
});


$(".delete").click(function (e) {
  if (
    !confirm(
      "vous êtes sur le point de supprimer cet objet\r\nVoulez-vous continuer ?"
    )
  ) {
    e.preventDefault();
  }
});

$(document).ready(function () {
  $(".confirm-link").on("click", function (e, data) {
    if (!data) {
      handleDelete(e, 1, $(this), $(this).data("message"));
    } else {
      window.location = $(this).attr("href");
    }
  });
});

$(".rest_form").on("click", function () {
  console.log($(this).closest("form"));
  $(this).closest("form").trigger("reset");
  $(this).closest("form").find(".select2").select2().val("").trigger("change");
});


$(document).on("click", ".loadcontent", function () {
  _this = $(this);
  const action = _this.data("action");
  const laodto = _this.data("loadto");
  $(laodto).html("");
  $(laodto).load(action);
});


$(document).on("click", ".delete-action", function (e) {
  e.preventDefault();
  _this = $(this);

  _action = _this.data("action");
  _closest = _this.data("closest");
  _refresh = _this.data("refresh");
  const href = _this.attr("href");
  swal({
    title: "Êtes-vous sûr?",
    text: "Vous ne pourrez pas récupérer!",
    icon: "warning",
    buttons: ["Non, annulez-le!", "Oui je suis sûr!"],
    dangerMode: true,
  }).then(function (isConfirm) {
    if (isConfirm) {
      $.ajax({
        method: "post",
        url: _action,
      })
        .done(function (data) {
          _this.closest(_closest).remove();
          if (_refresh) {
            location.reload();
          }

          if (href) {
            console.log("hrere");
            location.href = href;
          }
        })
        .fail(function (data) {
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

$(document).on("click", ".href-action", function (e) {
  _this = $(this);
  e.preventDefault();
  _action = _this.data("action");
  _closest = _this.data("closest");
  _refresh = _this.data("refresh");
  _text = _this.data("text");
  swal({
    title: "Êtes-vous sûr?",
    text: _text,
    icon: "warning",
    buttons: ["Non, annulez-le!", "Oui je suis sûr!"],
  }).then(function (isConfirm) {
    if (isConfirm) {
      $.ajax({
        method: "post",
        url: _action,
      })
        .done(function (data) {
          _this.closest(_closest).remove();
          if (_refresh) {
            location.reload();
          }
        })
        .fail(function (data) {
          swal({
            title: data.responseText,
            icon: "warning",
          });
        });
    } else {
      swal("Annulé", "", "error");
    }
  });
});

$(document).on("click", ".toggle_elements", function () {
  _this = $(this);
  const hide_classe = _this.data("to_hide");
  const show_classe = _this.data("to_show");
  $(hide_classe).hide();
  $(show_classe).show();
});

$(document).on("click", ".toggle_element", function () {
  _this = $(this);
  const togle_classe = _this.data("toggle");
  $(togle_classe).toggle();
});

$(".open_if_checked").on("change", function () {
  _this = $(this);
  $(_this.data("ele-hide-if-checked")).hide();
  const toggle_classe = _this.data("ele-open-if-checked");
  if (_this.is(":checked")) {
    $(toggle_classe).show();
  } else {
    $(toggle_classe).hide();
  }
});

$(".enable-input-range").change(function () {
  var checked = $(this).is(":checked");
  target = $(this).data("target") ? $(this).data("target") : "#periode-range";
  console.log(target);
  if (checked) $(target).prop("disabled", false);
  else $(target).prop("disabled", true);
});

$(document).on("keyup", ".max-value", function () {
  // Check correct, else revert back to old value.
  _this = $(this);
  if (parseInt(_this.val()) > parseInt(_this.attr("max"))) {
    _this.val(parseInt(_this.attr("max")));
  }
});

$(document).on("click", ".instant-edit", function (event) {
  _this = $(this);

  $(this)
    .closest(".instant-edit-container")
    .find(".edit")
    .toggle(
      function () {
        _this.css("color", "");
      },
      function () {
        $(this).css("color", "");
      }
    );

  $(this).closest(".instant-edit-container").find(".label").toggle();
});