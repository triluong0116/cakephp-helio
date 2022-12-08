<style>
    .btn-choose-room {
        position: absolute;
        bottom: 0;
        right: 12px;
        background-color: #3dabff;
        color: #FFFFFF;
    }

    .popup-input-room {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    @media screen and (max-width: 1200px) {
        .fs22 {
            font-size: 20px;
        }

        .fs16 {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 768px) {
        .popup-input-room {
            width: 99%;
        }
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-36 col-xs-36 mt20 mb20">
            <div class="pc box-shadow p20">
                <form action="<?= \Cake\Routing\Router::url('/khach-san-vinpearl') ?>">
                    <div class="row">
                        <div class="col-sm-14">
                            <label class="text-left">
                                Địa điểm
                            </label>
                            <div class="">
                                <div class="input-group br4">
                                    <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggestVin(this, '#auto-complete')" name="keyword" placeholder="Nhập địa điểm du lịch hoặc tên Khách sạn Vin Group" value="<?= $keyword ?>">
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
                                       class="form-control popup-voucher border-blue custom-daterange-picker"
                                       placeholder="Thời gian đi" value="<?= $fromDate ?>"/>
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
                                                        <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                    </div>
                                                    <div class="col-sm-4 text-center no-pad-left no-pad-right">
                                                        <span id="num-room" class="fs24"><?= count($dataVinRoom) ?></span>
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
                                            <?php foreach ($dataVinRoom as $k => $room): ?>
                                                <div class="single-input-room">
                                                    <div class="row mt10 mb10">
                                                        <div class="col-sm-36">
                                                            <p class="text-center">Phòng <?= $k + 1 ?></p>
                                                        </div>
                                                        <div class="col-sm-11">
                                                            <div class="row p05">
                                                                <div class="col-sm-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-adult" style="padding-left: 3px"><?= $room['num_adult'] ?></span>
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
                                                                    <span class="fs24 num-room-child" style="padding-left: 3px"><?= $room['num_child'] ?></span>
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
                                                                    <span class="fs24 num-room-kid" style="padding-left: 3px"><?= $room['num_kid'] ?></span>
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
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div id="list-input-room-data">
                                        <?php foreach ($dataVinRoom as $k => $roomData): ?>
                                            <input type="hidden" name="vin_room[<?= $k ?>][num_adult]" value="<?= $roomData['num_adult'] ?>">
                                            <input type="hidden" name="vin_room[<?= $k ?>][num_kid]" value="<?= $roomData['num_kid'] ?>">
                                            <input type="hidden" name="vin_room[<?= $k ?>][num_child]" value="<?= $roomData['num_child'] ?>">
                                        <?php endforeach; ?>
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
            <div class="sp">
                <form action="<?= \Cake\Routing\Router::url('/khach-san-vinpearl') ?>">
                    <div class="row mt20 mb20">
                        <div class="col-sm-14 col-xs-36">
                            <div class="row">
                                <div class="col-xs-36">
                                    <p class="text-center text-res bold mb10">
                                        Địa điểm
                                    </p>
                                </div>
                                <div class="col-xs-36">
                                    <div class="">
                                        <div class="input-group br4">
                                            <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" class="form-control" autocomplete="off" onkeyup="Frontend.searchSuggestVin(this, '#auto-complete-sp')" name="keyword" value="<?= $keyword ?>" placeholder="Nhập địa điểm du lịch, tên Khách sạn thuộc Vin Group">
                                        </div>
                                    </div>
                                    <div class="popup-search-vin br4" id="auto-complete-sp">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-36">
                            <div class="row">
                                <div class="col-xs-36">
                                    <p class="text-center text-res bold mb10 mt10">
                                        Nhận phòng - Trả phòng
                                    </p>
                                </div>
                                <div class="col-xs-36">
                                    <div class='input-group date'>
                                        <input type='text' name="fromDate"
                                               class="form-control popup-voucher border-blue custom-daterange-picker"
                                               placeholder="Thời gian đi" value="<?= $fromDate ?>"/>
                                        <span class="input-group-addon">
                                        <span class="far fa-calendar-alt"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-36">
                            <p class="text-center text-res bold mb10 mt10">
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
                                                        <span class="room-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                    </div>
                                                    <div class="col-sm-4 col-xs-4 text-center no-pad-left no-pad-right">
                                                        <span id="num-room" class="fs24"><?= count($dataVinRoom) ?></span>
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
                                            <?php foreach ($dataVinRoom as $k => $room): ?>
                                                <div class="single-input-room">
                                                    <div class="row mt10 mb10">
                                                        <div class="col-sm-36 col-xs-36">
                                                            <p class="text-center">Phòng <?= $k + 1 ?></p>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-11">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-adult-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-adult" style="padding-left: 3px"><?= $room['num_adult'] ?></span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-right" style="margin-top: 3px">
                                                                    <span class="room-adult-plus"><i class="fas fa-plus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-36 col-xs-36">
                                                                    <p class="text-center">Người lớn</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-11">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-child-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-child" style="padding-left: 3px"><?= $room['num_child'] ?></span>
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
                                                        <div class="col-sm-12 col-xs-11">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-xs-12 text-left" style="margin-top: 3px">
                                                                    <span class="room-kid-minus"><i class="fas fa-minus" style="border-radius: 50%; border: 1px solid #0098d5; padding: 5px 6px; color: #0098d5"></i></span>
                                                                </div>
                                                                <div class="col-sm-12 col-xs-12 text-center no-pad-right">
                                                                    <span class="fs24 num-room-kid" style="padding-left: 3px"><?= $room['num_kid'] ?></span>
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
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div id="list-input-room-data">
                                        <?php foreach ($dataVinRoom as $k => $roomData): ?>
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
        <div class="col-sm-20 col-xs-36 mb20">
            <p>Có <?= count($listVinInfor) ?> khách sạn</p>
        </div>
        <div class="col-sm-16 col-xs-36 pull-right mb20">
            <div class="row">
                <div class="col-sm-36 pull-right pc">
                    <div class="row">
                        <div class="col-sm-11 pull-right">
                            <button type="button" class="btn btn-vin-asc <?= $sortType == 'ASC' ? 'btn-sort-active' : '' ?>" onclick="Frontend.sortLinkv2('ASC', this);">Giá tăng dần <i class="fas fa-chevron-up"></i></button>
                        </div>
                        <div class="col-sm-11 pull-right">
                            <button type="button" class="btn btn-vin-desc <?= $sortType == 'DESC' ? 'btn-sort-active' : '' ?>" onclick="Frontend.sortLinkv2('DESC', this);">Giá giảm dần <i class="fas fa-chevron-down"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-36 col-xs-36 sp">
                    <div class="row">
                        <div class="col-sm-11 col-xs-18 text-center">
                            <button type="button" class="btn btn-vin-asc <?= $sortType == 'ASC' ? 'btn-sort-active' : '' ?>" onclick="Frontend.sortLinkv2('ASC', this);">Giá tăng dần <i class="fas fa-chevron-up"></i></button>
                        </div>
                        <div class="col-sm-11 col-xs-18 text-center">
                            <button type="button" class="btn btn-vin-desc <?= $sortType == 'DESC' ? 'btn-sort-active' : '' ?>" onclick="Frontend.sortLinkv2('DESC', this);">Giá giảm dần <i class="fas fa-chevron-down"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="list-vin-hotel mb20">
            <?php foreach ($listVinInfor as $k => $hotel):  ?>
                <div class="single-vin-hotel" data-url="<?= \Cake\Routing\Router::url(['_name' => 'hotel.viewVinpearl', 'slug' => $hotel['slug'], 'num_people' => $numPeople, 'date' => $fromDate, 'vin_room' => $dataVinRoom]) ?>" id="vin-room-<?= $hotel['singlePrice'] ?>-<?= $k ?>">
                    <div class="col-sm-36 col-xs-36 mb20">
                        <div class="col-sm-36 col-xs-36 p10 box-shadow">
                            <div class="row row-eq-height">
                                <div class="col-sm-10 col-xs-36">
                                    <img class="w100" src="<?= $this->Url->assetUrl($hotel['thumbnail']) ?>" alt="" style="min-height: 180px">
                                </div>
                                <div class="col-sm-26 col-xs-36">
                                    <div class="row">
                                        <div class="col-sm-27 col-xs-36">
                                            <a class="fs22 text-dark" href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.viewVinpearl', 'slug' => $hotel['slug'], 'num_people' => $numPeople, 'date' => $fromDate, 'vin_room' => $dataVinRoom]) ?>"><?= $this->System->splitByWords($hotel['name'], 35) ?></a>
                                        </div>
                                        <div class="col-sm-9 col-xs-36">
                                            <div class="pc">
                                                <?php if ($hotel['singlePrice'] != 0): ?>
                                                    <p class="fs16 pull-right">Chỉ từ <span class="text-main-blue"><?= $hotel['singlePrice'] ?> VNĐ</span></p>
                                                    <p class="pull-right">/<?= $dateDiff->days ?> đêm</p>
                                                <?php else: ?>
                                                <p class="fs16 pull-right text-red">Hết phòng</p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="sp">
                                                <?php if ($hotel['singlePrice'] != 0): ?>
                                                    <p class="fs16 mb10 mt10">Chỉ từ <span class="text-main-blue"><?= number_format($hotel['singlePrice']) ?> VNĐ/đêm</span></p>
                                                <?php else: ?>
                                                    <p class="fs16 mb10 mt10 text-red">Hết phòng</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1 col-xs-1">
                                            <p><i class="fas fa-map-marker-alt"></i></p>
                                        </div>
                                        <div class="col-sm-26 col-xs-26">
                                            <p class="fs14"><?= $hotel['address'] ?></p>
                                        </div>
                                    </div>
                                    <div class="row mt10">
                                        <?php $listExtend = $hotel['extends'] ?>
                                        <?php if ($listExtend): ?>
                                            <?php foreach ($listExtend as $extendKey => $extend): ?>
                                                <div class="col-sm-2 col-xs-2 no-pad-right">
                                                    <img class="w100" src="<?= $this->Url->assetUrl($extend['image']) ?>" alt="">
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <a type="button" href="<?= \Cake\Routing\Router::url(['_name' => 'hotel.viewVinpearl', 'slug' => $hotel['slug'], 'num_people' => $numPeople, 'date' => $fromDate, 'vin_room' => $dataVinRoom]) ?>" class="btn btn-choose-room pc">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
