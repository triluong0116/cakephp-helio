<!-- Header Slider -->
<style>
    .wrapper-slider .bx-wrapper {
        height: 450px;
    }
    .wrapper-slider::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        height: 180px;
        width: 100%;
        background-color: #000;
        opacity: 0.5;
    }
    .box-search {
        margin-top: -150px;
        z-index: 999;
        position: relative;
    }
    @media screen and (max-width: 768px) {
        .popup-input-room {
            width: 99%;
        }
        .image-search {
            overflow-x: hidden;
        }
        .box-search {
            margin-top: -615px;
            z-index: 999;
            position: relative;
        }
        .wrapper-slider::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 1%;
            height: 275px;
            width: 98%;
            background-color: #fff;
            opacity: 0.5;
            border-radius: 5px;
            z-index: -1;
        }
    }
</style>
<div class="no-pad-right no-pad-left wrapper-slider pc">
    <ul class="bxslider-home slider text-center" style="display: none;">
        <li><img src="<?= $this->Url->assetUrl($config->value) ?>"/></li>
    </ul>
    <div class="container pos-relative">
        <!--        <div class="row">-->
        <!--            <div class="col-sm-36">-->
        <!--                <div class="bx-pager">-->
        <!--                    <a data-slide-index="0"></a>-->
        <!--                    <a data-slide-index="1"></a>-->
        <!--                    <a data-slide-index="2"></a>-->
        <!--                    <a data-slide-index="3"></a>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="row">
            <div class="col-sm-36">
                <div class="box-search pc">
                    <div class="nav-center">
                        <ul class="nav nav-tabs">
                            <li class="active"><a class="text-white semi-bold" data-toggle="tab" href="#home">Tổng hợp</a></li>
                            <li><a class="text-white semi-bold" data-toggle="tab" href="#menu1">Khách sạn</a></li>
                            <li><a class="text-white semi-bold" data-toggle="tab" href="#menu2">Vinpearl</a></li>
                            <li><a class="text-white semi-bold" data-toggle="tab" href="#menu3">Landtour</a></li>
<!--                            <li><a class="text-white semi-bold" data-toggle="tab" href="#menu4">Voucher</a></li>-->
                        </ul>

                        <div class="tab-content mt15">
                            <div id="home" class="tab-pane fade in active">
                                <form action="<?= \Cake\Routing\Router::url('/tim-kiem') ?>">
                                    <div class="row">
                                        <div class="col-sm-14 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Địa điểm
                                            </label>
                                            <div class="">
                                                <div class="input-group br4">
                                                    <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggest(this, '#auto-complete', 'all')" name="keyword" placeholder="Nhập tên khách sạn, địa điểm du lịch">
                                                </div>
                                            </div>
                                            <div class="popup-search-vin br4" id="auto-complete">

                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Nhận phòng - Trả phòng
                                            </label>
                                            <div class='input-group date'>
                                                <input type='text' name="fromDate"
                                                       class="form-control popup-voucher border-blue custom-daterange-picker"
                                                       placeholder="Thời gian đi" value=""/>
                                                <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Số phòng
                                            </label>
                                            <div class='input-group date w100' onclick="Frontend.showInputRoom(this, '#list-room' , 'all')">
                                                <input type='text' name="num_people" class="form-control w100 popup-voucher border-blue" value="1 Phòng-1NL-0TE-0EB">
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
                                                                        <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                    </div>
                                                                    <div class="col-sm-4 text-center no-pad-left no-pad-right">
                                                                        <span id="num-room" class="fs24">1</span>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center" style="margin-top: 3px">
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
                                                                    <div class="col-sm-36">
                                                                        <p class="text-center">Phòng 1</p>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-adult" style="padding-left: 3px">1</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Người lớn</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-child" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-child-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Trẻ em</p>
                                                                                <p class="text-center ml15">(4-12 tuổi)</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-kid" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-kid-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
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
                            <div id="menu1" class="tab-pane fade">
                                <form action="<?= \Cake\Routing\Router::url('/tim-kiem') ?>">
                                    <div class="row">
                                        <div class="col-sm-14 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Địa điểm
                                            </label>
                                            <div class="">
                                                <div class="input-group br4">
                                                    <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggest(this, '#auto-complete-hotel', <?= HOTEL ?>)" name="keyword" placeholder="Nhập tên khách sạn, địa điểm du lịch">
                                                </div>
                                            </div>
                                            <div class="popup-search-vin br4" id="auto-complete-hotel">

                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Nhận phòng - Trả phòng
                                            </label>
                                            <div class='input-group date'>
                                                <input type='text' name="fromDate"
                                                       class="form-control popup-voucher border-blue custom-daterange-picker"
                                                       placeholder="Thời gian đi" value=""/>
                                                <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Số phòng
                                            </label>
                                            <div class='input-group date w100' onclick="Frontend.showInputRoom(this, '#list-room' , 'all')">
                                                <input type='text' name="num_people" class="form-control w100 popup-voucher border-blue" value="1 Phòng-1NL-0TE-0EB">
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
                                                                        <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                    </div>
                                                                    <div class="col-sm-4 text-center no-pad-left no-pad-right">
                                                                        <span id="num-room" class="fs24">1</span>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center" style="margin-top: 3px">
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
                                                                    <div class="col-sm-36">
                                                                        <p class="text-center">Phòng 1</p>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-adult" style="padding-left: 3px">1</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Người lớn</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-child" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-child-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Trẻ em</p>
                                                                                <p class="text-center ml15">(4-12 tuổi)</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-kid" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-kid-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-36">
                                            <button type="button" onclick="Frontend.findAgencyWithoutLoading(this)" class="btn button-orange semi-bold w100 mt25"> Tìm kiếm</button>
                                        </div>
                                        <div class="col-sm-36">
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?= HOTEL ?>">
                                </form>
                            </div>
                            <div id="menu2" class="tab-pane fade">
                                <form action="<?= \Cake\Routing\Router::url('/khach-san-vinpearl') ?>">
                                    <div class="row">
                                        <div class="col-sm-14 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Địa điểm
                                            </label>
                                            <div class="">
                                                <div class="input-group br4">
                                                    <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggestVin(this, '#auto-complete-vin')" name="keyword" placeholder="Nhập tên khách sạn, địa điểm du lịch">
                                                </div>
                                            </div>
                                            <div class="popup-search-vin br4" id="auto-complete-vin">

                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Nhận phòng - Trả phòng
                                            </label>
                                            <div class='input-group date'>
                                                <input type='text' name="fromDate"
                                                       class="form-control popup-voucher border-blue custom-daterange-picker"
                                                       placeholder="Thời gian đi" value=""/>
                                                <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Số phòng
                                            </label>
                                            <div class='input-group date w100' onclick="Frontend.showInputRoom(this, '#list-room' , 'all')">
                                                <input type='text' name="num_people" class="form-control w100 popup-voucher border-blue" value="1 Phòng-1NL-0TE-0EB">
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
                                                                        <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                    </div>
                                                                    <div class="col-sm-4 text-center no-pad-left no-pad-right">
                                                                        <span id="num-room" class="fs24">1</span>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center" style="margin-top: 3px">
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
                                                                    <div class="col-sm-36">
                                                                        <p class="text-center">Phòng 1</p>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-adult" style="padding-left: 3px">1</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Người lớn</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-child" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-child-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Trẻ em</p>
                                                                                <p class="text-center ml15">(4-12 tuổi)</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-kid" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-kid-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
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
                                        <div class="col-sm-6 col-xs-36">
                                            <button type="button" onclick="Frontend.findAgencyWithoutLoading(this)" class="btn button-orange semi-bold w100 mt25"> Tìm kiếm</button>
                                        </div>
                                        <div class="col-sm-36">
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?= HOMESTAY ?>">
                                </form>
                            </div>
                            <div id="menu3" class="tab-pane fade">
                                <form action="<?= \Cake\Routing\Router::url('/tim-kiem') ?>">
                                    <div class="row">
                                        <div class="col-sm-14 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Địa điểm
                                            </label>
                                            <div class="">
                                                <div class="input-group br4">
                                                    <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggest(this, '#auto-complete-landtour', <?= LANDTOUR ?>)" name="keyword" placeholder="Nhập tên khách sạn, địa điểm du lịch">
                                                </div>
                                            </div>
                                            <div class="popup-search-vin br4" id="auto-complete-landtour">

                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Ngày đi
                                            </label>
                                            <div class='input-group date'>
                                                <input type='text' name="fromDate"
                                                       class="form-control popup-voucher border-blue datepicker"
                                                       placeholder="Thời gian đi" value=""/>
                                                <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-xs-36">
                                            <label class="text-left text-res text-white">
                                                Số Người
                                            </label>
                                            <div class='input-group date w100' onclick="Frontend.showInputRoom(this, '#list-room' , 'all')">
                                                <input type='text' name="num_people" class="form-control w100 popup-voucher border-blue" value="1NL-0TE-0EB">
                                                <span class="input-group-addon">
                                        <i class="fas fa-user-friends"></i>
                                    </span>
                                                <div class="popup-input-room br4" id="input-room">
                                                    <div id="room-container">
                                                        <div id="list-input-room">
                                                            <div class="single-input-room">
                                                                <div class="row mt10 mb10">
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-adult" style="padding-left: 3px">1</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Người lớn</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-child" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-child-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
                                                                                <p class="text-center ml15">Trẻ em</p>
                                                                                <p class="text-center ml15">(4-12 tuổi)</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-11">
                                                                        <div class="row p05">
                                                                            <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                                <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center no-pad-right">
                                                                                <span class="fs24 num-room-kid" style="padding-left: 3px">0</span>
                                                                            </div>
                                                                            <div class="col-sm-12 text-right" style="margin-top: 3px">
                                                                                <span class="room-kid-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                            </div>
                                                                            <div class="col-sm-36">
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-36">
                                            <button type="button" onclick="Frontend.findAgencyWithoutLoading(this)" class="btn button-orange semi-bold w100 mt25"> Tìm kiếm</button>
                                        </div>
                                        <div class="col-sm-36">
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?= LANDTOUR ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="no-pad-right no-pad-left mt10 sp" style="background: url(<?= ".." . $this->Url->assetUrl($config->value) ?>);-webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;">
    <div class="container pos-relative">
        <div class="row">
            <div class="col-xs-36">
                <div class="sp">
                    <div class="row">
                        <div class="col-xs-36 mt20 mb20">
                            <center><span class="text-white box-underline-center pb10 fs24 semi-bold">TÌM KHÁCH SẠN GIÁ TỐT</span></center>
                        </div>
                        <div class="box-search sp">
                            <div class="row">
                                <div class="col-xs-36  col-sm-offset-6 col-sm-20">
                                    <ul class="nav nav-tabs nav-tabs-responsive ml10-sp mr10-sp mb05-sp">
                                        <li class="active"><a class="text-white semi-bold" data-toggle="tab" href="#home-sp">Tổng hợp</a></li>
                                        <li><a class="text-white semi-bold" data-toggle="tab" href="#menu1-sp">Khách sạn</a></li>
                                        <li><a class="text-white semi-bold" data-toggle="tab" href="#menu2-sp">VINPEARL</a></li>
                                        <li><a class="text-white semi-bold" data-toggle="tab" href="#menu3-sp">Landtour</a></li>
                                    </ul>
                                    <div class="tab-content mt15">
                                        <div id="home-sp" class="tab-pane fade in active mb20">
                                            <div class="wrapper-slider">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-xs-36">
                                                            <form action="<?= \Cake\Routing\Router::url('/tim-kiem') ?>">
                                                                <div class="row">
                                                                    <div class="col-sm-14 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Địa điểm
                                                                        </p>
                                                                        <div class="">
                                                                            <div class="input-group br4">
                                                                                <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                                                <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggest(this, '#auto-complete-sp', 'all')" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn Vin Group">
                                                                            </div>
                                                                        </div>
                                                                        <div class="popup-search-vin br4" id="auto-complete-sp">

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-8 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Nhận phòng - Trả phòng
                                                                        </p>
                                                                        <div class='input-group date'>
                                                                            <input type='text' name="fromDate"
                                                                                   class="form-control popup-voucher border-blue custom-daterange-picker"
                                                                                   placeholder="Thời gian đi" value=""/>
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
                                        <div id="menu1-sp" class="tab-pane fade mb20">
                                            <div class="wrapper-slider">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-xs-36">
                                                            <form action="<?= \Cake\Routing\Router::url('/tim-kiem') ?>">
                                                                <div class="row">
                                                                    <div class="col-sm-14 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Địa điểm
                                                                        </p>
                                                                        <div class="">
                                                                            <div class="input-group br4">
                                                                                <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                                                <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggest(this, '#auto-complete-hotel-sp', <?= HOTEL ?>)" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn Vin Group">
                                                                            </div>
                                                                        </div>
                                                                        <div class="popup-search-vin br4" id="auto-complete-hotel-sp">

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-8 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Nhận phòng - Trả phòng
                                                                        </p>
                                                                        <div class='input-group date'>
                                                                            <input type='text' name="fromDate"
                                                                                   class="form-control popup-voucher border-blue custom-daterange-picker"
                                                                                   placeholder="Thời gian đi" value=""/>
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
                                        <div id="menu2-sp" class="tab-pane fade mb20">
                                            <div class="wrapper-slider">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-xs-36">
                                                            <form action="<?= \Cake\Routing\Router::url('/khach-san-vinpearl') ?>">
                                                                <div class="row">
                                                                    <div class="col-sm-14 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Địa điểm
                                                                        </p>
                                                                        <div class="">
                                                                            <div class="input-group br4">
                                                                                <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                                                <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggestVin(this, '#auto-complete-vin-sp')" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn Vin Group">
                                                                            </div>
                                                                        </div>
                                                                        <div class="popup-search-vin br4" id="auto-complete-vin-sp">

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-8 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Nhận phòng - Trả phòng
                                                                        </p>
                                                                        <div class='input-group date'>
                                                                            <input type='text' name="fromDate"
                                                                                   class="form-control popup-voucher border-blue custom-daterange-picker"
                                                                                   placeholder="Thời gian đi" value=""/>
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
                                        <div id="menu3-sp" class="tab-pane fade mb20">
                                            <div class="wrapper-slider">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-xs-36">
                                                            <form action="<?= \Cake\Routing\Router::url('/tim-kiem') ?>">
                                                                <div class="row">
                                                                    <div class="col-sm-14 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Địa điểm
                                                                        </p>
                                                                        <div class="">
                                                                            <div class="input-group br4">
                                                                                <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                                                                <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggest(this, '#auto-complete-landtour-sp', <?= LANDTOUR ?>)" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn Vin Group">
                                                                            </div>
                                                                        </div>
                                                                        <div class="popup-search-vin br4" id="auto-complete-landtour-sp">

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-8 col-xs-36 mb10-sp">
                                                                        <p class="text-left mb05 bold text-center-sp">
                                                                            Ngày đi
                                                                        </p>
                                                                        <div class='input-group date'>
                                                                            <input type='text' name="fromDate"
                                                                                   class="form-control popup-voucher border-blue datepicker"
                                                                                   placeholder="Thời gian đi" value=""/>
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
                                                                                <div id="room-container">
                                                                                    <div id="list-input-room">
                                                                                        <div class="single-input-room">
                                                                                            <div class="row mt10 mb10">
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
                                    </div>
<!--                                    <div class="input-group border-blue-input br4 ml10-sp mr10-sp">-->
<!--                                        <span class="input-group-addon"><i class="fas fa-search text-light-blue mr10"></i></span>-->
<!--                                        <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggest(this, '#auto-complete-sp' , 'all')" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn">-->
<!--                                    </div>-->
                                </div>
<!--                                <div class="col-xs-36 col-sm-4 text-center">-->
<!--                                    <div class="ml10-sp mr10-sp pos-relative">-->
<!--                                        <button type="button" onclick="Frontend.findAgencyWithoutLoading(this)" class="btn btn-home-search btn-block semi-bold h35-sp mt10-sp mb10-sp fs16-sp"> Tìm kiếm</button>-->
<!--                                        <div class="popup-search-sp br4" id="auto-complete-sp">-->
<!---->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Slider -->
<!-- Sologan -->
<div class="sologan pt10 pb10">
    <div class="container">
        <p class="text-center fs14">
            Mustgo đồng hành cùng các bạn 24/7 - tư vấn giúp bạn xuyên suốt chuyến hành trình - gọi cho Mustgo: 092.5959.777
        </p>
    </div>
</div>
<!-- End Sologan -->
