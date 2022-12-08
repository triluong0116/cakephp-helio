<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hotel $hotel
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive">
        <tr>
            <th scope="row"><?= __('Tên khách sạn') ?></th>
            <td><?= h($hotel->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Địa điểm') ?></th>
            <td><?= $hotel->has('location') ? $this->Html->link($hotel->location->name, ['controller' => 'Locations', 'action' => 'view', $hotel->location->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Địa chỉ cụ thể') ?></th>
            <td><?= $hotel->map ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh đại diện') ?></th>
            <td>
                <?= ($hotel->thumbnail) ? $this->Html->image('/' . $hotel->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-7']) : "" ?>
            </td>
        </tr>
        <?php if ($hotel->album): ?>
            <tr>
                <th scope="row"><?= __('Album') ?></th>
                <td>
                    <?php
                    $albums = json_decode($hotel->album);
                    foreach ($albums as $image) {
                        if ($image)
                            echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                    }
                    ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <th scope="row"><?= __('Hotline') ?></th>
            <td><?= h($hotel->hotline) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Đánh giá trung bình') ?></th>
            <td><?= $this->Number->format($hotel->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mô tả') ?></th>
            <td>
                <?= $hotel->description ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Chính sách') ?></th>
            <td>
                <?= $hotel->term ?>
            </td>
        </tr>
    </table>
    <div class="related">
        <?php if (!empty($hotel->categories)): ?>
            <h4><?= __('Danh mục liên quan') ?></h4>
            <table class="table">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                </tr>
                <?php foreach ($hotel->categories as $key => $categories): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($categories->name) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <?php if (!empty($hotel->rooms)): ?>
            <h4><?= __('Các hạng mục phòng') ?></h4>
            <table class="table">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Hạng mục phòng') ?></th>
                    <th scope="col"><?= __('Diện tích') ?></th>
                    <th scope="col"><?= __('Số giường') ?></th>
                    <th scope="col"><?= __('Ảnh phòng') ?></th>
                </tr>
                <?php foreach ($hotel->rooms as $key => $rooms): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($rooms->name) ?></td>
                        <td><?= h($rooms->area) ?></td>
                        <td><?= h($rooms->num_bed) ?></td>
                        <td>
                            <?= ($rooms->thumbnail) ? $this->Html->image('/' . $rooms->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']) : "" ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
