jQuery(document).ready(function($) {
	if ($('select[name=_edd_product_type]').length > 0) {
		if ($('select[name=_edd_product_type]').val() == '0') {
			$('#edd-software-licensing-custom-expiration').hide();
		}
		$('select[name=_edd_product_type]').change(function() {
			if ($('select[name=_edd_product_type]').val() == '0') {
				$('#edd-software-licensing-custom-expiration').hide();
			} else {
				$('#edd-software-licensing-custom-expiration').show();
			}
		});
	}
});