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
            <h2>Chỉnh sửa Booking</h2>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="form-horizontal form-label-left">
    <?= $this->Form->create(null, ['class' => '', 'data-parsley-validate', 'id' => 'form-booking-system', 'type' => 'file']) ?>
    <?php
    //    dd($booking);
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
    <?php if ($booking): ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <!--            <div class="x_title">-->
                <!--                <h2>Thêm mới Booking Vinpearl</h2>-->
                <!--                <div class="clearfix"></div>-->
                <!--            </div>-->
                <div class="x_content">
                    <br/>
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                        </div>
                        <input type="text" class="d-none" name="id" value="<?= $booking->id ?>">
                        <div class="col-sm-6">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Họ và tên đoàn trưởng</label>
                                    <div class="col-md-4 col-sm-8 col-xs-12">
                                        <div class="">
                                            <input type="text" name="first_name" placeholder="Tên" class="form-control"
                                                   value="<?= $booking->sur_name ?>" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-8 col-xs-12">
                                        <div class="">
                                            <input type="text" name="sur_name" placeholder="Họ" class="form-control"
                                                   value="<?= $booking->first_name ?>" required/>
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
                                            <input type="text" name="phone" placeholder="Số điện thoại" class="form-control"
                                                   value="<?= $booking->phone ?>" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mt10">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Địa chỉ (Quốc gia, tỉnh thành
                                        phố)</label>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="">
                                            <input type="text" name="nationality" placeholder="Nhập quốc tịch"
                                                   class="form-control" value="<?= $booking->nationality ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="">
                                            <input type="text" name="nation" placeholder="Nhập quốc gia"
                                                   class="form-control" value="<?= $booking->nation ?>"/>
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
                                            <input type="text" name="email" placeholder="Nhập Email" class="form-control"
                                                   value="<?= $booking->email ?>" required/>
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
                                            <input type="text" name="agency_discount" onchange="calculatePrice(this)"
                                                   class="form-control" value="<?= $booking->agency_discount ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mt10">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Đại lý tăng giảm giá khách
                                        sạn</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <div class="">
                                            <input type="text" name="change_price" onchange="calculatePrice(this)"
                                                   class="form-control" value="<?= $booking->change_price ?>"/>
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
                                            <input type="text" name="note" class="form-control" value="<?= $booking->note ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                    <div id="list-input-room-data">-->
                        <!--                        <input type="hidden" class="vin_room-0-num_adult" name="vin_room[0][num_adult]" value="1">-->
                        <!--                        <input type="hidden" class="vin_room-0-num_kid" name="vin_room[0][num_kid]" value="0">-->
                        <!--                        <input type="hidden" class="vin_room-0-num_child" name="vin_room[0][num_child]" value="0">-->
                        <!--                    </div>-->
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="vin-booking-room-information">
            </div>

        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <?php
            $listRooms = json_decode($booking['vin_information']);
            foreach ($listRooms as $k => $room): ?>
                <?php ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Phòng <?= $k + 1 ?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br/>
                        <div class="row">
                            <?php foreach ($room as $c => $item): ?>
                                <div class="col-sm-6 mt10">
                                    <div class="control-group">
                                        <div class="controls">
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Họ và tên</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="name[<?= $k ?>][<?= $c ?>]" placeholder="" class="form-control"
                                                       value="<?= $item->name ?>" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt10">
                                    <div class="control-group">
                                        <div class="controls">
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Ngày sinh</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <div class="input-prepend input-group">
                                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                    <input type="text" name="birthday[<?= $k ?>][<?= $c ?>]" class="custom-singledate-picker form-control" value="<?= $item->birthday ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="form-by-type-system">
        </div>
        <span id="error-choose-room" class="text-red"></span><br>
        <button type="button" class="btn btn-primary btn-log" type="submit" onclick="saveEditVinpearl(this)  " data-ctl="bookings" data-role="sale" data-title="2" data-id="<?= $booking->id ?>" data-code="<?= $booking->code ?>">
            <i class="fa fa-cog fa-spin fa-fw hidden" id="cog-3"></i> Lưu
        </button>
    <?php endif; ?>
    <?= $this->Form->end() ?>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
