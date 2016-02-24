<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplemental\Traits\CaseStudy;

class Physician extends Model
{
    //
    use CaseStudy;

    protected $guarded = []; // Less code to set Guarded as opposed to Fillable to avoid getting MassAssignmentException

    protected $propertyMap = [
        'physician_profile_id'          => 'id',
        'physician_first_name'          => 'first_name',
        'physician_last_name'           => 'last_name',
        'physician_middle_name'         => 'middle_name',
        'physician_ownership_indicator' => 'ownership_indicator',
        'physician_primary_type'        => 'primary_type',
        'physician_license_state_code1' => 'license_state_code1',
        'physician_specialty'           => 'specialty'

    ];

    protected $casts = [
      'ownership_indicator' => 'boolean'
    ];

    protected $dates = [
      'created_at'
    ];

    public function setFirstNameAttribute($first){
        $this->attributes['first_name'] = ucfirst(strtolower($first));
    }

    public function setMiddleNameAttribute($middle){
        $this->attributes['middle_name'] = ucfirst(strtolower($middle));
    }

    public function setLastNameAttribute($last){
        $this->attributes['last_name'] = ucfirst(strtolower($last));
    }

    public function setOwnershipIndicatorAttribute($ownership){
        $this->attributes['ownership_indicator'] = strcasecmp($ownership, "No") == 0 ? false: true;
    }

    public function getIdAttribute(){
        return $this->attributes['id'].'';
    }
}
