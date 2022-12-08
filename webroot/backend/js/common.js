$(document).ready(function () {
    if ($('.body-content').length) {
        $('.body-content').scrollTop($('.body-content')[0].scrollHeight);
    }
    $('body').on('click', 'button.btnAddNewPackage', function () {
        let vinroom_id = $(this).data('vinroom-id');
        let vinroom_index = $(this).data('vinroom-index');
        let hotel_id = $(this).data('hotel-id');
        let num_adult = $(this).data('num-adult');
        let num_child = $(this).data('num-child');
        let num_kid = $(this).data('num-kid');
        $('input[name=start_date_search]').val($('.list-package-input-room-' + vinroom_index + ' .single-packet-input').last().find('.last-package-end-date').val());
        $('input[name=end_date_search]').val($('.list-package-input-room-' + vinroom_index + ' .single-packet-input').last().find('.last-package-end-date').val());

        $('#searchForVinPackage').attr('data-vinroom-id', vinroom_id);
        $('#searchForVinPackage').attr('data-vinroom-index', vinroom_index);
        $('#searchForVinPackage').attr('data-num-adult', num_adult);
        $('#searchForVinPackage').attr('data-num-child', num_child);
        $('#searchForVinPackage').attr('data-num-kid', num_kid);
        $('#searchForVinPackage').attr('data-hotel-id', hotel_id);

        $('#list-vin-package').empty();
    });
    $('body').on('click', 'button.remove-package-room', function () {
        if ($('.list-package-room-' + $(this).data('room-id') + ' .single-package').length > 1) {
            let roomIndex = $(this).data('room-id');
            let countPackage = $('.list-package-input-room-' + roomIndex + ' .single-packet-input').length - 1;
            let removeEle = $('.list-package-input-room-' + roomIndex + ' .package-input-' + countPackage);

            let currentRoomPrice = $('#vin-room-' + roomIndex).find('p.total-vin-room-' + roomIndex).text();
            let removePackagePrice = removeEle.data('price');
            let removePackageRevenue = removeEle.data('revenue');
            let removePackageSaleRevenue = removeEle.data('sale-revenue');
            let totalVinBookingPrice = $('#totalVinBookingPrice').text();
            let totalVinBookingRevenue = $('#totalVinBookingRevenue').text();

            $.ajax({
                url: baseUrl + 'sale/bookings/removePackageCalPrice',
                data: {
                    currentRoomPrice: currentRoomPrice,
                    removePackagePrice: removePackagePrice,
                    removePackageRevenue: removePackageRevenue,
                    removePackageSaleRevenue: removePackageSaleRevenue,
                    totalVinBookingPrice: totalVinBookingPrice,
                    totalVinBookingRevenue: totalVinBookingRevenue,
                },
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    $('.list-package-room-' + roomIndex + ' .single-package').last().remove();
                    $('.list-package-input-room-' + roomIndex + ' .single-packet-input').last().remove();

                    $('.total-vin-room-' + roomIndex).text(res.room_total);
                    $('#totalVinBookingPrice').text(res.total_vin_booking_price);
                    $('#totalVinBookingRevenue').text(res.total_vin_booking_revenue);
                    $('#totalAgencyPayVinBooking').text(res.total_agency_pay_vin_booking);
                }
            });
        }
    });
    $('body').on('click', 'button#searchForVinPackage', function (e) {
        e.preventDefault();
        $('button#searchForVinPackage').prop('disabled', true);
        searchForVinPackage($(this));


        let vinroom_id = $('#searchForVinPackage').attr('data-vinroom-id');
        let vinroom_index = $('#searchForVinPackage').attr('data-vinroom-index');
        let hotel_id = $('#searchForVinPackage').attr('data-hotel-id');
        let num_adult = $('#searchForVinPackage').attr('data-num-adult');
        let num_child = $('#searchForVinPackage').attr('data-num-child');
        let num_kid = $('#searchForVinPackage').attr('data-num-kid');
        let startDate = $('input[name=start_date_search]').val();
        let endDate = $('input[name=end_date_search]').val();
    });
    $('body').on('click', 'button#btnAddVinPackage', function () {
        $('button#btnAddVinPackage').prop('disabled', true);
        addVinSearchPackage(this);
    });
    $('.vin-date-picker').daterangepicker({
        "singleDatePicker": true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('body').on('click', 'span.room-plus', function () {
        let parent = $(this).closest('.popup-input-room');
        let currentNumber = $(parent).find('#num-room').text();
        currentNumber = parseInt(currentNumber) + 1;
        $(parent).find('#num-room').text(currentNumber);
        $.ajax({
            url: baseUrl + 'sale/bookings/inputRoomVin',
            data: {
                room_number: parseInt(currentNumber)
            },
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $(parent).find('#list-input-room').append(response);
                calculateTotalBookingRoom(parent);
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
        let parent = $(this).closest('#list-input-room');
        calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-adult-plus', function () {
        let ele = $(this).parent().prev().find('.num-room-adult');
        var numAdult = parseInt(ele.text());
        numAdult += 1;
        ele.text(numAdult);
        let parent = $(this).closest('#list-input-room');

        calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-kid-minus', function () {
        let ele = $(this).parent().next().find('.num-room-kid');
        var numKid = parseInt(ele.text());
        if (numKid - 1 >= 0) {
            numKid = numKid - 1;
        }
        ele.text(numKid);
        let parent = $(this).closest('#list-input-room');
        calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-kid-plus', function () {
        let ele = $(this).parent().prev().find('.num-room-kid');
        var numKid = parseInt(ele.text());
        numKid += 1;
        ele.text(numKid);
        let parent = $(this).closest('#list-input-room');
        calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-child-minus', function () {
        let ele = $(this).parent().next().find('.num-room-child');
        var numChild = parseInt(ele.text());
        if (numChild - 1 >= 0) {
            numChild = numChild - 1;
        }
        ele.text(numChild);
        let parent = $(this).closest('#list-input-room');
        calculateTotalBookingRoom(parent);
    });
    $('body').on('click', 'span.room-child-plus', function () {
        let ele = $(this).parent().prev().find('.num-room-child');
        var numChild = parseInt(ele.text());
        numChild += 1;
        ele.text(numChild);
        let parent = $(this).closest('#list-input-room');
        calculateTotalBookingRoom(parent);
    });
    $(document).mouseup(function (e) {
        var container_input_room_vin = $('.popup-input-room');
        if (!container_input_room_vin.is(e.target) && container_input_room_vin.has(e.target).length === 0) {
            container_input_room_vin.hide();
        }
    });
    if ($('.body-content').length) {
        $('.body-content').scrollTop($('.body-content')[0].scrollHeight);
    }
    var avatar3 = new KTImageInput('kt_image_3');
    if ($('div#dropzone-upload').length) {
        $("div#dropzone-upload").dropzone({
            url: baseUrl + "medias/upload_ajax",
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
                        updateOrder();
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
                updateOrder();
            },
            init: function () {
                thisDropzone = this;
                if ($('input[name=list_image]').length > 1) {
                    var images = $('input[name=list_image]').val();
                    console.log(images);
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

        // $("div#dropzone-upload").sortable({
        //     items: '.dz-preview',
        //     cursor: 'move',
        //     opacity: 0.5,
        //     containment: "parent",
        //     distance: 20,
        //     tolerance: 'pointer',
        //     update: function (e, ui) {
        //         updateOrder();
        //     }
        // });
    }
    if ($('div#dropzone-upload-meeting').length) {
        $("div#dropzone-upload-meeting").dropzone({
            url: baseUrl + "medias/upload_ajax",
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
                        updateOrderMeeting();
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
                updateOrderMeeting();
            },
            init: function () {
                thisDropzone = this;
                if ($('input[name=vinhms_meeting_list_image]').length) {
                    var images = $('input[name=vinhms_meeting_list_image]').val();
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

        // $("div#dropzone-upload").sortable({
        //     items: '.dz-preview',
        //     cursor: 'move',
        //     opacity: 0.5,
        //     containment: "parent",
        //     distance: 20,
        //     tolerance: 'pointer',
        //     update: function (e, ui) {
        //         updateOrder();
        //     }
        // });
    }

    if ($('div#dropzone-upload-accessory').length) {
        $("div#dropzone-upload-accessory").dropzone({
            url: baseUrl + "medias/upload_ajax",
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
                        updateOrderMeeting();
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
                updateOrderAccessory();
            },
            init: function () {
                thisDropzone = this;
                if ($('input[name=vinhms_accessory_list_image]').length) {
                    var images = $('input[name=vinhms_accessory_list_image]').val();
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

        // $("div#dropzone-upload").sortable({
        //     items: '.dz-preview',
        //     cursor: 'move',
        //     opacity: 0.5,
        //     containment: "parent",
        //     distance: 20,
        //     tolerance: 'pointer',
        //     update: function (e, ui) {
        //         updateOrder();
        //     }
        // });
    }
    // $('.lightgallery2').lightGallery({download: false});
    // $('#customer-pay-photo .lightgallery2').lightGallery({download: false});
    // $('#partner-pay-photo .lightgallery2').lightGallery({download: false});
    // $('.starrr').starrr({
    //     change: function (e, value) {
    //         $('input[name=rating]').val(value);
    //     }
    // });
    // if ($('.starrr-existing').length) {
    //     var rating = $('.starrr-existing').data('rating');
    //     $('.starrr-existing').starrr({
    //         rating: rating,
    //         change: function (e, value) {
    //             $('input[name=rating]').val(value);
    //         }
    //     });
    // }
    $(".currency").keyup(function (e) {
        $(this).val(formatCurrency($(this).val()));
    });
    $('.currency').each(function () {
        $(this).val(formatCurrency($(this).val()));
    });

    $('.select2').select2();
    $('.custom-daterange-picker').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('.timepicker').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        singleDatePicker: true,
        locale: {
            format: 'HH:mm'
        }
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });
    if ($('#editSurcharges').length) {
        $('#editSurcharges select').trigger('change');
    }
    if ($('#validationErrorSurcharges').length) {
        $('#validationErrorSurcharges select').trigger('change');
    }

    if ($('div.date-range-edit-value').length) {
        $('div.date-range-edit-value').each(function () {
            var start_date = $(this).data('start-date');
            var end_date = $(this).data('end-date');
            var siblingDateRange = $(this).siblings('.custom-daterange-picker');
            siblingDateRange.data('daterangepicker').setStartDate(start_date);
            siblingDateRange.data('daterangepicker').setEndDate(end_date);
        });
    }
    if ($('.gen-room-by-hotel').length) {
        $('.gen-room-by-hotel').each(function () {
            $(this).trigger('onchange');
        })
    }

    $('#modal-post-facebook').on('hidden.bs.modal', function () {
        var form = $('#modal-post-facebook form#post-to-facebook');
        form.find('input[name=object_type]').val('');
        form.find('input[name=object_id]').val('');
        form.find('input[select=fb_post_type]').val('');
        form.find('#list-result').empty();
    });
    $('input.same-radio').on('ifChecked', function (event) {
        $('input.same-radio:checked').not(this).iCheck('uncheck');
//        $('input.same-radio:checked').not(this).val('');
//        this.value = 1;
    });
    $('input#checkAll').on('ifChecked', function (event) {
        $(".check").iCheck('check');
        $('.groupAction').removeClass('hidden');
    });
    $('input#checkAll').on('ifUnchecked', function (event) {
        $(".check").iCheck('uncheck');
        $('.groupAction').addClass('hidden');
    });
    $('input.check').on('ifChecked', function (event) {
        $('.groupAction').removeClass('hidden');
    });
    $('input.check').on('ifUnchecked', function (event) {
        var countCheckbox = $('input.check:checked').length;
        if (countCheckbox == 0) {
            $('.groupAction').addClass('hidden');
        }
    });
    if ($('#choose-type-booking-system').length) {
        $('#choose-type-booking-system').trigger('change');
    }
    if ($('#choose-type-booking-another').length) {
        $('#choose-type-booking-another').trigger('change');
    }

    if ($('#choose-promote-type').length) {
        $('#choose-promote-type').trigger('change');
    }

    if ($('#pick_template').length) {
        var templateEdit = $('select[name=booking_template]').val();
        $('#pick_template').trigger('change');
    }
    $("#checkInTime").change(function () {
        var val = $(this).val();
        var firstCheckInItem;
        $('.custom-surcharge-item').each(function () {
            if ($(this).data('type') == surcharges.SUR_CHECKIN_SOON) {
                firstCheckInItem = $(this);
                return false;
            }
        });
        if (firstCheckInItem) {
            firstCheckInItem.find("input[name*='end']").val(val);
        }
    });
    $("#checkOutTime").change(function () {
        var val = $(this).val();
        var firstCheckOutItem;
        $('.custom-surcharge-item').each(function () {
            if ($(this).data('type') == surcharges.SUR_CHECKOUT_LATE) {
                firstCheckOutItem = $(this);
                return false;
            }
        });
        if (firstCheckOutItem) {
            firstCheckOutItem.find("input[name*='start']").val(val);
        }
    });

    if ($('#pick_template_edit').length) {
        var templateEdit = $('select[name=booking_template]').val();
        switch (templateEdit) {
            case '1':
                $('#another_booking').hide();
                $('#system_booking').show();
                break;
            case '2':
                $('#system_booking').hide();
                $('#another_booking').show();

                break;
        }
    }

    $('textarea.tinymce2').each(function () {
        var textId = $(this).attr('id');
        initMCEexact(textId);
    });

    $('body').on('change', '#hotelBookingForm input,select', function () {
        if ($("#hotelBookingForm").length) {
            // calBookingHotelPrice();
            calBookingHotelPriceV2();
        }
    });
    $('body .room-booking-sDate, body .room-booking-eDate').on('dp.change', function (e) {
        if ($("#hotelBookingForm").length) {
            // calBookingHotelPrice();
            calBookingHotelPriceV2();
        }
    });
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

    $('input.iCheck.payment-for-hotel').on('ifChecked', function (event) {
        $(".paytype-information").addClass('payment-paytype-hotel');
        $('.payment-paytype-hotel').hide();
        var fieldId = $(this).data('field-id');
        $('#' + fieldId).show();
    });
    $('input.iCheck.payment-for-partner').on('ifChecked', function (event) {
        $('.payment-paytype-partner').hide();
        var fieldId = $(this).data('field-id');
        $('#' + fieldId).show();
    });

    //$('input.iCheck').iCheck({
    //checkboxClass: 'icheckbox_flat-green',
    //radioClass: 'iradio_flat-green'
    //});
    // $('body').on('click', '.btn-log', function (event) {
    //     saveCommentLog(event);
    // });

    $('form#form-booking-system').submit(function () {
        $('#saveBooking').attr('disabled', 'disabled');
    });
    $('form#form-booking-another').submit(function () {
        $('#saveBooking').attr('disabled', 'disabled');
    });

    $('.check-vinpearl').on('ifChecked', function (e) {
        var hotel_id = $(this).data('id');
        changeToVinpearl(hotel_id);
    });

    $('#user_id').on('change', function () {
        var name = $('select[name=user_id] option:selected').data('name');
        var email = $('select[name=user_id] option:selected').data('email');
        var phone = $('select[name=user_id] option:selected').data('phone');
        $('input[name=full_name]').val(name);
        $('input[name=phone]').val(phone);
        $('input[name=email]').val(email);
    });
    // var test = document.getElementById("id-room").getAttribute('data-value');
    // console.log(test);


    $('body').on('click', 'span.room-minus', function () {
        let parent = $(this).closest('.popup-input-room');
        let currentNumber = $(parent).find('#num-room').text();
        console.log(currentNumber);
        if (parseInt(currentNumber) - 1 >= 0) {
            currentNumber = parseInt(currentNumber) - 1;
        }
        $(parent).find('#num-room').text(currentNumber);
        $(parent).find('.single-input-room').last().remove();
        calculateTotalBookingRoom(parent);
    });
    if ($('#edit-vin-booking').length) {
        $('input.iCheck').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
        $('.custom-singledate-picker').daterangepicker({
            "singleDatePicker": true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $('input.iCheck.vin-room-pick').on('ifChecked', function (event) {
            let roomIndex = $(this).data('room-index');
            let roomKey = $(this).data('room-key');
            let packagePrice = $(this).data('package-pice');
            let packageId = $(this).data('package-id');
            let rateplanId = $(this).data('rateplan-id');
            let revenue = $(this).data('revenue');
            let saleRevenue = $(this).data('sale-revenue');
            let packageCode = $(this).data('package-code');
            let packageName = $(this).data('package-name');
            let allotmentId = $(this).data('allotment-id');
            let roomTypeCode = $(this).data('room-type-code');
            let ratePlanCode = $(this).data('rate-plan-code');
            let defaultPrice = $(this).data('package-default-price');
            let dateRange = $('input.custom-daterange-picker[name=daterange]').val();
            chooseVinRoom(roomIndex, roomKey, packagePrice, packageId, rateplanId, revenue, saleRevenue, packageCode, packageName, allotmentId, roomTypeCode, ratePlanCode, defaultPrice, dateRange);
        });
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
        $('input.iCheck.payment-for-hotel').on('ifChecked', function (event) {
            $('.paytype-information').find('input[type=radio]').prop('required', false);
            $(".paytype-information").addClass('payment-paytype-hotel');
            $('.payment-paytype-hotel').hide();
            var fieldId = $(this).data('field-id');
            $('#' + fieldId).show();
            $('#' + fieldId).find('input[type=radio]').prop('required', true);

        });
        $('input.iCheck.payment-for-partner').on('ifChecked', function (event) {
            $('.paytype-information').find('input[type=radio]').prop('required', false);
            $('.payment-paytype-partner').hide();
            var fieldId = $(this).data('field-id');
            $('#' + fieldId).show();
        });
    }

    if (Object.entries(chat_room_id).length > 0) {
        let sale_id = chat_room_id[0].split('-');
        // console.log(sale_id[1]);
        let docRef = db.collection('chatroom').where('sale_id', '==', parseInt(sale_id[1], 10)).orderBy('updatedAt');
        docRef.onSnapshot((querySnapshot) => {
            querySnapshot.forEach((doc) => {
                // console.log("-------------------------");
                // console.log(doc.id);
                // console.log(doc.data());
                let id = doc.id.split('-');
                let room_id = doc.id;
                if (id[1] === current_u_id) {
                    if ($('#' + doc.data().latestMessage.createdAt).length === 0) {
                        let chat = $("#" + room_id)[0];
                        let text = $(chat).find('.title-message').text();
                        let name = $(chat).find('.name-message').text();
                        let avatar = $(chat).find('.avatar-message img').attr('src');
                        let chatText = '<div class="custom-lc"  href="#"  onclick="getMessage(this)" id="' + room_id + '" data-value="' + room_id + '">\n' +
                            '   <div class="row">\n' +
                            '       <div class="col-sm-3 avatar-message mb20">\n' +
                            '           <img src="' + avatar + '" alt="" width="100px">\n' +
                            '       </div>\n' +
                            '       <div class="col-sm-9">\n' +
                            '           <a href="#" class="r-message">\n' +
                            '               <p class="name-message">' + name + '</p>\n' +
                            '               <P class="" id="new-chat-' + room_id + '">' + text + '</P>\n' +
                            '           </a>\n' +
                            '       </div>\n' +
                            '   </div>\n' +
                            '</div>\n';
                        $('#' + room_id).remove();
                        $('#chatList').prepend(chatText);
                        let newChat = [];
                        if (doc.data().is_read === 0 && parseInt(doc.data().latestMessage.createdBy) != current_u_id) {
                            $('#new-message').removeClass('d-none');
                            newChat = $('<P class="title-message" id="' + doc.data().latestMessage.createdBy + '">' + doc.data().latestMessage.text + ' <span class="notify-chat"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span> <!-- <span class="custom-notify"><i>  + doc.data().is_read_number   + </i></span> --> </P>\n');
                        } else if (doc.data().is_read == 1) {
                            $('#new-message').addClass('d-none');
                            newChat = $('<P class="title-message" id="' + doc.data().latestMessage.createdBy + '">' + doc.data().latestMessage.text + '</P>\n');
                        } else {
                            $('#new-message').addClass('d-none');
                            newChat = $('<P cl|ass="title-message" id="' + doc.data().latestMessage.createdBy + '">' + doc.data().latestMessage.text + '</P>\n');
                        }
                        $('#new-chat-' + room_id).empty();
                        $('#new-chat-' + room_id).append(newChat);
                        if ($('.user-' + doc.data().latestMessage.createdBy)) {
                            if (doc.data().latestMessage.createdBy !== parseInt(current_u_id)) {
                                if (typeof doc.data().latestMessage.img !== 'undefined' && Object.entries(doc.data().latestMessage.img).length > 0) {
                                    var chatContent = $('<div class="row new-chat">\n' +
                                        '                        <div class="w-100-custom">\n' +
                                        '                        <div class="message-admin">\n' +
                                        '                            <img src="/' + doc.data().latestMessage.img + '" alt="No Image" width="560px">\n' +
                                        '                        </div>\n' +
                                        '                        </div>\n' +
                                        '                    </div>');

                                } else {
                                    var chatContent = $('<div class="row new-chat">\n' +
                                        '                        <div class="w-100-custom">\n' +
                                        '                        <div class="message-admin">\n' +
                                        '                            <p>' + doc.data().latestMessage.text + '\n' +
                                        '                        </div>\n' +
                                        '                        </div>\n' +
                                        '                    </div>');
                                }

                            } else {
                                if (typeof doc.data().latestMessage.img !== 'undefined') {
                                    if (Object.entries(doc.data().latestMessage.img).length > 0) {
                                        var chatContent = $('<div class="row new-chat">\n' +
                                            '                        <div class="w-100-custom">\n' +
                                            '                        <div class="message-guest">\n' +
                                            '                            <img src="/' + doc.data().latestMessage.img + '" alt="No Image" width="560px">\n' +
                                            '                        </div>\n' +
                                            '                        </div>\n' +
                                            '                    </div>');
                                    }
                                }
                                $('.opacity-custom').removeClass('opacity-custom');
                            }
                            if (doc.data().is_read === 0) {
                                if ($('.user-' + room_id).append(chatContent)) {
                                    if ($('.body-content')) {
                                        $('.body-content').scrollTop($('.body-content')[0].scrollHeight);
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    }


});

// tinymce.init({
//     selector: "textarea.tinymce",
//     protect: [
//         /\<!\[if !mso\]\>/g,   // Protect <![if !mso]>
//         /\<!\[if !vml\]\>/g,   // Protect <![if !vml]>
//         /\<!\[endif\]\>/g,     // Protect <![endif]>
//         /<\?php[\s\S]*?\?>/g   // Protect <?php ?> code
//     ],
//     height: 400,
//     menubar: false,
//     relative_urls: false,
//     remove_script_host: false,
//     convert_urls: true,
//     plugins: ["advlist autolink link image imagetools lists charmap print preview hr", "searchreplace wordcount visualblocks visualchars media", "table contextmenu directionality emoticons paste textcolor code"],
//     toolbar1: "undo redo | styleselect | bold italic underline | alignleft aligncenter alignright | bullist outdent indent | forecolor backcolor removeformat | hr | unlink link | media image",
//     image_advtab: true,
//     images_upload_url: baseUrl + 'medias/upload_for_editor',
//     automatic_uploads: true,
//     images_reuse_filename: true,
//     setup: function (editor) {
//         editor.on('change', function (e) {
//             editor.save();
//         });
//     },
//     images_upload_handler: function (blobInfo, success, failure) {
//         var xhr, formData;
//
//         xhr = new XMLHttpRequest();
//         xhr.withCredentials = false;
//         xhr.open('POST', baseUrl + 'medias/upload_for_editor');
//         xhr.setRequestHeader('X-CSRF-Token', csrfToken);
//
//         xhr.onload = function () {
//             var json;
//
//             if (xhr.status != 200) {
//                 failure('HTTP Error: ' + xhr.status);
//                 return;
//             }
//
//             json = JSON.parse(xhr.responseText);
//
//             if (!json || typeof json.location != 'string') {
//                 failure('Invalid JSON: ' + xhr.responseText);
//                 return;
//             }
//             success(baseUrl + json.location);
//         };
//
//         formData = new FormData();
//         formData.append('file', blobInfo.blob(), blobInfo.filename());
//
//         xhr.send(formData);
//     }
//
//
// });

function initMCEexact(e) {
    tinyMCE.init({
        mode: "exact",
        elements: e,
        menubar: false,
        protect: [
            /\<!\[if !mso\]\>/g,   // Protect <![if !mso]>
            /\<!\[if !vml\]\>/g,   // Protect <![if !vml]>
            /\<!\[endif\]\>/g,     // Protect <![endif]>
            /<\?php[\s\S]*?\?>/g   // Protect <?php ?> code
        ]
    });
}

$('.custom-singledate-picker').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    timePicker: true,
    showDropdowns: true,
    locale: {
        format: 'DD/MM/YYYY'
    }
});
$('.custom-singledate-picker').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY'));
});

$('.custom-singledate-picker').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('');
});
$('.daterangepicker_input').hide();
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
});
