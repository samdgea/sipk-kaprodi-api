<?php

namespace app\jobs;

use Yii;

/**
 * Class SendRegistrationMailJob.
 */
class SendRegistrationMailJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $to_email;
    public $subject_title;
    public $body;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        return Yii::$app->mailer->compose()
                ->setFrom('no-reply@yai.ac.id')
                ->setTo($this->to_email)
                ->setSubject($this->subject_title)
                ->setTextBody($this->body)
                ->send();
    }
}
