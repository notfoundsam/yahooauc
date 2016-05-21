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
