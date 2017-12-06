<?php 

use Database\Factory;

abstract class AbstractLazyFactory {
    
    protected function getPropertiesConfig() {
        return array(
            'LAZYFACTORY_INCLUDE_LOG'               => function($serviceLocator) { return $serviceLocator->get('Log'); }, // strange, but using std 'Log' does not work correctly
            'LAZYFACTORY_INCLUDE_CONFIG'            => function($serviceLocator) { return $serviceLocator->get('Config'); }, // strange, but using std 'Log' does not work correctly
            'LAZYFACTORY_INCLUDE_SERVICELOCATOR'    => function($serviceLocator) { return $serviceLocator; },
            'LAZYFACTORY_INCLUDE_TRANSLATOR'        => 'translator'
        );
    }

    protected function addObjectProperties($object, ServiceLocatorInterface $serviceLocator) 
    {
        foreach($this->getPropertiesConfig() as $constant => $property) {
            if (defined(get_class($object) . '::' . $constant))
                if (!is_null(constant(get_class($object) . '::' . $constant))) {
                    $propertyName = constant(get_class($object) . '::' . $constant);
                    if (is_string($property)) // setting the property for string values (retrieves from serviceLocator)
                        $object->$propertyName = $serviceLocator->get($property);
                    if (is_callable($property)) // setting the property for callback values
                        $object->$propertyName = $property($serviceLocator);
                }
        }
    }

}
    