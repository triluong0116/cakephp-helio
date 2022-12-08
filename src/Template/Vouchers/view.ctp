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
            <div class="col-sm-36 col-xs-36 no-pad-right">
                <div class="col-sm-24">
                    <p class="text-white fs22"><?= $voucher->name ?></p>
                    <div class="address pt05 text-white">
                        <p><i class="far fa-clock fs20"></i> <?= $voucher->days_attended + 1 ?> ngày <?= $voucher->days_attended ?> đêm</p>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-36 text-right pc">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $voucher->rating ?>"></p>
                    </div>
                </div>
                <div class="col-sm-12 text-left mt05 sp">
                    <div class="combo-rating fs22">
                        <p class="star-rating" data-point="<?= $voucher->rating ?>"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-sm-36 no-pad-right-pc col-xs-36 mb15-sp">
                <div class="combo-slider">
                    <div class="box-image">
                        <?php
                        $list_images = json_decode($voucher->media, true);
                        if (count($list_images) == 0) {
                            $list_images = [];
                            if ($voucher->hotel->media) {
                                $hotel_medias = json_decode($voucher->hotel->media, true);
                            } else {
                                $hotel_medias = [];
                            }
                            $list_images = array_merge($list_images, $hotel_medias);
                        }
                        $other = count($list_images) - 6;
                        if (count($list_images) == 1) {
                            $grid = "grid_1";
                        } else {
                            $grid = "grid_6";
                        }
                        ?>
                        <div id="lightgallery" class="imgs_gird <?= $grid ?>">

                            <?php if ($list_images) : ?>
                                <?php foreach ($list_images as $key => $image) : ?>
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
                                        <?php if ($other > 0) : ?>
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

<div class="combo-detail mb50">
    <div class="container no-pad-left no-pad-right">
        <div class="combo-detail-title mt50 text-center">
            <span class="semi-bold box-underline-center fs24 pb20"> TIN ĐẶT VOUCHER</span>
        </div>
        <div class="bg-grey">
            <div class="mt60">
                <div class="row">
                    <div class="col-sm-36">
                        <div class="pt10">
                            <form id="voucherSelection" method="post" action="<?= \Cake\Routing\Router::url(['_name' => 'voucher.booking', 'slug' => $voucher->slug]) ?>">
                                <input type="hidden" name="voucher_id" value="<?= $voucher->id ?>"/>
                                <input type="hidden" name="_csrfToken" value="<?= $this->request->getParam('_csrfToken') ?>">
                                <div class="col-sm-12 mb10-sp">
                                    <span class="text-center">Check in</span>
                                    <div class='input-group date datepicker' id="start-date-picker">
                                        <span class="input-group-addon">
                                            <span class="far fa-calendar-alt main-color"></span>
                                        </span>
                                        <input type='text' name="fromDate" class="form-control popup-voucher border-blue" value="<?= date('d-m-Y') ?>"/>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb10-sp">
                                    <span class="text-center">Số lượng Voucher</span>
                                    <input type='text' name="numVoucher" class="form-control popup-voucher border-blue" value="1"/>
                                </div>
                                <div class="col-sm-12 mb10-sp">
                                    <span class="text-center">Giá</span>
                                    <input type='text' disabled="disabled" class="form-control popup-voucher border-blue" placeholder="Số người" value="<?= number_format($voucher->price + $voucher->customer_price + $voucher->trippal_price) ?>"/>
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
                                    <div class="col-xs-36 col-sm-12 mb10-sp">
                                        <button class="btn btn-request text-white full-width btnGoBooking" <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?> data-form-id="#voucherSelection">
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
                <?php if (($this->request->getSession()->read('Auth.User.role_id') == 3 || $this->request->getSession()->read('Auth.User.role_id') == 2) && $this->request->getSession()->read('Auth.User.is_active') == 1) : ?>
                    <div class="mb30">
                        <div class="row">
                            <div class="row-sm-36">
                                <div class="pl30 pr30 pb25">
                                    <div class="row row-eq-height">
                                        <div class="col-xs-36  col-sm-8 mb10-sp">
                                            <a class="btn btn-white full-width text-center" href="tel: <?= $voucher->hotline ?>">
                                                <div class="">
                                                    <p class="text-center fs16 text-blue">Hotline</p>
                                                    <div class="text-center semi-bold fs16 text-blue">
                                                        <p><?= $voucher->hotel->hotline ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-xs-36  col-sm-8 mb10-sp">
                                            <a class="full-width btn btn-white text-center" href="<?= $this->Url->assetUrl('/' . $voucher->hotel->contract_file) ?>" target="_blank">
                                                <p class="text-center fs16 p15 text-blue">File hợp đồng</p>
                                            </a>
                                        </div>
                                        <div class="col-xs-36  col-sm-8 mb10-sp">
                                            <div class="bg-white full-width">
                                                <div class="p10">
                                                    <p class="text-center fs16">Giá tiền lãi</p>
                                                    <div class="text-center semi-bold fs16" id="filter_result_profit"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-36  col-sm-6 mb10-sp">
                                            <div class="btn btn-primary full-width">
                                                <p class="p15 fs18" onclick="Frontend.showModalPostFB(<?= VOUCHER ?>, <?= $voucher->id ?>);">
                                                    <i class="fab fa-facebook-f fs20"></i>&nbsp;&nbsp;Facebook
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-xs-36  col-sm-6 mb10-sp flex">
                                            <div class="btn btn-white full-width p15">
                                                <div class="zalo-share-button" data-object-type="<?= VOUCHER ?>" data-object-id="<?= $voucher->id ?>" data-callback="shareZaloSuccess" data-href="<?= $this->Url->build(['_name' => 'hotel.view', 'slug' => $voucher->slug, 'ref' => $ref], true) ?>" data-oaid="579745863508352884" data-layout="3" data-color="blue" data-customize="false">
                                                </div>
                                            </div>
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

<div class="combo-detail-title mt50 text-center" id="detail">
    <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN VỀ VOUCHER</span>
    <div class="container mt50 info-hotel no-pad-left no-pad-right">
        <div class="row">
            <div class="col-sm-36">
                <div class="bg-grey p20">
                    <div class="row">
                        <div class="col-sm-36 fs16 mb20">
                            <div class="p10 bg-white">
                                <div class="basic-info">
                                    <div class="rating-hotel rating-inline text-center bold name-hotel">
                                        <span class=""><?= $voucher->name ?> </span>
                                        <div class="combo-rating fs22">
                                            <p class="star-rating" data-point="<?= $voucher->rating ?>"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mt20 mb20">
                                            <div class="short-info mt15 mb20">
                                                <?php
                                                $list_captions = json_decode($voucher->caption, true);
                                                ?>
                                                <?php if ($list_captions) : ?>
                                                    <?php foreach ($list_captions as $capKey => $caption) : ?>
                                                        <?php if (is_array($caption)) : ?>
                                                            <div class="col-sm-offset-3 col-sm-31 mt15 col-xs-36 mt0-sp">
                                                                <span id="caption-content" class="fs18"><i class="ficon ficon-noti-check-mark-rounded-inner main-color fs20"></i> <?= $caption['content'] ?></span>
                                                            </div>
                                                        <?php else : ?>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Combo -->
<!-- Start content -->
<div class="combo-detail mb50">
    <div class="combo-detail-title mt50 text-center">
        <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN KHÁCH SẠN</span>
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
                                        <span class=""><?= $voucher->hotel->name ?> </span>
                                        <div class="combo-rating fs22">
                                            <p class="star-rating" data-point="<?= $voucher->hotel->rating ?>"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mt20 mb20">
                                            <div class="short-info mt15 mb20">
                                                <?php
                                                $list_captions = json_decode($voucher->hotel->caption, true);
                                                ?>
                                                <?php if ($list_captions) : ?>
                                                    <?php foreach ($list_captions as $capKey => $caption) : ?>
                                                        <?php if (is_array($caption)) : ?>
                                                            <div class="col-sm-offset-3 col-sm-31 mt15 col-xs-36 mt0-sp">
                                                                <span id="caption-content" class="fs18"><i class="ficon ficon-noti-check-mark-rounded-inner main-color fs20"></i> <?= $caption['content'] ?></span>
                                                            </div>
                                                        <?php else : ?>
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
                                    Tiện nghi khách sạn
                                </div>
                                <div class="info-utility mb30 mt20">
                                    <div class="row">
                                        <div class="pc">
                                            <?php foreach ($voucher->hotel->categories as $key => $category) : ?>
                                                <?php if ($key % 3 == 0) : ?>
                                                    <div class="col-sm-offset-3 col-sm-10 funitures pt10">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i>&nbsp;&nbsp;&nbsp; <?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="col-sm-offset-1 col-sm-10 funitures pt10">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i>&nbsp;&nbsp;&nbsp; <?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($key % 3 == 2) : ?>
                                                    <div class="clearfix"></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="sp">
                                            <?php foreach ($voucher->hotel->categories as $key => $category) : ?>
                                                <?php if ($key % 2 == 0) : ?>
                                                    <div class="col-xs-18 funitures pt10 text-center">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i><br><?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="col-xs-18 funitures pt10 text-center">
                                                        <p>
                                                            <i id="caption-content" class="ficon <?= (isset($category->icon) && !empty($category->icon)) ? $category->icon : 'ficon-right-tick' ?> fs28"></i><br><?= $category->name ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($key % 2 == 1) : ?>
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
                                    <span><?= $voucher->hotel->address ?></span>
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
                $terms = json_decode($voucher->term, true);
                ?>
                <?php if ($terms) : ?>
                    <div class="panel-group" id="accordion-term">
                        <?php foreach ($terms as $key => $term) : ?>
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
<script type="text/javascript">
    var geocoder;
    var map;
    var address = "<?= $voucher->hotel->address ?>";

    function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var myOptions = {
            zoom: 16,
            center: latlng,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        if (geocoder) {
            geocoder.geocode({
                'address': address
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                        map.setCenter(results[0].geometry.location);

                        var infowindow = new google.maps.InfoWindow({
                            content: '<b>' + address + '</b>',
                            size: new google.maps.Size(150, 50)
                        });

                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                            title: address
                        });
                        google.maps.event.addListener(marker, 'click', function () {
                            infowindow.open(map, marker);
                        });

                    } else {
                        alert("No results found");
                    }
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAP_API; ?>&callback=initialize"></script>
<!-- End content -->
<?= $this->element('Front/Popup/description') ?>
<?= $this->element('Front/Popup/category') ?>
<?= $this->element('Front/Popup/bookingvoucher') ?>
