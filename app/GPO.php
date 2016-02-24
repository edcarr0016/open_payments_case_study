<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplemental\Traits\CaseStudy;

class GPO extends Model
{
    use CaseStudy;

    protected $table = 'gpo';

    protected $guarded = [];

    protected $propertyMap = [
        'applicable_manufacturer_or_applicable_gpo_making_payment_id'           => 'given_id',
        'applicable_manufacturer_or_applicable_gpo_making_payment_name'         => 'name',
        'applicable_manufacturer_or_applicable_gpo_making_payment_state'        => 'state',
        'applicable_manufacturer_or_applicable_gpo_making_payment_country'      => 'country',

    ];

    protected $dates = [
        'created_at'
    ];
}
