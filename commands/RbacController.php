<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\rbac\UserGroupRule;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RbacController extends Controller
{

    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;

        $authManager->removeAll();

        // Create roles
        $user  = $authManager->createRole('user');
        $admin  = $authManager->createRole('admin');

        // Create simple, based on action{$NAME} permissions
        $login  = $authManager->createPermission('login');


        // Add permissions in Yii::$app->authManager
        $authManager->add($login);



        // Add rule, based on UserExt->group === $user->group
        $userGroupRule = new UserGroupRule();
        $authManager->add($userGroupRule);

        // Add rule "UserGroupRule" in roles
        $user->ruleName  = $userGroupRule->name;
        $admin->ruleName  = $userGroupRule->name;

        // Add roles in Yii::$app->authManager
        $authManager->add($user);
        $authManager->add($admin);


        // Add permission-per-role in Yii::$app->authManager
        // Guest
        $authManager->addChild($user, $login);
    }
}
