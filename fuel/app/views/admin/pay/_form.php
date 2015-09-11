<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Status', 'status', array('class'=>'control-label')); ?>

				<?php echo Form::input('status', Input::post('status', isset($part) ? $part->status : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Status')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Price', 'price', array('class'=>'control-label')); ?>

				<?php echo Form::input('price', Input::post('price', isset($part) ? $part->price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Ship number', 'ship_number', array('class'=>'control-label')); ?>

				<?php echo Form::input('ship_number', Input::post('ship_number', isset($part) ? $part->ship_number : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Ship number')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Box number', 'box_number', array('class'=>'control-label')); ?>

				<?php echo Form::input('box_number', Input::post('box_number', isset($part) ? $part->box_number : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Box number')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Tracking', 'tracking', array('class'=>'control-label')); ?>

				<?php echo Form::input('tracking', Input::post('tracking', isset($part) ? $part->tracking : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Tracking')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Memo', 'memo', array('class'=>'control-label')); ?>

				<?php echo Form::input('memo', Input::post('memo', isset($part) ? $part->memo : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Memo')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>