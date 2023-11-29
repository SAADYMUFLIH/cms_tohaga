function Populate() {
  var favorite = [];
  $.each($("input[name='id_content']:checked"), function () {
    favorite.push($(this).val());
  });
  $("#tags").val(favorite.join("|"));
}
function init_grid_data_manipulation(btnname = "") {

  $(".select-all").click(function () {
    if (this.checked) {
      $(".chk-box").prop("checked", true);
    } else {
      $(".chk-box").prop("checked", false);
    }

    if ($(".chk-box:checked").length > 0) {
      if (btnname.length > 0) {

        jQuery.each(btnname, function (i, val) {
          $("#" + val).prop("disabled", false);
        });
      } else {
        $("#btndelete").prop("disabled", false);
      }
    } else {
      if (btnname.length > 0) {
        jQuery.each(btnname, function (i, val) {
          $("#" + val).prop("disabled", true);
        });
      } else {
        $("#btndelete").prop("disabled", true);
      }
    }
  });

  $(".chk-box").click(function () {
    if ($(".chk-box").length == $(".chk-box:checked").length) {
      $(".select-all").prop("checked", true);
    } else {
      $(".select-all").removeAttr("checked");
    }
    if ($(".chk-box:checked").length > 0) {
      if (btnname.length > 0) {
        jQuery.each(btnname, function (i, val) {
          $("#" + val).prop("disabled", false);
        });
      } else {
        $("#btndelete").prop("disabled", false);
      }
    } else {
      if (btnname.length > 0) {
        jQuery.each(btnname, function (i, val) {
          $("#" + val).prop("disabled", true);
        });
      } else {
        $("#btndelete").prop("disabled", true);
      }
    }
  });
}

function delete_record(url, frmname, btnname = 'btndelete') {
  var data = $("#" + frmname + "").serialize();
  //$("#loader").show();
  $.ajax({
    url: url, // link of your "whatever" php
    type: "POST",
    async: true,
    cache: false,
    data: data, // all data will be passed here
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(xhr.responseText);
      console.log(thrownError);
      // $("#loader").hide();
    },
    success: function (response) {
      toastr.options = {
        timeOut: 3000,
        positionClass: "toast-bottom-right"
      };
      toastr["info"](response);
      searchFilter(document.getElementById("page_pos").value);
      $("#confirm_del").modal("hide");
      $("#" + btnname + "").prop("disabled", true);
      // $("#loader").hide();
    }
  });
}


function edit_record(id, url, list_container, form_container, tbl_name = "") {
  url = url;
  $("#" + list_container + "").hide("slow");
  $("#" + form_container + "").show("slow");

  $.ajax({
    type: "POST",
    async: true,
    url: url,
    data: {
      the_id: id,
      tblname: tbl_name
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(xhr.responseText);
      console.log(thrownError);
    },
    success: function (data) {
      $("#" + form_container + "").html(data);
    }
  });
}

function editdata_popup(id, url, edit_container, modal_id, tbl_name) {
  $("#loader").show();
  $("#" + modal_id + "").modal({ backdrop: false });
  $.ajax({
    type: "POST",
    url: url,
    async: true,
    data: {
      the_id: id,
      tblname: tbl_name
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(xhr.responseText);
      console.log(thrownError);
      $("#loader").hide();
    },
    success: function (data) {
      $("#" + edit_container + "").html(data);
      $("#" + edit_container + "").show();
      $("#" + modal_id + "").modal("show");
      $("#loader").hide();
    }
  });
}

function cascade_dropdown(url, triger_object, impacted_object, single_selection = true) {
  $("#" + triger_object + "").change(function () {
    $("#" + impacted_object + "").empty();
    $.ajax({
      type: "POST",
      url: url,
      dataType: "json",
      async: true,
      data: {
        kodex: $("#" + triger_object + "").val()
      },
      success: function (data) {

        if (single_selection == false) {
          $("#" + impacted_object + "")
            .prepend("<option value=''>-Please Select-</option>")
            .val("");
        }
        $.each(data, function (index, val) {
          $("#" + impacted_object + "").append(
            '<option value="' + val.kd + '">' + val.nm + "</option>"
          );
        });
        $("#" + impacted_object + "").trigger("chosen:updated");
        $("#" + impacted_object + "").trigger("liszt:updated");

      },
      error: function (ex) {
        $("#" + impacted_object + "").empty();
        $("#" + impacted_object + "").trigger("chosen:updated");
        $("#" + impacted_object + "").trigger("liszt:updated");

      }
    });
  });
}

function load_content(url_page, container_id, id = "", method_name = "", id_menu = "") {

  $("#loader").show();
  $.ajax({
    type: "POST",
    async: true,
    url: url_page,
    data: {
      the_id: id,
      mtd_name: method_name
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(xhr.responseText);
      console.log(thrownError);
    },
    success: function (data) {

      $("#loader").hide();
      $("#" + container_id + "").hide().html(data).fadeIn("slow");

      $(".id_menu").removeClass("mm-active");
      $("#id_menu-" + id_menu).addClass("mm-active");
    }
  });
}

function send_data(url, frmname, dtl_container, modal_id, btnname = 'btndelete') {
  $("#img_mdl_loader").show();
  $("#" + modal_id + "").modal({
    backdrop: false
  });
  var data = $("#" + frmname + "").serialize();
  $.ajax({
    url: url, // link of your "whatever" php
    type: "POST",
    async: true,
    cache: false,
    data: data, // all data will be passed here
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(xhr.responseText);
      console.log(thrownError);
      //doModal('Error Info', xhr.responseText);
    },
    success: function (response) {
      $("#" + dtl_container + "").html(response);
      $("#" + dtl_container + "").show();
      $("#" + modal_id + "").modal("show");
      $("#img_mdl_loader").hide();
    }
  });
}


function load_modal_dtl_popup(id, url, dtl_container, modal_id) {
  $("#img_mdl_loader").show();
  $("#" + modal_id + "").modal({
    backdrop: false
  });
  $.ajax({
    type: "POST",
    url: url,
    data: {
      the_id: id
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(xhr.responseText);
      console.log(thrownError);
    },
    success: function (data) {
      $("#" + dtl_container + "").html(data);
      $("#" + dtl_container + "").show();
      $("#" + modal_id + "").modal("show");
      $("#img_mdl_loader").hide();
    }
  });
}

function retrieveDataFromDb(page_num, uri, qryString, container_list) {
  page_num = page_num ? page_num : 0;
  document.getElementById("page_pos").value = page_num;
  var keywords = $("#keywords").val();
  var sortBy = $("#sortBy").val();
  $(".loading").show();
  $.ajax({
    type: "POST",
    url: uri,
    async: true,
    data: qryString,
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(xhr.status);
      console.log(xhr.responseText);
      console.log(thrownError);
    },
    success: function (html) {
      $("#" + container_list + "").html(html);
      $(".loading").hide();
    }
  });
}

function NumberPostiveNegativeWithDecimal(evt, element) {
  var charCode = evt.which ? evt.which : event.keyCode;
  if (
    (charCode != 45 ||
      $(element)
        .val()
        .indexOf("-") != -1) &&
    (charCode != 46 ||
      $(element)
        .val()
        .indexOf(".") != -1) &&
    (charCode < 48 || charCode > 57)
  )
    evt.preventDefault();
  return true;
}

function NumberPostiveDecimal(evt, element) {
  var charCode = evt.which ? evt.which : event.keyCode;
  if (
    (charCode != 46 ||
      $(element)
        .val()
        .indexOf(".") != -1) &&
    (charCode < 48 || charCode > 57)
  )
    evt.preventDefault();
  return true;
}

function AllowNumberOnly(evt) {

  var theEvent = evt || window.event;
  // Handle paste
  if (theEvent.type === "paste") {
    key = event.clipboardData.getData("text/plain");
  }
  var charCode = evt.which ? evt.which : event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
  return true;
}

function thousandSparator(event, element) {
  if (event.which >= 37 && event.which <= 40) return;
  // format number
  $(element).val(function (index, value) {
    return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  });
}

function addCommas(nStr) {
  nStr += "";
  var x = nStr.split(".");
  var x1 = x[0];
  var x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
}

function currencyFormat(num) {
  return num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}

function number_only(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === "paste") {
    key = event.clipboardData.getData("text/plain");
  } else {
    // Handle key press
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if (!regex.test(key)) {
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}

function getCodeByFirstChar(str, objOutPut) {
  let acronym = str
    .split(/\s/)
    .reduce((response, word) => (response += word.slice(0, 1)), "");
  let upper_acronym = acronym.toUpperCase();
  $("#" + objOutPut + "").val(upper_acronym);
}

function store_value_from_checkbox(elm) {
  var checkboxes = document.getElementsByClassName("checkbox-btn");
  var selected = [];
  for (var i = 0; i < checkboxes.length; ++i) {
    if (checkboxes[i].checked) {
      selected.push(checkboxes[i].value);
    }
  }
  document.getElementById("hdn_size").value = selected.join();
  generate_tab(document.getElementById("hdn_size").value);
  //document.getElementById("total").innerHTML = selected.length;
}
