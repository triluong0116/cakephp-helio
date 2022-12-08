<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LandTour $landTour
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <h3><?= h($landTour->name) ?></h3>
    <table class=" table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $landTour->has('user') ? $this->Html->link($landTour->user->username, ['controller' => 'Users', 'action' => 'view', $landTour->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->currency($landTour->price, 'VND') ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Trippal Price') ?></th>
            <td><?= $this->Number->currency($landTour->trippal_price, 'VND') ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer Price') ?></th>
            <td><?= $this->Number->currency($landTour->customer_price, 'VND') ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Promote') ?></th>
            <td><?= $this->Number->format($landTour->promote) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Departure') ?></th>
            <td><?= $landTour->has('departure') ? $landTour->departure->name : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Destination') ?></th>
            <td><?= $landTour->has('destination') ? $landTour->destination->name : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Days') ?></th>
            <td><?= $this->Number->format($landTour->days) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rating') ?></th>
            <td><?= $this->Number->format($landTour->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $this->Number->format($landTour->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Date') ?></th>
            <td><?= h($landTour->start_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('End Date') ?></th>
            <td><?= h($landTour->end_date) ?></td>
        </tr>        
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= $landTour->description ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Media') ?></th>
            <td><?php
                if ($landTour->media) {
                    $medias = json_decode($landTour->media);
                    foreach ($medias as $image) {
                        if ($image)
                            echo $this->Html->image('/' . $image, ['alt' => 'thumbnail', 'class' => 'col-xs-4 img-responsive']);
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Term') ?></th>
            <td><?= $landTour->term ?></td>
        </tr>
    </table>
</div>
