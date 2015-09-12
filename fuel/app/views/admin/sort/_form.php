<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Item count', 'item_count', array('class'=>'control-label')); ?>

				<?php echo Form::input('item_count', Input::post('item_count', isset($sort) ? $sort->item_count : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Item count')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Auc id', 'auc_id', array('class'=>'control-label')); ?>

				<?php echo Form::input('auc_id', Input::post('auc_id', isset($sort) ? $sort->auc_id : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Auc id')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Description', 'description', array('class'=>'control-label')); ?>

				<?php echo Form::input('description', Input::post('description', isset($sort) ? $sort->description : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Description')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Price', 'price', array('class'=>'control-label')); ?>

				<?php echo Form::input('price', Input::post('price', isset($sort) ? $sort->price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Won date', 'won_date', array('class'=>'control-label')); ?>

				<?php echo Form::input('won_date', Input::post('won_date', isset($sort) ? $sort->won_date : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Won date')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Vendor', 'vendor', array('class'=>'control-label')); ?>

				<?php echo Form::input('vendor', Input::post('vendor', isset($sort) ? $sort->vendor : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Vendor')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Won user', 'won_user', array('class'=>'control-label')); ?>

				<?php echo Form::input('won_user', Input::post('won_user', isset($sort) ? $sort->won_user : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Won user')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Part id', 'part_id', array('class'=>'control-label')); ?>

				<?php echo Form::input('part_id', Input::post('part_id', isset($sort) ? $sort->part_id : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Part id')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Memo', 'memo', array('class'=>'control-label')); ?>

				<?php echo Form::input('memo', Input::post('memo', isset($sort) ? $sort->memo : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Memo')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>