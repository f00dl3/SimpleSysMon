(function worker() {
	$.ajax({
	url: 'Include/SNMP.php',
	success: function(data) {
		$('#SNMPView').html(data);
	},
	complete: function() { setTimeout(worker, 1000*5); }
	});
})();
