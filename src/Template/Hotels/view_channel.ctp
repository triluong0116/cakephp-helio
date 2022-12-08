<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $voucher
 */
?>
<!-- Header Combo -->
<style>

    .wrapper-slider::after {
        content: "";
        position: absolute;
        bottom: 70px;
        left: 17%;
        height: 120px;
        width: 66%;
        background-color: #fff;
        opacity: 0.5;
        border-radius: 5px;
    }

    .box-search {
        margin-top: -160px;
        z-index: 999;
        position: relative;
        margin-left: 30px;
        margin-right: 30px;
    }

    .hotel-name {
        margin-top: -400px;
        z-index: 999;
        position: relative;
        margin-left: 30px;
        margin-right: 30px;
    }

    .popup-input-room {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    .image {
        position: relative;
        height: 624px;
        width: 100%;
        background: url('<?= $this->Url->assetUrl($hotel->banner) ?>');
        background-size: cover;
        -webkit-box-shadow: inset 0px -150px 43px 0px rgba(0, 0, 0, 0.5);
        box-shadow: inset 0px -150px 43px 0px rgba(0, 0, 0, 0.5);;
    }

    .image:before, .image:after {
        content: '';
        position: absolute;
        opacity: 0.5;
    }

    .image:before {
        top: 0;
        width: 100%;
        height: 100%;
        -webkit-box-shadow: inset 0px -150px 43px 0px rgba(0, 0, 0, 0.5);
        box-shadow: inset 0px -150px 43px 0px rgba(0, 0, 0, 0.5);
    }

    .image:after {
        width: 100%;
        top: 100%;
        background: #000;
    }

    .check-room {
        position: absolute;
        bottom: 0px;
        left: 15px;
    }

    .choose-room {
        position: absolute;
        right: 15px;
        bottom: 0px;
    }

    .panel-heading {
        padding: 10px;
    }

    .panel-body {
        background-color: #f5f5f5 !important;
        border-top: none !important;
        padding-top: 0px !important;
    }

    .panel {
        border: none;
    }

    .list-vin-package {
        max-height: 400px;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    .nav-tabs {
        overflow-x: auto;
        overflow-y: hidden;
        display: -webkit-box;
        display: -moz-box;
    }

    .nav-tabs > li {
        float: none;
    }

    @media screen and (max-width: 1550px) {
        .wrapper-slider::after {
            content: "";
            position: absolute;
            bottom: 70px;
            left: 10%;
            height: 120px;
            width: 80%;
            background-color: #fff;
            opacity: 0.5;
            border-radius: 5px;
        }
    }

    @media screen and (max-width: 1200px) {
        .wrapper-slider::after {
            content: "";
            position: absolute;
            bottom: 70px;
            left: 10%;
            height: 120px;
            width: 80%;
            background-color: #fff;
            opacity: 0.5;
            border-radius: 5px;
        }

        .text-res {
            font-size: 13px;
        }

        .fs22 {
            font-size: 20px;
        }

        .fs16 {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 768px) {
        .choose-room {
            position: unset;
        }

        .modal-lg {
            width: 95%;
        }

        .popup-input-room {
            width: 99%;
        }

        .box-search {
            margin-top: -330px;
            z-index: 999;
            position: relative;
            margin-left: 0px;
            margin-right: 0px;
            -webkit-margin-before: 60px;
        }

        .wrapper-slider::after {
            content: "";
            position: absolute;
            bottom: 70px;
            left: 1%;
            height: 290px;
            width: 98%;
            background-color: #fff;
            opacity: 0.5;
            border-radius: 5px;
        }

        .image {
            height: 400px;
        }
    }

</style>
<div class="no-pad-right no-pad-left wrapper-slider">
    <div class="image">

    </div>
    <div class="container pos-relative">
        <div class="row">
            <div class="col-sm-36">
                <div class="hotel-name">
                    <p class="text-white semi-bold fs36 pc"><?= $hotel->name ?></p>
                    <div class="address pt05 pc">
                        <i class="fas fa-map-marker-alt text-white fs24"></i>
                        <span class="text-white semi-bold fs24"> <?= $hotel->address ?></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-36 col-xs-36">
                <div class="box-search">
                    <div class="box-search">
                        <form action="<?= \Cake\Routing\Router::url('/khach-san-channel') ?>">
                            <div class="row">
                                <div class="col-sm-14 col-xs-36 mb05-sp">
                                    <p class="text-left text-res bold text-center-sp mb05">
                                        Địa điểm
                                    </p>
                                    <div class="">
                                        <div class="input-group br4">
                                            <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggestVin(this, '#auto-complete')" value="<?= $hotel->name ?>" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn">
                                        </div>
                                    </div>
                                    <div class="popup-search-vin br4" id="auto-complete">

                                    </div>
                                </div>
                                <div class="col-sm-8 col-xs-36 mb05-sp">
                                    <p class="text-left mb05 text-center-sp bold text-res">
                                        Nhận phòng - Trả phòng
                                    </p>
                                    <div class='input-group date'>
                                        <input type='text' name="fromDate"
                                               class="form-control popup-voucher border-blue custom-daterange-picker"
                                               placeholder="Thời gian đi" value="<?= $dateParam ?>"/>
                                        <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-xs-36">
                                    <p class="text-left text-res mb05 bold text-center-sp">
                                        Số phòng
                                    </p>
                                    <div class='input-group date w100' onclick="Frontend.showInputRoom(this, '#list-room' , 'all')">
                                        <input type='text' name="num_people" class="form-control w100 popup-voucher border-blue" value="<?= $numPeople ?>">
                                        <span class="input-group-addon">
                                        <i class="fas fa-user-friends"></i>
                                    </span>
                                        <div class="popup-input-room br4" id="input-room">
                                            <div class="col-sm-36 col-xs-36 text-left mt10 mb10">
                                                <div class="row">
                                                    <div class="col-sm-10 col-xs-10" style="margin-top: 3px">
                                                        <span class="text-left fs20">Phòng</span>
                                                    </div>
                                                    <div class="col-sm-26 col-xs-26">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-xs-12 text-center" style="margin-top: 3px">
                                                                <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                            </div>
                                                            <div class="col-sm-4 col-xs-4 text-center no-pad-left no-pad-right">
                                                                <span id="num-room" class="fs24"><?= count($channel_room) ?></span>
                                                            </div>
                                                            <div class="col-sm-12 col-xs-12 text-center" style="margin-top: 3px">
                                                                <span class="room-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="room-container">
                                                <div id="list-input-room">
                                                    <?php foreach ($channel_room as $k => $room): ?>
                                                        <div class="single-input-room">
                                                            <div class="row mt10 mb10">
                                                                <div class="col-sm-36">
                                                                    <p class="text-center">Phòng <?= $k + 1 ?></p>
                                                                </div>
                                                                <div class="col-sm-11 col-xs-11">
                                                                    <div class="row p05">
                                                                        <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                            <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                                        </div>
                                                                        <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                            <span class="fs24 num-room-adult" style="padding-left: 3px"><?= $room['num_adult'] ?></span>
                                                                        </div>
                                                                        <div class="col-sm-12 col-xs-12 text-right" style="margin-top: 3px">
                                                                            <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                                        </div>
                                                                        <div class="col-sm-36 col-xs-36">
                                                                            <p class="text-center ml15">Người lớn</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-11 col-xs-11">
                                                                    <div class="row p05">
                                                                        <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                            <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                                        </div>
                                                                        <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                            <span class="fs24 num-room-child" style="padding-left: 3px"><?= $room['num_child'] ?></span>
                                                                        </div>
                                                                        <div class="col-sm-12 col-xs-12 text-right" style="margin-top: 3px">
                                                                            <span class="room-child-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                                        </div>
                                                                        <div class="col-sm-36 col-xs-36">
                                                                            <p class="text-center ml15">Trẻ em</p>
                                                                            <p class="text-center ml15">(4-12 tuổi)</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-11 col-xs-11">
                                                                    <div class="row p05">
                                                                        <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                            <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                                        </div>
                                                                        <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                            <span class="fs24 num-room-kid" style="padding-left: 3px"><?= $room['num_kid'] ?></span>
                                                                        </div>
                                                                        <div class="col-sm-12 col-xs-12 text-right" style="margin-top: 3px">
                                                                            <span class="room-kid-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5;"></i></span>
                                                                        </div>
                                                                        <div class="col-sm-36 col-xs-36">
                                                                            <p class="text-center ml15">Em bé</p>
                                                                            <p class="text-center ml15">(0-4 tuổi)</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr/>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div id="list-input-room-data">
                                                <?php foreach ($channel_room as $k => $roomData): ?>
                                                    <input type="hidden" name="vin_room[<?= $k ?>][num_adult]" value="<?= $roomData['num_adult'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][num_kid]" value="<?= $roomData['num_kid'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][num_child]" value="<?= $roomData['num_child'] ?>">
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-36">
                                    <button type="button" onclick="Frontend.findAgencyWithoutLoading(this)" class="btn button-orange semi-bold w100 mt25"> Tìm kiếm</button>
                                </div>
                                <div class="col-sm-36">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Combo -->
<!-- Start content -->
<div class="bg-grey">
    <div class="container">
        <div class="row">
            <div class="col-sm-24 col-xs-36 mt10">
                <ul class="nav nav-tabs">
                    <?php for ($i = 0; $i < $numRoom; $i++): ?>
                        <li class="<?= $i == 0 ? 'active' : '' ?>">
                            <a data-toggle="tab" href="#room-<?= $i ?>">Phòng <?= $i + 1 ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-24 col-xs-36">
                <div class="tab-content ">
                    <?php for ($i = 0; $i < $numRoom; $i++): ?>
                        <div id="room-<?= $i ?>" class="tab-pane fade in <?= $i == 0 ? 'active' : '' ?>">
                            <div class="row">
                                <?php foreach ($hotel->channelrooms as $k => $channelRoom): ?>
                                    <div class="col-sm-36 col-xs-36 bg-white mb15">
                                        <div class="row pt10 pb10 row-eq-height">
                                            <div class="col-sm-14 col-xs-36">
                                                <div id="myCarousel-<?= $i ?>-<?= $k ?>" class="carousel slide" data-ride="carousel">
                                                    <!-- Wrapper for slides -->
                                                    <div class="carousel-inner">
                                                        <?php foreach (json_decode($channelRoom->media) as $kimage => $roomImage): ?>
                                                            <div class="item <?= $kimage == 0 ? 'active' : '' ?>">
                                                                <img class="w100" style="object-fit: cover; height: 175px"
                                                                     src="<?= $this->Url->assetUrl($roomImage) ?>" alt="">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <a class="left carousel-control" href="#myCarousel-<?= $i ?>-<?= $k ?>"
                                                       data-slide="prev">
                                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="right carousel-control" href="#myCarousel-<?= $i ?>-<?= $k ?>"
                                                       data-slide="next">
                                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-sm-22 col-xs-36">
                                                <div class="row">
                                                    <div class="col-sm-24 col-xs-24">
                                                        <p class="fs22 fs16-sp"><?= $channelRoom->name ?></p>
                                                    </div>
                                                    <?php if (isset($dataPrice[$channelRoom->room_code]['statusRoom']) && $dataPrice[$channelRoom->room_code]['statusRoom'] == 1): ?>
                                                        <div class="col-sm-12 col-xs-12 mt10">
                                                            <p class="fs12 semi-bold-italic text-red pull-right">Sắp hết
                                                                phòng</p>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="col-sm-12 col-xs-12 mt10">
                                                            <p class="fs12 semi-bold-italic text-red pull-right">Hết
                                                                phòng</p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="check-room">
                                                    <input type="hidden" name="room-<?= $k ?>"
                                                           value='<?= json_encode($channelRoom['information']) ?>'>
                                                    <?php if (isset($dataPrice[$channelRoom->room_code]['statusRoom']) && $dataPrice[$channelRoom->room_code]['statusRoom'] == 1): ?>
                                                        <a class="fs14 text-main-blue" data-toggle="modal"
                                                           data-target="#myModal-<?= $i ?>-<?= $k ?>">Xem phòng</a>
                                                        <div class="modal fade" id="myModal-<?= $i ?>-<?= $k ?>" role="dialog">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal">
                                                                            &times;
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-16">
                                                                                <img class="w100" id="room-img"
                                                                                     src="<?= $this->Url->assetUrl('/' . $channelRoom->thumbnail) ?>"
                                                                                     alt="image">
                                                                            </div>
                                                                            <div class="col-sm-20">
                                                                                <p class="fs24 bold room-name"> <?php echo $channelRoom->name ?> </p>
                                                                                <p class="room-information"> <?php echo $channelRoom->area . 'm2 | ' . $channelRoom->view_type ?> </p>
                                                                                <p class="mt10 room-description"><?php echo $channelRoom->description ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="choose-room">
                                                    <?php if (isset($dataPrice[$channelRoom->room_code]['statusRoom']) && $dataPrice[$channelRoom->room_code]['statusRoom'] == 1): ?>
                                                        <div class="vin-price-room" id="check-room-<?= $i ?>-<?= $k ?>-price">
                                                            <p class="fs16 text-right">Chỉ từ <span
                                                                    class="text-main-blue fs18"><?= number_format($dataPrice[$channelRoom->room_code]['min_price']) . ' ' . $dataPrice[$channelRoom->room_code]['currency'] ?></span>
                                                            </p>
                                                            <p class="text-right mb10">/<?= $dateDiff->days ?> đêm</p>
                                                        </div>
                                                        <?php
                                                        $dataJson = [];
                                                        $dataJson['name'] = $channelRoom->name;
                                                        ?>
                                                        <input type="hidden" name="choose-room-<?= $k ?>"
                                                               value='<?= json_encode($dataJson) ?>'>
                                                        <button class="btn btn-blue pull-right" data-toggle="collapse"
                                                                data-target="#check-room-<?= $i ?>-<?= $k ?>"
                                                                onclick="Frontend.hiddenRoomPrice(this)">Chọn phòng
                                                        </button>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse list-vin-package" id="check-room-<?= $i ?>-<?= $k ?>">
                                            <?php if (isset($dataPrice[$channelRoom->room_code]['statusRoom']) && $dataPrice[$channelRoom->room_code]['statusRoom'] == 1): ?>
                                                <?php foreach ($channelRoom->channelrateplanes as $packageKey => $package): ?>
                                                    <?php if (isset($dataPrice[$channelRoom->room_code][$package->rateplan_code]) && $dataPrice[$channelRoom->room_code][$package->rateplan_code]): ?>
                                                        <div class="single-vin-room" >
                                                            <hr class="mt10">
                                                            <div class="row mt10 mb10">
                                                                <div class="col-sm-2 col-xs-4 no-pad-right">
                                                                    <p class="fs16 mb15 text-light-blue">
                                                                        <?php
                                                                        if ($package->sale_revenue_type == 1 ){
                                                                            $price = $dataPrice[$channelRoom->room_code][$package->rateplan_code]['price'] * ($package->sale_revenue/100 +1);
                                                                            $saleRevenue = $dataPrice[$channelRoom->room_code][$package->rateplan_code]['price'] * $package->sale_revenue/100;
                                                                        }
                                                                        else{
                                                                            $price = $dataPrice[$channelRoom->room_code][$package->rateplan_code]['price'] + $package->sale_revenue;
                                                                            $saleRevenue = $package->sale_revenue;
                                                                        }
                                                                        ?>
                                                                        <input type="radio" class="iCheck channel-room-pick"
                                                                               name="package[<?= $i ?>]"
                                                                               data-rate-plan-code="<?= $package->rateplan_code ?>"
                                                                               data-room-type-code="<?= $channelRoom->room_code ?>"
                                                                               data-sale-revenue="<?= $saleRevenue ?>"
                                                                               data-currency="<?= $dataPrice[$channelRoom->room_code]['currency'] ?>"
                                                                               data-package-name="<?= $package->name ?>"
                                                                               data-package-code="<?= $package->rateplan_code ?>"
                                                                               data-rateplan-id="<?= $package->id ?>"
                                                                               data-room-index="<?= $i ?>"
                                                                               data-room-key="<?= $k ?>"
                                                                               data-package-pice="<?= number_format($price) ?>"
                                                                               data-package-default-price="<?= $dataPrice[$channelRoom->room_code][$package->rateplan_code]['price'] ?>"
                                                                               data-package-left="<?= $dataPrice[$channelRoom->room_code]['quantity'] ?>"
                                                                               data-date-range='<?= $dataPrice[$channelRoom->room_code][$package->rateplan_code]['DateRange'] ?>'
                                                                        >
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-24 col-xs-32">
                                                                    <p class="fs18 fs16-sp"
                                                                       style="text-decoration: underline"><?= $package->name ?></p>
                                                                </div>
                                                                <div class="col-sm-9 col-xs-36">
                                                                    <div class="pc row pull-right">
                                                                        <p class="col-12 fs18 "><?= number_format($price) . ' ' . $dataPrice[$channelRoom->room_code]['currency'] ?></p>
                                                                        <p class="col-12 fs16 pull-right"> / <?= $dateDiff->days ?> đêm</p>
                                                                    </div>
                                                                    <div class="sp">
                                                                        <p class="fs14 mt10 mb10 pull-right"><span
                                                                                class="bold"><?= number_format($price) . ' ' . $dataPrice[$channelRoom->room_code][$package->rateplan_code]['currency'] ?></span><span
                                                                                class="fs12">/<?= $dateDiff->days ?> đêm</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-offset-2 col-sm-25 col-xs-36">
                                                                    <p> <?= $package->description ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="col-sm-12 col-xs-36">
                <div class="row">
                    <div class="col-sm-36 col-xs-36">
                        <div class="bg-white">
                            <div class="p10">
                                <div class="information-header">
                                    <p class="text-main-blue">Thông tin đặt phòng</p>
                                    <p class="semi-bold mt10"><?= $hotel->name ?></p>
                                    <div class="row mt10">
                                        <div class="col-sm-36">
                                            <div class="row">
                                                <div class="col-sm-6 text-light-blue">
                                                    <input type="checkbox" data-hotel-slug="<?= $hotel->slug ?>"
                                                           id="checkMutiChooseRoom" class="iCheck"
                                                           name="channel_booking_type" value="2">
                                                </div>
                                                <div class="col-sm-30 pl0">
                                                    <p class="fs17 fs16-sp ">Đặt nhiều gói trong một đơn hàng</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--                                        <div class="col-sm-18">-->
                                        <!--                                            <div class="row">-->
                                        <!--                                                <div class="col-sm-6">-->
                                        <!--                                                    <input type="radio" class="iCheck" data-hotel-slug="-->
                                        <!--" name="vin_booking_type" value="2">-->
                                        <!--                                                </div>-->
                                        <!--                                                <div class="col-sm-30">-->
                                        <!--                                                    <p class="fs18 fs16-sp">Combo</p>-->
                                        <!--                                                </div>-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                    </div>
                                    <p class="w100 mt10 fs12">
                                        <span><?= date('d/m/Y', strtotime($fromDate)) ?></span> -
                                        <span><?= date('d/m/Y', strtotime($endDate)) ?></span> <span
                                            class="pull-right semi-bold"><?= $dateDiff->days + 1 ?> ngày <?= $dateDiff->days ?> đêm</span>
                                    </p>
                                    <p class="mt05 fs12"><?= $numAdult ?> Người lớn, <?= $numChild ?> trẻ
                                        em, <?= $numKid ?> em bé</p>
                                    <p class="mt05 fs12"><?= $numRoom ?> phòng</p>
                                </div>
                                <div class="detail-room-information mt10">
                                    <div class="panel-group" id="accordion-term">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed semi-bold fs16"
                                                       data-toggle="collapse" data-parent="#accordion-term"
                                                       href="#collapseTerm">
                                                        Thông tin phòng
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTerm" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <?php for ($i = 0; $i < $numRoom; $i++): ?>
                                                            <div class="single-room-detail" data-channelroom-price="0"
                                                                 data-channelroom-revenue="0" id="channel-room-<?= $i ?>"
                                                                 data-room-number="<?= $i ?>">

                                                            </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-room-information row">
                                    <div class="col-sm-36 col-xs-36">
                                        <p class="pull-right">
                                            Tổng cộng
                                        </p>
                                    </div>
                                    <div class="col-sm-36 col-xs-36">
                                        <p class="pull-right text-orange fs24 semi-bold" id="">
                                            <span id="totalChannelBookingPrice">0</span> <?= $currency ?>
                                        </p>
                                    </div>
<!--                                    --><?php //if ($this->request->session()->read('Auth.User.role_id') == 3): ?>
<!--                                        <div class="col-sm-36 col-xs-36 mt10">-->
<!--                                            <p class="pull-right">-->
<!--                                                Chiết khấu Đại Lý-->
<!--                                            </p>-->
<!--                                        </div>-->
<!--                                        <div class="col-sm-36 col-xs-36">-->
<!--                                            <p class="pull-right text-orange fs24 semi-bold" id="">-->
<!--                                                <span id="totalChannelBookingRevenue">0</span> VNĐ-->
<!--                                            </p>-->
<!--                                        </div>-->
<!--                                        <div class="col-sm-36 col-xs-36 mt10">-->
<!--                                            <p class="pull-right">-->
<!--                                                Đại lý phải thanh toán-->
<!--                                            </p>-->
<!--                                        </div>-->
<!--                                        <div class="col-sm-36 col-xs-36">-->
<!--                                            <p class="pull-right text-orange fs24 semi-bold" id="">-->
<!--                                                <span id="totalAgencyPayChannelBooking">0</span> VNĐ-->
<!--                                            </p>-->
<!--                                        </div>-->
<!--                                    --><?php //endif; ?>
                                    <div class="col-sm-36 col-xs-36">
                                        <p class="pull-right fs13">(Giá đã bao gồm phí dịch vụ và thuế GTGT)</p>
                                    </div>
                                    <?= $this->Form->create(null, ['id' => 'channelBookingRoom', 'method' => 'post', 'url' => '/khach-san-channel/' . $hotel->slug . '/booking/']) ?>
                                    <input type="hidden" name="channel_booking_type" value="1">
                                    <div class="list-booking-vin-room">
                                        <input type="hidden" name="hotel_id" value="<?= $hotel->id ?>">
                                        <input type="hidden" name="num_adult" value="<?= $numAdult ?>">
                                        <input type="hidden" name="num_child" value="<?= $numChild ?>">
                                        <input type="hidden" name="num_kid" value="<?= $numKid ?>">
                                        <input type="hidden" name="num_room" value="<?= $numRoom ?>">
                                        <input type="hidden" name="fromDate" value="<?= $fromDate ?>">
                                        <input type="hidden" name="end_date" value="<?= $endDate ?>">
                                        <input type="hidden" name="currency" value="<?= $currency ?>">
                                        <?php for ($indexRoom = 0; $indexRoom < $numRoom; $indexRoom++): ?>
                                            <div class="single-booking-channel-room-<?= $indexRoom ?> vin-bk-room">

                                            </div>
                                        <?php endfor; ?>
                                        <?php foreach ($channel_room as $k => $roomData): ?>
                                            <input type="hidden" name="channel_room[<?= $k ?>][num_adult]" value="<?= $roomData['num_adult'] ?>">
                                            <input type="hidden" name="channel_room[<?= $k ?>][num_kid]" value="<?= $roomData['num_kid'] ?>">
                                            <input type="hidden" name="channel_room[<?= $k ?>][num_child]" value="<?= $roomData['num_child'] ?>">
                                        <?php endforeach; ?>
                                    </div>
                                    <?= $this->Form->end() ?>
                                    <?php if (count($hotel->channelrooms) > 0): ?>
                                        <div class="col-sm-36 col-xs-36 mt10">
                                            <button
                                                class="btn btn-request text-white full-width full-height btnChannelBooking"
                                                data-num-room="<?= $numRoom  ?> " <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?>>
                                                <span class="semi-bold fs16">TIẾP TỤC ĐƠN HÀNG</span>
                                                <br/>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt20">
            <div class="col-sm-36 col-xs-36 bg-white">
                <p class="fs26 fs22-sp text-center mt10 bold">
                    <?= $hotel->name ?>
                </p>
                <div class="row mt20">
                    <div class="col-sm-18 col-xs-36">
                        <div class="row">
                            <div class="col-sm-1 col-xs-1 mb10-sp">
                                <i class="fas fa-map-marker-alt fs16"></i>
                            </div>
                            <div class="col-sm-33 col-xs-33 mb10-sp">
                                <p class="fs16"><?= $hotel->address ?></p>
                            </div>
                            <div class="col-sm-35 col-xs-35 mb10-sp">
                                <div class="google-map">
                                    <div id="map" style="width:100%;height:350px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-18 col-xs-36 fs16">
                        <?php $captions = json_decode($hotel->caption, true) ?>
                        <?php foreach ($captions as $k => $caption): ?>
                            <?= $caption['content'] ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="row row-eq-height mb30 mt20">
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
                                            <div class="<?= $class ?> "
                                                 data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                                <img class="img-responsive"
                                                     src="<?= $this->Url->assetUrl('/' . $image) ?>">
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
        <?php $vinpearlCaption = json_decode($hotel->vinhms_caption, true) ?>
        <?php if ($vinpearlCaption): ?>
            <div class="row mt20">
                <div class="col-sm-36 col-xs-36 bg-white mb10 pb10">
                    <p class="fs26 fs22-sp text-center mt10 bold">
                        <?= isset($vinpearlCaption['title']) ? $vinpearlCaption['title'] : "" ?>
                    </p>
                    <div class="pc">
                        <?php if (isset($vinpearlCaption['caption'])): ?>
                            <?php foreach ($vinpearlCaption['caption'] as $k => $singleVinCaption): ?>
                                <?php if ($k % 2 == 0): ?>
                                    <div class="row mt10 row-eq-height">
                                        <div class="col-sm-18 col-xs-36">
                                            <img class="w100"
                                                 src="<?= $this->Url->assetUrl($singleVinCaption['image']) ?>"
                                                 alt="">
                                        </div>
                                        <div class="col-sm-18 col-xs-36">
                                            <?= $singleVinCaption['content'] ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row row-eq-height">
                                        <div class="col-sm-18 col-xs-36">
                                            <?= $singleVinCaption['content'] ?>
                                        </div>
                                        <div class="col-sm-18 col-xs-36">
                                            <img class="w100"
                                                 src="<?= $this->Url->assetUrl($singleVinCaption['image']) ?>"
                                                 alt="">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="sp">
                        <?php if (isset($vinpearlCaption['caption'])): ?>
                            <?php foreach ($vinpearlCaption['caption'] as $k => $singleVinCaption): ?>
                                <div class="row mt10 row-eq-height">
                                    <div class="col-sm-18 col-xs-36">
                                        <img class="w100"
                                             src="<?= $this->Url->assetUrl($singleVinCaption['image']) ?>" alt="">
                                    </div>
                                    <div class="col-sm-18 col-xs-36">
                                        <?= $singleVinCaption['content'] ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php $vinpearlMeeting = json_decode($hotel->vinhms_meeting, true) ?>
        <?php if (!empty($vinpearlMeeting['tittle']) && !empty($vinpearlMeeting['content']) && !empty($vinpearlMeeting['media'])): ?>
            <div class="row mt20 mb20">
                <div class="col-sm-36 col-xs-36 bg-white mb10 pb10">
                    <p class="fs26 fs22-sp text-center mt10 bold">
                        <?= isset($vinpearlMeeting['tittle']) ? $vinpearlMeeting['tittle'] : "" ?>
                    </p>
                    <div class="mt20 fs16">
                        <?= isset($vinpearlMeeting['content']) ? $vinpearlMeeting['content'] : '' ?>
                    </div>
                    <div class="combo-slider mt15">
                        <div class="box-image">
                            <div class="imgs_gird grid_6 lightgallery2">
                                <?php
                                $list_images = json_decode($vinpearlMeeting['media'], true);
                                if ($list_images) {
                                    $other = count($list_images) - 6;
                                }
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
                                        <div class="<?= $class ?> "
                                             data-src="<?= $this->Url->assetUrl('/' . $image) ?>">
                                            <img class="img-responsive"
                                                 src="<?= $this->Url->assetUrl('/' . $image) ?>">
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
        <?php endif; ?>
        <div class="row mt20 mb20">
            <div class="col-sm-36 col-xs-36 bg-white mb10 pb10 fs16">
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
</div>
<!-- End content -->
<!-- Map Zone -->
<!--<link href="https://leafletjs-cdn.s3.amazonaws.com/content/leaflet/master/leaflet.css" rel="stylesheet" type="text/css"/>-->
<!--<script type="text/javascript" src="https://leafletjs-cdn.s3.amazonaws.com/content/leaflet/master/leaflet.js"></script>-->
<!--<script type="text/javascript" src="https://tiles.unwiredmaps.com/js/leaflet-unwired.js"></script>-->
<script type="text/javascript">
    //var lat = "<?//= $hotel->lat ?>//";
    //var lon = "<?//= $hotel->lon ?>//";
    //lat = parseFloat(lat);
    //lon = parseFloat(lon);
    //// Maps access token goes here
    //var key = '<?//= LOCATIONIQ_ACCESS_TOKEN?>//';
    //
    //// Add layers that we need to the map
    //var streets = L.tileLayer.Unwired({key: key, scheme: "streets"});
    //
    //// Initialize the map
    //var map = L.map('map', {
    //    center: [lat, lon], // Map loads with this location as center
    //    zoom: 18,
    //    scrollWheelZoom: false,
    //    layers: [streets] // Show 'streets' by default
    //});
    //
    //// Add the 'scale' control
    //L.control.scale().addTo(map);
    //
    //// Add the 'layers' control
    //L.control.layers({
    //    "Streets": streets
    //}).addTo(map);
    //
    //// Add a 'marker'
    //var marker = L.marker([lat, lon]).addTo(map);


</script>
<!-- End Map Zone -->
<?= $this->element('Front/Popup/description') ?>
<?= $this->element('Front/Popup/category') ?>
<?= $this->element('Front/Popup/bookinghotel') ?>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-16">
                        <img class="w100" id="room-img" src="" alt="">
                    </div>
                    <div class="col-sm-20">
                        <p class="fs24 bold room-name"></p>
                        <p class="room-information"></p>
                        <p class="mt10 room-description"></p>
                    </div>
                    <div class="col-sm-36 mt10">
                        <div class="row list-room-accessories">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailPackageModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-36 col-xs-36">
                        <h3 class="bold">Tên gói</h3>
                        <p><span class="package-name"></span> - <span class="package-code"></span></p>
                        <h3 class="mt10 bold">Chính sách mua gói</h3>
                        <p class="guarantee-policy"></p>
                        <h3 class="mt10 bold">Chính sách hoàn hủy</h3>
                        <p class="cancel-policy"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
