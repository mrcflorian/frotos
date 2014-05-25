<script type="text/javascript">
$(document).ready(function() {
	$('input.user-input').keyup( function(e) {
		autocomplete($(this).val(), 'friends', $('.user-input-autocomplete'), $('.user-input'));
	});
	$('input.support-group').keyup( function(e) {
		autocomplete($(this).val(), 'friends', $('.support-group-autocomplete'), $('.support-group'), true);
	});
	$('.btn-add-supporter').click( function() {
		$('.panel-support-group').toggle();
		$('.support-group').focus();
	});
	$('.create-intervention').click( function() {
		var data = {};
		data['title'] = $('input.title-input').val();
		data['description'] = '';
		data['user_id'] = $('input.user-input').attr('user_id');
		data['author_id'] = '<?=$_SESSION['user_id']?>';
		data['users'] = [];
		$('.support-group-members-container div').each( function(index) {
			var user = {};
			user['user_id'] = $(this).attr('user_id');
			data['users'].push(user);
		});
		var path = interventionURL + 'api/intervention.php';
		$.ajax({
		  type: "POST",
		  url: path,
		  data: {
		  	'action': 'create',
			'json_data': JSON.stringify(data)
		  },
		  success: function(res) {
			if (res[0]['error'] == true) {
				$('#createInterventionModal .alert-error p').text(res[0]['message']);
				$('#createInterventionModal .alert-error').fadeIn();
			} else {
				$('#createInterventionModal .alert-success p').text(res[0]['message']);
				$('#createInterventionModal .alert-success').fadeIn();
			}
		  },
		  dataType: 'json',
		  error: function (xhr, ajaxOptions, thrownError) {
        	console.log('ERROR');
			console.log(xhr.status);
        	console.log(thrownError);
		  }
		});
	});
});
</script>
<div id="createInterventionModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Create an intervention</h4>
      </div>
	  
	  <div class="modal-body">
	  <div class="alert alert-success">  
		<p></p>
	  </div>
	  <div class="alert alert-error">  
		<p></p>
	  </div>
			<form role="create">
			  <div class="form-group">
				<input type="text" class="form-control title-input" placeholder="Stop drinking" />
				<div class="autocomplete-container">
					<input type="text" class="form-control user-input" name="friends" placeholder="Your friend's name"  />
					<ul class="autocomplete user-input-autocomplete">
					</ul>
				</div>
			  </div>
			  <div class="panel panel-support-group">
				  <div class="panel-body panel-support-group-body">
				  	<div class="support-group-members-container"></div>
					<div class="autocomplete-container">
						<input type="text" class="form-control support-group" placeholder="Who else supports this?" />
						<ul class="autocomplete support-group-autocomplete">
						</ul>
					</div>
				  </div>
				</div>
			</form>
	</div> <!-- body -->
	
	<div class="modal-footer">
		<button type="button" class="btn-add-supporter pull-left"><img src="images/add-person.png" /></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary create-intervention">Create intervention</button>
    </div>
	
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->