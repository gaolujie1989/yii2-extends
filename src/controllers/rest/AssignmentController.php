<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

use lujie\auth\forms\AssignmentForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;

/**
 * Class AuthAssignmentController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AssignmentController extends BaseAuthController
{
    /**
     * @param $userId
     * @return array
     * @inheritdoc
     */
    public function actionIndex($userId): array
    {
        return $this->authManager->getAssignments($userId);
    }

    /**
     * @param $userId
     * @return array
     * @inheritdoc
     */
    public function actionIndexRoles($userId): array
    {
        return $this->authManager->getRolesByUser($userId);
    }

    /**
     * @param $userId
     * @return array
     * @inheritdoc
     */
    public function actionIndexPermissions($userId): array
    {
        return $this->authManager->getPermissionsByUser($userId);
    }

    /**
     * @param $userId
     * @return array
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @inheritdoc
     */
    public function actionAssign(): AssignmentForm
    {
        $assignmentForm = new AssignmentForm([
            'authManager' => $this->authManager
        ]);
        $assignmentForm->setScenario(AssignmentForm::SCENARIO_ASSIGN);
        $assignmentForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($assignmentForm->assign() === false && !$assignmentForm->getErrors()) {
            throw new ServerErrorHttpException('Failed to assign for unknown reason.');
        }
        return $assignmentForm;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @inheritdoc
     */
    public function actionRevoke(): AssignmentForm
    {
        $assignmentForm = new AssignmentForm([
            'authManager' => $this->authManager
        ]);
        $assignmentForm->setScenario(AssignmentForm::SCENARIO_REVOKE);
        $assignmentForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($assignmentForm->revoke() === false && !$assignmentForm->getErrors()) {
            throw new ServerErrorHttpException('Failed to revoke for unknown reason.');
        }
        return $assignmentForm;
    }

    /**
     * @param $userId
     * @inheritdoc
     */
    public function actionRevokeAll($userId): void
    {
        $this->authManager->revokeAll($userId);
    }
}
