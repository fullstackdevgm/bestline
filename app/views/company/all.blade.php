@extends('layout')

@section('title')
    Bestline - Companies
@stop

@section('stylesheets')
    @parent
@stop

@section('javascript-head')
    @parent
    <script src="/js/source/angular/services/bestline-api.js" type="application/javascript"></script>
    <script src="/js/source/angular/services/bestline-forms.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-isolate-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-address-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-contact-form.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/company/all.js" type="application/javascript"></script>

@stop

@section('main')
	<div class="templates templateCompanies" ng-controller="CompanyAllController as companyAll">

		<div class="row">
			<div class="col-md-6">
				<h1>All Companies</h1>
			</div>
			<div class="col-md-6 companyActions">
				<div class="row">
					<div class="col-md-4">
						<input class="form-control" type="search" placeholder="Search Companies..." ng-model="companyAll.searchTerm"/>
					</div>
					<div class="col-md-5">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-sort-amount-desc pointer" ng-show="companyAll.sortDesc" ng-click="companyAll.sortDesc = false"></i>
								<i class="fa fa-sort-amount-asc pointer" ng-show="!companyAll.sortDesc" ng-click="companyAll.sortDesc = true"></i>
							</span>
							<select ng-model="companyAll.sortSlug" class="form-control">
								<option value="created_at" selected>Sort: Date Created</option>
								<option value="name" selected>Sort: Company Name</option>
								<option value="account_no" selected>Sort: Account No.</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<a class="btn btn-primary btn-block" title="" href=""
							ng-show="!loadingNewCompany"
							ng-click="addCompany()">
							<i class="fa fa-fw fa-plus-square"></i> Add Company
						</a>
						<span ng-show="loadingNewCompany" class="add"><i class="fa fa-circle-o-notch fa-spin"></i></span>
					</div>
				</div>
			</div>
		</div>
		<hr/>
		<div ng-show="loadingCompanies"><span class="fa fa-circle-o-notch fa-spin fa-3x"></span></div>
		<div class="companies clearfix" ng-show="!loadingCompanies" ng-cloak>

			<div class="item"
				ng-repeat="company in companies | filter:companyAll.searchTerm | orderBy : companyAll.sortSlug : companyAll.sortDesc"
				ng-class="{fifth: $index%4===0, deleted: company.deleted}"
				ng-form="companyForm">

				<h4 ng-click="loadCompany(company)"><i class="fa fa-building"></i>{[{ company.name }]}</h4>
				<div class="actions">
					<span ng-show="company.updating"><i class="fa fa-circle-o-notch fa-spin"></i></span>
					<i class="remove fa fa-remove text-danger" title="Undo Edits" ng-show="!company.id" ng-click="deleteCompany(company, companyForm)"></i>
				</div>

				<div class="companySummary" ng-show="!company.editing && company.companyLoaded">
					<div class="actions">
						<i class="edit fa fa-pencil" title="Edit Company" ng-show="!company.editing" ng-click="startCompanyEdit(company, companyForm)"></i>
					</div>
					<div ng-click="startCompanyEdit(company, companyForm)">
						<div ng-show="company.phone_number"><b>Phone Number:</b> <a class="" href="tel:{[{ company.phone_number }]}" target="_blank">{[{ company.phone_number }]}</a></div>
						<div ng-show="company.website"><b>Website:</b> <a class="" title="Visit {[{ company.name }]}" href="{[{ company.website }]}" target="_blank">{[{ company.website }]}</a></div>
						<div ng-show="company.account_no"><b>Account No:</b> {[{ company.account_no }]}</div>
						<div ng-show="company.customer_type_id"><b>Customer Type:</b> {[{ getCustomerTypeName(company.customer_type_id) }]}</div>
						<div ng-show="company.credit_terms"><b>Credit Term:</b> {[{ companySelectOptions.credit_terms[company.credit_terms] }]}</div>
						<div ng-show="company.credit_term_notes"><b>Credit Term Notes:</b> {[{ company.credit_term_notes }]}</div>
						<div ng-show="company.notes"><b>Company Notes:</b> {[{ company.notes }]}</div>
					</div>
				</div>

				<div class="details companyDetails clearfix" ng-show="company.editing">
					<div class="actions">
						<span ng-show="company.updating"><i class="fa fa-circle-o-notch fa-spin"></i></span>
						<i class="cancel fa fa-remove" title="Undo Edits" ng-show="company.id" ng-click="cancelCompanyEdit(company, companyForm)"></i>
					</div>

					<input name="primary_billing_address_id" type="hidden" ng-model="company.primary_billing_address_id"/>
					<input name="primary_shipping_address_id" type="hidden" ng-model="company.primary_shipping_address_id"/>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.name.$dirty, 'has-error': companyForm.name.$invalid}">
						<label class="form-control-label">Company Name:</label>
						<input name="name" class="form-control input-sm" type="text" ng-model="company.name" ng-show="company.editing"/>
						<div class="alert alert-danger" ng-messages="companyForm.name.$error" role="alert" ng-show="companyForm.name.$invalid">
						    <ul ng-message="custom">
						    	<li ng-repeat="error in companyForm.name.baselineApiErrors">{[{ error }]}</li>
						    </ul>
						</div>
					</div>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.phone_number.$dirty}">
						<label>Phone Number:</label>
						<input name="phone_number" class="form-control input-sm" type="text" ng-model="company.phone_number" ng-show="company.editing"/>
					</div>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.website.$dirty}">
						<label>Website:</label> <a class="fa fa-external-link" title="" href="{[{ company.website }]}" ng-show="company.website"></a>
						<input name="website" class="form-control input-sm" type="text" ng-model="company.website" ng-show="company.editing"/>
					</div>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.account_no.$dirty}">
						<label>Account No:</label>
						<input class="form-control input-sm" type="text" ng-model="company.account_no" ng-show="company.editing"/>
					</div>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.customer_type_id.$dirty, 'has-error': companyForm.customer_type_id.$invalid}">
						<label>Customer Type:</label>
						<select name="customer_type_id" class="form-control input-sm"
							ng-show="company.editing"
							ng-model="company.customer_type_id"
							ng-options="item.id as item.name for item in companySelectOptions.customer_types">
						</select>
					</div>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.credit_terms.$dirty, 'has-error': companyForm.credit_terms.$invalid}">
						<label>Credit Terms:</label>
						<select name="credit_terms" class="form-control input-sm"
							ng-show="company.editing"
							ng-model="company.credit_terms"
							ng-options="key as value for (key , value) in companySelectOptions.credit_terms">
						</select>
						<div class="alert alert-danger" ng-messages="companyForm.credit_terms.$error" role="alert" ng-show="companyForm.credit_terms.$invalid">
						    <ul ng-message="custom">
						    	<li ng-repeat="error in companyForm.credit_terms.baselineApiErrors">{[{ error }]}</li>
						    </ul>
						</div>
					</div>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.credit_term_notes.$dirty}">
						<label>Credit Notes:</label>
						<textarea class="form-control input-sm" ng-show="company.editing" ng-model="company.credit_term_notes"></textarea>
					</div>

					<div class="detail form-group" ng-class="{'has-warning' : companyForm.notes.$dirty}">
						<label>Company Notes:</label>
						<textarea class="form-control input-sm" ng-show="company.editing" ng-model="company.notes"></textarea>
					</div>

					<div class="actions-footer">
						<span ng-show="company.updating" class="pull-right"><i class="fa fa-circle-o-notch fa-spin"></i></span>
						<a class="delete btn btn-sm btn-danger"
							ng-show="!company.updating"
							ng-click="deleteCompany(company, companyForm)">
							<i class="fa fa-trash"></i>
						</a>
						<a class="save btn btn-sm btn-success pull-right"
							ng-show="companyForm.$dirty && !company.updating"
							ng-click="saveCompany(company, companyForm)">
							Save
						</a>
						<a class="save btn btn-sm btn-secondary text-muted pull-right"
							ng-show="companyForm.$dirty && !company.updating"
							ng-click="cancelCompanyEdit(company, companyForm)">
							Cancel
						</a>
					</div>
				</div>

				<div class="notifications">
					<div class="alert alert-danger" ng-if="company.apiError">
						{[{ company.apiError }]}
					</div>
					<div class="alert alert-success" ng-if="company.apiSuccess">
						{[{ company.apiSuccess }]}
					</div>
				</div>

				<div ng-show="company.companyLoaded">
					<div class="detail form-group">
						<label><span>Addresses:</span></label>
						<span ng-show="!company.id">Save company to add...</span>
						<a class="add pull-right" title="Add Address" href="" ng-click='addAddress(company)' ng-show="!company.loadingAddresses && company.id"><i class="fa fa-fw fa-plus-square"></i> Add</a>
						<span ng-show="company.loadingAddresses" class="add pull-right"><i class="fa fa-circle-o-notch fa-spin"></i></span>
						<hr/>
						<div class="subitems">

							<div bestline-address-form="{ address: address, shippingMethods: companySelectOptions.shipping_methods, states: companySelectOptions.states, company: company, companyForm: companyForm}"
								ng-repeat="address in company.addresses | orderBy : 'updated_at' : true">
							</div>
						</div>
					</div>

					<div class="detail form-group">
						<label><span>Contacts:</span></label>
						<span ng-show="!company.id">Save company to add...</span>
						<a class="add pull-right" title="Add Address" href="" ng-click='addContact(company)' ng-show="!company.loadingContacts && company.id"><i class="fa fa-fw fa-plus-square"></i> Add</a>
						<span ng-show="company.loadingContacts" class="add pull-right"><i class="fa fa-circle-o-notch fa-spin"></i></span>
						<hr/>
						<div class="subitems">

							<bestline-contact-form bcf="{ contact: contact, phoneTypes: companySelectOptions.phone_types, }"
								ng-repeat="contact in company.contacts | orderBy : 'updated_at' : true">
							</bestline-contact-form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop