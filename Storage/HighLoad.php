<?php
namespace GraDus59\Bitrix24\Storage;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

class HighLoad
{
    private string $entityId;
    private $dataClass;

    /**
     * @author < GraDus59 > Перебиковский Ярослав
     */
    public function __construct()
    {
        Loader::includeModule("highloadblock");
    }

    public static function getObject(): HighLoad
    {
        return new self();
    }

    public function classById(int $id): HighLoad
    {
        $hb = HighloadBlockTable::getById($id)->fetch();
        $entity = HighloadBlockTable::compileEntity($hb);
        $this->entityId = HighloadBlockTable::compileEntityId($id);
        $this->dataClass = $entity->getDataClass();

        return $this;
    }

    public function classByName(string $name): HighLoad
    {
        return $this->classById($this->getIdByName($name));
    }

    public function getDataClass()
    {
        return $this->dataClass;
    }

    public function getEntityId(): string
    {
        return $this->entityId;
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

        return $result->Fetch();
    }

    public function getList(array $parameters)
    {
        return $this->getDataClass()::getList($parameters);
    }

    public function getListArray()
    {
        $resHb = HighloadBlockTable::getList([
            "select" => ["ID", "NAME"],
            "filter" => []
        ]);

        $hbList = [];

        while ($item = $resHb->Fetch()):
            $hbList[$item["ID"]] = $item["NAME"];
        endwhile;

        return $hbList;
    }

    public function add(array $data)
    {
        $result = $this->getDataClass()::add($data);
        return $result->isSuccess() ? $result->getId() : $result->getErrorMessages();
    }

    public function update(int $id, array $data)
    {
        $result = $this->getDataClass()::update($id, $data);
        return $result->isSuccess() ?: $result->getErrorMessages();
    }

    public function delete(int $id)
    {
        $result = $this->getDataClass()::Delete($id);
        return $result->isSuccess() ?: $result->getErrorMessages();
    }

    public function getIdByName(string $name)
    {
        $obHb = HighloadBlockTable::getList([
            "select" => ["ID"],
            "filter" => ['NAME' => $name]
        ]);

        $res = $obHb->fetch();
        return $res['ID'];
    }
}