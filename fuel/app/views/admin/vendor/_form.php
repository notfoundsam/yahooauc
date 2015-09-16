<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Name', 'name', array('class'=>'control-label')); ?>

				<?php echo Form::input('name', Input::post('name', isset($vendor) ? $vendor->name : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Name')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('By now', 'by_now', array('class'=>'control-label')); ?>

				<?php echo Form::input('by_now', Input::post('by_now', isset($vendor) ? $vendor->by_now : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'By now')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Post index', 'post_index', array('class'=>'control-label')); ?>

				<?php echo Form::input('post_index', Input::post('post_index', isset($vendor) ? $vendor->post_index : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Post index')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Address', 'address', array('class'=>'control-label')); ?>

				<?php echo Form::input('address', Input::post('address', isset($vendor) ? $vendor->address : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Address')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Color', 'color', array('class'=>'control-label')); ?>

				<?php echo Form::input('color', Input::post('color', isset($vendor) ? $vendor->color : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Color')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Memo', 'memo', array('class'=>'control-label')); ?>

				<?php echo Form::input('memo', Input::post('memo', isset($vendor) ? $vendor->memo : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Memo')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>