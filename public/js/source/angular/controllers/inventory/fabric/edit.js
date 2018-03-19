(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:InventoryFabricController
 * @description
 */
angular.module('bestline').controller('InventoryFabricController', InventoryFabricController);

function InventoryFabricController(
	$scope,
	$q,
	$location,
	bestlineApi,
	$window,
	FileUploader,
	$timeout
){

	var vm = this;

	function setup(){

		var urlFabricId = Number($location.path().split("/")[4]);
		var fabricId = isNaN(urlFabricId)? null : urlFabricId;
		var location = $location.search();

		vm.flaws = [];
		vm.showVideoCapture = false;
		vm.loadingFabric = true;
		vm.savingFabric = false;
		vm.isCom = (location.hasOwnProperty('com'))? true : false;
		vm.openPrint = (location.hasOwnProperty('print'))? true : false;
		vm.saveErrors = [];
		vm.uploadErrors = [];
		vm.adjustment = {};
		vm.companyPricesOptions = {type: 'fabric'};

		viewApi();
		setupUploader();
		getFabric(fabricId).then(function(fabric){
			getCompanyPrices(fabric);
		});
	};
	function viewApi(){

		vm.addFlaw = function(){
			vm.flaws.push("")
		};
		vm.saveFabric = function(fabric){
			saveFabric(fabric);
		};
		vm.saveWebcamPicture = function(){
			uploadWebcamPicture(vm.picture);
		};
		vm.adjustFabric = function(adjustment){
			adjustFabric(vm.fabric.id, adjustment);
		};
		vm.newCompanyPrice = function(){

		    var newPrice = {
		        fabric_id: $scope.fabric.id,
		    }
		    vm.companyPrices.unshift(newPrice);
		    openCompanyPriceEdit(newPrice)
		};
	}
	function setupUploader(){
		vm.uploader = new FileUploader();
		vm.uploaderOptions = {
			url: bestlineApi.upload('fabric').getUploadUrl(),
			autoUpload: true,
			queueLimit: 1,
			alias: 'image'
		};

		vm.uploader.onAfterAddingFile = onAfterAddingFile;
		vm.uploader.onSuccessItem = onSuccessItem;
		vm.uploader.onErrorItem = onErrorItem;

		function onAfterAddingFile(image){
			vm.showVideoCapture = false;
			vm.uploadErrors = [];
			image.upload();
		}
		function onSuccessItem(item, response, status, headers){
			vm.fabric.image = response;
		}
		function onErrorItem(item, response, status, headers){
			if(status === 500){
				vm.uploadErrors.push('Something went wrong. Contact support.');
			} else {
				vm.uploadErrors.push(response.error.message);
			}
		}
	}
	function getFabric(fabricId){

		return $q(getFabricPromise);

		function getFabricPromise(getFabricResolve, getFabricReject){
			vm.loadingFabric = true;
			bestlineApi.fabric(fabricId).get().then(getFabricSuccess, getFabricError);

			function getFabricSuccess(response){
				vm.fabric = response.data;
				vm.loadingFabric = false;
				if(vm.openPrint){
					$timeout(function(){$window.print();});
				}
				getFabricResolve(response.data);
			}
			function getFabricError(response){
				vm.loadingFabric = false;
				getFabricReject(response);
			}
		}
	}
	function saveFabric(fabric){

		return $q(saveFabricPromise);

		function saveFabricPromise(saveFabricResolve, saveFabricReject){
			vm.savingFabric = true;
			vm.saveErrors = [];
			bestlineApi.fabric(fabric.id).save(fabric).then(saveFabricSuccess, saveFabricError);

			function saveFabricSuccess(response){
				var savedFabric = response.data;
				redirectIfNew(fabric, savedFabric);
				vm.savingFabric = false;
				saveFabricResolve(response);
			}
			function saveFabricError(response){
				vm.savingFabric = false;
				vm.saveErrors.push(response.data.error.message);
				saveFabricReject(response);
			}
		}
	}
	function redirectIfNew(fabric, savedFabric){
		if(!fabric.hasOwnProperty('id') && savedFabric.hasOwnProperty('id')){
			if(savedFabric.owner_company_id){
				$window.location.href = "/inventory/fabric/edit/"+ savedFabric.id +"?com=1";
			} else {
				$window.location.href = "/inventory/fabric/edit/"+ savedFabric.id;
			}
		}
	}
	function uploadWebcamPicture(base64String){

		return $q(uploadWebcamPicturePromise);
		function uploadWebcamPicturePromise(uploadWebcamPictureResolve, uploadWebcamPictureReject){
			vm.uploadingPicture = true;
			vm.uploadErrors = [];
			var uploadType = 'fabric';
			var requestPayload = {base64: base64String};
			bestlineApi.upload(uploadType).image(requestPayload).then(uploadWebcamPictureSuccess, uploadWebcamPictureError);

			function uploadWebcamPictureSuccess(response){
				vm.uploadingPicture = false;
				vm.fabric.image = response.data;
				vm.showVideoCapture = false;
				uploadWebcamPictureResolve(response);
			}
			function uploadWebcamPictureError(response){
				vm.uploadingPicture = false;
				uploadWebcamPictureReject(response);
				vm.uploadErrors.push(response.data.error.message);
			}
		}
	}
	function adjustFabric(fabricId, adjustment){
		return $q(adjustFabricPromise);

		function adjustFabricPromise(adjustFabricResolve, adjustFabricReject){
			vm.adjustmentApiErrors = [];
			vm.adjustingFabric = true;
			bestlineApi.inventory().fabric(fabricId).adjust(adjustment).then(adjustFabricSuccess, adjustFabricError);

			function adjustFabricSuccess(response){
				vm.adjustingFabric = false;
				if(!vm.fabric.hasOwnProperty('all_inventory')){
					vm.fabric.hasOwnProperty = [];
				}
				vm.fabric.all_inventory.push(response.data);
				if(!vm.fabric.inventory){
					vm.fabric.inventory = {};
				}
				vm.fabric.inventory.quantity = response.data.quantity;
				adjustFabricResolve(response);
			}
			function adjustFabricError(response){

				vm.adjustingFabric = false;
				if(response.data.hasOwnProperty('adjustment')){
					vm.adjustmentApiErrors.push(response.data.adjustment[0]);
				}
				if(response.data.hasOwnProperty('reason')){
					vm.adjustmentApiErrors.push(response.data.reason[0]);
				}
				if(response.status === 500){
					vm.adjustmentApiErrors.push(response.data.error.message);
				}
				adjustFabricReject(response);
			}
		}
	}
	function getCompanyPrices(fabric){

	    return $q(getCompanyPricesPromise);

	    function getCompanyPricesPromise(getCompanyPricesResolve, getCompanyPricesReject){

	        var companyId = null;
	        var fabricId = fabric.id;

	        if(!fabricId){
	            var response = {
	                data: [],
	            }
	            getCompanyPricesSuccess(response);
	            return false;
	        }

	        bestlineApi.company(companyId).price().fabric(fabricId).then(getCompanyPricesSuccess, getCompanyPricesError);
	        function getCompanyPricesSuccess(response){
	            vm.companyPrices = response.data;
	            getCompanyPricesResolve(response);
	        }
	        function getCompanyPricesError(response){
	            getCompanyPricesReject(response);
	        }
	    }
	}
	setup();
}
InventoryFabricController.$inject = [
	'$scope',
	'$q',
	'$location',
	'bestlineApi',
	'$window',
	'FileUploader',
	'$timeout'
];

}(window.jQuery || window.$, window.angular));
