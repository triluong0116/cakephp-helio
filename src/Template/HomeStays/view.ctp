<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $voucher
 */
?>
<!-- Header Combo -->
<div class="combo-header">
    <div class="container">
        <div class="breadcrumb">
            <!--            <ul class="breadcrumb-menu">
                            <li><a href="/">Trang chủ</a></li>
                            <li><a href="#">Đà Nẵng</a></li>
                            <li><a href="#">Khách sạn tại Đà Nẵng</a></li>
                            <li>Italy</li>
                        </ul>-->
        </div>
        <div class="clearfix"></div>
        <div class="row mb20 mt30">
            <div class="col-sm-36">
                <div class="col-sm-24">
                    <p class="text-white fs22"><?= $homeStay->name ?></p>
                    <div class="address pt05">
                        <i class="fas fa-map-marker-alt text-red"></i>
                        <span class="text-white fs12"><?= $homeStay->address ?></span>
                    </div>
                </div>
                <div class="col-sm-12 text-right pc">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $homeStay->rating ?>"></p>
                    </div>
                </div>
                <div class="col-sm-12 text-left mt05 sp">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $homeStay->rating ?>"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-eq-height mb30">
            <div class="col-sm-36 col-xs-36 mb15-sp">
                <div class="combo-slider"
                <div class="box-image">
                    <div id="lightgallery" class="imgs_gird grid_6">
                        <?php
                        $list_images = json_decode($homeStay->media, true);
                        $other = count($list_images) - 6;
                        ?>
                        <?php if ($list_images): ?>
                            <?php foreach ($list_images as $key => $image): ?>
                                <?php
                                $class = '';
                                if ($key <= 5) {
                                    $class = 'img item_' . $key;
                                    if ($key == 0) {
                                        $class .= ' big';
                                    } else {
                                        $class .= ' small';
                                    }
                                    if ($key == 5) {
                                        $class .= ' end';
                                    }
                                } else {
                                    $class = 'hide';
                                }
                                ?>
                                <div class="<?= $class ?> " data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                    <img class="img-responsive" src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                    <?php if ($other > 0): ?>
                                        <span class="other">+<?= $other ?></span>
                                    <?php endif; ?>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Header Combo -->
<!-- Start content -->

<div class="combo-detail mb50">
    <div class="container no-pad-left no-pad-right">
        <div class="combo-detail-title mt50 text-center">
            <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN ĐẶT PHÒNG</span>
        </div>
        <div class="bg-grey">
            <div class="mt60">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="pt10">
                            <form id="homestaySelection" method="post" action="<?= \Cake\Routing\Router::url(['_name' => 'homestay.booking', 'slug' => $homeStay->slug]) ?>">
                                <input type="hidden" name="_csrfToken" value="<?= $this->request->getParam('_csrfToken') ?>">
                                <input type="hidden" name="item_id" value="<?= $homeStay->id ?>"/>
                                <div class="col-sm-9">
                                    <span class="text-center">Check in</span>
                                    <div class='input-group date datepicker' id="start-date-picker">
                                            <span class="input-group-addon">
                                                <span class="far fa-calendar-alt main-color"></span>
                                            </span>
                                        <input type='text' name="start_date" class="form-control popup-voucher border-blue"
                                               value="<?= $currentDay ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <span class="text-center">Check out</span>
                                    <div class='input-group date datepicker' id="end-date-picker">
                                            <span class="input-group-addon">
                                                <span class="far fa-calendar-alt main-color"></span>
                                            </span>
                                        <input type='text' name="end_date" class="form-control popup-voucher border-blue"
                                               value="<?= $nextDay ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <span class="text-center">Số phòng</span>
                                    <input type='text' disabled name="numRoom" class="form-control popup-voucher border-blue"
                                           value="<?= $homeStay->num_bed_room ?>"/>
                                </div>
                                <div class="col-sm-9">
                                    <span class="text-center">Số người lớn + trẻ em</span>
                                    <input type='text' name="numPeople" class="form-control popup-voucher border-blue"
                                           placeholder="Số người lớn + trẻ em" value="2NL + 1TE"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt20 mb20">
                <div class="">
                    <div class="row">
                        <div class="row-sm-36">
                            <div class="pl30 pr30">
                                <div class="filter-header mb15">
                                    <div class="row">
                                        <div class="col-xs-20">
                                            <p class="text-center semi-bold text-super-dark">Lịch thuê</p>
                                        </div>
                                        <div class="col-xs-10">
                                            <p class="text-center semi-bold text-super-dark">Giá</p>
                                        </div>
                                        <div class="clear-fix"></div>
                                    </div>
                                </div>

                                <div id="filter_result" class="accordion filter-accordion">

                                </div>
                            </div>
                            <div class="pl30 pr30 pb25 mt20">
                                <div class="row row-eq-height">
                                    <div class="col-xs-36 col-sm-18 mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="fs16" id="filter_result_str"></div>
                                        </div>
                                    </div>

                                    <div class="col-xs-36 col-sm-6 mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="grp-filter-price">
                                                <p class="text-center fs16">Giá tiền</p>
                                                <div class="text-center semi-bold fs16" id="filter_result_price"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-36 col-sm-12">
                                        <button <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?> class="btn btn-request text-white full-width full-height btnGoBooking <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?>" data-form-id="#homestaySelection">
                                            <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                            <br/>
                                            <span class="fs16">MUSTGO sẽ liên hệ lại trong 30 phút</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (($this->request->getSession()->read('Auth.User.role_id') == 3 || $this->request->getSession()->read('Auth.User.role_id') == 2) && $this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                    <div class="mb30">
                        <div class="row">
                            <div class="row-sm-36">
                                <div class="pl30 pr30 pb25">
                                    <div class="row row-eq-height">
                                        <div class="col-xs-36 col-sm-12 mb10-sp">
                                            <a class="btn btn-white full-height full-width text-center" href="tel: <?= $homeStay->hotline ?>">
                                                <div class="">
                                                    <p class="text-center fs16 text-blue">Hotline</p>
                                                    <div class="text-center semi-bold fs16 text-blue"><p><?= $homeStay->hotline ?></p></div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-xs-36 col-sm-12 mb10-sp">
                                            <div class="bg-white full-height full-width">
                                                <div class="p10">
                                                    <p class="text-center fs16">Giá tiền lãi</p>
                                                    <div class="text-center semi-bold fs16" id="filter_result_profit"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-36 col-sm-12 mb10-sp">
                                            <a class="full-width full-height btn btn-white text-center" href="<?= $this->Url->assetUrl('/' . $homeStay->contract_file) ?>" target="_blank">
                                                <p class="text-center fs16 p15 text-blue">File hợp đồng</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-sm-36">
                                <div class="pl30 pr30 pb25">
                                    <div class="row row-eq-height">
                                        <div class="col-xs-36">
                                            <p class="text-left semi-bold text-red">Lưu ý: CTV gọi điện cho khách sạn xin gặp bộ phận sale đặt phòng, Giới thiệu mình gọi từ <a class="text-left semi-bold text-light-blue"> Mustgo.vn</a> check tình trạng phòng trống ( hỏi tên sale làm việc để đối chiếu thông tin về sau).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="combo-detail mb50">
    <div class="combo-detail-title mt50 text-center">
        <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN HOMESTAY</span>
    </div>
    <div class="container mt50 info-hotel no-pad-left no-pad-right">
        <div class="row">
            <div class="col-sm-36">
                <div class="bg-grey p20">
                    <div class="row">
                        <div class="col-sm-36 fs16 mb20">
                            <div class="p10 bg-white">
                                <div class="basic-info">
                                    <div class="rating-hotel rating-inline text-center bold name-hotel">
                                        <span class=""><?= $homeStay->name ?> </span>
                                        <div class="combo-rating fs22">
                                            <p class="star-rating" data-point="<?= $homeStay->rating ?>"></p>
                                        </div>
                                    </div>
                                    <div class="mt20">
                                        <div class="row">
                                            <div class="pc">
                                                <div class="col-sm-offset-3 col-sm-10 col-xs-36 ">
                                                    <?php if ($homeStay->homestay_type == APARTMENT): ?>
                                                        <span class="fs18"><i class="ficon ficon-apartment fs28"></i> Chung cư</span>
                                                    <?php elseif ($homeStay->homestay_type == VILLA): ?>
                                                        <span class="fs18"><i class="ficon ficon-villa fs28"></i> Biệt thự</span>
                                                    <?php elseif ($homeStay->homestay_type == HOME): ?>
                                                        <span class="fs18"><i class="ficon ficon-homestay fs28"></i> Nhà riêng</span>
                                                    <?php elseif ($homeStay->homestay_type == BUNGALOW): ?>
                                                        <span class="fs18"><i class="ficon ficon-bungalow fs28"></i> Bungalow</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-offset-1 col-sm-10 col-xs-36 ">
                                                    <?php if ($homeStay->room_type == SINGLE_ROOM): ?>
                                                        <span class="fs18"><i class="ficon ficon-single-bed-b fs28"></i> Phòng riêng</span>
                                                    <?php elseif ($homeStay->room_type == WHOLE_HOUSE): ?>
                                                        <span class="fs18"><i class="ficon ficon-guest-house fs28"></i> Nguyên căn</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-sm-offset-1 col-sm-10 col-xs-36 ">
                                                    <span class="fs18"><i class="ficon ficon-bedroom fs28"></i> <?= $homeStay->num_bed_room ?> Phòng ngủ</span>
                                                </div>
                                                <div class="col-sm-offset-3 col-sm-10 mt15 col-xs-36 mt0-sp">
                                                    <span class="fs18"><i class="ficon ficon-hotel-people-looking fs28"></i> <?= $homeStay->num_guest ?> Người</span>
                                                </div>
                                                <div class="col-sm-offset-1 col-sm-10 mt15 col-xs-36 mt0-sp">
                                                    <span class="fs18"><i class="ficon ficon-bed fs28"></i> <?= $homeStay->num_bed ?> Giường ngủ</span>
                                                </div>
                                                <div class="col-sm-offset-1 col-sm-10 mt15 col-xs-36 mt0-sp">
                                                    <span class="fs18"><i class="ficon ficon-hot-spring-bath fs28"></i> <?= $homeStay->num_bath_room ?> Phòng tắm</span>
                                                </div>
                                            </div>
                                            <div class="sp">
                                                <div class="col-xs-18 text-center pt10">
                                                    <?php if ($homeStay->homestay_type == APARTMENT): ?>
                                                        <span class="fs18"><i class="ficon ficon-apartment fs28"></i><br>Chung cư</span>
                                                    <?php elseif ($homeStay->homestay_type == VILLA): ?>
                                                        <span class="fs18"><i class="ficon ficon-villa fs28"></i><br>Biệt thự</span>
                                                    <?php elseif ($homeStay->homestay_type == HOME): ?>
                                                        <span class="fs18"><i class="ficon ficon-homestay fs28"></i><br>Nhà riêng</span>
                                                    <?php elseif ($homeStay->homestay_type == BUNGALOW): ?>
                                                        <span class="fs18"><i class="ficon ficon-bungalow fs28"></i><br>Bungalow</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-xs-18 text-center pt10">
                                                    <?php if ($homeStay->room_type == SINGLE_ROOM): ?>
                                                        <span class="fs18"><i class="ficon ficon-single-bed-b fs28"></i><br>Phòng riêng</span>
                                                    <?php elseif ($homeStay->room_type == WHOLE_HOUSE): ?>
                                                        <span class="fs18"><i class="ficon ficon-guest-house fs28"></i><br>Nguyên căn</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-xs-18 text-center pt10">
                                                    <span class="fs18"><i class="ficon ficon-bedroom fs28"></i><br><?= $homeStay->num_bed_room ?> Phòng ngủ</span>
                                                </div>
                                                <div class="mt15 col-xs-18 text-center mt0-sp pt10">
                                                    <span class="fs18"><i class="ficon ficon-hotel-people-looking fs28"></i><br><?= $homeStay->num_guest ?> Người</span>
                                                </div>
                                                <div class="mt15 col-xs-18 text-center mt0-sp pt10">
                                                    <span class="fs18"><i class="ficon ficon-bed fs28"></i><br><?= $homeStay->num_bed ?> Giường ngủ</span>
                                                </div>
                                                <div class="mt15 col-xs-18 text-center mt0-sp pt10">
                                                    <span class="fs18"><i class="ficon ficon-hot-spring-bath fs28"></i><br><?= $homeStay->num_bath_room ?> Phòng tắm</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mt20 mb20">
                                            <div class="short-info mt15 mb20">
                                                <?php
                                                $list_captions = json_decode($homeStay->caption, true);
                                                ?>
                                                <?php if ($list_captions): ?>
                                                    <?php foreach ($list_captions as $capKey => $caption): ?>
                                                        <?php if (is_array($caption)): ?>
                                                            <div class="col-sm-offset-3 col-sm-31 mt15 col-xs-36 mt0-sp">
                                                                <span id="caption-content" class="fs18"><i class="ficon ficon-noti-check-mark-rounded-inner main-color fs20"></i> <?= $caption['content'] ?></span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="col-sm-offset-3 col-sm-31 mt15 col-xs-36 mt0-sp">
                                                                <span id="caption-content" class="fs18"><i class="ficon ficon-noti-check-mark-rounded-inner main-color fs20"></i> <?= $caption ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-36 fs16">
                            <div class="p10 bg-white">
                                <div class="name-hotel text-center bold pb20 mt20">
                                    Tiện nghi homestay
                                </div>
                                <div class="info-utility mb30 mt20">
                                    <div class="row">
                                        <div class="pc">
                                            <?php foreach ($homeStay->categories as $key => $category): ?>
                                                <?php if ($key % 3 == 0): ?>
                                                    <div class="col-sm-offset-3 col-sm-10 funitures pt10">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i>&nbsp;&nbsp;&nbsp; <?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-sm-offset-1 col-sm-10 funitures pt10">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i>&nbsp;&nbsp;&nbsp; <?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($key % 3 == 2): ?>
                                                    <div class="clearfix"></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="sp">
                                            <?php foreach ($homeStay->categories as $key => $category): ?>
                                                <?php if ($key % 2 == 0): ?>
                                                    <div class="col-xs-18 funitures pt10 text-center">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i><br><?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-xs-18 funitures pt10 text-center">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i><br><?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($key % 2 == 1): ?>
                                                    <div class="clearfix"></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-36">
                <div class="bg-grey pl20 pr20 pb15">
                    <div class="map bg-white">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="address pt10 pl15 pr10 mb10-sp">
                                    <i class="fas fa-map-marker-alt pr15 text-red"></i>
                                    <span><?= $homeStay->address ?></span>
                                </div>
                            </div>
                            <div class="col-sm-27">
                                <div class="google-map">
                                    <div id="map" style="width:100%;height:350px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container term no-pad-left-pc no-pad-right-pc">
        <div class="vertical-center mt30">
            <div class="combo-detail-title box-underline-center text-center pb20 mb20">
                <span class="semi-bold fs24">ĐIỀU KHOẢN QUY ĐỊNH, CHÍNH SÁCH HOÀN HỦY</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-36">
                <?php
                $terms = json_decode($homeStay->term, true);
                ?>
                <?php if ($terms): ?>
                    <div class="panel-group" id="accordion-term">
                        <?php foreach ($terms as $key => $term): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle collapsed text-uppercase semi-bold" data-toggle="collapse" data-parent="#accordion-term" href="#collapseTerm-<?= $key ?>">
                                            <?= $term['name'] ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTerm-<?= $key ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?= $term['content'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- End content -->
<!-- Map Zone -->
<link href="https://leafletjs-cdn.s3.amazonaws.com/content/leaflet/master/leaflet.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="https://leafletjs-cdn.s3.amazonaws.com/content/leaflet/master/leaflet.js"></script>
<script type="text/javascript" src="https://tiles.unwiredmaps.com/js/leaflet-unwired.js"></script>
<script type="text/javascript">
    var lat = "<?= $homeStay->lat ?>";
    var lon = "<?= $homeStay->lon ?>";
    lat = parseFloat(lat);
    lon = parseFloat(lon);
    // Maps access token goes here
    var key = '<?= LOCATIONIQ_ACCESS_TOKEN?>';

    // Add layers that we need to the map
    var streets = L.tileLayer.Unwired({key: key, scheme: "streets"});

    // Initialize the map
    var map = L.map('map', {
        center: [lat, lon], // Map loads with this location as center
        zoom: 18,
        scrollWheelZoom: false,
        layers: [streets] // Show 'streets' by default
    });

    // Add the 'scale' control
    L.control.scale().addTo(map);

    // Add the 'layers' control
    L.control.layers({
        "Streets": streets
    }).addTo(map);

    // Add a 'marker'
    var marker = L.marker([lat, lon]).addTo(map);


</script>
<!-- End Map Zone -->
<?= $this->element('Front/Popup/description') ?>
<?= $this->element('Front/Popup/category') ?>
<?= $this->element('Front/Popup/bookinghomestay') ?>
