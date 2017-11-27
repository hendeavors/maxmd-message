<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\Support\VO\ModernArray;
use Endeavors\Support\VO\ModernString;

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

        if( ! $this->fhirResourceType->hasKey('resource') ) {
            throw new Exceptions\InvalidResourceException("Missing parameter: resource");
        }

        $this->resourceType = ModernString::create($this->fhirResourceType->get()['resource']);

        if( $this->resourceType->isEmpty() ) {
            throw new Exceptions\InvalidResourceException("The resource cannot be blank");
        }

        if( $this->fhirResourceType->hasKey('parameters') ) {
            $this->queryParameters = ModernArray::create($this->fhirResourceType->get()['parameters']);
        }
    }

    public static function create($value)
    {
        return new static($value);
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
        $result = [
            'resourceType' => $this->resource()->get()
        ];

        if( null !== $this->queryParameters ) {
            $result['queryParameters'] = $this->parameters()->get();
        }

        return $result;
    }
}