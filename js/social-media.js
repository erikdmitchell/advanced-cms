jQuery(document).ready(function($) {

	var input_id;

	$('.icon-modal-link').modal();

	$('body').on('click','.icon-modal-link',function() {
		input_id=$(this).data('input-id');
	});

	$('.fa-icons-list li a').click(function(e) {
		e.preventDefault();

		$('#'+input_id).val($(this).data('icon'));

		$('.'+input_id+'-icon .icon-img-fa').html('');
		$('.'+input_id+'-icon.icon-img .icon-img-fa').append('<i class="fa '+$(this).data('icon')+'"></i>');

		$(".jquery-modal").fadeOut(200);
		$("#fa-icons-overlay").fadeOut(200);
	});

	/**
	 *
	 */
	$('#default_field').parent().parent().hide();

	/**
	 *
	 */
	$('#add-field').click(function(e) {
		e.preventDefault();

		if ($('#add-field-name').val()=='') {
			return false;
		}

		var $tr=$('#default_field').parent().parent();
		var $clone=$tr.clone();
		$tr.after($clone);
		$clone.show();
		setupClone($clone,$('#add-field-name').val());

		$('#add-field-name').val('');
	});

	/**
	 *
	 */
	function setupClone($tr,name) {
		var id=name.replace(/\W+/g, " ");
		var $td=$tr.find('td');
		var url=$td.find('input#default_field');
		id=id.toLowerCase();

		$tr.find('th').text(name); // set name

		$td.html($td.html().replace(/default_field/g,id));

		$td.find('#github-name').val(name); // set name

	}

});