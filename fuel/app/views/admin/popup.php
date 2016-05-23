<div id="item-edit-popup" class="overlay">
	<div class="popup">
		<h2></h2>
		<span class="close">&times;</span>
		<div class="item-inputs">
			<label class="item-label">Items count</label>
			<input class="item-input" id="count" type="text">
			<label class="item-label">Price</label>
			<input class="item-input" id="price" type="text">
			<input id="item-id" type="hidden">
		</div>
		<label class="item-label">comment</label>
		<textarea id="comment"></textarea>
		<div class="send-button">
			<button class="ladda-button" data-style="zoom-in" data-size="xs" data-color="blue">
				<span class="ladda-label">Update</span>
			</button>
		</div>
	</div>
</div>

<div id="item-delete-popup" class="overlay">
	<div class="popup">
		<h2></h2>
		<span class="close">&times;</span>
		<input id="item-id" type="hidden">
		<div class="send-button">
			<button class="ladda-button" data-style="zoom-in" data-size="xs" data-color="blue">
				<span class="ladda-label">Delete</span>
			</button>
		</div>
	</div>
</div>

<div id="part-edit-popup" class="overlay">
	<div class="popup">
		<h2></h2>
		<span class="close">&times;</span>
		<div class="item-inputs">
			<label>Ship</label>
			<input id="part-ship" type="text">
			<label>Tracking</label>
			<input id="part-tracking" type="text">
			<label>Box</label>
			<input id="part-box" type="text">
			<input id="part-id" type="hidden">
		</div>
		<div class="part-status-radio">
			<?php foreach (Config::get('my.status') as $status) : ?>
				<input id="radio_<?= $status['id']; ?>" type="radio" name="status" value="<?= $status['id']; ?>">
				<label for="radio_<?= $status['id']; ?>"><?= $status['name']; ?></label>
			<?php endforeach; ?>
		</div>
		<label>comment</label>
		<textarea id="comment"></textarea>
		<div class="send-button">
			<button class="ladda-button" data-style="zoom-in" data-size="xs" data-color="blue">
				<span class="ladda-label">Update</span>
			</button>
		</div>
	</div>
</div>

<div id="part-delete-popup" class="overlay">
	<div class="popup">
		<h2>Are you sure want to delete ID: <span></span></h2>
		<span class="close">&times;</span>
		<input id="part-id" type="hidden">
		<div class="send-button">
			<button class="ladda-button" data-style="zoom-in" data-size="xs" data-color="blue">
				<span class="ladda-label">Delete</span>
			</button>
		</div>
	</div>
</div>

<div id="comment-popup" class="overlay">
	<div class="popup">
		<h2></h2>
		<span class="close">&times;</span>
	</div>
</div>
