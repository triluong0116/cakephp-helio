<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room $room
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
?>
<?= $this->Form->create($room, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Phòng Khách sạn</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />            
            <?php
            echo $this->Form->control('hotel_id', [
                'options' => $hotels,
                'class' => 'form-control select2',
                'label' => 'Khách sạn *',
                'required' => 'required'
            ]);
            echo $this->Form->control('name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tiêu đề *',
                'required' => 'required'
            ]);
            echo $this->Form->control('area', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Diện tích (m2) *',
                'required' => 'required',
            ]);
            echo $this->Form->control('num_bed', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số giường *',
                'required' => 'required',
            ]);
            ?> 
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="thumbnail" required="required" />
                </div>
            </div>
            <hr />
            <a class="btn btn-success" onclick="addRoomPriceItem(this, '.list-price-by-room');"><i class="fa fa-plus"></i> Thêm Giá <i class="hidden fa fa-spinner fa-spin"></i></a>
            <br />   
            <div class="list-price-by-room">
                
            </div>

        </div>
    </div>
</div>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Tiện ích Khách sạn</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />  
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
        </div>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách ảnh</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
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
<?= $this->Form->end() ?>