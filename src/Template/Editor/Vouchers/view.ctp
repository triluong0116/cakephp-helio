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
            <td><?= h($voucher->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Điểm đi') ?></th>
            <td><?= $voucher->has('departure') ? $this->Html->link($voucher->departure->name, ['controller' => 'Locations', 'action' => 'view', $voucher->departure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Điểm đến') ?></th>
            <td><?= $voucher->has('destination') ? $this->Html->link($voucher->destination->name, ['controller' => 'Locations', 'action' => 'view', $voucher->destination->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh đại diện') ?></th>
            <td>
                <?= ($voucher->thumbnail) ? $this->Html->image('/' . $voucher->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-7']) : "" ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Giá') ?></th>
            <td><?= $this->Number->currency($voucher->price, 'VND') ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Giá CTV') ?></th>
            <td><?= $this->Number->currency($voucher->trippal_price, 'VND') ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Giá Khách hàng') ?></th>
            <td><?= $this->Number->currency($voucher->customer_price, 'VND') ?> %</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Khuyến mãi') ?></th>
            <td><?= $this->Number->format($voucher->promote) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Đánh giá') ?></th>
            <td><?= $this->Number->format($voucher->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ngày bắt đầu') ?></th>
            <td><?= h($voucher->start_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ngày kết thúc') ?></th>
            <td><?= h($voucher->end_date) ?></td>
        </tr>        
        <tr>
            <th scope="row"><?= __('Mô tả') ?></th>
            <td><?= $voucher->description ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Album ảnh') ?></th>
            <td><?php
                if ($voucher->media) {
                    $medias = json_decode($voucher->media);
                    foreach ($medias as $image) {
                        if ($image)
                            echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                    }
                }
                ?>
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
