<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Question[]|\Cake\Collection\CollectionInterface $questions
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Danh sách câu hỏi cho Đại lý</h2>
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
                                <th scope="col">#</th>
                                <th scope="col">Nội dung câu hỏi</th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($questions as $key => $question): ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <td><?= h($question->content) ?></td>
                                    <td class="actions">
                                        <a type="button" class="btn btn-xs btn-primary" href="<?= $this->Url->build(['controller' => 'Questions', 'action' => 'view', $question->id]) ?>">Xem</a>
                                        <a type="button" class="btn btn-xs btn-warning" href="<?= $this->Url->build(['controller' => 'Questions', 'action' => 'edit', $question->id]) ?>">Sửa</a>
                                        <?php echo $this->Form->postLink(__('Xóa'), ['action' => 'delete', $question->id], ['confirm' => __('Bạn có chắc muốn xóa Review: {0}?', $question->title), 'class' => 'btn btn-xs btn-danger']); ?>
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
