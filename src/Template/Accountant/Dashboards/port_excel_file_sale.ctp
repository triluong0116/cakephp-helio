<?php echo $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min', ['block' => 'scriptBottom']);
?>

<?php
$this->Form->setTemplates([
    'formStart' => '<form class="" {{attrs}}>',
    'label' => '<label class="control-label col-md-3 col-sm-3 col-xs-12" {{attrs}}>{{text}}</label>',
    'input' => '<div class="col-md-9 col-sm-9 col-xs-12"><input type="{{type}}" name="{{name}}" {{attrs}} /></div>',
    'select' => '<div class="col-md-9 col-sm-9 col-xs-12"><select name="{{name}}"{{attrs}}>{{content}}</select></div>',
    'textarea' => '<div class="col-md-9 col-sm-9 col-xs-12"><textarea name="{{name}}"{{attrs}}>{{content}}</textarea></div>',
    'inputContainer' => '<div class="item form-group">{{content}}</div>',
    'inputContainerError' => '<div class="item form-group">{{content}}{{error}}</div>',
    'checkContainer' => ''
]);
?>
<!-- MAIN -->
<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Xuất file Excel</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br/>
            <form id="choose_date">
                <div class="control-group">
                    <div class="row">
                        <div class="controls">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Chọn khoảng thời gian *</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-prepend input-group">
                                <span class="add-on input-group-addon"><i
                                        class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input type="text" name="reservation" class="custom-daterange-picker form-control"
                                           value=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a onclick="exportAccountantFileSale(this)" class="btn btn-success" id="blog-submit">
                        <i class="fa fa-cog fa-spin fa-fw hidden" id="cog-3"></i> Xuất file Excel
                    </a>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 mt10" id="download-link">

                </div>
            </div>

        </div>
    </div>
</div>