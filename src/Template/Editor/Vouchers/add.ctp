<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher $voucher
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<?= $this->Form->create($voucher, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-9 col-sm-9 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-9 col-sm-9 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-9 col-sm-9 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);
?>
<h1>Thêm Voucher</h1>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thông tin Voucher</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />            
            <?php
            echo $this->Form->control('name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên Voucher *',
                'required' => 'required'
            ]);
            echo $this->Form->control('departure_id', [
                'options' => $departures,
                'class' => 'form-control select2',
                'label' => 'Điểm đi *',
                'required' => 'required'
            ]);
            echo $this->Form->control('destination_id', [
                'options' => $destinations,
                'class' => 'form-control select2',
                'label' => 'Điểm đến *',
                'required' => 'required'
            ]);
            ?>            
            <div class="control-group">
                <div class="controls">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Thời gian *</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="reservation" class="custom-daterange-picker form-control" value="" />
                        </div>
                    </div>
                </div>
            </div>                        
            <div class="clearfix"></div>
            <div class="combo-hotel-item">
                <hr />
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <?php
                        echo $this->Form->control('hotel_id', [
//                            'templates' => [
//                                'inputContainer' => '<div class="item form-group">{{content}}</div>',
//                                'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
//                                'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
//                            ],
                            'type' => 'select',
                            'options' => $hotels,
                            'empty' => 'Chọn khách sạn',
                            'class' => 'form-control select2',
                            'required' => 'required',
                            'label' => 'Khách sạn *'
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <?php
                        echo $this->Form->control('days_attended', [
//                            'templates' => [
//                                'inputContainer' => '<div class="item form-group">{{content}}</div>',
//                                'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
//                                'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
//                            ],
                            'type' => 'text',
                            'placeholder' => 'Ví dụ: 3',
                            'class' => 'form-control',
                            'data-inputmask' => "'alias' : 'numeric', 'rightAlign' : 'false', 'allowMinus': false",
                            'required' => 'required',
                            'label' => 'Số ngày *'
                        ]);
                        ?>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Media</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="thumbnail" required="required" />
                </div>
            </div>
            <div class="text-center">
                <label class="control-label">Danh sách Ảnh</label>
            </div>
            <div id="dropzone-upload" class="dropzone">
            </div>
            <input type="hidden" name="media"/>
            <div class="clearfix"></div>
            <div class="ln_solid"></div>


        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Mô tả ngắn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addCaption(this, '.list-caption')"><i class="fa fa-plus"></i> Thêm Mô tả ngắn <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-caption">
                <?php if (isset($dataValiError['list_caption'])): ?>
                    <?php foreach ($dataValiError['list_caption'] as $key => $caption): ?>
                        <div class="caption-combo-item">
                            <hr />
                            <div class="row">
                                <div class="col-sm-10 col-xs-10">
                                    <?php
                                    echo $this->Form->control('list_caption.' . $key . '.content', [
                                        'type' => 'textarea',
                                        'class' => 'form-control',
                                        'label' => 'Mô tả *',
                                        'required' => 'required',
                                        'rows' => 2,
                                    ]);
                                    ?>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right">
                                    <a href="#" onclick="deleteItem(this, '.caption-combo-item');" class="mt10">
                                        <i class="text-danger fa fa-minus" ></i>
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
            <h2>Nội dung chia sẻ Facebook</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php
            echo $this->Form->control('fb_content', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Nội dung *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success" id="blog-submit">Submit</button>
            </div>
        </div>
    </div>


</div>
<div class="col-md-6 col-xs-12">

    <div class="x_panel">
        <div class="x_title">
            <h2>Chi tiết Voucher</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />  
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Đánh giá</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class='starrr'></div>
                    <input type="hidden" name="rating" />
                </div>
            </div>
            <?php
            echo $this->Form->control('price', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá *',
                'required' => 'required',
            ]);
            echo $this->Form->control('trippal_price', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Lợi nhuận của Mustgo *',
                'required' => 'required',
            ]);
            echo $this->Form->control('customer_price', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Lợi nhuận cho Đại lý *',
                'required' => 'required',
            ]);
            echo $this->Form->control('promote', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Khuyến mại',
                'placeholder' => 'Ví dụ: 10%'
            ]);
            ?>
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