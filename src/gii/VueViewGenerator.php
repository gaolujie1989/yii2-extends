<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\gii;

use yii\base\BaseObject;
use yii\base\View;
use yii\base\ViewContextInterface;
use yii\di\Instance;

/**
 * Class VueViewGenerator
 * @package lujie\extend\gii
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class VueViewGenerator extends BaseObject implements ViewContextInterface
{
    public const COLUMN_TYPE_INDEX = 'INDEX';
    public const COLUMN_TYPE_SELECTION = 'SELECTION';
    public const COLUMN_TYPE_ACTION = 'ACTION';
    public const COLUMN_TYPE_OPTION = 'OPTION';
    public const COLUMN_TYPE_TIMESTAMP = 'TIMESTAMP';
    public const COLUMN_TYPE_TEXT = 'TEXT';

    public const INPUT_TYPE_TEXT = 'TEXT';
    public const INPUT_TYPE_TEXTAREA = 'TEXTAREA';
    public const INPUT_TYPE_DATE = 'DATE';
    public const INPUT_TYPE_DATETIME = 'DATETIME';
    public const INPUT_TYPE_DATE_RANGE = 'DATE_RANGE';
    public const INPUT_TYPE_SELECT = 'SELECT';
    public const INPUT_TYPE_CHECKBOX = 'CHECKBOX';
    public const INPUT_TYPE_RADIO = 'RADIO';
    public const INPUT_TYPE_UPLOAD = 'UPLOAD';

    public const BUTTON_SEARCH = 'SEARCH';
    public const BUTTON_CREATE = 'CREATE';
    public const BUTTON_UPLOAD = 'UPLOAD';
    public const BUTTON_DOWNLOAD = 'DOWNLOAD';
    public const BUTTON_BATCH_UPDATE = 'BATCH_UPDATE';

    /**
     *  [
     *      'text_field' => ['type' => self::INPUT_TYPE_TEXT],
     *      'date_range_field' => ['type' => self::INPUT_TYPE_DATE_RANGE],
     *      'select_field' => ['type' => self::INPUT_TYPE_SELECT, 'optionsName' => 'xxxOptions'],
     *      'select_multi_field' => ['type' => self::INPUT_TYPE_SELECT, 'optionsName' => 'xxxOptions', 'multiple' => true],
     *      'checkbox_field' => ['type' => self::INPUT_TYPE_CHECKBOX],
     *  ]
     * @var array
     */
    public $searchFields = [];

    /**
     * @var array
     */
    public $buttons = [];

    /**
     *  [
     *      'index' => ['type' => self::COLUMN_TYPE_INDEX, 'properties' => ['align' => 'center', 'width' => '50']],
     *      'selection' => ['type' => self::COLUMN_TYPE_SELECTION, 'properties' => ['align' => 'center', 'width' => '50']],
     *      'xxx_id' => ['type' => self::COLUMN_TYPE_TEXT, 'properties' => ['width' => '120']],
     *      'xxx_name' => ['type' => self::COLUMN_TYPE_TEXT],
     *      'xxx_status' => ['type' => self::COLUMN_TYPE_OPTION, 'optionsName' => 'xxxOptions'],
     *      'xxx_at' => ['type' => self::COLUMN_TYPE_TIMESTAMP],
     *      'action' => ['type' => self::COLUMN_TYPE_ACTION, 'properties' => ['align' => 'center', 'width' => '160']],
     *  ]
     * @var array
     */
    public $listFields = [];

    /**
     *  [
     *      'date_field' => ['type' => self::INPUT_TYPE_DATE],
     *      'datetime_field' => ['type' => self::INPUT_TYPE_DATETIME],
     *      'checkbox_field' => ['type' => self::INPUT_TYPE_CHECKBOX],
     *      'checkbox_multi_field' => ['type' => self::INPUT_TYPE_CHECKBOX, 'optionsName' => 'xxxOptions'],
     *      'radio_field' => ['type' => self::INPUT_TYPE_SELECT, 'optionsName' => 'xxxOptions'],
     *      'upload_field' => ['type' => self::INPUT_TYPE_UPLOAD],
     *  ]
     * @var array
     */
    public $formFields = [];

    /**
     * @var array
     */
    public $batchFormFields = [];

    /**
     * @var array
     */
    public $uploadFormFields = [];

    /**
     * @var View
     */
    public $view = [];

    /**
     * @var string
     */
    public $messageCategory = 'common';

    /**
     * @var string
     */
    public $templateDir = __DIR__ . '/templates/vue-elements';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->view = Instance::ensure($this->view, View::class);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getViewPath(): string
    {
        return $this->templateDir;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function generate(): string
    {
        $contents = [
            'filterInputContent' => [],
            'filterButtonContent' => [],
            'tableColumnContent' => [],
            'formItemContent' => [],
            'batchFormItemContent' => [],
            'uploadFormItemContent' => [],
        ];
        foreach ($this->searchFields as $field => $fieldConfig) {
            $params = ['field' => $field];
            $contents['filterInputContent'][] = $this->view->render('el-filter-input.vue.php',
                array_merge($fieldConfig, $params), $this);
        }
        foreach ($this->buttons as $buttonType) {
            $contents['filterButtonContent'][] = $this->view->render('el-filter-button.vue.php',
                ['type' => $buttonType], $this);
        }
        foreach ($this->listFields as $field => $fieldConfig) {
            $params = ['field' => $field];
            $contents['tableColumnContent'][] = $this->view->render('el-table-column.vue.php',
                array_merge($fieldConfig, $params), $this);
        }
        foreach ($this->formFields as $field => $fieldConfig) {
            $params = ['field' => $field];
            $contents['formItemContent'][] = $this->view->render('el-form-item.vue.php',
                array_merge($fieldConfig, $params), $this);
        }
        foreach ($this->batchFormFields as $field => $fieldConfig) {
            $params = ['field' => $field];
            $contents['batchFormItemContent'][] = $this->view->render('el-form-item.vue.php',
                array_merge($fieldConfig, $params), $this);
        }
        foreach ($this->uploadFormFields as $field => $fieldConfig) {
            $params = ['field' => $field];
            $contents['uploadFormItemContent'][] = $this->view->render('el-form-item.vue.php',
                array_merge($fieldConfig, $params), $this);
        }
        foreach ($contents as $key => $content) {
            $contents[$key] = implode("\n", $content);
        }

        return $this->view->render('index.vue.php', $contents, $this);
    }
}
