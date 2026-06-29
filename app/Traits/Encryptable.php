<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    protected $encryptable = [];

    public function getAttributeValue($key)
    {
        if (in_array($key, $this->encryptable)) {
            $value = parent::getAttributeValue($key);
            return $value ? Crypt::decrypt($value) : null;
        }

        return parent::getAttributeValue($key);
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable) && !is_null($value)) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
}
