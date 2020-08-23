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
        'browse-user-management'        => 'User can access User Management Page',
        'read-user-management'          => 'User can see Users on Management Page',
        'create-user-Management'        => 'User can create new User on Management Page',
        'update-user-Management'        => 'User can update user on Management Page',
        'delete-user-Management'        => 'User can delete user on Management Page',

        'browse-major-management'       => 'User can access Major Management Page',
        'read-major-management'         => 'User can see all Major on Management Page',
        'create-major-management'       => 'User can create Major on Management Page',
        'update-major-management'       => 'User can update Major on Management Page',
        'delete-major-management'       => 'User can delete Major on Management Page',

        'browse-faculty-management'     => 'User can access Faculty on Management Page',
        'read-faculty-management'       => 'User can see all Faculty on Management Page',
        'create-faculty-management'     => 'User can create Faculty on Management Page',
        'update-faculty-management'     => 'User can update Faculty on Management Page',
        'delete-faculty-management'     => 'User can delete Faculty on Management Page'
    ];

    private $role = [
        'super-admin',
        'staff',
    ];

    private $assign = [
        'super-admin' => [
            'browse-user-management',
            'read-user-management',
            'create-user-Management',
            'update-user-Management',
            'delete-user-Management',
            'browse-major-management',
            'read-major-management',
            'create-major-management',
            'update-major-management',
            'delete-major-management',
            'browse-faculty-management',
            'read-faculty-management',
            'create-faculty-management',
            'update-faculty-management',
            'delete-faculty-management'   
        ],
        'staff' => [
            'browse-user-management',
            'browse-faculty-management',
            'browse-major-management'
        ]
    ];

    private $faculties = [
        'Teknik' => [
            'Informatika S1'
        ]
    ];

    /**
     * Initialize role 
     * @return int Exit code
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        foreach($this->permission as $permission => $desc) {
            $x = $auth->createPermission($permission);
            $x->description = $desc;
            $auth->add($x);
        }

        foreach($this->assign as $role => $perm) {
            $y = $auth->createRole($role);
            foreach($perm as $permission) {
                $perm = $auth->getPermission($permission);
                $auth->addChild($y, $perm);
            }
        }

        foreach($this->assign as $role => $permissions) {
            $role = $auth->createRole($role);
            $auth->add($role);
            foreach($permissions as $permission) {
                $auth->addChild($role, $auth->getPermission($permission));
            }
        }

        echo "Done!";
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

    public function actionGenerateRandomData()
    {
        $data = [
            'Teknik' => [
                'Informatika S1' => [
                    '2015' => 57,
                    '2016' => rand(20, 100),
                    '2017' => rand(20, 100),
                    '2018' => rand(20, 100),
                    '2019' => rand(20, 100),
                    '2020' => rand(20, 100),
                ]
            ]
        ];
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
