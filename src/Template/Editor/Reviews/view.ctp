<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review $review
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= $review->has('category') ? $this->Html->link($review->category->name, ['controller' => 'Categories', 'action' => 'view', $review->category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location') ?></th>
            <td><?= $review->has('location') ? $this->Html->link($review->location->name, ['controller' => 'Locations', 'action' => 'view', $review->location->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($review->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Caption') ?></th>
            <td><?= h($review->caption) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thumbnail') ?></th>
            <td>
                <?= ($review->thumbnail) ? $this->Html->image('/' . $review->thumbnail, ['alt' => 'thumbnail', 'class' => 'img-responsive col-xs-7']) : "" ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rating') ?></th>
            <td><?= $this->Number->format($review->rating) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price Start') ?></th>
            <td><?= $this->Number->currency($review->price_start, "VND") ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price End') ?></th>
            <td><?= $this->Number->currency($review->price_end, "VND") ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Nội dung đánh giá') ?></h4>
        <?= $review->content ?>
    </div>
</div>
