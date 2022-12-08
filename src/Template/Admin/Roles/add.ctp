<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Vai trò</h2>            
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <?= $this->Form->create($role, ['class' => 'form-horizontal form-label-left', 'data-parsley-validate']) ?>            

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tên Vai trò <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12">
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