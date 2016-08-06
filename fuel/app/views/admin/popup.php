<div id="item-edit-popup" class="overlay">
	<div class="popup">
		<h2></h2>
		<span class="close">&times;</span>
		<div class="item-inputs">
			<label>Items count</label>
			<input id="count" type="text">
			<label>Price</label>
			<input id="price" type="text">
			<input id="item-id" type="hidden">
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
			<input class="part-ship" id="part-ship" type="text">
			<label>Tracking</label>
			<input id="part-tracking" type="text">
			<label>Box</label>
			<input class="part-box" id="part-box" type="text">
			<input id="part-id" type="hidden">
		</div>
		<div class="part-status-radio">
			<?php foreach (Config::get('my.status') as $status) : ?>
				<div class="radio-group">
					<input id="radio_<?= $status['id']; ?>" type="radio" name="status" value="<?= $status['id']; ?>">
					<label for="radio_<?= $status['id']; ?>"><?= $status['name']; ?></label>
				</div>
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

<div id="vendor_popup" class="overlay">
	<div class="popup">
		<h2>Add vendor</h2>
		<span class="close">&times;</span>
		<input id="vendor_id" type="hidden">
		<div class="item-inputs">
			<label>Vendor ID</label>
			<input id="vendor_name" type="text">
			<label>Post index</label>
			<input id="post_index" type="text">
		</div>
		<div class="item-inputs">
			<label>Address</label>
			<input class="address" id="address" type="text">
		</div>
		<div class="part-status-radio">
			<div class="radio-group">
				<label>By now: </label>
				<input id="radio_1" type="radio" name="by_now" value="1">
				<label for="radio_1">Yes</label>
				<input id="radio_0" type="radio" name="by_now" value="0" checked>
				<label for="radio_0">No</label>
			</div>
		</div>
		<label>comment</label>
		<textarea id="comment"></textarea>
		<div class="send-button">
			<button class="ladda-button" data-style="zoom-in" data-size="xs" data-color="blue">
				<span class="ladda-label">Add</span>
			</button>
		</div>
	</div>
</div>
