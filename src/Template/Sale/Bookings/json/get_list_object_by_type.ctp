<?php

$form_template = '';
if ($booking_type == SYSTEM_BOOKING) {
    $form_template = 'form#form-booking-system .hotel-room-system';
} else {
    $form_template = 'form#form-booking-another .hotel-room-system';
}
$bookingIdHtml = isset($booking_id)?",".$booking_id:"";
echo $this->Form->control('item_id', [
    'empty' => 'Chọn',
    'templates' => [
        'inputContainer' => '<div class="item form-group">{{content}}</div>',
        'label' => '<label class="control-label col-md-2 col-sm-2 col-xs-12" {{attrs}}>{{text}}</label>',
        'select' => '<div class="col-md-10 col-sm-10 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    ],
    'class' => 'form-control select2 listObject',
    'label' => $label,
    'default' => $item_id,
    'options' => $objects,
    'onchange' => "getListRoomsForHotel(this, " . $type . ", 'item_id', '" . $form_template . "'" . $bookingIdHtml ." )"
]);
?>
<div class="hotel-room-system">

</div>