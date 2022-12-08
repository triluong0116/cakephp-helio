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
            echo $this->Form->control('type', [
                'empty' => 'Chọn loại hình',
                'class' => 'form-control select2',
                'label' => 'Chọn Loại hình *',
                'id' => 'choose-type-booking',
                'default' => (isset($dataValiError['type']) && !empty($dataValiError['type'])) ? $dataValiError['type'] : '',
                'data-item-id' => (isset($dataValiError['item_id']) && !empty($dataValiError['item_id'])) ? $dataValiError['item_id'] : '',
                'options' => $object_types,
                'required' => 'required',
                'onchange' => 'getListObjectByType(this)'
            ]);
            ?>
            <div id="list-object">
            </div>
            <div class="control-group">
                <div class="controls">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Thời gian *</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                            <input type="text" name="reservation" class="custom-daterange-picker form-control" value="<?= (isset($dataValiError['reservation']) && !empty($dataValiError['reservation'])) ? $dataValiError['reservation'] : '' ?>" />
                        </div>
                    </div>
                </div>
            </div>                        
            <div class="clearfix"></div>
            <?php
            echo $this->Form->control('amount', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số lượng *',
                'required' => 'required'
            ]);
            echo $this->Form->control('full_name', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Họ Tên Khách hàng *',
                'required' => 'required'
            ]);

            echo $this->Form->control('phone', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số ĐT *',
                'required' => 'required'
            ]);
//            echo $this->Form->control('num_room', [
//                'type' => 'text',
//                'class' => 'form-control',
//                'label' => 'Số phòng *',
//                'required' => 'required'
//            ]);
            echo $this->Form->control('hotel_code', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Mã Booking khách sạn'                
            ]);
            echo $this->Form->control('room_level', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Hạng phòng',                
            ]);
            echo $this->Form->control('people_amount', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Số lượng người *',
                'required' => 'required'
            ]);
            echo $this->Form->control('adult_fee', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Phụ thu người lớn',                
            ]);
            echo $this->Form->control('children_fee', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Phụ thu trẻ em',                
            ]);
            echo $this->Form->control('holiday_fee', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Phụ thu ngày lễ',                
            ]);
            echo $this->Form->control('other_fee', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Phụ thu khác',                
            ]);
            echo $this->Form->control('car', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Hãng xe',                
            ]);
            echo $this->Form->control('service', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Lưu ý gửi CTV',
            ]);
            echo $this->Form->control('note', [
                'type' => 'textarea',
                'class' => 'form-control',
                'label' => 'Lưu ý gửi khách sạn',
            ]);
            echo $this->Form->control('other', [
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
