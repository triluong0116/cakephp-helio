/**
 * Created by D4rk on 11/07/2018.
 */
$(document).ready(function () {
    /* common.js by D4rk*/
    $('.bxslider-home').show().bxSlider({
        controls: false,
        //        auto: true,
        pause: 2000,
        mode: 'fade',
        pagerCustom: $('.bx-pager')
    });
    $('#lightgallery').lightGallery();
    $('.lightgallery2').lightGallery();
    $('#viewdetail').click(function () {
        $([document.documentElement, document.body]).animate({
            scrollTop: $('#detail').offset().top
        }, 1000);
    });
    $('.select2').select2();
    $('.datepicker').datetimepicker({
        format: 'DD-MM-YYYY',
        ignoreReadonly: true
    });
    $('.custom-daterange-picker').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $('p.star-rating').each(function () {
        var point = $(this).data('point'),
            html = '';

        for (var i = 1; i <= 5; i++) {
            if (i <= point) {
                html += '<span class="fas fa-star"></span>';
            } else {
                html += '<span class="far fa-star"></span>';
            }
        }

        $(this).prepend(html);
    });

    if ($('ul#filter-location').length) {
        var strListId = $('ul#filter-location').data('list-selected-location') + '';
        if (strListId) {
            var lists = strListId.split(',');
            Frontend.listLocation = $.map(strListId.split(','), function (value) {
                return parseInt(value, 10);
            });
        }
    }
    if ($('ul#filter-price').length) {
        var strListId = $('ul#filter-price').data('list-selected-price') + '';
        if (strListId) {
            Frontend.listPrice = $.map(strListId.split(','), function (value) {
                return value;
            });
        }
    }
    if ($('ul#filter-rating').length) {
        var strListId = $('ul#filter-rating').data('list-selected-rating') + '';
        if (strListId) {
            var lists = strListId.split(',');
            Frontend.listRating = $.map(strListId.split(','), function (value) {
                return parseInt(value, 10);
            });
        }
    }

    $('input.iCheck').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'icheckbox_flat-blue'
    });
    $('input.iCheck').on('ifCreated', function (e) {
    });
    $('input.iCheck.location-checkbox').on('ifChecked', function (event) {
        Frontend.filterLocation($(this));
    });
    $('#location-filter').change(function () {
        Frontend.filterLocation($(this));
    });
    $('#price-filter').change(function () {
        Frontend.filterPrice($(this));
    });
    $('#rating-filter').change(function () {
        Frontend.filterRating($(this));
    });
    $('input.iCheck.location-checkbox').on('ifUnchecked', function (event) {
        Frontend.uncheckFilterLocation($(this));
    });
    $('input.iCheck.price-checkbox').on('ifChecked', function (event) {
        Frontend.filterPrice($(this));
    });
    $('input.iCheck.price-checkbox').on('ifUnchecked', function (event) {
        Frontend.uncheckFilterPrice($(this));
    });
    $('input.iCheck.rating-checkbox').on('ifChecked', function (event) {
        Frontend.filterRating($(this));
    });
    $('input.iCheck.rating-checkbox').on('ifUnchecked', function (event) {
        Frontend.uncheckFilterRating($(this));
    });


    $('#modal-fb-finish-2').on('hidden.bs.modal', function () {
        window.location.href = baseUrl + '/chinh-sach-cong-tac-vien-page-2';
    });

    $('#finishWithdraw').on('hidden.bs.modal', function () {
        window.location.reload();
    });

    $('#modal-fb-finish').on('hidden.bs.modal', function () {
        Frontend.checkZaloStatus();
    });

    $('#modal-update-info').on('hidden.bs.modal', function () {
        window.location.href = baseUrl + '/chinh-sach-cong-tac-vien-page-2';
    });

    $('#resultAgency').on('hidden.bs.modal', function () {
        window.location.href = baseUrl;
    });

    $('#location').multiselect({
        templates: {// Use the Awesome Bootstrap Checkbox structure
            button: '<button type="button" class="multiselect dropdown-toggle semi-bold fs118 text-left list-location" data-toggle="dropdown"><i class="fas fa-search text-light-blue"></i> Nơi bạn muốn đến</button>',
            li: '<li class="checkList mb10 mt10"><a tabindex="0"><div class="aweCheckbox aweCheckbox-danger"><label for=""></label></div></a></li>'
        },

        buttonWidth: '100%',
    });
    $('#price').multiselect({
        templates: {// Use the Awesome Bootstrap Checkbox structure
            button: '<button type="button" class="multiselect dropdown-toggle semi-bold fs118 text-left list-location" data-toggle="dropdown"><i class="fas fa-search text-light-blue"></i> Ngân sách của bạn</button>',
            li: '<li class="checkList mb10 mt10"><a tabindex="0"><div class="aweCheckbox aweCheckbox-danger"><label for=""></label></div></a></li>'
        },

        buttonWidth: '100%',
    });
    $('#rating').multiselect({
        templates: {// Use the Awesome Bootstrap Checkbox structure
            button: '<button type="button" class="multiselect dropdown-toggle semi-bold fs118 text-left list-location" data-toggle="dropdown"><i class="fas fa-search text-light-blue"></i> Hạng sao khách sạn</button>',
            li: '<li class="checkList mb10 mt10"><a tabindex="0"><div class="aweCheckbox aweCheckbox-danger check-rating"><label for=""></label></div></a></li>'
        },

        buttonWidth: '100%',
    });
    $('#slider-departure').val('').multiselect({
        templates: {// Use the Awesome Bootstrap Checkbox structure
            li: '<li class="checkList mb10 mt10"><a tabindex="0"><div class="aweCheckbox aweCheckbox-danger"><label for=""></label></div></a></li>'
        },
        buttonClass: 'multiselect dropdown-toggle btn btn-default btnDeparture',
        buttonWidth: '100%'
    });

    $('#slider-destination').val('').multiselect({
        templates: {// Use the Awesome Bootstrap Checkbox structure
            li: '<li class="checkList mb10 mt10"><a tabindex="0"><div class="aweCheckbox aweCheckbox-danger"><label for=""></label></div></a></li>'
        },
        buttonClass: 'multiselect dropdown-toggle btn btn-default btnDestination',
        buttonWidth: '100%'
    });
    if ($('.btnDeparture .multiselect-selected-text').length) {
        $('.btnDeparture .multiselect-selected-text')[0].innerHTML = '<i class="fas fa-search text-light-blue mr10"></i> Nơi bạn xuất phát';
    }
    if ($('.btnDestination .multiselect-selected-text').length) {
        $('.btnDestination .multiselect-selected-text')[0].innerHTML = '<i class="fas fa-map-marker-alt red-color mr10"></i> Nơi bạn muốn đến';
    }

    $('.multiselect-container div.aweCheckbox').each(function (index) {
        var id = 'multiselect-' + index,
            $input = $(this).find('input');

        // Associate the label and the input
        $(this).find('label').attr('for', id);
        $input.attr('id', id);
        if ($(this).hasClass('check-rating')) {

            var point = $input.val(),
                html = '<p class="star-rating text-yellow">';

            for (var i = 1; i <= 5; i++) {
                if (i <= point) {
                    html += '<span class="fas fa-star"></span>';
                } else {
                    html += '<span class="far fa-star"></span>';
                }
            }
            html += '</p>';
            $(this).find('label').html(html);
        }

        // Remove the input from the label wrapper
        $input.detach();

        // Place the input back in before the label
        $input.prependTo($(this));

        $(this).click(function (e) {
            // Prevents the click from bubbling up and hiding the dropdown
            e.stopPropagation();
        });
    });
    $(".currency").keyup(function (e) {
        $(this).val(Frontend.formatCurrency($(this).val()));
    });
    $('.currency').each(function () {
        $(this).val(Frontend.formatCurrency($(this).val()));
    });
    $('#choose-date-price').on('dp.change', function (e) {
        var dateChange = e.date._d;
        var day = dateChange.getDate();
        var days = (day < 10) ? "0" + day : day + "";
        var month = dateChange.getMonth() + 1;
        var months = (month < 10) ? "0" + month : month + "";
        var year = dateChange.getFullYear();

        var hotel_id = $(this).data('hotel-id');
        var date = year + '-' + months + '-' + days;
        Frontend.getPriceHotelByDate(date, hotel_id);
    });
    $('#choose-combo-date').on('dp.change', function (e) {
        var dateChange = e.date._d;
        var day = dateChange.getDate();
        var days = (day < 10) ? "0" + day : day + "";
        var month = dateChange.getMonth() + 1;
        var months = (month < 10) ? "0" + month : month + "";
        var year = dateChange.getFullYear();

        var combo_id = $(this).data('combo-id');
        var date = year + '-' + months + '-' + days;
        Frontend.getPriceComboByDate(date, combo_id);
    });
    $('#booking-start-date').on('dp.change', function (e) {
        var combo_day = $('form#addBooking input[name=days_attended]').val();
        var data = $('form#addBooking').serialize();
        var dateChange = e.date._d;
        var day = dateChange.getDate() + parseInt(combo_day);
        var days = (day < 10) ? "0" + day : day + "";
        var month = dateChange.getMonth() + 1;
        var months = (month < 10) ? "0" + month : month + "";
        var year = dateChange.getFullYear();
        var date = year + '-' + months + '-' + days;
        if ($('#booking-end-date').length) {
            $('#booking-end-date').data("DateTimePicker").date(new Date(date));
        }
    });

    $('.datepicker input').click(function (event) {
        var parent = $(this).parents('.datepicker');
        parent.data("DateTimePicker").show();
    });

    $('.inputmask-number').inputmask({
        'alias': 'numeric', 'rightAlign': false, 'allowMinus': false
    })

    $('#pagination-here').bootpag({
        total: paginateTotal,
        page: 1,
        maxVisible: 5,
        leaps: true,
        firstLastUse: true,
        first: '←',
        last: '→',
        wrapClass: 'pagination',
        activeClass: 'active',
        disabledClass: 'disabled',
        nextClass: 'next',
        prevClass: 'prev',
        lastClass: 'last',
        firstClass: 'first'
    }).on("page", function (event, num) {
        window.location.href = Frontend.updateQueryStringParameter(window.loction.href, 'p', num)
//        $("#content").html("Page " + num); // or some ajax content loading...
//        // ... after content load -> change total to 10
//        $(this).bootpag({total: paginateTotal, maxVisible: 10});
    });

    if ($('.price-range').length) {
        $('.price-range').slider({
            tooltip: 'show',
            formatter: function formatter(val) {
                if (Array.isArray(val)) {
                    return Frontend.formatCurrency(val[0]) + " : " + Frontend.formatCurrency(val[1]);
                } else {
                    return Frontend.formatCurrency(val);
                }
            }
        });
        $('.price-range').slider().on('slideStop', function (ev) {
            var newVal = $('.price-range').data('slider').getValue();
            Frontend.filterPriceV2(newVal);

        });
    }

    if ($('form#hotelRoomSelection').length) {
        Frontend.filterHotelRoom();
    }

    $('form#hotelRoomSelection :input').change(function () {
        Frontend.filterHotelRoom();
    });
    $('#start-date-picker').on('dp.change', function (e) {
        var dateChange = e.date._d;
        var day = dateChange.getDate() + 1;
        var days = (day < 10) ? "0" + day : day + "";
        var month = dateChange.getMonth() + 1;
        var months = (month < 10) ? "0" + month : month + "";
        var year = dateChange.getFullYear();
        var date = year + '-' + months + '-' + days;
        if ($('#end-date-picker').length) {
            $('#end-date-picker').data("DateTimePicker").date(new Date(date));
        }
        Frontend.filterHotelRoom();
    });
    $('#end-date-picker').on('dp.change', function (e) {
        Frontend.filterHotelRoom();
    });
    // $('form#hotelRoomSelection .datepicker').on('dp.change', function(e){
    //     var data = $('form#hotelRoomSelection').serialize();
    //     console.log(data);
    //     var dateChange = e.date._d;
    //     var day = dateChange.getDate() + 1;
    //     var days = (day < 10) ? "0" + day : day + "";
    //     var month = dateChange.getMonth() + 1;
    //     var months = (month < 10) ? "0" + month : month + "";
    //     var year = dateChange.getFullYear();
    //     var date = year + '-' + months + '-' + days;
    //     $('#end-date').data("DateTimePicker").date(new Date(date));
    //     Frontend.filterHotelRoom();
    // })

    if ($('form#homestaySelection').length) {
        Frontend.filterHomestay();
    }
    $('form#homestaySelection :input').change(function () {
        Frontend.filterHomestay();
    });
    $('form#homestaySelection .datepicker').on('dp.change', function (e) {
        Frontend.filterHomestay();
    });

    if ($('form#landTourSelection').length) {
        Frontend.filterLandtour();
    }
    $('form#landTourSelection :input').change(function () {
        Frontend.filterLandtour();
    });
    $('form#landTourSelection .iCheck').on('ifChanged', function (e) {
        Frontend.filterLandtour();
    });
    $('form#landTourSelection .datepicker').on('dp.change', function (e) {
        Frontend.filterLandtour();
    });

    if ($('form#voucherSelection').length) {
        Frontend.filterVoucher();
    }
    $('form#voucherSelection :input').change(function () {
        Frontend.filterVoucher();
    });
    $('form#voucherSelection .datepicker').on('dp.change', function (e) {
        Frontend.filterbtnGoBookingVoucher();
    });
    $(document).mouseup(function (e) {
        var container_s = $('.popup-search');
        if (!container_s.is(e.target) && container_s.has(e.target).length === 0) {
            container_s.hide();
        }
        var container_s_sp = $('.popup-search-sp');
        if (!container_s_sp.is(e.target) && container_s_sp.has(e.target).length === 0) {
            container_s_sp.hide();
        }
        var container_s_vin = $('.popup-search-vin');
        if (!container_s_vin.is(e.target) && container_s_vin.has(e.target).length === 0) {
            container_s_vin.hide();
        }
        var container_input_room_vin = $('.popup-input-room');
        if (!container_input_room_vin.is(e.target) && container_input_room_vin.has(e.target).length === 0) {
            container_input_room_vin.hide();
        }
    });

    // Booking Page
    if ($("form#hotelBookingForm").length) {
        Frontend.calBookingHotelPrice();
    }
    $('body').on('change', 'form#hotelBookingForm input,select', function () {
        Frontend.calBookingHotelPrice();
    });
    $('body .room-booking-sDate, body .room-booking-eDate').on('dp.change', function (e) {
        Frontend.calBookingHotelPrice();
    });

    $('.btnGoBooking').click(function (e) {
        var form_id = $(this).data('form-id');
        $(form_id).submit();
    });
    $('body').on('change', '.booking-num-child', function () {
        var selector = $(this).parents('.booking-room-item');
        Frontend.addSelectChildAge(selector);
    });
    $('#btn-add-room').click(function (e) {
        e.preventDefault();
        Frontend.addRoomToBooking(this);
    });
    $('#btn-add-roomVin').click(function (e) {
        e.preventDefault();
        Frontend.addRoomToBookingVin(this);
    });
    $('#searchForVinPackage').click(function (e) {
        e.preventDefault();
        Frontend.searchForVinPackage($(this));
    });
    $('.btnAddNewPackage').click(function (e) {
        $('button#searchForVinPackage').attr('data-vinroom-id', $(this).data('vinroom-id'));
        $('button#searchForVinPackage').attr('data-vinroom-index', $(this).data('vinroom-index'));
        $('button#searchForVinPackage').attr('data-num-adult', $(this).data('num-adult'));
        $('button#searchForVinPackage').attr('data-num-kid', $(this).data('num-kid'));
        $('button#searchForVinPackage').attr('data-num-child', $(this).data('num-child'));

        let last_date = $('#room-' + $(this).attr('data-vinroom-index') + ' input.end-date-vin').last().val();
        let dateParts = last_date.split("-");
        let dateObject = new Date(+dateParts[2], dateParts[1], +dateParts[0]);
        let new_string_date = ((dateObject.getDate() > 9) ? dateObject.getDate() : ('0' + dateObject.getDate())) + '-' + ((dateObject.getMonth() > 9) ? (dateObject.getMonth()) : ('0' + (dateObject.getMonth()))) + '-' + dateObject.getFullYear();

        $('input[name=start_date_search]').val(new_string_date);
        $('#list-vin-package').empty();
    });
    // if ($('#list-auto-surcharge').length) {
    //     Frontend.calAutoSurcharge();
    // }

    $('input.iCheck.surcharge-check').on('ifChecked', function (event) {
        var parent = $(this).parents('.normal-surcharge-item');
        parent.find('.surcharge-normal-quantity input,select').val('');
        parent.find('.surcharge-normal-quantity input,select').prop('disabled', false);
        parent.find('.surcharge-normal-quantity').show();
        Frontend.calBookingHotelPrice();
    });
    $('input.iCheck.surcharge-check').on('ifUnchecked', function (event) {
        var parent = $(this).parents('.normal-surcharge-item');
        parent.find('.surcharge-normal-quantity input,select').val('')
        parent.find('.surcharge-normal-quantity input,select').prop('disabled', true);
        parent.find('.surcharge-normal-quantity').hide();
        parent.find('.normal-surcharge-fee').text(0);
        Frontend.calBookingHotelPrice();
    });
    $('.timepicker').datetimepicker({
        format: 'HH:mm'
    });
    $('input.iCheck.other-surcharge-check').on('ifChecked', function (event) {
        $('.other-surcharge .list-other-surcharge').empty();
        $('.other-surcharge').show();
    });
    $('input.iCheck.other-surcharge-check').on('ifUnchecked', function (event) {
        $('.other-surcharge .list-other-surcharge').empty();
        $('.other-surcharge').hide();
    });
    $('#add-other-surcharge').click(function (e) {
        e.preventDefault();
        Frontend.addOtherSurcharge(this);
    });

    $('.timepicker').on('dp.hide', function (e) {
        Frontend.calBookingHotelPrice();
    });
    $('body').on('change', '.other-surcharge-price', function (e) {
        Frontend.calBookingTotalPrice();
    });
    $('body').on('change', '.children-age-selector', function (e) {
    });
    $('#requestBooking').click(function () {
        $('#requestBooking').prop("disabled", true);
        Frontend.addBookingHotel();
    });
    /* Booking HomeStay */
    if ($('form#homeStayBookingForm').length) {
        Frontend.calBookingHomeStayPrice();
    }
    $('body #start-date-picker.homestay, body #end-date-picker.homestay').on('dp.change', function (e) {
        Frontend.calBookingHomeStayPrice();
    });
    $('#requestHomeStayBooking').click(function () {
        $('#requestHomeStayBooking').prop("disabled", true);
        Frontend.addBookingHomestay();
    });
    /* End Booking HomeStay */
    /* Booking Voucher */
    if ($('form#voucherBookingForm').length) {
        Frontend.calVoucherTotalPrice();
    }
    $('form#voucherBookingForm :input').change(function () {
        Frontend.calVoucherTotalPrice();
    });
    $('form#voucherBookingForm .datepicker').on('dp.change', function (e) {
        Frontend.calVoucherTotalPrice();
    });

    $('#requestVoucherBooking').click(function () {
        $('#requestVoucherBooking').prop("disabled", true);
        Frontend.addBookingVoucher();
    });
    /* End Booking Voucher */
    /* Booking LandTour */
    if ($('form#landTourBookingForm').length) {
        Frontend.calLandTourTotalPrice();
    }
    $('form#landTourBookingForm :input').change(function () {
        Frontend.calLandTourTotalPrice();
    });
    $('form#landTourBookingForm .datepicker').on('dp.change', function (e) {
        Frontend.calLandTourTotalPrice();
    });
    $('form#landTourBookingForm .iCheck').on('ifChanged', function (e) {
        Frontend.calLandTourTotalPrice();
    });
    // $('body').on('change', '.landtour-booking-num-child', function () {
    //     var selector = $(this).parents('#landTourBookingForm');
    //     Frontend.LandTourAddSelectChildAge(selector);
    // });
    $('body').on('change', '.landTourBookingForm input,select', function () {
        var parent = $(this).parents('#landTourBookingForm');
        Frontend.calLandTourTotalPrice(parent);
    });
    $('#requestLandTourBooking').click(function () {
        $('#requestLandTourBooking').prop("disabled", true);
        Frontend.addBookingLandtour();
    });
    /* End Booking LandTour */
    $('input.payment-method-check').on('ifChecked', function (event) {
        if ($("#hotelBookingForm").length) {
            Frontend.calBookingHotelPrice();
        }
        if ($("#homeStayBookingForm").length) {
            Frontend.calBookingHomeStayPrice();
        }
        if ($("#landTourBookingForm").length) {
            var check = $(this).val();
            if (check == 2) {
                $('.mustgo-deposit').show();
            } else {
                $('.mustgo-deposit').hide();
            }
            Frontend.calLandTourTotalPrice();
        }
        if ($("#voucherBookingForm").length) {
            Frontend.calVoucherTotalPrice();
        }
    });
    /* Payment */
    $('input.iCheck.payment-check').on('ifChecked', function (event) {
        $('.payment-fieldset').hide();
        var fieldId = $(this).data('field-id');
        $('#' + fieldId).show();
    });
    $('input.iCheck.invoice-check').on('ifChecked', function (event) {
        $('.invoice-zone').hide();
        var fieldId = $(this).data('field-id');
        $('#' + fieldId).show();
    });
    if ($('div#dropzone-upload').length) {
        Dropzone.autoDiscover = false;
        $("div#dropzone-upload").dropzone({
            url: baseUrl + "medias/upload_ajax_clone",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            autoProcessQueue: true,
            parallelUploads: 5,
            maxFiles: 10,
            maxFilesize: 5,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            dictFileTooBig: 'Image is bigger than 5MB',
            addRemoveLinks: true,
            removedfile: function (file) {
                var filePath = '';
                if (file.xhr) {
                    var obj = jQuery.parseJSON(file.xhr.response);
                    filePath = obj.image;
                } else {
                    filePath = $(file.previewElement).data('path');
                }
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'medias/delete_image_ajax',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        filePath: filePath
                    },
                    dataType: 'html',
                    success: function (data) {
                        $("#msg").html(data);
                        Frontend.updateListImgUploaded();
                    }
                });
                var _ref;
                if (file.previewElement) {
                    if ((_ref = file.previewElement) != null) {
                        _ref.parentNode.removeChild(file.previewElement);
                    }
                }
                return this._updateMaxFilesReachedClass();
            },
            previewsContainer: null,
            hiddenInputContainer: "body",
            success: function (file) {
                var obj = jQuery.parseJSON(file.xhr.response);
                $(file.previewElement).attr('data-path', obj.image);
                Frontend.updateListImgUploaded();
            },
            init: function () {
                thisDropzone = this;
                if ($('input[name=list_image]').length) {
                    var images = $('input[name=list_image]').val();
                    var data = jQuery.parseJSON(images);
                    $.each(data, function (key, value) {
                        if (value.name) {
                            var mockFile = {name: value.name, size: value.size};
                            thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseUrl + "files/uploads/" + value.name);
                            thisDropzone.createThumbnailFromUrl(mockFile, baseUrl + "files/uploads/" + value.name, function () {
                                thisDropzone.emit("complete", mockFile);
                            });
//                thisDropzone.emit("complete", mockFile);
                            $(thisDropzone.element).children('.dz-preview').eq(key).attr('data-path', "files/uploads/" + value.name);
                        } else {
                            var mockFile = {name: value};
                            thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseUrl + value);
                            thisDropzone.createThumbnailFromUrl(mockFile, baseUrl + value, function () {
                                thisDropzone.emit("complete", mockFile);
                            });
//                thisDropzone.emit("complete", mockFile);
                            $(thisDropzone.element).children('.dz-preview').eq(key).attr('data-path', value);
                        }
                    });
                }
            }
        });
    }

    if ($('div#dropzone-upload-2').length) {
        Dropzone.autoDiscover = false;
        $("div#dropzone-upload-2").dropzone({
            url: baseUrl + "medias/upload_ajax_clone",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            autoProcessQueue: true,
            parallelUploads: 5,
            maxFiles: 10,
            maxFilesize: 5,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            dictFileTooBig: 'Image is bigger than 5MB',
            addRemoveLinks: true,
            removedfile: function (file) {
                var filePath = '';
                if (file.xhr) {
                    var obj = jQuery.parseJSON(file.xhr.response);
                    filePath = obj.image;
                } else {
                    filePath = $(file.previewElement).data('path');
                }
                $.ajax({
                    type: 'POST',
                    url: baseUrl + 'medias/delete_image_ajax',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        filePath: filePath
                    },
                    dataType: 'html',
                    success: function (data) {
                        $("#msg").html(data);
                        Frontend.updateListImgUploaded();
                    }
                });
                var _ref;
                if (file.previewElement) {
                    if ((_ref = file.previewElement) != null) {
                        _ref.parentNode.removeChild(file.previewElement);
                    }
                }
                return this._updateMaxFilesReachedClass();
            },
            previewsContainer: null,
            hiddenInputContainer: "body",
            success: function (file) {
                var obj = jQuery.parseJSON(file.xhr.response);
                $(file.previewElement).attr('data-path', obj.image);
                Frontend.updateListImgUploaded();
            },
            init: function () {
                thisDropzone = this;
                if ($('input[name=list_image]').length) {
                    var images = $('input[name=list_image]').val();
                    var data = jQuery.parseJSON(images);
                    $.each(data, function (key, value) {
                        if (value.name) {
                            var mockFile = {name: value.name, size: value.size};
                            thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseUrl + "files/uploads/" + value.name);
                            thisDropzone.createThumbnailFromUrl(mockFile, baseUrl + "files/uploads/" + value.name, function () {
                                thisDropzone.emit("complete", mockFile);
                            });
//                thisDropzone.emit("complete", mockFile);
                            $(thisDropzone.element).children('.dz-preview').eq(key).attr('data-path', "files/uploads/" + value.name);
                        } else {
                            var mockFile = {name: value};
                            thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseUrl + value);
                            thisDropzone.createThumbnailFromUrl(mockFile, baseUrl + value, function () {
                                thisDropzone.emit("complete", mockFile);
                            });
//                thisDropzone.emit("complete", mockFile);
                            $(thisDropzone.element).children('.dz-preview').eq(key).attr('data-path', value);
                        }
                    });
                }
            }
        });
    }
    $('#requestPayment').click(function () {
        Frontend.requestPayment();
    });
    $('#requestVinPayment').click(function () {
        Frontend.requestVinPayment();
    });
    $('#requestChannelPayment').click(function () {
        Frontend.requestChannelPayment();
    });
    $('#collapseLocation').on('shown.bs.collapse', function () {
        $('a#buttonCollapseLocation').text('Ẩn bớt khách sạn');
    });

    $('#collapseLocation').on('hidden.bs.collapse', function () {
        $('a#buttonCollapseLocation').text('Xem thêm khách sạn');
    });
    /* End Payment */
    $('body').on('click', 'span.room-minus', function () {
        let parent = $(this).closest('.popup-input-room');
        let currentNumber = $(parent).find('#num-room').text();
        console.log(currentNumber);
        if (parseInt(currentNumber) - 1 > 0) {
            currentNumber = parseInt(currentNumber) - 1;
            $(parent).find('#num-room').text(currentNumber);
            $(parent).find('.single-input-room').last().remove();
            Frontend.calculateTotalBookingRoom(parent);
        }
    });
    $('body').on('click', 'span.room-plus', function () {
        let parent = $(this).closest('.popup-input-room');
        let currentNumber = $(parent).find('#num-room').text();
        currentNumber = parseInt(currentNumber) + 1;
        $(parent).find('#num-room').text(currentNumber);
        $.ajax({
            url: baseUrl + 'Pages/inputRoomVin',
            data: {
                room_number: parseInt(currentNumber)
            },
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $(parent).find('#list-input-room').append(response);
                Frontend.calculateTotalBookingRoom(parent);
            }
        });
    });
    $('body').on('click', 'span.room-adult-minus', function () {
        let ele = $(this).parent().next().find('.num-room-adult');
        let numAdult = parseInt(ele.text());
        if (numAdult - 1 >= 1) {
            numAdult = numAdult - 1;
        }
        ele.text(numAdult);
        let parent = $(this).closest('.popup-input-room');
        Frontend.calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-adult-plus', function () {
        let ele = $(this).parent().prev().find('.num-room-adult');
        var numAdult = parseInt(ele.text());
        numAdult += 1;
        ele.text(numAdult);
        let parent = $(this).closest('.popup-input-room');

        Frontend.calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-kid-minus', function () {
        let ele = $(this).parent().next().find('.num-room-kid');
        var numKid = parseInt(ele.text());
        if (numKid - 1 >= 0) {
            numKid = numKid - 1;
        }
        ele.text(numKid);
        let parent = $(this).closest('.popup-input-room');
        Frontend.calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-kid-plus', function () {
        let ele = $(this).parent().prev().find('.num-room-kid');
        var numKid = parseInt(ele.text());
        numKid += 1;
        ele.text(numKid);
        let parent = $(this).closest('.popup-input-room');
        Frontend.calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-child-minus', function () {
        let ele = $(this).parent().next().find('.num-room-child');
        var numChild = parseInt(ele.text());
        if (numChild - 1 >= 0) {
            numChild = numChild - 1;
        }
        ele.text(numChild);
        let parent = $(this).closest('.popup-input-room');
        Frontend.calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-child-plus', function () {
        let ele = $(this).parent().prev().find('.num-room-child');
        var numChild = parseInt(ele.text());
        numChild += 1;
        ele.text(numChild);
        let parent = $(this).closest('.popup-input-room');
        Frontend.calculateTotalBookingRoom(parent);
    });

    $('body').on('click', 'button.delete-vin-infor', function () {
        Frontend.deleteItem(this, '.single-vin-information');
        Frontend.updateIndexVinInfor();
    });
    $('body').on('click', '.single-vin-hotel', function () {
        let url = $(this).data('url');
        window.location.href = url;
    });

    $('input.iCheck.payment_type_vinpearl').on('ifChecked', function (event) {

    });

    $('input.iCheck.vin-room-pick').on('ifChecked', function (event) {
        let roomIndex = $(this).data('room-index');
        let roomKey = $(this).data('room-key');
        let packagePrice = $(this).data('package-pice');
        let packageId = $(this).data('package-id');
        let rateplanId = $(this).data('rateplan-id');
        let allotmentId = $(this).data('allotment-id');
        let roomTypeCode = $(this).data('room-type-code');
        let ratePlanCode = $(this).data('rate-plan-code');
        let revenue = $(this).data('revenue');
        let saleRevenue = $(this).data('sale-revenue');
        let packageCode = $(this).data('package-code');
        let packageName = $(this).data('package-name');
        let defaultPrice = $(this).data('package-default-price');
        let totalChoosePackage = $('input.vin-room-pick[data-room-key="' + roomKey + '"][data-allotment-id="' + allotmentId + '"]:checked').length;
        console.log($(this),totalChoosePackage);
        if (parseInt(totalChoosePackage) <= parseInt($(this).data('package-left'))) {
            Frontend.chooseVinRoom(roomIndex, roomKey, packagePrice, packageId, rateplanId, revenue, saleRevenue, packageCode, packageName, allotmentId, roomTypeCode, ratePlanCode, defaultPrice);
        } else {
            let selector = $(this)
            setTimeout(function () {
                selector.closest('div.icheckbox_flat-blue').removeClass('checked');
            }, 10);
            Swal.fire({
                title: 'Error!',
                text: 'Đã vượt quá giới hạn gói!',
                icon: 'error'
            })
        }
    });
    $('input.iCheck.channel-room-pick').on('ifChecked', function (event) {
        let roomIndex = $(this).data('room-index');
        let roomKey = $(this).data('room-key');
        let packagePrice = $(this).data('package-pice');
        let packageId = $(this).data('package-id');
        let rateplanId = $(this).data('rateplan-id');
        let allotmentId = $(this).data('allotment-id');
        let roomTypeCode = $(this).data('room-type-code');
        let ratePlanCode = $(this).data('rate-plan-code');
        let revenue = $(this).data('revenue');
        let currency = $(this).data('currency');
        let saleRevenue = $(this).data('sale-revenue');
        let packageCode = $(this).data('package-code');
        let packageName = $(this).data('package-name');
        let defaultPrice = $(this).data('package-default-price');
        let dateRange = JSON.stringify($(this).data('date-range'));
        let totalChoosePackage = $('input.channel-room-pick[data-room-key="' + roomKey + '"][data-allotment-id="' + allotmentId + '"]:checked').length;
        if (parseInt(totalChoosePackage) <= parseInt($(this).data('package-left'))) {
            Frontend.chooseChannelRoom(roomIndex, roomKey, packagePrice, packageId, rateplanId, revenue, saleRevenue, packageCode, packageName, allotmentId, roomTypeCode, ratePlanCode, defaultPrice,currency,dateRange);
        } else {
            let selector = $(this)
            setTimeout(function () {
                selector.closest('div.icheckbox_flat-blue').removeClass('checked');
            }, 10);
            Swal.fire({
                title: 'Error!',
                text: 'Đã vượt quá giới hạn gói!',
                icon: 'error'
            })
        }
    });

    $('body').on('click', 'button.btnVinBooking', function () {
        var input = $("#checkMutiChooseRoom");
        var hotel_slug = input.data('hotel-slug');
        var check = input.parent().hasClass('checked');
        if (check) {
            $('form#vinBookingRoom').attr('action', baseUrl + '/khach-san-vinpearl/' + hotel_slug + '/chooseRoom/');
        } else {
            $('form#vinBookingRoom').attr('action', baseUrl + '/khach-san-vinpearl/' + hotel_slug + '/booking/');
        }
        if ($('.have-data').length === $(this).data('num-room')) {
            $('form#vinBookingRoom').submit();
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Vui lòng chọn đủ thông tin phòng',
                icon: 'error'
            })
        }
    });
    $('body').on('click', 'button.btnChannelBooking', function () {
        var input = $("#checkMutiChooseRoom");
        var hotel_slug = input.data('hotel-slug');
        var check = input.parent().hasClass('checked');
        if (check) {
            $('form#channelBookingRoom').attr('action', baseUrl + '/khach-san-channel/' + hotel_slug + '/chooseRoom/');
        } else {
            $('form#channelBookingRoom').attr('action', baseUrl + '/khach-san-channel/' + hotel_slug + '/booking/');
        }
        console.log(   $('.have-data').length  ,   $(this).data('num-room'))
        if ($('.have-data').length == $(this).data('num-room')) {
            $('form#channelBookingRoom').submit();
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Vui lòng chọn đủ thông tin phòng',
                icon: 'error'
            })
        }
    });
    $('body').on('click', 'button#vinBookingPayment', function () {
        Frontend.submitVinBooking();
    });
    $('body').on('click', 'button#channelBookingPayment', function () {
        Frontend.submitChannelBooking();
    });
    $('body').on('click', 'button.btn-vin-asc', function () {
        Frontend.sortVinHotel('asc');
    });
    $('body').on('click', 'button.btn-vin-desc', function () {
        Frontend.sortVinHotel('desc');
    });
    $('input.iCheck[name=vin_booking_type]').on('ifChecked', function (event) {
        let hotel_slug = $(this).data('hotel-slug');
        console.log($(this).data);
        if ($(this).val() == 2) {
            $('form#vinBookingRoom').attr('action', '/khach-san-vinpearl/' + hotel_slug + '/chooseRoom/');
        } else {
            $('form#vinBookingRoom').attr('action', '/khach-san-vinpearl/' + hotel_slug + '/booking/');
        }
    });
    $('body').on('click', 'button#btnAddVinPackage', function () {
        Frontend.addVinSearchPackage(this);
    });
    $('body').on('click', 'button.btnVinBookingMulti', function () {
        $('form#vinBookingRoom').submit();
    });
    $('body').on('click', 'input#text-message', function () {
        Frontend.checkImg();
    });
    $('body').on('click', 'p.remove-package', function () {
        $(this).prop('disabled', true);
        Frontend.removeVinroomPackage($(this));
    });


    $('body').on('click', 'a#message-custom', function () {
        $('.body-message').removeClass('d-none');
        $('.content-message').scrollTop($('.content-message')[0].scrollHeight);
        let element = document.getElementById('message-custom');
        let roomId = element.getAttribute('data-value');
        let id = element.getAttribute('data-id');
        let docRef = db.collection('chatroom').doc(roomId);
        $('#icon-notify').addClass('d-none');
        Frontend.updateStatusReadMessage(id,roomId);
    });
    $('body').on('click', 'a#close-message', function () {
        $('.body-message').addClass('d-none');
    });
    $('body').on('click', '#btn-send-message', function () {
        Frontend.sendFirebaseMessage();
    });

    if (chat_room_id.length > 0) {
        db.collection('chatroom').doc(chat_room_id).onSnapshot((doc) => {
            // var source = doc.metadata.hasPendingWrites ? "Local" : "Server";
            // console.log(source, " data: ", doc.data());
            // console.log((doc.data().latestMessage.text).length)
            var content = [];
            var newChatEle = [];
            if (doc.data().latestMessage.createdBy == current_u_id ){
                if (typeof doc.data().latestMessage.img !== 'undefined' ){
                    if (Object.entries(doc.data().latestMessage.img).length > 0){
                        newChatEle = $('<div class="col-sm-36">\n' +
                            '                <div class="message-guest">\n' +
                            '                            <img src="/'+ doc.data().latestMessage.img +'" alt="No Image" width="100%">\n' +
                            '                        </div>\n' +
                            '                        </div>\n' +
                            '                    </div>');
                    }

                }
                // newChatEle = $('<div class="col-sm-36" id="' + doc.data().createdAt + '">\n' +
                //     '                        <div class="message-guest">\n' +
                //     '                            <p>' + content + '\n' +
                //     '                        </div>\n' +
                //     '                    </div>');
                $('.opacity-custom').removeClass('opacity-custom');
                $('.newMessage').empty();
                $('.newMessage').removeClass('newMessage');
                $('.content-message').scrollTop($('.content-message')[0].scrollHeight);
            } else {
                if ((doc.data().latestMessage.text).length > 0){
                    newChatEle = $('<div class="col-sm-36" id="' + doc.data().createdAt + '">\n' +
                        '                        <div class="message-admin">\n' +
                        '                            <p class="da">' + doc.data().latestMessage.text + '\n' +
                        '                        </div>\n' +
                        '                    </div>');
                } else {
                    newChatEle = $('<div class="col-sm-36" id="' + doc.data().createdAt + '">\n' +
                        '                        <div class="message-admin">\n' +
                        '                            <img src="/' + doc.data().latestMessage.img + '" class="da" width="100%">\n' +
                        '                        </div>\n' +
                        '                    </div>');
                }

            }
            $('.content-message .row').append(newChatEle);
            $('#icon-notify').removeClass('d-none');
            if ((doc.data().is_read) == 1 || doc.data().latestMessage.createdBy == current_u_id) {
                $('#icon-notify').addClass('d-none');
            }
            $('.list-chat').scrollTop($('.list-chat')[0].scrollHeight);
        });

        // db.collection('chatroom').doc(chat_room_id).onSnapshot((doc) => {
        //         console.log(doc.data())
        //     // doc.data() is never undefined for query doc snapshots
        //     console.log(doc.id, " => ", doc.data());
        //     if (doc.data().createdBy == current_u_id ){
        //         let newChatEle = $('<div class="col-sm-36" id="' + doc.data().createdAt + '">\n' +
        //             '                        <div class="message-guest">\n' +
        //             '                            <p>' + doc.data().text + '\n' +
        //             '                        </div>\n' +
        //             '                    </div>');
        //         $('.content-message .row').append(newChatEle);
        //     } else {
        //         let newChatEle = $('<div class="col-sm-36" id="' + doc.data().createdAt + '">\n' +
        //             '                        <div class="message-admin">\n' +
        //             '                            <p>' + doc.data().text + '\n' +
        //             '                        </div>\n' +
        //             '                    </div>');
        //         $('.content-message .row').append(newChatEle);
        //     }
        // $('.list-chat').scrollTop($('.list-chat')[0].scrollHeight);
        // });
        // db.collection('chatroom').doc(chat_room_id).onSnapshot(
        //     (doc) => {
        //         console.log("----------------");
        //         console.log("Chat new");
        //         if ((doc.data().is_read) == 0 && doc.data().latestMessage.createdBy != current_u_id) {
        //             if ($('#' + doc.data().latestMessage.createdAt).length == 0) {
        //                 $('#icon-notify').removeClass('d-none');
        //             }
        //         }

        //     }
        // );
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
});

$(window).on('load', function () {
    $.ajax({
        url: baseUrl + '/users/checkPopupPromoteStatus',
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modal-popup-promote').modal('show');
            }
        }
    });
});

function updateOrder() {
    var order = $("#dropzone-upload .dz-preview").map(function () {
        var src = $(this).data('path');
        return src;
    }).get();
    var json = JSON.stringify(order);
    $('input[name=media]').val(json);
}
