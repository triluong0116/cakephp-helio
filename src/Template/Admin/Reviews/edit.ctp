<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review $review
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm Bài viết Đánh giá</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />            
            <?= $this->Form->create($review, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
            <?php
            $this->Form->setTemplates([
                'formStart' => '<form class="" {{attrs}}>',
                'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                'textarea' => '<div class="col-md-6 col-sm-6 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                'checkContainer' => ''
            ]);
            echo $this->Form->control('category_id', [
                'options' => $categories,
                'class' => 'form-control select2',
                'label' => 'Danh mục *',
                'required' => 'required'
            ]);
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
                    <div class='starrr-existing' data-rating="<?= $review->rating ?>"></div>
                    <input type="hidden" name="rating" />
                </div>
            </div>

            <?php
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
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
