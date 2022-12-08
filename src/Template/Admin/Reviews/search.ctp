<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review[]|\Cake\Collection\CollectionInterface $reviews
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Có <?= $number ?> kết quả được tìm thấy với từ khóa: <?= $data ?></h2>
            <?= $this->element('Backend/search') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th scope="col"><?= $this->Paginator->sort('category_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('caption') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('rating') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reviews as $key => $review): ?>
                    <tr>
                        <td><?= ++$key ?></td>
                        <td><?= $review->has('category') ? $this->Html->link($review->category->name, ['controller' => 'Categories', 'action' => 'view', $review->category->id]) : '' ?></td>
                        <td><?= h($review->title) ?></td>
                        <td><?= h($review->caption) ?></td>
                        <td><?= $this->Number->format($review->rating) ?></td>
                        <td class="actions">
                            <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'view', $review->id]) ?>">Xem</a>
                            <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'edit', $review->id]) ?>">Sửa</a>
                            <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $review->id], ['confirm' => __('Bạn có chắc muốn xóa Review: {0}?', $review->title), 'class' => 'btn btn-xs btn-danger']);?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <?= $this->element('Backend/admin_paging') ?>
            </div>
        </div>
    </div>
</div>
