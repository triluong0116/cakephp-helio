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
                    <input type="file" name="thumbnail" />
                </div>
                <input name="thumbnail_edit" type="hidden" value='<?= $room->thumbnail ?>' />
            </div>
            <?php if ($room->thumbnail): ?>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <?= $this->Html->image('/' . $room->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']); ?>
                    </div>
                </div>
            <?php endif; ?>         
            <hr />
            <a class="btn btn-success" onclick="addRoomPriceItem(this, '.list-price-by-room');"><i class="fa fa-plus"></i> Thêm Giá <i class="hidden fa fa-spinner fa-spin"></i></a>
            <br />   
            <div class="list-price-by-room">
                <?php foreach ($room->price_rooms as $key => $price): ?>
                    <div class="price-room-item">
                        <div class="row">
                            <hr />
                            <input type="hidden" name="reservation[<?= $key ?>][id]" value="<?= $price->id ?>" />
                            <div class="col-sm-7 col-xs-7">                        
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Khoảng thời gian *</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <div class="input-prepend input-group">
                                                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                                <input type="text" name="reservation[<?= $key ?>][date]" required="required" class="custom-daterange-picker form-control" value="" />
                                                <div class="date-range-edit-value" data-start-date="<?= date('d/m/Y', strtotime($price->start_date)) ?>" data-end-date="<?= date('d/m/Y', strtotime($price->end_date)) ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-sm-4 col-xs-4">
                                <?php
                                echo $this->Form->control('reservation.' . $key . '.price', [
                                    'templates' => [
                                        'inputContainer' => '<div class="item form-group">{{content}}</div>',
                                        'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                                        'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>'
                                    ],
                                    'type' => 'text',
                                    'class' => 'form-control currency',
                                    'label' => 'Giá *',
                                    'default' => $price->price,
                                    'required' => 'required',
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-1 col-xs-1 text-right">
                                <a href="#" onclick="deleteItem(this, '.price-room-item');" class="mt10">
                                    <i class="text-danger fa fa-minus" ></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
            <input type="hidden" name="media" value='<?= $room->media ?>'/>
            <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
            <div class="clearfix"></div>
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success" id="blog-submit">Lưu</button>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->Form->end() ?>