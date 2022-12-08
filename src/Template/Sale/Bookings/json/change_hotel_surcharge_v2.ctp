<?php

use App\View\Helper\SystemHelper;

?>
<?php
//
//foreach ($autoSurcharges as $key => $surcharge) {
//    echo '<div class="row">';
//    echo '<div class="normal-surcharge-item col-sm-6">';
//    echo '<input type="hidden" value="'.$surcharge->id.'" />';
//    echo $this->Form->control('booking_surcharges.' . $key . '.id', [
//        'type' => 'hidden',
//        'value' => $surcharge->id
//    ]);
//    echo $this->Form->control('booking_surcharges.' . $key . '.surcharge_type', [
//        'type' => 'hidden',
//        'value' => $surcharge->surcharge_type
//    ]);
//    echo $this->Form->control('booking_surcharges.' . $key . '.price', [
//        'templates' => [
//            'label' => '<label class="control-label" {{attrs}}>{{text}}</label>',
//            'input' => '<input type="{{type}}" name="{{name}}" {{attrs}} />',
//            'inputContainer' => '<div class="item form-group">{{content}}</div>',
//        ],
//        'type' => 'text',
//        'class' => 'form-control currency',
//        'readonly' => true,
//        'label' => SystemHelper::getSurchargeName($surcharge->surcharge_type),
//        'id' => SystemHelper::getSurchargeId($surcharge->surcharge_type),
//        'value' => (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? number_format($arr_booking_surcharges[$surcharge->surcharge_type]['price']) : ''
//    ]);
//    echo '</div>';
//    echo '</div>';
//}
?>
<div class="row">
    <?php foreach ($autoSurcharges as $key => $surcharge): ?>
        <!--        --><?php //dd($surcharge); ?>
        <div class="normal-surcharge-item col-sm-6 form-group item">
            <input type="hidden" id="booking-surcharges-<?= $key ?>-id" name="booking_surcharges[<?= $key ?>][id]"
                   value="<?= $surcharge->id ?>">
            <input type="hidden" id="booking-surcharges-<?= $key ?>-surcharge-type"
                   name="booking_surcharges[<?= $key ?>][surcharge_type]" value="<?= $surcharge->surcharge_type ?>">
            <input type="hidden" id="booking-surcharges-<?= $key ?>-quantity"
                   name="booking_surcharges[<?= $key ?>][quantity]" value="0">
            <label for="">
                <p class="ds18"><?= SystemHelper::getSurchargeName($surcharge->surcharge_type) ?></p>
            </label>
            <input type="text" id="<?= SystemHelper::getSurchargeId($surcharge->surcharge_type) ?>"
                   class="form-control currency" style="background-color: lightgrey"
                   name="booking_surcharges[<?= $key ?>][price]" readonly="readonly"
                   value="<?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? number_format($arr_booking_surcharges[$surcharge->surcharge_type]['price']) : '' ?>">
        </div>
    <?php endforeach; ?>
    <?php foreach ($normalSurcharges as $nkey => $surcharge): ?>
        <div class="normal-surcharge-item col-sm-6 form-group item">
            <label class="control-label">
                <?= ($surcharge->surcharge_type != SUR_OTHER) ? SystemHelper::getSurchargeName($surcharge->surcharge_type) : $surcharge->other_name ?>
            </label>
            <div class="row">
                <div class="col-sm-8">
                    <input type="hidden" name="booking_surcharges[<?= $nkey ?>][id]" value="<?= $surcharge->id ?>">
                    <input type="hidden" name="booking_surcharges[<?= $nkey ?>][surcharge_type]"
                           value="<?= $surcharge->surcharge_type ?>"/>
                    <div
                        class="surcharge-normal-quantity <?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? '' : 'hidden' ?>">
                        <?php if ($surcharge->surcharge_type == SUR_CHECKIN_SOON || $surcharge->surcharge_type == SUR_CHECKOUT_LATE): ?>
                            <div class="input-group timepicker">
                                <input class="form-control " id="kt_timepicker_2" readonly
                                       placeholder="Select time" type="text" name="booking_surcharges[<?= $nkey ?>][quantity]"
                                       value="<?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? $arr_booking_surcharges[$surcharge->surcharge_type]['quantity'] : '' ?>" />
                                <div class="input-group-append">
                                                       <span class="input-group-text">
                                                        <i class="la la-clock-o"></i>
                                                       </span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="">
                                <select class="form-control popup-voucher normal-surcharge-value" id="amount"
                                        name="booking_surcharges[<?= $nkey ?>][quantity]">
                                    <?php for ($i = 0; $i <= 10; $i++): ?>
                                        <option
                                            value="<?= $i ?>" <?= (isset($arr_booking_surcharges[$surcharge->surcharge_type]) && $i == $arr_booking_surcharges[$surcharge->surcharge_type]['quantity']) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control currency" style="background-color: lightgrey"
                           id="<?= SystemHelper::getSurchargeId($surcharge->surcharge_type, $surcharge->other_slug) ?>"
                           name="booking_surcharges[<?= $nkey ?>][price]"
                           value="<?= (isset($arr_booking_surcharges[$surcharge->surcharge_type])) ? number_format($arr_booking_surcharges[$surcharge->surcharge_type]['price']) : '' ?>">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        KTBootstrapDatepicker.init();
    });
    // Class definition

    var KTBootstrapTimepicker = function () {

        // Private functions
        var time = function () {
            // minimum setup
            $('#kt_timepicker_2, #kt_timepicker_2_modal').timepicker({
                minuteStep: 1,
                defaultTime: '',
                showSeconds: true,
                showMeridian: false,
                snapToStep: true
            });
        }
        return {
            // public functions
            init: function () {
                time();
            }
        };
    }();
</script>

<!--<div class="pl-12">-->
<!--    <div class="row">-->
<!--        <div class="col-sm-6 item form-group">-->
<!--            <label for="hotel">-->
<!--                <p class="fs18">Người lớn</p>-->
<!--            </label>-->
<!--            <input type="text" name="room_single_price"-->
<!--                   readonly="readonly"-->
<!--                   class="form-control " style="background-color: lightgrey" id="booking-rooms-0-room-single-price">-->
<!--        </div>-->
<!---->
<!--        <div class="col-sm-6 item form-group">-->
<!--            <label for="hotel">-->
<!--                <p class="fs18">Trẻ nhỏ</p>-->
<!--            </label>-->
<!--            <input type="text" name="room_single_price"-->
<!--                   readonly="readonly"-->
<!--                   class="form-control " style="background-color: lightgrey " id="booking-rooms-0-room-single-price">-->
<!--        </div>-->
<!---->
<!--        <div class="col-sm-6 form-group">-->
<!--            <label for="room-total">-->
<!--                <p class="fs18">Gường phụ</p>-->
<!--            </label>-->
<!--            <div class="row">-->
<!--                <div class="col-sm-8">-->
<!--                    <div class="input-group timepicker">-->
<!--                        <input class="form-control" id="" type="text"/>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-sm-4">-->
<!--                    <input type="text" name="room_single_price"-->
<!--                           readonly="readonly" class="form-control " style="background-color: lightgrey "-->
<!--                           id="booking-rooms-0-room-single-price">-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-sm-6 form-group">-->
<!--            <label for="room-total">-->
<!--                <p class="fs18">Check Out muộn(<span class="text-danger">*</span>)</p>-->
<!--            </label>-->
<!--            <div class="row">-->
<!--                <div class="col-sm-8">-->
<!--                    <div class="input-group timepicker">-->
<!--                        <input class="form-control " id="kt_timepicker_2" readonly-->
<!--                               placeholder="Select time" type="text"/>-->
<!--                        <div class="input-group-append">-->
<!--                                                       <span class="input-group-text">-->
<!--                                                        <i class="la la-clock-o"></i>-->
<!--                                                       </span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-sm-4">-->
<!--                    <input type="text" name="room_single_price"-->
<!--                           readonly="readonly" class="form-control " style="background-color: lightgrey "-->
<!--                           id="booking-rooms-0-room-single-price">-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </div>-->
<!--</div>-->
