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
?>
<?= $this->Form->create($homestay, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
                <h2>Thêm Homestay</h2>
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
                echo $this->Form->control('fb_content', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => 'Nội dung chia sẻ Facebook *',
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
                        <button type="submit" class="btn btn-success" id="blog-submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xs-12">

        <div class="x_panel">
            <div class="x_title">
                <h2>Thêm giá Homestay</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <?php
                    echo $this->Form->control('weekday_price', [
                        'class' => 'form-control currency',
                        'label' => 'Từ thứ 2 đến thứ 5 *',
                        'required' => 'required',
                    ]);
                    echo $this->Form->control('weekday_price_description', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => 'Mô tả giá *',
                        'required' => 'required',
                    ]);

                    echo $this->Form->control('weekend_price', [
                        'class' => 'form-control currency',
                        'label' => 'Từ thứ 6 đến chủ nhật *',
                        'required' => 'required',
                    ]);
                    echo $this->Form->control('weekend_price_description', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => 'Mô tả giá *',
                        'required' => 'required',
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">
                <h2>Thông tin cơ bản Homestay</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <?php
                    echo $this->Form->control('homestay_type', [
                        'options' => $listHouse,
                        'class' => 'form-control select2 col-md-6',
                        'label' => 'Loại Homestay *',
                        'required' => 'required'
                    ]);
                    echo $this->Form->control('room_type', [
                        'options' => $typeHouse,
                        'class' => 'form-control select2 col-md-6',
                        'label' => 'Loại phòng *',
                        'required' => 'required'
                    ]);
                    echo $this->Form->control('num_bed_room', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Số phòng ngủ *',
                        'required' => 'required',
                    ]);
                    echo $this->Form->control('num_guest', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Số người *',
                        'required' => 'required',
                    ]);
                    echo $this->Form->control('num_bed', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Số giường *',
                        'required' => 'required',
                    ]);
                    echo $this->Form->control('num_bath_room', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => 'Số phòng tắm *',
                        'required' => 'required',
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">
                <h2>Mô tả Homestay</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <a class="btn btn-success" onclick="addCaption(this, '.list-caption')"><i class="fa fa-plus"></i> Thêm Mô tả Homestay <i class="fa fa-spinner fa-spin hidden"></i></a>
                <div class="list-caption">
                </div>
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

                </div>
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