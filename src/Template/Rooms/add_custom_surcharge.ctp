<div class="custom-surcharge-item" data-type="<?= $type ?>">
    <div class="row mt10">
        <?php if ($type == SUR_CHILDREN): ?>
            <div class="col-xs-3 col-sm-3">
                <?php
                echo $this->Form->control('hotel_surcharges[0][options][0][start]', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Từ Tuổi *',
                    'required' => 'required',
                    'readonly' => true,
                    'default' => $newValue,
                ]);
                ?>
            </div>
            <div class="col-xs-3 col-sm-3">
                <?php
                echo $this->Form->control('hotel_surcharges[0][options][0][end]', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => 'Đến tuổi *',
                    'required' => 'required',
                    'onchange' => 'updateNextCustomSurchargeValue(this, ' . $type . ')'
                ]);
                ?>
            </div>
        <?php endif; ?>
        <?php if ($type == SUR_CHECKIN_SOON): ?>
            <div class="col-xs-3 col-sm-3">
                <?php
                echo $this->Form->control('hotel_surcharges[0][options][0][start]', [
                    'type' => 'text',
                    'class' => 'form-control timepicker',
                    'label' => 'Từ Giờ *',
                    'required' => 'required',
                    'onchange' => 'updateNextCustomSurchargeValue(this, ' . $type . ')',
                ]);
                ?>
            </div>
            <div class="col-xs-3 col-sm-3">
                <?php
                echo $this->Form->control('hotel_surcharges[0][options][0][end]', [
                    'type' => 'text',
                    'class' => 'form-control timepicker',
                    'label' => 'Đến Giờ *',
                    'required' => 'required',
                    'readonly' => true,
                    'default' => $newValue
                ]);
                ?>
            </div>
        <?php endif; ?>
        <?php if ($type == SUR_CHECKOUT_LATE): ?>
            <div class="col-xs-3 col-sm-3">
                <?php
                echo $this->Form->control('hotel_surcharges[0][options][0][start]', [
                    'type' => 'text',
                    'class' => 'form-control timepicker',
                    'label' => 'Từ Giờ *',
                    'required' => 'required',
                    'readonly' => true,
                    'default' => $newValue
                ]);
                ?>
            </div>
            <div class="col-xs-3 col-sm-3">
                <?php
                echo $this->Form->control('hotel_surcharges[0][options][0][end]', [
                    'type' => 'text',
                    'class' => 'form-control timepicker',
                    'label' => 'Đến Giờ *',
                    'required' => 'required',
                    'onchange' => 'updateNextCustomSurchargeValue(this, ' . $type . ')',
                ]);
                ?>
            </div>
        <?php endif; ?>
        <div class="col-xs-5 col-sm-5">
            <?php
            echo $this->Form->control('hotel_surcharges[0][options][0][price]', [
                'type' => 'text',
                'class' => 'form-control currency',
                'label' => 'Đơn giá *',
                'required' => 'required',
            ]);
            ?>
        </div>
        <div class="col-sm-1 col-xs-1 text-right">
            <a href="#" onclick="deleteChildItem(this, '.custom-surcharge-item');" class="mt10">
                <i class="text-danger fa fa-minus"></i>
            </a>
        </div>
    </div>
</div>

