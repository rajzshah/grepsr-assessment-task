/* simple media selector for resources fallback image */
jQuery(function ($) {
	let frame;
	const selectBtn = $('#resources-cpt-select-fallback');
	const removeBtn = $('#resources-cpt-remove-fallback');
	const input = $('#resources_cpt_fallback_image_id');
	const preview = $('#resources-cpt-fallback-preview');

	selectBtn.on('click', function (e) {
		e.preventDefault();
		if (frame) {
			frame.open();
			return;
		}
		frame = wp.media({
			title: 'Select fallback image',
			button: { text: 'Use this image' },
			multiple: false
		});
		frame.on('select', function () {
			const attachment = frame.state().get('selection').first().toJSON();
			input.val(attachment.id);
			preview.attr('src', attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url);
			preview.show();
		});
		frame.open();
	});

	removeBtn.on('click', function (e) {
		e.preventDefault();
		input.val('');
		preview.hide();
	});
});


