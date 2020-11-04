<?php

namespace App\Booking\Presenters;

trait UserPresenter {

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->surname}";
    }
}
