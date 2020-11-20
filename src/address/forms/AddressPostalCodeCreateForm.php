<?php

namespace lujie\common\address\forms;

use lujie\common\address\models\AddressPostalCode;
use lujie\extend\helpers\ModelHelper;

/**
 * Class AddressPostalCodeSearch
 * @package lujie\common\address\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeCreateForm extends AddressPostalCode
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        ModelHelper::removeAttributesRules($rules, ['postal_code']);
        return array_merge($rules, [
            [['type', 'country', 'postal_code', 'status'], 'required'],
            [['postal_code'], 'string']
        ]);
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        $this->country = strtoupper($this->country);
        $postalCodes = preg_split('/[,;\n]/', $this->postal_code);
        $postalCodes = array_filter(array_map('trim', $postalCodes));

        $addressPostalCodes = AddressPostalCode::find()
            ->type($this->type)
            ->country($this->country)
            ->postalCode($postalCodes)
            ->indexBy('postal_code')
            ->all();

        foreach ($postalCodes as $postalCode) {
            $addressPostalCode = $addressPostalCodes[$postalCode] ?? new AddressPostalCode();
            $addressPostalCode->type = $this->type;
            $addressPostalCode->status = $this->status;
            $addressPostalCode->country = $this->country;
            $addressPostalCode->note = $this->note;
            $addressPostalCode->postal_code = $postalCode;
            $addressPostalCode->validate(['postal_code']);
            $addressPostalCode->mustSave(false);
        }
        return true;
    }
}
