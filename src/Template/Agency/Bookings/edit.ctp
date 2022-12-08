<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm mới Booking</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />            
            <?= $this->Form->create($booking, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
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
            echo $this->Form->control('combo_id', [
                'class' => 'form-control select2',
                'label' => 'Chọn Combo *',
                'options' => $combos,
                'required' => 'required'
            ]);
            echo $this->Form->input('full_name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Họ Tên Khách hàng *',
                'required' => 'required'
            ]);
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Giới tính</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="radio" class="flat" checked name="gender" value="1"> Nam
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" class="flat" name="gender" value="2"> Nữ
                        </label>
                    </div>              
                </div>
            </div>
            <?php
            echo $this->Form->input('email', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Email *',
                'required' => 'required'
            ]);
            echo $this->Form->input('phone', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số ĐT *',
                'required' => 'required'
            ]);
            echo $this->Form->input('other', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Thông tin thêm'
            ]);
            ?>

            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
