<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

use App\ElasticSearch\Interfaces\Stretchable;
use App\ElasticSearch\Traits\ElasticSearchIndexer;
use App\Supplemental\Traits\CaseStudy;

class Payments extends Model implements Stretchable
{
    //
    use CaseStudy, ElasticSearchIndexer;

    protected $guarded = []; // Less code to set Guarded as opposed to Fillable to avoid getting MassAssignmentException

    protected $propertyMap = [
        'record_id'                                                 => 'id',
        'nature_of_payment_or_transfer_of_value'                    => 'payment_type',
        'form_of_payment_or_transfer_of_value'                      => 'payment_form',
        'third_party_payment_recipient_indicator'                   => 'payment_recipient_indicator',
        'number_of_payments_included_in_total_amount'               => 'payment_number',
        'total_amount_of_payment_usdollars'                         => 'payment_amount',
        'date_of_payment'                                           => 'payment_date',
        'program_year'                                              => 'program_year',
        'charity_indicator'                                         => 'charity_indicator',
        'dispute_status_for_publication'                            => 'publication_dispute_indicator',
        'delay_in_publication_indicator'                            => 'publication_delay_indicator',
        'payment_publication_date'                                  => 'publication_date',
        'name_of_associated_covered_device_or_medical_supply1'      => 'product_name',
        'submitting_applicable_manufacturer_or_applicable_gpo_name' => 'gpo_name',
        'covered_recipient_type'                                    => 'covered_recipient_type',
        'product_indicator'                                         => 'product_indicator',
        'contextual_information'                                    => 'contextual_information'
    ];

    protected $dates = [
        'payment_date',
        'publication_date',
        'created_at'
    ];

    protected $casts = [
        'publication_dispute_indicator' => 'boolean',
        'publication_delay_indicator'   => 'boolean',
        'charity_indicator'             => 'boolean'
    ];

    public function setPaymentDateAttribute($date){
        $this->attributes['payment_date'] = Carbon::createFromFormat('Y-m-d', substr($date, 0, strpos($date,'T')));
    }

    public function setPublicationDateAttribute($date){
        $this->attributes['publication_date'] = Carbon::createFromFormat('Y-m-d', substr($date, 0, strpos($date,'T')));
    }

    public function setPublicationDisputeIndicatorAttribute($indicator){
        $this->attributes['publication_dispute_indicator'] = strcasecmp($indicator, "No") == 0 ? false: true;
    }

    public function setPublicationDelayIndicatorAttribute($indicator){
        $this->attributes['publication_delay_indicator'] = strcasecmp($indicator, "No") == 0 ? false: true;
    }

    public function setCharityIndicatorAttribute($indicator){
        $this->attributes['charity_indicator'] = strcasecmp($indicator, "No") == 0 ? false: true;
    }

    public function getIdAttribute(){
        return $this->attributes['id'].'';
    }

    public function getPaymentTypeAttribute(){
        if(str_contains($this->attributes['payment_type'],'Compensation for services')){
            return 'Non-Consulting';
        }

        if(str_contains($this->attributes['payment_type'], 'Compensation for serving')){
            return 'Faculty or Speaking Fee';
        }

        if(str_contains($this->attributes['payment_type'], 'Space')){
            return 'Space Rental';
        }

        if(strlen($this->attributes['payment_type']) > 22){
            return substr($this->attributes['payment_type'], 0, 19).'...';
        }

        return $this->attributes['payment_type'];
    }

    public function physician(){
        return $this->belongsTo('App\Physician');
    }

    public function recipientLocation(){
        return $this->belongsTo('App\RecipientLocation');
    }

    public function gpo(){
        return $this->belongsTo('App\GPO');
    }

    public function getPaymentsByType(){
        return $this->newQueryWithoutScopes()
            ->select(['payment_type',DB::raw('SUM(payment_amount*payment_number) AS payment_amount')])
            ->whereNotIn('payment_type',['Royalty or License','Grant'])
            ->groupBy('payment_type')
            ->orderBy('payment_type')
            ->get();
    }

    public function getPaymentsByTypeAndMonth(){
        return $this->newQueryWithoutScopes()
            ->select([DB::raw('MONTH(payment_date) AS payment_month'), 'payment_type', DB::raw('SUM(payment_amount*payment_number) AS payment_amount')])
            ->whereNotIn('payment_type',['Royalty or License','Grant'])
            ->groupBy(['payment_type',DB::raw('MONTH(payment_date)')])
            ->orderBy('payment_type')
            ->get();
    }

    public function getIndexName()
    {
        return "case_study";
    }

    public function getTypeName()
    {
        return "open_payments";
    }

    public function getMappings()
    {
        $path = getenv('BASE_DIR').getenv("ELASTIC_OPEN_PAYMENTS_MAPPING_PATH");

        return json_decode(file_get_contents($path), true);
    }

    public function getSearchParams()
    {
        $path = getenv('BASE_DIR').getenv("ELASTIC_SEARCH_PATH");

        return json_decode(file_get_contents($path), true);
    }

    public function getData()
    {
        $data['id'] = $this->id.'';
        $data['payment_type'] = $this->payment_type;
        $data['payment_form'] = $this->payment_form;
        $data['payment_recipient_indicator'] = $this->payment_recipient_indicator;
        $data['payment_date'] = $this->payment_date->toDateString();
        $data['product_name'] = $this->product_name;
        $data['product_indicator'] = $this->product_indicator;

        if(!is_null($this->contextual_information)){
            $data['contextual'] = $this->contextual_information;
        }

        $data['gpo_name_orig'] = $this->gpo->name;
        $data['gpo_name'] = $this->gpo_name;

        $data['recipient_city'] = $this->recipientLocation->city;

        if($this->physician){
            $data['physician_first_name'] = $this->physician->first_name;
            $data['physician_last_name'] = $this->physician->last_name;
            $data['physician_type'] = $this->physician->primary_type;
            $data['physician_specialty'] = $this->physician->specialty;
        }

        return $data;
    }
}
