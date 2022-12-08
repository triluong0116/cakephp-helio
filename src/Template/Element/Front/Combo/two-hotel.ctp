<div class="combo-detail mb50">
    <div class="container mt50 info-combo">
        <div class="row ">
            <div class="col-sm-36">
                <div class="bg-grey p10">

                    <div class="block-2 bg-white p10">
                        <div class="description fs16">
                            <?= $combo->description ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="combo-detail-title mt50 text-center">
        <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN KHÁCH SẠN</span>
    </div>

    <div class="container mt50 info-hotel">
        <div class="row">
            <?php $index = 0; ?>
            <?php foreach ($combo->hotels as $key => $hotel): ?>
                <?php
                $listLocations[] = $hotel->address;
                ?>
                <div class="col-sm-18">
                    <div class="bg-grey p10">
                        <div class="row">
                            <div class="col-sm-36 fs16">
                                <div class="name-hotel text-center pb15 bold pt35 bg-white ">
                                    <?= $hotel->name ?>
                                </div>
                                <div class="basic-info bg-white p10">
                                    <div class="rating-inline">
                                        <span>Hạng khách sạn:&nbsp; </span>
                                        <div class="combo-rating fs22">

                                            <p class="star-rating" data-point="<?= $hotel->rating ?>"></p>
                                        </div>
                                    </div>
                                    <div class="short-info pt20">
                                        <?= $hotel->description ?>
                                    </div>
                                    <!--                                        <div class="area pt20">
                                                                                <i class="far fa-map text-blue fs22 pr15"></i>
                                                                                <span>Area: 1000m2</span>
                                                                            </div>
                                                                            <div class="number-rooms pt20">
                                                                                <i class="fas fa-bed text-blue fs22 pr13"></i>
                                                                                <span>
                                                                                    Num beds: 1000
                                                                                </span>
                                                                            </div>-->
                                </div>
                                <div class="pb20 bg-white">
                                    <center>
                                        <button class="btn btn-primary view-al" onclick="Frontend.openHotelDescription(<?= $hotel->id ?>)">View all</button>
                                    </center>
                                </div>
                            </div>
                            <div class="col-sm-36 fs16">
                                <div class="bg-white p10">
                                    <div class="name-hotel text-center bold p10">
                                        Tiện nghi khách sạn
                                    </div>
                                    <div class="info-utility">
                                        <div class="row">
                                            <?php foreach ($hotel->categories as $key => $category): ?>
                                                <div class="col-sm-18 funitures">
                                                    <p>
                                                        <i class="fas fa-check text-blue"></i> <?= $category->name ?>
                                                    </p>
                                                </div>
                                                <?php if ($key % 2 == 1): ?>
                                                    <div class="clearfix"></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="p20">
                                        <center>
                                            <button class="btn btn-primary view-al" onclick="Frontend.openHotelCategory(<?= $hotel->id ?>)">View all</button>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-36">
                                <div class="bg-grey pt20">
                                    <div class="map bg-white">
                                        <div class="address pt20 pl10">
                                            <i class="fas fa-map-marker-alt pr15 text-red"></i>
                                            <span><?= $hotel->address ?></span>
                                        </div>
                                        <div class="pt10">
                                            <div id="googleMap<?= $index ?>" style="width:100%;height:350px">

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-36 fs16 mt20">
                                <div class="p10 bg-white">
                                    <div class="combo-detail-title text-center">
                                        <span class="semi-bold fs16 pb20">ĐIỀU KHOẢN QUY ĐỊNH, CHÍNH SÁCH HOÀN HỦY</span>
                                    </div>
                                    <div class="mt30 term short-info">
                                        <div class="row">
                                            <div class="col-sm-36">
                                                <div class="">
                                                    <div class="">
                                                        <?= $hotel->term ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p30">
                                        <center>
                                            <button class="btn btn-primary view-al" onclick="Frontend.openHotelTerm(<?= $hotel->id ?>)">View all</button>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <?php $index++; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAP_API; ?>&libraries=places"></script>
<script type="text/javascript">
    var geocoder = new google.maps.Geocoder();
    var map = [];
    var locationPhp = '<?= json_encode($listLocations) ?>';
    var locations = JSON.parse(locationPhp);
    var pos = [];
    var infowindow = new google.maps.InfoWindow({});

    function initialize()
    {
        google.maps.visualRefresh = true;
        getGeoCode();
    }

    function makeMap(i) {
        geocoder.geocode({'address': locations[i]}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK)
            {

                pos[i] = results[0].geometry.location;
                var mapOptions = {
                    zoom: 16,
                    center: pos[i],
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: true,
                    mapTypeControl: true,
                    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                    navigationControl: true,
                };
                map[i] = new google.maps.Map(document.getElementById("googleMap" + i), mapOptions);
                var marker = new google.maps.Marker({
                    position: pos[i],
                    map: map[i],
                    title: locations[i]
                });

                marker.setMap(map[i]);

            } else
            {
                alert("Not found");
            }
        });
    }
    function getGeoCode()
    {
        for (var i = 0; i < 2; i++) {
            makeMap(i);
        }
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?= $this->element('Front/Popup/description') ?>
<?= $this->element('Front/Popup/category') ?>
<?= $this->element('Front/Popup/term') ?>

