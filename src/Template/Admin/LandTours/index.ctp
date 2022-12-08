<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LandTour[]|\Cake\Collection\CollectionInterface $landTours
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
        </div>
        <?php if ($this->request->is('get') && $this->request->getQuery('search')): ?>
            <h2>Có <?= $number ?> kết quả được tìm thấy với từ khóa: <?= $data ?></h2>
        <?php else: ?>
            <h2><?= __('Land Tour') ?></h2>
        <?php endif; ?>
        <?= $this->element('Backend/search') ?>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="groupAction hidden">
                <button class="btn btn-success" onclick="setFeatured('<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'setFeatured', 'prefix' => 'sale'], true) ?>');">
                    <i class="fa fa-map-marker"></i> Chọn Land Tour Phổ biến
                </button>
                <button class="btn btn-warning" onclick="unsetFeatured('<?= $this->Url->build(['controller' => 'LandTours', 'action' => 'unsetFeatured', 'prefix' => 'sale'], true) ?>');">
                    <i class="fa fa-minus-circle"></i> Bỏ Land Tour Phổ biến
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"><input type="checkbox" class="flat" id="checkAll"></th>
                            <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('price') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('trippal_price') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('customer_price') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('promote') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('departure_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('destination_id') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('days') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('rating') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('start_date') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('end_date') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('Phổ Biến') ?></th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($landTours as $key => $landTour): ?>
                            <tr>
                                <td><?= ++$key ?></td>
                                <td><input type="checkbox" class="check flat" data-id="<?= $landTour->id ?>"></td>
                                <td><?= $landTour->has('user') ? $this->Html->link($landTour->user->username, ['controller' => 'Users', 'action' => 'view', $landTour->user->id]) : '' ?></td>
                                <td><?= h($landTour->name) ?></td>
                                <td><?= $this->Number->currency($landTour->price, 'VND') ?></td>
                                <td><?= $this->Number->currency($landTour->trippal_price, 'VND') ?></td>
                                <td><?= $this->Number->currency($landTour->customer_price, 'VND') ?></td>
                                <td><?= $this->Number->format($landTour->promote) ?></td>
                                <td><?= $landTour->has('departure') ? $this->Html->link($landTour->departure->name, ['controller' => 'Locations', 'action' => 'view', $landTour->departure->id]) : '' ?></td>
                                <td><?= $landTour->has('destination') ? $this->Html->link($landTour->destination->name, ['controller' => 'Locations', 'action' => 'view', $landTour->destination->id]) : '' ?></td>
                                <td><?= $this->Number->format($landTour->days) ?></td>
                                <td><?= $this->Number->format($landTour->rating) ?></td>
                                <td><?= h($landTour->start_date) ?></td>
                                <td><?= h($landTour->end_date) ?></td>
                                <td>
                                    <?php if ($landTour->is_feature): ?>
                                        <i class="fa fa-check-circle text-success"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'view', $landTour->id]) ?>">Xem</a>
                                    <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['action' => 'edit', $landTour->id]) ?>">Sửa</a>
                                    <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $landTour->id], ['confirm' => __('Bạn có chắc muốn xóa Land Tour: {0}?', $landTour->name), 'class' => 'btn btn-xs btn-danger']); ?>
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
