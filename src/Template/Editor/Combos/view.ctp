<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Combo $combo
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <h3><?= h($combo->name) ?></h3>
    <table class=" table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Tên gói') ?></th>
            <td><?= h($combo->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh đại diện') ?></th>
            <td>
                <?= ($combo->thumbnail) ? $this->Html->image('/' . $combo->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-3']) : "" ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Danh sách ảnh') ?></th>
            <td><?php
                if ($combo->media) {
                    $medias = json_decode($combo->media);
                    foreach ($medias as $image) {
                        if ($image)
                            echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"> <?= __('Khuyến mãi') ?></th>
            <td><?= $combo->promote ?> %</td>
        </tr>
        <tr>
            <th scope="row"><?= __('Điểm đi') ?></th>
            <td><?= $combo->has('departure') ? $this->Html->link($combo->departure->name, ['controller' => 'Locations', 'action' => 'view', $combo->departure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Điểm đến') ?></th>
            <td><?= $combo->has('destination') ? $this->Html->link($combo->destination->name, ['controller' => 'Locations', 'action' => 'view', $combo->destination->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thời gian') ?></th>
            <td><?= $combo->days ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Đánh giá') ?></th>
            <td><?= $this->Number->format($combo->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thời điểm tạo') ?></th>
            <td><?= h($combo->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thời điểm chỉnh sửa') ?></th>
            <td><?= h($combo->modified) ?></td>
        </tr>
    </table>
</div>
