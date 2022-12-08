<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher $voucher
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Tên voucher') ?></th>
            <td><?= h($requestVoucher->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thời hạn') ?></th>
            <td><?= h($requestVoucher->time) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Giá voucher') ?></th>
            <td><?= h($requestVoucher->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tên người bán') ?></th>
            <td><?= h($requestVoucher->full_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Số điện thoại') ?></th>
            <td><?= h($requestVoucher->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($requestVoucher->email) ?></td>
        </tr>
        <tr>
            <th scope="row" class="actions"><?= __('Actions') ?></th>
            <td class="actions">
                <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $requestVoucher->id], ['confirm' => __('Bạn có chắc muốn xóa Combo: {0}?', $requestVoucher->title), 'class' => 'btn btn-xs btn-danger']); ?>
            </td>
        </tr>
    </table>

    <div class="related">
        <?php if (!empty($voucher->rooms)): ?>
            <h4><?= __('Related Rooms') ?></h4>
            <table class="table table-striped table-responsive">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"><?= __('Hotel Id') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                    <th scope="col"><?= __('Slug') ?></th>
                    <th scope="col"><?= __('Area') ?></th>
                    <th scope="col"><?= __('Num Bed') ?></th>
                    <th scope="col"><?= __('Thumbnail') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($voucher->rooms as $key => $rooms): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($rooms->hotel_id) ?></td>
                        <td><?= h($rooms->name) ?></td>
                        <td><?= h($rooms->slug) ?></td>
                        <td><?= h($rooms->area) ?></td>
                        <td><?= h($rooms->num_bed) ?></td>
                        <td>
                            <?= ($rooms->thumbnail) ? $this->Html->image('/' . $rooms->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-5']) : "" ?>
                        </td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'view', $rooms->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'edit', $rooms->id]) ?>">Sửa</a>
                            <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $rooms->id], ['confirm' => __('Bạn có chắc muốn xóa Combo: {0}?', $rooms->name), 'class' => 'btn btn-xs btn-danger']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
