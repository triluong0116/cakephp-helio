<div class="package-input-<?= $listRoom['packageIndex'] ?> single-packet-input" data-price="<?= $listRoom['packagePrice'] ?>" data-sale-revenue="<?= $listRoom['saleRevenue'] ?>" data-revenue="<?= $listRoom['revenue'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][code]" value="<?= $listRoom['packageCode'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][package_name]" value="<?= $listRoom['packageName'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][price]" value="<?= $listRoom['packagePrice'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][default_price]" value="<?= $listRoom['defaultPrice'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][package_id]" value="<?= $listRoom['packageId'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][rateplan_code]" value="<?= $listRoom['ratePlanCode'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][revenue]" value="<?= $listRoom['revenue'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][sale_revenue]" value="<?= $listRoom['saleRevenue'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][rateplan_id]" value="<?= $listRoom['rateplanId'] ?>">
    <input type="hidden" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][allotment_id]" value="<?= $listRoom['allotmentId'] ?>">
    <input type="hidden" class="last-package-start-date" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][start_date]" value="<?= $fromDate ?>">
    <input type="hidden" class="last-package-end-date" name="vin_room[<?= $listRoom['roomIndex'] ?>][package][<?= $listRoom['packageIndex'] ?>][end_date]" value="<?= $toDate ?>">
</div>
