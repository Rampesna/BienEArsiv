Inputmask({mask: "(999) 999-9999"}).mask(".phoneMask");

Inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: false,
    onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace("mailto:", "");
    },
    definitions: {
        "*": {
            validator: '[0-9A-Za-z!#$%&"*+/=?^_`{|}~\-]',
            cardinality: 1,
            casing: "lower"
        }
    }
}).mask(".emailMask");

function initializeMoneyInputMask() {
    Inputmask({
        mask: "*{1,20}.*{2,4}",
        definitions: {
            "*": {
                validator: '[0-9]',
                cardinality: 1,
                casing: "lower"
            },
        },
        placeholder: "0",
    }).mask(".moneyMask");
}

initializeMoneyInputMask();

$('.decimal').on("copy cut paste drop", function () {
    return false;
}).keyup(function () {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

$(".onlyNumber").keypress(function (e) {
    if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

function reformatDateForCalendar(date) {
    var formattedDate = new Date(date);
    return formattedDate.getFullYear() + '-' +
        String(formattedDate.getMonth() + 1).padStart(2, '0') + '-' +
        String(formattedDate.getDate()).padStart(2, '0') + 'T' +
        String(formattedDate.getHours()).padStart(2, '0') + ':' +
        String(formattedDate.getMinutes()).padStart(2, '0') + ':00';
}

function reformatDatetime(date) {
    var formattedDate = new Date(date);
    return formattedDate.getFullYear() + '-' +
        String(formattedDate.getMonth() + 1).padStart(2, '0') + '-' +
        String(formattedDate.getDate()).padStart(2, '0') + ' ' +
        String(formattedDate.getHours()).padStart(2, '0') + ':' +
        String(formattedDate.getMinutes()).padStart(2, '0') + ':00';
}

function reformatInvoiceNumber(datetime, number) {
    return (new Date(datetime)).getFullYear() + '-' + number.padStart(9, '0');
}

$(window).on('load', function () {
    $("#loader").fadeOut(250);
});

$.sum = function (arr) {
    var r = 0;
    $.each(arr, function (i, v) {
        r += +v;
    });
    return r;
}

function reformatNumberToMoney(number) {
    return parseFloat(number).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
}

function detectMobile() {
    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ) {
        return true;
    } else {
        return false;
    }
}

$(window).resize(function () {
    checkScreen();
});

checkScreen();

function checkScreen() {
    if (detectMobile() || $(window).width() < 950) {
        $('.showIfMobile').show();
        $('.hideIfMobile').hide();
        $('#DashboardQuickActions').hide();
        $('#defaultFooter').hide();
        $('#mobileFooter').show();
    } else {
        $('.showIfMobile').show();
        $('.hideIfMobile').show();
        $('#DashboardQuickActions').show();
        $('#defaultFooter').show();
        $('#mobileFooter').hide();
    }
}

(function ($) {
    /**
     * jqGrid Turkish Translation
     * Erhan Gündoğan (erhan@trposta.net)
     * http://blog.zakkum.com
     * Dual licensed under the MIT and GPL licenses:
     * http://www.opensource.org/licenses/mit-license.php
     * http://www.gnu.org/licenses/gpl.html
     **/
    $.jgrid = $.jgrid || {};
    $.extend($.jgrid,
        {
            defaults: {
                recordtext: "{0}-{1} listeleniyor. Toplam:{2}",
                emptyrecords: "Kayıt bulunamadı",
                loadtext: "Yükleniyor...",
                pgtext: "{0}/{1}. Sayfa"
            },
            search: {
                caption: "Arama...",
                Find: "Bul",
                Reset: "Temizle",
                odata: [
                    'eşit',
                    'eşit değil',
                    'daha az',
                    'daha az veya eşit',
                    'daha fazla',
                    'daha fazla veya eşit',
                    'ile başlayan',
                    'ile başlamayan',
                    'içinde',
                    'içinde değil',
                    'ile biten',
                    'ile bitmeyen',
                    'içeren',
                    'içermeyen'
                ],
                groupOps: [
                    {
                        op: "VE",
                        text: "tüm"
                    },
                    {
                        op: "VEYA",
                        text: "herhangi"
                    }
                ],
                matchText: " uyan",
                rulesText: " kurallar"
            },
            edit: {
                addCaption: "Kayıt Ekle",
                editCaption: "Kayıt Düzenle",
                bSubmit: "Gönder",
                bCancel: "İptal",
                bClose: "Kapat",
                saveData: "Veriler değişti! Kayıt edilsin mi?",
                bYes: "Evet",
                bNo: "Hayıt",
                bExit: "İptal",
                msg: {
                    required: "Alan gerekli",
                    number: "Lütfen bir numara giriniz",
                    minValue: "girilen değer daha büyük ya da buna eşit olmalıdır",
                    maxValue: "girilen değer daha küçük ya da buna eşit olmalıdır",
                    email: "geçerli bir e-posta adresi değildir",
                    integer: "Lütfen bir tamsayı giriniz",
                    url: "Geçerli bir URL değil. ('http://' or 'https://') ön eki gerekli.",
                    nodefined: " is not defined!",
                    novalue: " return value is required!",
                    customarray: "Custom function should return array!",
                    customfcheck: "Custom function should be present in case of custom checking!"
                }
            },
            view: {
                caption: "Kayıt Görüntüle",
                bClose: "Kapat"
            },
            del: {
                caption: "Sil",
                msg: "Seçilen kayıtlar silinsin mi?",
                bSubmit: "Sil",
                bCancel: "İptal"
            },
            nav: {
                edittext: " ",
                edittitle: "Seçili satırı düzenle",
                addtext: " ",
                addtitle: "Yeni satır ekle",
                deltext: " ",
                deltitle: "Seçili satırı sil",
                searchtext: " ",
                searchtitle: "Kayıtları bul",
                refreshtext: "",
                refreshtitle: "Tabloyu yenile",
                alertcap: "Uyarı",
                alerttext: "Lütfen bir satır seçiniz",
                viewtext: "",
                viewtitle: "Seçilen satırı görüntüle"
            },
            col: {
                caption: "Sütunları göster/gizle",
                bSubmit: "Gönder",
                bCancel: "İptal"
            },
            errors: {
                errcap: "Hata",
                nourl: "Bir url yapılandırılmamış",
                norecords: "İşlem yapılacak bir kayıt yok",
                model: "colNames uzunluğu <> colModel!"
            },
            formatter: {
                integer: {
                    thousandsSeparator: " ",
                    defaultValue: '0'
                },
                number: {
                    decimalSeparator: ".",
                    thousandsSeparator: " ",
                    decimalPlaces: 2,
                    defaultValue: '0.00'
                },
                currency: {
                    decimalSeparator: ".",
                    thousandsSeparator: " ",
                    decimalPlaces: 2,
                    prefix: "",
                    suffix: "",
                    defaultValue: '0.00'
                },
                date: {
                    dayNames: [
                        "Paz",
                        "Pts",
                        "Sal",
                        "Çar",
                        "Per",
                        "Cum",
                        "Cts",
                        "Pazar",
                        "Pazartesi",
                        "Salı",
                        "Çarşamba",
                        "Perşembe",
                        "Cuma",
                        "Cumartesi"
                    ],
                    monthNames: [
                        "Oca",
                        "Şub",
                        "Mar",
                        "Nis",
                        "May",
                        "Haz",
                        "Tem",
                        "Ağu",
                        "Eyl",
                        "Eki",
                        "Kas",
                        "Ara",
                        "Ocak",
                        "Şubat",
                        "Mart",
                        "Nisan",
                        "Mayıs",
                        "Haziran",
                        "Temmuz",
                        "Ağustos",
                        "Eylül",
                        "Ekim",
                        "Kasım",
                        "Aralık"
                    ],
                    AmPm: [
                        "am",
                        "pm",
                        "AM",
                        "PM"
                    ],
                    S: function (j) {
                        return j < 11 || j > 13 ? [
                            'st',
                            'nd',
                            'rd',
                            'th'
                        ][
                            Math.min((j - 1) % 10,
                                3)
                            ] : 'th'
                    },
                    srcformat: 'Y-m-d',
                    newformat: 'd/m/Y',
                    masks: {
                        ISO8601Long: "Y-m-d H:i:s",
                        ISO8601Short: "Y-m-d",
                        ShortDate: "n/j/Y",
                        LongDate: "l, F d, Y",
                        FullDateTime: "l, F d, Y g:i:s A",
                        MonthDay: "F d",
                        ShortTime: "g:i A",
                        LongTime: "g:i:s A",
                        SortableDateTime: "Y-m-d\\TH:i:s",
                        UniversalSortableDateTime: "Y-m-d H:i:sO",
                        YearMonth: "F, Y"
                    },
                    reformatAfterEdit: false

                },
                baseLinkUrl: '',
                showAction: '',
                target: '',
                checkbox: {
                    disabled: true
                },
                idName: 'id'

            }
        });
})(jQuery);

$('.modal').on('shown.bs.modal', function (e) {
    $(this).find('.select2Input').select2({
        dropdownParent: $(this).find('.modal-content')
    });
})
