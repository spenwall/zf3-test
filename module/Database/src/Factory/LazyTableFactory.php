<?php

namespace Database\Factory;

class LazyTableFactory extends AbstractLazyFactory implements AbstractFactoryInterface {

    protected $tableName;
    protected $sequencedField;

    public static function createTableGateway(\Zend\Db\Adapter\Adapter $dbAdapterObject, string $tableName = null, $prototypeObject = null) 
    {
        if (!is_null($prototypeObject)) {
            $resultSetPrototype = new ResultSet();
            if (!is_null($dbAdapterObject))
                $prototypeObject->setDbAdapter($dbAdapterObject);
                if (!is_null($tableName))
                    $prototypeObject->setDbTableName($tableName);
                    $resultSetPrototype->setArrayObjectPrototype($prototypeObject);
        }
        else
            $resultSetPrototype = null;
        return new TableGateway($tableName, $dbAdapterObject, null, $resultSetPrototype);
    }


    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) 
    {
        // Check if $requestedName is from Model and consists Table keywords
        if (!(
            stristr($name, str_replace("\\", "", $requestedName)) !== false // $name needs to be same as $requestedName without \
            && strstr($requestedName, '\Model\\') !== false  // $requestedName needs to consist \Model\
            && strstr($requestedName, 'Table') !== false // $requestedName needs to consist Table
        ) && !class_exists($requestedName))
            return false;


        // Check if correct table exists
        if (defined($requestedName . '::LAZYFACTORY_TABLENAME'))
            if (!is_null(constant($requestedName . '::LAZYFACTORY_TABLENAME')))
                $requestedNameData[1] = constant($requestedName . '::LAZYFACTORY_TABLENAME');
        elseif (!preg_match("/([a-zA-Z0-9]*)Table$/U", $requestedName, $requestedNameData))
            return false;
        $dbMetadata = \Zend\Db\Metadata\Source\Factory::createSourceFromAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));
        $dbTables = $dbMetadata->getTableNames();
        if (in_array(strtolower($requestedNameData[1]), $dbTables)) // simple lowercase of tablename
            $this->tableName = strtolower($requestedNameData[1]);
        else {
            $tableName = strtolower(preg_replace("/\B([A-Z])/", "_$1", $requestedNameData[1])); // prefixing all capitals with "_" and lowercasing
            if (in_array($tableName, $dbTables))
                $this->tableName = $tableName;
            else
                return false; // neither the lowercased or prefixed and lowercased table name has been found - aborting
        }

        // Checks if there is pkey constraint that should be used for SequenceFeature creation
        foreach($dbMetadata->getConstraints($this->tableName) as $tableConstraint)
            if (
                $tableConstraint->getName() == $this->tableName . "_pkey" // only for constranint specifically named
                && $tableConstraint->isPrimaryKey() // only for pkey constraint
                && sizeof($tableConstraint->getColumns()) < 2 // only for constraint set for one column
            ) {
                $columns = $tableConstraint->getColumns();
                if ($dbMetadata->getColumn($columns[0], $this->tableName)->getDataType() == 'integer') // checks if column is integer
                $this->sequencedField = $columns[0];
            }

        return true;
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) 
    {
        $tableGateway = $this->createTableGateway($serviceLocator->get('Zend\Db\Adapter\Adapter'), $this->tableName);
        if (isset($this->sequencedField))
            $tableGateway->getFeatureSet()->addFeature(new SequenceFeature($this->sequencedField, $this->tableName . "_" . $this->sequencedField . "_seq"));
        $tableGateway->initialize();

        $tableObject = new $requestedName($tableGateway);

        $this->addObjectProperties($tableObject, $serviceLocator);

        return $tableObject;
    }
}