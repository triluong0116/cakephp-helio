<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Question $question
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <table class="table table-responsive table-striped">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $question->has('user') ? $this->Html->link($question->user->username, ['controller' => 'Users', 'action' => 'view', $question->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nội dung câu hỏi') ?></th>
            <td><?= h($question->content) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Được hỏi vào ngày') ?></th>
            <td><?= h($question->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Trả lời') ?></h4>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <?php
            $answers = json_decode($question->answer);
            foreach ($answers as $answer): ?>
                <?php (property_exists($answer, 'is_correct') && $answer->is_correct == 'on')  ? $is_correct = true : $is_correct = false ?>
                <div class="col-xs-9">
                    <input type="text" readonly value="<?= $answer->content ?>" class="form-control" style="<?= ($is_correct) ?  "background-color:#42d7f4" : '' ?>">
                    <br/>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
