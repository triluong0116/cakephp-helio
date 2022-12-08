<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $voucher
 */
?>

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
                    <p class="text-white fs30"><?= $newReviews->title ?></p>
                    <div class="address pt05">
                        <?php $location = json_decode($newReviews->place, true); ?>
                        <?php foreach ($location as $place): ?>
                            <span class="text-white"><i class="fas fa-map-marker-alt text-red"></i>
                              <?= $place['address'] . ", " . $place['name'] ?>
                            </span>
                            <br>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-sm-18 text-right">
                    <div class="combo-rating fs20">
                        <p class="star-rating" data-point="<?= $newReviews->rating ?>"></p>
                    </div>
                    <p class="pc "><span class="fs20 text-orange"><?= number_format($newReviews->price_start) . " - " . number_format($newReviews->price_end) ?></span>
                        <span class="text-white fs11"> vnđ</span></p>
                    <p class="sp "><span class="fs20 text-orange"><?= number_format($newReviews->price_end) ?></span>
                        <span class="text-white fs11"> vnđ</span></p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height mb30">
            <div class="col-sm-36 col-xs-36 mb15-sp">
                <div class="combo-slider"
                <div class="box-image">
                    <div id="lightgallery" class="imgs_gird grid_6">
                        <?php
                        $list_images = json_decode($newReviews->media, true);
                        if ($list_images) {
                            $other = count($list_images) - 6;
                        }
                        ?>
                        <?php if ($list_images) : ?>
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
<div class="blog-detail mb20">
    <div class="container mt50 no-pad-left-sp no-pad-right-sp">
        <div class="row">
            <div class="col-sm-36">
                <div class="list-location">
                    <div class="row">
                        <div class="col-sm-36">
                            <div class="mt30 ml40 mr30">
                                <span class="semi-bold fs24 pb20"><?= $newReviews->title ?></span>
                            </div>
                            <!-- Ten -->
                            <div class="ml40 mr30 mt20">
                                <div class="content text-justify">
                                    <?= $newReviews->content ?>
                                </div>
                            </div>
                            <!-- Anh -->
                            <?php if ($newReviews->place): ?>
                                <div class="mt20 ml30 mr30 mb30 review-content">
                                    <div class="row">
                                        <div class="col-sm-36">
                                            <div class="bg-grey">
                                                <div class="map bg-white">
                                                    <div class="row">
                                                        <div class="col-sm-9">
                                                            <div class="address pt10 pl10 pr10">
                                                                <?php $listReviewPlaces = json_decode($newReviews->place, true) ?>
                                                                <?php foreach ($listReviewPlaces as $place): ?>
                                                                    <p class="fs16 text-dark bold mt10"><i class="fas fa-map-marker-alt pr15 text-red"></i>
                                                                        <a onclick="loadMap('<?= $place['address'] ?>')"><?= $place['name'] ?></a>
                                                                    </p>
                                                                    <p class="ml30 fs14 mb10"><?= $place['address'] ?></p>
                                                                    <hr>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-27">
                                                            <div class="google-map">
                                                                <div id="map_canvas"
                                                                     style="width:100%;height:400px"></div>
                                                            </div>
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
        </div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
    var map;
    //var locations = '<?//= json_encode($listPlaces) ?>//';
    var address = <?= json_encode($listPlaces[0]) ?>;

    function initialize() {
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
            zoom: 16,
            center: new google.maps.LatLng(21.002061, 105.803938),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);

        if (geocoder) {
            loadMap(address)
        }
    }

    function loadMap(address) {
        geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                    map.setCenter(results[0].geometry.location);

                    var infowindow = new google.maps.InfoWindow(
                        {
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

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAP_API; ?>&callback=initialize"></script>