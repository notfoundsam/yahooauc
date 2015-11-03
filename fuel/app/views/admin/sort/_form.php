<?php echo Form::open(array("class"=>"form-horizontal")); ?>
<?php echo Form::csrf(); ?>
	<fieldset>

		<div class="form-group">
			<?php echo Form::label('User ID', 'user_id', array('class'=>'control-label')); ?>
				<?php
				$select_box	= \Presenter::forge('admin/sort/selector');
				$select_box->set(
					'user_id', Input::post('user_id', isset($auction) ? $auction->user_id : null)
					);
					echo $select_box;
				?>

		</div>

		<div class="form-group">
			<?php echo Form::label('Item count', 'item_count', array('class'=>'control-label')); ?>

				<?php echo Form::input('item_count', Input::post('item_count', isset($auction) ? $auction->item_count : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Item count')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Price', 'price', array('class'=>'control-label')); ?>

				<?php echo Form::input('price', Input::post('price', isset($auction) ? $auction->price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Memo', 'memo', array('class'=>'control-label')); ?>

				<?php echo Form::input('memo', Input::post('memo', isset($auction) ? $auction->memo : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Memo')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>
