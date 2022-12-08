<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Chỉnh sửa danh mục</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?= $this->Form->create($category, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate']) ?>
            <?php
            $this->Form->setTemplates([
                'formStart' => '<form class="" {{attrs}}>',
                'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                'textarea' => '<div class="col-md-6 col-sm-6 col-xs-12"><textarea name="{{name}}" {{attrs}}>{{content}}{{value}}</textarea></div>',
                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                'checkContainer' => ''
            ]);
            if ($category->parent_id)
                echo $this->Form->control('parent_id',
                    [
                        'options' => $parentCategories,
                        'label' => 'Danh mục cha'
                    ]
                );
            echo $this->Form->control('name',
                [
                    'label' => 'Tên danh mục'
                ]);
            echo $this->Form->control('icon', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Icon *',
            ]);
            ?>
            <?= $this->Form->button(__('Cập nhật')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
