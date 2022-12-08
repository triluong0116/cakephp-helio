<!-- Start content -->
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
    <?php $hotel = $combo->hotels[0]; ?>
    <div class="combo-detail-title mt50 text-center">
        <span class="semi-bold box-underline-center fs24 pb20">THÔNG TIN KHÁCH SẠN</span>
    </div>
    <div class="container mt50 info-hotel">
        <div class="row">
            <div class="col-sm-36">
                <div class="bg-grey p10">
                    <div class="row">
                        <div class="col-sm-18 fs16">
                            <div class="p10 bg-white">
                                <div class="name-hotel text-center bold">
                                    <?= $hotel->name ?>
                                </div>
                                <div class="basic-info">
                                    <div class="rating-hotel rating-inline">
                                        <span>Hạng khách sạn: </span>
                                        <div class="combo-rating fs22">
                                            <p class="star-rating" data-point="<?= $hotel->rating ?>"></p>
                                        </div>
                                    </div>
                                    <div class="short-info">
                                        <?= $hotel->description ?>
                                    </div>
                                    <!--                                <div class="area pt20">
                                                                        <i class="far fa-map text-blue fs22 pr15"></i>
                                                                        <span>Area: 1000m2</span>
                                                                    </div>
                                                                    <div class="number-rooms pt20">
                                                                        <i class="fas fa-bed text-blue fs22 pr13"></i>
                                                                        <span>
                                                                            Num beds: 1000
                                                                        </span>
                                                                    </div>-->
                                    <div class="text-center pt20 pb30">
                                        <button class="btn btn-primary view-all p10" onclick="Frontend.openHotelDescription(<?= $hotel->id ?>)">View all</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-18 fs16">
                            <div class="p10 bg-white">
                                <div class="name-hotel text-center bold pb20">
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
                                <div class="text-center pt30 pb30">
                                    <button class="btn btn-primary view-all p10" onclick="Frontend.openHotelCategory(<?= $hotel->id ?>)">View all</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-36">
                <div class="bg-grey p10">                    
                    <div class="map bg-white">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="address pt10 pl10 pr10">
                                    <i class="fas fa-map-marker-alt pr15 text-red"></i>
                                    <span><?= $hotel->address ?></span>
                                </div>
                            </div>
                            <div class="col-sm-27">
                                <div class="p10">
                                    <div id="map" style="width:100%;height:350px">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container term">
        <div class="vertical-center mt30">
            <div class="combo-detail-title box-underline-center text-center pb20 mb20">
                <span class="semi-bold fs24">ĐIỀU KHOẢN QUY ĐỊNH, CHÍNH SÁCH HOÀN HỦY</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-36">
                <div class="bg-grey p10">
                    <div class="bg-white p10">
                        <?= $hotel->term ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var geocoder;
    var map;
    var address = "<?= $hotel->address ?>";
    function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var myOptions = {
            zoom: 16,
            center: latlng,
            mapTypeControl: true,
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        if (geocoder) {
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                        map.setCenter(results[0].geometry.location);

                        var infowindow = new google.maps.InfoWindow(
                                {content: '<b>' + address + '</b>',
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