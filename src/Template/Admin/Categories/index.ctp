<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location[]|\Cake\Collection\CollectionInterface $locations
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách danh mục</h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên danh mục</th>
                                <th>Danh mục cha</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($categories as $key => $category): ?>
                                <tr>
                                    <th scope="row"><?= $key + 1 ?></th>
                                    <td><?= $category->name ?></td>
                                    <td><?= $category->has('parent_category') ? $category->parent_category->name : '' ?></td>
                                    <td>
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Categories', 'action' => 'view', $category->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Categories', 'action' => 'edit', $category->id]) ?>">Sửa</a>
                                        <?php
                                        echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $category->id], ['confirm' => __('Bạn có chắc muốn xóa Danh mục: {0}?', $category->name), 'class' => 'btn btn-xs btn-danger']);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
