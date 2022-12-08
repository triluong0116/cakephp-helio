<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


define('FB_APP_ID', '328968574492385');
define('FB_APP_SECRET', '137822d8f2fd532cdb934fc0781304ed');

/** config your app id here */
define('ZALO_APP_ID_CFG', '1003026734674591233');

/** config your app secret key here */
define('ZALO_APP_SECRET_KEY_CFG', 'JuNWS57S0dNWO81qe4IX');

/** config your offical account id here */
//define('ZALO_OA_ID_CFG', '758559221143286');
//
///** config your offical account secret key here */
//define('ZALO_OA_SECRET_KEY_CFG', '758559221143286');

define('SHARE_FB', 'https://www.facebook.com/sharer/sharer.php?u=');

define('MAP_API', 'AIzaSyBorjwWiz72E5_mmVkW2OOGZ_VLxFCWzRc');

define('LOCATIONIQ_TOKEN', 'ec5284d930ecd7');
define('LOCATIONIQ_ACCESS_TOKEN', 'pk.661ff19e25332288646a4a49fd104410');

define('COMBO', 1);
define('VOUCHER', 2);
define('LANDTOUR', 3);
define('HOTEL', 4);
define('HOMESTAY', 5);
define('LOCATION', 6);
define('VINPEARL', 7);
define('CHANNEL', 8);

define('WEEK_MON', 'Monday');
define('WEEK_TUE', 'Tuesday');
define('WEEK_WED', 'Wednesday');
define('WEEK_THU', 'Thursday');
define('WEEK_FRI', 'Friday');
define('WEEK_SAT', 'Saturday');
define('WEEK_SUN', 'Sunday');

define('FACEBOOK_POST_TYPE', 11);
define('ZALO_POST_TYPE', 12);

define('E_PAY_AGENCY', 21);
define('E_BOOK_HOTEL', 22);
define('E_BOOK_AGENCY', 23);
define('E_PAY_OBJECT', 24);

define('P_REG_CONNECT', 31);
define('P_BOOK_SHARE', 32);
define('P_BOOK_SHARE_HOTEL', 33);
define('P_BOOK_SHARE_LOCATION', 34);

define('WEEK_DAY', 41);
define('WEEK_END', 42);
define('HOLIDAY', 43);

define('APARTMENT', 51);
define('VILLA', 52);
define('HOME', 53);
define('BUNGALOW', 54);
define('SINGLE_ROOM', 55);
define('WHOLE_HOUSE', 56);

define('HOTEL_REPORT', 60);
define('AGENCY_REPORT', 61);
define('PROFIT_REPORT', 62);
define('PROFIT_REPORT_LANDTOUR', 63);

define('CUSTOMER_PAY', 0);
define('AGENCY_PAY', 1);
define('MUSTGO_DEPOSIT', 2);

define('SYSTEM_BOOKING', 1);
define('ANOTHER_BOOKING', 2);

define('SIZE_BYTE', 200000);

define('SUR_WEEKEND', 1);
define('SUR_HOLIDAY', 2);
define('SUR_ADULT', 3);
define('SUR_CHILDREN', 4);
define('SUR_BONUS_BED', 5);
define('SUR_BREAKFAST', 6);
define('SUR_CHECKIN_SOON', 7);
define('SUR_CHECKOUT_LATE', 8);
define('SUR_OTHER', 9);

define('PAYMENT_TRANSFER', 1);
define('PAYMENT_OFFICE', 2);
define('PAYMENT_HOME', 3);
define('PAYMENT_ONEPAY_CREDIT', 4);
define('PAYMENT_ONEPAY_ATM', 5);
define('PAYMENT_ONEPAY_QR', 6);
define('PAYMENT_BALANCE', 7);

define('PAY_HOTEL', 1);
define('PAY_PARTNER', 2);

define('NO_CHECK', 1);
define('HAVE_CHECK', 2);

//Special request
define('BOOKER_INSTRUCTION', 'BookerInstruction');
define('SPECIAL_REQUEST', 'SpecialRequest');
define('GUEST_INSTRUCTION', 'GuestInstruction');
define('ROOM_FEATURE', 'RoomFeature');

//other occupany
define('CHILD', 'child');
define('INFANT', 'infant');

//profile
define('TRAVEL_AGENT', 'TravelAgent');
define('GUEST', 'Guest');
define('ACCOMPANY_GUEST', 'AccompanyGuest');
define('BOOKER', 'Booker');

define('ACCESSCODE_INVOICE', 'E0605B50');
define('MERCHANT_ID_INVOICE', 'OP_MUSTGO');
define('ONEPAY_USER_INVOICE', 'op01');
define('ONEPAY_PASSWORD_INVOICE', 'op123456');
define('HASHCODE_INVOICE', 'BE8A2E9373B3FE5ACD2C1784CF7EA4C3');

define('ACCESSCODE_NO_INVOICE', 'E0605B05');
define('MERCHANT_ID_NO_INVOICE', 'OP_MUSTGO2');
define('ONEPAY_USER_NO_INVOICE', 'op01');
define('ONEPAY_PASSWORD_NO_INVOICE', 'op123456');
define('HASHCODE_NO_INVOICE', '36CC8BA37397EA512BDFF97AD8EFC8B6');

//test
//define('ACCESSCODE_INVOICE', '6BEB2546');
//define('MERCHANT_ID_INVOICE', 'TESTONEPAY');
//define('ONEPAY_USER_INVOICE', 'op01');
//define('ONEPAY_PASSWORD_INVOICE', 'op123456');
//define('HASHCODE_INVOICE', '6D0870CDE5F24F34F3915FB0045120DB');
//
//define('ACCESSCODE_NO_INVOICE', '6BEB2546');
//define('MERCHANT_ID_NO_INVOICE', 'TESTONEPAY');
//define('ONEPAY_USER_NO_INVOICE', 'op01');
//define('ONEPAY_PASSWORD_NO_INVOICE', 'op123456');
//define('HASHCODE_NO_INVOICE', '6D0870CDE5F24F34F3915FB0045120DB');

define('RO', 'Phòng không ăn sáng');
define('BB', 'Phòng + ăn sáng');
define('BX', 'Phòng + ăn sáng + vui chơi không giới hạn tại Vinwonder và Safari');
define('FB', 'Phòng + ăn 03 bữa (Tối-sáng-trưa)');
define('FX', 'Phòng + ăn 03 bữa (Tối-sáng-trưa) + vui chơi không giới hạn tại Vinwonder và Safari');
define('FBSFD', 'Phòng + ăn 03 bữa, hải sản tối + vui chơi không giới hạn tại Vinwonder và Safari');
define('FBBQD', 'Phòng + ăn 03 bữa, BBQ tối');
define('FXBQD', 'Phòng + ăn 03 bữa, BBQ tối+ vui chơi không giới hạn tại Vinwonder và Safari');
define('BG', 'Phòng + 1 vòng golf 18 lỗ (bao gồm phí sân cỏ + xe điện)');
define('BV', 'Phòng + 1 vòng golf 18 lỗ (bao gồm phí sân cỏ + xe điện)');
define('FV', 'Phòng + ăn 03 bữa (Tối-sáng-trưa) + vui chơi không giới hạn tại Vinwonders');
define('FVSFD', 'Phòng + ăn 03 bữa, hải sản tối + vui chơi không giới hạn tại Vinwonders');
define('FVBQD', 'Phòng + ăn 03 bữa,  BBQ tối+ vui chơi không giới hạn tại  Vinwonders');

/////////////// API Constant /////////////////////

define('EXPO_API_URL', 'https://exp.host/--/api/v2/push/send');

define('API_GEN_CODE', 'mustgo-webservice');
define('API_GEN_CODE_LOGIN', 'mustgo-webservice-login');
define('API_NAME', 'MUSTGO');
define('API_NAME_V2', 'GOATSM');


define('EXPIRE_CLASS', 'ExpiredException');
define('WRONG_SIGN_CLASS', 'SignatureInvalidException');

define('STT_EXPIRE', 2);
define('STT_INVALID', 3);
define('STT_NOT_LOGIN', 4);
define('STT_NOT_ALLOW', 5);
define('STT_NOT_VALIDATION', 6);
define('STT_NOT_SAVE', 7);
define('STT_SUCCESS', 1);
define('STT_ERROR', 0);
define('STT_NOT_FOUND', 8);
define('STT_EMPTY_NAME', 9);
define('STT_NOT_ENOUGH_BALANCE', 10);


define('CHAT_CLIENT_TO_AGENCY', 1);
define('CHAT_AGENCY_TO_CLIENT', 2);
