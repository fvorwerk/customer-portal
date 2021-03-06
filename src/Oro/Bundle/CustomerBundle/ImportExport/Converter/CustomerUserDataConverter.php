<?php

namespace Oro\Bundle\CustomerBundle\ImportExport\Converter;

use Oro\Bundle\CustomerBundle\Entity\Customer;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\ImportExportBundle\Converter\ConfigurableTableDataConverter;
use Oro\Bundle\UserBundle\Entity\User;

class CustomerUserDataConverter extends ConfigurableTableDataConverter
{
    /**
     * {@inheritdoc}
     */
    public function getBackendHeader()
    {
        $result = parent::getBackendHeader();

        $result = $this->addCustomerName($result);
        $result = $this->addOwnerId($result);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityRulesAndBackendHeaders(
        $entityName,
        $fullData = false,
        $singleRelationDeepLevel = 0,
        $multipleRelationDeepLevel = 0
    ) {
        if (!$fullData) {
            $header = $this->getHeaderForRelatedClass($entityName);
            if ($header) {
                return $header;
            }
        }

        return parent::getEntityRulesAndBackendHeaders(
            $entityName,
            $fullData,
            $singleRelationDeepLevel,
            $multipleRelationDeepLevel
        );
    }

    /**
     * @param string $entityName
     * @return array
     */
    protected function getHeaderForRelatedClass($entityName)
    {
        switch ($entityName) {
            case User::class:
                return [
                    ['Id' => 'id'],
                    ['Id' => 'id']
                ];
            
            case Customer::class:
                // Since Customer is a relation here, getEntityRulesAndBackendHeaders will be called recursively for it
                return [
                    ['Id' => 'id', 'Name' => 'name'],
                    ['Id' => 'id', 'Name' => 'name'],
                ];
        }
        
        return null;
    }

    /**
     * @param array $result
     * @return array
     */
    private function addCustomerName(array $result)
    {
        if (!$this->fieldHelper->getConfigValue(CustomerUser::class, 'customer', 'excluded')) {
            $result[] = 'customer:name';
        }

        return $result;
    }

    /**
     * @param array $result
     * @return array
     */
    private function addOwnerId(array $result)
    {
        if (!$this->fieldHelper->getConfigValue(CustomerUser::class, 'owner', 'excluded')) {
            $result[] = 'owner:id';
        }

        return $result;
    }
}
