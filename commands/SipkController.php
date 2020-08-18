<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

use app\models\User;
use yii\console\widgets\Table;
use yii\helpers\Console;

use app\models\Faculty;
use app\models\Major;

/**
 * Manage SIPK Environment.
 *
 * Manage SIPK Application Environment from Super Admin Account to RBAC
 *
 * @author Abdilah Sammi <ask@abdilah.id>
 */
class SipkController extends Controller
{
    private $permission = [
        'viewUserManagement',
        'createUserManagement',
        'updateUserManagement',
        'deleteUserManagement',
    ];

    private $role = [
        'super-admin',
        'staff',
    ];

    private $assign = [
        'super-admin' => [
            'viewUserManagement',
            'createUserManagement',
            'updateUserManagement',
            'deleteUserManagement',
        ],
        'staff' => [
            'viewUserManagement'
        ]
    ];

    private $faculties = [
        'Teknik' => [
            'Informatika S1'
        ]
    ];

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    /**
     * Initialize Application Core Data
     * @return int Exit code
     */
    public function actionInitialize()
    {
        foreach($this->faculties as $faculty => $majors) {
            $fc = new Faculty;
            $fc->name = $faculty;
            if($fc->save()) {
                foreach($majors as $major) {
                    $mj = new Major;
                    $mj->name = $major;
                    $mj->faculty_id = $fc->id;
                    $mj->save();
                }
            }
        }
        echo $this->ansiFormat("[Faculty & Major] Success!", Console::FG_GREEN);
        $this->initializeRoles();
        return ExitCode::OK;
    }

    private function initializeRoles()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $manageUserMan = $auth->createPermission('manageUserManagement');
        $manageUserMan->description = "User can access User Management Module";
        $auth->add($manageUserMan);

        $viewUserMan = $auth->createPermission('viewUserManagement');
        $viewUserMan->description = "User can view user detail on User Management Module";
        $auth->add($viewUserMan);

        $createUserMan = $auth->createPermission('createUserManagement');
        $createUserMan->description = "User can create new user on User Management Module";
        $auth->add($createUserMan);

        $updateUserMan = $auth->createPermission('updateUserManagement');
        $updateUserMan->description = "User can update user on User Management Module";
        $auth->add($updateUserMan);

        $deleteUserMan = $auth->createPermission('deleteUserManagement');
        $deleteUserMan->description = "User can delete user on User Management Module";
        $auth->add($deleteUserMan);

        $staffRole = $auth->createRole('staff');
        $auth->add($staffRole);
        $auth->addChild($staffRole, $manageUserMan);
        $auth->addChild($staffRole, $viewUserMan);

        $adminRole = $auth->createRole('super-admin');
        $auth->add($adminRole);
        $auth->addChild($adminRole, $createUserMan);
        $auth->addChild($adminRole, $updateUserMan);
        $auth->addChild($adminRole, $deleteUserMan);
        $auth->addChild($adminRole, $staffRole);

        echo "[Roles & Permission] Done!";
        // return ExitCode::OK;
    }

    /**
     * Create an Super Administrator Account
     * @param string $username Account username.
     * @param string $email Account email address.
     * @param string $password Account Password. If not set, will automatically generates.
     */
    public function actionCreateSuAccount($username, $email, $password = null)
    {
        $auth = Yii::$app->authManager;
        $_password = (!empty($password)) ? $password : Yii::$app->getSecurity()->generateRandomString();

        $user = new User();
        $user->first_name = "Super";
        $user->last_name = "Administrator";
        $user->user_name = $username;
        $user->email_address = $email;
        $user->password_hashed = Yii::$app->getSecurity()->generatePasswordHash($_password);
        $user->account_status = User::ACCOUNT_ACTIVE;
        if ($user->save()) {
            $auth->assign($auth->getRole('super-admin'), $user->id);
            echo "Account created successfully\n";
            echo Table::widget([
                'headers' => ['Details'],
                'rows' => [
                    ['First Name', 'Super'],
                    ['Last Name', 'Admin'],
                    ['Username', $username],
                    ['Email Address', $email],
                    ['Password', $_password],
                ]
            ]);
            return ExitCode::OK;
        } else {
            echo $this->ansiFormat("Failed to create Account, make sure username or email address is not registered before", Console::FG_RED);
            return ExitCode::DATAERR;
        }
    }
}
