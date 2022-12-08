<?php
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
?>
<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-10 col-sm-10 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-10 col-sm-10 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thông tin booking</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?php
            echo $this->Form->control('item_id', [
                'type' => 'select',
                'class' => 'form-control select2',
                'options' => $listObjs,
                'label' => 'Tên Tour *',
                'required' => 'required',
                'default' => $booking ? $booking->item_id : '',
                'onchange' => 'bookingChangeObject(this, '. LANDTOUR .')'
            ]);
            echo $this->Form->control('booking_landtour.num_adult', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số lượng người lớn *',
                'required' => 'required',
                'onchange' => 'updateTotalPriceLandtour()',
                'value' => $booking ? $booking->booking_landtour->num_adult : 0
            ]);
            echo $this->Form->control('booking_landtour.num_children', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số lượng trẻ em *',
                'required' => 'required',
                'onchange' => 'updateTotalPriceLandtour()',
                'value' => $booking ? $booking->booking_landtour->num_children : 0
            ]);
            echo $this->Form->control('booking_landtour.num_kid', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số lượng em bé *',
                'required' => 'required',
                'onchange' => 'updateTotalPriceLandtour()',
                'value' => $booking ? $booking->booking_landtour->num_kid : 0
            ]);
            ?>
            <div id="list-accessory">
                <?php if($booking): ?>
                    <div class="row">
                        <div class="col-sm-offset-2 col-sm-10">
                            <?php foreach ($landtourAccessories as $k => $accessory): ?>
                                <div class="col-sm-12">
                                    <p class="fs16 mb15 text-light-blue"><input type="checkbox" <?= in_array($accessory->id, $accessoryId) ? 'checked' : '' ?> class="iCheck checkbox-iCheck backend-accessory" name="accessroy[]" value="<?= $accessory->id ?>"><?= $accessory->name ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <h3>Địa điểm đón *</h3>
                            <div class="control-group">
                                <div class="row">
                                    <?php foreach ($landtour->land_tour_drivesurchages as $k => $driveSurchage): ?>
                                        <div class="col-sm-12">
                                            <p class="fs16 mb15 text-light-blue"><input type="radio" required <?= $driveSurchage->id == $booking->booking_landtour->pickup_id ? 'checked' : '' ?> class="iCheck radio-iCheck-pick" name="drive_surchage_pickup" value="<?= $driveSurchage->id ?>"><?= $driveSurchage->name ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 mb10">
                                        <label for="">Chi tiết điểm đón</label>
                                        <input class="form-control" name="booking_landtour[detail_pickup]" value="<?= $booking ? $booking->booking_landtour->detail_pickup : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <h3>Địa điểm trả *</h3>
                            <div class="control-group">
                                <div class="row">
                                    <?php foreach ($landtour->land_tour_drivesurchages as $k => $driveSurchage): ?>
                                        <div class="col-sm-12">
                                            <p class="fs16 mb15 text-light-blue"><input type="radio" required <?= $driveSurchage->id == $booking->booking_landtour->drop_id ? 'checked' : '' ?> class="iCheck radio-iCheck-drop" name="drive_surchage_drop" value="<?= $driveSurchage->id ?>"><?= $driveSurchage->name ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 mb10">
                                        <label for="">Chi tiết điểm trả</label>
                                        <input class="form-control" name="booking_landtour[detail_drop]" value="<?= $booking ? $booking->booking_landtour->detail_drop : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                <?php endif; ?>
            </div>
            <div class="control-group">
                <div class="controls">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Ngày đi *</label>
                    <div class="col-md-10 col-sm-10 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="start_date" class="custom-singledate-picker form-control" value="<?= $booking ? date_format($booking->start_date, 'd/m/Y') : '' ?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo $this->Form->control('price', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá Net đại lý',
                'required' => 'required',
                'readonly' => true,
                'value' => $booking ? number_format($booking->price) : 0
            ]);
            echo $this->Form->control('drive_surchage', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Phụ thu đưa đón',
                'readonly' => true,
                'value' => $booking ? number_format($booking->booking_landtour->drive_surchage) : 0
            ]);
            echo $this->Form->control('full_name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Họ Tên Khách hàng *',
                'required' => 'required',
                'value' => $booking ? $booking->full_name : ''
            ]);
            echo $this->Form->control('phone', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số ĐT *',
                'required' => 'required',
                'value' => $booking ? $booking->phone : ''
            ]);
            echo $this->Form->control('email', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Email *',
                'required' => 'required',
                'value' => $booking ? $booking->email : $email
            ]);
            echo $this->Form->control('other', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Yêu cầu thêm',
                'value' => $booking ? $booking->other : ''
            ]);
            echo $this->Form->control('note', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Lưu ý cho đơn vị tổ chức tour',
                'value' => (isset($booking->note)) ? $booking->note : ''
            ]);
//            echo $this->Form->control('note_agency', [
//                'type' => 'textarea',
//                'class' => 'form-control',
//                'label' => 'Lưu ý gửi Đại lý',
//                'value' => (isset($booking->note_agency)) ? $booking->note_agency : ''
//            ]);
            ?>
            <?php
            echo $this->Form->control('payment_method', [
                'type' => 'select',
                'class' => 'form-control',
                'options' => $method,
                'label' => 'Phương thức thanh toán *',
                'required' => 'required',
                'onchange' => 'updateTotalPriceLandtour()',
                'value' => $booking ? $booking->payment_method : ''
            ]);
            echo $this->Form->control('mustgo_deposit', [
                'type' => 'text',
                'class' => 'form-control currency',
                'options' => $method,
                'label' => 'Số tiền Mustgo thu hộ',
                'onchange' => 'updateTotalPriceLandtour()',
                'value' => $booking ? number_format($booking->mustgo_deposit) : 0
            ]);
            echo $this->Form->control('customer_deposit', [
                'type' => 'text',
                'class' => 'form-control',
                'options' => $method,
                'label' => 'Số tiền Đại lý đóng thêm',
                'value' => $booking ? number_format($booking->customer_deposit) : 0
            ]);
            echo $this->Form->control('agency_pay', [
                'type' => 'select',
                'class' => 'form-control',
                'options' => $status,
                'label' => 'Đại lý thanh toán *',
                'required' => 'required',
                'value' => $booking ? $booking->agency_pay : ''
            ]);
            ?>
            <?php
            echo $this->Form->control('sale_discount', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Tăng giảm giá NET',
                'onchange' => 'updateTotalPriceLandtour()',
                'value' => $booking ? number_format($booking->sale_discount) : 0
            ]);
            echo $this->Form->control('agency_discount', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Tăng giảm chiết khấu đại lý',
                'onchange' => 'updateTotalPriceLandtour()',
                'value' => $booking ? number_format($booking->agency_discount) : 0
            ]);
            ?>

        </div>
    </div>
</div>
<div class="col-md-12 col-sm-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thanh toán của Đại lý/Khách lẻ</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <h4 class="text-light-blue mb10">Phương thức thanh toán</h4>
            <?php if (!$payment || ($payment && $payment->type == 0)): ?>
                <p class="error-messages" id="error_type"></p>
                <p class="fs16 mb15 text-light-blue"><input type="radio" class="iCheck payment-check" name="payment_type" value="<?= PAYMENT_TRANSFER; ?>" data-field-id="payment-transfer"> Chuyển khoản ngân hàng</i></p>
                <fieldset class="scheduler-border payment-fieldset" id="payment-transfer">
                    <p class="error-messages" id="error_invoice"></p>
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="payment_invoice" value="0" data-field-id="no-invoice"> Không xuất hóa đơn</i></p>
                    <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck invoice-check" name="payment_invoice" value="1" data-field-id="has-invoice"> Xuất hóa đơn VAT</i></p>
                    <div class="invoice-zone" id="has-invoice">
                        <div class="row ml15 mr15 mb15 mt15">
                            <p class="fs14 mb10">Quý khách vui lòng chuyển khoản vào tài khoản dưới đây và điền địa chỉ thông tin chi tiết để mustgo xuất và gửi hóa đơn thanh toán</p>
                            <p class="error-messages" id="error_invoice_information"></p>
                            <div class="row-eq-height">
                                <div class="col-sm-12">
                                    <div class="form-group full-height">
                                        <textarea class="form-control" placeholder="Thông tin xuất hóa đơn..." name="payment_invoice_information" style="height: 100%"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php else: ?>
                <?php if ($payment->type == PAYMENT_TRANSFER): ?>
                    <p class="fs16 mb15 text-light-blue"><i class="fa fa-check"></i> Chuyển khoản ngân hàng</p>
                    <?php if ($payment->invoice == 0): ?>
                        <p class="fs14 mb15 text-light-blue ml10"><i class="fa fa-check"></i> Không xuất hóa đơn</p>
                    <?php endif; ?>
                    <?php if ($payment->invoice == 1): ?>
                        <p class="fs14 mb15 text-light-blue ml10"><i class="fa fa-check"></i> Xuất hóa đơn VAT</p>
                        <p class="fs14 mb15 text-light-blue ml10">Thông tin xuât hóa đơn: <?= $payment->invoice_information ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <div class="deligate-payment">
                <div class="row ml15 mr15 mb15 mt15">
                    <h4 class="fs14 mb10">Ảnh hóa đơn thanh toán</h4>
                    <p class="error-messages" id="error_images"></p>
                    <div class="col-sm-36 text-center">
                        <div id="dropzone-upload" class="dropzone">
                        </div>
                        <input type="hidden" name="media" value='<?= $list_images ?>'/>
                        <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
    <div class="text-center">
        <button type="submit" class="btn btn-success" id="saveBooking">Lưu</button>
    </div>
</div>
