<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review $review
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<?= $this->Form->create($review, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
<div class="col-md-6 col-sm-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Bài viết Đánh giá</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
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
            ]);
//            echo $this->Form->control('category_id', [
//                'options' => $categories,
//                'class' => 'form-control select2',
//                'label' => 'Danh mục *',
//                'required' => 'required'
//            ]);
            echo $this->Form->control('category_id', [
                'options' => $categories,
                'class' => 'form-control select2',
                'label' => 'Danh mục *',
                'required' => 'required',
            ]);
            ?>
            <?php
            echo $this->Form->control('location_id', [
                'options' => $locations,
                'class' => 'form-control select2',
                'label' => 'Địa điểm *',
                'required' => 'required',
            ]);
            echo $this->Form->control('title', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tiêu đề *',
                'required' => 'required'
            ]);
            echo $this->Form->control('caption', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Mô tả ngắn *',
                'required' => 'required',
            ]);
            echo $this->Form->control('price_start', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá khởi điểm *',
                'required' => 'required'
            ]);
            echo $this->Form->control('price_end', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Giá cuối cùng *',
                'required' => 'required'
            ]);
            ?>            
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="thumbnail" />
                    <input name="thumbnail_edit" type="hidden" value='<?= $review->thumbnail ?>' />
                </div>
            </div>
            <?php if ($review->thumbnail): ?>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <?= $this->Html->image('/' . $review->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php
            echo $this->Form->control('content', [
                'type' => 'textarea',
                'class' => 'form-control tinymce',
                'label' => 'Nội dung *',
                'required' => 'required',
            ]);
            echo $this->Form->control('status', [
                'type' => 'hidden',
                'default' => 1
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
<div class="col-sm-6 col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Media</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Ảnh đại diện *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" name="thumbnail" />
                    <input name="thumbnail_edit" type="hidden" value='<?= $review->thumbnail ?>' />
                </div>
            </div>
            <?php if ($review->thumbnail): ?>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <?= $this->Html->image('/' . $review->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="text-center">
                <label class="control-label">Danh sách Ảnh</label>
            </div>
            <div id="dropzone-upload" class="dropzone">
            </div>
            <input type="hidden" name="media" value='<?= $review->media ?>'/>
            <input type="hidden" name="list_image" value='<?= $list_images ?>'/>
            <div class="clearfix"></div>

        </div>
    </div>
    <div class="x_panel">
        <div class="x_title">
            <h2>Review địa chỉ</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <a class="btn btn-success" onclick="addReview(this, '.list-review')"><i class="fa fa-plus"></i> Thêm địa chỉ <i class="fa fa-spinner fa-spin hidden"></i></a>
            <div class="list-review">
                <?php
                    $listAdresses = json_decode($review->place);
                ?>
                <?php foreach ($listAdresses as $key => $adress): ?>
                    <div class="caption-combo-item">
                        <hr/>
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                <?php
                                echo $this->Form->control('list_review.' . $key . '.name', [
                                    'templates' => [
                                        'inputContainer' => '<div class="item form-group">{{content}}</div>',
                                        'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                                        'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                                    ],
                                    'type' => 'text',
                                    'class' => 'form-control',
                                    'label' => 'Tên *',
                                    'required' => 'required',
                                    'default' => $adress->name
                                ]);
                                echo "<div class='clearfix'></div>";
                                echo $this->Form->control('list_review.' . $key . '.address', [
                                    'templates' => [
                                        'inputContainer' => '<div class="item form-group">{{content}}</div>',
                                        'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                                        'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                                    ],
                                    'type' => 'text',
                                    'class' => 'form-control',
                                    'label' => 'Địa chỉ *',
                                    'required' => 'required',
                                    'default' => $adress->address
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-2 col-sm-2 text-right">
                                <a href="#" onclick="deleteItem(this, '.caption-combo-item');" class="mt10">
                                    <i class="text-danger fa fa-minus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
