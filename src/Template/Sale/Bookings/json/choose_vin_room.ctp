<div class="row">
    <div class="col-sm-1">
        <button type="button" class="btn btn-success mt15 btnAddNewPackage" data-toggle="modal" data-target="#modalAddNewPackage"
                data-vinroom-id="<?= $room_id ?>"
                data-vinroom-index="<?= $id ?>"
                data-hotel-id="<?= $hotelId ?>"
                data-num-adult="<?= $numAdult ?>"
                data-num-child="<?= $numChild ?>"
                data-num-kid="<?= $numKid ?>"
                style="border-radius: 50%; padding: 6px 11px !important;">
            <i class="fa fa-plus"></i>
        </button>
    </div>
    <div class="col-sm-11">
        <div class="row">
            <div class="col-sm-7 mt10">
                <p class="fs14 bold">Phòng <?= $id + 1 ?> : <?= $roomData['name'] ?> </p>
            </div>
            <div class="col-sm-5 mt10">
                <p class="pull-right fs14 bold"><span class="total-vin-room-<?= $id ?>"><?= $price ?></span> VNĐ</p>
            </div>
            <div class="col-sm-12">
                <div class="row list-package-room-<?= $id ?>">
                    <div class="single-package">
                        <input type="hidden" class="start_date_vin" name="package[0][start_date]" value="<?= $startDate ?>">
                        <input type="hidden" class="end_date_vin" name="package[0][end_date]" value="<?= $endDate ?>">
                        <div class="col-sm-7">
                            <p class="fs14" style="margin-bottom: 0px">Gói: <?= $packageCode ?></p>
                            <p class="mt05 fs14"><?= $startDate ?> - <?= $endDate ?></p>
                        </div>
                        <div class="col-sm-5">
                            <p class="fs14 pull-right"><?= $price ?> VNĐ</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-xs btn-warning text-center remove-package-room" data-room-id="<?= $id ?>">
                            Xóa gói
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
