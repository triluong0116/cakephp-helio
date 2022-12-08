/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




var eventReset = function () {
    body.css('background-color', 'white');
    $('#winner').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
};
var eventPick = function (data) {
    $('#winner').modal('show');
    $('#winning_avatar').attr('src', data.winning_avatar);
    if (!is_admin) {
        if (data.winner) {
            body.css('background-color', 'green');
            $('#win_message').html('YOU WIN!');
        } else {
            body.css('background-color', 'red');
            $('#win_message').html("Sorry you weren't picked.");
        }
    }
};
var eventShowModalToAgency = function (data) {
    // $("#agencyChoosing button.btn-submit").data("finger-print", data.fingerPrint);
    // $("#agencyChoosing button.btn-submit").data("timestamp", data.timestamp);
    $("#agencyChoosing button#agencyAcceptedRequest").attr('data-finger-print', data.fingerPrint);
    $("#agencyChoosing button#agencyAcceptedRequest").attr('data-timestamp', data.timestamp);
    if (data.haveRef) {
        if (data.priority) {
            $('#agencyChoosing').modal('show');
        } else {
            setTimeout(function () {
                $('#agencyChoosing').modal('show');
            }, 20000);
        }
    } else {
        $('#agencyChoosing').modal('show');
    }
};
var eventAccept = function (data) {
    var modal = $('#foundAgency');
    var avatar = '';

    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if (regexp.test(data.avatar)) {
        avatar = data.avatar;
    } else {
        avatar = baseUrl + data.avatar;
    }

    modal.find('img#foundedAgencyAvatar').attr('src', avatar);
    modal.find('a#foundedAgencyName b').text(data.screen_name);
    modal.find('a#foundedAgencyFB').attr('href', data.fbid);
    modal.find('a#foundedAgencyZalo').attr('href', 'http://zalo.me/' + data.zalo);
    modal.find('a#foundedAgencyPhone').attr('href', 'tel:' + data.phone);
    $('#findAgencyLoading').modal('hide');
    modal.modal('show');
}
var eventConnect = function (data) {
//        console.log(data.clients);
//        var html = '';
//        var num_users = 0;
//        for (key in data.clients) {
//            if (!data.clients.hasOwnProperty(key))
//                continue;
//            if (data.clients[key].is_admin == false) {
//                num_users++;
//                html += "<img src='" + data.clients[key].avatar + "' />";
//            }
//        }
//        $('#number').html(num_users);
};

var sendMsg = function (obj) {
    websocket.send(JSON.stringify(obj));
};
$('#pick').click(function () {
    sendMsg({event: 'pick'});
});
$('#reset').click(function () {
    sendMsg({event: 'reset'});
});