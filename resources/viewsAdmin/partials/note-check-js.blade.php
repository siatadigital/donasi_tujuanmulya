<script>
	function confirm_check(id)
	{
			console.log(id);
			$.ajax({
				type: "GET",
				url: '/backend/zakat/confirm_check/'+id,
				success: function() {
					location.reload();
				},
				error:function(){
					alert('failure');
				}
			});     
	}
	function cancel_check(id)
	{
			console.log(id);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$.ajax({
				type: "GET",
				url: '/backend/zakat/cancel_check/'+id,
				success: function() {
					location.reload();
				},
				error:function(){
					alert('failure');
				}
			});     
	}

	function submit_note(id)
	{
		var note = $("#note"+id).val();
			
			if(note == ''){
				alert('Catatan harus diisi')
				return false;
			}

			$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
			});

			$.ajax({
				type: "POST",
				url: '/backend/zakat/submit_note',
				data: {note: note, id:id},
				success: function(data) {
					alert('Berhasil Disimpan!');
					location.reload();
				},
				error:function(){
					alert('failure');
					location.reload();
				}
			});     
	}
</script>