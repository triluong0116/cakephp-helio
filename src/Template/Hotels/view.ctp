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
            <div class="col-sm-36 no-pad-right-pc">
                <div class="col-sm-18">
                    <p class="text-white fs22"><?= $hotel->name ?></p>
                    <div class="address pt05">
                        <i class="fas fa-map-marker-alt text-red"></i>
                        <span class="text-white fs12"><?= $hotel->address ?></span>
                    </div>
                </div>
                <div class="col-sm-18 text-right pc">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $hotel->rating ?>"></p>
                    </div>
                </div>
                <div class="col-sm-18 text-left mt05 sp">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $hotel->rating ?>"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-eq-height mb30">
            <div class="col-sm-36 col-xs-36 mb15-sp">
                <div class="combo-slider">
                    <div class="box-image">
                        <div id="lightgallery" class="imgs_gird grid_6">
                            <?php
                            $list_images = json_decode($hotel->media, true);
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
        <div class="combo-detail-title mt50 mt30-sp text-center">
            <span class="semi-bold box-underline-center fs24 pb20 pb05-sp">THÔNG TIN ĐẶT PHÒNG</span>
        </div>
        <div class="bg-grey">
            <div class="mt60 mt30-sp">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="pt10">
                            <form id="hotelRoomSelection" method="post" action="<?= \Cake\Routing\Router::url(['_name' => 'hotel.booking', 'slug' => $hotel->slug]) ?>">
                                <input type="hidden" name="_csrfToken" value="<?= $this->request->getParam('_csrfToken') ?>">
                                <input type="hidden" name="hotel_id" value="<?= $hotel->id ?>"/>
                                <input type="hidden" name="room_id" value=""/>
                                <div class="col-sm-9">
                                    <span class="text-center">Check in</span>
                                    <div class='input-group date datepicker' id="start-date-picker">
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-alt main-color"></span>
                                    </span>
                                        <input type='text' name="fromDate" class="form-control popup-voucher border-blue" readonly="readonly"
                                               placeholder="Thời gian đi" id="start-date" value="<?= date('d-m-Y') ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <span class="text-center">Check out</span>
                                    <div class='input-group date datepicker' id="end-date-picker">
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-alt main-color"></span>
                                    </span>
                                        <input type='text' id="end-date" name="toDate" class="form-control popup-voucher border-blue" readonly="readonly"
                                               placeholder="Thời gian về" value="<?= date('d-m-Y', strtotime(date('d-m-Y') . "+1 days")) ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-center">Số phòng</span>
                                    <!--                                    <input type='text' name="numRoom" class="form-control popup-voucher border-blue"-->
                                    <!--                                           placeholder="Số phòng" value="1"/>-->
                                    <select class="form-control popup-voucher select-no-arrow" name="numRoom">
                                        <?php for ($i = 1; $i <= 50; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> Phòng</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-center">Số người lớn</span>
                                    <!--                                    <input type='text' name="num_adult" class="form-control popup-voucher border-blue"-->
                                    <!--                                           placeholder="Số người lớn + trẻ em" value="2"/>-->
                                    <select class="form-control popup-voucher select-no-arrow" name="num_adult">
                                        <?php for ($i = 1; $i <= 50; $i++): ?>
                                            <option value="<?= $i ?>" <?= $i == 2 ? 'selected' : '' ?>><?= $i ?> Người lớn</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-center">Số trẻ em</span>
                                    <!--                                    <input type='text' name="num_children" class="form-control popup-voucher border-blue"-->
                                    <!--                                           placeholder="Số người lớn + trẻ em" value="1"/>-->
                                    <select class="form-control popup-voucher select-no-arrow" name="num_children">
                                        <?php for ($i = 1; $i <= 50; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> Trẻ em</option>
                                        <?php endfor; ?>
                                    </select>
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
                                        <div class="col-sm-14 col-xs-18">
                                            <p class="ml30 fs12-sp text-left semi-bold text-super-dark">Hạng phòng</p>
                                        </div>
                                        <div class="col-sm-8 col-xs-12">
                                            <p class="text-center fs12-sp semi-bold text-super-dark">Giá</p>
                                        </div>
                                        <div class="col-sm-8 pc">
                                            <p class="ml10 text-center fs12-sp semi-bold text-super-dark">Tình trạng phòng</p>
                                        </div>
                                        <div class="clear-fix"></div>
                                    </div>
                                </div>
                                <div id="filter_result" class="accordion filter-accordion">

                                </div>
                            </div>
                            <div class="pl30 pr30 pb25 mt20">
                                <div class="row row-eq-height">
                                    <div class="col-xs-36 <?= $this->request->getSession()->read('Auth.User.id') ? 'col-sm-18' : 'col-sm-15' ?> mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="fs16 fs15-sp" id="filter_result_str"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-36 <?= $this->request->getSession()->read('Auth.User.id') ? 'col-sm-18' : 'col-sm-9' ?>  mb10-sp flex">
                                        <div class="bg-white p15 full-width">
                                            <div class="grp-filter-price">
                                                <p class="text-right fs16 fs15-sp">Tổng cộng</p>
                                                <div class="text-right semi-bold fs24 fs15-sp text-orange" id="filter_result_price"></div>
                                            </div>
                                            <?php if ($this->request->getSession()->read('Auth.User.id')): ?>
                                                <div class="grp-filter-price">
                                                    <p class="text-right fs16 fs15-sp">Chiết khấu đại lý</p>
                                                    <div class="text-right semi-bold fs24 fs15-sp text-orange" id="filter_result_profit"></div>
                                                </div>
                                                <div class="grp-filter-price">
                                                    <p class="text-right fs16 fs15-sp">Đại lý phải thanh toán</p>
                                                    <div class="text-right semi-bold fs24 fs15-sp text-orange" id="filter_result_final_price"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!$this->request->getSession()->read('Auth.User.id')): ?>
                                        <div class="col-xs-36 col-sm-12 mb10-sp">
                                            <button class="btn btn-request text-white full-width full-height btnGoBooking" <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?> data-form-id="#hotelRoomSelection">
                                                <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                                <br/>
                                                <span class="fs16">MUSTGO sẽ liên hệ lại trong 30 phút</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="row row-eq-height mt20 mt10-sp">
                                    <?php if (($this->request->getSession()->read('Auth.User.role_id') == 3 || $this->request->getSession()->read('Auth.User.role_id') == 2) && $this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                                        <div class="col-xs-16 col-sm-6 mb10-sp vertical-center flex-direction-column bg-white text-light-blue mr15 ml15 ml15-sp mr0-sp no-pad-right no-pad-left border-light-blue">
                                            <a class="btn btn-white full-height full-width text-center" href="tel: <?= $hotel->hotline ?>">
                                                <div class="">
                                                    <p class="text-center fs16 fs11-sp">Hotline 1</p>
                                                    <div class="text-center semi-bold fs16 fs11-sp"><p><?= $hotel->hotline ?></p></div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-xs-16 col-sm-6 mb10-sp vertical-center flex-direction-column bg-white text-light-blue mr15 ml15 ml10-sp mr0-sp no-pad-right no-pad-left border-light-blue">
                                            <a class="btn btn-white full-height full-width text-center" href="tel: <?= $hotel->hotline ?>">
                                                <div class="">
                                                    <p class="text-center fs16 fs11-sp">Hotline 2</p>
                                                    <div class="text-center semi-bold fs16 fs11-sp"><p><?= $hotel->hotline ?></p></div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-xs-16 col-sm-6 mb15-sp vertical-center flex-direction-column bg-white text-light-blue mr15 ml15 mr0-sp no-pad-right no-pad-left border-light-blue">
                                            <a class="full-width full-height btn btn-white text-center" href="<?= $this->Url->assetUrl('/' . $hotel->promotion) ?>" target="_blank">
                                                <p class="text-center fs16 p15 p08-sp fs11-sp">File Khuyễn mãi</p>
                                            </a>
                                        </div>
                                        <div class="col-xs-16 col-sm-6 mb15-sp vertical-center flex-direction-column bg-white text-light-blue mr15 ml15 ml10-sp mr0-sp no-pad-right no-pad-left border-light-blue">
                                            <a class="full-width full-height btn btn-white text-center" href="<?= $this->Url->assetUrl('/' . $hotel->contract_file) ?>" target="_blank">
                                                <p class="text-center fs16 p15 p08-sp fs11-sp">File hợp đồng</p>
                                            </a>
                                        </div>
                                        <div class="col-xs-36 col-sm-12 mb10-sp">
                                            <button class="btn btn-request text-white full-width full-height btnGoBooking" <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?> data-form-id="#hotelRoomSelection">
                                                <span class="semi-bold fs20">GỬI YÊU CẦU</span>
                                                <br/>
                                                <span class="fs16">MUSTGO sẽ liên hệ lại trong 30 phút</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($this->request->getSession()->read('Auth.User.role_id') == 3 && $this->request->getSession()->read('Auth.User.is_active') == 1): ?>
                    <div class="mb30">
                        <div class="row">
                            <div class="row-sm-36">
                                <!--<div class="pl30 pr30 pb25">
                                        <div class="row row-eq-height">
                                            <div class="col-xs-36 col-sm-8 mb10-sp">
                                                <a class="btn btn-white full-height full-width text-center" href="tel: <? /*= $hotel->hotline */ ?>">
                                                    <div class="">
                                                        <p class="text-center fs16 text-blue">Hotline</p>
                                                        <div class="text-center semi-bold fs16 text-blue"><p><? /*= $hotel->hotline */ ?></p></div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-xs-36 col-sm-8 mb10-sp">
                                                <a class="full-width full-height btn btn-white text-center" href="<? /*= $this->Url->assetUrl('/' . $hotel->contract_file) */ ?>" target="_blank">
                                                    <p class="text-center fs16 p15 text-blue">File hợp đồng</p>
                                                </a>
                                            </div>
                                            <div class="col-xs-36 col-sm-8 mb10-sp">
                                                <div class="bg-white full-height full-width">
                                                    <div class="p10">
                                                        <p class="text-center fs16">Giá tiền lãi</p>
                                                        <div class="text-center semi-bold fs16" id="filter_result_profit"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-36 col-sm-6 mb10-sp">
                                                <div class="btn btn-primary full-width full-height">
                                                    <p class="p15 fs18"
                                                       onclick="Frontend.showModalPostFB(<? /*= HOTEL */ ?>, <? /*= $hotel->id */ ?>);">
                                                        <i class="fab fa-facebook-f fs20"></i>&nbsp;&nbsp;Facebook
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-xs-36 col-sm-6 mb10-sp flex">
                                                <div class="btn btn-white full-width full-height p15">
                                                    <div class="zalo-share-button" data-object-type="<? /*= HOTEL */ ?>"
                                                         data-object-id="<? /*= $hotel->id */ ?>" data-callback="shareZaloSuccess"
                                                         data-href="<? /*= $this->Url->build(['_name' => 'hotel.view', 'slug' => $hotel->slug, 'ref' => $ref], true) */ ?>"
                                                         data-oaid="579745863508352884" data-layout="3" data-color="blue"
                                                         data-customize="false">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
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

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="combo-detail mb50">
    <div class="combo-detail-title mt50 mt20-sp text-center">
        <span class="semi-bold box-underline-center fs24 pb20 pb05-sp">THÔNG TIN KHÁCH SẠN</span>
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
                                        <span class=""><?= $hotel->name ?> </span>
                                        <div class="combo-rating fs22">
                                            <p class="star-rating" data-point="<?= $hotel->rating ?>"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mt20 mb20">
                                            <div class="short-info mt15 mb20">
                                                <?php
                                                $list_captions = json_decode($hotel->caption, true);
                                                ?>
                                                <?php if ($list_captions): ?>
                                                    <?php foreach ($list_captions as $capKey => $caption): ?>
                                                        <?php if (is_array($caption)): ?>
                                                            <div class="col-sm-offset-3 col-sm-31 mt15 col-xs-36 mt0-sp">
                                                                <span id="caption-content" class="fs18 fs14-sp"><i class="ficon ficon-noti-check-mark-rounded-inner main-color fs20"></i> <?= $caption['content'] ?></span>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="col-sm-offset-3 col-sm-31 mt15 col-xs-36 mt0-sp">
                                                                <span id="caption-content" class="fs18 fs14-sp"><i class="ficon ficon-noti-check-mark-rounded-inner main-color fs20"></i> <?= $caption ?></span>
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
                                    Tiện nghi khách sạn
                                </div>
                                <div class="info-utility mb30 mt20">
                                    <div class="row">
                                        <div class="pc">
                                            <?php foreach ($hotel->categories as $key => $category): ?>
                                                <?php if ($key % 3 == 0): ?>
                                                    <div class="col-sm-offset-3 col-sm-10 funitures">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i>&nbsp;&nbsp;&nbsp; <?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-sm-offset-1 col-sm-10 funitures">
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
                                            <?php foreach ($hotel->categories as $key => $category): ?>
                                                <?php if ($key % 2 == 0): ?>
                                                    <div class="col-xs-18 funitures text-center">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i><br><?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-xs-18 funitures text-center">
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
                                    <span><?= $hotel->address ?></span>
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
            <div class="combo-detail-title box-underline-center text-center pb20 mb20 pb05-sp">
                <span class="semi-bold fs24">ĐIỀU KHOẢN QUY ĐỊNH, CHÍNH SÁCH HOÀN HỦY</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-36">
                <?php
                $terms = json_decode($hotel->term, true);
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
    var lat = "<?= $hotel->lat ?>";
    var lon = "<?= $hotel->lon ?>";
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
<?= $this->element('Front/Popup/bookinghotel') ?>
