<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
?>
<?= $this->Form->create($hotel, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
            <h2>Thêm Khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
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
                    <input type="hidden" name="rating" />
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
            echo $this->Form->control('description', [
                'type' => 'textarea',
                'class' => 'form-control tinymce',
                'label' => 'Mô tả *',
                'required' => 'required',
            ]);
            echo $this->Form->control('hotline', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Hotline *',
                'required' => 'required',
            ]);
            echo $this->Form->control('address', [
                'class' => 'form-control',
                'label' => 'Địa chỉ *',
                'required' => 'required',
            ]);
            echo $this->Form->control('term', [
                'type' => 'textarea',
                'class' => 'form-control tinymce',
                'label' => 'Chính sách *',
                'required' => 'required',
            ]);
            echo $this->Form->control('fb_content', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Nội dung chia sẻ Facebook *',
                'required' => 'required',
            ]);
            ?>
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success" id="blog-submit" onclick="tinyMCE.triggerSave();">Submit</button>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="col-md-6 col-xs-12">
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
            <h2>Thêm Giá cho Khách sạn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="col-sm-5 col-xs-4">
            <?php
            echo $this->Form->control('price_agency', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>'
                ],
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá Đại lý *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-6 col-xs-4">
            <?php
            echo $this->Form->control('price_customer', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'label' => '<label class="control-label col-md-6 col-sm-6 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>'
                ],
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá Khách hàng *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addRoomPriceItem(this, '.list-price-by-room');"><i class="fa fa-plus"></i> Thêm Giá <i class="hidden fa fa-spinner fa-spin"></i></a>
            <br />
            <div class="list-price-by-room">

            </div>
        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách ảnh</h2>
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
        </div>
    </div>
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
<?= $this->Form->end() ?>
