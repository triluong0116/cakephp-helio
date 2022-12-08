/**
 * Created by D4rk on 11/07/2018.
 */
if ('undefined' == $.type(Frontend)) {
    var Frontend = {
        listLocation: [],
        listPrice: [],
        listRating: [],
        isErrorTotalPeople: false
    };
}
Frontend.updateQueryStringParameter = function (uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
};
Frontend.numberFormat = function (nStr) {
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
Frontend.updateIndexInput = function (selector, cls) {
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
            var newId = newName.replace("][", "_");
            newId = newId.replace("[", "_");
            newId = newId.replace("]", "");
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

//                    $this.val('');
        });
    });
}
Frontend.filterLocation = function (e) {
    var value = $(e).val();
    Frontend.listLocation.push(parseInt(value));
    var url = Frontend.updateQueryStringParameter(window.location.href, 'location', Frontend.listLocation);
    document.location.href = url;
};
Frontend.uncheckFilterLocation = function (e) {
    var value = parseInt($(e).val());
    var index = Frontend.listLocation.indexOf(value);
    if (index > -1) {
        Frontend.listLocation.splice(index, 1);
    }
    var url = Frontend.updateQueryStringParameter(window.location.href, 'location', Frontend.listLocation);
    document.location.href = url;
};

Frontend.filterPrice = function (e) {
    var value = $(e).val();
    Frontend.listPrice.push(value);
    var url = Frontend.updateQueryStringParameter(window.location.href, 'price', Frontend.listPrice);
    console.log(url);
    document.location.href = url;
};
Frontend.filterPriceV2 = function (val) {
    var value = val[0] + "-" + val[1];
    var url = Frontend.updateQueryStringParameter(window.location.href, 'slider-price', value);
    console.log(url);
    document.location.href = url;
};
Frontend.uncheckFilterPrice = function (e) {
    var value = $(e).val();
    var index = Frontend.listPrice.indexOf(value);
    if (index > -1) {
        Frontend.listPrice.splice(index, 1);
    }
    var url = Frontend.updateQueryStringParameter(window.location.href, 'price', Frontend.listPrice);
    document.location.href = url;
};

Frontend.filterRating = function (e) {
    var value = $(e).val();
    Frontend.listRating.push(parseInt(value));
    var url = Frontend.updateQueryStringParameter(window.location.href, 'rating', Frontend.listRating);
    document.location.href = url;
};
Frontend.uncheckFilterRating = function (e) {
    var value = parseInt($(e).val());
    var index = Frontend.listRating.indexOf(value);
    if (index > -1) {
        Frontend.listRating.splice(index, 1);
    }
    var url = Frontend.updateQueryStringParameter(window.location.href, 'rating', Frontend.listRating);
    document.location.href = url;
};

Frontend.formatCurrency = function (num) {
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
Frontend.checkLoginViaFacebook = function () {
    FB.login(function (response) {
        if (response.status === 'connected') {
            FB.api('/me?fields=id,email,name,picture.width(800).height(800)', function (response) {
                Frontend.exeLoginViaFacebook(response);
            });
        }
    }, {scope: 'email,user_location,user_birthday,manage_pages,publish_pages'});

};
Frontend.exeLoginViaFacebook = function (data) {
    $('#modal-loading-fb').modal('show');
    $.ajax({
        url: baseUrl + '/users/loginViaFb',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#modal-fb-finish #fb-avatar').attr('src', response.avatar);
                $('#modal-fb-finish #fb-name').text(response.name);
                $('#modal-loading-fb').modal('hide');
                $('#modal-fb-finish').modal('show');
            }
        }
    });
};

Frontend.checkLoginViaFacebookv2 = function () {
    FB.login(function (response) {
        if (response.status === 'connected') {
            FB.api('/me?fields=id,email,name,picture.width(800).height(800)', function (response) {
                Frontend.exeLoginViaFacebookv2(response);
            });
        }
    }, {scope: 'email,user_location,user_birthday,manage_pages,publish_pages'});
};
Frontend.exeLoginViaFacebookv2 = function (data) {
    $('#modal-loading-fb').modal('show');
    $.ajax({
        url: baseUrl + '/users/loginViaFb',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                window.location.href = baseUrl;
            }
        }
    });
};

Frontend._parseErrors = function (selector, errors) {
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
    if ($("#error_" + scrollId).length) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#error_" + scrollId).offset().top - 250
        }, 500);
    }


};
Frontend.hideHightlightErrors = function () {
    $('.error-messages').hide().text('');
}
Frontend.addVoucher = function () {
    Frontend.hideHightlightErrors();
    var data = $('form#addVoucher').serialize();
    $.ajax({
        url: baseUrl + 'RequestVouchers/add',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (!response.success) {
                Frontend._parseErrors('form#addVoucher', response.errors);
            } else {
                $('#addingvoucher').modal('hide');
                window.location.reload();
            }
        }
    });
};

Frontend.submitAnswers = function () {
    var data = $('form#agency-quiz').serialize();
//    console.log(data);
    $.ajax({
        url: baseUrl + 'Questions/agencyquiz',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $('#resultAgency #total').text(response.total);
            $('#resultAgency #result').text(response.count);
            $('#resultAgency').modal('show');

        }
    });
}

Frontend.selectLocation = function (e) {
    var selected = $(e).find("option:selected");
    var arrSelected = [];
    selected.each(function () {
        arrSelected.push(parseInt($(this).val()));
    });
    Frontend.listLocation = arrSelected;
    var url = Frontend.updateQueryStringParameter(window.location.href, 'location', Frontend.listLocation);
    document.location.href = url;
}

Frontend.selectPrice = function (e) {
    var selected = $(e).find("option:selected");
    var arrSelected = [];
    selected.each(function () {
        arrSelected.push($(this).val());
    });
    Frontend.listPrice = arrSelected;
    var url = Frontend.updateQueryStringParameter(window.location.href, 'price', Frontend.listPrice);
    document.location.href = url;
}

Frontend.selectRating = function (e) {
    var selected = $(e).find("option:selected");
    var arrSelected = [];
    selected.each(function () {
        arrSelected.push(parseInt($(this).val()));
    });
    Frontend.listRating = arrSelected;
    var url = Frontend.updateQueryStringParameter(window.location.href, 'rating', Frontend.listRating);
    document.location.href = url;
}

Frontend.checkZaloStatus = function () {
    $('#modal-fb-finish').modal('hide');
    $.ajax({
        url: baseUrl + 'Users/checkZalo',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (!response.success) {
                $('#modal-update-info').modal('show');
            } else {
                $('#modal-fb-finish-2').modal('show');
            }
        }
    });
}

Frontend.loadModalUpdateInfo = function () {
    $('#modal-fb-finish').modal('hide');
    var data = $('form#updateZalo').serialize();
    $.ajax({
        url: baseUrl + 'Users/updateZalo',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (!response.success) {
//                Frontend._parseErrors('form#updateZalo', response.errors);
            } else {
                $('#modal-update-info').modal('hide');
//                $('#modal-update-fanpage').modal('show');
                window.location.href = baseUrl + '/chinh-sach-cong-tac-vien-page-2';
            }
        }
    });
}

Frontend.checkSessionUser = function (object_id, days_attended, type, form_id) {
    $.ajax({
        url: baseUrl + 'Users/checkSession',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('form#addBooking input[name=object_id]').val(object_id);
                $('form#addBooking input[name=object_type]').val(type);
                $('form#addBooking input[name=days_attended]').val(days_attended);
                $('form#addBooking input[name=user_id]').val(response.user_id);

                var checkIn = Frontend.convertDate($('form' + form_id).find('input[name=fromDate]').val());
                if ($('form' + form_id + ' input[name=toDate]').length) {
                    var checkOut = Frontend.convertDate($('form' + form_id).find('input[name=toDate]').val());
                }
                var amount = '';
                var people = '';
                switch (form_id) {
                    case '#hotelRoomSelection':
                        amount = $('form' + form_id + ' input[name=numRoom]').val();
                        people = $('form' + form_id + ' input[name=numPeople]').val();
                        break;
                    case '#homestaySelection':
                        amount = $('form' + form_id + ' input[name=numRoom]').val();
                        people = $('form' + form_id + ' input[name=numPeople]').val();
                        break;
                    case '#voucherSelection':
                        amount = $('form' + form_id + ' input[name=numVoucher]').val();
                        break;
                    case '#landTourSelection':
                        amount = $('form' + form_id + ' input[name=numPeople]').val();
                        break;
                }
                var room_id = $('form' + form_id + ' input[name=room_id]').val();

                $('form#addBooking #booking-start-date').data("DateTimePicker").date(new Date(checkIn));
                if ($('form#addBooking #booking-end-date').length) {
                    $('form#addBooking #booking-end-date').data("DateTimePicker").date(new Date(checkOut));
                }
                $('form#addBooking select[name=room_level]').val(room_id);
                $('form#addBooking input[name=amount]').val(amount);
                $('form#addBooking input[name=people_amount]').val(people);

                $('#booking').modal('show');

            } else {
                $('#findAgencyLoading').modal('show');
//                console.log(referenceCode);
                websocket.send(JSON.stringify({event: 'find', ref_code: referenceCode, fingerprint: fingerPrint}));
            }
        }
    });
}

Frontend.convertDate = function (str) {
    var from = str.split("-");
    var f = new Date(from[2], from[1] - 1, from[0]);
    return f;
}

Frontend.addBookingCombo = function () {
//    $('#booking').modal('hide');
    Frontend.hideHightlightErrors();
    var data = $('form#addBooking').serialize();
//    console.log(data);
    $.ajax({
        url: baseUrl + 'Bookings/add_combo',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#booking').modal('hide');
                $('#finish-booking').modal('show');
            } else {
                Frontend._parseErrors('#addBooking', response.errors);
            }
        }
    });
}

Frontend.addBookingVoucher = function () {
    Frontend.hideHightlightErrors();
    var data = $('form#voucherBookingForm').serialize();
//    console.log(data);
    $.ajax({
        url: baseUrl + 'Bookings/add_voucher',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $('#requestVoucherBooking').prop("disabled", true);
            if (response.success) {
                window.location.href = baseUrl + 'dat-booking/success';
            } else {
                Frontend._parseErrors('#voucherBookingForm', response.errors);
                $('#requestVoucherBooking').prop("disabled", true);
            }
        }
    });
}

Frontend.addBookingLandtour = function () {
    Frontend.hideHightlightErrors();
    var data = $('form#landTourBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'bookings/add_landtour',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $('#requestLandTourBooking').prop("disabled", false);
            if (response.success) {
                window.location.href = baseUrl + 'thanh-toan/' + response.booking_code;
            } else {
                Frontend._parseErrors('#landTourBookingForm', response.errors);
            }
        }
    });
}

Frontend.addBookingHotel = function () {
//    $('#booking').modal('hide');
    Frontend.hideHightlightErrors();
    var data = $('form#hotelBookingForm').serialize();
    $('body .fa-spinner .send-booking-request').removeClass('hidden');
    $.ajax({
        url: baseUrl + 'bookings/add_hotel',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $('#requestBooking').prop("disabled", false);
            if (response.success) {
                window.location.href = baseUrl + 'dat-booking/success';
            } else {
                Frontend._parseErrors('#hotelBookingForm', response.errors);
            }

        }
    });
}

Frontend.addBookingHomestay = function () {
//    $('#booking').modal('hide');
    Frontend.hideHightlightErrors();
    var data = $('form#homeStayBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'Bookings/add_homestay',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            $('#requestHomeStayBooking').prop("disabled", false);
            if (response.success) {
                window.location.href = baseUrl + 'dat-booking/success';
            } else {
                Frontend._parseErrors('#homeStayBookingForm', response.errors);
            }
        },

    });
}

Frontend.loginViaTrippal = function (e) {
    var spinner = $(e).find('i');
    spinner.removeClass('hidden');
    Frontend.hideHightlightErrors();
    var data = $('form#loginTrippal').serialize();
//    console.log(data);
    $.ajax({
        url: baseUrl + 'Users/loginViaTrippal',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            spinner.addClass('hidden');
            if (response.success) {
                window.location.reload();
            } else {
                Frontend._parseErrors('#loginTrippal', response.errors);
            }
        },

    });
}
Frontend.forgetPassword = function (e) {
    var spinner = $(e).find('i');
    spinner.removeClass('hidden');
    Frontend.hideHightlightErrors();
    var data = $('form#forgetTrippal').serialize();
    $.ajax({
        url: baseUrl + 'Users/forgetPassword',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            spinner.addClass('hidden');
            if (response.success) {
                $('#forgetPassword').modal('hide');
                $('#forgotPassword-sent-success').modal('show');
            } else {
                Frontend._parseErrors('#forgetTrippal', response.errors);
            }
        }
    });
}

/* ------------------ Socket ------------------ */
Frontend.agencyAccept = function (e) {
    var fingerPrint = $(e).attr('data-finger-print') + '';
    var timestamp = $(e).attr("data-timestamp") + '';
    var send_data = {
        event: 'accept',
        fingerPrint: fingerPrint,
        timestamp: timestamp,
        user_id: currentUserId
    };
    websocket.send(JSON.stringify(send_data));
    $('#agencyChoosing').modal('hide');
}
/* ------------------ End Socket ------------------ */
Frontend.showModalPostFB = function (object_type, object_id) {
    $.ajax({
        url: baseUrl + 'fanpages/getListFanpage',
        type: 'GET',
        success: function (response) {
            $('#post-to-facebook #postFBSelectFanpage').empty();
            $('#post-to-facebook #postFBSelectFanpage').append(response);
            $('form#post-to-facebook input[name=object_type]').val(object_type);
            $('form#post-to-facebook input[name=object_id]').val(object_id);
            $('#modal-post-facebook').modal('show');
        }
    });
}
Frontend.postFacebook = function () {
    $('#modal-post-facebook #postFbLoading').removeClass('hidden');
    var data = $('form#post-to-facebook').serialize();
    $.ajax({
        type: "POST",
        url: baseUrl + 'fanpages/post_facebook',
        data: data,
//        dataType: 'json',
        success: function (res) {
            $('#modal-post-facebook #postFbLoading').addClass('hidden');
            if (res.success) {
                $('#modal-post-facebook').modal('hide');
                $('#finish-booking').modal('show');
            } else {

            }
        },
        error: function (res) {
            $('#modal-post-facebook #postFbLoading').addClass('hidden');
        }

    });
}

Frontend.withdraw = function () {
    $('#withdraw').modal('show');
}

Frontend.processWithdraw = function (e) {
    var spinner = $(e).find('i');
    spinner.removeClass('hidden');
    var data = $('form#addWithdraw').serialize();
    $.ajax({
        type: "POST",
        url: baseUrl + 'Withdraws/add',
        data: data,
        dataType: 'json',
        success: function (response) {
            spinner.addClass('hidden');
            if (response.success) {
                $('#withdraw').modal('hide');
                $('#finishWithdraw').modal('show');
            } else {
                Frontend._parseErrors('#withdraw', response.errors);
            }
        },
    });
}
Frontend.getPriceHotelByDate = function (date, hotel_id) {
    $.ajax({
        type: "POST",
        url: baseUrl + 'hotels/get_price_by_date',
        data: {
            hotel_id: hotel_id,
            date: date
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#priceUpdate').empty();
                $('#priceUpdate').text(response.new_price);
            }
        }
    });
}

Frontend.getPriceComboByDate = function (date, combo_id) {
    $.ajax({
        type: "POST",
        url: baseUrl + 'combos/get_price_by_date',
        data: {
            combo_id: combo_id,
            date: date
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#comboUpdate').empty();
                $('#comboUpdate').text(response.new_price);
                $('#comboUpdatePrice').empty();
                $('#comboUpdatePrice').text(response.combo_price);

            }
        }
    });
}

Frontend.openHotelDescription = function (hotel_id) {
    $.ajax({
        type: "get",
        url: baseUrl + 'Hotels/description/' + hotel_id,
        dataType: 'html',
        success: function (res) {
            $('#descriptionModal .modal-body').empty();
            $('#descriptionModal .modal-body').append(res);
            $('#descriptionModal').modal('show');
        }
    })
}

Frontend.openHotelCategory = function (hotel_id) {
    $.ajax({
        type: "get",
        url: baseUrl + 'Hotels/category/' + hotel_id,
        dataType: 'html',
        success: function (res) {
            $('#categoryModal .modal-body').empty();
            $('#categoryModal .modal-body').append(res);
            $('#categoryModal').modal('show');
        }
    })
}

Frontend.openHotelTerm = function (hotel_id) {
    $.ajax({
        type: "get",
        url: baseUrl + 'Hotels/term/' + hotel_id,
        dataType: 'html',
        success: function (res) {
            $('#termModal .modal-body').empty();
            $('#termModal .modal-body').append(res);
            $('#termModal').modal('show');
        }
    })
}

Frontend.sortLink = function (type) {
    var newUrl = Frontend.updateQueryStringParameter(window.location.href, 'sort', type);
    window.location.href = newUrl;
}
Frontend.sortLinkv2 = function (type, e) {
    var newUrl = Frontend.updateQueryStringParameter(window.location.href, 'sort', type);
    $(e).addClass("btn-sort-active");
    if (type == "ASC") {
        $(".btn-vin-desc").removeClass("btn-sort-active");
    }
    if (type == "DESC") {
        $(".btn-vin-asc").removeClass("btn-sort-active");
    }
    window.location.href = newUrl;
}
Frontend.findAgencyWithoutLoading = function (e) {
    var form = $(e).parents('form');
    // if (!currentUserId) {
    //     websocket.send(JSON.stringify({event: 'findv2', ref_code: referenceCode, fingerprint: fingerPrint}));
    // }
    form.submit();
}
Frontend.findAgencyWithoutLoadingVer2 = function (e) {
    var href = $(e).data('href');
    if (!currentUserId) {
        websocket.send(JSON.stringify({event: 'findv2', ref_code: referenceCode, fingerprint: fingerPrint}));
    }
    window.location.href = href;
}

Frontend.changeUserInfo = function () {
    var data = new FormData();

    //Form data
    var form_data = $('form#usereditinfo').serializeArray();
    $.each(form_data, function (key, input) {
        data.append(input.name, input.value);
    });

    //File data
    var file_data = $('input[name="avatar"]')[0].files;
    for (var i = 0; i < file_data.length; i++) {
        data.append("avatar", file_data[i]);
    }

    //Custom data
    data.append('key', 'value');

    $.ajax({
        type: "post",
        url: baseUrl + 'Users/edit_infor',
        processData: false,
        contentType: false,
        data: data,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                Frontend._parseErrors('#usereditinfo', response.errors);
            }
        },
    });
}

Frontend.changeUserPassword = function () {
    var data = $('form#usereditpass').serialize();
    $.ajax({
        type: "post",
        url: baseUrl + 'Users/edit_password',
        data: data,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                window.location.reload();
            } else {
                Frontend._parseErrors('#usereditpass', response.errors);
            }
        },
    });
}

Frontend.connectFacebook = function () {
    FB.login(function (response) {
        if (response.status === 'connected') {
            FB.api('/me?fields=id,email,name,picture.width(800).height(800)', function (response) {
                Frontend.exeConnectFacebook(response);
            });
        }
    }, {scope: 'email,user_location,user_birthday,manage_pages,publish_pages'});
}

Frontend.exeConnectFacebook = function (data) {
    $.ajax({
        url: baseUrl + '/users/connectFacebook',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                window.location.reload();
            }
        }
    });
};

Frontend.addZalo = function () {
    $('#zalo').modal('show');
}

Frontend.editZalo = function () {
    var data = $('form#editZalo').serialize();
    $.ajax({
        url: baseUrl + '/users/edit_zalo',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#zalo').modal('hide');
                $('#finishZalo').modal('show');
            } else {
                Frontend._parseErrors('#usereditpass', response.errors);
            }
        }
    });
}
Frontend.shareZaloSuccess = function () {
    var object_type = $('.zalo-share-button').data('object-type');
    var object_id = $('.zalo-share-button').data('object-id');
    $.ajax({
        url: baseUrl + 'users/share_zalo_success',
        data: {
            object_type: object_type,
            object_id: object_id
        },
        type: 'post',
        dataType: 'json',
        success: function (response) {
        }
    });
}

Frontend.generateLoading = function () {
    var loadingTbl = '<div class="panel"><div class="row pb10 pt10 no-mar-left no-mar-right panel-row "><div class="col-sm-20"><div class="loading-fb"></div></div><div class="col-sm-10"><div class="loading-fb"></div></div><div class="col-sm-6"><div class="loading-fb"></div></div></div></div>';
    $('#filter_result').empty();
    $('#filter_result').append(loadingTbl);
    var loadingStr = '<div class="loading-fb loading-fb-full mb10"></div><div class="loading-fb"></div>';
    $('#filter_result_str').empty();
    $('#filter_result_str').append(loadingStr);
    var loadingPrice = '<div class="loading-fb loading-fb-full"></div>';
    $('#filter_result_price').empty();
    $('#filter_result_price').append(loadingPrice);
    var loadingProfit = '<div class="loading-fb loading-fb-full"></div>';
    $('#filter_result_profit').empty();
    $('#filter_result_profit').append(loadingProfit);
    var loadingFinalPrice = '<div class="loading-fb loading-fb-full"></div>';
    $('#filter_result_final_price').empty();
    $('#filter_result_final_price').append(loadingFinalPrice);
}

Frontend.generateLoadingVer2 = function () {
    var loadingStr = '<div class="loading-fb loading-fb-full mb10"></div><div class="loading-fb"></div>';
    $('#filter_result_str').empty();
    $('#filter_result_str').append(loadingStr);
    var loadingPrice = '<div class="loading-fb loading-fb-full"></div>';
    $('#filter_result_price').empty();
    $('#filter_result_price').append(loadingPrice);
    var loadingProfit = '<div class="loading-fb loading-fb-full"></div>';
    $('#filter_result_profit').empty();
    $('#filter_result_profit').append(loadingProfit);


}

Frontend.filterHotelRoom = function () {
    Frontend.generateLoading();
    var data = $('form#hotelRoomSelection').serialize();
    $.ajax({
        url: baseUrl + 'hotels/filterRoomData',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#filter_result').empty();
            $('#filter_result').append(res.data);
            $('#filter_result_str').empty();
            $('#filter_result_str').append(res.result);
            $('#filter_result_price').empty();
            $('#filter_result_price').append(res.price);
            $('#filter_result_profit').empty();
            $('#filter_result_profit').append(res.profit);
            $('#filter_result_final_price').empty();
            $('#filter_result_final_price').append(res.final_price);
            $('form#hotelRoomSelection input[name=room_id]').val(res.first_room_id);
            $('.lightgallery2').lightGallery({download: false});
        }
    });
}

Frontend.filterHomestay = function () {
    Frontend.generateLoading();
    var data = $('form#homestaySelection').serialize();
    $.ajax({
        url: baseUrl + 'HomeStays/getHomestayPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#filter_result').empty();
            $('#filter_result').append(res.data);
            $('#filter_result_str').empty();
            $('#filter_result_str').append(res.result);
            $('#filter_result_price').empty();
            $('#filter_result_price').append(res.price);
            $('#filter_result_profit').empty();
            $('#filter_result_profit').append(res.profit);

        }
    });
}

Frontend.filterLandtour = function () {
    Frontend.generateLoadingVer2();
    var data = $('form#landTourSelection').serialize();
    $.ajax({
        url: baseUrl + 'land-tours/processLandtourPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#filter_result_str').empty();
            $('#filter_result_str').append(res.result);
            $('#filter_result_price').empty();
            $('#filter_result_price').append(res.price);
            $('#filter_result_profit').empty();
            $('#filter_result_profit').append(res.profit);

            $('.landtour-adult-price').empty();
            $('.landtour-adult-price').append(res.adult_price);

            $('.landtour-child-price').empty();
            $('.landtour-child-price').append(res.child_price);

            $('.landtour-kid-price').empty();
            $('.landtour-kid-price').append(res.kid_price);

            $('input[name=price]').val(res.adult_price);
        }
    });
}

Frontend.filterVoucher = function () {
    Frontend.generateLoadingVer2();
    var data = $('form#voucherSelection').serialize();
    $.ajax({
        url: baseUrl + 'vouchers/processVoucherPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#filter_result_str').empty();
            $('#filter_result_str').append(res.result);
            $('#filter_result_price').empty();
            $('#filter_result_price').append(res.price);
            $('#filter_result_profit').empty();
            $('#filter_result_profit').append(res.profit);
        }
    });
}

Frontend.filterHighlight = function (e, is_hotel) {
    var result = $(e).data('result');
    var price = $(e).data('price');
    var profit = $(e).data('profit');
    var final_price = $(e).data('final-price');
    var room_id = $(e).data('room-id');
    $('#filter_result_str').empty();
    $('#filter_result_str').append(result);
    $('#filter_result_price').empty();
    $('#filter_result_price').append(price);
    $('#filter_result_profit').empty();
    $('#filter_result_profit').append(profit);
    $('#filter_result_final_price').empty();
    $('#filter_result_final_price').append(final_price);
    $('.filter-accordion .panel-row').removeClass('panel-bg-blue');
    $('.filter-accordion .panel').removeClass('panel-border-blue');
    if (is_hotel) {
        $(e).find('.panel-row').addClass('panel-bg-blue');
        $(e).addClass('panel-border-blue');
    }
    if ($('form#hotelRoomSelection').length) {
        $('form#hotelRoomSelection input[name=room_id]').val(room_id);
    }
}

Frontend.filterHighlightLandtour = function (e) {
    $('.filter-accordion .panel-row').removeClass('panel-bg-blue');
    $('.filter-accordion .panel').removeClass('panel-border-blue');
    $(e).find('.panel-row').addClass('panel-bg-blue');
    $(e).addClass('panel-border-blue');
}

Frontend.searchSuggest = function (e, selector, type) {
    var keyword = $(e).val();
    if (keyword && keyword.length >= 3) {
        $(selector).show();
        $.ajax({
            url: baseUrl + 'Pages/search_suggest',
            data: {
                keyword: keyword,
                type: type
            },
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $(selector).empty();
                $(selector).append(response);
            }
        });
    }

}
Frontend.searchSuggestVin = function (e, selector) {
    var keyword = $(e).val();
    if (keyword && keyword.length >= 3) {
        $(selector).show();
        $.ajax({
            url: baseUrl + 'Pages/searchSuggestVin',
            data: {
                keyword: keyword
            },
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $(selector).empty();
                $(selector).append(response);
            }
        });
    }

}
Frontend.showInputRoom = function (e, selector, type) {
    $('.popup-input-room').show();
}

/*-----------------  Booking JS  -----------------------*/
Frontend.addRoomToBooking = function (e) {
    var spinner = $(e).find('i');
    spinner.removeClass('hidden');
    var hotel_id = $('#hotelBookingForm').find('input[name=hotel_id]').val();
    var selector = $('#list-room-booking');
    $.ajax({
        url: baseUrl + 'hotels/addHotelRoom',
        data: {
            hotel_id: hotel_id
        },
        type: 'get',
        dataType: 'html',
        success: function (res) {
            $('#requestBooking').prop("disabled", false);
            spinner.addClass('hidden');
            $('#list-room-booking').append(res);
            console.log('here');
            selector.find('.datepicker').datetimepicker({
                format: 'DD-MM-YYYY',
                ignoreReadonly: true
            });
            selector.find('.datepicker input').click(function (event) {
                var parent = $(this).parents('.datepicker');
                parent.data("DateTimePicker").show();
            });
            Frontend.updateIndexInput(selector, 'booking-room-item');
        },
        error: function () {
            spinner.addClass('hidden');
            $('#requestBooking').prop("disabled", false);
        }
    });
}
Frontend.addRoomToBookingVin = function (e) {
    var spinner = $(e).find('i');
    spinner.removeClass('hidden');
    var hotel_id = $('#hotelBookingForm').find('input[name=hotel_id]').val();
    var selector = $('#list-room-booking');
    $.ajax({
        url: baseUrl + 'hotels/addHotelRoomVin',
        data: {
            hotel_id: hotel_id
        },
        type: 'get',
        dataType: 'html',
        success: function (res) {
            $('#requestBooking').prop("disabled", false);
            spinner.addClass('hidden');
            $('#list-room-booking').append(res);
            console.log('here');
            selector.find('.datepicker').datetimepicker({
                format: 'DD-MM-YYYY',
                ignoreReadonly: true
            });
            selector.find('.datepicker input').click(function (event) {
                var parent = $(this).parents('.datepicker');
                parent.data("DateTimePicker").show();
            });
            Frontend.updateIndexInput(selector, 'booking-room-item');
        },
        error: function () {
            spinner.addClass('hidden');
            $('#requestBooking').prop("disabled", false);
        }
    });
}

Frontend.deleteItem = function (e, parentClass) {
    $(e).removeAttr('href');
    var parent = $(e).parents(parentClass);
    var selector = parent.parent();
    var cls = parent.attr('class');
    parent.remove();
    Frontend.updateIndexInput(selector, cls);
    if ($('form#hotelBookingForm').length) {
        Frontend.calBookingHotelPrice();
    }
}

Frontend.addSelectChildAge = function (selector) {
    console.log('run select age');
    var data = selector.find(':input').serialize();
    $.ajax({
        url: baseUrl + 'hotels/addSelectChildAge',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                selector.find('.list-child-age').empty();
                selector.find('.list-child-age').append(res.data);
                Frontend.updateIndexInput('#list-room-booking', 'booking-room-item');
                Frontend.hideHightlightErrors();
            } else {
                Frontend._parseErrors('#hotelBookingForm', res.errors);
            }
        },
        error: function () {
        }
    });
}
Frontend.LandTourAddSelectChildAge = function (selector) {
    var data = selector.find(':input').serialize();
    console.log(data);
    $.ajax({
        url: baseUrl + 'land-tours/LandTourAddSelectChildAge',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                selector.find('.list-child-age').empty();
                selector.find('.list-child-age').append(res.data);
            }
        },
        error: function () {
        }
    });
}
Frontend.calBookingHotelPrice = function () {
    var selector = $('#booking-list-surcharges');
    Frontend.updateIndexInput(selector, 'normal-surcharge-item');
    var data = $("form#hotelBookingForm").serialize();
    Frontend.hideHightlightErrors();
    $.ajax({
        url: baseUrl + 'hotels/calBookingHotelPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                // Frontend.calAutoSurcharge();
                // update Auto Surcharge
                // $('#list-auto-surcharge').empty();
                // $('#list-auto-surcharge').append(res.data_auto_surcharge);
                var selector = $('#booking-list-surcharges');
                Frontend.updateIndexInput(selector, 'normal-surcharge-item');
                $.each(res.data_surcharge_price, function (index, value) {
                    if (index.indexOf('show-price') != -1) {
                        $("#" + index).text(Frontend.numberFormat(value));
                    } else {
                        $("#" + index).val(value);
                    }
                });

                // update Normal Surcharge
                // $('#list-normal-surcharge .normal-surcharge-item').each(function () {
                //     var inputCheck = $(this).find('input.surcharge-check');
                //     var inputQuantity = $(this).find('input.normal-surcharge-value');
                //     if (inputCheck.is(':checked')) {
                //         Frontend.calNormalSurcharge(inputQuantity);
                //     }
                // });
                $('#total_booking_price').text(Frontend.numberFormat(res.total_price));
                $('#total_booking_revenue').text(Frontend.numberFormat(res.total_revenue));
                $('#booking-str').text(res.booking_str);
                // $('.booking-room-item').each(function () {
                //     Frontend.addSelectChildAge($(this));
                // })
            } else {
                Frontend._parseErrors('#hotelBookingForm', res.errors);
            }

        },
        error: function () {
        }
    });
}
Frontend.calBookingRoomPrice = function (selector) {
    var data = selector.find(':input').serialize();
    $.ajax({
        url: baseUrl + 'hotels/calBookingRoomPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            // Frontend.calAutoSurcharge();
            $('#list-normal-surcharge .normal-surcharge-item').each(function () {
                var inputCheck = $(this).find('input.surcharge-check');
                var inputQuantity = $(this).find('input.normal-surcharge-value');
                if (inputCheck.is(':checked')) {
                    Frontend.calNormalSurcharge(inputQuantity);
                }
            });
            selector.find('input.room-price').val(res.price);
            Frontend.calBookingTotalPrice();
        },
        error: function () {
        }
    });
}
Frontend.calAutoSurcharge = function () {
    // Frontend.hideHightlightErrors();
    // var data = $('#hotelBookingForm').serialize();
    // $.ajax({
    //     url: baseUrl + 'hotels/calAutoSurcharge',
    //     data: data,
    //     type: 'get',
    //     dataType: 'json',
    //     success: function (res) {
    //         if (res.success) {
    //             $('#list-auto-surcharge').empty();
    //             $('#list-auto-surcharge').append(res.data);
    //             var selector = $('#booking-list-surcharges');
    //             Frontend.updateIndexInput(selector, 'normal-surcharge-item');
    //             Frontend.calBookingTotalPrice();
    //         } else {
    //             Frontend._parseErrors('#hotelBookingForm', res.errors);
    //         }
    //     },
    //     error: function () {
    //     }
    // });
}

Frontend.addOtherSurcharge = function (e) {
    $.ajax({
        url: baseUrl + 'hotels/addOtherSurcharge',
        type: 'get',
        dataType: 'html',
        success: function (res) {
            $('.list-other-surcharge').append(res);

            var selector = $('#booking-list-surcharges');
            Frontend.updateIndexInput(selector, 'normal-surcharge-item');
            $('.currency').keyup(function () {
                $(this).val(Frontend.formatCurrency($(this).val()));
            });
        },
        error: function () {
        }
    });
}
Frontend.calNormalSurcharge = function (e) {
    var hotel_id = $('form#hotelBookingForm input[name=hotel_id]').val();
    var data = $('#list-room-booking :input').serialize();
    var surcharge_type = $(e).parents('.normal-surcharge-item').find("input[name*='surcharge_type']").val();
    if ($(e).hasClass('timepicker')) {
        var date = $(e).data('DateTimePicker').date()._d;
        var quantity = date.getHours().toString().padStart(2, 0) + ":" + date.getMinutes().toString().padStart(2, 0);
    } else {
        var quantity = $(e).val();
    }
    data += '&hotel_id=' + hotel_id + "&surcharge_type=" + surcharge_type + "&quantity=" + quantity;
    // var data = $('form#hotelBookingForm').serialize();

    $.ajax({
        url: baseUrl + 'hotels/calNormalSurcharge',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            $(e).parents('.normal-surcharge-item').find('span.normal-surcharge-fee').text(Frontend.numberFormat(res.price));
            $(e).parents('.normal-surcharge-item').find('input.booking-value').val(res.price);
            Frontend.calBookingTotalPrice();
        },
        error: function () {
        }
    });
}

Frontend.calBookingTotalPrice = function () {
    var total_price = 0
    $('input.booking-value').each(function () {
        var value = $(this).val();
        if (value) {
            var price = parseInt(value.replace(',', ''));
            total_price += price;
        }
    });
    $('#total_booking_price').text(Frontend.numberFormat(total_price));
};
Frontend.updateListImgUploaded = function () {
    var order = $("#dropzone-upload .dz-preview").map(function () {
        var src = $(this).data('path');
        console.log(src);
        return src;
    }).get();
    var json = JSON.stringify(order);
    console.log(json);
    if ($('#paymentForm').length) {
        $('#paymentForm input[name=images]').val(json);
    }
    if ($('#hotelVinBookingForm').length) {
        $('#hotelVinBookingForm input[name=images]').val(json);
    }
    if ($('#hotelChannelBookingForm').length) {
        $('#hotelChannelBookingForm input[name=images]').val(json);
    }
}
Frontend.requestPayment = function () {
    Frontend.hideHightlightErrors();
    var data = $('form#paymentForm').serialize();
    $.ajax({
        url: baseUrl + 'bookings/requestPayment',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                if (response.is_onepay) {
                    window.location.href = response.redirect_link;
                } else {
                    window.location.href = baseUrl + '/thanh-toan/success';
                }
            } else {
                Frontend._parseErrors('#paymentForm', response.errors);
            }
        }
    });
}
Frontend.requestVinPayment = function () {
    Frontend.hideHightlightErrors();
    var data = $('form#hotelVinBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'bookings/requestVinPayment',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                if (response.is_onepay) {
                    window.location.href = response.redirect_link;
                } else {
                    window.location.href = baseUrl + '/thanh-toan-vinpearl/success/' + response.booking_code;
                }
            } else {
                console.log(response);
                Frontend._parseErrors('#hotelVinBookingForm', response.errors);
            }
        }
    });
}
Frontend.requestChannelPayment = function () {
    Frontend.hideHightlightErrors();
    var data = $('form#hotelChannelBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'bookings/requestChannelPayment',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                if (response.is_onepay) {
                    window.location.href = response.redirect_link;
                } else {
                    window.location.href = baseUrl + '/thanh-toan-channel/success/' + response.booking_code;
                }
            } else {
                console.log(response);
                Frontend._parseErrors('#hotelVinBookingForm', response.errors);
            }
        }
    });
}
Frontend.calBookingHomeStayPrice = function () {
    var data = $("form#homeStayBookingForm").serialize();
    $.ajax({
        url: baseUrl + 'HomeStays/getHomestayPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#booking_result').empty();
            $('#booking_result').append(res.result);
            $('#total_booking_price').empty();
            $('#total_booking_price').append(res.price);
            $('#total_booking_profit').empty();
            $('#total_booking_profit').append(res.profit);
        },
        error: function () {
        }
    });
};

Frontend.calVoucherTotalPrice = function () {
    var data = $("form#voucherBookingForm").serialize();
    $.ajax({
        url: baseUrl + 'vouchers/getVoucherPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#booking_result').empty();
            $('#booking_result').append(res.result);
            $('#total_booking_price').empty();
            $('#total_booking_price').append(res.price);
            $('#total_booking_profit').empty();
            $('#total_booking_profit').append(res.profit);
        },
        error: function () {
        }
    });
}

Frontend.calLandTourTotalPrice = function () {
    var data = $('#landTourBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'land-tours/getLandtourPrice',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#booking_result').empty();
            $('#booking_result').append(res.result);
            $('#total_booking_price').empty();
            $('#total_booking_price').append(res.price);
            $('#total_booking_profit').empty();
            $('#total_booking_profit').append(res.profit);
            $('#total_booking_child_surcharge').empty();
            $('#total_booking_child_surcharge').append(res.child_surcharge);
        },
        error: function () {
        }
    });
};

Frontend.calLandtourDriveSurcharge = function () {
    var pickup_id = $('select[name=pickup_id]').val();
    var drop_id = $('select[name=drop_id]').val();
    var num_adult = $('select[name=num_adult]').val();
    var land_tour_id = $('input[name=land_tour_id]').val();
    $.ajax({
        url: baseUrl + 'land-tours/calLandtourDriveSurcharge',
        data: {
            pickup_id: pickup_id,
            drop_id: drop_id,
            land_tour_id: land_tour_id,
            num_adult: num_adult
        },
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#total_booking_drive_surcharge').html(res.drive_surcharge);
            }
        },
        error: function () {
        }
    });
}

Frontend.calculateTotalBookingRoom = function (selector) {
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
    Frontend.parseVinRoomForm(selector);
}

Frontend.parseVinRoomForm = function (selector) {
    console.log(selector);
    let adultEle = $(selector).find('.num-room-adult');
    let html = '';
    adultEle.each(function (index) {
        html += '<input type="hidden" name="vin_room[' + index + '][num_adult]" value="' + parseInt($(this).text()) + '">';
    });

    let childEle = $(selector).find('.num-room-child');
    childEle.each(function (index) {
        html += '<input type="hidden" name="vin_room[' + index + '][num_child]" value="' + parseInt($(this).text()) + '">';
    });

    let kidEle = $(selector).find('.num-room-kid');
    kidEle.each(function (index) {
        html += '<input type="hidden" name="vin_room[' + index + '][num_kid]" value="' + parseInt($(this).text()) + '">';
    });
    $(selector).find('#list-input-room-data').empty();
    $(selector).find('#list-input-room-data').append(html);
}
//

Frontend.addVinInformation = function () {
    let html = '<div class="single-vin-information">\n' +
        '                                    <div class="col-sm-8 mt10">\n' +
        '                                        <p class="fs16 pull-right mt05">Thành viên <span class="vin-infor-index"></span>:</p>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-sm-11 mt10">\n' +
        '                                        <input type="text" class="form-control popup-voucher" name="vin_information[0][name]" placeholder="Họ và tên">\n' +
        '                                    </div>\n' +
        '                                    <div class="col-sm-11 mt10">\n' +
        '                                        <input type=\'text\' readonly="readonly" name="vin_information[0][birthday]" class="form-control popup-voucher date datepicker" placeholder="Ngày sinh"/>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-sm-6 mt10">\n' +
        '                                        <button type="button" class="btn pull-right delete-vin-infor btn-delete"><i class="fas fa-trash"></i></button>\n' +
        '                                    </div>\n' +
        '                                </div>';
    $('.list-vin-information').append(html);
    $('.datepicker').datetimepicker({
        format: 'DD-MM-YYYY',
        ignoreReadonly: true
    });
    Frontend.updateIndexInput('.list-vin-information', 'single-vin-information');
    Frontend.updateIndexVinInfor();
}

Frontend.updateIndexVinInfor = function () {
    $('.vin-infor-index').each(function (index) {
        // console.log(index);
        $(this).text(index + 1);
    });
}

Frontend.changeVinPaymentType = function (type) {
    console.log(type);
}

Frontend.checkDetailVinRoom = function (room_id) {
    let data = $('input[name=room-' + room_id + ']').val();
    roomData = JSON.parse(data);
    $('.room-name').text(roomData.name);
    $('.room-information').text(roomData.squareUnit + " " + roomData.squareUnitType + " | " + roomData.maxAdult + " người lớn, " + roomData.maxChild + " em bé");
    $('.room-description').text(roomData.description);
    $('#room-img').attr('src', baseUrl + roomData.image);
    $('.list-room-accessories').empty();
    $.each(roomData.extends, function (index, extend) {
        let html = '<div class="col-sm-12 mt10">\n' +
            '                                <div class="row">\n' +
            '                                    <div class="col-sm-6 no-pad-right">\n' +
            '                                        <img class="w100" src="' + baseUrl + extend.image + '" alt="">\n' +
            '                                    </div>\n' +
            '                                    <div class="col-sm-30">\n' +
            '                                        <p class="mt05">' + extend.content + '</p>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>';
        $('.list-room-accessories').append(html);
    });
}
Frontend.checkDectailVinPackage = function (packageInputName) {
    let data = $('input[name=' + packageInputName + ']').val();
    packageData = JSON.parse(data);
    console.log(data);
    $('.package-name').text(packageData.name);
    $('.package-code').text(packageData.code);
    $('.guarantee-policy').text(packageData.guaranteePolicy);
    $('.cancel-policy').text(packageData.cancelPolicy);
}
Frontend.parseVinName = function (text) {
    $('input[name=keyword]').val(text);
    var container_s_vin = $('.popup-search-vin');
    container_s_vin.hide();
}
Frontend.parseNormalName = function (text, selector) {
    $('input[name=keyword]').val(text);
    var container_s_vin = $('.popup-search-vin');
    container_s_vin.hide();
}

Frontend.chooseVinRoom = function (id, room_id, price, package_id, rateplan_id, revenue, saleRevenue, packageCode, packageName, allotmentId, roomTypeCode, ratePlanCode, defaultPrice) {
    let data = $('input[name=choose-room-' + room_id + ']').val();
    console.log(data);
    roomData = JSON.parse(data);
    $('#vin-room-' + id).empty();
    $('#vin-room-' + id).attr('data-vinroom-price', price);
    $('#vin-room-' + id).attr('data-vinroom-revenue', revenue);
    let html = '<div class="col-sm-20 mt10">\n' +
        '                                                                    <p class="fs14">Phòng ' + (parseInt(id) + 1) + ': ' + roomData.name + '</p>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="col-sm-16 mt10">\n' +
        '                                                                    <p class="pull-right fs14">' + price + ' VNĐ</p>\n' +
        '                                                                </div>' +
        '<div class="col-sm-36">\n' +
        '                                                                    <p class="fs14">Gói: ' + packageCode + '</p>\n' +
        '</div>';
    $('#vin-room-' + id).append(html);
    let input = '<input type="hidden" name="vin_room[' + id + '][id]" value="' + room_id + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][name]" value="' + roomData.name + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][code]" value="' + packageCode + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][package_name]" value="' + packageName + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][allotment_id]" value="' + allotmentId + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][price]" value="' + price + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][default_price]" value="' + defaultPrice + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][package_id]" value="' + package_id + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][rateplan_id]" value="' + rateplan_id + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][room_type_code]" value="' + roomTypeCode + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][rateplan_code]" value="' + ratePlanCode + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][revenue]" value="' + revenue + '">';
    input += '<input type="hidden" name="vin_room[' + id + '][sale_revenue]" value="' + saleRevenue + '">';
    $('.single-booking-vin-room-' + id).empty();
    $('.single-booking-vin-room-' + id).append(input);
    $('.single-booking-vin-room-' + id).removeClass('have-data');
    $('.single-booking-vin-room-' + id).addClass('have-data');
    let totalPrice = 0;
    let totalRevenue = 0;
    let totalPay = 0;
    $('.single-room-detail').each(function (index, item) {
        let str = $(item).attr('data-vinroom-price');
        let strRevenue = $(item).attr('data-vinroom-revenue');
        totalPrice += parseInt(str.replaceAll(',', ''));
        totalRevenue += parseInt(strRevenue.replaceAll(',', ''));
        totalPay = totalPrice - totalRevenue;
    });
    totalPrice = new Intl.NumberFormat('de-DE').format(totalPrice);
    totalRevenue = new Intl.NumberFormat('de-DE').format(totalRevenue);
    totalPay = new Intl.NumberFormat('de-DE').format(totalPay);

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
}
Frontend.chooseChannelRoom = function (id, room_id, price, package_id, rateplan_id, revenue, saleRevenue, packageCode, packageName, allotmentId, roomTypeCode, ratePlanCode, defaultPrice,currency,dateRange) {
    let data = $('input[name=choose-room-' + room_id + ']').val();
    roomData = JSON.parse(data);
    $('#channel-room-' + id).empty();
    $('#channel-room-' + id).attr('data-channelroom-price', price);
    $('#channel-room-' + id).attr('data-channelroom-revenue', revenue);
    let html = '<div class="col-sm-20 mt10">\n' +
        '                                                                    <p class="fs14">Phòng ' + (parseInt(id) + 1) + ': ' + roomData.name + '</p>\n' +
        '                                                                </div>\n' +
        '                                                                <div class="col-sm-16 mt10">\n' +
        '                                                                    <p class="pull-right fs14">' + price + ' VNĐ</p>\n' +
        '                                                                </div>' +
        '<div class="col-sm-36">\n' +
        '                                                                    <p class="fs14">Gói: ' + packageCode + '</p>\n' +
        '</div>';
    $('#channel-room-' + id).append(html);
    let input = '<input type="hidden" name="channel_room[' + id + '][id]" value="' + room_id + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][name]" value="' + roomData.name + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][code]" value="' + packageCode + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][package_name]" value="' + packageName + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][allotment_id]" value="' + allotmentId + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][price]" value="' + price + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][currency]" value="' + currency + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][default_price]" value="' + defaultPrice + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][package_id]" value="' + package_id + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][rateplan_id]" value="' + rateplan_id + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][room_type_code]" value="' + roomTypeCode + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][rateplan_code]" value="' + ratePlanCode + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][revenue]" value="' + revenue + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][sale_revenue]" value="' + saleRevenue + '">';
    input += '<input type="hidden" name="channel_room[' + id + '][date_range]" value='+"'" + dateRange +"'" + '>';
    $('.single-booking-channel-room-' + id).empty();
    $('.single-booking-channel-room-' + id).append(input);
    $('.single-booking-channel-room-' + id).removeClass('have-data');
    $('.single-booking-channel-room-' + id).addClass('have-data');
    let totalPrice = 0;
    let totalRevenue = 0;
    let totalPay = 0;
    $('.single-room-detail').each(function (index, item) {
        let str = $(item).attr('data-channelroom-price');
        let strRevenue = $(item).attr('data-channelroom-revenue');
        totalPrice += parseInt(str.replaceAll(',', ''));
        totalRevenue += parseInt(strRevenue.replaceAll(',', ''));
        totalPay = totalPrice - totalRevenue;
    });


    totalPrice = new Intl.NumberFormat('de-DE').format(totalPrice);
    totalRevenue = new Intl.NumberFormat('de-DE').format(totalRevenue);
    totalPay = new Intl.NumberFormat('de-DE').format(totalPay);

    $('span#totalChannelBookingPrice').empty();
    $('span#totalChannelBookingPrice').text(totalPrice);

    // if ($('#totalChannelBookingRevenue').length) {
    //     $('span#totalChannelBookingRevenue').empty();
    //     $('span#totalChannelBookingRevenue').text(totalRevenue);
    //
    // }
    // if ($('#totalAgencyPayChannelBooking').length) {
    //     $('span#totalAgencyPayChannelBooking').empty();
    //     $('span#totalAgencyPayChannelBooking').text(totalPay);
    // }
}
Frontend.submitVinBooking = function () {
    let data = $('form#hotelVinBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'bookings/addBookingVin',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            // $('#requestLandTourBooking').prop("disabled", false);
            if (res.success) {
                window.location.href = baseUrl + '/thanh-toan-vinpearl/' + res.booking_code;
            } else {
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#error_email").offset().top - 250
                }, 500);
                if (res.errors.email) {
                    $('#error_email').show().text(res.errors.email);
                }
                if (res.errors.first_name) {
                    $('#error_first_name').show().text(res.errors.first_name);
                }
                if (res.errors.sur_name) {
                    $('#error_sur_name').show().text(res.errors.sur_name);
                }
                if (res.errors.phone) {
                    $('#error_phone').show().text(res.errors.phone);
                }
            }
        },
        error: function () {
        }
    });
}
Frontend.submitChannelBooking = function () {
    let data = $('form#hotelChannelBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'bookings/addBookingChannel',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            // $('#requestLandTourBooking').prop("disabled", false);
            if (res.success) {
                window.location.href = baseUrl + '/thanh-toan-channel/' + res.booking_code;
            } else {
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#error_email").offset().top - 250
                }, 500);
                if (res.errors.email) {
                    $('#error_email').show().text(res.errors.email);
                }
                if (res.errors.first_name) {
                    $('#error_first_name').show().text(res.errors.first_name);
                }
                if (res.errors.sur_name) {
                    $('#error_sur_name').show().text(res.errors.sur_name);
                }
                if (res.errors.phone) {
                    $('#error_phone').show().text(res.errors.phone);
                }
            }
        },
        error: function () {
        }
    });
}
Frontend.sortVinHotel = function (type) {
    let elements = [];
    $("div[id*=vin-room]").sort(function (a, b) {
        if (type == "asc" ? a.id < b.id : a.id > b.id) {
            return -1;
        } else {
            return 1;
        }
    }).each(function () {
        elements.push($(this));
    });
    $('.list-vin-hotel').empty();
    $('.list-vin-hotel').append(elements)
}
Frontend.hiddenRoomPrice = function (e) {
    var $myGroup = $('.tab-content');
    $myGroup.on('show.bs.collapse', '.collapse', function () {
        $myGroup.find('.collapse.in').collapse('hide');
    });
    let divId = $(e).data('target');
    $('.vin-price-room').show();
    $(divId + '-price').hide();
    $(divId).on('hidden.bs.collapse', function () {
        $(divId + '-price').show();
    });
}
Frontend.saleShowData = function () {
    let data = $('#saleBookingForm').serialize();
    $.ajax({
        url: baseUrl + 'bookings/saleAddBookingVin',
        data: data,
        type: 'post',
        dataType: 'html',
        success: function (res) {
            $('#body-sale-booking-vin').empty();
            $('#body-sale-booking-vin').append(res);
            $('input.iCheck').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'icheckbox_flat-blue'
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
                let allotmentId = $(this).data('allotment-id');
                let defaultPrice = $(this).data('package-default-price');
                console.log(allotmentId)
                Frontend.chooseVinRoom(roomIndex, roomKey, packagePrice, packageId, rateplanId, revenue, saleRevenue, packageCode, allotmentId);
            });
        },
        error: function () {
        }
    });
}

Frontend.searchForVinPackage = function (selector) {
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
        url: baseUrl + 'hotels/searchForVinPackage',
        data: {
            room_id: room_id,
            room_index: room_index,
            hotel_id: hotel_id,
            start_date: start_date,
            end_date: end_date,
            num_adult: num_adult,
            num_child: num_child,
            num_kid: num_kid,
        },
        type: 'post',
        dataType: 'html',
        success: function (res) {
            selector.find('.fa-spinner').addClass('hidden');
            $('#list-vin-package').empty();
            $('#list-vin-package').append(res);
            $('input.iCheck').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'icheckbox_flat-blue'
            });
            $('input.iCheck.vin-room-search-pick').on('ifChecked', function (event) {
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
        }
    });
}

Frontend.addVinSearchPackage = function (e) {
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
    $.ajax({
        url: baseUrl + 'hotels/addSearchPackageVin',
        data: {
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
        },
        type: 'post',
        dataType: 'json',
        success: function (res) {
            $('.list-package-room-' + roomIndex).append(res.html_package);
            $('.total-vin-room-' + roomIndex).text(res.room_total);
            $('#totalVinBookingPrice').text(res.total_vin_booking_price);
            $('#totalVinBookingRevenue').text(res.total_vin_booking_revenue);
            $('#totalAgencyPayVinBooking').text(res.total_agency_pay_vin_booking);
            $('#modalAddNewPackage').modal('hide');

            $('.single-booking-vin-room-' + roomIndex).empty();
            let html = '';
            $.each($('.list-package-room-' + roomIndex + ' .single-room-package'), function (index, item) {
                html += '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][room_index]" value="' + $(item).attr('data-room-index') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][room_key]" value="' + $(item).attr('data-room-key') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_pice]" value="' + $(item).attr('data-package-pice') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_id]" value="' + $(item).attr('data-package-id') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][rateplan_id]" value="' + $(item).attr('data-rateplan-id') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][allotment_id]" value="' + $(item).attr('data-allotment-id') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][room_type_code]" value="' + $(item).attr('data-room-type-code') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][rateplan_code]" value="' + $(item).attr('data-rate-plan-code') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][revenue]" value="' + $(item).attr('data-revenue') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][sale_revenue]" value="' + $(item).attr('data-sale-revenue') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_code]" value="' + $(item).attr('data-package-code') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_name]" value="' + $(item).attr('data-package-name') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][default_price]" value="' + $(item).attr('data-package-default-price') + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][start_date]" value="' + $(item).find('input.start-date-vin').val() + '">\n' +
                    '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][end_date]" value="' + $(item).find('input.end-date-vin').val() + '">';
            });
            $('.single-booking-vin-room-' + roomIndex).append(html);
            if ($('.list-package-room-' + roomIndex + ' .single-room-package').length > 1) {
                $('.list-package-room-' + roomIndex).closest('.booking-room-item').find('p.remove-package').removeClass('hidden')
            }
        },
        error: function () {

        }
    });
}

Frontend.removeVinroomPackage = function (selector) {
    let roomIndex = selector.attr('data-room-index');
    let removePackage = $('.list-package-room-' + roomIndex).children().last();
    let package_price = removePackage.attr('data-package-pice');
    let revenue = removePackage.attr('data-revenue');
    let sale_revenue = removePackage.attr('data-sale-revenue');
    let default_package_price = removePackage.attr('data-package-default-price');
    let total_vin_room = $('span.total-vin-room-' + roomIndex).text();
    let total_booking_price = $('span#totalVinBookingPrice').text();
    let total_booking_revenue = $('span#totalVinBookingRevenue').text();
    let total_agency_pay = $('span#totalAgencyPayVinBooking').text();
    $.ajax({
        url: baseUrl + 'hotels/removeVinroomPackage',
        data: {
            package_price: package_price,
            revenue: revenue,
            sale_revenue: sale_revenue,
            default_package_price: default_package_price,
            total_vin_room: total_vin_room,
            total_booking_price: total_booking_price,
            total_booking_revenue: total_booking_revenue,
            total_agency_pay: total_agency_pay,
        },
        type: 'post',
        dataType: 'json',
        success: function (res) {
            if ($('.list-package-room-' + roomIndex + ' .single-room-package').length > 1) {
                $('.list-package-room-' + roomIndex).children().last().remove();
                $('.single-booking-vin-room-' + roomIndex).empty();
                let html = '';
                $.each($('.list-package-room-' + roomIndex + ' .single-room-package'), function (index, item) {
                    html += '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][room_index]" value="' + $(item).attr('data-room-index') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][room_key]" value="' + $(item).attr('data-room-key') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_pice]" value="' + $(item).attr('data-package-pice') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_id]" value="' + $(item).attr('data-package-id') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][rateplan_id]" value="' + $(item).attr('data-rateplan-id') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][allotment_id]" value="' + $(item).attr('data-allotment-id') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][room_type_code]" value="' + $(item).attr('data-room-type-code') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][rateplan_code]" value="' + $(item).attr('data-rate-plan-code') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][revenue]" value="' + $(item).attr('data-revenue') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][sale_revenue]" value="' + $(item).attr('data-sale-revenue') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_code]" value="' + $(item).attr('data-package-code') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][package_name]" value="' + $(item).attr('data-package-name') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][default_price]" value="' + $(item).attr('data-package-default-price') + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][start_date]" value="' + $(item).find('input.start-date-vin').val() + '">\n' +
                        '<input type="hidden" name="vin_room[' + roomIndex + '][package][' + index + '][end_date]" value="' + $(item).find('input.end-date-vin').val() + '">';
                });
                $('.single-booking-vin-room-' + roomIndex).append(html);

                if ($('.list-package-room-' + roomIndex + ' .single-room-package').length == 1) {
                    $('.list-package-room-' + roomIndex).closest('.booking-room-item').find('p.remove-package').addClass('hidden')
                }

                $('span.total-vin-room-' + roomIndex).text(res.total_vin_room);
                $('span#totalVinBookingPrice').text(res.total_booking_price);
                $('span#totalVinBookingRevenue').text(res.total_booking_revenue);
                $('span#totalAgencyPayVinBooking').text(res.total_agency_pay);
                selector.prop('disabled', false);
            }
        },
        error: function () {
            selector.prop('disabled', false);
        }
    });
}
/*-----------------  End Booking JS  -----------------------*/
/*-----------------  recharge  -----------------------*/
Frontend.recharge = function () {
    var data = $('form#recharge').serialize();
    var image = $('.dz-image-preview').attr('data-path');
    data = data + '&image=' + image;
    console.log('data', data);
    $.ajax({
        type: "post",
        url: baseUrl + '/recharge',
        data: data,
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response.success) {
                console.log('reload');
                window.location.href = baseUrl + '/lich-su-nap-tien/';
            } else {
                console.log('faile');
                Frontend._parseErrors('#recharge', response.errors);
            }
        },
    });
}

$(function () {
    $('a.thumbnail').click(function (e) {
        e.preventDefault();
        $('#image-modal .modal-body img').attr('src', $(this).find('img').attr('src'));
        $("#image-modal").modal('show');
    });
    $('#image-modal .modal-body img').on('click', function () {
        $("#image-modal").modal('hide')
    });
});


/*-----------------  end recharge  -----------------------*/
Frontend.sendFirebaseMessage = function () {
    var data = new FormData();
    let text_message = $('input[name=text-message]').val();
    data.append("message", text_message);
    var file_data = $('input[name="image-message"]')[0].files;
    for (var i = 0; i < file_data.length; i++) {
        data.append("images", file_data[i]);
    }
    if (text_message != '' || Object.entries(file_data).length > 0) {
        var newChatEle = []
        if (Object.entries(file_data).length > 0) {
            newChatEle = $('<div class="col-sm-36 newMessage">\n' +
                '                <div class="message-guest opacity-custom">\n' +
                '                     <p>' + file_data[0]['name'] + '\n' +
                '                 </div>\n' +
                '              </div>');
        } else {
            newChatEle = $('<div class="col-sm-36">\n' +
                '                <div class="message-guest opacity-custom">\n' +
                '                     <p>' + text_message + '\n' +
                '                 </div>\n' +
                '            </div>');
        }
        $('.content-message .row').append(newChatEle);
        $('input[name=text-message]').val('');
        $('input[name=image-message]').val('');
        $('.content-message').scrollTop($('.content-message')[0].scrollHeight);
        $.ajax({
            type: "post",
            url: baseUrl + '/chat/sendFirebaseMessage',
            processData: false,
            contentType: false,
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // console.log(1)
                    // document.getElementById('text-message').removeAttribute('readonly');
                    // $('.body-content').scrollTop($('.body-content')[0].scrollHeight);
                    // $('.newMessage').empty();
                    // $('.newMessage').removeClass('newMessage');
                }
            }
        });
    }
}

Frontend.checkImg = function () {
    var file_data = $('input[name="image-message"]')[0].files;
    if (Object.entries(file_data).length > 0) {
        document.getElementById('text-message').value = file_data[0]['name'];
    }
}

Frontend.updateStatusReadMessage = function (id, roomId) {
    $.ajax({
        type: "post",
        url: baseUrl + '/chat/changeStatusMess',
        data: {
            id: id,
            room_id: roomId
        },
        dataType: 'json',
        success: function (response) {
        },
    });
}

