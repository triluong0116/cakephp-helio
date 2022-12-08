<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Question $question
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Thêm mới Địa điểm</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />            
            <?= $this->Form->create($question, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate', 'type' => 'file', 'id' => 'create-question']) ?>
            <?php
            $this->Form->setTemplates([
                'formStart' => '<form class="" {{attrs}}>',
                'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                'select' => '<div class="col-md-6 col-sm-6 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
                'textarea' => '<div class="col-md-6 col-sm-6 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}</textarea></div>',
                'inputContainer' => '<div class="item form-group">{{content}}</div>',
                'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                'checkContainer' => ''
            ]);
            echo $this->Form->control('content', [
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Nội dung câu hỏi *',
                'required' => 'required'
            ]);
            $answers = json_decode($question->answer, true);
            ?>
            <?php foreach ($answers as $key => $answer): ?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Đáp án <?= $key + 1 ?> *</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="answer[<?= $key ?>][content]" class="form-control" required="required" value="<?= $answer['content'] ?>" />
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="radio">
                            <label>
                                <input type="radio" name="answer[<?= $key ?>][is_correct]" class="flat same-radio" <?= (isset($answer['is_correct']) && !empty($answer['is_correct'])) ? 'checked' : '' ?>> Đáp án chính xác
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>        

            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
