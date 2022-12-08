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
            echo $this->Form->control('content', [
                'templates' => [
                    'inputContainer' => '<div class="item form-group">{{content}}</div>',
                    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
                    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
                    'input' => '<div class="col-md-6 col-sm-6 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
                ],
                'type' => 'text',
                'class' => 'form-control',
                'label' => 'Nội dung câu hỏi *',
                'required' => 'required'
            ]);
            ?>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Đáp án 1 *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <!--<input type="text" name="answer[0][content]" class="form-control" required="required" />-->
                    <?php
                    echo $this->Form->control('answer.0.content', [
                        'templates' => false,
                        'class' => 'form-control',
                        'label' => 'Đáp án 1 *',
                        'label' => false,
                        'required' => 'required'
                    ])
                    ?>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="radio" name="answer[0][is_correct]" class="flat same-radio" > Đáp án chính xác
                        </label>
                    </div>
                </div>
            </div>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Đáp án 2 *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php
                    echo $this->Form->control('answer.1.content]', [
                        'templates' => false,
                        'class' => 'form-control',
                        'label' => 'Đáp án 2 *',
                        'label' => false,
                        'required' => 'required'
                    ])
                    ?>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="radio" name="answer[1][is_correct]" class="flat same-radio" > Đáp án chính xác
                        </label>
                    </div>
                </div>
            </div>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Đáp án 3 *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php
                    echo $this->Form->control('answer.2.content]', [
                        'templates' => false,
                        'class' => 'form-control',
                        'label' => 'Đáp án 3 *',
                        'label' => false,
                        'required' => 'required'
                    ])
                    ?>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="radio" name="answer[2][is_correct]"  class="flat same-radio" > Đáp án chính xác
                        </label>
                    </div>
                </div>
            </div>
            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Đáp án 4 *</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php
                    echo $this->Form->control('answer.3.content]', [
                        'templates' => false,
                        'class' => 'form-control',
                        'label' => 'Đáp án 4 *',
                        'label' => false,
                        'required' => 'required'
                    ])
                    ?>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="radio">
                        <label>
                            <input type="radio" name="answer[3][is_correct]" class="flat same-radio" > Đáp án chính xác
                        </label>
                    </div>
                </div>
            </div>            

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