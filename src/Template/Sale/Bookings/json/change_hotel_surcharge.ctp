<?php

use App\View\Helper\SystemHelper;

foreach ($autoSurcharges as $key => $surcharge) {
    echo '<div class="normal-surcharge-item">';
    echo $this->Form->control('booking_surcharges.' . $key . '.id', [
        'type' => 'hidden',
        'value' => $surcharge->id
    ]);
    echo $this->Form->control('booking_surcharges.' . $key . '.surcharge_type', [
        'type' => 'hidden',
        'value' => $surcharge->surcharge_type
    ]);
    echo $this->Form->control('booking_surcharges.' . $key . '.price', [
        'templates' => [
            'label' => '<label class="control-label col-md-4 col-sm-4 col-xs-12" {{attrs}}>{{text}}</label>',
            'input' => '<div class="col-md-8 col-sm-8 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
            'inputContainer' => '<div class="item form-group">{{content}}</div>',
        ],
        'type' => 'text',
        'class' => 'form-control currency',
        'readonly' => true,
        'label' => SystemHelper::getSurchargeName($surcharge->surcharge_type),
        'id' => SystemHelper::getSurchargeId($surcharge->surcharge_type),
        'value' => (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? number_format($arr_booking_surcharges[$surcharge->surcharge_type]['price']) : ''
    ]);
    echo '</div>';
}
?>
<?php foreach ($normalSurcharges as $nkey => $surcharge): ?>
    <div class="normal-surcharge-item">
        <div class="form-group">
            <input type="hidden" name="booking_surcharges[<?= $nkey ?>][id]" value="<?= $surcharge->id ?>">
            <div class="col-md-1 col-sm-1">
                <input type="checkbox" class="form-control flat surcharge-check" name="booking_surcharges[<?= $nkey ?>][surcharge_type]" <?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? 'checked' : '' ?>>
                <input type="hidden" name="booking_surcharges[<?= $nkey ?>][surcharge_type]" value="<?= $surcharge->surcharge_type ?>"/>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <label class="control-label">
                    <?= ($surcharge->surcharge_type != SUR_OTHER) ? SystemHelper::getSurchargeName($surcharge->surcharge_type) : $surcharge->other_name ?>
                </label>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="surcharge-normal-quantity <?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? '' : 'hidden' ?>">
                    <?php if ($surcharge->surcharge_type == SUR_CHECKIN_SOON || $surcharge->surcharge_type == SUR_CHECKOUT_LATE): ?>
                        <div class="col-sm-18">
                            <input type='text' class="form-control timepicker" name="booking_surcharges[<?= $nkey ?>][quantity]" placeholder="Giờ" value="<?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? $arr_booking_surcharges[$surcharge->surcharge_type]['quantity'] : '' ?>"/>
                        </div>
                    <?php else: ?>
                        <div class="col-sm-18">
                            <select class="form-control popup-voucher normal-surcharge-value" id="amount" name="booking_surcharges[<?= $nkey ?>][quantity]">
                                <option value="">Số lượng</option>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($arr_booking_surcharges[$surcharge->surcharge_type]) && $i == $arr_booking_surcharges[$surcharge->surcharge_type]['quantity']) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-12">
                <input type="text" readonly class="form-control currency" id="<?= SystemHelper::getSurchargeId($surcharge->surcharge_type, $surcharge->other_slug) ?>" name="booking_surcharges[<?= $nkey ?>][price]" value="<?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? number_format($arr_booking_surcharges[$surcharge->surcharge_type]['price']) : '' ?>">
            </div>
        </div>
    </div>

<?php endforeach; ?>
