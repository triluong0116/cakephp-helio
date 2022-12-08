<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm mới Người dùng</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?= $this->Form->create($user, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate']) ?>
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
                'onchange' => 'pickManager(this)',
                'empty' => 'Chọn vai trò',
                'type' => 'select',
                'options' => $roles,
                'class' => 'form-control',
                'required' => 'required',
                'label' => 'Vai trò *'
            ]);
            echo '<div id="pickParentId" class="hidden">';
            echo $this->Form->control('parent_id', [
                'empty' => 'Chọn Sale Phòng',
                'type' => 'select',
                'options' => $managers,
                'class' => 'form-control',
                'label' => 'Chọn Sale Phòng *'
            ]);
            echo $this->Form->control('landtour_parent_id', [
                'empty' => 'Chọn Sale Landtour',
                'type' => 'select',
                'options' => $landtour_managers,
                'class' => 'form-control',
                'label' => 'Chọn Sale Landtour *'
            ]);
            echo '</div>';
            echo $this->Form->input('username', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Username *',
                'required' => 'required'
            ]);
            echo $this->Form->input('password', [
                'type' => 'password',
                'class' => 'form-control',
                'required' => 'required',
                'label' => 'Mật khẩu *'
            ]);
            echo $this->Form->input('screen_name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên hiển thị *',
                'required' => 'required'
            ]);
            echo '<div id="telegramSale" class="hidden">';
            echo $this->Form->control('telegram_id', [
                'empty' => 'Telegram Id',
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Telegram Id *'
            ]);
            echo $this->Form->control('telegram_username', [
                'empty' => 'Telegram username',
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Telegram Username *'
            ]);
            echo '</div>';
            echo $this->Form->input('email', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Email *',
                'required' => 'required'
            ]);
            echo $this->Form->input('email_access_code', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Email access code *',
                'required' => 'required'
            ]);
            echo $this->Form->input('phone', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Phone number *',
                'required' => 'required'
            ]);
            echo $this->Form->input('fbid', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Link facebook *',
                'required' => 'required'
            ]);
            echo $this->Form->input('zalo', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Zalo *',
                'required' => 'required'
            ]);
            //            echo $this->Form->input('email_access_code', [
            //                'type' => 'password',
            //                'class' => 'form-control',
            //                'label' => 'Email Acess Code',
            //                'required' => 'required'
            //            ]);
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
