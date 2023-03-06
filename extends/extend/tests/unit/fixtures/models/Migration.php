<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\fixtures\models;

use yii\db\ActiveRecord;

/**
 * Class Migration
 *
 * @property string $version
 * @property int $apply_time
 *
 * @package tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Migration extends ActiveRecord
{
    /**
     * @return string
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%migration}}';
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['version', 'apply_time'], 'required'],
            [['apply_time'], 'integer', 'min' => 0],
        ];
    }
}
