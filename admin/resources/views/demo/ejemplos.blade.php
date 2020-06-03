
<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<!-- Modal HTML embedded directly into document -->
<div id="ex1" class="modal">
  <p>Thanks for clicking. That felt good.</p>
  <a href="#" rel="modal:close">Close</a>
</div>

<!-- Link to open the modal -->
<p><a href="#ex1" rel="modal:open">Open Modal</a></p>

<script>

// Open modal in AJAX callback
$('#manual-ajax').click(function(event) {
	event.preventDefault();
	this.blur(); // Manually remove focus from clicked link.
	$.get(this.href, function(html) {
		$(html).appendTo('body').modal();
	});
});

$("#sticky").modal({
	escapeClose: false,
	clickClose: false,
	showClose: false
});

</script>
<!-- AJAX response must be wrapped in the modal's root class. -->
<div class="modal">
  <p>Second AJAX Example!</p>
</div>

