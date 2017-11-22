<?php

namespace Endeavors\MaxMD\Message;

/**
 * @todo we'll need to map resource to resourceType and parameters to queryParameters
 */
class FHIRResourceType
{
    protected $fhirResourceType;

    protected $resourceType;

    protected $queryParameters;

    private function __construct($value)
    {
        $this->fhirResourceType = ModernArray::create($value);

        if( $this->fhirResourceType->hasKey('resource') ) {
            $this->resourceType = ModernString::create($this->fhirResourceType->get(['resource']));
        }

        if( $this->fhirResourceType->hasKey('parameters') ) {
            $this->queryParameters = ModernArray::create($this->fhirResourceType->get(['parameters']));
        }
    }

    public function resource()
    {
        return $this->resourceType;
    }

    public function parameters()
    {
        return $this->queryParameters;
    }

    public function toArray()
    {
        return [
            'resourceType' => $this->resource(),
            'queryParameters' => $this->parameters()
        ];
    }
}