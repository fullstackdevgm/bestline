<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use \Zizaco\Entrust\HasRole; //read more at https://github.com/Zizaco/entrust/tree/1.0
    
	protected $table = 'users';
	protected $hidden = array('password');

	public function station(){
		return $this->belongsTo('Station');
	}

	public function openOrderStations(){
		return $this->hasMany('Order\LineItem\Work')->whereNull('complete_time');
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	public function getFullNameAttribute()
	{
	    return "{$this->first_name} {$this->last_name}";
	}
}
