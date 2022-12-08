<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Question $question
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('Tiêu đề') ?></th>
            <td><?= $agency->title ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nội dung huấn luyện') ?></th>
            <td><?= $agency->description ?></td>
        </tr>        
    </table>
</div>
