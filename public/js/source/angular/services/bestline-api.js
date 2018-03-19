/**
 * @ngdoc service
 * @name bestline.bestlineApi
 * @description
 * # bestlineApi
 * Factory in the bestline.
 */
angular.module('bestline').factory('bestlineApi', function ($http) {
  'use strict';

  var bestlineApi = {};//object returned from factory
  var apiCache = {};

  bestlineApi.setup = function(){
    //setup api cache
    apiCache.fabricTypes = null;
    apiCache.optionOptions = null;
  };

  bestlineApi.company = function(companyId){

    var company = {};

    company.all = function(){
      var url = '/api/company/all';
      var all = $http.get(url);
      return all;
    };

    company.newCompany = function(){
      var url = '/api/company/new';
      var newCompany = $http.get(url);
      return newCompany;
    };

    company.update = function(data){
      var url = '/api/company/' + companyId;
      var update = $http.put(url, data);
      return update;
    };

    company.destroy = function(){
      var url = '/api/company/' + companyId ;
      var destroy = $http.delete(url);
      return destroy;
    };

    company.selectOptions = function(){
      var url = '/api/company/select-options';
      var selectOptions = $http.get(url);
      return selectOptions;
    };

    company.addresses = function(){
      var url = '/api/company/' + companyId + '/address-all';
      var addresses = $http.get(url);
      return addresses;
    };

    company.fabrics = function(){
      var url = '/api/company/' + companyId + '/fabrics';
      var fabrics = $http.get(url);
      return fabrics;
    };

    company.address = function(addressId){

      var address = {};

      address.newAddress = function(){
        var url = '/api/company/undefined/new-address/';
        var newAddress = $http.get(url);
        return newAddress;
      };

      address.update = function(data){
        var url = '/api/company/' + companyId + '/address/' + addressId + '/';
        var update = $http.put(url, data);
        return update;
      };

      address.destroy = function(data){
        var url = '/api/company/' + companyId + '/address/' + addressId + '/';
        var destroy = $http.delete(url);
        return destroy;
      };

      return address;
    };

    company.contacts = function(){
      var url = '/api/company/' + companyId + '/contacts';
      var contacts = $http.get(url);
      return contacts;
    };

    company.contact = function(contactId){

      var contact = {};

      contact.newContact = function(){
        var url = '/api/company/undefined/new-contact/';
        var newContact = $http.get(url);
        return newContact;
      };

      contact.update = function(data){
        var url = '/api/company/' + companyId + '/contact/' + contactId + '/';
        var update = $http.put(url, data);
        return update;
      };

      contact.destroy = function(data){
        var url = '/api/company/undefined/contact/' + contactId + '/';
        var destroy = $http.delete(url);
        return destroy;
      };

      contact.email = function(emailId){

        var email = {};

        email.newEmail = function(){
          var url = '/api/contact/undefined/new-email/';
          var newEmail = $http.get(url);
          return newEmail;
        };

        email.update = function(data){
          var url = '/api/contact/' + contactId + '/email/' + emailId + '/';
          var update = $http.put(url, data);
          return update;
        };

        email.destroy = function(data){
          var url = '/api/contact/undefined/email/' + emailId + '/';
          var destroy = $http.delete(url);
          return destroy;
        };

        return email;
      };

      contact.phone = function(phoneId){

        var phone = {};

        phone.newPhone = function(){
          var url = '/api/contact/undefined/new-phone-number/';
          var newphone = $http.get(url);
          return newphone;
        };

        phone.update = function(data){
          var url = '/api/contact/' + contactId + '/phone-number/' + phoneId + '/';
          var update = $http.put(url, data);
          return update;
        };

        phone.destroy = function(data){
          var url = '/api/contact/undefined/phone-number/' + phoneId + '/';
          var destroy = $http.delete(url);
          return destroy;
        };

        return phone;
      };

      return contact;
    };

    company.price = function(priceId){
      var price = {};

      //bestlineApi.company(companyId).price().fabric(fabricId)
      price.fabric = function(fabricId){
        return $http.get('/api/company/'+ companyId +'/price/fabric/'+ fabricId);
      }

      //bestlineApi.company(companyId).price().product(productId)
      price.product = function(productId){
        return $http.get('/api/company/'+ companyId +'/price/product/'+ productId);
      }

      //bestlineApi.company(companyId).price().option(optionId)
      price.option = function(optionId){
        return $http.get('/api/company/'+ companyId +'/price/option/'+ optionId);
      }

      //bestlineApi.company(companyId).price().selectOptions()
      price.selectOptions = function(){
        return $http.get('/api/company/'+ companyId +'/price/select-options/');
      };

      //bestlineApi.company(companyId).price().save(payload)
      price.save = function(payload){
        return $http.put('/api/company/'+ companyId +'/price/'+ priceId +'/save/', payload);
      }

      //bestlineApi.company().price(priceId).delete()
      price.delete = function(){
        return $http.delete('/api/company/'+ companyId +'/price/'+ priceId +'/delete/');
      }

      return price;
    };

    return company;
  };

  bestlineApi.order = function(orderId){

    var order = {};

    order.get = function(){
      var url = '/api/order/' + orderId;
      var get = $http.get(url);
      return get;
    };

    order.update = function(data){
      var url = '/api/order/' + orderId;
      var update = $http.put(url, {data: data});
      return update;
    };

    order.finalize = function(){
      return $http.post('/api/order/' + orderId + '/finalize');
    };

    order.confirm = function(){
      return $http.post('/api/order/' + orderId + '/confirm');
    };

    order.destroy = function(){
      return  $http.delete('/api/order/' + orderId);
    };

    order.updateStep1 = function(data){
      var url = '/api/order/step-one';
      var updateStep1 = $http.put(url, {data: data});
      return updateStep1;
    };

    order.finalized = function(){

      var finalized = {};

      finalized.get = function(){
        return $http.get('/api/order/final/' + orderId);
      };

      //bestlineApi.order(orderId).finalized().labels()
      finalized.labels = function(){
        return $http.get('/api/order/final/' + orderId + '/labels');
      }

      finalized.unfinalize = function(){
        return $http.get('/api/order/final/'+orderId+'/unfinalize');
      };

      return finalized;
    };

    order.selectOptions = function(){
      var url = '/api/order/select-options';
      var selectOptions = $http.get(url);
      return selectOptions;
    };

    order.option = function(optionId){

      var option = {};

      option.destroy = function(){
        var url = '/api/order/' + orderId + '/option/' + optionId + '/';
        var destroy = $http.delete(url);
        return destroy;
      };

      return option;
    };

    order.fabric = function(fabricId){

      var fabric = {};

      fabric.destroy = function(){
        var url = '/api/order/' + orderId + '/fabric/' + fabricId + '/';
        var destroy = $http.delete(url);
        return destroy;
      };

      fabric.option = function(optionId){

        var option = {};

        option.destroy = function(){
          var url = '/api/order/' + orderId + '/fabric/' + fabricId + '/option/'+ optionId;
          var destroy = $http.delete(url);
          return destroy;
        };

        return option;
      };

      return fabric;
    };

    order.orderLine = function(orderLineId){

      var orderLine = {};

      orderLine.getNew = function(){
        var url = '/api/order/'+orderId+'/order-line/'+orderLineId+'/new/';
        var getNew = $http.get(url);
        return getNew;
      };

      orderLine.calculate = function(data, promise){
        var url = '/api/order/'+orderId+'/order-line/'+orderLineId+'/calculate';
        var calculate = $http({
          method: 'POST',
          url: url,
          data: data,
          timeout: promise
        });
        return calculate;
      };

      orderLine.destroy = function(){
        var url = '/api/order/'+orderId+'/order-line/'+orderLineId+'/';
        var destroy = $http.delete(url);
        return destroy;
      };

      orderLine.option = function(optionId){

        var option = {};

        option.destroy = function(){
          var url = '/api/order/'+orderId+'/order-line/'+orderLineId+'/option/'+optionId+'/';
          var destroy = $http.delete(url);
          return destroy;
        };

        return option;
      };

      orderLine.work = function(workId){

        var work = {};

        work.checkin = function(){
          return $http.post('/api/order/'+orderId+'/order-line/'+orderLineId+'/work/'+workId+'/checkin');
        };

        work.undo = function(){
          return $http.post('/api/order/'+orderId+'/order-line/'+orderLineId+'/work/'+workId+'/undo');
        };

        work.checkout = function(userId){
          return $http.post('/api/order/'+orderId+'/order-line/'+orderLineId+'/work/'+workId+'/checkout/'+ userId);
        };

        return work;
      };

      return orderLine;
    };

    order.all = function(allId){

      var all = {};

      all.openOrders = function(){
        return $http.get('/api/order/all/open/');
      };

      return all;
    };

    return order;
  };

  bestlineApi.fabric = function(fabricId){

    var fabric = {};

    fabric.get = function(){
      return $http.get('/api/fabric/' + fabricId);
    };

    fabric.save = function(fabricData){
      return $http.put('/api/fabric/' + fabricId + '/save', fabricData);
    };

    fabric.type = function(type){
      var url = '/api/fabric/type/' + type +'/';
      var type = $http.get(url);
      return type;
    };

    fabric.types = function(){

      //return cache if exists
      if(apiCache.fabricTypes){
        return apiCache.fabricTypes;
      }

      var url = '/api/fabric/types/';
      apiCache.fabricTypes = $http.get(url);
      return apiCache.fabricTypes;
    };

    fabric.all = function(){
      return $http.get('/api/fabric/all');
    };

    fabric.bestline = function(){
      return $http.get('/api/fabric/bestline');
    };

    fabric.com = function(){
      return $http.get('/api/fabric/com');
    };

    fabric.unknown = function(){
      return $http.get('/api/fabric/unknown');
    };

    return fabric;
  };

  bestlineApi.option = function(optionId){

    var option = {};

    option.options = function(){

      //return cache if exists
      if(apiCache.optionOptions){
        return apiCache.optionOptions;
      }

      var url = '/api/option/all';
      apiCache.optionOptions = $http.get(url);
      return apiCache.optionOptions;
    };

    option.children = function(id){
      var url = '/api/option/' + id + '/';
      var children = $http.get(url);
      return children;
    };

    return option;
  };

  bestlineApi.user = function(userId){

    var user = {};

    user.get = function(){
      var url = '/api/user/get';
      return $http.get(url);
    };

    return user;
  };

  bestlineApi.station = function(stationId){

    var station = {};

    station.orders = function(){

      return $http.get('/api/station/'+stationId+'/orders/');
    };

    station.users = function(){

      return $http.get('/api/station/'+stationId+'/users/');
    };

    return station;
  };

  bestlineApi.upload = function(type){
    var upload = {};

    upload.getUploadUrl = getUploadUrl;
    upload.image = uploadImage;

    function getUploadUrl(){
      return '/api/upload/' + type;
    }
    function uploadImage(payload){
      return $http.post('/api/upload/' + type, payload);
    }

    return upload;
  };

  bestlineApi.inventory = function(){

    var inventory = {};

    inventory.fabric = function(fabricId){

      var fabric = {};

      fabric.adjust = function(payload){
        return $http.post('/api/inventory/fabric/'+ fabricId +'/adjust', payload);
      };

      return fabric;
    };

    return inventory;
  };

  bestlineApi.parts = function(){

    var parts = {};

    parts.products = function(){

      var products = {};

      products.all = function(){
        return $http.get('/api/parts/products/all');
      };

      products.getProduct = function(productId){
        return $http.get('/api/parts/products/'+ productId);
      };

      products.save = function(productId, payload){
        return $http.put('/api/parts/products/'+ productId +'/save', payload);
      };

      products.delete = function(productId, payload){
        return $http.delete('/api/parts/products/'+ productId);
      };

      products.selectOptions = function(){
        return $http.get('/api/parts/products/select-options');
      };

      return products;
    };

    parts.options = function(){

      var options = {};

      options.all = function(){
        return $http.get('/api/parts/options/all');
      };

      options.getOption = function(optionId){
        return $http.get('/api/parts/options/'+ optionId);
      };

      options.save = function(optionId, payload){
        return $http.put('/api/parts/options/'+ optionId +'/save', payload);
      };

      options.delete = function(optionId, payload){
        return $http.delete('/api/parts/options/'+ optionId);
      };

      options.selectOptions = function(){
        return $http.get('/api/parts/options/select-options');
      };

      options.allSuboptions = function(){
        return $http.get('/api/parts/options/all-suboptions');
      };

      return options;
    };

    return parts;
  };

  bestlineApi.setup();

  return bestlineApi;
});
