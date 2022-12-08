<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $combo
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<?= $this->Form->create($combo, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
<h1>Thêm Combo</h1>
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Chi tiết Combo</h2>            
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
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Combo tiêu biểu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="checkbox" class="flat" name="is_feature" value="1">
                        </label>
                    </div>
                </div>
            </div>
            <?php
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
            echo $this->Form->control('addition_fee', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá phụ thu *',
                'required' => 'required',
            ]);
            ?>

        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Chọn Khách sạn</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addHotel(this, '.list-room-by-hotel')"><i class="fa fa-plus"></i> Thêm Khách sạn <i class="fa fa-spinner fa-spin hidden"></i></a>
            <br />  
            <div class="list-room-by-hotel">

            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Chi tiết Combo</h2>
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
            echo $this->Form->control('promote', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Khuyến mại *',
                'placeholder' => 'Ví dụ: 10%',
                'required' => 'required',
            ]);
            echo $this->Form->control('days', [
                'class' => 'form-control',
                'label' => 'Thời gian *',
                'placeholder' => 'Ví dụ: 3 ngày 2 đêm',
                'required' => 'required'
            ]);
            echo $this->Form->control('description', [
                'type' => 'textarea',
                'class' => 'form-control tinymce',
                'label' => 'Nội dung Combo *',
                'required' => 'required'
            ]);
            ?>
        </div>
    </div>


</div>
<div class="col-md-6 col-xs-12">
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
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success" id="blog-submit">Submit</button>
                </div>
            </div>

        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Icon</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addIcon(this, '.list-icon')"><i class="fa fa-plus"></i> Thêm Icon <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-icon">
                <?php if (isset($dataValiError['list_icon'])): ?>
                    <?php foreach ($dataValiError['list_icon'] as $key => $icon): ?>
                        <div class="icon-item">
                            <hr />
                            <div class="row">
                                <div class="col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Chọn Icon</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select class="form-control font-awesome" name="list_icon[]">
                                                <?php foreach ($captionIconLists as $k => $ico): ?>                        
                                                    <option value="<?= $k ?>" <?= ($icon == $k) ? 'selected' : '' ?>><?= $ico ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right">
                                    <a href="#" onclick="deleteItem(this, '.icon-item');" class="mt10">
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
                                <div class="col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Chọn Icon</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select class="form-control font-awesome" name="list_caption[<?= $key ?>][icon]">
                                                <?php foreach ($captionIconLists as $k => $icon): ?>                        
                                                    <option value="<?= $k ?>" <?= ($caption['icon'] == $k) ? 'selected' : '' ?>><?= $icon ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5 col-xs-5">
                                    <?php
                                    echo $this->Form->control('list_caption.' . $key . '.content', [
                                        'templates' => [
                                            'inputContainer' => '<div class="item form-group">{{content}}</div>',
                                            'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                                            'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                                        ],
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Mô tả *',
                                        'required' => 'required',
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
            <textarea rows="15" cols="30" name="fb_content"></textarea>
        </div>
    </div>

</div>
<?= $this->Form->end() ?>