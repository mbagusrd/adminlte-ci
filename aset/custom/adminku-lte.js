var selected_menu = function () {
    var menuItem = $("a.nav-link[href=\"" + window.location.href + "\"]");

    if (!menuItem.length) {
        return;
    }

    menuItem.addClass('active');

    menuItem.parents('ul.nav-treeview').each(function () {
        var treeview = $(this);
        var navItem = treeview.parent('li.nav-item');
        var navLink = treeview.prev('a.nav-link');

        navItem.addClass('menu-open menu-is-opening');
        navLink.addClass('active');
    });
}
var dt_search = function (id_tabel, obj) {
    var input = $("#" + id_tabel + "_filter input").unbind(),
        self = obj.api(),
        $searchButton = $('<button>').addClass('btn btn-primary').text('Cari').click(function () {
            self.search(input.val()).draw();
        }),
        $clearButton = $('<button>').addClass('btn btn-default').text('Reset').click(function () {
            input.val('');
            self.search('').draw();
        });

    $("#" + id_tabel + "_filter").append("&nbsp;", $searchButton, "&nbsp;", $clearButton);
    $("#" + id_tabel + "_filter input").keyup(function (e) {
        if (e.keyCode === 13) {
            self.search(input.val()).draw();
        }
    });
}
var init_datatable_server = function (id_tabel, url, columns, options) {
    var tableSelector = "#" + id_tabel;
    var dtObject;
    var settings = $.extend(true, {
        scrollY: 350,
        scrollX: true,
        ordering: false,
        paging: true,
        searching: true,
        select: 'single',
        processing: true,
        serverSide: true,
        ajax: url,
        columns: columns,
        initComplete: function () {
            dt_search(id_tabel, this);
        }
    }, options || {});

    if ($.fn.DataTable.isDataTable(tableSelector)) {
        dtObject = $(tableSelector).DataTable();
        dtObject.ajax.url(url).load(function () {
        }, false);
    } else {
        dtObject = $(tableSelector).DataTable(settings);
    }

    return dtObject;
};
var clear_form = function (id) {
    var allInputs = $("#" + id).serializeArray();
    $.each(allInputs, function (k, v) {
        var fieldSelector = "#" + id + " #" + v['name'];
        var elTag = $(fieldSelector).prop("tagName").toLowerCase();

        switch (elTag) {
            case "select":
                if ($(fieldSelector).hasClass("select2-hidden-accessible")) { $(fieldSelector).val(null).trigger('change'); } else { $(fieldSelector).val(''); }
                break;
            default:
                $(fieldSelector).val('');
        }
    });
};
var set_form = function (id, dataset) {
    var allInputs = $("#" + id).serializeArray();
    $.each(
        allInputs,
        function (k, v) {
            var fieldSelector = "#" + id + " #" + v['name'];
            var elTag = $(fieldSelector).prop("tagName").toLowerCase();

            if (typeof (dataset[v['name']]) != "undefined") {
                switch (elTag) {
                    case "input":
                        if ($(fieldSelector).hasClass("datepicker")) { $(fieldSelector).datepicker("update", dataset[v['name']]); } else if ($(fieldSelector).hasClass("number_format")) { $(fieldSelector).val(number_format(dataset[v['name']], 2)); } else { $(fieldSelector).val(dataset[v['name']]); }
                        break;
                    default:
                        $(fieldSelector).val(dataset[v['name']]);
                }
            }
        }
    );
};
var set_select2_value = function (elmID, id, text) {
    var newOption = new Option(text, id, true, true);
    $(elmID).html(newOption).trigger('change');
};
var init_select2_ajax = function (elmID, url, options) {
    var settings = $.extend(true, {
        ajax: {
            url: url,
            dataType: 'json'
        }
    }, options || {});

    return $(elmID).select2(settings);
};
var init_select2 = function (elmID, options) {
    return $(elmID).select2(options || {});
};
var pesan = function (pesan, waktu, tipe) {
    var detik = (waktu == true) ? 3000 : null;
    var tipe_swal = (!tipe) ? "info" : tipe;
    setTimeout(function () { swal({ title: "", type: tipe_swal, text: pesan, timer: detik, showConfirmButton: true, onBeforeOpen: function () { if (swal.isVisible()) { no_proses(); } } }); }, 300);
};
var proses = function () { swal({ title: "Harap Tunggu!", text: "Memproses data", type: "info", showConfirmButton: false, allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false, onBeforeOpen: function () { if (swal.isVisible()) { no_proses(); } }, onOpen: function () { swal.showLoading(); } }); };
var no_proses = function () { swal.close(); };
var swal_progress = function () { swal({ type: "info", title: "Harap Tunggu!", text: "Memproses data", html: "<div id=\"swal_pg\" class=\"text-center\"><b>0% (0/0)</b></div>", showConfirmButton: false, allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false, onBeforeOpen: function () { if (swal.isVisible()) { no_proses(); } }, onOpen: function () { swal.showLoading(); } }); };
var hapus_koma = function ($string) { return $string.toString().replace(/,/g, ''); };
var get_form_array = function (id_form) {
    var $new_array = {};
    var $data_form = $("#" + id_form).serializeArray();
    $.map($data_form, function (n, i) { $new_array[n['name']] = n['value']; });
    return $new_array;
};
var mode_laporan = function () {
    $('.layout-navbar-fixed .wrapper .content-wrapper').css({ 'min-width': 'fit-content' })
}

jQuery.validator.setDefaults({
    debug: true,
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass("is-invalid");
    }
});

$.fn.datepicker.defaults.format = "dd-mm-yyyy";
$.fn.datepicker.defaults.todayHighlight = true;
$.fn.datepicker.defaults.autoclose = true;
$.fn.datepicker.defaults.todayBtn = "linked";
$.fn.datepicker.defaults.orientation = "bottom auto";

// $.fn.modal.Constructor.prototype.enforceFocus = function() {};

$(document).ready(function () {
    selected_menu();

    setTimeout(function () {
        $('.preloader').fadeOut();
    }, 700);

    $(".datepicker").datepicker();

    $(".number_format").on("change", function () { $(this).val(number_format($(this).val(), 2)); });

    $('.select2').on('select2:select', function () {
        var closestForm = $(this).closest('form');

        if (closestForm.is("form")) {
            closestForm.valid();
        }
    });
});