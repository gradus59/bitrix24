<?php
namespace GraDus59\Bitrix24\Crm;

use Bitrix\Crm\Service\Container;
use Bitrix\Crm\Service\Factory;
use Bitrix\Main\Loader;
use Bitrix\Crm\Search;
use GraDus59\Bitrix24\Crm\Src\Smart\Result;

class Smart
{
    private Container $container;
    private Factory $factory;
    private int $id;

    /**
     * @author < GraDus59 > Перебиковский Ярослав
     */
    private function __construct()
    {
        Loader::includeModule('crm');
        $this->container = Container::getInstance();
    }

    public static function getObject()
    {
        return new self();
    }

    public function classById(int $id): Smart
    {
        $this->id = $id;
        $type = $this->container->getType($id);
        $this->setFactoryByType($type);
        return $this;
    }

    public function classByEntityTypeId(string $entityTypeId): Smart
    {
        $id = $this->getTypeBy("ENTITY_TYPE_ID",$entityTypeId)["ID"];
        $this->id = $id;
        $type = $this->container->getTypeByEntityTypeId($entityTypeId);
        $this->setFactoryByType($type);
        return $this;
    }

    public function classByCode(string $code): Smart
    {
        $id = $this->getTypeBy("CODE",$code)["ID"];
        $this->id = $id;
        return $this->classById($id);
    }

    public function getById(int $id)
    {
        return $this->getBy("ID",$id);
    }

    public function getBy(string $field, $value)
    {
        $parameters['filter'] = [$field => $value];
        $parameters['select'] = ["*"];
        $parameters['order'] = ["ID"];
        $parameters['limit'] = 1;
        $result = $this->getList($parameters);

        return !$result ? $result : $result->fetch();
    }

    public function getList(array $parameters)
    {
        $items = $this->getFactory()->getItems($parameters);

        $resultObject = Result::getInstance();
        $resultObject->setResult($items);
        return $resultObject;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCount(array $filter = []): int
    {
        return $this->getFactory()->getItemsCount($filter);
    }

    public function add(array $data)
    {
        $item = $this->getFactory()->createItem();
        $item->setFromCompatibleData($data);

        $result = $item->save();
        return $result->isSuccess() ? $result->getId() : $result->getErrorMessages();
    }

    public function update(int $id, array $data)
    {
        $item = $this->getFactory()->getItem($id);
        if(!$item)
            return false;

        foreach ($data as $key => $field)
            $item->set($key, $field);

        $result = $item->save();
        return $result->isSuccess() ?: $result->getErrorMessages();
    }

    public function delete(int $id)
    {
        $this->getFactory()->getItem($id)->delete();
    }

    public function getEntityId(): string
    {
        return \CCrmOwnerType::DynamicTypePrefixName . $this->id;
    }

    public function getFieldsInfo(): array
    {
        return $this->getFactory()->getFieldsInfo();
    }

    public function getFactory(): Factory
    {
        return $this->factory;
    }

    public function createIndex(int $id)
    {
        $search = new Search\DynamicTypeSearchContentBuilder($this->factory->getEntityTypeId());
        $search->build($id);
    }

    public function getTypeList(array $parameters): \Bitrix\Main\ORM\Query\Result
    {
        $typeDataClass = $this->container->getDynamicTypeDataClass();
        return $typeDataClass::getList($parameters);
    }

    private function setFactoryByType($type): void
    {
        $this->factory = $this->container->getDynamicFactoryByType($type);
    }

    private function getTypeBy(string $field, string $value)
    {
        $resType = $this->getTypeList([
            "filter" => [
                $field => $value
            ],
            "select" => ["ID","CODE","ENTITY_TYPE_ID"]
        ]);

        return $resType->fetch();
    }
}