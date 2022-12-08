<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher $voucher
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <h3><?= h($voucher->title) ?></h3>
    <table class=" table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($voucher->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thumbnail') ?></th>
            <td><?php
                $this->Html->image('/' . $voucher->thumbnail, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($voucher->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->format($voucher->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Trippal Price') ?></th>
            <td><?= $this->Number->format($voucher->trippal_price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer Price') ?></th>
            <td><?= $this->Number->format($voucher->customer_price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Media') ?></th>
            <td><?php
                $medias = json_decode($voucher->media);
                foreach ($medias as $image) {
                    if ($image)
                        echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                }
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Departure') ?></th>
            <td><?= $voucher->has('departure') ? $this->Html->link($voucher->departure->name, ['controller' => 'Locations', 'action' => 'view', $voucher->departure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Destination') ?></th>
            <td><?= $voucher->has('destination') ? $this->Html->link($voucher->destination->name, ['controller' => 'Locations', 'action' => 'view', $voucher->destination->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Days') ?></th>
            <td><?= $this->Number->format($voucher->days) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rating') ?></th>
            <td><?= $this->Number->format($voucher->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($voucher->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Date') ?></th>
            <td><?= h($voucher->start_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('End Date') ?></th>
            <td><?= h($voucher->end_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($voucher->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($voucher->description)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Rooms') ?></h4>
        <?php if (!empty($voucher->rooms)): ?>
            <table class=" table table-responsive table-striped">
                <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Name') ?></th>
                    <th scope="col"><?= __('Area') ?></th>
                    <th scope="col"><?= __('Num Bed') ?></th>
                    <th scope="col"><?= __('Thumbnail') ?></th>
                    <th scope="col"><?= __('Media') ?></th>
                    <th scope="col"><?= __('Start Date') ?></th>
                    <th scope="col"><?= __('End Date') ?></th>
                    <th scope="col"><?= __('Price') ?></th>
                    <th scope="col"><?= __('Created') ?></th>
                </tr>
                <?php foreach ($voucher->rooms as $rooms): ?>
                    <tr>
                        <td><?= h($rooms->id) ?></td>
                        <td><?= h($rooms->name) ?></td>
                        <td><?= h($rooms->area) ?></td>
                        <td><?= h($rooms->num_bed) ?></td>
                        <td><?php
                            $this->Html->image('/' . $voucher->thumbnail, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                            ?>
                        </td>
                        <td><?php
                            $medias = json_decode($voucher->media);
                            foreach ($medias as $image) {
                                if ($image)
                                    echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-3 img-responsive']);
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
