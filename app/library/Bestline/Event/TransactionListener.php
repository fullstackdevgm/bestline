<?php

namespace Bestline\Event;

use Order\Transaction;
use Order\LineItem;
use \Log;

class TransactionListener
{
    public function onOrderLineItemCreated(\User $user = null, LineItem $lineItem) 
    {
        if(\App::runningInConsole()) {
            return;
        }
    }
    
    public function onOrderLineItemUpdated(\User $user = null, LineItem $lineItem)
    {
        if(\App::runningInConsole()) {
            return;
        }
    }
    
    public function onOrderCreated(\User $user = null, \Order $order)
    {
        if(\App::runningInConsole()) {
            return;
        }
        
        $msg = "Created order #{$order->id}";
        Transaction::recordEntry($user, $order, Events::ORDER_CREATED, $msg);
    }
    
    public function onOrderUpdated(\User $user = null, \Order $order)
    {
        if(\App::runningInConsole()) {
            return;
        }
        
        $dirtyFields = $order->getDirty();
        $originalFields = $order->getOriginal();
        
        foreach($dirtyFields as $dirtyField => $dirtyValue) {
            $message = null;
            switch($dirtyField) {
                case 'notes':
                    $message = "Updated Order Notes";
                    break;
                case 'ticket_notes':
                    $message = "Updated Manufacturing Notes";
                    break;
                case 'invoice_notes':
                    $message = "Updated Invoice Notes";
                    break;
                case 'sidemark':
                    $message = "Updated Sidemark from '{$originalFields[$dirtyField]}' to '$dirtyValue'";
                    break;
                case 'date_received':
                    $message = "Updated Date Received from '{$originalFields[$dirtyField]}' to '$dirtyValue'";
                    break;
                case 'date_due':
                    $message = "Updated Due Date from '{$originalFields[$dirtyField]}' to '$dirtyValue'";
                    break;
                case 'date_shipped':
                    $message = "Updated Shipping Date from '{$originalFields[$dirtyField]}' to '$dirtyValue'";
                    break;
                case 'purchase_order':
                    $message = "Updated Purchase Order Number from '{$originalFields[$dirtyField]}' to '$dirtyValue'";
                    break;
                case 'deposit_amount':
                    $message = "Updated Deposit Amount from '{$originalFields[$dirtyField]}' to '$dirtyValue'";
                    break;
                case 'customer_type_id':
                    $oldCustomerType = \CustomerType::find($originalFields[$dirtyField]);
                    $newCustomerType = \CustomerType::find($dirtyValue);
                    $message = "Updated customer type from '{$oldCustomerType->name}' to '{$newCustomerType->name}'";
                    break;
                case 'shipping_method_id':

                    $oldShippingMethod = \ShippingMethod::find($originalFields[$dirtyField]);
                    $hasOldShippingMethod = $oldShippingMethod instanceof \ShippingMethod;
                    $newShippingMethod = \ShippingMethod::find($dirtyValue);
                    $hasNewShippingMethod = $newShippingMethod instanceof \ShippingMethod;
                    if($hasOldShippingMethod && $hasNewShippingMethod){  
                        $message = "Updated shipping method from '{$oldShippingMethod->name}' to '{$newShippingMethod->name}'";
                    } else {
                        $message = "Updated shipping method.";
                    }
                    break;
                case 'company_id':
                    $oldCompany = \Company::find($originalFields[$dirtyField]);
                    $newCompany = \Company::find($dirtyValue);
                    $message = "Updated Company from '{$oldCompany->name}' to '{$newCompany->name}'";
                    break;
                case 'shipping_address_id':
                    continue; // @todo Remove this once we stop creating a new address every save
                    $message = "Shipping Address Updated";
                    break;
                case 'billing_address_id':
                    continue; // @todo Fix this once we stop creating a new address every save
                    $message = "Billing Address Updated";
                    break;
                case 'credit_terms':
                    $message = "Credit Terms Updated from '{$originalFields[$dirtyField]}' to '{$dirtyValue}'";
                    break;
                case 'phone_number':
                    $message = "Phone Number updated from '{$originalFields[$dirtyField]}' to '{$dirtyValue}'";
                    break;
                case 'fax_number':
                    $message = "Fax number updated from '{$originalFields[$dirtyField]}' to '{$dirtyValue}'";
                    break;
                case 'deposit_check_no':
                    $message = "Deposit Check No. Update from '{$originalFields[$dirtyField]}' to '{$dirtyValue}'";
                    break;
                case 'discount_percent':
                    $message = "Discount updated from '{$originalFields[$dirtyField]}%' to '{$dirtyValue}%'";
                    break;
                case 'rush_percent':
                    $message = "Rush Percent updated from '{$originalFields[$dirtyField]}%' to '{$dirtyValue}%'";
                    break;
                case 'boxing_cost':
                    $message = "Boxing cost updated from '{$originalFields[$dirtyField]}' to '{$dirtyValue}'";
                    break;
                case 'shipping_amount':
                    $message = "Shipping Amount updated from '{$originalFields[$dirtyField]}' to '{$dirtyValue}'";
                    break;
            }
            
            if(!is_null($message)) {
                Transaction::recordEntry($user, $order, Events::ORDER_UPDATED, $message, [
                    'original' => $originalFields[$dirtyField],
                    'new' => $dirtyValue
                ]);
            }
        }
    }
}