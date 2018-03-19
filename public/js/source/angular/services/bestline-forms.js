(function ($, angular) {
'use strict';
/**
 * @ngdoc service
 * @name bestline.bestlineForms
 * @description
 * # bestlineForms
 * Factory in the bestline.
 */
angular.module('bestline')
.factory('bestlineForms', function ($q) {
  
  	var bestlineForms = {};//object returned from factory

  	bestlineForms.startEdit = function(details, detailsForm, remove){ 

  	    if(!details.editing){

  	        detailsForm.originalValues = angular.fromJson(angular.toJson(details));

  	        //remove any keys that are specified in the comma separated list remove
  	        if(remove){
  	            var removeArray = remove.split(",");
  	            angular.forEach(removeArray, function(key){

  	                detailsForm.originalValues[key.trim()] = undefined;
  	            });
  	        }

  	        details.editing = true;
  	    }
  	};

  	bestlineForms.cancelEdit = function(details, detailsForm){

  	    if(detailsForm.$dirty){
            details = $.extend(details, detailsForm.originalValues);
            detailsForm.$setPristine();
        }
  	    details.editing = false;
  	};

  	bestlineForms.updateResource = function(resource, item, itemForm){

      return $q(updateResourcePromise);

      function updateResourcePromise(resolveUpdateResrouce, rejectUpdateResource){

        item.updating = true;
        resource.then(updateResourceSuccess, updateResourceError);

        function updateResourceSuccess(response){

          bestlineForms.handleUpdateSuccess(item, itemForm, response);
          item.updating = undefined;
          resolveUpdateResrouce(response);
        };
        function updateResourceError(response){

          bestlineForms.handleUpdateError(item, itemForm, response);
          item.updating = undefined;
          rejectUpdateResource();
        };
      };
  	};

  	bestlineForms.handleUpdateSuccess = function(item, form, response){

      bestlineForms.resetFormValidity(form, 'baseline');
  		form.$setUntouched();
  		form.$setPristine();
  		item.deleted = undefined;
  		item.apiError = undefined;
  		item.apiSuccess = undefined;
      item.id = response.data.id;
  	};

  	bestlineForms.handleUpdateError = function(item, form, response){
  		
  		if(response.status === 422){ //validation errors

  			bestlineForms.resetFormValidity(form, 'baseline');
  			angular.forEach(response.data, function(errors, key){
  				form[key].$setValidity("baseline", false);
  				form[key].baselineApiErrors = errors;
  			});

        item.apiError = 'See new errors.';
  		}
  		if(response.status === 410){ //something is not found

  			item.apiError = response.data;
  		}
  		if(response.status === 500){

  			item.apiError = response.data.error.message;
  		}
  	};

  	bestlineForms.resetFormValidity = function(form, errorKey){

  		if(form.$error[errorKey]){
  			angular.forEach(form.$error[errorKey], function(error){
  				error.$setValidity(errorKey, true);
  			});
  			angular.forEach(form.$error[errorKey], function(error){ //not happy that I have to do this twice
  				error.$setValidity(errorKey, true);
  			});
  		}
  	};

  	bestlineForms.deleteResource = function(resource, item){

        return $q(deleteResourcePromise);

        function deleteResourcePromise(resolveDeleteResource, rejectDeleteResource){

            item.updating = true;
            resource.destroy().then(deleteResourceSuccess, deleteResourceError);

            function deleteResourceSuccess(response){
                
                bestlineForms.handleDeleteSuccess(item);
                item.updating = undefined;
                resolveDeleteResource();
            };
            function deleteResourceError(response){
                item.apiError = response.data.error.message;
                item.updating = undefined;
                rejectDeleteResource();
            };
        };
  	};

  	bestlineForms.handleDeleteSuccess = function(item){
  		//item = company, address, etc.

  		item.deleted = true;
  		item.id = null;
  		item.apiSuccess = 'Deleted';
  		item.apiError = undefined;
  		item.showDetails = undefined;
  		item.editing = undefined;
  	};

  	bestlineForms.setParentKey = function(parent, parentForm, key, value){

  	    parent[key] = value;
  	    parentForm[key].$setDirty();
  	};
  
  	return bestlineForms;
});
}(window.jQuery || window.$, window.angular));
