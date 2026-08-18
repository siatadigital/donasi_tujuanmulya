(function($) {

validasiAngka = function (field) {
	var Char;
	var sudahkoma = false;
	var belakangkoma = 2;
	var k = 1;
	Char = "";
	for (i = 0; i < (field.value.length); i++) {
		if (isNaN(field.value.charAt(i)) && field.value.charAt(i) != '.' && field.value.charAt(i) != ',') {
			break;
		} else {
			if (sudahkoma == true) {
				if (field.value.charAt(i) == '.' || k > belakangkoma) {
					break;
				}
				k++;
			}
			if (field.value.charAt(i) == ',') {
				sudahkoma = true;
			}
			Char = Char + field.value.charAt(i);
		}
	}
	field.value = Char;
}

validasi_float = function (num) {
	numfloat = parseFloat(num);
	if (isNaN(numfloat)) {
		numfloat = 0.00;
	}
	return numfloat;
}

nisab_emas = function () {
	harga = $('#harga_emas').val();
	harga = $.elsyifaJS.indonesianNumberToFloat(harga);
	
	nisab = 85 * harga;
	$('#nisab_emas_float').val(nisab);
	
	nisab = $.elsyifaJS.toIndonesianNumber(nisab);
	$('#nisab_emas').val(nisab);
}

nisab_beras = function () {
	harga = $('#harga_beras').val();
	harga = $.elsyifaJS.indonesianNumberToFloat(harga);
	
	nisab = 750 * harga;
	$('#nisab_beras_float').val(nisab);
	
	nisab = $.elsyifaJS.toIndonesianNumber(nisab);
	$('#nisab_beras').val(nisab);
}

nisab_penghasilan = function () {
	harga = $('#zp_harga_emas').val();
	harga = $.elsyifaJS.indonesianNumberToFloat(harga);
	
	nisab = (85 * harga) / 12;
	$('#zp_nisab_float').val(nisab);
	
	nisab = $.elsyifaJS.toIndonesianNumber(nisab);
	$('#zp_nisab').val(nisab);
}

/* zakat tabungan (umum) */
zc_mal_nisab = function () {

	$("#zt_harga_emas").prop('disabled', false);
		
	harga = $('#zt_harga_emas').val();
	harga = $.elsyifaJS.indonesianNumberToFloat(harga);
	nisab = 85 * harga;
	
	$('#zt_nisab_float').val(nisab);
	
	nisab = $.elsyifaJS.toIndonesianNumber(nisab);
	$('#zt_nisab').val(nisab);
}

zc_mal_total_harta = function () {
	uang_tabungan = $('#zt_uang_tabungan').val();
	
	uang_tabungan = $.elsyifaJS.indonesianNumberToFloat(uang_tabungan);
	
	total_harta = uang_tabungan;
	$('#zt_total_harta_float').val(total_harta);
	
	total_harta = $.elsyifaJS.toIndonesianNumber(total_harta);
	$('#zt_total_harta').val(total_harta);
	
	zc_tab_hitung();
}

zc_mal_total_kewajiban = function () {
	hutang = $('#zt_hutang').val();
	hutang = $.elsyifaJS.indonesianNumberToFloat(hutang);
	
	total_kewajiban = hutang;
	$('#zt_total_kewajiban_float').val(total_kewajiban);
	
	total_kewajiban = $.elsyifaJS.toIndonesianNumber(total_kewajiban);
	$('#zt_total_kewajiban').val(total_kewajiban);
	
	zc_tab_hitung();
}

zc_tab_hitung = function () {
	nisab = $('#zt_nisab_float').val();
	harta = $('#zt_total_harta_float').val();
	kewajiban = $('#zt_total_kewajiban_float').val();
	
	nisab = validasi_float(nisab);
	harta = validasi_float(harta);
	kewajiban = validasi_float(kewajiban);
	
	selisih_harta = harta - kewajiban;
	$('#zt_selisih_harta').val($.elsyifaJS.toIndonesianNumber(selisih_harta));
	
	if (selisih_harta >= nisab) {
		zakat = 0.025 * selisih_harta;
		$('#keterangan_tabungan').html('Harta SUDAH mencapai nishab. Dikenakan KEWAJIBAN ZAKAT.');
	} else {
		zakat = 0.00;
		$('#keterangan_tabungan').html('Harta BELUM mencapai nishab. Tidak dikenai kewajiban zakat.');
	}
	
	$('#zt_zakat_harta').val($.elsyifaJS.toIndonesianNumber(zakat));
}

/* zakat perdagangan */

zc_emas_nisab = function () {

	$("#harga_emas").prop('disabled', false);
		
	harga = $('#harga_emas').val();
	harga = $.elsyifaJS.indonesianNumberToFloat(harga);
	nisab = 85 * harga;
	
	$('#nisab_emas_float').val(nisab);
	
	nisab = $.elsyifaJS.toIndonesianNumber(nisab);
	$('#nisab_emas').val(nisab);

	zc_mal_hitung();
}

zc_mal_hitung = function () {
	nisab = $('#nisab_emas').val();
	harta = $('#total_harta_float').val();
	kewajiban = $('#total_kewajiban_float').val();
	
	nisab = validasi_float(nisab);
	harta = validasi_float(harta);
	kewajiban = validasi_float(kewajiban);
	
	selisih_harta = harta - kewajiban;
	$('#selisih_harta').val($.elsyifaJS.toIndonesianNumber(selisih_harta));
	
	if (selisih_harta >= nisab) {
		zakat = 0.025 * selisih_harta;
		$('#keterangan').html('Harta SUDAH mencapai nishab. Dikenakan KEWAJIBAN ZAKAT.');
	} else {
		zakat = 0.00;
		$('#keterangan').html('Harta BELUM mencapai nishab. Tidak dikenai kewajiban zakat.');
	}
	
	$('#zakat_harta').val($.elsyifaJS.toIndonesianNumber(zakat));
}


zc_dagang_total_harta = function () {
	uang = $('#uang').val();
	stok = $('#stok').val();
	piutang = $('#piutang').val();
	
	uang = $.elsyifaJS.indonesianNumberToFloat(uang);
	stok = $.elsyifaJS.indonesianNumberToFloat(stok);
	piutang = $.elsyifaJS.indonesianNumberToFloat(piutang);
	
	total_harta = uang + stok + piutang;
	$('#total_harta_float').val(total_harta);
	$('#total_harta').val($.elsyifaJS.toIndonesianNumber(total_harta));
	
	zc_mal_hitung();
}

zc_dagang_total_kewajiban = function () {
	hutang = $('#hutang').val();
	hutang = $.elsyifaJS.indonesianNumberToFloat(hutang);
	
	biaya = $('#biaya').val();
	biaya = $.elsyifaJS.indonesianNumberToFloat(biaya);
	
	kewajiban = hutang + biaya;
	
	$('#total_kewajiban_float').val(kewajiban);
	$('#total_kewajiban').val($.elsyifaJS.toIndonesianNumber(kewajiban));
	
	zc_mal_hitung();
}

/* zakat emas */
zc_emas_perak = function () {
	emas = $('#emas').val();
	emas = $.elsyifaJS.indonesianNumberToFloat(emas);
	
	if (emas < 85) {
		zakat_emas = 0;
		$('#zakat_emas2').val(zakat_emas);
		$('#keterangan_emas').html('Harta BELUM mencapai nishab. Tidak dikenakan KEWAJIBAN ZAKAT.');
	} else {
		zakat_emas = 0.025 * emas;
		$('#zakat_emas2').val($.elsyifaJS.toIndonesianNumber(zakat_emas));
		$('#keterangan_emas').html('Harta SUDAH mencapai nishab. Dikenakan KEWAJIBAN ZAKAT.');
	}
	
	harga_emas = $.elsyifaJS.indonesianNumberToFloat($('#harga_emas2').val());
	
	zakat_emas_uang = zakat_emas * harga_emas;
	zakat_total_uang = zakat_emas_uang;
	
	$('#zakat_emas_uang').val($.elsyifaJS.toIndonesianNumber(zakat_emas_uang));
	$('#zakat_total_uang').val($.elsyifaJS.toIndonesianNumber(zakat_total_uang));
}

/* zakat penghasilan */
zc_penghasilan = function () {
	penghasilan = $('#zp_penghasilan').val();
	
	penghasilan = $.elsyifaJS.indonesianNumberToFloat(penghasilan);
	
	total_harta = penghasilan;
	$('#zp_total_harta_float').val(total_harta);
	
	total_harta = $.elsyifaJS.toIndonesianNumber(total_harta);
	$('#zp_total_harta').val(total_harta);
	
	zc_penghasilan_hitung();
}

zc_penghasilan_kewajiban = function () {
	hutang = $('#zp_hutang').val();
	hutang = $.elsyifaJS.indonesianNumberToFloat(hutang);
	
	total_kewajiban = hutang;
	$('#zp_total_kewajiban_float').val(total_kewajiban);
	
	total_kewajiban = $.elsyifaJS.toIndonesianNumber(total_kewajiban);
	$('#zp_total_kewajiban').val(total_kewajiban);
	
	zc_penghasilan_hitung();
}

zc_penghasilan_hitung = function () {
	nisab = $('#zp_nisab_float').val();
	harta = $('#zp_total_harta_float').val();
	kewajiban = $('#zp_total_kewajiban_float').val();
	
	nisab = validasi_float(nisab);
	harta = validasi_float(harta);
	kewajiban = validasi_float(kewajiban);
	
	selisih_harta = harta - kewajiban;
	$('#zp_selisih_harta').val($.elsyifaJS.toIndonesianNumber(selisih_harta));
	
	if (selisih_harta >= nisab) {
		zakat = 0.025 * selisih_harta;
		$('#keterangan_penghasilan').html('Harta SUDAH mencapai nishab. Dikenakan KEWAJIBAN ZAKAT.');
	} else {
		zakat = 0.00;
		$('#keterangan_penghasilan').html('Harta BELUM mencapai nishab. Tidak dikenai kewajiban zakat.');
	}
	
	$('#zp_zakat_harta').val($.elsyifaJS.toIndonesianNumber(zakat));
}

})(jQuery);
