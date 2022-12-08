
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Đổi mật khẩu</h2>
            <div class="clearfix"></div>
        </div>
        <?= $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
            <div class="x_content">
                <br/>
                <?php
                echo $this->Form->control('oldPassword', [
                    'type' => 'password',
                    'class' => 'form-control',
                    'label' => 'Mật khẩu cũ*',
                    'required' => 'required'
                ]);
                ?>
                <br/>
                <?php
                echo $this->Form->control('newPassword', [
                    'type' => 'password',
                    'class' => 'form-control',
                    'label' => 'Mật khẩu mới*',
                    'required' => 'required'
                ]);
                ?>
                <br/>
                <?php
                echo $this->Form->control('confPassword', [
                    'type' => 'password',
                    'class' => 'form-control',
                    'label' => 'Nhập lại mật khẩu mới*',
                    'required' => 'required'
                ]);
                ?>
                <br/>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                        <button type="submit" class="btn btn-success" id="blog-submit">Submit</button>
                    </div>
                </div>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>