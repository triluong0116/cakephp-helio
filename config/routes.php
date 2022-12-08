<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use App\Middleware\CheckTokenMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);
Router::connect('/siteminder', ['controller' => 'testing', 'action' => 'testApi'], ['_name' => 'testing.testApi']);
Router::prefix('api', function ($routes) {
    $routes->registerMiddleware('token', new CheckTokenMiddleware());
    $routes->applyMiddleware('token');

    Router::prefix('V600',  ['path' => '/V600'], function ($routes) {
        $routes->setExtensions(['json', 'xml']);
        $routes->resources('Cocktails');
        $routes->resources('Users');
        $routes->resources('Combos');
        $routes->resources('Hotels');
        $routes->resources('Homestays');
        Router::connect('/home_stays/calPrice', ['controller' => 'Homestays', 'action' => 'calPrice']);
    });

    $routes->setExtensions(['json', 'xml']);
    $routes->resources('Cocktails');
    $routes->resources('Users');
    $routes->resources('Combos');
    $routes->resources('Hotels');
    $routes->resources('Homestays');
    Router::connect('/gen_token', ['controller' => 'Tokens', 'action' => 'gen_token']);
    Router::connect('/home_stays/calPrice', ['controller' => 'Homestays', 'action' => 'calPrice']);


    $routes->fallbacks('InflectedRoute');
});

Router::prefix('api/v400', function ($routes) {
    $routes->registerMiddleware('token', new CheckTokenMiddleware());
    $routes->applyMiddleware('token');

    $routes->setExtensions(['json', 'xml']);
    $routes->fallbacks('InflectedRoute');
});

Router::scope('/', function (RouteBuilder $routes) {
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware());
    $routes->applyMiddleware('csrf');
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'home'], ['_name' => 'home']);
    $routes->connect('/test-iframe', ['controller' => 'Pages', 'action' => 'testIframe'], ['_name' => 'iframe']);
    $routes->connect('/tat-ca-diem-den', ['controller' => 'Pages', 'action' => 'location'], ['_name' => 'location.all']);
    $routes->connect('/tim-kiem', ['controller' => 'Pages', 'action' => 'search'], ['_name' => 'search']);
    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/combo/:slug', ['controller' => 'Combos', 'action' => 'view'], ['_name' => 'combo.view']);
//    $routes->connect('/dia-diem', ['controller' => 'Locations', 'action' => 'index'], ['_name' => 'location.index']);
    $routes->connect('/dia-diem/:slug', ['controller' => 'Locations', 'action' => 'view'], ['_name' => 'location.view']);
    $routes->connect('/hot-voucher/:slug', ['controller' => 'Locations', 'action' => 'hotVoucher'], ['_name' => 'voucherLocation.view']);
    $routes->connect('/chinh-sach-cong-tac-vien', ['controller' => 'Blogs', 'action' => 'agencyP1']);
    $routes->connect('/chinh-sach-cong-tac-vien-page-2', ['controller' => 'Blogs', 'action' => 'agencyP2']);
    $routes->connect('/chinh-sach-cong-tac-vien-page-3', ['controller' => 'Blogs', 'action' => 'agencyP3']);
    $routes->connect('/chinh-sach-cong-tac-vien-page-4', ['controller' => 'Blogs', 'action' => 'agencyP4']);
    $routes->connect('/cam-nang', ['controller' => 'Reviews', 'action' => 'index']);
    $routes->connect('/data-board', ['controller' => 'Users', 'action' => 'dataBoard']);
    $routes->connect('/thong-tin-ca-nhan', ['controller' => 'Users', 'action' => 'edit']);
    $routes->connect('/cam-nang/:slug', ['controller' => 'Reviews', 'action' => 'view'], ['_name' => 'review.view']);
    $routes->connect('/dang-xuat', ['controller' => 'Users', 'action' => 'logout']);



    $routes->connect('/cam-nang/danh-muc/:slug', ['controller' => 'Reviews', 'action' => 'viewall'], ['_name' => 'review.viewall']);
    $routes->connect('/cam-nang/dia-diem/:slug', ['controller' => 'Reviews', 'action' => 'locationDetail'], ['_name' => 'review.location_detail']);
    $routes->connect('/cam-nang/dia-diem', ['controller' => 'Reviews', 'action' => 'location'], ['_name' => 'review.location']);

    $routes->connect('/hotdeal', ['controller' => 'Combos', 'action' => 'index']);
    $routes->connect('/cho-voucher', ['controller' => 'Vouchers', 'action' => 'index']);
    $routes->connect('/cho-voucher/:slug', ['controller' => 'Vouchers', 'action' => 'view'], ['_name' => 'voucher.view']);
    $routes->connect('/cho-voucher/:slug/booking/', ['controller' => 'Vouchers', 'action' => 'booking'], ['_name' => 'voucher.booking']);
    $routes->connect('/land-tour/dia-diem', ['controller' => 'LandTours', 'action' => 'location'], ['_name' => 'landtour.location']);
    $routes->connect('/land-tour/:slug', ['controller' => 'LandTours', 'action' => 'view'], ['_name' => 'landtour.view']);
    $routes->connect('/land-tour/:slug/booking/', ['controller' => 'LandTours', 'action' => 'booking'], ['_name' => 'landtour.booking']);
    $routes->connect('/khach-san/dia-diem', ['controller' => 'Hotels', 'action' => 'location'], ['_name' => 'hotel.location']);

    $routes->connect('/khach-san/:slug', ['controller' => 'Hotels', 'action' => 'view'], ['_name' => 'hotel.view']);
    $routes->connect('/khach-san/:slug/booking/', ['controller' => 'Hotels', 'action' => 'booking'], ['_name' => 'hotel.booking']);
    $routes->connect('/khach-san-vinpearl/vinpearl-booking/', ['controller' => 'Hotels', 'action' => 'vinpearlBooking'], ['_name' => 'hotel.vinpearlBooking']);

    $routes->connect('/khach-san-vinpearl/:slug', ['controller' => 'Hotels', 'action' => 'viewVinpearl'], ['_name' => 'hotel.viewVinpearl']);
    $routes->connect('/khach-san-vinpearl/:slug/chooseRoom', ['controller' => 'Hotels', 'action' => 'chooseRoomVinpearl'], ['_name' => 'hotel.chooseRoomVinpearl']);
    $routes->connect('/khach-san-vinpearl/:slug/booking/', ['controller' => 'Hotels', 'action' => 'bookingVinpearl'], ['_name' => 'hotel.bookingVinpearl']);
    $routes->connect('/khach-san-vinpearl/', ['controller' => 'Hotels', 'action' => 'listVinpearlHotels'], ['_name' => 'hotel.listVinpearlHotels']);
    $routes->connect('/vinpearl/', ['controller' => 'Hotels', 'action' => 'searchVinpearlHotels'], ['_name' => 'hotel.searchVinpearlHotels']);

    //khách sạn channel
    $routes->connect('/khach-san-channel/:slug', ['controller' => 'Hotels', 'action' => 'viewChannel'], ['_name' => 'hotel.viewChannel']);
    $routes->connect('/khach-san-channel/:slug/chooseRoom', ['controller' => 'Hotels', 'action' => 'chooseRoomChannel'], ['_name' => 'hotel.chooseRoomChannel']);
    $routes->connect('/khach-san-channel/:slug/booking/', ['controller' => 'Hotels', 'action' => 'bookingChannel'], ['_name' => 'hotel.bookingChannel']);
//    $routes->connect('/khach-san-vinpearl/', ['controller' => 'Hotels', 'action' => 'listVinpearlHotels'], ['_name' => 'hotel.listVinpearlHotels']);
    $routes->connect('/khach-san-channel/', ['controller' => 'Hotels', 'action' => 'listChannelHotels'], ['_name' => 'hotel.listChannelHotels']);
    $routes->connect('/thanh-toan-channel/:code', ['controller' => 'Bookings', 'action' => 'paymentChannel'], ['_name' => 'booking.paymentChannel']);
    $routes->connect('/thanh-toan-channel/success/:code', ['controller' => 'Bookings', 'action' => 'paymentChannelSuccess'], ['_name' => 'booking.paymentChannelSuccess']);


    //end khách sạn channel

    $routes->connect('/thanh-toan/:code', ['controller' => 'Bookings', 'action' => 'payment'], ['_name' => 'booking.payment']);
    $routes->connect('/thanh-toan/tinh-trang-thanh-toan/:code', ['controller' => 'Bookings', 'action' => 'paymentStatus'], ['_name' => 'booking.paymentStatus']);
    $routes->connect('/thanh-toan/success', ['controller' => 'Bookings', 'action' => 'paymentSuccess'], ['_name' => 'booking.payment_success']);
    $routes->connect('/dat-booking/success', ['controller' => 'Bookings', 'action' => 'bookingSuccess'], ['_name' => 'booking.success']);
    $routes->connect('/xem-lai-don-hang/:code', ['controller' => 'Bookings', 'action' => 'reviewPayment'], ['_name' => 'booking.reviewPayment']);
    $routes->connect('/xem-lai-don-hang-vin/:code', ['controller' => 'Bookings', 'action' => 'reviewVinPayment'], ['_name' => 'booking.reviewVinPayment']);
    $routes->connect('/sua-don-hang-landtour/:code', ['controller' => 'LandTours', 'action' => 'editBooking'], ['_name' => 'landtour.editBooking']);
    $routes->connect('/sua-don-hang-khach-san/:code', ['controller' => 'Hotels', 'action' => 'editBooking'], ['_name' => 'hotel.editBooking']);
    $routes->connect('/returnOnePaySuccess', ['controller' => 'Bookings', 'action' => 'returnOnePaySuccess'], ['_name' => 'booking.returnOnePaySuccess']);
    $routes->connect('/returnOnePayFail', ['controller' => 'Bookings', 'action' => 'returnOnePayFail'], ['_name' => 'booking.returnOnePayFail']);

    $routes->connect('/sale-dat-hang-vin', ['controller' => 'Bookings', 'action' => 'saleBookingVin'], ['_name' => 'booking.saleBookingVin']);
    $routes->connect('/sale-dat-hang-vin/:slug/booking', ['controller' => 'Bookings', 'action' => 'saleCreateBookingVin'], ['_name' => 'booking.saleCreateBookingVin']);

    $routes->connect('/thanh-toan-vinpearl/:code', ['controller' => 'Bookings', 'action' => 'paymentVinpearl'], ['_name' => 'booking.paymentVinpearl']);
    $routes->connect('/thanh-toan-vinpearl/success/:code', ['controller' => 'Bookings', 'action' => 'paymentVinpearlSuccess'], ['_name' => 'booking.paymentVinpearlSuccess']);
//    $routes->connect('/huy-don-hang/:code', ['controller' => 'Bookings', 'action' => 'denyBooking'], ['_name' => 'booking.denyBooking']);

    $routes->connect('/list-combo/:slug', ['controller' => 'Locations', 'action' => 'combo'], ['_name' => 'location.combo']);
    $routes->connect('/list-hotel/:slug', ['controller' => 'Locations', 'action' => 'hotel'], ['_name' => 'location.hotel']);
    $routes->connect('/list-landtour/:slug', ['controller' => 'Locations', 'action' => 'landtour'], ['_name' => 'location.landtour']);
    $routes->connect('/list-homestay/:slug', ['controller' => 'Locations', 'action' => 'homestay'], ['_name' => 'location.homestay']);

    $routes->connect('/homestay/', ['controller' => 'HomeStays', 'action' => 'index']);
    $routes->connect('/homestay/:slug', ['controller' => 'HomeStays', 'action' => 'view'], ['_name' => 'homestay.view']);
    $routes->connect('/homestay/:slug/booking/', ['controller' => 'HomeStays', 'action' => 'booking'], ['_name' => 'homestay.booking']);

//    $routes->connect('/review/:slug', ['controller' => 'Reviews', 'action' => 'food'], ['_name' => 'review.food']);
//    $routes->connect('/review/:slug', ['controller' => 'Reviews', 'action' => 'sight'], ['_name' => 'review.sight']);

    $routes->connect('/huong-dan-thanh-toan', ['controller' => 'Blogs', 'action' => 'paymentmethod']);
    $routes->connect('/chinh-sach-rieng-tu-bao-mat', ['controller' => 'Blogs', 'action' => 'secretpolicy']);
    $routes->connect('/dieu-khoan-su-dung', ['controller' => 'Blogs', 'action' => 'usemethod']);
    $routes->connect('/cau-hoi-thuong-gap', ['controller' => 'Blogs', 'action' => 'simplequestion']);
    $routes->connect('/chinh-sach-cam-ket-gia-tot-nhat', ['controller' => 'Blogs', 'action' => 'bestprice']);
    $routes->connect('/giai-quyet-tranh-chap', ['controller' => 'Blogs', 'action' => 'dispute']);

    $routes->connect('/noi-dung/:slug', ['controller' => 'Blogs', 'action' => 'view'], ['_name' => 'blog.view']);

    $routes->connect('/vemaybaygiare', ['controller' => 'Pages', 'action' => 'flightSearch'], ['_name' => 'flight.search']);
    $routes->connect('/vemaybaygiare2', ['controller' => 'Pages', 'action' => 'flightSearchSimple'], ['_name' => 'flight.search.simple']);
    $routes->connect('/xesanbaygiare', ['controller' => 'Pages', 'action' => 'carSearch'], ['_name' => 'car.search']);
    $routes->connect('/xesanbaygiare2', ['controller' => 'Pages', 'action' => 'carSearchSimple'], ['_name' => 'car.search.simple']);
    $routes->connect('/phongveMustgoFly', ['controller' => 'Pages', 'action' => 'flightSearchResult'], ['_name' => 'flight.search_result']);
    $routes->connect('/phongveMustgoFly2', ['controller' => 'Pages', 'action' => 'flightSearchResultSimple'], ['_name' => 'flight.search_result_simple']);
    $routes->connect('/nap-tien', ['controller' => 'Pages', 'action' => 'depositCash'], ['_name' => 'pages.depositCash']);
    $routes->connect('/recharge', ['controller' => 'Pages', 'action' => 'recharge'], ['_name' => 'pages.recharge']);
    $routes->connect('/lich-su-nap-tien', ['controller' => 'Pages', 'action' => 'listRecharge'], ['_name' => 'pages.listRecharge']);




    $routes->connect('/commit', ['controller' => 'Hotels', 'action' => 'commit'], ['_name' => 'commit.view']);

    $routes->connect('/recharge-agent', ['controller' => 'Users', 'action' => 'rechargeAgent']);
    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('admin', function ($routes) {
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware());
    $routes->applyMiddleware('csrf');
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->connect('/', ['controller' => 'Dashboards', 'action' => 'profitReport']);
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('sale', function ($routes) {
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware());
    $routes->applyMiddleware('csrf');
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->connect('/', ['controller' => 'Dashboards', 'action' => 'index']);
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('editor', function ($routes) {
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware());
    $routes->applyMiddleware('csrf');
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->connect('/', ['controller' => 'Dashboards', 'action' => 'index']);
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('manager', function ($routes) {
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware());
    $routes->applyMiddleware('csrf');
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->connect('/', ['controller' => 'Dashboards', 'action' => 'index']);
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('accountant', function ($routes) {
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware());
    $routes->applyMiddleware('csrf');
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->connect('/', ['controller' => 'Dashboards', 'action' => 'profitReport']);
    $routes->fallbacks(DashedRoute::class);
});
Router::extensions(['pdf']);
