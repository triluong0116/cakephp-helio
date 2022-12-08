<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location $location
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table">
        <tr>
            <th scope="row"><?= __('Tên địa điểm') ?></th>
            <td><?= h($location->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mô tả') ?></th>
            <td>
                <?= h($location->description) ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ảnh đại diện') ?></th>
            <td>
                <?= ($location->thumbnail) ? $this->Html->image('/' . $location->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']) : "" ?>
            </td>
        </tr>
    </table>
    <div class="related">
<?php if (!empty($location->hotels)): ?>
            <h4><?= __('Khách sạn liên quan') ?></h4>
            <table class="table">
                <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                    <th scope="col"><?= __('Description') ?></th>
                    <th scope="col"><?= __('Map') ?></th>
                    <th scope="col"><?= __('Hotline') ?></th>
                    <th scope="col"><?= __('Term') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
    <?php foreach ($location->hotels as $hotels): ?>
                    <tr>
                        <td><?= h($hotels->id) ?></td>
                        <td><?= h($hotels->name) ?></td>
                        <td><?= $hotels->description ?></td>
                        <td><?= h($hotels->map) ?></td>
                        <td><?= h($hotels->hotline) ?></td>
                        <td><?= $hotels->term ?></td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary"
                               href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'view', $hotels->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning"
                               href="<?= $this->Url->build(['controller' => 'Hotels', 'action' => 'edit', $hotels->id]) ?>">Sửa</a>
        <?= $this->Form->postLink(__('Xóa'), ['controller' => 'Hotels', 'action' => 'delete', $hotels->id], ['confirm' => __('Bạn có chắc muốn xóa Khách sạn # {0}?', $hotels->id), 'class' => 'btn btn-xs btn-danger']); ?>
                        </td>
                    </tr>
            <?php endforeach; ?>
            </table>
<?php endif; ?>
    </div>
</div>
