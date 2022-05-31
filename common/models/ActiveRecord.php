<?php

namespace common\models;


use common\utils\DateUtils;
use yii\db\ActiveQuery;

class ActiveRecord extends \yii\db\ActiveRecord
{

    const status_draft = 'draft';
    const status_created = 'created';
    const status_active = 'active';
    const status_inactive = 'inactive';
    const status_deleted = 'deleted';
    const status_in_process = 'in_process';
    const status_completed = 'completed';

    public $unsetAttributes = [];


    static $statusLabels = [];

    static function getStatusLabelAuto($value, $statusField = 'status', $default = 'Khác')
    {
        if (isset(static::$statusLabels[$statusField][$value])) {
            return static::$statusLabels[$statusField][$value];
        }
        return $default;
    }

    static function bindObjectStatusLabel(&$row, $fields = ['status'])
    {
        foreach ($fields as $field) {
            if (isset($row[$field])) {
                $row[$field . '_label'] = static::getStatusLabelAuto($row[$field], $field);
            }
        }
        return $row;
    }

    static function bindArrayObjectStatusLabel(&$rows, $fields = ['status'])
    {
        foreach ($rows as $k => $row) {
            $rows[$k] = static::bindObjectStatusLabel($row, $fields);
        }
        return $rows;
    }

    public function attributes()
    {
        return array_diff(array_keys(static::getTableSchema()->columns), $this->unsetAttributes);
    }

    public function hasAttributeAndNotEmpty($name)
    {
        if ($this->hasAttribute($name)) {
            $attributeValue = $this->getAttribute($name);
            if (!empty($attributeValue)) {
                return true;
            }
        }
        return false;
    }

    function setDefaultCreateUpdateTime()
    {
        if ($this->hasAttribute('created_at')) {
            $createAt = $this->getAttribute('created_at');
            if (empty($createAt)) {
                $this->setAttribute('created_at', time());
            }
        }
        if ($this->hasAttribute('updated_at')) {
            $this->setAttribute('updated_at', time());
        }
    }

    public function beforeSave($insert)
    {
        $this->setDefaultCreateUpdateTime();
        return parent::beforeSave($insert);
    }


    static function bindDateRange(ActiveQuery &$activeQuery, $rangeBy = null, $range = null, $dateFormat = '%d/%m/%Y')
    {
        $range = explode(' ', $range);
        if (!empty($rangeBy) && count($range) == 2) {
            $activeQuery->andWhere([
                'AND',
                ['>=', $rangeBy, DateUtils::getUnixFromDateFormat($range[0], $dateFormat)],
                ['<=', $rangeBy, DateUtils::getUnixFromDateFormat($range[1], $dateFormat, false)],
            ]);
        }
    }

    function virtualDelete()
    {
        if ($this->hasAttribute('status')) {
            $this->setAttribute('status', static::status_deleted);
            $this->save(false);
        }
    }

}
