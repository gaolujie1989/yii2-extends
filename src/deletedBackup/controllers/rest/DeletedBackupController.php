<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\deleted\backup\controllers\rest;

use lujie\common\deleted\backup\models\DeletedBackup;
use lujie\common\deleted\backup\searches\DeletedBackupRestoreForm;
use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;

/**
 * Class DeletedBackupController
 * @package lujie\common\deleted\backup\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeletedBackupController extends ActiveController
{

    public $modelClass = DeletedBackup::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = array_intersect_key(parent::actions(), ['index']);
        return array_merge($actions, [
            'restore' => [
                'class' => MethodAction::class,
                'modelClass' => DeletedBackupRestoreForm::class,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'restore'
            ]
        ]);
    }
}
