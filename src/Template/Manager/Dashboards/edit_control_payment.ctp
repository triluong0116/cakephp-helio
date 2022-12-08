<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
//dd($dataValiError);
?>
<?= $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file']) ?>
<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-9 col-sm-9 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-9 col-sm-9 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-9 col-sm-9 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]) ?>
<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm điều hành chi phí</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <?php
            echo $this->Form->control('fee_type', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Loại chi phí',
                'value' => $fee->fee_type
            ]);
            echo $this->Form->control('partner_name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Tên đối tác',
                'value' => $fee->partner_name
            ]);
            echo $this->Form->control('partnet_information', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Thông tin đối tác',
                'value' => $fee->partnet_information
            ]);
            echo $this->Form->control('detail', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Diễn giải chi phí',
                'value' => $fee->detail
            ]);
            echo $this->Form->control('single_price', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Đơn giá',
                'onchange' => 'calManageLandtourFee()',
                'value' => number_format($fee->single_price)
            ]);
            echo $this->Form->control('amount', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số lượng',
                'onchange' => 'calManageLandtourFee()',
                'value' => number_format($fee->amount)
            ]);
            echo $this->Form->control('total', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Tổng',
                'value' => number_format($fee->total),
                'readonly' => true,
            ]);
            ?>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Thanh toán</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select name="payment_status" class="form-control">
                        <?php foreach ($paymentStatus as $k => $status): ?>
                            <option value="<?= $k ?>" <?= $k == $fee->payment_status ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Loại hình thanh toán</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select name="payment_type" class="form-control">
                        <?php foreach ($paymentType as $k => $type): ?>
                            <option value="<?= $k ?>" <?= $k == $fee->payment_type ? 'selected' : '' ?>><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Chọn ngày</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="input-prepend input-group">
                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                        <input type="text" name="date" value="<?= date_format($fee->date, 'd/m/Y') ?>" class="custom-singledate-picker form-control"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-offset-3 col-sm-9">
                    <button class="btn btn-success">
                        Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
