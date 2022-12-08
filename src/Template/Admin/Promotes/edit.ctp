<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Promote $promote
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Sửa Khuyến Mãi</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />            
            <?= $this->Form->create($promote, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
            echo $this->Form->control('title', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên Chương trình *',
                'required' => 'required'
            ]);
            echo $this->Form->control('description', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Mô tả *',
                'required' => 'required'
            ]);
            ?>
            <div class="control-group">
                <div class="controls">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Thời gian *</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="reservation" class="custom-daterange-picker form-control" value="<?= date_format($promote->start_date, 'd-m-Y') . ' - ' . date_format($promote->end_date, 'd-m-Y')?>" />
                        </div>
                    </div>
                </div>
            </div>   
            <div class="clearfix"></div>  
            <?php
            echo $this->Form->control('type', [
                'options' => $promoteTypes,
                'class' => 'form-control',
                'id' => 'choose-promote-type',
                'label' => 'Loại hình khuyến mãi *',
                'data-object-id' => $promote->object_id,
                'data-num-book' => $promote->num_booking,
                'data-num-share' => $promote->num_share,
                'required' => 'required',
                'onchange' => 'choosePromoteType(this)'
            ]);
            ?>
            <div id="promote-other-content">
                
            </div>
            <?php
            echo $this->Form->control('revenue', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Mức thưởng *',
                'required' => 'required'
            ]);
            ?>                                  
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