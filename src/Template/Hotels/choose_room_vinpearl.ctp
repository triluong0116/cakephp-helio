<?php

use App\View\Helper\SystemHelper;

?>
<style>
    .panel-body {
        background-color: #f5f5f5 !important;
        border-top: none !important;
        padding-top: 0px !important;
    }
</style>
<div class="bg-grey" xmlns="http://www.w3.org/1999/html">

    <div class="container pb40 pb10-sp">
        <div class="combo-detail-title mt50 mt10-sp text-center">
            <span class="semi-bold box-underline-center fs24 pb05-sp pb20">THÔNG TIN ĐẶT PHÒNG</span>
        </div>
    </div>
    <div class="combo-detail pb50">
        <div class="container ">
            <div class="row">
                <div class="col-sm-24 col-xs-36 mt10">
                    <ul class="nav nav-tabs">
                        <?php for ($i = 0; $i < $numRoom; $i++): ?>
                            <li class="<?= $i == 0 ? 'active' : '' ?>"><a data-toggle="tab" href="#room-<?= $i ?>">Phòng <?= $i + 1 ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-24 col-xs-36">
                    <div class="tab-content">
                        <?php
                        $totalPrice = 0;
                        $totalRevenue = 0;
                        $totalPaid = 0;
                        ?>
                        <?php foreach ($dataRoom as $i => $listRoom): ?>
                            <?php
                            $totalPrice += str_replace(',', '', $listRoom['price']);
                            $totalRevenue += $listRoom['revenue'];
                            $totalPaid += str_replace(',', '', $listRoom['price']) - $listRoom['revenue'];
                            ?>
                            <div id="room-<?= $i ?>" class="tab-pane fade in <?= $i == 0 ? 'active' : '' ?>">
                                <div class="bg-white p15">
                                    <div id="list-room-booking">
                                        <p id="error_incorrect_info" class="error-messages"></p>
                                        <?php if ($listRoom): ?>
                                            <fieldset class="booking-room-item" style="position: relative">
                                                <legend class="pc"><?= $listRoom['name'] ?></legend>
                                                    <p class="sp text-center">Hạng phòng<?= $listRoom['name'] ?></p>
                                                <div class="row ml10 mr10 list-package-room-<?= $i ?>">
                                                    <div class="col-sm-36 bg-light-grey p15 single-room-package"
                                                         data-room-index="<?= $i ?>"
                                                         data-room-key="<?= $listRoom['id'] ?>"
                                                         data-package-pice="<?= $listRoom['price'] ?>"
                                                         data-package-id="<?= $listRoom['package_id'] ?>"
                                                         data-rateplan-id="<?= $listRoom['rateplan_id'] ?>"
                                                         data-allotment-id="<?= $listRoom['allotment_id'] ?>"
                                                         data-room-type-code="<?= $listRoom['room_type_code'] ?>"
                                                         data-rate-plan-code="<?= $listRoom['rateplan_code'] ?>"
                                                         data-revenue="<?= $listRoom['revenue'] ?>"
                                                         data-sale-revenue="<?= $listRoom['sale_revenue'] ?>"
                                                         data-package-code="<?= $listRoom['code'] ?>"
                                                         data-package-name="<?= $listRoom['package_name'] ?>"
                                                         data-package-default-price="<?= $listRoom['default_price'] ?>">
                                                        <?php
                                                        $arrText = explode('-', $listRoom['package_name']);
                                                        $packageName = '';
                                                        foreach ($arrText as $kText => $text) {
                                                            $text = trim($text);
                                                            $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                                            $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                                                        }
                                                        ?>
                                                        <p class="fs18 fs16-sp"><span class="bold"><?= $this->System->splitByWords($packageName, 45) ?></span> <span class="pull-right text-main-blue"><?= $listRoom['price'] ?> VNĐ</span></p>
                                                        <p><span>Mã gói: <?= $listRoom['code'] ?></span> <span class="pull-right"><?= str_replace('-', '/', $fromDate) ?> - <?= str_replace('-', '/', $toDate) ?></span></p>
                                                        <input type="hidden" class="start-date-vin" value="<?= $fromDate ?>">
                                                        <input type="hidden" class="end-date-vin" value="<?= $toDate ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-36">
                                                        <p class="text-center text-orange bold fs14 mt10 pointer remove-package hidden" data-room-index="<?= $i ?>">
                                                            Loại bỏ <i class="fa fa-trash"></i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row mt10">
                                        <div class="col-sm-6 text-white">
                                            <a class="btn btn-submit btnAddNewPackage" href="#" data-toggle="modal"
                                               data-target="#modalAddNewPackage"
                                               data-vinroom-id="<?= $listRoom['id'] ?>"
                                               data-vinroom-index="<?= $i ?>"
                                               data-num-adult="<?= $listRoom['num_adult'] ?>"
                                               data-num-child="<?= $listRoom['num_child'] ?>"
                                               data-num-kid="<?= $listRoom['num_kid'] ?>">
                                                <i class="fas fa-spinner fa-pulse hidden"></i>
                                                <span class="fs18">Thêm Gói</span>
                                                <br/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                                        <p class="mt05 fs12"><?= $numAdult ?> Người lớn, <?= $numChild ?> trẻ em, <?= $numKid ?> em bé</p>
                                        <p class="mt05 fs12"><?= $numRoom ?> phòng</p>
                                    </div>
                                    <div class="detail-room-information mt10">
                                        <div class="panel-group" id="accordion-term">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle collapsed semi-bold fs16" data-toggle="collapse" data-parent="#accordion-term" href="#collapseTerm">
                                                            Thông tin phòng
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTerm" class="panel-collapse collapsed">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <?php foreach ($dataRoom as $i => $listRoom): ?>
                                                                <div class="single-room-detail" data-vinroom-price="0" data-vinroom-revenue="0" id="vin-room-<?= $i ?>" data-room-number="<?= $i ?>">
                                                                    <div class="col-sm-20">
                                                                        <p class="fs14">Phòng <?= $i + 1 ?>: <?= $listRoom['name'] ?></p>
                                                                    </div>
                                                                    <div class="col-sm-16">
                                                                        <p class="fs14 pull-right"><span class="total-vin-room-<?= $i ?>"><?= $listRoom['price'] ?></span> VNĐ</p>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
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
                                                <span id="totalVinBookingPrice"><?= number_format($totalPrice) ?></span> VNĐ
                                            </p>
                                        </div>
                                        <?php if ($this->request->session()->read('Auth.User.role_id') == 3): ?>
                                            <div class="col-sm-36 col-xs-36 mt10">
                                                <p class="pull-right">
                                                    Chiết khấu Đại Lý
                                                </p>
                                            </div>
                                            <div class="col-sm-36 col-xs-36">
                                                <p class="pull-right text-orange fs24 semi-bold" id="">
                                                    <span id="totalVinBookingRevenue"><?= number_format($totalRevenue) ?></span> VNĐ
                                                </p>
                                            </div>
                                            <div class="col-sm-36 col-xs-36 mt10">
                                                <p class="pull-right">
                                                    Đại lý phải thanh toán
                                                </p>
                                            </div>
                                            <div class="col-sm-36 col-xs-36">
                                                <p class="pull-right text-orange fs24 semi-bold" id="">
                                                    <span id="totalAgencyPayVinBooking"><?= number_format($totalPaid) ?></span> VNĐ
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-sm-36 col-xs-36">
                                            <p class="pull-right fs13">(Giá đã bao gồm phí dịch vụ và thuế GTGT)</p>
                                        </div>
                                        <?= $this->Form->create(null, ['id' => 'vinBookingRoom', 'method' => 'post', 'url' => '/khach-san-vinpearl/' . $hotel->slug . '/booking/']) ?>
                                        <input type="hidden" name="vin_booking_type" value="2">
                                        <div class="list-booking-vin-room">
                                            <input type="hidden" name="hotel_id" value="<?= $hotel->id ?>">
                                            <input type="hidden" name="num_adult" value="<?= $numAdult ?>">
                                            <input type="hidden" name="num_child" value="<?= $numChild ?>">
                                            <input type="hidden" name="num_kid" value="<?= $numKid ?>">
                                            <input type="hidden" name="num_room" value="<?= $numRoom ?>">
                                            <input type="hidden" name="start_date" value="<?= $startDate ?>">
                                            <input type="hidden" name="end_date" value="<?= $endDate ?>">
                                            <?php foreach ($dataRoom as $k => $roomData): ?>
                                                <div class="single-booking-vin-room-<?= $k ?> vin-bk-room">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][room_index]" value="<?= $k ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][room_key]" value="<?= $roomData['id'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][package_pice]" value="<?= $roomData['price'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][package_id]" value="<?= $roomData['package_id'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][rateplan_id]" value="<?= $roomData['rateplan_id'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][allotment_id]" value="<?= $roomData['allotment_id'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][room_type_code]" value="<?= $roomData['room_type_code'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][rateplan_code]" value="<?= $roomData['rateplan_code'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][revenue]" value="<?= $roomData['revenue'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][sale_revenue]" value="<?= $roomData['sale_revenue'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][package_code]" value="<?= $roomData['code'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][package_name]" value="<?= $roomData['package_name'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][default_price]" value="<?= $roomData['default_price'] ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][start_date]" value="<?= $startDate ?>">
                                                    <input type="hidden" name="vin_room[<?= $k ?>][package][0][end_date]" value="<?= $endDate ?>">
                                                </div>
                                                <input type="hidden" name="vin_room[<?= $k ?>][name]" value="<?= $roomData['name'] ?>">
                                                <input type="hidden" name="vin_room[<?= $k ?>][num_adult]" value="<?= $roomData['num_adult'] ?>">
                                                <input type="hidden" name="vin_room[<?= $k ?>][num_kid]" value="<?= $roomData['num_kid'] ?>">
                                                <input type="hidden" name="vin_room[<?= $k ?>][num_child]" value="<?= $roomData['num_child'] ?>">
                                            <?php endforeach; ?>
                                        </div>
                                        <?= $this->Form->end() ?>
                                        <?php if (count($listRoom) > 0): ?>
                                            <div class="col-sm-36 col-xs-36 mt10">
                                                <button class="btn btn-request text-white full-width full-height btnVinBookingMulti" data-num-room="<?= count($dataRoom) ?>" <?= $this->request->getSession()->read('Auth.User.role_id') == 2 ? 'disabled' : '' ?>>
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
        </div>
    </div>
</div>
<div class="modal fade" id="modalAddNewPackage" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content modal-voucher">
            <div class="modal-header text-center pt25">
                <button type="button" class="modal-close modal-close-voucher" data-dismiss="modal"><i
                        class="fas fa-times"></i></button>
                <h4 class="modal-title bold fs25 mt20">Tìm kiếm</h4>
            </div>
            <div class="modal-body">
                <form id="addNewVinPackage">
                    <div class="row mt30 mt05-sp">
                        <div class="col-sm-15 col-xs-36">
                            <p class="text-super-dark fs14 fs12-sp mb05 mb05-sp">Ngày Checkin</p>
                            <div class='input-group date datepicker room-booking-sDate'>
                                <span class="input-group-addon"><span
                                        class="far fa-calendar-alt main-color"></span></span>
                                <input type='text' readonly="readonly" name="start_date_search" disabled
                                       class="form-control popup-voucher" placeholder="Thời gian đi"/>
                            </div>
                            <p id="error_booking_rooms_0_start_date" class="error-messages"></p>
                        </div>
                        <div class="col-sm-15 col-xs-36">
                            <p class="text-super-dark fs14 fs12-sp mb05 mb05-sp">Ngày Checkout</p>
                            <div class='input-group date datepicker room-booking-eDate'>
                                <span class="input-group-addon"><span
                                        class="far fa-calendar-alt main-color"></span></span>
                                <input type='text' readonly="readonly" name="end_date_search"
                                       class="form-control popup-voucher" placeholder="Thời gian về"/>
                            </div>
                            <p id="error_booking_rooms_0_end_date" class="error-messages"></p>
                        </div>
                        <div class="col-sm-6 col-xs-36">
                            <button type="button" class="form-control btn btn-submit" style="margin-top: 24px"
                                    id="searchForVinPackage"
                                    data-vinroom-id=""
                                    data-vinroom-index=""
                                    data-num-adult=""
                                    data-num-child=""
                                    data-num-kid=""
                                    data-hotel-id="<?= $hotelId ?>">
                                Tìm kiếm <i class="fas fa-spinner fa-pulse hidden"></i>
                            </button>
                        </div>
                        <div class="col-sm-36 mt10">
                            <button type="button" class="btn btn-submit hidden" id="btnAddVinPackage">
                                Thêm gói
                            </button>
                        </div>
                        <div class="col-sm-36" id="list-vin-package">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
