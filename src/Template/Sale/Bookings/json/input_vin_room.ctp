<input type="hidden" name="vin_room[<?= $id ?>][id]" value="<?= $room_id ?>">
<input type="hidden" name="vin_room[<?= $id ?>][name]" value="<?= $roomData['name'] ?>">
<input type="hidden" name="vin_room[<?= $id ?>][room_type_code]" value="<?= $roomTypeCode ?>">
<div class="list-package-input-room-<?= $id ?>">
    <div class="package-input-0 single-packet-input" data-price="<?= $price ?>" data-sale-revenue="<?= $saleRevenue ?>" data-revenue="<?= $revenue ?>">
        <input type="hidden" name="vin_room[<?= $id ?>][package][0][code]" value="<?= $packageCode ?>">
        <input type="hidden" name="vin_room[<?= $id ?>][package][0][package_name]" value="<?= $packageName ?>">
        <input type="hidden" class="package-price" name="vin_room[<?= $id ?>][package][0][price]" value="<?= $price ?>">
        <input type="hidden" name="vin_room[<?= $id ?>][package][0][default_price]" value="<?= $defaultPrice ?>">
        <input type="hidden" name="vin_room[<?= $id ?>][package][0][package_id]" value="<?= $package_id ?>">
        <input type="hidden" name="vin_room[<?= $id ?>][package][0][rateplan_code]" value="<?= $ratePlanCode ?>">
        <input type="hidden" class="package-revenue" name="vin_room[<?= $id ?>][package][0][revenue]" value="<?= $revenue ?>">
        <input type="hidden" class="package-sale-revenue" name="vin_room[<?= $id ?>][package][0][sale_revenue]" value="<?= $saleRevenue ?>">
        <input type="hidden" name="vin_room[<?= $id ?>][package][0][rateplan_id]" value="<?= $rateplan_id ?>">
        <input type="hidden" name="vin_room[<?= $id ?>][package][0][allotment_id]" value="<?= $allotmentId ?>">
        <input type="hidden" class="last-package-start-date" name="vin_room[<?= $id ?>][package][0][start_date]" value="<?= $startDate ?>">
        <input type="hidden" class="last-package-end-date" name="vin_room[<?= $id ?>][package][0][end_date]" value="<?= $endDate ?>">
    </div>
</div>
