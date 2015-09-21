<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Status', 'status', array('class'=>'control-label')); ?>
<?php Profiler::console($part->status); ?>
				<?php echo Form::select('status', $part->status, [
						\Config::get('my.status.pay') => 'Pay',
						\Config::get('my.status.paid') => 'Paid',
						\Config::get('my.status.received') => 'Received',
						\Config::get('my.status.ship') => 'Ship',
						\Config::get('my.status.sell') => 'Sell',
					], ['class' => 'col-md-4 form-control']); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Ship price', 'price', array('class'=>'control-label')); ?>

				<?php echo Form::input('price', Input::post('price', isset($part) ? $part->price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Price')); ?>

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