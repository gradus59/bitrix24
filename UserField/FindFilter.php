<?php

namespace GraDus59\Bitrix24\UserField;

class FindFilter
{
    public const FULL_STRING = 1;
    public const SUB_STRING = 2;
    public const WORD_STRING = 3;
    public const START_STRING = 4;

    private $search;
    private $field;

    public function __construct($search, $field)
    {
        $this->search = $search;
        $this->field = $field;
    }

    public function getFilterByType($typeSearch): array
    {
        $filter = [];

        if(is_array($typeSearch)):
            foreach ($typeSearch as $type):
                $resFilter = self::getSearchByType($type);
                if($resFilter)
                    $filter[] = $resFilter;
            endforeach;

            if(count($filter) != 0)
                $filter["LOGIC"] = "OR";
        endif;

        if(is_int($typeSearch)):
            $resFilter = self::getSearchByType($typeSearch);
            if($resFilter)
                $filter[] = $resFilter;
        endif;

        return $filter;
    }

    public function searchFullString(): array
    {
        return [$this->field => $this->search];
    }

    public function searchSubString(): array
    {
        return ["%=" . $this->field => "%" . $this->search . "%"];
    }

    public function searchWordString(): array
    {
        return [$this->field => "% " . $this->search . "%"];
    }

    public function searchStartString(): array
    {
        return [$this->field => $this->search . "%"];
    }

    private function getSearchByType($typeSearch)
    {
        switch ($typeSearch):
            case self::FULL_STRING:
                return $this->searchFullString();
            case self::SUB_STRING:
                return $this->searchSubString();
            case self::WORD_STRING:
                return $this->searchWordString();
            case self::START_STRING:
                return $this->searchStartString();
            default:
                return false;
        endswitch;
    }
}