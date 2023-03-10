<?php

namespace lujie\ar\deleted\backup\behaviors\tests\unit;

use lujie\ar\deleted\backup\behaviors\BackupDeleteBehavior;
use lujie\ar\deleted\backup\models\DeletedData;

class BackupDeleteBehaviorTest extends \Codeception\Test\Unit
{
    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $deletedData = new DeletedData();
        $deletedData->setAttributes([
            'table_name' => 'xxx_table',
            'row_id' => 123,
            'row_data' => ['test_data' => 'test_data'],
        ]);
        $this->assertEquals(1, $deletedData->save(false));
        $rowId = $deletedData->id;

        $deletedData->attachBehavior('backupDelete', [
            'class' => BackupDeleteBehavior::class,
        ]);
        $this->assertEquals(1, $deletedData->delete());

        $exists = DeletedData::find()->andWhere(['table_name' => 'xxx_table', 'row_id' => 123])->exists();
        $this->assertFalse($exists);

        $backupDeletedData = DeletedData::findOne(['table_name' => DeletedData::tableName(), 'row_id' => $rowId]);
        $this->assertNotNull($backupDeletedData);

        $backupDeletedData->attachBehavior('backupDelete', [
            'class' => BackupDeleteBehavior::class,
        ]);
        $this->assertTrue($backupDeletedData->restoreModelData($rowId));
        $exists = DeletedData::find()->andWhere(['table_name' => 'xxx_table', 'row_id' => 123])->exists();
        $this->assertTrue($exists);
        $exists = DeletedData::find()->andWhere(['id' => $rowId])->exists();
        $this->assertTrue($exists);
    }
}
