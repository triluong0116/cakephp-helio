<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive">
        <tr>
            <?php if ($category->has('parent_category')): ?>
                <th scope="row"><?= __('Danh mục cha') ?></th>
                <td><?= $this->Html->link($category->parent_category->name, ['controller' => 'Categories', 'action' => 'view', $category->parent_category->id]) ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><?= __('Tên danh mục') ?></th>
            <td><?= h($category->name) ?></td>
        </tr>
    </table>
    <div class="related">
        <?php if (!empty($category->rooms)): ?>
            <h4><?= __('Phòng liên quan') ?></h4>
            <table class="table">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Hạng mục phòng') ?></th>
                    <th scope="col"><?= __('Diện tích') ?></th>
                    <th scope="col"><?= __('Số giường') ?></th>
                    <th scope="col"><?= __('Ảnh phòng') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($category->rooms as $key => $rooms): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($rooms->name) ?></td>
                        <td><?= h($rooms->area) ?></td>
                        <td><?= h($rooms->num_bed) ?></td>
                        <td>
                            <?= ($rooms->thumbnail) ? $this->Html->image('/' . $rooms->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive']) : "" ?>
                        </td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'view', $rooms->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Rooms', 'action' => 'edit', $rooms->id]) ?>">Sửa</a>
                            <?= $this->Form->postLink(__('Xóa'), ['controller' => 'Rooms', 'action' => 'delete', $rooms->id], ['confirm' => __('Bạn có chắc muốn xóa Phòng: {0}?', $rooms->name), 'class' => 'btn btn-xs btn-danger']);?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <?php if (!empty($category->child_categories)): ?>
            <h4><?= __('Danh sách danh mục con') ?></h4>
            <table class="table">
                <tr>
                    <th scope="col"><?= __('#') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                <?php foreach ($category->child_categories as $key => $childCategories): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= h($childCategories->name) ?></td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Categories', 'action' => 'view', $childCategories->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Categories', 'action' => 'edit', $childCategories->id]) ?>">Sửa</a>
                            <?= $this->Form->postLink(__('Xóa'), ['controller' => 'Categories', 'action' => 'delete', $childCategories->id], ['confirm' => __('Bạn có chắc muốn xóa Danh mục con # {0}?', $childCategories->id), 'class' => 'btn btn-xs btn-danger']);?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
