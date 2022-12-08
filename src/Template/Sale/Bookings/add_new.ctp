<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
if ($this->request->getData()) {
    $dataValiError = $this->request->getData();
} else {
    $dataValiError = [];
}
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm mới Booking</h2>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="form-horizontal form-label-left">
    <?= $this->Form->create($booking, ['class' => '', 'data-parsley-validate', 'id' => 'form-booking-system', 'type' => 'file']) ?>
    <?php
    $this->Form->setTemplates([
        'formStart' => '<form class="" {{attrs}}>',
        'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
        'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
        'select' => '<div class="col-md-8 col-sm-8 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
        'textarea' => '<div class="col-md-8 col-sm-8 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}{{value}}</textarea></div>',
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
        'checkContainer' => ''
    ]) ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Thêm mới Booking thuộc hệ thống</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br/>
                <input type="hidden" value="<?= SYSTEM_BOOKING ?>" name="booking_type">
                <input type="hidden" value="1" name="creator_type">
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                        echo $this->Form->control('user_id', [
                            'empty' => 'Chọn cộng tác viên',
                            'label' => 'Chọn cộng tác viên *',
                            'class' => 'form-control select2',
                            'options' => $objects,
                            'required' => 'required',
                            'onchange' => 'changeAgency(this)'
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo $this->Form->control('type', [
                            'empty' => 'Chọn loại hình',
                            'class' => 'form-control text-left select2',
                            'label' => 'Chọn Loại hình *',
                            'id' => 'choose-type-booking-system',
                            'default' => (isset($dataValiError['type']) && !empty($dataValiError['type'])) ? $dataValiError['type'] : '',
                            'data-item-id' => (isset($dataValiError['item_id']) && !empty($dataValiError['item_id'])) ? $dataValiError['item_id'] : '',
                            'data-room-level' => '',
                            'data-form-id' => 'form-booking-system',
                            'options' => $object_types,
                            'required' => 'required',
                            'onchange' => "showFormBookingByType(this, '#form-by-type-system')"
                        ]);
                        ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div id="form-by-type-system">

    </div>
    <?= $this->Form->end() ?>
</div>
