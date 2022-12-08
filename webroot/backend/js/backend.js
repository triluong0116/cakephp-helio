function updateOrder() {
    var order = $("#dropzone-upload .dz-preview").map(function () {
        var src = $(this).data('path');
        return src;
    }).get();
    var json = JSON.stringify(order);
    $('input[name=media]').val(json);
}

function updateOrderMeeting() {
    var order = $("#dropzone-upload-meeting .dz-preview").map(function () {
        var src = $(this).data('path');
        return src;
    }).get();
    var json = JSON.stringify(order);
    $('input[name=vinhms_meeting_media]').val(json);
}

function updateOrderAccessory() {
    var order = $("#dropzone-upload-accessory .dz-preview").map(function () {
        var src = $(this).data('path');
        return src;
    }).get();
    var json = JSON.stringify(order);
    $('input[name=vinhms_accessory_media]').val(json);
}

function formatCurrency(num) {
    if (num) {
        var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
        if (str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }
        str = str.split("").reverse();
        for (var j = 0, len = str.length; j < len; j++) {
            if (str[j] != ",") {
                output.push(str[j]);
                if (i % 3 == 0 && j < (len - 1)) {
                    output.push(",");
                }
                i++;
            }
        }
        formatted = output.reverse().join("");
        return (formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    }
}

function numberFormat(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function updateIndexInput(selector, cls) {
    console.log(selector, cls);
    $(selector).find('.' + cls).each(function (index, value) {
        $(value).find('input, select, textarea').each(function () {
            var $this = $(this);
            if ($this[0].hasAttribute('name')) {
                $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                    var order = '[' + index + ']';
                    return order;
                }));
            }
            var newName = $this.attr('name');
            console.log(newName);
            var newId = newName.replace("][", "_");
            newId = newId.replace("[", "_");
            newId = newId.replace("]", "");
            // console.log(newId);
            // $this.attr('id', newId);
            if (newId.includes('caption')) {
                $this.attr('id', newId);
            }
            console.log(newId);
            var next = $this.next();
            var parent = $this.parent();
            if (parent.hasClass('input-group')) {
                if (parent.next().is("p")) {
                    parent.next().attr('id', 'error_' + newId);
                }
            } else {
                if (next.is("p")) {
                    next.attr('id', 'error_' + newId);
                }
            }
        });

    });

}

parseErrors = function (selector, errors) {
    $(selector).find('.error-messages').hide().text('');
    var scrollId = '';

    $.each(errors, function (field, mess) {
        $.each(mess, function (index, msg) {
            if (typeof msg === 'object') {
                $.each(msg, function (childField, msgChild) {
                    if (typeof msgChild === 'object') {
                        msgChild = Object.values(msgChild);
                    }
                    var errorId = field + "_" + index + "_" + childField;
                    $(selector).find('#error_' + errorId).show().text(msgChild[0]);
                    scrollId = errorId;
                });
            } else {
                $(selector).find('#error_' + field).show().text(msg);
                scrollId = field;
            }
        });
    });
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#error_" + scrollId).length > 0 ? $("#error_" + scrollId).offset().top - 250 : 250
    }, 500);

};

function hideHightlightErrors() {
    $('.error-message').hide().text('');
}

function update2ndIndexInput(selector, cls) {
    $(selector).find('.' + cls).each(function (index, value) {
        $(value).find('input, select, textarea').each(function () {
            var $this = $(this);
            $this.attr('name', $this.attr('name').replace(/([^\[\]]+)(?=\]\[[^\]]+\]$)/, function ($0, $1) {
                var order = index;
                return order;
            }));
//                    $this.val('');
        });
    });
}

function getRoomByHotel(e) {
    var hotel_id = $(e).val();
    var url = baseUrl + 'rooms/get_room_by_hotel/' + hotel_id;
    var selector = $(e).parents('.combo-room-item').find('.room-by-hotel');
    var room_id = $(e).data('room-id');
    $.ajax({
        type: "GET", url: url, data: {
            room_id: room_id
        }, dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).empty();
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });
            $('.select2').select2();
        }, failure: function () {

        }
    });
}

function addHotel(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    var num_room = $('.combo-room-item').length;
    if (num_room < 2) {
        $.ajax({
            type: "GET", url: baseUrl + 'hotels/addHotelForCombo', dataType: 'html', success: function (res) {
                var clone = $($.parseHTML(res));
                var cls = clone.attr('class');
                $(selector).append(clone);
                $(selector).find('.' + cls).each(function (index, value) {
                    $(value).find('input, select, textarea').each(function () {
                        var $this = $(this);
                        $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                            var order = '[' + index + ']';
                            return order;
                        }));
//                    $this.val('');
                    });
                });
                $('.select2').select2();
                $(e).find('.fa-spinner').addClass('hidden');
            }, failure: function () {

            }
        });
    } else {
        $(e).find('.fa-spinner').addClass('hidden');
        new PNotify({
            title: 'Cảnh Báo', text: 'Không được chọn quá 2 Khách sạn', hide: false, delay: 2500, styling: 'bootstrap3'
        });
    }
}

function countComboPriceByHotel(e) {
    var parent = $(e).parents('.combo-hotel-item');
}

function getListHotelPriceRoom(e) {
    $('#e-loading-icon').show();
    var hotel_id = $(e).val();
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/getHotelPriceRoom/' + hotel_id, dataType: 'html', success: function (res) {
            $('#list-price').empty();
            $('#list-price').append(res);
            $('.custom-daterange-picker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            $('div.date-range-edit-value').each(function () {
                var start_date = $(this).data('start-date');
                var end_date = $(this).data('end-date');
                var siblingDateRange = $(this).siblings('.custom-daterange-picker');
                siblingDateRange.data('daterangepicker').setStartDate(start_date);
                siblingDateRange.data('daterangepicker').setEndDate(end_date);
            });
            $('#e-loading-icon').hide();
        }, failure: function () {
            $('#e-loading-icon').hide();
        }
    });
}

function addRoomPriceItem(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    var hotel_id = $('form#formPriceRoom select[name=hotel]').val();
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addRoomPrice/' + hotel_id, dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });

            $('.custom-daterange-picker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {

        }
    });
}

function addSurcharge(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    var hotel_id = $('form#formPriceRoom select[name=hotel]').val();
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addSurcharge', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(selector, cls);

            $('.custom-daterange-picker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {

        }
    });
}

function switchCustomSurchage(e) {
    var type = $(e).val();
    var selector = $(e).parents('.surcharge-item').find('.custom-surcharge-price');
    var normalPrice = $(e).parents('.surcharge-item').find('.surcharge-normal-price');
    var otherPrice = $(e).parents('.surcharge-item').find('.custom-other-surcharge');
    if (type == surcharges.SUR_CHILDREN || type == surcharges.SUR_CHECKIN_SOON || type == surcharges.SUR_CHECKOUT_LATE) {
        normalPrice.find('input').prop("required", false);
        normalPrice.find('input').prop("disabled", true);
        otherPrice.find('input').prop("required", false);
        otherPrice.find('input').prop("disabled", true);
        normalPrice.hide();
        selector.show();
        otherPrice.hide();
    } else if (type == surcharges.SUR_OTHER) {
        selector.find('.list-custom-surcharge').empty();
        normalPrice.find('input').prop("required", false);
        normalPrice.find('input').prop("disabled", true);
        otherPrice.find('input').prop("required", true);
        otherPrice.find('input').prop("disabled", false);
        normalPrice.hide();
        selector.hide();
        otherPrice.show();
    } else {
        selector.find('.list-custom-surcharge').empty();
        normalPrice.find('input').prop("required", true);
        normalPrice.find('input').prop("disabled", false);
        otherPrice.find('input').prop("required", false);
        otherPrice.find('input').prop("disabled", true);
        otherPrice.hide();
        normalPrice.show();
        selector.hide();
    }
}

function addCustomSurcharge(e) {
    var type = $(e).parents('.surcharge-item').find('select').val();
    var checkIn = $('input#checkInTime').val();
    var checkOut = $('input#checkOutTime').val();

    var selector = $(e).parents('.surcharge-item').find('.list-custom-surcharge');
    var lastCustomSurcharge = selector.find('.custom-surcharge-item').last();
    if (type == surcharges.SUR_CHECKIN_SOON) {
        var lastValue = lastCustomSurcharge.find("input[name*='start']").val();
    }
    if (type == surcharges.SUR_CHILDREN || type == surcharges.SUR_CHECKOUT_LATE) {
        var lastValue = lastCustomSurcharge.find("input[name*='end']").val();
    }

    var parent = $(e).parents('.surcharge-item').parents('.list-surcharge');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addCustomSurcharge', data: {
            type: type, checkIn: checkIn, checkOut: checkOut, lastValue: lastValue
        }, dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(parent, 'surcharge-item');
            update2ndIndexInput(selector, cls);
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            clone.find('.timepicker').daterangepicker({
                timePicker: true, timePicker24Hour: true, singleDatePicker: true, locale: {
                    format: 'HH:mm'
                }
            }).on('show.daterangepicker', function (ev, picker) {
                picker.container.find(".calendar-table").hide();
            });
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {

        }
    });
}

function updateNextCustomSurchargeValue(e, type) {
    var newValue = 0;
    var parent = $(e).parents('.custom-surcharge-item');
    var nextParent = parent.next();
    if (type == surcharges.SUR_CHILDREN) {
        var value = $(e).val();
        newValue = parseInt(value) + 1;
        var nextIndex = nextParent.find("input[name*='start']");
    }
    if (type == surcharges.SUR_CHECKOUT_LATE) {
        var inputTime = $(e).data('daterangepicker');
        if (inputTime) {
            var currentTime = inputTime.endDate._d;
            var newTime = new Date(currentTime.getTime() + 60000);
            newValue = newTime.getHours().toString().padStart(2, '0') + ':' + newTime.getMinutes().toString().padStart(2, '0');
            var nextIndex = nextParent.find("input[name*='start']");
        }
    }
    if (type == surcharges.SUR_CHECKIN_SOON) {
        var inputTime = $(e).data('daterangepicker');
        if (inputTime) {
            var currentTime = inputTime.startDate._d;
            var newTime = new Date(currentTime.getTime() - 60000);
            newValue = newTime.getHours().toString().padStart(2, '0') + ':' + newTime.getMinutes().toString().padStart(2, '0');
            var nextIndex = nextParent.find("input[name*='end']");
        }
    }

    if (nextIndex) {
        nextIndex.val(newValue);
    }
}

function addCaption(e, selector, limited = true) {
    console.log(limited);
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    var num_room = $('.caption-combo-item').length;
    if (limited) {
        if (num_room < 5) {
            $.ajax({
                type: "GET", url: baseUrl + 'rooms/addCaptionForCombo', dataType: 'html', success: function (res) {
                    var clone = $($.parseHTML(res));
                    var cls = clone.attr('class');
                    $(selector).append(clone);
                    updateIndexInput(selector, cls);
                    var textId = clone.find('textarea.tinymce').attr('id');
                    tinymce.remove('#' + textId);
                    initMCEexact(textId);
                    $(e).find('.fa-spinner').addClass('hidden');
                }, failure: function () {
                    $(e).find('.fa-spinner').addClass('hidden');
                }
            });
        } else {
            $(e).find('.fa-spinner').addClass('hidden');
            new PNotify({
                title: 'Cảnh Báo',
                text: 'Không được chọn quá 5 Mô tả ngắn',
                hide: false,
                delay: 2500,
                styling: 'bootstrap3'
            });
        }
    } else {
        $.ajax({
            type: "GET", url: baseUrl + 'rooms/addCaptionForCombo', dataType: 'html', success: function (res) {
                var clone = $($.parseHTML(res));
                var cls = clone.attr('class');
                $(selector).append(clone);
                updateIndexInput(selector, cls);
                var textId = clone.find('textarea.tinymce').attr('id');
                tinymce.remove('#' + textId);
                initMCEexact(textId);
                $(e).find('.fa-spinner').addClass('hidden');
            }, failure: function () {
                $(e).find('.fa-spinner').addClass('hidden');
            }
        });
    }

}

function addVinCaption(e, selector, limited = true) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addVinCaption', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(selector, cls);
            var textId = clone.find('textarea.tinymce').attr('id');
            tinymce.remove('#' + textId);
            initMCEexact(textId);
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });

}

function addVinExtends(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addVinExtends', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(selector, cls);
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });
}

function addEmail(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addEmail', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            var textId = clone.find('textarea.tinymce2').attr('id');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
                });
            });
            // $(selector).append(res);
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });
}

function addIcon(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addIcon', dataType: 'html', success: function (res) {
            $(selector).append(res);
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });
}

function addHoliday(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addHoliday', dataType: 'html', success: function (res) {
            $(selector).append(res);
            $(e).find('.fa-spinner').addClass('hidden');
            $('.custom-daterange-picker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });
}

function addHotelTerm(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addTerm', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            var textId = clone.find('textarea.tinymce2').attr('id');
            console.log(textId);
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
                });
            });
            initMCEexact(textId);
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });
}

function addBankAccount(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'admin/configs/addAccount', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                console.log(value);
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/bank_account\[(\d+)\]/, function ($0, $1) {
                        var order = 'bank_account[' + index + ']';
                        console.log(order);
                        return order;
                    }));
                });
            });
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });
}

function deleteItem(e, parentClass) {
    $(e).removeAttr('href');
    var parent = $(e).parents(parentClass);
    var selector = parent.parent();
    var cls = parent.attr('class');
    parent.remove();
    updateIndexInput(selector, cls);
}

function deleteChildItem(e, parentClass) {
    $(e).removeAttr('href');
    var parent = $(e).parents(parentClass);
    var selector = parent.parent();
    var cls = parent.attr('class');
    parent.remove();
    updateIndexInput(parent, 'surcharge-item');
    update2ndIndexInput(selector, cls);
}

function showModalPostFacebook(e) {
    $(e).removeAttr('href');
    var object_type = $(e).data('object-type');
    var object_id = $(e).data('object-id');
    $('#modal-post-facebook form#post-to-facebook input[name=object_type]').val(object_type);
    $('#modal-post-facebook form#post-to-facebook input[name=object_id]').val(object_id);
    $('#modal-post-facebook').modal('show');
}

function selectTypePostFacebook(e) {
    var value = $(e).find(":selected").val();
    switch (value) {
        case '2':
            $('#modal-post-facebook .modal-loading .fa-spinner').removeClass('hidden');
            $.ajax({
                type: "GET", url: baseUrl + 'fanpages/get_list_fanpage', dataType: 'html', success: function (res) {
                    $('#modal-post-facebook .modal-loading .fa-spinner').addClass('hidden');
                    var selector = '#modal-post-facebook #list-result';
                    $(selector).append(res);
                }, failure: function () {
                    $('#modal-post-facebook .modal-loading .fa-spinner').addClass('hidden');
                }
            });
            break;
        default:
            break;
    }
}

function postFacebook(e) {
    $(e).find('.fa-spinner').removeClass('hidden');
    var data = $('form#post-to-facebook').serialize();
    $.ajax({
        type: "POST", url: baseUrl + 'fanpages/post_facebook', data: data, headers: {
            'X-CSRF-TOKEN': csrfToken
        }, dataType: 'json', success: function (res) {
            $(e).find('.fa-spinner').addClass('hidden');
            if (res.success) {
                new PNotify({
                    title: 'Thành công',
                    text: res.message,
                    hide: false,
                    type: 'success',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                $('#modal-post-facebook').modal('hide');
            } else {
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 2500, styling: 'bootstrap3'
                });
            }
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
            new PNotify({
                title: 'Cảnh Báo',
                text: 'Có lỗi xảy ra. Vui lòng thử lại.',
                hide: false,
                delay: 2500,
                type: 'error',
                styling: 'bootstrap3'
            });
        }
    });
}

function pickManager(e) {
    var data = $(e).val();
//    console.log(data);
    switch (data) {
        case '1':
            data = $('#pickParentId select').val('');
            $('#pickParentId').addClass('hidden');
            $('#telegramSale').addClass('hidden');
            break;
        case '2':
            data = $('#pickParentId select').val('');
            $('#pickParentId').addClass('hidden');
            $('#telegramSale').removeClass('hidden');
            break;
        case '3':
            $('#pickParentId').removeClass('hidden');
            $('#telegramSale').addClass('hidden');
            break;
        case '4':
            $('#pickParentId').addClass('hidden');
            $('#telegramSale').removeClass('hidden');
            break;
        case '5':
            $('#pickParentId').addClass('hidden');
            $('#telegramSale').removeClass('hidden');
            break;
        case '6':
            $('#pickParentId').addClass('hidden');
            $('#telegramSale').removeClass('hidden');
            break;
        case '7':
            $('#pickParentId').addClass('hidden');
            $('#telegramSale').removeClass('hidden');
            break;
    }
}

function setFeatured(url) {
    var selected = [];
    $('input.check:checked').each(function () {
        selected.push($(this).data('id'));
    });
    $.ajax({
        url: url, type: 'post', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, data: {
            ids: selected
        }, dataType: 'json', success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert('Có lỗi xảy ra. Vui lòng tải lại trang là thực hiện lại.');
            }
        }, error: function () {
        }
    });
}

function unsetFeatured(url) {
    var selected = [];
    $('input.check:checked').each(function () {
        selected.push($(this).data('id'));
    });
    $.ajax({
        url: url, type: 'post', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, data: {
            ids: selected
        }, dataType: 'json', success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                alert('Có lỗi xảy ra. Vui lòng tải lại trang là thực hiện lại.');
            }
        }, error: function () {
        }
    });
}

function getBooking(e, booking_id) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'sale/bookings/get_sale_booking', data: {
            booking_id: booking_id
        }, type: 'post', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, dataType: 'json', success: function (res) {
            spin.addClass('hidden');
            if (res.success) {
                new PNotify({
                    title: 'Thành công', hide: false, type: 'success', delay: 2500, styling: 'bootstrap3'
                });
                window.location.reload();
            } else {
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 2500, styling: 'bootstrap3'
                });
            }
        }, error: function () {
            btn.prop('disabled', false);
            spin.addClass('hidden');
        }
    });
}

function getVinBooking(e, booking_id) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'sale/bookings/get_vin_sale_booking', data: {
            booking_id: booking_id
        }, type: 'post', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, dataType: 'json', success: function (res) {
            spin.addClass('hidden');
            if (res.success) {
                new PNotify({
                    title: 'Thành công', hide: false, type: 'success', delay: 2500, styling: 'bootstrap3'
                });
                window.location.reload();
            } else {
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 2500, styling: 'bootstrap3'
                });
            }
        }, error: function () {
            btn.prop('disabled', false);
            spin.addClass('hidden');
        }
    });
}

function sendEmail(e, booking_id, type) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'sale/bookings/booking_send_email/' + booking_id, type: 'POST', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, data: {
            type: type
        }, dataType: 'json', success: function (res) {
            if (res.success) {
                if (res.request_booking) {
                    $.each(res.data, function (i, item) {
                        var isSuccess = 'success';
                        if (!item.success) {
                            isSuccess = 'danger';
                        }
                        new PNotify({
                            title: 'Thành công',
                            text: item.message,
                            hide: false,
                            type: isSuccess,
                            delay: 2500,
                            styling: 'bootstrap3'
                        });
                        setTimeout(function () {
                            btn.prop('disabled', false);
                            spin.addClass('hidden');
                            window.location.reload();
                        }, 3000);
                    });
                } else {
                    new PNotify({
                        title: 'Thành công',
                        text: res.message,
                        hide: false,
                        type: 'success',
                        delay: 2500,
                        styling: 'bootstrap3'
                    });
                    setTimeout(function () {
                        btn.prop('disabled', false);
                        spin.addClass('hidden');
                        window.location.reload();
                    }, 3000);
                }
            } else {
                btn.prop('disabled', false);
                spin.addClass('hidden');
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 2500, styling: 'bootstrap3'
                });
            }
        }, error: function () {
            btn.prop('disabled', false);
            spin.addClass('hidden');
        }
    });
}

function sendEmailV2(e, booking_id, type) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'sale/bookings/bookingSendEmailV2/' + booking_id, type: 'POST', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, data: {
            type: type
        }, dataType: 'json', success: function (res) {
            if (res.success) {
                if (res.request_booking) {
                    $.each(res.data, function (i, item) {
                        var isSuccess = 'success';
                        if (!item.success) {
                            isSuccess = 'danger';
                        }
                        new PNotify({
                            title: 'Thành công',
                            text: item.message,
                            hide: false,
                            type: isSuccess,
                            delay: 2500,
                            styling: 'bootstrap3'
                        });
                        setTimeout(function () {
                            btn.prop('disabled', false);
                            spin.addClass('hidden');
                            window.location.reload();
                        }, 3000);
                    });
                } else {
                    new PNotify({
                        title: 'Thành công',
                        text: res.message,
                        hide: false,
                        type: 'success',
                        delay: 2500,
                        styling: 'bootstrap3'
                    });
                    setTimeout(function () {
                        btn.prop('disabled', false);
                        spin.addClass('hidden');
                        window.location.reload();
                    }, 3000);
                }
            } else {
                btn.prop('disabled', false);
                spin.addClass('hidden');
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 2500, styling: 'bootstrap3'
                });
            }
        }, error: function () {
            btn.prop('disabled', false);
            spin.addClass('hidden');
        }
    });
}

function choosePromoteType(e) {
    var type = $(e).val();
    var num_book = $(e).data('num-book');
    var num_share = $(e).data('num-share');
    var object_id = $(e).data('object-id');
    $.ajax({
        url: baseUrl + 'admin/promotes/get_other_component_promote/' + type, type: 'GET', data: {
            num_book: num_book, num_share: num_share, object_id: object_id
        }, dataType: 'html', success: function (response) {
            $('#promote-other-content').empty();
            $('#promote-other-content').html(response);
            $('.select2').select2();
        }, error: function () {
        }
    });
}

function addRevenue(e, user_id, booking_id) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'admin/users/add_revenue', data: {
            user_id: user_id, booking_id: booking_id
        }, type: 'post', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, dataType: 'json', success: function (res) {
            spin.addClass('hidden');
            if (res.success) {
                new PNotify({
                    title: 'Thành công',
                    text: res.message,
                    hide: false,
                    type: 'success',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                setTimeout(function () {
                    window.location.reload();
                }, 3000);
            } else {
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 2500, styling: 'bootstrap3'
                });
            }
        }, error: function () {
            btn.prop('disabled', false);
            spin.addClass('hidden');
        }
    });
}

function changeStatusBooking(e, booking_id) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'admin/dashboards/change_status', data: {
            booking_id: booking_id
        }, type: 'post', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, dataType: 'json', success: function (res) {
            spin.addClass('hidden');
            if (res.success) {
                new PNotify({
                    title: 'Thành công',
                    text: res.message,
                    hide: false,
                    type: 'success',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                setTimeout(function () {
                    window.location.reload();
                }, 3000);
            } else {
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 2500, styling: 'bootstrap3'
                });
            }
        }, error: function () {
            btn.prop('disabled', false);
            spin.addClass('hidden');
        }
    });
}

function changeStatusDone(e, booking_id) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'accountant/dashboards/changeStatusDone', data: {
            booking_id: booking_id
        }, type: 'post', headers: {
            'X-CSRF-TOKEN': csrfToken
        }, dataType: 'json', success: function (res) {
            spin.addClass('hidden');
            if (res.success) {
                new PNotify({
                    title: 'Thành công',
                    text: res.message,
                    hide: false,
                    type: 'success',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            } else {
                new PNotify({
                    title: 'Cảnh Báo', text: res.message, hide: false, delay: 1000, styling: 'bootstrap3'
                });
            }
        }, error: function () {
            btn.prop('disabled', false);
            spin.addClass('hidden');
        }
    });
}

function addRoom(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addRoom', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML($.trim(res)));
            var cls = clone.attr('class');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });
            $(e).find('.fa-spinner').addClass('hidden');
        },
    });
}

function addPayment(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addPayment', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML($.trim(res)));
            var cls = clone.attr('class');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });
            $(e).find('.fa-spinner').addClass('hidden');
        },
    });
}

function addRoomPriceDate(e, selector, timestamp) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addRoomPriceByDate', data: {
            timestamp: timestamp
        }, dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            console.log(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });
            $('.select2').select2();
            $('.custom-daterange-picker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {

        }
    });
}

function addReview(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addItemForReview', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {
            $(e).find('.fa-spinner').addClass('hidden');
        }
    });


}

function addHomeStayPrice(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addHomestayPrice', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML($.trim(res)));
            var cls = clone.attr('class');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, function ($0, $1) {
                        var order = '[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });
            $(e).find('.fa-spinner').addClass('hidden');
        },
    });
}

function exportFile(type, e) {
    $(e).find('#cog-3').removeClass('hidden');
    var year = $('select[name=year]').val();
    $.ajax({
        type: "GET", url: baseUrl + 'admin/dashboards/export_excel', data: {
            type: type, year: year
        }, dataType: 'json', success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        }, failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });

}

function exportSaleFile(e) {
    $(e).find('#cog-3').removeClass('hidden');
    var data = $('form#choose_date').serialize();
    $.ajax({
        type: "GET",
        url: baseUrl + 'sale/dashboards/process_date',
        data: data,
        dataType: 'json',
        success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        },
        failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function exportSaleLandtourFile(e) {
    $(e).find('#cog-3').removeClass('hidden');
    var data = $('form#choose_date').serialize();
    $.ajax({
        type: "GET",
        url: baseUrl + 'sale/dashboards/process_date_landtour',
        data: data,
        dataType: 'json',
        success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        },
        failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function exportListSaleCTV(e) {
    $.ajax({
        type: "GET", url: baseUrl + 'sale/dashboards/processSaleListCTV', dataType: 'json', success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        }, failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function exportListSaleCTVAdmin(e) {
    var sale_id = $('select[name=sale_id]').val();
    $.ajax({
        type: "GET", url: baseUrl + 'admin/dashboards/processSaleListCTV', data: {
            sale_id: sale_id
        }, dataType: 'json', success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        }, failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function processDataSale(e) {
    var data = $('form#sale-data').serialize();
    $.ajax({
        type: "GET",
        url: baseUrl + 'admin/dashboards/statisticSaleByDate',
        data: data,
        dataType: 'html',
        success: function (res) {
            console.log(res);
            $(e).find('#cog-3').addClass('hidden');
            $('#table-search').empty();
            $('#table-search').append(res);
        },
        failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function calculateDefaultPrice(booking_id) {
    var method = $('select[name=payment_method]').val();
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/calculateDefaultPrice', data: {
            method: method, booking_id: booking_id
        }, dataType: 'json', success: function (res) {
            $('input[name=default_price]').val(res.price);
        }, failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}


function setBookingTemplate() {
    var bookingTemplate = $('select[name=booking_template]').val();
    $('#list-object-another').empty();
    $('#list-object-system').empty();
    $('select[name=type]').val('');
    switch (bookingTemplate) {
        case '1':
            $('#another_booking').hide();
            $('#system_booking').show();
            break;
        case '2':
            $('#system_booking').hide();
            $('#another_booking').show();

            break;
        default:
            $('#system_booking').hide();
            $('#another_booking').hide();
            break;
    }
    $('.select2').select2();
}

function getListObjectByType(e, selector, boooking_id = null) {
    var object_type = $(e).val();
    var object_id = $(e).data('item-id');
    var form = $(e).parents('form');
    var booking_type = form.find('input[name=booking_type]').val();
    if (!object_id) {
        object_id = '';
    }
    if (object_type) {
        $.ajax({
            url: baseUrl + 'sale/bookings/get_list_object_by_type', type: 'GET', //        headers: {
//            'X-CSRF-TOKEN': csrfToken
//        },
            data: {
                object_type: object_type, object_id: object_id, booking_type: booking_type, booking_id: boooking_id
            }, dataType: 'json', success: function (response) {
                $(selector).empty();
                $(selector).append(response.data);
                $('.select2').select2();
                if (response.is_edited) {
                    console.log($('form#form-booking-another .listObject'));
                    $('form#form-booking-system .listObject').trigger('change');
                    $('form#form-booking-another .listObject').trigger('change');
                }
            }, error: function () {
            }
        });
    }
}

function getListRoomsForHotel(e, type, name, selector, booking_id = null) {
    var room_id = $(e).data('room-id');
    var item_id = $('select[name=' + name + ']').val();
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/getListRoomsForHotel', data: {
            item_id: item_id, type: type, room_id: room_id, booking_id: booking_id
        }, dataType: 'json', success: function (res) {
            $(selector).empty();
            $(selector).append(res.data);
            $('.select2').select2();
            $('.custom-singledate-picker').daterangepicker({
                "singleDatePicker": true, locale: {
                    format: 'DD/MM/YYYY'
                }
            });
        }, failure: function () {

        }
    });
}

function confirmChange(form_id) {
    if (confirm('Are you sure?')) {
        // Post the form
        $(form_id).submit() // Post the surrounding form
    }
}

function addPriceByAge(e, selector) {
    var select = $('#list-price-by-age').find('.age-price-item').last();
    var lastValue = select.find('input[name*="end"]').val();
    var parent = $(e).parents('.age-price-item').parents('.list-price-by-age');
    $.ajax({
        type: 'GET', url: baseUrl + 'rooms/addPriceByAge', dataType: 'html', data: {
            lastValue: lastValue
        }, success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(parent, 'age-price-item');
            update2ndIndexInput(selector, cls);
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
        }
    })
}

function addLandtourAccessory(e, selector) {
    $.ajax({
        type: 'GET', url: baseUrl + 'rooms/addLandtourAccessory', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(selector, 'accessory-item');
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
        }
    })
}

function addLandtourDriveSurchage(e, selector) {
    $.ajax({
        type: 'GET', url: baseUrl + 'rooms/addLandtourDriveSurchage', dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(selector, 'drive-surchage-item');
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
        }
    })
}

function getListRevenueLandtour(e) {
    $('#e-loading-icon').show();
    var landtour_id = $(e).val();
    $.ajax({
        type: "GET",
        url: baseUrl + 'rooms/getListRevenueLandtour/' + landtour_id,
        dataType: 'html',
        success: function (res) {
            $('#list-revenue-agency').empty();
            $('#list-revenue-agency').append(res);
            $("#list-revenue .select2").each(function (key, item) {
                if ($(item).data('select2')) {
                    $(item).select2('destroy');
                }
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            $('.select2').select2();
            $('#e-loading-icon').hide();
        },
        failure: function () {
            $('#e-loading-icon').hide();
        }
    });
}


function addAgencyRevenueLandtour(e, selector) {
    var data = $('form').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + 'rooms/addAgencyRevenueLandtour?' + data,
        dataType: 'html',
        success: function (res) {
            $("#list-revenue .select2").each(function (key, item) {
                if ($(item).data('select2')) {
                    $(item).select2('destroy');
                }
            });
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            updateIndexInput(selector, 'agency-revenue-item');

            $('.select2').select2();
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
        }
    })
}


function updateNextSiblingAge(e) {
    var lastValue = $(e).val();
    var value = parseInt(lastValue) + 1;
    var parent = $(e).parents('.age-price-item');
    var nextParent = parent.next();
    if (nextParent) {
        nextParent.find('input[name*="start"]').val(value);
    }

}

function addPriceHotel(e, selector) {
    $(e).find('.fa-spinner').removeClass('hidden');
    $(e).removeAttr('href');
    var hotel_id = $('form#formPriceRoom select[name=hotel]').val();
    $.ajax({
        type: "GET", url: baseUrl + 'rooms/addPriceHotel/' + hotel_id, dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $(selector).append(clone);
            $(selector).find('.' + cls).each(function (index, value) {
                $(value).find('input, select, textarea').each(function () {
                    var $this = $(this);
                    $this.attr('name', $this.attr('name').replace(/price_rooms\[(\d+)\]/, function ($0, $1) {
                        var order = 'price_rooms[' + index + ']';
                        return order;
                    }));
//                    $this.val('');
                });
            });

            $('.custom-daterange-picker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            $(e).find('.fa-spinner').addClass('hidden');
        }, failure: function () {

        }
    });
}


function deletePriceItem(e) {
    $(e).removeAttr('href');
    var parent = $(e).parents('.price-room-item');
    var selector = parent.parent();
    var cls = parent.attr('class');
    parent.remove();
}

function showFormBookingByType(e, selector) {
    console.log("loaded type");
    var type = $('select[name=type] option:selected').val();
    var user_id = $('select[name=user_id] option:selected').val();
    var booking_id = $(e).data('booking-id');
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/addFormType', data: {
            type: type, user_id: user_id, booking_id: booking_id
        }, dataType: 'html', success: function (res) {
            $(selector).empty();
            $(selector).append(res);
            $('input.iCheck').iCheck({
                checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            $('.select2').select2();
            $('.custom-singledate-picker').daterangepicker({
                "singleDatePicker": true, locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            if (booking_id) {
                $('body #booking-hotel-select').trigger('change');
                addEditedBookingRoom(booking_id);
            }
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
            $('.checkbox-iCheck.iCheck').on('ifUnchecked', function (e) {
                updateTotalPriceLandtour();
            });
            $('.checkbox-iCheck.iCheck').on('ifChecked', function (e) {
                updateTotalPriceLandtour();
            });
            $('.radio-iCheck-pick.iCheck').on('ifChecked', function (e) {
                updateTotalPriceLandtour();
            });
            $('.radio-iCheck-drop.iCheck').on('ifChecked', function (e) {
                updateTotalPriceLandtour();
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
            if ($('div#dropzone-upload').length) {
                Dropzone.autoDiscover = false;
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
                            type: 'POST', url: baseUrl + 'medias/delete_image_ajax', headers: {
                                'X-CSRF-TOKEN': csrfToken
                            }, data: {
                                filePath: filePath
                            }, dataType: 'html', success: function (data) {
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
                        if ($('input[name=list_image]').length) {
                            var images = $('input[name=list_image]').val();
                            if (images) {
                                var data = jQuery.parseJSON(images);
                                $.each(data, function (key, value) {
                                    var mockFile = {name: value};
                                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseUrl + value);
                                    thisDropzone.createThumbnailFromUrl(mockFile, baseUrl + value, function () {
                                        thisDropzone.emit("complete", mockFile);
                                    });
//                thisDropzone.emit("complete", mockFile);
                                    $(thisDropzone.element).children('.dz-preview').eq(key).attr('data-path', value);
                                });
                            }
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
            /* End Payment */
        }, failure: function () {

        }
    });
}

function bookingChangeHotel(e) {
    $("#list-hotel-room").empty();
    var hotel_id = $(e).val();
    var booking_id = $(e).data('booking-id');
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/changeHotelSurcharge', data: {
            hotel_id: hotel_id, booking_id: booking_id
        }, dataType: 'json', success: function (res) {
            // $("#list-hotel-item").empty();
            $("#hotel-list-surcharges").empty();
            $("#hotel-list-surcharges").append(res.surcharge);
            $("#object-payment-information").append(res.payment_information);
            var selector = $('#hotel-list-surcharges');
            updateIndexInput(selector, 'normal-surcharge-item');
            $("#hotel-list-surcharges input.flat").iCheck({
                checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'
            });
            $('input.surcharge-check').on('ifChecked', function (event) {
                var parent = $(this).parents('.normal-surcharge-item');
                parent.find('.surcharge-normal-quantity input,select').val('');
                parent.find('.surcharge-normal-quantity input,select').prop('disabled', false);
                parent.find('.surcharge-normal-quantity input,select').prop('required', true);
                parent.find('.surcharge-normal-quantity').removeClass('hidden');

            });
            $('input.surcharge-check').on('ifUnchecked', function (event) {
                var parent = $(this).parents('.normal-surcharge-item');
                parent.find('.surcharge-normal-quantity input,select').val('')
                parent.find('.surcharge-normal-quantity input,select').prop('disabled', true);
                parent.find('.surcharge-normal-quantity input,select').prop('required', false);
                parent.find('.surcharge-normal-quantity').addClass('hidden');
            });
            $('.timepicker').daterangepicker({
                timePicker: true, timePicker24Hour: true, singleDatePicker: true, locale: {
                    format: 'HH:mm'
                }, autoUpdateInput: false,
            }).on('show.daterangepicker', function (ev, picker) {
                picker.container.find(".calendar-table").hide();
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('HH:mm'));
                // calBookingHotelPrice();
                calBookingHotelPriceV2();
            });
            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            // calBookingHotelPrice();
            calBookingHotelPriceV2();
        }, failure: function () {

        }
    });
}

// function calBookingHotelPrice() {
//     hideHightlightErrors();
//     var data = $("#hotelBookingForm input,select").serialize();
//     $.ajax({
//         url: baseUrl + 'sale/bookings/calBookingHotelPrice',
//         data: data,
//         type: 'get',
//         dataType: 'json',
//         success: function (res) {
//             if (res.success) {
//                 $.each(res.data_surcharge_price, function (index, value) {
//                     if (index.indexOf('show-price') != -1) {
//                         $("#" + index).text(numberFormat(value));
//                     } else {
//                         $("#" + index).val(numberFormat(value));
//                     }
//                 });
//                 $(".currency").keyup(function (e) {
//                     $(this).val(formatCurrency($(this).val()));
//                 });
//                 // $('.booking-room-item').each(function () {
//                 //     addSelectChildAge($(this).find('.list-child-age'));
//                 // });
//                 console.log(res);
//                 $.each(res.data_booking_rooms, function (index, value) {
//                     var single_price_name = "booking_rooms[" + index + "][single_price]";
//                     $('input[name="booking_rooms[' + index + '][room_single_price]"]').val(numberFormat(value.room_single_price));
//                     $('input[name="booking_rooms[' + index + '][room_total_price]"]').val(numberFormat(value.room_total_price));
//                 });
//                 $('input[name=price]').val(numberFormat(res.total_price));
//             } else {
//                 parseErrors('#hotelBookingForm', res.errors);
//             }
//
//         },
//         error: function () {
//         }
//     });
// }

function calBookingHotelPriceV2() {
    hideHightlightErrors();
    addNameInput('.rooms', 'booking_rooms');
    var data = $("#hotelBookingForm input,select").serialize();
    console.log(data);
    $.ajax({
        url: baseUrl + 'sale/bookings/calBookingHotelPriceV2',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                console.log(res);
                $.each(res.data_surcharge_price, function (index, value) {
                    if (index.indexOf('show-price') != -1) {
                        $("#" + index).text(numberFormat(value));
                    } else {
                        $("#" + index).val(numberFormat(value));
                    }
                });
                $(".currency").keyup(function (e) {
                    $(this).val(formatCurrency($(this).val()));
                });
                // $('.booking-room-item').each(function () {
                //     addSelectChildAge($(this).find('.list-child-age'));
                // });
                console.log(res);
                $.each(res.data_booking_rooms, function (index, value) {
                    var single_price_name = "booking_rooms[" + index + "][single_price]";
                    $('input[name="booking_rooms[' + index + '][room_single_price]"]').val(numberFormat(value.room_single_price));
                    $('input[name="booking_rooms[' + index + '][room_total_price]"]').val(numberFormat(value.room_total_price));
                    console.log(value.num_children, res);
                    $('input[name="booking_rooms[' + index + '][num_children]"]').val(numberFormat(value.num_children));
                });
                $('input[name=price]').val(numberFormat(res.total_price));
            } else {
                parseErrors('#hotelBookingForm', res.errors);
            }

        },
        error: function () {
        }
    });
}

function addNameInput(selector, cls) {
    console.log(selector, cls);
    $(selector).find('.' + cls).each(function (index, value) {
        $(value).find('input, select, textarea').each(function () {
            var $this = $(this);
            var name = $this.attr('name');
            if (name.includes(cls) == false) {
                console.log('--------------');
                var newName = cls + name;
                $this.removeAttr('name');
                $this.attr('name', newName);
                console.log(name, newName, $this);
            }
        });
    });
}

function addHotelRoom(selector, isChangeHotel) {
    var hotel_id = $('select[name=item_id] option:selected').val();
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/showListRooms', data: {
            hotel_id: hotel_id,
        }, dataType: 'html', success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            if (isChangeHotel) {
                $(selector).empty();
            }
            $(selector).append(clone);
            updateIndexInput(selector, cls);
            $('.custom-singledate-picker').daterangepicker({
                "singleDatePicker": true, locale: {
                    format: 'DD/MM/YYYY'
                }
            });
        }, failure: function () {

        }
    });
}

function addEditedBookingRoom(booking_id) {
    var hotel_id = $('select[name=item_id] option:selected').val();
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/getEditedBookingRoom', data: {
            hotel_id: hotel_id, booking_id: booking_id,
        }, dataType: 'html', success: function (res) {
            $('#list-hotel-item').append(res);
            updateIndexInput($('#list-hotel-item'), 'booking-room-item');
            $('.custom-singledate-picker').daterangepicker({
                "singleDatePicker": true, locale: {
                    format: 'DD/MM/YYYY'
                }
            });
        }, failure: function () {

        }
    });
}

function addSelectChildAge(e) {
    var selector = $(e).parents('.booking-room-item');
    var data = selector.find(':input').serialize();
    $.ajax({
        url: baseUrl + 'sale/bookings/addSelectChildAge',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                selector.find('.list-child-age').empty();
                selector.find('.list-child-age').append(res.data);
            } else {
                parseErrors('#hotelRoomAnotherBooking', res.errors);
            }
        },
        error: function () {
        }
    });
}

function showFieldInput(e, field_name) {
    if ($(e).is(':checked')) {
        console.log(1);
        $('input#' + field_name).removeClass('hidden');
    } else {
        $('input#' + field_name).addClass('hidden');
    }
}

function calculateSurcharge(field, sur_type) {
    var hotel_id = $('select[name=item_id] option:selected').val();
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/calculateSurcharge', data: {
            hotel_id: hotel_id, sur_type: sur_type
        }, dataType: 'json', success: function (res) {

        }, failure: function () {

        }
    });
}

function addInforIfSpecial() {
    var item_id = $('select[name=item_id] option:selected').val();
    $.ajax({
        type: "Get", url: baseUrl + 'sale/bookings/addInforIfSpecial', data: {
            item_id: item_id,
        }, dataType: 'json', success: function (res) {
            if (res.success) {
                $('#more_info').empty();
                $('#more_info').append(res.moreInfor);
            } else {
                $('#more_info').empty();
            }
        }, failure: function () {

        }
    });
}

function addSelectChildAgeLandtour(e) {
    var num_child = $(e).val();
    var landtour_id = $('select[name=item_id] option:selected').val();
    $.ajax({
        type: "Get", url: baseUrl + 'sale/bookings/addSelectChildAgeLandtour', data: {
            num_child: num_child, landtour_id: landtour_id
        }, dataType: 'html', success: function (res) {
            $('#list-child-age').empty();
            $('#list-child-age').append(res);
            updateTotalPriceLandtour();
        }, failure: function () {

        }
    });
}

function updateTotalPriceLandtour() {
    var data = $('form#form-booking-system').serialize();
    $.ajax({
        type: "Get",
        url: baseUrl + 'sale/bookings/updateTotalPriceLandtour',
        data: data,
        dataType: 'json',
        success: function (res) {
            $('input[name=price_without_surcharge]').val(res.price_without_surcharge);
            $('input[name=price_surcharge]').val(res.price_surcharge);
            $('input[name=price]').val(res.price);
            $('input[name=drive_surchage]').val(res.drive_surchage);
        },
        failure: function () {

        }
    });

}

function updateTotalPriceHomestay() {
    var data = $('form#form-booking-system').serialize();
    $.ajax({
        type: "Get",
        url: baseUrl + 'sale/bookings/updateTotalPriceHomestay',
        data: data,
        dataType: 'json',
        success: function (res) {
            $('input[name=price]').val(res.price);
        },
        failure: function () {

        }
    });
}

function updateTotalPriceVoucher() {
    var data = $('form#form-booking-system').serialize();
    $.ajax({
        type: "Get",
        url: baseUrl + 'sale/bookings/updateTotalPriceVoucher',
        data: data,
        dataType: 'json',
        success: function (res) {
            $('input[name=price]').val(res.price);
        },
        failure: function () {

        }
    });
}

function addRoomHotelAnotherBooking(hotel_id) {
    $.ajax({
        type: "Get",
        url: baseUrl + 'sale/bookings/addRoomHotelAnotherBooking/' + hotel_id,
        dataType: 'html',
        success: function (res) {
            var clone = $($.parseHTML(res));
            var cls = clone.attr('class');
            $('#list-hotel-room-another').append(clone);
            updateIndexInput('#list-hotel-room-another', cls);
            $('.custom-singledate-picker').daterangepicker({
                "singleDatePicker": true, locale: {
                    format: 'DD/MM/YYYY'
                }
            });
        },
        failure: function () {

        }
    });
}

function addListChildAgeAnother(e) {
    var selector = $(e).parents('.booking-room-item');
    var data = selector.find(':input').serialize();
    $.ajax({
        url: baseUrl + 'sale/bookings/addListChildAgeAnother',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                var clone = $($.parseHTML(res.data));
                var cls = clone.attr('class');
                selector.find('.list-child-age-another').empty();
                selector.find('.list-child-age-another').append(clone);
                updateIndexInput('#list-object-another', 'booking-room-item');
            } else {
                parseErrors('#another_booking', res.errors);
            }
        },
        error: function () {
        }
    });
}

function updatePayHotel(e, booking_id) {
    var payHotel = $(e).val();
    $.ajax({
        url: baseUrl + 'admin/dashboards/updatePayHotel/' + booking_id, data: {
            payHotel: payHotel
        }, type: 'post', dataType: 'json', success: function (res) {
            if (res.success) {
                window.location.reload();
            } else {

            }
        }, error: function () {
        }
    });
}

function bookingChangeObject(e, booking_type) {
    var objectId = $(e).val();
    $.ajax({
        url: baseUrl + 'sale/bookings/bookingChangeObject', data: {
            objectId: objectId, booking_type: booking_type
        }, type: 'post', dataType: 'html', success: function (res) {
            $('#object-payment-information').empty();
            $('#object-payment-information').append(res);
            if (booking_type == 3) {
                addLandtourAccessories(objectId);
            }
        }, error: function () {
        }
    });
}

function addLandtourAccessories(landtour_id) {
    console.log(1);
    $.ajax({
        url: baseUrl + 'sale/bookings/addLandtourAccessories/' + landtour_id,
        type: 'post',
        dataType: 'html',
        success: function (res) {

            $('#list-accessory').empty();
            $('#list-accessory').append(res);
            $('.iCheck').iCheck({
                checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'
            });

            $('.checkbox-iCheck.iCheck').on('ifUnchecked', function (e) {
                updateTotalPriceLandtour();
            });
            $('.checkbox-iCheck.iCheck').on('ifChecked', function (e) {
                updateTotalPriceLandtour();
            });
            // $('.radio-iCheck-pick.iCheck').on('ifChanged', function (e) {
            //     updateTotalPriceLandtour();
            // });
            $('.radio-iCheck-pick.iCheck').on('ifChecked', function (e) {
                updateTotalPriceLandtour();
            });
            $('.radio-iCheck-drop.iCheck').on('ifChecked', function (e) {
                updateTotalPriceLandtour();
            });
        },
        error: function () {
        }
    });
}

function showSearchByDate() {
    $('#search-by-date').empty();
    $('#search-by-date').append('<button type="button" class="btn btn-default" onclick="hideSearchByDate()"><i class="fa fa-filter"></i> Ẩn tìm kiếm theo ngày</button>\n' + '                        <div class="form-group">\n' + '                            <label for="exampleInputName2">Ngày đi</label>\n' + '                            <input type="text" class="custom-singledate-picker form-control" name="start_date" value=""/>\n' + '                        </div>\n' + '                        <div class="form-group">\n' + '                            <label for="exampleInputName2">Ngày về</label>\n' + '                            <input type="text" class="custom-singledate-picker form-control" name="end_date" value=""/>\n' + '                        </div>');
    $('.custom-singledate-picker').daterangepicker({
        "singleDatePicker": true, showDropdowns: true, locale: {
            format: 'DD/MM/YYYY'
        }
    });
}

function showSearchByDate2() {
    $('#search-by-date').empty();
    $('#search-by-date').append('<button type="button" class="btn btn-default" onclick="hideSearchByDate2()"><i class="fa fa-filter"></i> Ẩn tìm kiếm theo ngày</button>\n' + '                        <div class="form-group">\n' + '                            <label for="exampleInputName2">Ngày đi</label>\n' + '                            <input type="text" class="custom-singledate-picker form-control" name="start_date" value=""/>\n' + '                        </div>\n' + '                        <div class="form-group">\n' + '                            <label for="exampleInputName2">Ngày về</label>\n' + '                            <input type="text" class="custom-singledate-picker form-control" name="end_date" value=""/>\n' + '                        </div>\n' + '                        <div class="form-group">\n' + '                            <label for="exampleInputName2">Ngày tạo</label>\n' + '                            <input type="text" class="custom-singledate-picker form-control" name="create_date" value=""/>\n' + '                        </div>');
    $('.custom-singledate-picker').daterangepicker({
        singleDatePicker: true, timePicker: true, showDropdowns: true, autoUpdateInput: false, locale: {
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
}

function hideSearchByDate() {
    $('#search-by-date').empty();
    $('#search-by-date').append('<button type="button" class="btn btn-default" onclick="showSearchByDate()"><i class="fa fa-filter"></i> Tìm kiếm theo ngày</button>');
}

function hideSearchByDate2() {
    $('#search-by-date').empty();
    $('#search-by-date').append('<button type="button" class="btn btn-default" onclick="showSearchByDate2()"><i class="fa fa-filter"></i> Tìm kiếm theo ngày</button>');
}

function changeAgency(e) {
    var agency_id = $(e).val();
    $.ajax({
        url: baseUrl + 'sale/bookings/changeAgency/' + agency_id,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.success == true && $('input[name=email]').length) {
                $('input[name=email]').val('');
                $('input[name=email]').val(res.email);
            } else {
                $('input[name=email]').val('');
            }
        },
        error: function () {
        }
    });
}

function calManageLandtourFee() {
    var singlePrice = $('input[name=single_price]').val();
    var amount = $('input[name=amount]').val();

    singlePrice = singlePrice.replaceAll(',', '');
    amount = amount.replaceAll(',', '');
    var total = parseInt(singlePrice) * parseInt(amount);
    total = total.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    $('input[name=total]').val(total);
}

function exportBookingRoom(e) {
    let data = $('form').serialize();
    $(e).find('#cog-3').removeClass('hidden');
    $.ajax({
        url: baseUrl + 'admin/dashboards/exportBookingRoom',
        type: 'get',
        data: data,
        dataType: 'json',
        success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name + '</a>';
            console.log(linkDownload);
            $('#download-link').append(linkDownload);
        },
        error: function () {
        }
    });
}

function changeIsCommit(e, hotel_id) {
    let checked = true;
    if (!$(e).is(":checked")) {
        checked = false;
    }
    console.log(checked);
    $.ajax({
        url: baseUrl + 'editor/hotels/changeIsCommit', type: 'post', data: {
            hotel_id: hotel_id, checked: checked
        }, dataType: 'json', success: function (res) {

        }, error: function () {
        }
    });
}

function changeToVinpearl(hotel_id) {
    $.ajax({
        url: baseUrl + 'editor/hotels/changeToVinpearl/' + hotel_id,
        type: 'post',
        dataType: 'json',
        success: function (res) {

        },
        error: function () {
        }
    });
}

function getHotelCode(e) {
    $('input[name=vinhms_code]').val($(e).val());
}

function commitBookingVinpearl(e, booking_id, mail_type) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'accountant/dashboards/sendBookingVin/' + booking_id, type: 'post', data: {
            mail_type: mail_type
        }, dataType: 'json', success: function (res) {
            if (res.success) {
                saveCommentLog(e);
                new PNotify({
                    title: 'Thành công',
                    text: res.message,
                    hide: false,
                    type: 'success',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                setTimeout(function () {
                    // btn.prop('disabled', false);
                    // spin.addClass('hidden');
                    window.location.reload();
                }, 5000);
            } else {
                console.log(res);
                new PNotify({
                    title: 'Đã xảy ra lỗi',
                    text: res.message,
                    hide: false,
                    type: 'error',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                // setTimeout(function () {
                //     // btn.prop('disabled', false);
                //     // spin.addClass('hidden');
                //     window.location.reload();
                // }, 5000);
            }
        }, error: function (res) {
            console.log(res);
        }
    });
}

function commitBooking(e, booking_id, mail_type) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'accountant/dashboards/commitBooking/' + booking_id, type: 'post', data: {
            mail_type: mail_type
        }, dataType: 'json', success: function (res) {
            if (res.success) {
                saveCommentLog(e);
                new PNotify({
                    title: 'Thành công',
                    text: res.message,
                    hide: false,
                    type: 'success',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                setTimeout(function () {
                    btn.prop('disabled', false);
                    spin.addClass('hidden');
                    window.location.reload();
                }, 500);
            } else {
                new PNotify({
                    title: 'Đã xảy ra lỗi',
                    text: res.message,
                    hide: false,
                    type: 'error',
                    delay: 2500,
                    styling: 'bootstrap3'
                });
                setTimeout(function () {
                    btn.prop('disabled', false);
                    spin.addClass('hidden');
                    window.location.reload();
                }, 500);
            }
        }, error: function () {
        }
    });
}

function sendVinRequestPayment(e, booking_id) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'sale/bookings/sendVinRequestPayment/' + booking_id,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            saveCommentLog(e);
            new PNotify({
                title: 'Thành công', text: res.message, hide: false, type: 'success', delay: 2500, styling: 'bootstrap3'
            });
            setTimeout(function () {
                btn.prop('disabled', false);
                spin.addClass('hidden');
                window.location.reload();
            }, 1500);
        },
        error: function () {
        }
    });
}

function sendPaymentToVin(e, booking_id) {
    var btn = $(e);
    btn.prop('disabled', true);
    var spin = $(e).find('i.fa-spin');
    spin.removeClass('hidden');
    $.ajax({
        url: baseUrl + 'accountant/dashboards/sendPaymentToVin/' + booking_id,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            saveCommentLog(e);
            new PNotify({
                title: 'Thành công', text: res.message, hide: false, type: 'success', delay: 2500, styling: 'bootstrap3'
            });
            setTimeout(function () {
                btn.prop('disabled', false);
                spin.addClass('hidden');
                window.location.reload();
            }, 1500);
        },
        error: function () {
        }
    });
}

function chooseVinHotel(e) {
    $('.vin-booking-room-information').empty();
    let hotel_id = $('select[name=hotel_id]').val();
    let data = $('#list-input-room-data :input').serialize();
    let user_id = $('select[name=user_id]').val();
    let daterange = $('input[name=daterange]').val();
    data += "&user_id=" + user_id + "&daterange=" + daterange;
    if (hotel_id && data && user_id && daterange) {
        $(e).prop('disabled', true);
        $.ajax({
            url: baseUrl + 'sale/bookings/chooseVinHotel/' + hotel_id,
            type: 'get',
            data: data,
            dataType: 'html',
            success: function (res) {
                $(e).prop('disabled', false);
                $('.vin-booking-room-information').append(res);
                $('.collapse-link').on('click', function () {
                    var $BOX_PANEL = $(this).closest('.x_panel'), $ICON = $(this).find('i'),
                        $BOX_CONTENT = $BOX_PANEL.find('.x_content');

                    // fix for some div with hardcoded fix class
                    if ($BOX_PANEL.attr('style')) {
                        $BOX_CONTENT.slideToggle(200, function () {
                            $BOX_PANEL.removeAttr('style');
                        });
                    } else {
                        $BOX_CONTENT.slideToggle(200);
                        $BOX_PANEL.css('height', 'auto');
                    }

                    $ICON.toggleClass('fa-chevron-up fa-chevron-down');
                });
                $('input.iCheck').iCheck({
                    checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'
                });
                $('.custom-singledate-picker').daterangepicker({
                    "singleDatePicker": true, showDropdowns: true, locale: {
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

                    let totalChoosePackage = $('input.vin-room-pick[data-room-key="' + roomKey + '"][data-allotment-id="' + allotmentId + '"][data-rate-plan-code="' + ratePlanCode + '"]:checked').length;
                    if (parseInt(totalChoosePackage) <= parseInt($(this).data('package-left'))) {
                        chooseVinRoom(roomIndex, roomKey, packagePrice, packageId, rateplanId, revenue, saleRevenue, packageCode, packageName, allotmentId, roomTypeCode, ratePlanCode, defaultPrice, dateRange);
                    } else {
                        let selector = $(this)
                        setTimeout(function () {
                            selector.closest('div.icheckbox_flat-blue').removeClass('checked');
                        }, 10);
                        Swal.fire({
                            title: 'Error!', text: 'Đã vượt quá giới hạn gói!', icon: 'error'
                        })
                    }
                });
                if ($('div#dropzone-upload').length) {
                    Dropzone.autoDiscover = false;
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
                                type: 'POST', url: baseUrl + 'medias/delete_image_ajax', headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }, data: {
                                    filePath: filePath
                                }, dataType: 'html', success: function (data) {
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
                            if ($('input[name=list_image]').length) {
                                var images = $('input[name=list_image]').val();
                                if (images) {
                                    var data = jQuery.parseJSON(images);
                                    $.each(data, function (key, value) {
                                        var mockFile = {name: value};
                                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, baseUrl + value);
                                        thisDropzone.createThumbnailFromUrl(mockFile, baseUrl + value, function () {
                                            thisDropzone.emit("complete", mockFile);
                                        });
//                thisDropzone.emit("complete", mockFile);
                                        $(thisDropzone.element).children('.dz-preview').eq(key).attr('data-path', value);
                                    });
                                }
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
            },
            error: function () {

            }
        });
    }
}

function chooseVinRoom(id, room_id, price, package_id, rateplan_id, revenue, saleRevenue, packageCode, packageName, allotmentId, roomTypeCode, ratePlanCode, defaultPrice, dateRange) {
    let data = $('input[name=choose-room-' + room_id + ']').val();
    let hotelId = $('select#choose-type-booking-system :selected').val();
    let numAdult = $('input.vin_room-' + id + '-num_adult').val();
    let numChild = $('input.vin_room-' + id + '-num_child').val();
    let numKid = $('input.vin_room-' + id + '-num_kid').val();
    $.ajax({
        url: baseUrl + 'sale/bookings/chooseVinRoom/', type: 'post', data: {
            id: id,
            room_id: room_id,
            price: price,
            package_id: package_id,
            rateplan_id: rateplan_id,
            revenue: revenue,
            saleRevenue: saleRevenue,
            packageCode: packageCode,
            packageName: packageName,
            allotmentId: allotmentId,
            roomTypeCode: roomTypeCode,
            ratePlanCode: ratePlanCode,
            defaultPrice: defaultPrice,
            dateRange: dateRange,
            hotelId: hotelId,
            roomData: JSON.parse(data),
            numAdult: numAdult,
            numChild: numChild,
            numKid: numKid,
        }, dataType: 'json', success: function (res) {
            if (res.success) {
                $('#vin-room-' + id).empty();
                $('#vin-room-' + id).attr('data-vinroom-price', price);
                $('#vin-room-' + id).attr('data-vinroom-revenue', revenue);
                $('#vin-room-' + id).append(res.choose_vin_room);
                $('#vin-room-' + id).css("color", "#73879C");

                $('.single-booking-vin-room-' + id).empty();
                $('.single-booking-vin-room-' + id).append(res.input_vin_room);
                $('.single-booking-vin-room-' + id).removeClass('have-data');
                $('.single-booking-vin-room-' + id).addClass('have-data');
                let totalPrice = 0;
                let totalRevenue = 0;
                let totalPay = 0;
                let discount = $('input[name=agency_discount]').val() ? parseInt($('input[name=agency_discount]').val()) : 0;
                $('.single-room-detail').each(function (index, item) {
                    let str = $(item).attr('data-vinroom-price');
                    let strRevenue = $(item).attr('data-vinroom-revenue');
                    totalPrice += parseInt(str.replaceAll(',', ''));
                    totalRevenue += parseInt(strRevenue.replaceAll(',', ''));
                    totalPay = totalPrice - totalRevenue - discount;
                });
                totalPrice += parseInt($('input[name=change_price]').val());
                totalPay += parseInt($('input[name=change_price]').val());
                totalPrice = new Intl.NumberFormat('de-DE').format(totalPrice);
                totalRevenue = new Intl.NumberFormat('de-DE').format(totalRevenue);
                totalPay = new Intl.NumberFormat('de-DE').format(totalPay);
                discount = new Intl.NumberFormat('de-DE').format(discount);

                $('span#totalVinBookingPrice').empty();
                $('span#totalVinBookingPrice').text(totalPrice);

                if ($('#totalVinBookingRevenue').length) {
                    $('span#totalVinBookingRevenue').empty();
                    $('span#totalVinBookingRevenue').text(totalRevenue);
                }
                if ($('#totalAgencyPayVinBooking').length) {
                    $('span#totalAgencyPayVinBooking').empty();
                    $('span#totalAgencyPayVinBooking').text(totalPay);
                }
                if ($('#totalDiscount').length) {
                    $('span#totalDiscount').empty();
                    $('span#totalDiscount').text(discount);
                }
            }
        }, error: function () {
        }
    });
}

function calculatePrice(e) {
    let totalPrice = $('span#totalVinBookingPrice').text();
    totalPrice = totalPrice.replaceAll('.', '');
    totalPrice = parseInt(totalPrice);
    let totalRevenue = 0;
    if ($('span#totalVinBookingRevenue').length) {
        totalRevenue = $('span#totalVinBookingRevenue').text();
        totalRevenue = totalRevenue.replaceAll('.', '');
        totalRevenue = parseInt(totalRevenue);
    }


    let discount = $('input[name=agency_discount]').val();
    discount = discount.replaceAll(',', '');
    discount = discount.replaceAll('.', '');
    discount = parseInt(discount);
    let totalPay = totalPrice - totalRevenue - discount;
    let changePrice = $('input[name=change_price]').val();
    changePrice = changePrice.replaceAll(',', '');
    changePrice = changePrice.replaceAll('.', '');
    changePrice = parseInt(changePrice);
    totalPrice += changePrice;
    totalPay += changePrice;

    totalPrice = new Intl.NumberFormat('de-DE').format(totalPrice);
    totalRevenue = new Intl.NumberFormat('de-DE').format(totalRevenue);
    totalPay = new Intl.NumberFormat('de-DE').format(totalPay);
    discount = new Intl.NumberFormat('de-DE').format(discount);

    $('span#totalVinBookingPrice').empty();
    $('span#totalVinBookingPrice').text(totalPrice);

    if ($('#totalVinBookingRevenue').length) {
        $('span#totalVinBookingRevenue').empty();
        $('span#totalVinBookingRevenue').text(totalRevenue);
    }
    if ($('#totalAgencyPayVinBooking').length) {
        $('span#totalAgencyPayVinBooking').empty();
        $('span#totalAgencyPayVinBooking').text(totalPay);
    }
    if ($('#totalDiscount').length) {
        $('span#totalDiscount').empty();
        $('span#totalDiscount').text(discount);
    }
}

function showInputRoom(e, selector, type) {
    $('.popup-input-room').show();
}

function calculateTotalBookingRoom(selector) {
    let numRoom = 0;
    if ($(selector).closest('.popup-input-room').find('#num-room').length) {
        numRoom = parseInt($(selector).closest('.popup-input-room').find('#num-room').text());
    }

    let numA = 0;
    let numC = 0;
    let numK = 0;
    let adultEle = $(selector).find('.num-room-adult');
    adultEle.each(function () {
        numA += parseInt($(this).text());
    });

    let childEle = $(selector).find('.num-room-child');
    childEle.each(function () {
        numC += parseInt($(this).text());
    });

    let kidEle = $(selector).find('.num-room-kid');
    kidEle.each(function () {
        numK += parseInt($(this).text());
    });
    let text = '';
    if (numRoom != 0) {
        text = numRoom + " Phòng" + "-" + numA + "NL" + "-" + numC + "TE" + "-" + numK + "EB";
    } else {
        text = numA + "NL" + "-" + numC + "TE" + "-" + numK + "EB";
    }

    $(selector).closest('.input-group').find('input[name=num_people]').val(text);
    parseVinRoomForm();
}

function parseVinRoomForm() {
    let adultEle = $('.num-room-adult');
    let html = '';
    adultEle.each(function (index) {
        html += '<input type="hidden" class="vin_room-' + index + '-num_adult" name="vin_room[' + index + '][num_adult]" value="' + parseInt($(this).text()) + '">';
    });

    let childEle = $('.num-room-child');
    childEle.each(function (index) {
        html += '<input type="hidden" class="vin_room-' + index + '-num_child" name="vin_room[' + index + '][num_child]" value="' + parseInt($(this).text()) + '">';
    });

    let kidEle = $('.num-room-kid');
    kidEle.each(function (index) {
        html += '<input type="hidden" class="vin_room-' + index + '-num_kid" name="vin_room[' + index + '][num_kid]" value="' + parseInt($(this).text()) + '">';
    });
    $('#list-input-room-data').empty();
    $('#list-input-room-data').append(html);
}

function addNewAllotment(e, hotel_id) {
    $(e).prop('disabled', true);
    $.ajax({
        url: baseUrl + 'editor/hotels/renderNewAllotment/' + hotel_id,
        type: 'post',
        dataType: 'html',
        success: function (res) {
            $('.list-allotment').append(res);
            $('input.iCheck').iCheck({
                checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'
            });
            $(this).prop('disabled', false);
        },
        error: function () {
            $(this).prop('disabled', false);
        }
    });
}

function saveAllotmentRevenue(e) {
    let data = $(e).closest('form.allotment-revenue').serialize();
    $.ajax({
        url: baseUrl + 'editor/hotels/saveAllotment',
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (res) {
            alert('Lưu thành công');
        },
        error: function () {
        }
    });
}

function saveAllotmentRevenueChannel(e) {
    let data = $(e).closest('form.allotment-revenue-channel').serialize();
    $.ajax({
        url: baseUrl + 'editor/hotels/saveAllotment',
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (res) {
            alert('Lưu thành công');
        },
        error: function () {
        }
    });
}

function deleteAllotmentRevenue(e, is_saved) {
    if (confirm("Are you sure you want to delete this?")) {
        if (is_saved) {
            let data = $(e).closest('form.allotment-revenue').serialize();
            $.ajax({
                url: baseUrl + 'editor/hotels/deleteAllotment',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function (res) {
                    alert('Xóa thành công');
                    $(e).closest('div.single-allotment-code').remove();
                },
                error: function () {
                }
            });
        } else {
            $(e).closest('div.single-allotment-code').remove();
        }
    }
}

function openModalAddCode(vinBookingId) {
    $.ajax({
        url: baseUrl + 'accountant/dashboards/openModalAddCode/' + vinBookingId,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#booking-code').empty();
                $('#booking-code').text(res.booking_code);
                $('input[name=booking_id]').val(vinBookingId);
                $('#modalVinCode').modal('show');
            } else {
                alert('Booking không tồn tại!');
            }
        },
        error: function () {
            alert('Booking không tồn tại!');
        }
    });
}

function saveVinpearlCode() {
    let booking_id = $('input[name=booking_id]').val();
    let reservation_id = $('input[name=reservation_id]').val();
    $.ajax({
        url: baseUrl + 'accountant/dashboards/saveVinpearlCode', type: 'post', dataType: 'json', data: {
            booking_id: booking_id, reservation_id: reservation_id,
        }, success: function (res) {
            if (res.success) {
                $('#modalVinCode').modal('hide');
                alert('Lưu thành công');
            } else {
                alert(res.message);
            }
        }, error: function () {
            alert('Có lỗi xảy ra!');
        }
    });
}

function browseDeposit(id) {
    $.ajax({
        url: baseUrl + 'accountant/users/browseDebosit/' + id, type: 'post', dataType: 'json', success: function (res) {
            window.location.reload();
            if (res.success) {

            } else {

            }
        }, error: function () {
        }
    });
}

function deleteDeposit(id) {
    $.ajax({
        url: baseUrl + 'accountant/users/deleteDebosit/' + id, type: 'post', dataType: 'json', success: function (res) {
            window.location.reload();
        }
    });
}

function exportAccountantFile(e) {
    $(e).find('#cog-3').removeClass('hidden');
    var data = $('form#choose_date').serialize();
    $.ajax({
        type: "GET",
        url: baseUrl + 'accountant/dashboards/process_date',
        data: data,
        dataType: 'json',
        success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        },
        failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function exportAccountantHotelFile(e) {
    $(e).find('#cog-3').removeClass('hidden');
    var data = $('form#choose_date').serialize();
    $.ajax({
        type: "GET",
        url: baseUrl + 'accountant/dashboards/hotel_process_date',
        data: data,
        dataType: 'json',
        success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        },
        failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function exportAccountantLandtourFile(e) {
    $(e).find('#cog-3').removeClass('hidden');
    var data = $('form#choose_date').serialize();
    $.ajax({
        type: "GET",
        url: baseUrl + 'accountant/dashboards/process_date_landtour',
        data: data,
        dataType: 'json',
        success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        },
        failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function sendFirebaseMessage(id) {
    var data = new FormData();
    let text_message = $('input[name=text-message]').val();
    data.append("message", text_message);
    let agency_id = $('input[name=agency_id]').val();
    data.append("agency_id", agency_id);
    let is_read = 0;
    data.append("is_read", is_read);
    var file_data = $('input[name="images"]')[0].files;
    for (var i = 0; i < file_data.length; i++) {
        data.append("images", file_data[i]);
    }
    var newChatEle = [];
    if (Object.entries(file_data).length > 0) {
        newChatEle = $('<div class="row newMessage">\n' + '                    <div class="w-100-custom">\n' + '                        <div class="message-guest opacity-custom">\n' + '                            <p>' + file_data[0]['name'] + '\n' + '                        </div>\n' + '                    </div>\n' + '              </div>');
    } else {
        newChatEle = $('<div class="row ">\n' + '                    <div class="w-100-custom">\n' + '                        <div class="message-guest opacity-custom">\n' + '                            <p>' + text_message + '\n' + '                        </div>\n' + '                    </div>\n' + '              </div>');
    }
    if (text_message != '' || Object.entries(file_data).length > 0) {
        document.getElementById('text-message').readOnly = true;
        $('.user-' + agency_id + '-' + id).append(newChatEle);
        $('input[name=text-message]').val('');
        $('.body-content').scrollTop($('.body-content')[0].scrollHeight);
        $.ajax({
            type: "post",
            url: baseUrl + '/chat/sendFirebaseMessage',
            processData: false,
            contentType: false,
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    document.getElementById('text-message').removeAttribute('readonly');
                    document.getElementById('file-input').value = "";
                    $('.newMessage').empty();
                    $('.newMessage').removeClass('newMessage');
                    $('.body-content').scrollTop($('.body-content')[0].scrollHeight);
                }
            }
        });
    }
}

function checkImg() {
    var file_data = $('input[name="images"]')[0].files;
    if (Object.entries(file_data).length > 0) {
        document.getElementById('text-message').value = file_data[0]['name'];
    }
}

function browseDeposit(id) {
    $.ajax({
        url: baseUrl + 'accountant/users/browseDebosit/' + id, type: 'post', dataType: 'json', success: function (res) {
            window.location.reload();
            if (res.success) {

            } else {

            }
        }, error: function () {
        }
    });
}

function deleteDeposit(id) {
    $.ajax({
        url: baseUrl + 'accountant/users/deleteDebosit/' + id, type: 'post', dataType: 'json', success: function (res) {
            window.location.reload();
            if (res.success) {

            } else {

            }
        }, error: function () {
        }
    });
}

function searchForVinPackage(selector) {
    let room_id = selector.attr('data-vinroom-id');
    let room_index = selector.attr('data-vinroom-index');
    let hotel_id = selector.attr('data-hotel-id');
    let num_adult = selector.attr('data-num-adult');
    let num_child = selector.attr('data-num-child');
    let num_kid = selector.attr('data-num-kid');
    let start_date = $('input[name=start_date_search]').val();
    let end_date = $('input[name=end_date_search]').val();
    selector.find('.fa-spinner').removeClass('hidden');

    $.ajax({
        url: baseUrl + 'sale/bookings/searchForVinPackage', data: {
            room_id: room_id,
            room_index: room_index,
            hotel_id: hotel_id,
            start_date: start_date,
            end_date: end_date,
            num_adult: num_adult,
            num_child: num_child,
            num_kid: num_kid,
            type: 'post',
            dataType: 'html',
            success: function (res) {
                $('button#searchForVinPackage').prop('disabled', false);
                selector.find('.fa-spinner').addClass('hidden');
                $('#modalAddNewPackage #list-vin-package').empty();
                $('#modalAddNewPackage #list-vin-package').append(res);
                $('input.iCheck.search-package').iCheck({
                    checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'
                });
                $('input.iCheck.vin-room-search-pick.search-package').on('ifChecked', function (event) {
                    $('#btnAddVinPackage').removeClass('hidden');

                    $('#btnAddVinPackage').attr('data-room-index', $(this).data('room-index'));
                    $('#btnAddVinPackage').attr('data-room-key', $(this).data('room-key'));
                    $('#btnAddVinPackage').attr('data-package-pice', $(this).data('package-pice'));
                    $('#btnAddVinPackage').attr('data-package-id', $(this).data('package-id'));
                    $('#btnAddVinPackage').attr('data-rateplan-id', $(this).data('rateplan-id'));
                    $('#btnAddVinPackage').attr('data-allotment-id', $(this).data('allotment-id'));
                    $('#btnAddVinPackage').attr('data-room-type-code', $(this).data('room-type-code'));
                    $('#btnAddVinPackage').attr('data-rate-plan-code', $(this).data('rate-plan-code'));
                    $('#btnAddVinPackage').attr('data-revenue', $(this).data('revenue'));
                    $('#btnAddVinPackage').attr('data-sale-revenue', $(this).data('sale-revenue'));
                    $('#btnAddVinPackage').attr('data-package-code', $(this).data('package-code'));
                    $('#btnAddVinPackage').attr('data-package-name', $(this).data('package-name'));
                    $('#btnAddVinPackage').attr('data-package-default-price', $(this).data('package-default-price'));
                });
            },
            error: function () {
                $('button#searchForVinPackage').prop('disabled', false);
            }
        }
    });
}

function addVinSearchPackage(e) {
    let roomIndex = $(e).attr('data-room-index');
    let roomKey = $(e).attr('data-room-key');
    let packagePrice = $(e).attr('data-package-pice');
    let packageId = $(e).attr('data-package-id');
    let rateplanId = $(e).attr('data-rateplan-id');
    let allotmentId = $(e).attr('data-allotment-id');
    let roomTypeCode = $(e).attr('data-room-type-code');
    let ratePlanCode = $(e).attr('data-rate-plan-code');
    let revenue = $(e).attr('data-revenue');
    let saleRevenue = $(e).attr('data-sale-revenue');
    let packageCode = $(e).attr('data-package-code');
    let packageName = $(e).attr('data-package-name');
    let defaultPrice = $(e).attr('data-package-default-price');

    let roomIndexPrice = $('.total-vin-room-' + roomIndex).text();
    let totalVinBookingPrice = $('#totalVinBookingPrice').text();
    let totalVinBookingRevenue = $('#totalVinBookingRevenue').text();
    let totalAgencyPayVinBooking = $('#totalAgencyPayVinBooking').text();

    let start_date = $('input[name=start_date_search]').val();
    let end_date = $('input[name=end_date_search]').val();

    $('#list-vin-package').empty()
    $('#btnAddVinPackage').addClass('hidden');

    let packageIndex = $('.list-package-input-room-' + roomIndex + ' .single-packet-input').length;
    $.ajax({
        url: baseUrl + 'sale/bookings/addSearchPackageVin', data: {
            start_date: start_date,
            end_date: end_date,
            roomIndex: roomIndex,
            roomIndexPrice: roomIndexPrice,
            totalVinBookingPrice: totalVinBookingPrice,
            totalVinBookingRevenue: totalVinBookingRevenue,
            totalAgencyPayVinBooking: totalAgencyPayVinBooking,
            roomKey: roomKey,
            packagePrice: packagePrice,
            packageId: packageId,
            rateplanId: rateplanId,
            allotmentId: allotmentId,
            roomTypeCode: roomTypeCode,
            ratePlanCode: ratePlanCode,
            revenue: revenue,
            saleRevenue: saleRevenue,
            packageCode: packageCode,
            packageName: packageName,
            defaultPrice: defaultPrice,
            packageIndex: packageIndex
        }, type: 'post', dataType: 'json', success: function (res) {
            $('button#btnAddVinPackage').prop('disabled', false);
            $('.list-package-room-' + roomIndex).append(res.html_package);
            $('.list-package-input-room-' + roomIndex).append(res.html_package_input);
            $('.total-vin-room-' + roomIndex).text(res.room_total);
            $('#totalVinBookingPrice').text(res.total_vin_booking_price);
            $('#totalVinBookingRevenue').text(res.total_vin_booking_revenue);
            $('#totalAgencyPayVinBooking').text(res.total_agency_pay_vin_booking);
            $('#modalAddNewPackage').modal('hide');
        }, error: function () {
            $('button#btnAddVinPackage').prop('disabled', false);
        }
    });
}

function getMessage(e) {
    let roomId = e.getAttribute('data-value');
    var af = roomId.split('-');
    $('.custom-notify').addClass('d-none');
    let docRef = db.collection('chatroom').doc(roomId);
    docRef.get().then((doc) => {
        if (doc.exists) {
            if (doc.data().latestMessage.createdBy != af[1] && doc.data().is_read != 1) {
                doc.ref.update({is_read: 1});
            }
        }
    });
    $.ajax({
        type: "post", url: baseUrl + 'sale/users/getMessage', data: {
            roomId: roomId
        }, dataType: 'html', success: function (res) {
            $('.message-content').empty();
            $('.message-content').append(res);
            $('.body-content').scrollTop($('.body-content')[0].scrollHeight);
        },
    });
}


function checkChooseVinRoom() {
    var check = true;
    if ($('.vin-booking-room-information').children().length == 0) {
        check = false;
    }
    $('.single-room-detail').each((index, e) => {
        if (e.children.length == 0) {
            e.append("Bạn chưa chọn gói cho phòng " + (index + 1));
            e.style.color = "red";
            $(window).scrollTop($('.vin-booking-room-information').offset().top);
            check = false;
        }
    });
    if (check == true) {
        $("#form-booking-system").submit();
    } else {
        $("#error-choose-room").text("Bạn chưa chọn đủ phòng!").css('color', 'red').show().fadeOut(10000);
    }
}

function checkVadidate(e) {
    console.log($('#list-hotel-item')[0].children);
    if ($('#list-hotel-item')[0].children.length == 0) {
        $("#error-choose-room").text("Bạn chưa chọn đủ phòng!").css('color', 'red').show().fadeOut(10000);
        $(window).scrollTop($('#error-choose-room').offset().top);
    } else {
        $("#form-booking-system").submit();
        // saveCommentLog(e);
    }
}

function checkVadidateV2(e) {
    var divElem = document.getElementById("list-hotel-item");
    var inputElements = divElem.querySelectorAll("input, select");
    inputElements.forEach(function (item) {
        if (item.value.length == 0 && item.id.length > 1) {
            if (document.getElementsByClassName('error_' + item.id)) {
                console.log($('.error_' + item.id));
                $('.error_' + item.id).addClass('error_' + item.name);
                console.log('error_' + item.name);
                console.log($('span.error_' + item.name));
                $('.error_' + item.id).text("Bạn chưa nhập thông tin").css('color', 'red').show().fadeOut(10000);
            }
        }
    });
    if ($('select#user_id').val() == 0) {
        $("#error-user-id").text("Bạn chưa chọn Cộng tác viên").css('color', 'red').show().fadeOut(10000);
        $(window).scrollTop($('#error-user-id').offset().top);
    } else if ($('select#booking-hotel-select').val() == 0) {
        $("#error-booking-hotel-select").text("Bạn chưa chọn Khách sạn").css('color', 'red').show().fadeOut(10000);
        $(window).scrollTop($('#error-booking-hotel-select').offset().top);
    } else {
        $("#form-booking-system").submit();
        // saveCommentLog(e);
    }
}

function searchMessage() {
    let text = document.getElementById("search-message").value;
    $.ajax({
        url: baseUrl + 'sale/users/listMessage', type: 'post', data: {
            search: text
        }, dataType: 'html', success: function (res) {
            $('.list-chat').empty();
            $('.list-chat').append(res);
        }
    });
}

function exportAccountantFileSale(e) {
    $(e).find('#cog-3').removeClass('hidden');
    var data = $('form#choose_date').serialize();
    $.ajax({
        type: "GET",
        url: baseUrl + 'accountant/dashboards/process_date_sale',
        data: data,
        dataType: 'json',
        success: function (res) {
            $(e).find('#cog-3').addClass('hidden');
            $('#download-link').empty();
            var linkDownload = '<a href="' + res.link + '" target="_blank"> ' + res.file_name;
            $('#download-link').append(linkDownload);
        },
        failure: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function saveEditVinpearl(e) {
    $(e).find('#cog-3').removeClass('hidden');
    var data = $('form#form-booking-system').serialize();
    $.ajax({
        url: baseUrl + 'sale/bookings/saveEditVinpearl',
        type: 'GET',
        data: data,
        dataType: 'html',
        success: function (res) {
            // window.location.reload();
            $(e).find('#cog-3').addClass('hidden');
            if (res.success) {

            } else {

            }
        },
        error: function () {
            $(e).find('#cog-3').removeClass('hidden');
        }
    });
}

function saveCommentLog(event) {
    var e = '';
    if (event.currentTarget) {
        e = event.currentTarget;
    } else {
        e = event;
    }
    // console.log(e);
    let title = Number($(e).data('title'));
    var cmt = '';
    if ($('#log-cmt').val()) {
        cmt = $('#log-cmt').val();
    }
    let id = $(e).data('id');
    let code = $(e).data('code');
    let role = $(e).data('role');
    let ctl = $(e).data('ctl');
    if (cmt.length > 0 || title > 1) {
        $.ajax({
            url: baseUrl + role + '/' + ctl + '/saveCommentLogs', type: 'GET', data: {
                cmt: cmt, id: id, code: code, title: title
            }, dataType: 'html', success: function (res) {
                window.location.reload();
            }, error: function () {

            }
        });
    }
}

function addHotelRoomV2(selector, isChangeHotel) {
    var hotel_id = $('select[name=item_id] option:selected').val();
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/showListRoomsV2', data: {
            hotel_id: hotel_id,
        }, dataType: 'html', success: function (res) {
            console.log(res);
            $('.option-room').empty();
            $('.option-room').append(res);
        }, failure: function () {

        }
    });
}

function bookingChangeHotelV2(e) {
    console.log(e)
    $("#list-hotel-room").empty();
    var hotel_id = $(e).val();
    var booking_id = $(e).data('booking-id');
    console.log(e, hotel_id, booking_id)
    $.ajax({
        type: "GET", url: baseUrl + 'sale/bookings/changeHotelSurchargeV2', data: {
            hotel_id: hotel_id, booking_id: booking_id
        }, dataType: 'json', success: function (res) {
            // $("#list-hotel-item").empty();
            if (booking_id.length == 0){
                addHotelRoomV2('#list-hotel-item', false)
            }
            $("#hotel-list-surcharges").empty();
            $("#hotel-list-surcharges").append(res.surcharge);
            $("#object-payment-information").append(res.payment_information);
            var selector = $('#hotel-list-surcharges');
            updateIndexInput(selector, 'normal-surcharge-item');

            // $("#hotel-list-surcharges input.flat").iCheck({
            //     checkboxClass: 'icheckbox_flat-green',
            //     radioClass: 'iradio_flat-green'
            // });
            // $('input.surcharge-check').on('ifChecked', function (event) {
            //     var parent = $(this).parents('.normal-surcharge-item');
            //     parent.find('.surcharge-normal-quantity input,select').val('');
            //     parent.find('.surcharge-normal-quantity input,select').prop('disabled', false);
            //     parent.find('.surcharge-normal-quantity input,select').prop('required', true);
            //     parent.find('.surcharge-normal-quantity').removeClass('hidden');
            //
            // });
            // $('input.surcharge-check').on('ifUnchecked', function (event) {
            //     var parent = $(this).parents('.normal-surcharge-item');
            //     parent.find('.surcharge-normal-quantity input,select').val('')
            //     parent.find('.surcharge-normal-quantity input,select').prop('disabled', true);
            //     parent.find('.surcharge-normal-quantity input,select').prop('required', false);
            //     parent.find('.surcharge-normal-quantity').addClass('hidden');
            // });
            // $('.timepicker').daterangepicker({
            //     timePicker: true,
            //     timePicker24Hour: true,
            //     singleDatePicker: true,
            //     locale: {
            //         format: 'HH:mm'
            //     },
            //     autoUpdateInput: false,
            // }).on('show.daterangepicker', function (ev, picker) {
            //     picker.container.find(".calendar-table").hide();
            // }).on('apply.daterangepicker', function (ev, picker) {
            //     $(this).val(picker.startDate.format('HH:mm'));
            //     // calBookingHotelPrice();
            //     calBookingHotelPriceV2();
            // });


            $(".currency").keyup(function (e) {
                $(this).val(formatCurrency($(this).val()));
            });
            // calBookingHotelPrice();
            calBookingHotelPriceV2();
        }, failure: function () {

        }
    });
}

function invoiceCheck(e, str) {
    if (e == 1) {
        var id = '#' + str;
        var id1 = '#' + str + '_1';
        var id2 = '#' + str + '_0';
        $(id).removeClass('d-none');
        $(id1).removeClass('d-none');
        $(id2).addClass('d-none');
    } else {
        var id = '#' + str;
        var id1 = '#' + str + '_0';
        var id2 = '#' + str + '_1';
        $(id).addClass('d-none');
        $(id1).removeClass('d-none');
        $(id2).addClass('d-none');
    }
}
//Daiacma code
function showModalUser(id){
    $.ajax({
        type: "GET",
        url: baseUrl + 'admin/users/showModalUser',
        data: {
            id: id,
        },
        dataType: 'json',
        success: function (res) {
            $('#modal-detail-user').empty();
            $('#modal-detail-user').append(res.modal_user);
            console.log( $('#modal-detail-user'));
            $('#exampleModal').modal('show');

        }, failure: function () {

        }
    });
}
//end Daiacm code
