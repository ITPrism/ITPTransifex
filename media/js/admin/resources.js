jQuery(document).ready(function() {
	
	// Validation script
    Joomla.submitbutton = function(task){
    	
    	// Update resources.
    	if (task == 'resources.update') {

    		if(!hasSelectedItems()) {
    			alert(Joomla.JText._("COM_ITPTRANSIFEX_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST"));
    		} else {
    			Joomla.submitform(task, document.getElementById('adminForm'));
    		}
    		
		// Display modal window for creating project
    	} else if (task == 'package.create') { 
    		
    		if(!hasSelectedItems()) {
    			alert(Joomla.JText._("COM_ITPTRANSIFEX_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST"));
    		} else {
    			jQuery('#js-cp-modal').modal('show');
    		}

    	// Submit form
        } else {
        	Joomla.submitform(task, document.getElementById('adminForm'));
        }
    	
    };
    
    // Event for button Submit
    jQuery("#js-btn-sp").on("click", function(event){
		
		event.preventDefault();
		
		// Get form data
		var formData = jQuery("#packageForm").serializeArray();
		
		// Get resource IDs
		var resourceIDs = new Array();
		var checkBoxes  = jQuery("#resourcesList input:checkbox");
		jQuery.each(checkBoxes, function( index, value ) {
			
			if(jQuery(value).is(":checked")) {
				var resourceId = parseInt(jQuery(value).val());
				if(resourceId) {
					resourceIDs.push(resourceId);
				}
			}
			
		});
		
		jQuery.each(resourceIDs, function( index, value ) {
			var resource = {
				"name" : "resource[]",
				"value" : value
			};
			formData.push(resource);
		});
		
		var url = jQuery("#packageForm").attr("action");
		
		// Load data for this package
		jQuery.ajax({
			url: url,
			type: "POST",
			data: formData,
			dataType: "text json",
			beforeSend: function() {
				jQuery("#js-ajaxloader").show();
			},
			success: function(response) {
				
				jQuery("#js-ajaxloader").hide();
				jQuery('#js-cp-modal').modal('hide');
				
				if(!response.success) {
					
					PrismUIHelper.displayMessageFailure(response.title, response.text);
				
				} else {
					
					// Reset form data
					resetProjectForm();
					
					PrismUIHelper.displayMessageSuccess(response.title, response.text);
					
				}
				
			}
				
		});
		
	});
    
    // Event for button Cancel
	jQuery("#js-btn-cp-cancel").on("click", function(event){
		event.preventDefault();
		
		// Reset form data
		resetProjectForm();
		
		jQuery('#js-cp-modal').modal('hide');
	});
	
	// Reset form data
    jQuery('#js-cp-modal').on('hide', function () {
    	resetProjectForm();
    });
        
    /**
     * Reset some form fields.
     */
    function resetProjectForm() {
    	
    	jQuery("#jform_name").val("");
		jQuery("#jform_filename").val("");
		jQuery("#jform_version").val("");
		jQuery("#jform_description").val("");
		
    }
    
    /**
     * Check for selected items.
     */
    function hasSelectedItems() {
    	
    	var hasSelectedItems = false;
    	
    	// Look for selected resources.
    	var checkBoxes  = jQuery("#adminForm input:checkbox");
		jQuery.each(checkBoxes, function( index, value ) {
			
			if(jQuery(value).is(":checked")) {
				hasSelectedItems = true;
			}
			
		});
		
		return hasSelectedItems;
		
    }

    // Editable filenames
    var editableFilenameElemenats = jQuery('.js-editable-filename');
    editableFilenameElemenats.editable({
        url: 'index.php?option=com_itptransifex&task=resource.saveFilename',
        type: 'text',
        title: Joomla.JText._('COM_ITPTRANSIFEX_ENTER_FILENAME'),
        emptytext: Joomla.JText._('COM_ITPTRANSIFEX_EMPTY'),
        ajaxOptions: {
            type: 'post',
            dataType: 'text json'
        },
        params: {
            format: "raw"
        },
        onblur: "cancel"
    });

    // Generate default value from resource name.
    editableFilenameElemenats.on('shown', function(e, editable) {
        if (editable) {

            var value = editable.input.$input.val();

            if (!value) {

                var pk = editable.options.pk;
                var resourceName = jQuery("#js-resource-name-"+pk).text();

                var pattern = new RegExp("\.([a-z0-9_]+\.(sys\.ini|ini))");

                var matches = pattern.exec(resourceName);

                if (matches) {
                    editable.input.$input.val(matches[1]);
                }
            }
        }
    });

    // Editable types
    jQuery('.js-editable-type').editable({
        url: 'index.php?option=com_itptransifex&task=resource.saveType',
        type: 'select',
        source: [{value: "site", text: "site"}, {value: "admin", text: "admin"}],
        prepend: Joomla.JText._('COM_ITPTRANSIFEX_NOT_SELECTED'),
        title: Joomla.JText._('COM_ITPTRANSIFEX_SELECT_TYPE_EDITABLE'),
        emptytext: Joomla.JText._('COM_ITPTRANSIFEX_EMPTY'),
        ajaxOptions: {
            type: 'post',
            dataType: 'text json'
        },
        params: {
            format: "raw"
        },
        onblur: "cancel"
    });

});