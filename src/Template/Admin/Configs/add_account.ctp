<div class="bank-account">
    <hr />
    <div class="row">
        <div class="col-sm-10 col-xs-10">
            <?php
            $this->Form->setTemplates([
                'formStart' => '<form class="" {{attrs}}>',
                'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
                'input' => '<div class="col-md-10 col-sm-10 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} value="{{value}}" /></div>',
                'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}" {{attrs}}>{{content}}</select></div>',
                'textarea' => '<div class="col-md-6 col-sm-6 col-xs-12"><textarea name="{{name}}" {{attrs}}>{{content}}{{value}}</textarea></div>',
                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                'checkContainer' => ''
            ]);
            echo $this->Form->control('bank_account.0.bank_name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên ngân hàng *',
                'required' => 'required',
            ]);
            echo $this->Form->control('bank_account.0.account_name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên chủ tài khoản *',
                'required' => 'required',
            ]);
            echo $this->Form->control('bank_account.0.account_number', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số tài khoản *',
                'required' => 'required',
            ]);
            echo $this->Form->control('bank_account.0.bank_branch', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Chi nhánh *',
                'required' => 'required',
            ]);
            echo $this->Form->control('bank_account.0.bank_logo', [
                'type' => 'file',
                'label' => 'Logo ngân hàng',
                'required' => 'required'
            ]);
            echo $this->Form->control('bank_account.0.bank_logo_edit', [
                'type' => 'hidden'
            ]);
            ?>
        </div>
        <div class="col-sm-2 col-sm-2 text-right">
            <a href="#" onclick="deleteItem(this, '.bank-account');" class="mt10">
                <i class="text-danger fa fa-minus" ></i>
            </a>
        </div>
    </div>
</div>
