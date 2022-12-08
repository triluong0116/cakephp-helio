<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LandTour[]|\Cake\Collection\CollectionInterface $landTours
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <?php if ($this->request->is('get') && $this->request->getQuery('search')): ?>
                <h2>Có <?= $number ?> kết quả được tìm thấy với từ khóa: <?= $data ?></h2>
            <?php else: ?>
                <h2><?= __('Land Tour') ?></h2>
            <?php endif; ?>
            <?= $this->element('Backend/searchv3') ?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
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
                                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($landTours as $key => $landTour): ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td><?= h($landTour->name) ?></td>
                                    <td><?= $this->Number->currency($landTour->price, 'VND') ?></td>
                                    <td><?= $this->Number->currency($landTour->trippal_price, 'VND') ?></td>
                                    <td><?= $this->Number->currency($landTour->customer_price, 'VND') ?></td>
                                    <td><?= $this->Number->format($landTour->promote) ?></td>
                                    <td><?= h($landTour->departure->name) ?></td>
                                    <td><?= h($landTour->destination->name) ?></td>
                                    <td><?= $this->Number->format($landTour->days) ?></td>
                                    <td><?= $this->Number->format($landTour->rating) ?></td>
                                    <td><?= h($landTour->start_date) ?></td>
                                    <td><?= h($landTour->end_date) ?></td>
                                    <td><?= $this->Number->format($landTour->status) ?></td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'view', $landTour->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['action' => 'edit', $landTour->id]) ?>">Sửa</a>

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
