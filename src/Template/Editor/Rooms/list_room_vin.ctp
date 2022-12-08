<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room[]|\Cake\Collection\CollectionInterface $rooms
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12 mt10">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách phòng của <?= $hotel->name ?></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?= $this->Form->create() ?>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th scope="col">
                        Tên phòng
                    </th>
                    <th scope="col">
                        Mã phòng
                    </th>
                    <th scope="col">
                        Loại lợi nhuận Mustgo
                    </th>
                    <th scope="col">
                        Lợi nhuận Mustgo
                    </th>
                    <th scope="col">
                        Loại lợi nhuận Đại lý
                    </th>
                    <th scope="col">
                        Lợi nhuận Đại lý
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php $k = 0 ?>
                <?php foreach ($listRoom as $roomCode => $singleRoom): ?>
                    <tr>
                        <td><?= $k + 1 ?></td>
                        <td><?= $singleRoom['name'] ?></td>
                        <td><?= $roomCode ?></td>
                        <input type="hidden" name="vin_room[<?= $k ?>][vin_code]" value="<?= $roomCode ?>">
                        <input type="hidden" name="vin_room[<?= $k ?>][hotel_id]" value="<?= $hotel->id ?>">
                        <td>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $singleRoom['trippal_price_type'] == 0 ? 'checked' : '' ?> name="vin_room[<?= $k ?>][trippal_price_type]" value="0"> Cố định</i></p>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $singleRoom['trippal_price_type'] == 1 ? 'checked' : '' ?> name="vin_room[<?= $k ?>][trippal_price_type]" value="1"> Theo %</i></p>
                        </td>
                        <td><input type="text" name="vin_room[<?= $k ?>][trippal_price]" value="<?= number_format($singleRoom['trippal_price']) ?>" class="form-control"></td>
                        <td>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $singleRoom['customer_price_type'] == 0 ? 'checked' : '' ?> name="vin_room[<?= $k ?>][customer_price_type]" value="0"> Cố định</i></p>
                            <p class="fs14 mb15 text-light-blue ml10"><input type="radio" class="iCheck" <?= $singleRoom['customer_price_type'] == 1 ? 'checked' : '' ?> name="vin_room[<?= $k ?>][customer_price_type]" value="1"> Theo %</i></p>
                        </td>
                        <td><input type="text" name="vin_room[<?= $k ?>][customer_price]" value="<?= number_format($singleRoom['customer_price']) ?>" class="form-control"></td>
                        <td>
                            <a href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'detailRoomVin', $roomCode, $hotel->id]) ?>" class="btn btn-primary">Chi tiết</a>
                        </td>
                    </tr>
                <?php $k++ ?>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (count($listRoom) > 0): ?>
            <button class="btn btn-success">Save</button>
            <?php endif; ?>
            <?= $this->Form->end() ?>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
