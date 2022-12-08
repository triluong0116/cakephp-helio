<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher $voucher
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
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
                'label' => 'Tên Combo *',
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
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="thumbnail" required="required" />
                </div>
            </div>
            <?php
            echo $this->Form->control('description', [
                'type' => 'textarea',
                'class' => 'form-control tinymce',
                'label' => 'Nội dung Combo *',
                'required' => 'required'
            ]);
            ?>
            <div class="text-center">
                <label class="control-label">Danh sách Ảnh</label>
            </div>
            <div id="dropzone-upload" class="dropzone">
            </div>
            <input type="hidden" name="media"/>
            <div class="clearfix"></div>
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
            <h2>Mô tả ngắn</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addCaption(this, '.list-caption')"><i class="fa fa-plus"></i> Thêm Mô tả ngắn <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-caption">
            </div>
        </div>
    </div>
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
                'label' => 'Giá Cộng tác viên *',
                'required' => 'required',
            ]);
            echo $this->Form->control('customer_price', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá cho Khách hàng *',
                'required' => 'required',
            ]);
            echo $this->Form->control('promote', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Khuyến mại'
            ]);
            ?> 
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Chọn Phòng Khách sạn</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addRoom(this, '.list-room-by-hotel')"><i class="fa fa-plus"></i> Thêm Phòng Khách sạn <i class="fa fa-spinner fa-spin hidden"></i></a>
            <br />  
            <div class="list-room-by-hotel">
                
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>