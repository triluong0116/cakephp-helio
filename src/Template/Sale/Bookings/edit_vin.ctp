<style>
    .popup-input-room {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm mới Booking</h2>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="form-horizontal form-label-left" id="edit-vin-booking">
    <?= $this->Form->create(null, ['class' => '', 'data-parsley-validate', 'id' => 'form-booking-system', 'type' => 'file']) ?>
    <?php
    $this->Form->setTemplates([
        'formStart' => '<form class="" {{attrs}}>',
        'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
        'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
        'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
        'textarea' => '<div class="col-md-8 col-sm-8 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
        'checkContainer' => ''
    ]) ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thêm mới Booking Vinpearl</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                        echo $this->Form->control('user_id', [
                            'empty' => 'Chọn cộng tác viên',
                            'label' => 'Chọn cộng tác viên *',
                            'class' => 'form-control select2',
                            'options' => $listAgency,
                            'required' => 'required',
                            'default' => $vinBooking->user_id
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        echo $this->Form->control('hotel_id', [
                            'empty' => 'Chọn Khách sạn Vin',
                            'class' => 'form-control text-left select2',
                            'label' => 'Chọn Khách sạn Vin *',
                            'id' => 'choose-type-booking-system',
                            'default' => (isset($dataValiError['type']) && !empty($dataValiError['type'])) ? $dataValiError['type'] : '',
                            'data-item-id' => (isset($dataValiError['item_id']) && !empty($dataValiError['item_id'])) ? $dataValiError['item_id'] : '',
                            'data-room-level' => '',
                            'data-form-id' => 'form-booking-system',
                            'options' => $listVinpearlHotel,
                            'default' => $vinBooking->hotel_id,
                            'required' => 'required',
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Ngày đi - Ngày về</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input type="text" name="daterange" class="custom-daterange-picker form-control" value="<?= $dateBooking ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Số người</label>
                                <div class="col-md-8 col-sm-8">
                                    <div class='input-group date' style="width: 100%" onclick="showInputRoom(this, '#list-room' , 'all')">
                                        <input type='text' name="num_people" class="form-control w100" value="<?= $numRoom ?> Phòng-<?= $numAdult ?>NL-<?= $numChild ?>TE-<?= $numKid ?>EB">
                                        <div class="popup-input-room br4" id="input-room">
                                            <div class="col-sm-12 text-left mt10 mb10">
                                                <div class="row">
                                                    <div class="col-sm-4" style="margin-top: 3px">
                                                        <span class="text-left fs20">Phòng</span>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="row">
                                                            <div class="col-sm-4 text-center" style="margin-top: 3px">
                                                                <span class="room-minus"><i class="fa fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                            </div>
                                                            <div class="col-sm-4 text-center no-pad-left no-pad-right">
                                                                <span id="num-room" class="fs24"><?= $numRoom ?></span>
                                                            </div>
                                                            <div class="col-sm-4 text-center" style="margin-top: 3px">
                                                                <span class="room-plus"><i class="fa fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="room-container">
                                                <div id="list-input-room">
                                                    <?php foreach($vinBooking->vinhmsbooking_rooms as $roomKey => $singleRoom): ?>
                                                    <div class="single-input-room">
                                                        <div class="row mt10 mb10">
                                                            <div class="col-sm-12">
                                                                <p class="text-center">Phòng <?= $roomKey + 1 ?></p>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="col-sm-4 text-left" style="margin-top: 3px">
                                                                    <span class="room-adult-minus"><i class="fa fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-4 text-center no-pad-right">
                                                                    <span class="fs24 num-room-adult" style="padding-left: 3px"><?= $singleRoom['num_adult'] ?></span>
                                                                </div>
                                                                <div class="col-sm-4 text-right" style="margin-top: 3px">
                                                                    <span class="room-adult-plus"><i class="fa fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <p class="text-center">Người lớn</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="col-sm-4 text-left" style="margin-top: 3px">
                                                                    <span class="room-child-minus"><i class="fa fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-4 text-center no-pad-right">
                                                                    <span class="fs24 num-room-child" style="padding-left: 3px"><?= $singleRoom['num_child'] ?></span>
                                                                </div>
                                                                <div class="col-sm-4 text-right" style="margin-top: 3px">
                                                                    <span class="room-child-plus"><i class="fa fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <p class="text-center">Trẻ em</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="col-sm-4 text-left" style="margin-top: 3px">
                                                                    <span class="room-kid-minus"><i class="fa fa-minus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-4 text-center no-pad-right">
                                                                    <span class="fs24 num-room-kid" style="padding-left: 3px"><?= $singleRoom['num_kid'] ?></span>
                                                                </div>
                                                                <div class="col-sm-4 text-right" style="margin-top: 3px">
                                                                    <span class="room-kid-plus"><i class="fa fa-plus" style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <p class="text-center">Em bé</p>
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
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9 col-sm-offset-2">
                        <button type="button" class="btn btn-primary" onclick="chooseVinHotel(this)">
                            Áp dụng
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Họ và tên đoàn trưởng</label>
                                <div class="col-md-4 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="first_name" placeholder="Tên" class="form-control" value="<?= $vinBooking->first_name ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="sur_name" placeholder="Họ" class="form-control" value="<?= $vinBooking->sur_name ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Số điện thoại</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="phone" placeholder="Số điện thoại" class="form-control" value="<?= $vinBooking->phone ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Địa chỉ (Quốc gia, tỉnh thành phố)</label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="">
                                        <input type="text" name="nationality" placeholder="Nhập quốc tịch" class="form-control" value="<?= $vinBooking->nationality ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="">
                                        <input type="text" name="nation" placeholder="Nhập quốc gia" class="form-control" value="<?= $vinBooking->nation ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Email</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="email" placeholder="Nhập Email" class="form-control" value="<?= $vinBooking->email ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Giảm giá</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="agency_discount" onchange="calculatePrice(this)" class="form-control" value="<?= $vinBooking->agency_discount ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Đại lý tăng giảm giá khách sạn</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="change_price" onchange="calculatePrice(this)" class="form-control" value="<?= $vinBooking->change_price ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt10">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Lưu ý</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="note" class="form-control" value="<?= $vinBooking->note ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="list-input-room-data">
                        <?php foreach ($vinBooking->vinhmsbooking_rooms as $roomKey => $singleRoom): ?>
                            <input type="hidden" name="vin_room[<?= $roomKey ?>][num_adult]" value="<?= $singleRoom['num_adult'] ?>">
                            <input type="hidden" name="vin_room[<?= $roomKey ?>][num_child]" value="<?= $singleRoom['num_child'] ?>">
                            <input type="hidden" name="vin_room[<?= $roomKey ?>][num_kid]" value="<?= $singleRoom['num_kid'] ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="vin-booking-room-information">
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12 mt10">
                            <ul class="nav nav-tabs">
                                <?php for ($i = 0; $i < count($vinBooking->vinhmsbooking_rooms); $i++): ?>
                                    <li class="<?= $i == 0 ? 'active' : '' ?>"><a data-toggle="tab" href="#room-<?= $i ?>">Phòng <?= $i + 1 ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                        <div class="col-sm-12">
                            <div class="tab-content">
                                <?php $initRevenue = []; $initPrice = []; $initSaleRevenue = [] ?>
                                <?php foreach ($singleVinChooseRoom as $i => $listRoom): ?>
                                    <div id="room-<?= $i ?>" class="tab-pane <?= $i == 0 ? 'active' : '' ?>">
                                        <?php foreach ($listRoom as $k => $room): ?>
                                            <div class="x_panel">
                                                <div class="x_title">
                                                    <h2><?= $room['information']['name'] ?></h2>
                                                    <ul class="nav navbar-right panel_toolbox">
                                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                                    </ul>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                                        <div class="x_content" <?= $vinBooking->vinhmsbooking_rooms[$i]['vinhms_room_id'] == $k ? 'style="height: auto"' : 'style="display: none"' ?>>
                                                            <?php if (isset($room['package'])): ?>
                                                                <?php
                                                                $dataJson = [];
                                                                $dataJson['name'] = $room['information']['name'];
                                                                ?>
                                                                <input type="hidden" name="choose-room-<?= $k ?>" value='<?= json_encode($dataJson) ?>'>
                                                                <?php foreach ($room['package'] as $packageKey => $package): ?>
                                                                    <div class="row">
                                                                        <div class="col-sm-1 no-pad-right">
                                                                            <p class="fs16 mb15 text-light-blue">
                                                                                <?php
                                                                                $price = $package['totalAmount']['amount']['amount'] + ($package['trippal_price'] + $package['customer_price']);
                                                                                $revenue = $package['customer_price'];
                                                                                $saleRevenue = $package['trippal_price'];
                                                                                ?>
                                                                                <?php
                                                                                $check = false;
                                                                                if ($vinBooking->vinhmsbooking_rooms[$i]['packages'][0]['vinhms_allotment_id'] == $package['rateAvailablity']['allotments'][0]['allotmentId']
                                                                                && $vinBooking->vinhmsbooking_rooms[$i]['packages'][0]['vinhms_package_code'] == $package['rateAvailablity']['ratePlanCode']
                                                                                && $vinBooking->vinhmsbooking_rooms[$i]['packages'][0]['vinhms_package_name'] == $package['rateAvailablity']['ratePlan']['name']
                                                                                && $vinBooking->vinhmsbooking_rooms[$i]['vinhms_room_id'] == $k
                                                                                && $vinBooking->vinhmsbooking_rooms[$i]['packages'][0]['vinhms_rateplan_id'] == $package['ratePlanID']
                                                                                && $vinBooking->vinhmsbooking_rooms[$i]['packages'][0]['vinhms_room_type_code'] == $package['rateAvailablity']['roomTypeCode']
                                                                                && $vinBooking->vinhmsbooking_rooms[$i]['packages'][0]['vinhms_rateplan_code'] == $package['rateAvailablity']['ratePlan']['rateCode']
                                                                                && $vinBooking->vinhmsbooking_rooms[$i]['packages'][0]['vinhms_package_id'] == $package['rateAvailablity']['propertyId']) {
                                                                                    $check = true;
                                                                                    $initPrice[] = $price;
                                                                                    $initRevenue[] = $revenue;
                                                                                    $initSaleRevenue[] = $saleRevenue;
                                                                                }
                                                                                ?>
                                                                                <input type="radio" class="iCheck vin-room-pick"
                                                                                       <?= $check ? 'checked' : '' ?>
                                                                                       name="package[<?= $i ?>]"
                                                                                       data-rate-plan-code="<?= $package['rateAvailablity']['ratePlan']['rateCode'] ?>"
                                                                                       data-room-type-code="<?= $package['rateAvailablity']['roomTypeCode'] ?>"
                                                                                       data-allotment-id="<?= $package['rateAvailablity']['allotments'][0]['allotmentId'] ?>"
                                                                                       data-package-name="<?= $package['rateAvailablity']['ratePlan']['name'] ?>"
                                                                                       data-package-code="<?= $package['rateAvailablity']['ratePlanCode'] ?>"
                                                                                       data-revenue="<?= $revenue ?>"
                                                                                       data-sale-revenue="<?= $saleRevenue ?>"
                                                                                       data-package-id="<?= $package['rateAvailablity']['propertyId'] ?>"
                                                                                       data-rateplan-id="<?= $package['ratePlanID'] ?>"
                                                                                       data-room-index="<?= $i ?>"
                                                                                       data-room-key="<?= $k ?>"
                                                                                       data-package-pice="<?= number_format($price) ?>"
                                                                                       data-package-default-price="<?= $package['totalAmount']['amount']['amount'] ?>"></i>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-sm-11">
                                                                            <?php
                                                                            $arrText = explode('-', $package['rateAvailablity']['ratePlan']['name']);
                                                                            $packageName = '';
                                                                            foreach ($arrText as $kText => $text) {
                                                                                $text = trim($text);
                                                                                $packageName .= defined($text) ? $text . "(" . constant($text) . ")" : $text;
                                                                                $packageName .= $kText != count($arrText) - 1 ? " - " : '';
                                                                            }
                                                                            ?>
                                                                            <p class="fs18" style="text-decoration: underline"><?= $packageName ?></p>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $vinInformation = json_decode($vinBooking->vin_information, true);
                        ?>
                        <?php foreach ($vinBooking->vinhmsbooking_rooms as $roomKey => $singleRoom): ?>
                            <?php
                            $total = intval($singleRoom['num_adult']) + intval($singleRoom['num_child']) + intval($singleRoom['num_kid']);
                            ?>
                            <div class="col-sm-12">
                                <h3>Phòng <?= $roomKey + 1 ?></h3>
                            </div>
                            <?php for ($i = 0; $i < $total; $i++): ?>
                                <div class="col-sm-6 mt10">
                                    <div class="control-group">
                                        <div class="controls">
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Họ và tên</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <div class="">
                                                    <input type="text" name="vin_information[<?= $roomKey ?>][<?= $i ?>][name]" class="form-control" value="<?= $vinInformation[$roomKey][$i]['name'] ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt10">
                                    <div class="control-group">
                                        <div class="controls">
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Ngày sinh</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <div class="">
                                                    <input type="text" name="vin_information[<?= $roomKey ?>][<?= $i ?>][birthday]" class="form-control custom-singledate-picker" value="<?= $vinInformation[$roomKey][$i]['birthday'] ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thông tin đơn hàng</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/>
                            <?php foreach($vinBooking->vinhmsbooking_rooms as $i => $inputRoom): ?>
                                <div class="single-room-detail" data-vinroom-price="<?= $initPrice[$i] ?>" data-vinroom-revenue="<?= $initRevenue[$i] ?>" id="vin-room-<?= $i ?>" data-room-number="<?= $i ?>">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <button type="button" class="btn btn-success mt15 btnAddNewPackage" data-toggle="modal" data-target="#modalAddNewPackage"
                                                    data-vinroom-id="<?= $inputRoom['vinhms_room_id'] ?>"
                                                    data-vinroom-index="<?= $i ?>"
                                                    data-hotel-id="<?= $vinBooking->hotel_id ?>"
                                                    data-num-adult="<?= $inputRoom['num_adult'] ?>"
                                                    data-num-child="<?= $inputRoom['num_child'] ?>"
                                                    data-num-kid="<?= $inputRoom['num_kid'] ?>"
                                                    style="border-radius: 50%; padding: 6px 11px !important;">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="col-sm-11">
                                            <div class="row">
                                                <div class="col-sm-7 mt10">
                                                    <p class="fs14 bold">Phòng <?= $i + 1 ?> : <?= $inputRoom['vinhms_name'] ?> </p>
                                                </div>
                                                <div class="col-sm-5 mt10">
                                                    <span class="pull-right fs14 bold"><p class="total-vin-room-<?= $i ?>"><?= number_format($inputRoom['room_price']) ?></p>VNĐ</span>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="row list-package-room-<?= $i ?>">
                                                        <?php foreach ($inputRoom['packages'] as $kP => $package): ?>
                                                            <div class="single-package">
                                                                <input type="hidden" class="start_date_vin" name="package[0][start_date]" value="<?= date('Y-m-d', strtotime($package['checkin'])) ?>">
                                                                <input type="hidden" class="end_date_vin" name="package[0][end_date]" value="<?= date('Y-m-d', strtotime($package['checkout'])) ?>">
                                                                <div class="col-sm-7">
                                                                    <p class="fs14" style="margin-bottom: 0px">Gói: <?= $package['vinhms_package_code'] ?></p>
                                                                    <p class="mt05 fs14"><?= date('d/m/Y', strtotime($package['checkin'])) ?> - <?= date('d/m/Y', strtotime($package['checkout'])) ?></p>
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <p class="fs14 pull-right"><?= number_format($package['price'] + $package['revenue'] + $package['sale_revenue']) ?> VNĐ</p>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <a href="#" class="text-center">Xóa gói</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-booking-vin-room-<?= $i ?> vin-bk-room">
                                    <input type="hidden" name="vin_room[<?= $i ?>][id]" value="<?= $inputRoom['room_id'] ?>">
                                    <input type="hidden" name="vin_room[<?= $i ?>][name]" value="<?= $inputRoom['vinhms_name'] ?>">
                                    <input type="hidden" name="vin_room[<?= $i ?>][room_type_code]" value="<?= $inputRoom['vinhms_room_type_code'] ?>">
                                    <div class="list-package-input-room-<?= $i ?>">
                                        <?php foreach ($inputRoom['packages'] as $kP => $package) ?>
                                        <div class="package-input-0 single-packet-input">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][code]" value="<?= $package['vinhms_package_code'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][package_name]" value="<?= $package['vinhms_package_name'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][price]" value="<?= $package['price'] + $package['revenue'] + $package['sale_revenue'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][default_price]" value="<?= $package['price'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][package_id]" value="<?= $package['vinhms_package_id'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][rateplan_code]" value="<?= $package['vinhms_rateplan_code'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][revenue]" value="<?= $package['revenue'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][sale_revenue]" value="<?= $package['sale_revenue'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][rateplan_id]" value="<?= $package['vinhms_rateplan_id'] ?>">
                                            <input type="hidden" name="vin_room[<?= $i ?>][package][<?= $kP ?>][allotment_id]" value="<?= $package['vinhms_allotment_id'] ?>">
                                            <input type="hidden" class="last-package-start-date" name="vin_room[<?= $i ?>][package][<?= $kP ?>][start_date]" value="<?= $package['checkin'] ?>">
                                            <input type="hidden" class="last-package-end-date" name="vin_room[<?= $i ?>][package][<?= $kP ?>][end_date]" value="<?= $package['checkout'] ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5 class="pull-right">
                                        Tổng cộng
                                    </h5>
                                </div>
                                <div class="col-sm-12">
                                    <h5 class="pull-right text-orange fs24 semi-bold" id="">
                                        <span id="totalVinBookingPrice"><?= number_format($vinBooking->price) ?></span> VNĐ
                                    </h5>
                                </div>
                                <div class="col-sm-12">
                                    <h5 class="pull-right">
                                        Giảm giá
                                    </h5>
                                </div>
                                <div class="col-sm-12">
                                    <h5 class="pull-right text-orange fs24 semi-bold" id="">
                                        <span id="totalDiscount"><?= number_format($vinBooking->agency_discount) ?></span> VNĐ
                                    </h5>
                                </div>
                                <?php if ($this->request->session()->read('Auth.User.id') != $userId): ?>
                                    <div class="col-sm-12">
                                        <h5 class="pull-right">
                                            Chiết khấu Đại Lý
                                        </h5>
                                    </div>
                                    <div class="col-sm-12">
                                        <h5 class="pull-right text-orange fs24 semi-bold" id="">
                                            <span id="totalVinBookingRevenue"><?= number_format($vinBooking->revenue) ?></span> VNĐ
                                        </h5>
                                    </div>
                                    <div class="col-sm-12">
                                        <h5 class="pull-right">
                                            Đại lý phải thanh toán
                                        </h5>
                                    </div>
                                    <div class="col-sm-12">
                                        <h5 class="pull-right text-orange fs24 semi-bold" id="">
                                            <span id="totalAgencyPayVinBooking"><?= number_format($vinBooking->price - $vinBooking->revenue) ?></span> VNĐ
                                        </h5>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-6 col-xs-12 mt10">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thanh toán của Đại lý/Khách lẻ</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <h4 class="text-light-blue mb10">Phương thức thanh toán</h4>
                            <p class="error-messages" id="error_type"></p>
                            <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck payment-check" <?= $vinBooking->vinpayment && $vinBooking->vinpayment->type == PAYMENT_TRANSFER ? 'checked' : '' ?> name="payment[payment_type]" value="<?= PAYMENT_TRANSFER; ?>" data-field-id="payment-transfer"> Chuyển khoản ngân hàng</i></p>
                            <fieldset class="scheduler-border payment-fieldset" id="payment-transfer"  <?= $vinBooking->vinpayment ? 'style="display: block !important"' : '' ?>>
                                <p class="error-messages" id="error_invoice"></p>
                                <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="payment[payment_invoice]" <?= $vinBooking->vinpayment && $vinBooking->vinpayment->invoice == 0 ? 'checked' : '' ?> value="0" data-field-id="no-invoice"> Không xuất hóa đơn</i></p>
                                <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="payment[payment_invoice]" <?= $vinBooking->vinpayment && $vinBooking->vinpayment->invoice == 1 ? 'checked' : '' ?> value="1" data-field-id="has-invoice"> Xuất hóa đơn VAT</i></p>
                                <div class="invoice-zone" id="has-invoice">
                                    <div class="row ml15 mr15 mb15 mt15">
                                        <p class="fs14 mb10">Quý khách vui lòng chuyển khoản vào tài khoản dưới đây và điền địa chỉ thông tin chi tiết để mustgo xuất và gửi hóa đơn thanh toán</p>
                                        <p class="error-messages" id="error_invoice_information"></p>
                                        <div class="row-eq-height">
                                            <div class="col-sm-12">
                                                <div class="form-group full-height">
                                                    <textarea class="form-control" placeholder="Thông tin xuất hóa đơn..." name="payment[payment_invoice_information]" style="height: 100%"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="deligate-payment">
                                <div class="row ml15 mr15 mb15 mt15">
                                    <h4 class="fs14 mb10">Ảnh hóa đơn thanh toán</h4>
                                    <p class="error-messages" id="error_images"></p>
                                    <div class="col-sm-36 text-center">
                                        <div id="dropzone-upload" class="dropzone">
                                        </div>
                                        <input type="hidden" name="media" value='<?= $vinBooking->vinpayment ? $vinBooking->vinpayment->images : "" ?>'/>
                                        <input type="hidden" name="list_image" value='<?= $vinBooking->vinpayment ? $vinBooking->vinpayment->images : "" ?>'/>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div id="form-by-type-system">

    </div>
    <button type="submit" class="btn btn-primary">
        Lưu
    </button>
    <?= $this->Form->end() ?>
</div>
<div class="modal fade" id="modalAddNewPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title bold fs25 mt20">Tìm kiếm</h4>
            </div>
            <div class="modal-body">
                <form id="addNewVinPackage">
                    <div class="row mt30 mt05-sp">
                        <div class="col-sm-5 col-xs-12">
                            <p class="text-super-dark fs14 fs12-sp mb05 mb05-sp">Ngày Checkin</p>
                            <div class='input-group date datepicker room-booking-sDate'>
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type='text' readonly="readonly" name="start_date_search" disabled
                                       class="form-control vin-date-picker" placeholder="Thời gian đi"/>
                            </div>
                            <p id="error_booking_rooms_0_start_date" class="error-messages"></p>
                        </div>
                        <div class="col-sm-5 col-xs-12">
                            <p class="text-super-dark fs14 fs12-sp mb05 mb05-sp">Ngày Checkout</p>
                            <div class='input-group date datepicker room-booking-eDate'>
                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type='text' readonly="readonly" name="end_date_search"
                                       class="form-control vin-date-picker" placeholder="Thời gian về"/>
                            </div>
                            <p id="error_booking_rooms_0_end_date" class="error-messages"></p>
                        </div>
                        <div class="col-sm-2 col-xs-12">
                            <button type="button" class="form-control btn btn-primary" style="margin-top: 24px"
                                    id="searchForVinPackage"
                                    data-vinroom-id=""
                                    data-vinroom-index=""
                                    data-num-adult=""
                                    data-num-child=""
                                    data-num-kid=""
                                    data-hotel-id="">
                                Tìm kiếm <i class="fas fa-spinner fa-pulse hidden"></i>
                            </button>
                        </div>
                        <div class="col-sm-12 mt10">
                            <button type="button" class="btn btn-success hidden" id="btnAddVinPackage">
                                Thêm gói
                            </button>
                        </div>
                        <div class="col-sm-12" id="list-vin-package">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
