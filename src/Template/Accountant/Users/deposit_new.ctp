<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Đại lý nạp tiền</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?= $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate']) ?>
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
            echo $this->Form->control('role_id', [
                'empty' => 'Chọn Đại lý',
                'type' => 'select',
                'options' => $users,
                'class' => 'form-control select2',
                'required' => 'required',
                'label' => 'Chọn Đại lý *'
            ]);
            echo $this->Form->control('title', [
                'class' => 'form-control',
                'label' => 'Tiêu đề',
                'required' => 'required',
            ]);
            echo $this->Form->control('message', [
                'class' => 'form-control',
                'label' => 'Nội dung chuyển khoản',
                'readonly ' ,
                'value' => $code,
            ]);
            echo $this->Form->control('amount', [
                'class' => 'form-control currency',
                'label' => 'Số tiền  *',
                'required' => 'required'
            ]);
            ?>
            <div class="deligate-payment">
                <div class="row ml15 mr15 mb15 mt15">
                    <h4 class="fs14 mb10">Ảnh hóa đơn thanh toán</h4>
                    <p class="error-messages" id="error_images"></p>
                    <div class="col-sm-36 text-center">
                        <div id="dropzone-upload" class="dropzone">
                        </div>
                        <input type="hidden" name="media" value=''/>
                    </div>
                </div>
            </div>

            <input type="hidden" name="type" value="1"/>
            <input type="hidden" name="status" value="2"/>
            <input type="hidden" name="is_active" value="1" disabled/>
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
