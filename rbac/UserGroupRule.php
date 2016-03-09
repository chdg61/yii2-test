<?php
namespace app\rbac;


use yii\rbac\Item;
use yii\rbac\Rule;

class UserGroupRule extends Rule
{

    public $name = 'userGroup';

    /**
     * Executes the rule.
     *
     * @param string|integer $user   the user ID. This should be either an integer or a string representing
     *                               the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item           $item   the role or permission that this rule is associated with
     * @param array          $params parameters passed to [[ManagerInterface::checkAccess()]].
     *
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            $group = \Yii::$app->user->identity->group;
            if ($item->name === 'admin') {
                return $group === 1;
            } elseif ($item->name === 'user') {
                return $group === 2;
            }
        }
        return false;
    }
}
