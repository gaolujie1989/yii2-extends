<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\constants;

/**
 * 错误码设计
 * 错误代码说明, 20502
 * 2 - 错误级别,
 *      1:系统级错误 - 开发人员查看，改代码或者启动服务(比如服务不可用或其他，一般以Yii的异常类抛出),
 *      2:业务级错误(一般为数据错误，数据没填或者不匹配没关联，UserException抛出)
 * 05 - 错误模块
 * 02 - 错误编号
 *
 * Class ErrorCodeConst
 * @package lujie\extend\constants
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ErrorCodeConst
{
    public const SYSTEM_UNKNOWN_ERROR = 10000;
    public const USER_UNKNOWN_ERROR = 20000;
}