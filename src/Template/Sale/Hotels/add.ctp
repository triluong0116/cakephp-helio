<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
//dd($dataValiError);
?>
<?= $this->Form->create($hotel, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-9 col-sm-9 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-9 col-sm-9 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-9 col-sm-9 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]) ?>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?php
            echo $this->Form->control('location_id', [
                'options' => $locations,
                'class' => 'form-control select2',
                'label' => 'Địa điểm *',
                'required' => 'required'
            ]);
            ?>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Đánh giá</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class='starrr'></div>
                    <input type="hidden" name="rating"/>
                </div>
            </div>

            <?php
            echo $this->Form->control('name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tiêu đề *',
                'required' => 'required'
            ]);
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Khách sạn đặc biệt</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="checkbox" class="flat" name="is_special" value="1">
                        </label>
                    </div>
                </div>
            </div>
            <?php
            echo $this->Form->control('address', [
                'class' => 'form-control',
                'label' => 'Địa chỉ *',
                'required' => 'required',
            ]);
            echo $this->Form->control('hotline', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Hotline *',
                'required' => 'required',
            ]);
            echo $this->Form->control('price_agency', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Lợi nhuận của Mustgo *',
                'required' => 'required',
            ]);
            echo $this->Form->control('price_customer', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Lợi nhuận cho Đại lý *',
                'required' => 'required',
            ]);
            ?>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">File hợp đồng *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="contract_file" required="required"/>
                </div>
            </div>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="thumbnail" required="required"/>
                </div>
            </div>
            <div class="text-center">
                <label class="control-label">Danh sách Ảnh</label>
            </div>
            <div id="dropzone-upload" class="dropzone">
            </div>
            <input type="hidden" name="media"/>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Tiện ích Khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?php
            echo $this->Form->control('categories._ids', [
                'templates' => [
                    'checkboxWrapper' => '<div class="col-md-4 col-sm-4 col-xs-6">{{label}}</div>',
                ],
                'options' => $ultilities,
                'class' => 'form-control flat',
                'label' => false,
                'multiple' => 'checkbox'
            ]);
            ?>
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success" id="blog-submit" onclick="tinymce.triggerSave();">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6 col-xs-12">

    <div class="x_panel">
        <div class="x_title">
            <h2>Mô tả khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addCaption(this, '.list-caption')"><i class="fa fa-plus"></i> Thêm Mô tả khách sạn <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-caption">

            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Cài đặt Ngày Lễ/Trong Tuần/Cuối Tuần</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Ngày trong tuần</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <select class="form-control select2 font-awesome" multiple name="weekday[]">
                                <?php foreach ($weekly as $k => $ico): ?>
                                    <option value="<?= $k ?>" <?= (isset($dataValiError['weekday']) && in_array($k, $dataValiError['weekday'])) ? "selected" : "" ?>><?= $ico ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Ngày cuối tuần</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <select class="form-control select2 font-awesome" multiple name="weekend[]">
                                <?php foreach ($weekly as $k => $ico): ?>
                                    <option value="<?= $k ?>" <?= (isset($dataValiError['weekend']) && in_array($k, $dataValiError['weekend'])) ? "selected" : "" ?>><?= $ico ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="list-holiday">
                <?php if (isset($dataValiError['holidays'])): ?>
                    <?php foreach ($dataValiError['holidays'] as $key => $date): ?>
                        <div class="holiday-item">
                            <div class="row">
                                <div class="col-sm-11 col-xs-11">
                                    <div class="control-group">
                                        <div class="controls">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Thời gian *</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="input-prepend input-group">
                                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                    <input type="text" name="holidays[]" class="custom-daterange-picker form-control" value="<?= $date ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 col-sm-1 text-right">
                                    <a href="#" onclick="deleteItem(this, '.holiday-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a class="btn btn-success" onclick="addHoliday(this, '.list-holiday')"><i class="fa fa-plus"></i> Thêm Ngày lễ <i class="fa fa-spinner fa-spin hidden"></i></a>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Email</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addEmail(this, '.list-email')"><i class="fa fa-plus"></i> Thêm Email <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-email">
                <?php if (isset($dataValiError['list_email'])): ?>
                    <?php foreach ($dataValiError['list_email'] as $key => $email): ?>
                        <div class="email-item">
                            <hr/>
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <?php
                                    echo $this->Form->control('list_email[' . $key . '][name]', [
                                        'templates' => [
                                            'inputContainer' => '<div class="item form-group">{{content}}</div>',
                                            'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                                            'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                                        ],
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Email *',
                                        'required' => 'required',
                                        'default' => $email['name']
                                    ]);
                                    ?>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="checkbox" class="flat" name="list_email[<?= $key ?>][is_main]" <?= (isset($email['is_main']) && $email['is_main']) ? 'checked' : '' ?> value="1">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 col-sm-1 text-right">
                                    <a href="#" onclick="deleteItem(this, '.email-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Phụ thu</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    echo $this->Form->control('checkin_time', [
                        'templates' => [
                            'inputContainer' => '<div class="item form-group">{{content}}</div>',
                            'label' => '<label {{attrs}}>{{text}}</label>',
                            'input' => '<input type="{{type}}" name="{{name}}" {{attrs}} />',
                        ],
                        'type' => 'text',
                        'class' => 'form-control timepicker',
                        'id' => 'checkInTime',
                        'label' => 'Giờ CheckIn *',
                        'required' => 'required',
                        'default' => '12:00'
                    ]);
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    echo $this->Form->control('checkout_time', [
                        'templates' => [
                            'inputContainer' => '<div class="item form-group">{{content}}</div>',
                            'label' => '<label {{attrs}}>{{text}}</label>',
                            'input' => '<input type="{{type}}" name="{{name}}" {{attrs}} />',
                        ],
                        'type' => 'text',
                        'class' => 'form-control timepicker',
                        'id' => 'checkOutTime',
                        'label' => 'Giờ CheckOut *',
                        'required' => 'required',
                        'default' => '12:00'
                    ]);
                    ?>
                </div>
            </div>
            <hr>
            <div class="list-surcharge" id="<?= (isset($dataValiError['hotel_surcharges'])) ? 'validationErrorSurcharges' : '' ?>">
                <?php if (isset($dataValiError['hotel_surcharges'])): ?>
                    <?php foreach ($dataValiError['hotel_surcharges'] as $key => $surcharge): ?>
                        <div class="surcharge-item">
                            <div class="row">
                                <div class="col-sm-11 col-xs-11">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-6">
                                            <?php
                                            echo $this->Form->control('hotel_surcharges.' . $key . '.surcharge_type', [
                                                'options' => $surcharges,
                                                'class' => 'form-control',
                                                'label' => 'Phụ thu *',
                                                'required' => 'required',
                                                'onchange' => 'switchCustomSurchage(this)'
                                            ]);
                                            ?>
                                        </div>
                                        <div class="col-xs-6 col-sm-6">
                                            <div class="surcharge-normal-price">
                                                <?php
                                                echo $this->Form->control('hotel_surcharges.' . $key . '.price', [
                                                    'type' => 'text',
                                                    'class' => 'form-control currency',
                                                    'label' => 'Đơn giá *',
                                                    'required' => 'required',
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($surcharge['options']) || isset($surcharge->options)) {
                                        if (is_array($surcharge['options'])) {
                                            $options = $surcharge['options'];
                                        } else {
                                            $options = json_decode($surcharge->options, true);
                                        }
                                    } else {
                                        $options = [];
                                    }
                                    $type = $surcharge['surcharge_type'];
                                    ?>
                                    <div class="custom-surcharge-price" style="padding-left: 20px; padding-right: 20px;display: none">
                                        <div class="list-custom-surcharge">
                                            <?php if ($options): ?>
                                                <?php foreach ($options as $k => $option): ?>
                                                    <div class="custom-surcharge-item">
                                                        <div class="row mt10">
                                                            <?php if ($type == SUR_CHILDREN): ?>
                                                                <div class="col-xs-3 col-sm-3">
                                                                    <?php
                                                                    echo $this->Form->control('hotel_surcharges[' . $key . '][options][' . $k . '][start]', [
                                                                        'type' => 'text',
                                                                        'class' => 'form-control',
                                                                        'label' => 'Từ Tuổi *',
                                                                        'required' => 'required',
                                                                        'readonly' => true,
                                                                        'default' => $option['start'],
                                                                    ]);
                                                                    ?>
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3">
                                                                    <?php
                                                                    echo $this->Form->control('hotel_surcharges[' . $key . '][options][' . $k . '][end]', [
                                                                        'type' => 'text',
                                                                        'class' => 'form-control',
                                                                        'label' => 'Đến tuổi *',
                                                                        'required' => 'required',
                                                                        'onchange' => 'updateNextCustomSurchargeValue(this, ' . $type . ')',
                                                                        'default' => $option['end'],
                                                                    ]);
                                                                    ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($type == SUR_CHECKIN_SOON): ?>
                                                                <div class="col-xs-3 col-sm-3">
                                                                    <?php
                                                                    echo $this->Form->control('hotel_surcharges[' . $key . '][options][' . $k . '][start]', [
                                                                        'type' => 'text',
                                                                        'class' => 'form-control timepicker',
                                                                        'label' => 'Từ Giờ *',
                                                                        'required' => 'required',
                                                                        'onchange' => 'updateNextCustomSurchargeValue(this, ' . $type . ')',
                                                                        'default' => $option['start'],
                                                                    ]);
                                                                    ?>
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3">
                                                                    <?php
                                                                    echo $this->Form->control('hotel_surcharges[' . $key . '][options][' . $k . '][end]', [
                                                                        'type' => 'text',
                                                                        'class' => 'form-control timepicker',
                                                                        'label' => 'Đến Giờ *',
                                                                        'required' => 'required',
                                                                        'readonly' => true,
                                                                        'default' => $option['end'],
                                                                    ]);
                                                                    ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($type == SUR_CHECKOUT_LATE): ?>
                                                                <div class="col-xs-3 col-sm-3">
                                                                    <?php
                                                                    echo $this->Form->control('hotel_surcharges[' . $key . '][options][' . $k . '][start]', [
                                                                        'type' => 'text',
                                                                        'class' => 'form-control timepicker',
                                                                        'label' => 'Từ Giờ *',
                                                                        'required' => 'required',
                                                                        'readonly' => true,
                                                                        'default' => $option['start'],
                                                                    ]);
                                                                    ?>
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3">
                                                                    <?php
                                                                    echo $this->Form->control('hotel_surcharges[' . $key . '][options][' . $k . '][end]', [
                                                                        'type' => 'text',
                                                                        'class' => 'form-control timepicker',
                                                                        'label' => 'Đến Giờ *',
                                                                        'required' => 'required',
                                                                        'onchange' => 'updateNextCustomSurchargeValue(this, ' . $type . ')',
                                                                        'default' => $option['end'],
                                                                    ]);
                                                                    ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="col-xs-5 col-sm-5">
                                                                <?php
                                                                echo $this->Form->control('hotel_surcharges[' . $key . '][options][' . $k . '][price]', [
                                                                    'type' => 'text',
                                                                    'class' => 'form-control currency',
                                                                    'label' => 'Đơn giá *',
                                                                    'required' => 'required',
                                                                    'default' => $option['price']
                                                                ]);
                                                                ?>
                                                            </div>
                                                            <div class="col-sm-1 col-xs-1 text-right">
                                                                <a href="#" onclick="deleteChildItem(this, '.custom-surcharge-item');" class="mt10">
                                                                    <i class="text-danger fa fa-minus"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="text-center mt10">
                                            <button type="button" class="btn btn-success btn-xs text-center" onclick="addCustomSurcharge(this)"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 col-xs-1 text-right">
                                    <a href="#" onclick="deleteItem(this, '.surcharge-item');" class="mt10">
                                        <i class="text-danger fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <hr>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a class="btn btn-success" onclick="addSurcharge(this, '.list-surcharge')"><i class="fa fa-plus"></i> Thêm <i class="fa fa-spinner fa-spin hidden"></i></a>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Điều khoản</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addHotelTerm(this, '.list-term')"><i class="fa fa-plus"></i> Thêm Điều khoản <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-term">
                <?php if (isset($dataValiError['list_term'])): ?>
                    <?php foreach ($dataValiError['list_term'] as $key => $term): ?>
                        <div class="term-item">
                            <hr/>
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <?php
                                    echo $this->Form->control('list_term[0][name]', [
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Tiêu đề *',
                                        'default' => $term['title'],
                                        'required' => 'required',
                                    ]);
                                    echo $this->Form->control('list_term[0][content]', [
                                        'type' => 'textarea',
                                        'class' => 'form-control tinymce2',
                                        'label' => 'Nội dung *',
                                        'default' => $term['content'],
                                        'required' => 'required',
                                    ]);
                                    ?>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right">
                                    <a href="#" onclick="deleteItem(this, '.term-item');" class="mt10">
                                        <i class="text-danger fa fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Thông tin thanh toán</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addPayment(this, '.list-payment')"><i class="fa fa-plus"></i>Thêm Thông tin <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-payment">

            </div>
        </div>
    </div>

</div>
<?= $this->Form->end() ?>
