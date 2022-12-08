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
<div class="form-horizontal form-label-left">
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
                                        <span class="add-on input-group-addon"><i
                                                class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input type="text" name="daterange" class="custom-daterange-picker form-control"
                                               value=""/>
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
                                    <div class='input-group date' style="width: 100%"
                                         onclick="showInputRoom(this, '#list-room' , 'all')">
                                        <input type='text' name="num_people" class="form-control w100"
                                               value="1 Phòng-1NL-0TE-0EB">
                                        <div class="popup-input-room br4" id="input-room">
                                            <div class="col-sm-12 text-left mt10 mb10">
                                                <div class="row">
                                                    <div class="col-sm-4" style="margin-top: 3px">
                                                        <span class="text-left fs20">Phòng</span>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="row">
                                                            <div class="col-sm-4 text-center" style="margin-top: 3px">
                                                                <span class="room-minus"><i class="fa fa-minus"
                                                                                            style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                            </div>
                                                            <div class="col-sm-4 text-center no-pad-left no-pad-right">
                                                                <span id="num-room" class="fs24">1</span>
                                                            </div>
                                                            <div class="col-sm-4 text-center" style="margin-top: 3px">
                                                                <span class="room-plus"><i class="fa fa-plus"
                                                                                           style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="room-container">
                                                <div id="list-input-room">
                                                    <div class="single-input-room">
                                                        <div class="row mt10 mb10">
                                                            <div class="col-sm-12">
                                                                <p class="text-center">Phòng 1</p>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="col-sm-4 text-left" style="margin-top: 3px">
                                                                    <span class="room-adult-minus"><i
                                                                            class="fa fa-minus"
                                                                            style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-4 text-center no-pad-right">
                                                                    <span class="fs24 num-room-adult"
                                                                          style="padding-left: 3px">1</span>
                                                                </div>
                                                                <div class="col-sm-4 text-right"
                                                                     style="margin-top: 3px">
                                                                    <span class="room-adult-plus"><i class="fa fa-plus"
                                                                                                     style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <p class="text-center">Người lớn</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="col-sm-4 text-left" style="margin-top: 3px">
                                                                    <span class="room-child-minus"><i
                                                                            class="fa fa-minus"
                                                                            style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-4 text-center no-pad-right">
                                                                    <span class="fs24 num-room-child"
                                                                          style="padding-left: 3px">0</span>
                                                                </div>
                                                                <div class="col-sm-4 text-right"
                                                                     style="margin-top: 3px">
                                                                    <span class="room-child-plus"><i class="fa fa-plus"
                                                                                                     style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <p class="text-center">Trẻ em</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="col-sm-4 text-left" style="margin-top: 3px">
                                                                    <span class="room-kid-minus"><i class="fa fa-minus"
                                                                                                    style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-4 text-center no-pad-right">
                                                                    <span class="fs24 num-room-kid"
                                                                          style="padding-left: 3px">0</span>
                                                                </div>
                                                                <div class="col-sm-4 text-right"
                                                                     style="margin-top: 3px">
                                                                    <span class="room-kid-plus"><i class="fa fa-plus"
                                                                                                   style="border-radius: 50%; border: 1px solid #e6b102; padding: 5px 6px; color: #e6b102"></i></span>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <p class="text-center">Em bé</p>
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
                                        <input type="text" name="first_name" placeholder="Tên" class="form-control"
                                               value="" required/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-8 col-xs-12">
                                    <div class="">
                                        <input type="text" name="sur_name" placeholder="Họ" class="form-control"
                                               value="" required/>
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
                                               value="" required/>
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
                                               class="form-control" value="" />
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="">
                                        <input type="text" name="nation" placeholder="Nhập quốc gia"
                                               class="form-control" value="" />
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
                                               value="" required/>
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
                                               class="form-control" value="0"/>
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
                                               class="form-control" value="0"/>
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
                                        <input type="text" name="note" class="form-control" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="list-input-room-data">
                        <input type="hidden" class="vin_room-0-num_adult" name="vin_room[0][num_adult]" value="1">
                        <input type="hidden" class="vin_room-0-num_kid" name="vin_room[0][num_kid]" value="0">
                        <input type="hidden" class="vin_room-0-num_child" name="vin_room[0][num_child]" value="0">
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="vin-booking-room-information">
        </div>

    </div>
    <div id="form-by-type-system">

    </div>
    <span id="error-choose-room" class="text-red"></span><br>
    <button type="button" class="btn btn-primary" onclick="checkChooseVinRoom()">
        Lưu
    </button>
    <?= $this->Form->end() ?>
</div>

<div class="modal fade" id="modalAddNewPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
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
                                <span class="add-on input-group-addon"><i
                                        class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                <input type='text' readonly="readonly" name="start_date_search" disabled
                                       class="form-control vin-date-picker" placeholder="Thời gian đi"/>
                            </div>
                            <p id="error_booking_rooms_0_start_date" class="error-messages"></p>
                        </div>
                        <div class="col-sm-5 col-xs-12">
                            <p class="text-super-dark fs14 fs12-sp mb05 mb05-sp">Ngày Checkout</p>
                            <div class='input-group date datepicker room-booking-eDate'>
                                <span class="add-on input-group-addon"><i
                                        class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
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
                                Tìm kiếm <i class="fas fa-spinner fa-pulse"></i>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
