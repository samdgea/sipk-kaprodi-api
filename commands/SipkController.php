<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

use app\models\User;
use yii\console\widgets\Table;
use yii\helpers\Console;

/**
 * Manage SIPK Environment.
 *
 * Manage SIPK Application Environment from Super Admin Account to RBAC
 *
 * @author Abdilah Sammi <ask@abdilah.id>
 */
class SipkController extends Controller
{
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
     * Create an Super Administrator Account
     * @param string $username Account username.
     * @param string $email Account email address.
     * @param string $password Account Password. If not set, will automatically generates.
     */
    public function actionCreateSuAccount($username, $email, $password = null)
    {
        $_password = (!empty($password)) ? $password : Yii::$app->getSecurity()->generateRandomString();

        $user = new User();
        $user->first_name = "Super";
        $user->last_name = "Administrator";
        $user->user_name = $username;
        $user->email_address = $email;
        $user->password_hashed = Yii::$app->getSecurity()->generatePasswordHash($_password);
        $user->account_status = User::ACCOUNT_ACTIVE;
        if ($user->save()) {
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
