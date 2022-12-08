<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room $room
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Tên khách sạn') ?></th>
            <td><?= $room->has('hotel') ? $this->Html->link($room->hotel->name, ['controller' => 'Hotels', 'action' => 'view', $room->hotel->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hạng mục phòng') ?></th>
            <td><?= h($room->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh đại diện') ?></th>
            <td>
                <?= ($room->thumbnail) ? $this->Html->image('/' . $room->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-7']) : "" ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Diện tích (m2)') ?></th>
            <td><?= $this->Number->format($room->area) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Số giường') ?></th>
            <td><?= $this->Number->format($room->num_bed) ?></td>
        </tr>
        <?php if ($room->media): ?>
            <tr>
                <th scope="row"><?= __('Media') ?></th>
                <td><?php
                    $medias = json_decode($room->media);
                    foreach ($medias as $image) {
                        if ($image)
                            echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                    }
                    ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="related">
        <h4><?= __('Giá phòng') ?></h4>
        <table class="table table-responsive">
            <tr>
                <th scope="col"><?= __('Date Start') ?></th>
                <th scope="col"><?= __('Date End') ?></th>
                <th scope="col"><?= __('Price') ?></th>
            </tr>
            <?php foreach ($room->price_rooms as $key => $price_room): ?>
                <tr>
                    <td><?= h($price_room->start_date) ?></td>
                    <td><?= h($price_room->end_date) ?></td>
                    <td><?= $this->Number->currency($price_room->price, "VND") ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>


    <div class="related">
        <?php if (!empty($room->combos)): ?>
            <h4><?= __('Các gói combo tương ứng') ?></h4>
            <table class="table table-responsive">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                    <th scope="col"><?= __('Price') ?></th>
                    <th scope="col"><?= __('Trippal Price') ?></th>
                    <th scope="col"><?= __('Customer Price') ?></th>
                    <th scope="col"><?= __('Promote') ?></th>
                    <th scope="col"><?= __('Date Start') ?></th>
                    <th scope="col"><?= __('Date End') ?></th>
                </tr>
                <?php foreach ($room->combos as $key => $combos): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= $combos->name ?></td>
                        <td><?= $this->Number->currency($combos->price, "VND") ?></td>
                        <td><?= $this->Number->currency($combos->trippal_price, "VND") ?></td>
                        <td><?= $this->Number->currency($combos->customer_price, "VND") ?></td>
                        <td><?= $combos->promote . "%" ?></td>
                        <td><?= h($combos->date_start) ?></td>
                        <td><?= h($combos->date_end) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <?php if (!empty($room->categories)): ?>
            <h4><?= __('Danh mục tương ứng') ?></h4>
            <table class="table table-responsive table-striped">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                </tr>
                <?php foreach ($room->categories as $key => $categories): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($categories->name) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
