<?php

namespace Order;

class Transaction extends \Eloquent
{
    protected $table = 'order_transaction_logs';
    
    static public function recordEntry(\User $user, \Order $order, $event, $message, $data = [])
    {
        $record = new static();
        $record->user_id = $user->id;
        $record->order_id = $order->id;
        $record->message = $message;
        $record->data = serialize($data);
        $record->event = $event;
        
        $record->save();
        
        return $record;
    }
    
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function order()
    {
        return $this->belongsTo('Order', 'order_id');
    }
    
    public function getUserFullNameAttribute()
    {
        return $this->user->full_name;
    }
    
}