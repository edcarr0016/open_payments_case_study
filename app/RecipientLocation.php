<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplemental\Traits\CaseStudy;

class RecipientLocation extends Model
{
    //
    use CaseStudy;

    protected $guarded = []; // Less code to set Guarded as opposed to Fillable to avoid getting MassAssignmentException

    protected $propertyMap = [
        'recipient_primary_business_street_address_line1'   => 'address_line1',
        'recipient_primary_business_street_address_line2'   => 'address_line2',
        'recipient_city'        => 'city',
        'recipient_state'       => 'state',
        'recipient_country'     => 'country',
        'recipient_zip_code'    => 'zip_code'
    ];

    protected $dates = [
        'created_at'
    ];

    public function getIdAttribute(){
        return $this->attributes['id'].'';
    }
}
