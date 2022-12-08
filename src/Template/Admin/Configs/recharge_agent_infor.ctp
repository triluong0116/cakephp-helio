<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location $location
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
?>
<?= $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm tài khoản ngân hàng không xuất hóa đơn</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="list-account">
                <?php
                $this->Form->setTemplates([
                    'formStart' => '<form class="" {{attrs}}>',
                    'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-5 col-sm-5 col-xs-6"><input type="{{type}}" name="{{name}}" {{attrs}} value="{{value}}" /></div>',
                    'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}" {{attrs}}>{{content}}</select></div>',
                    'textarea' => '<div class="col-md-6 col-sm-6 col-xs-12"><textarea name="{{name}}" {{attrs}}>{{content}}{{value}}</textarea></div>',
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                    'checkContainer' => ''
                ]);
                if (isset($bank_accounts)) {
                    foreach ($bank_accounts as $key => $bank_account) {
                        ?>
                        <div class="bank-account">
                            <div class="row">
                                <div class="col-sm-10">
                                    <?php
                                    echo $this->Form->control('bank_account.' . $key . '.bank_name', [
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Tên ngân hàng *',
                                        'required' => 'required',
                                        'default' => $bank_account['bank_name']
                                    ]);
                                    echo $this->Form->control('bank_account.' . $key . '.account_name', [
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Tên chủ tài khoản *',
                                        'required' => 'required',
                                        'default' => $bank_account['account_name']
                                    ]);
                                    echo $this->Form->control('bank_account.' . $key . '.account_number', [
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Số tài khoản *',
                                        'required' => 'required',
                                        'default' => $bank_account['account_number']
                                    ]);
                                    echo $this->Form->control('bank_account.' . $key . '.bank_branch', [
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'label' => 'Chi nhánh *',
                                        'required' => 'required',
                                        'default' => $bank_account['bank_branch']
                                    ]);
                                    echo $this->Form->control('bank_account.' . $key . '.bank_logo', [
                                        'type' => 'file',
                                        'label' => 'Logo ngân hàng'
                                    ]);
                                    echo $this->Form->control('bank_account.' . $key . '.bank_logo_edit', [
                                        'class' => 'form-control',
                                        'type' => 'hidden',
                                        'value' => $bank_account['bank_logo_edit']
                                    ]);
                                    ?>
                                    <br>
                                    <div class="col-sm-3 col-sm-offset-3">
                                        <?= $this->Html->image('/' . $bank_account['bank_logo'], ['alt' => 'thumbnail', 'class' => 'img-responsive']); ?>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-sm-2 text-right">
                                    <a href="#" onclick="deleteItem(this, '.bank-account');" class="mt10">
                                        <i class="text-danger fa fa-minus" ></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr/>
                    <?php } ?>
                <?php } ?>
                <a class="btn btn-success" onclick="addBankAccount(this, '.list-account')"><i class="fa fa-plus"></i> Thêm tài khoản <i class="fa fa-spinner fa-spin hidden"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</div>
<?= $this->Form->end() ?>
