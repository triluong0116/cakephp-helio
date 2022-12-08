<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm mới Danh mục</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />            
            <?= $this->Form->create($category, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate']) ?>
            <?php
            $this->Form->setTemplates([
                'formStart' => '<form class="" {{attrs}}>',
                'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                'checkContainer' => ''
            ]);
            echo $this->Form->control('parent_id', [
                'empty' => 'Chọn danh mục Cha',
                'type' => 'select',
                'options' => $parentCategories,
                'class' => 'form-control',
                'label' => 'Danh mục cha'
            ]);
            echo $this->Form->control('name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên Danh mục *',
                'required' => 'required'
            ]);
            echo $this->Form->control('icon', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Icon *',
            ]);
            ?>
            <input type="hidden" name="is_active" value="1"/>
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>