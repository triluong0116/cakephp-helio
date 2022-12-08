<!-- Header Slider -->
<style>
    .wrapper-slider::after {
        content: "";
        position: absolute;
        bottom: 320px;
        left: 17%;
        height: 100px;
        width: 66%;
        background-color: #fff;
        opacity: 0.5;
        border-radius: 15px;
    }

    .box-search {
        margin-top: -400px;
        z-index: 999;
        position: relative;
    }

    .popup-input-room {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    @media screen and (max-width: 1550px) {
        .wrapper-slider::after {
            content: "";
            position: absolute;
            bottom: 410px;
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
            bottom: 410px;
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
    }

    @media screen and (max-width: 768px) {
        .popup-input-room {
            width: 99%;
        }
        .image-search {
            overflow-x: hidden;
        }
        .box-search {
            margin-top: -460px;
            z-index: 999;
            position: relative;
        }
        .wrapper-slider::after {
            content: "";
            position: absolute;
            bottom: 200px;
            left: 1%;
            height: 265px;
            width: 98%;
            background-color: #fff;
            opacity: 0.5;
            border-radius: 5px;
        }
    }
</style>
<div class="no-pad-right no-pad-left wrapper-slider">
    <li class="image-search pc"><img class="w100" src="<?= $this->Url->assetUrl('frontend/img/banner.gif') ?>"/></li>
    <li class="image-search sp"><img class="w100" src="<?= $this->Url->assetUrl('frontend/img/mobile-banner.jpg') ?>"/></li>
    <div class="container pos-relative">
        <div class="row">
            <div class="col-sm-36 col-xs-36">
                <div class="box-search">
                    <form action="<?= \Cake\Routing\Router::url('/khach-san-vinpearl') ?>">
                        <div class="row">
                            <div class="col-sm-14 col-xs-36 mb10-sp">
                                <p class="text-left mb05 bold text-center-sp">
                                    Địa điểm
                                </p>
                                <div class="">
                                    <div class="input-group br4">
                                        <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggestVin(this, '#auto-complete')" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn Vin Group">
                                    </div>
                                </div>
                                <div class="popup-search-vin br4" id="auto-complete">

                                </div>
                            </div>
                            <div class="col-sm-8 col-xs-36 mb10-sp">
                                <p class="text-left mb05 bold text-center-sp">
                                    Nhận phòng - Trả phòng
                                </p>
                                <div class='input-group date'>
                                    <input type='text' name="fromDate"
                                           class="form-control popup-voucher border-blue custom-daterange-picker"
                                           placeholder="Thời gian đi" value="<?= $date ?>"/>
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-8 col-xs-36">
                                <p class="text-left mb05 bold text-center-sp">
                                    Số phòng
                                </p>
                                <div class='input-group date w100' onclick="Frontend.showInputRoom(this, '#list-room' , 'all')">
                                    <input type='text' name="num_people" class="form-control w100 popup-voucher border-blue" value="1 Phòng-1NL-0TE-0EB">
                                    <span class="input-group-addon">
                                        <i class="fas fa-user-friends"></i>
                                    </span>
                                    <div class="popup-input-room br4" id="input-room">
                                        <div class="col-sm-36 text-left mt10 mb10">
                                            <div class="row">
                                                <div class="col-sm-10 col-xs-10" style="margin-top: 3px">
                                                    <span class="text-left fs20">Phòng</span>
                                                </div>
                                                <div class="col-sm-26 col-xs-26">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-xs-12 text-center" style="margin-top: 3px">
                                                            <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                        </div>
                                                        <div class="col-sm-4 col-xs-4 text-center no-pad-left no-pad-right">
                                                            <span id="num-room" class="fs24">1</span>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12 text-center" style="margin-top: 3px">
                                                            <span class="room-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="room-container">
                                            <div id="list-input-room">
                                                <div class="single-input-room">
                                                    <div class="row mt10 mb10">
                                                        <div class="col-sm-36 col-xs-36">
                                                            <p class="text-center">Phòng 1</p>
                                                        </div>
                                                        <div class="col-sm-11 col-xs-11">
                                                            <div class="row p05">
                                                                <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-adult" style="padding-left: 3px">1</span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-right" style="margin-top: 3px">
                                                                    <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-36 col-xs-36">
                                                                    <p class="text-center ml15">Người lớn</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-11 col-xs-11">
                                                            <div class="row p05">
                                                                <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-child" style="padding-left: 3px">0</span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-right" style="margin-top: 3px">
                                                                    <span class="room-child-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
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
                                                                    <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-kid" style="padding-left: 3px">0</span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-right" style="margin-top: 3px">
                                                                    <span class="room-kid-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
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
                                            </div>
                                        </div>
                                        <div id="list-input-room-data">
                                            <input type="hidden" name="vin_room[0][num_adult]" value="1">
                                            <input type="hidden" name="vin_room[0][num_kid]" value="0">
                                            <input type="hidden" name="vin_room[0][num_child]" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
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

