<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Status</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="box-tools">
            {!! Form::checkbox('status',1,null,['data-onstyle'=>'success','data-offstyle'=>'danger','data-on'=>'Visiable','data-off'=>'Invisiable','data-toggle'=>'toggle']) !!}
        </div>
    </div>
</div>