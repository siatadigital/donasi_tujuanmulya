<script>
$(document).ready(function(){
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			$('#filter').click();
		}
	});

	$('.input-daterange').datepicker({
		todayBtn:'linked',
		format:'yyyy-mm-dd',
		autoclose:true
	});

  $('#filter').click(function(){
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		var cari = $('#cari').val();
		var type_cari = $('#type_cari').val();
		var categories = $('#categories').val();

		var query = $.param({
			from_date: from_date,
			to_date: to_date,
			cari: cari,
			type_cari: type_cari,
			category_ids: categories,
		});

		if(from_date == '' &&  to_date == '' && cari != '' && type_cari == 'Pilih Tipe Cari'){
			alert('Pilih Tipe Cari Harus Diisi');
		}else if(from_date != '' &&  to_date != '' && cari != '' && type_cari == 'Pilih Tipe Cari'){
			alert('Pilih Tipe Cari Harus Diisi');
		}else if(from_date == '' &&  to_date == '' && cari != '' && type_cari != 'Pilih Tipe Cari'){
			window.location.href = `?${query}`;
		}
		else if(from_date != '' &&  to_date != '' &&  cari == '' &&  type_cari == 'Pilih Tipe Cari')
		{
			window.location.href = `?${query}`;
		}else if(from_date != '' &&  to_date != '' && cari != '' && type_cari != 'Pilih Tipe Cari'){
			window.location.href = `?${query}`;
		}else if(categories != ''){
			window.location.href = `?${query}`;
		}
	});

	$('#export').click(function(){
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		var cari = $('#cari').val();
		var type_cari = $('#type_cari').val();

		if(from_date == '' &&  to_date == '' && cari != '' && type_cari == 'Pilih Tipe Cari'){
			alert('Pilih Tipe Cari Harus Diisi');
			return false;
		}else if(from_date != '' &&  to_date != '' && cari != '' && type_cari == 'Pilih Tipe Cari'){
			alert('Pilih Tipe Cari Harus Diisi');
			return false;
		}
	});

	$('#refresh').click(function(){
    window.location.href = '?page=1';
	});
});
</script>
