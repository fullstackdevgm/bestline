$(document).ready(function() {

	$('#optionsTree').on('delete_node.jstree', function(e, data) {
		var optionId = data.node.id;
		
		$('#options_edit_form :input').prop('disabled', true);
		
		if(data.node.id.substring(0,1) == 'j') {
			return;
		}
		
		$.post('/api/option/delete/' + optionId);
	});
	
	$('#savebtn').on('click', function(e) {
		var optionId = $('#optionsTree').jstree(true).get_selected()[0];

		var data = {
				'option_name' : $('#name').val(),
				'option_pricing_type' : $('#pricing_type').val(),
				'option_min_charge' : $('#mincharge').val(),
				'option_pricing_value' : $('#pricingvalue').val()
		};
		
		var parentId = $('#optionsTree').jstree(true).get_parent(optionId);
		
		if(parentId != -1) {
			data.parent_id = parentId;
		}
		
		if(data.node.id.substring(0,1) != 'j') {
			data.id = optionId;
		} 
		
		$.post('/api/option/save', data, function(data) {
			if(!data.id) {
				alert("Failed to save");
				return;
			} 
			
			$('#optionsTree').jstree(true).rename_node($('#optionsTree').jstree(true).get_selected(true), data.name);
			alert("Record Saved!")
		});
		
	});
	
	$('#options_edit_form :input').prop('disabled', true);
	
	$('#optionsTree').on('changed.jstree', function(e, data) {
		if(data.selected.length) {
			
			if(data.node.id == '-1') {
				$('#options_edit_form :input').prop('disabled', false);
				$('#name').val('');
				return;
			}
			
			if(data.node.id.substring(0,1) == 'j') {
				$('#options_edit_form :input').prop('disabled', false);
				$('#name').val(data.node.text);
				return;
			}
			
			$.get('/api/option/' + data.node.id, function(data) {
				$('#options_edit_form :input').prop('disabled', false);
				$('#name').val(data.name);
				
				if(!data.parent_id) {
					$('#pricing_type').prop('disabled', true).val('');
					$('#mincharge').prop('disabled', true).val('');
					$('#pricingvalue').prop('disabled', true).val('');
				} else {
					$('#pricing_type').prop('disabled', false).val(data.pricing_type);
					$('#mincharge').prop('disabled', false).val(data.min_charge);
					$('#pricingvalue').prop('disabled', false).val(data.pricing_value);
				}
			});
		}
	});
	
	$.get('/api/option/tree', function(data) {
		$('#optionsTree').jstree({
			'core' : {
				'check_callback' : true,
				'data' : data,
				'themes' : {
					'stripes' : true
				},
			},
			'types' : {
				'#' : {
					'max_depth' : 3,
					'valid_children' : ['root', 'option', 'suboption']
				},
				'option' : {
					'icon' : 'fa fa-fw fa-file-text-o'
				},
				'suboption' : {
					'icon' : 'fa fa-fw fa-file-text-o'
				}
			},
			"contextmenu" : {
				"items" : function(node) {
					var tree = $('#optionsTree').jstree(true);
					
					if(node.id == -1) {
						return {
							'Add' : {
								'label' : "Add",
								'action' : function(obj) {
									var selected = $('#optionsTree').jstree(true).get_selected();
									
									var newNode = $('#optionsTree').jstree(true).create_node(selected, {type : 'option'});
									
									$('#optionsTree').jstree(true).deselect_all();
									$('#optionsTree').jstree(true).select_node(newNode);
								}
							}
						};
					}
					
					return {
						'Add' : {
							'label' : "Add",
							'action' : function(obj) {
								var selected = $('#optionsTree').jstree(true).get_selected();
								
								var newNode = $('#optionsTree').jstree(true).create_node(selected, {type : 'option'});
								
								$('#optionsTree').jstree(true).deselect_all();
								$('#optionsTree').jstree(true).select_node(newNode);
							}
						},
						"Delete" : {
							"separator_before" : false,
							"separator_after" : false,
							"label" : "Delete",
							"action" : function(obj) {
								if(confirm("Are you sure?")) {
									tree.delete_node(node);
								}
							}
						}
					};
				}
			},
			'plugins' : [
			    "contextmenu", 
			    "state", "types", "wholerow"
		    ]
		});
		
		$('#optionsTree').jstree(true).disable_node(-1);
	});
});
