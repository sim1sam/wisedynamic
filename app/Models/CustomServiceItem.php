<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomServiceItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'custom_service_request_id',
        'service_name',
        'amount',
        'platform',
        'post_link',
        'service_date',
        'duration_days',
        'domain_name',
        'duration_months',
        'description',
        'additional_data',
    ];
    
    protected $casts = [
        'additional_data' => 'array',
        'service_date' => 'date',
    ];
    
    /**
     * Get the custom service request that owns this item.
     */
    public function customServiceRequest()
    {
        return $this->belongsTo(CustomServiceRequest::class);
    }
    
    /**
     * Check if this is a marketing service item.
     */
    public function isMarketingService()
    {
        return $this->customServiceRequest->service_type === CustomServiceRequest::TYPE_MARKETING;
    }
    
    /**
     * Check if this is a web/app service item.
     */
    public function isWebAppService()
    {
        return $this->customServiceRequest->service_type === CustomServiceRequest::TYPE_WEB_APP;
    }
    
    /**
     * Get the formatted service details based on service type.
     */
    public function getServiceDetails()
    {
        if ($this->isMarketingService()) {
            return [
                'Platform' => $this->platform,
                'Post Link' => $this->post_link,
                'Service Date' => $this->service_date ? $this->service_date->format('M d, Y') : null,
            ];
        }
        
        if ($this->isWebAppService()) {
            return [
                'Domain Name' => $this->domain_name,
                'Duration' => $this->duration_months ? $this->duration_months . ' months' : null,
            ];
        }
        
        return [];
    }
    
    /**
     * Get the service type from the parent request.
     */
    public function getServiceType()
    {
        return $this->customServiceRequest->service_type;
    }
}
