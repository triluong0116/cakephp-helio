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
    }

    .image {
        position: relative;
        height: 624px;
        width: 100%;
        background: url('<?= $this->Url->assetUrl('/img/banner.jpeg') ?>');
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
</style>
<div class="no-pad-right no-pad-left wrapper-slider pc">
    <div class="container">
        <div class="row">
            <div class="col-sm-36 col-xs-36">
                <div class="pc bg-grey p10">
                    <form id="saleBookingForm" class="mt20">
                        <div class="row">
                            <div class="col-sm-16">
                                <label class="text-left">
                                    Địa điểm
                                </label>
                                <div class="">
                                    <div class="input-group br4">
                                        <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggestVin(this, '#auto-complete')" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn">
                                    </div>
                                </div>
                                <div class="popup-search-vin br4" id="auto-complete">

                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label class="text-left">
                                    Nhận phòng - Trả phòng
                                </label>
                                <div class='input-group date'>
                                    <input type='text' name="fromDate"
                                           class="form-control popup-voucher border-blue custom-daterange-picker" value="<?= $date ?>"
                                           placeholder="Thời gian đi"/>
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label class="text-left">
                                    Số phòng
                                </label>
                                <div class='input-group date w100' onclick="Frontend.showInputRoom(this, '#list-room' , 'all')">
                                    <input type='text' name="num_people" class="form-control w100 popup-voucher border-blue" value="<?= $numPeople ?>">
                                    <span class="input-group-addon">
                                        <i class="fas fa-user-friends"></i>
                                    </span>
                                    <div class="popup-input-room br4" id="input-room">
                                        <div class="col-sm-36 text-left mt10 mb10">
                                            <div class="row">
                                                <div class="col-sm-10" style="margin-top: 3px">
                                                    <span class="text-left fs20">Phòng</span>
                                                </div>
                                                <div class="col-sm-26">
                                                    <div class="row">
                                                        <div class="col-sm-12 text-center" style="margin-top: 3px">
                                                            <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                        </div>
                                                        <div class="col-sm-4 text-center no-pad-left no-pad-right">
                                                            <span id="num-room" class="fs24"><?= count($dataRoom) ?></span>
                                                        </div>
                                                        <div class="col-sm-12 text-center" style="margin-top: 3px">
                                                            <span class="room-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="room-container">
                                            <div id="list-input-room">
                                                <?php foreach ($dataRoom as $k => $room): ?>
                                                    <div class="single-input-room">
                                                        <div class="row mt10 mb10">
                                                            <div class="col-sm-36">
                                                                <p class="text-center">Phòng <?= $k + 1 ?></p>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-adult" style="padding-left: 3px"><?= $room['num_adult'] ?></span>
                                                                </div>
                                                                <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                    <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-36">
                                                                    <p class="text-center">Người lớn</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-child" style="padding-left: 3px"><?= $room['num_child'] ?></span>
                                                                </div>
                                                                <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                    <span class="room-child-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-36">
                                                                    <p class="text-center">Trẻ em</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-kid" style="padding-left: 3px"><?= $room['num_kid'] ?></span>
                                                                </div>
                                                                <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                    <span class="room-kid-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-36">
                                                                    <p class="room-kid-center">Em bé</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button onclick="Frontend.saleShowData()" type="button" class="btn btn-success mt25">
                                    Áp dụng
                                </button>
                            </div>
                            <div class="col-sm-36">
                            </div>
                        </div>
                        <div id="list-input-room-data">
                            <?php foreach ($dataRoom as $k => $roomData): ?>
                                <input type="hidden" name="vin_room[<?= $k ?>][num_adult]" value="<?= $roomData['num_adult'] ?>">
                                <input type="hidden" name="vin_room[<?= $k ?>][num_kid]" value="<?= $roomData['num_kid'] ?>">
                                <input type="hidden" name="vin_room[<?= $k ?>][num_child]" value="<?= $roomData['num_child'] ?>">
                            <?php endforeach; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Combo -->
<!-- Start content -->
<div class="bg-grey container" id="body-sale-booking-vin">
</div>
<!-- End content -->
<!-- Map Zone -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
