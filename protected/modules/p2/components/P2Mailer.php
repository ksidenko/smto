<?php
/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * ApplicationComponent P2Mailer is the email class for P2.
 *
 * This class requires that a controller is present
 * in Yii::app()->controller!!
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2Mailer.php 506 2010-03-24 00:32:15Z schmunk $
 * @package p2.components
 * @since 2.0
 */

class P2Mailer extends CApplicationComponent {

    /**
     * Sends verification email with info on how
     * to activate the account.
     *
     * @param mixed $user the new registered user
     */
    public function sendUserVerification($user) {
        $mail=new P2Mail;
        $mail->to=$user->eMail;
        $mail->from=Yii::app()->params['adminEmail'];

        $mail->body=$this->renderMail(
            'register',
            array(
            'mail'=>$mail,
            'user'=>$user,
            'verificationUrl' => $this->getController()->createAbsoluteUrl('/p2/p2User/verify',
            array(
            'token' => $user->createToken( Yii::app()->params['p2.activationPeriod']),
            )),),
            true);
        $mail->send();
    }

    /**
     * Sends email with info on how to update the password.
     *
     * @param mixed $user the user that should receive the email
     */
    public function sendUserForgotPassword($user) {
        $mail=new P2Mail;
        $mail->to=$user->eMail;
        $mail->from=Yii::app()->params['adminEmail'];

        $mail->body=$this->renderMail('forgotpassword',array(
            'mail'=>$mail,
            'user'=>$user,
            'resetPasswordUrl' => $this->getController()->createAbsoluteUrl('/p2/p2User/resetPassword',array(
            'token' => $user->createToken( Yii::app()->params['p2.activationPeriod']),
            )),
            ),true);
        $mail->send();
    }

    /**
     * Sends activation to inform user about activation of his account.
     *
     * @param mixed $user the activated user
     */
    public function sendUserActivation($user) {
        $mail=new P2Mail;
        $mail->to=$user->eMail;
        $mail->from=Yii::app()->params['adminEmail'];

        $mail->body=$this->renderMail('activate',array(
            'mail'=>$mail,
            'user'=>$user,
            ),true);
        $mail->send();
    }

    /**
     * Sends verification email to user's verifyEmail address.
     *
     * @param mixed $user the user to verify
     */
    public function sendUserEmailVerification($user) {
        $mail=new P2Mail;
        $mail->to=$user->verifyEmail;
        $mail->from=Yii::app()->params['adminEmail'];

        $mail->body=$this->renderMail('verifyemail',array(
            'mail'=>$mail,
            'verificationUrl' => $this->getController()->createAbsoluteUrl('/p2/p2User/verifyemail',array(
            'token' => $user->createToken( Yii::app()->params['p2.activationPeriod']),
            )),
            'user'=>$user,
        ));
        $mail->send();
    }

    /**
     * Sends a notification email to the admin.
     *
     * @param mixed $user the new user
     */
    public function sendAdminNotification($user) {
        $mail=new P2Mail;
        $mail->from=Yii::app()->params['adminEmail'];
        $mail->to=Yii::app()->params['adminEmail'];

        $mail->body=$this->renderMail('notify',array(
            'mail'=>$mail,
            'user'=>$user,
            'activationUrl' => Yii::app()->createAbsoluteUrl('/p2/p2User/show',array(
            'id' => $user->id,
            )),
            ),true);
        $mail->send();
    }

    public function sendUserMail($view, $data, $subject = null) {
        $controller=$this->getController();
        $mail=new P2Mail;
        if ($subject)
            $mail->setSubject($subject);
        else
            $mail->setSubject('Mail from '.Yii::app()->name);
        $mail->to=Yii::app()->user->getModel()->eMail;
        $mail->from=Yii::app()->params['adminEmail'];
        $mail->body=$controller->renderPartial($view,$data,true);
        $mail->send();
    }

    public function sendAdminMail($view, $data, $subject) {
        $controller=$this->getController();
        $mail=new P2Mail;
        if ($subject)
            $mail->setSubject($subject);
        else
            $mail->setSubject('Mail from '.Yii::app()->name);
        $mail->to=Yii::app()->params['adminEmail'];
        $mail->from=Yii::app()->params['adminEmail'];
        $mail->body=$controller->renderPartial($view,$data,true);
        $mail->send();
    }


    public function sendEMail($recipient, $view, $data, $subject = null) {
        $controller=$this->getController();
        $mail=new P2Mail;
        if ($subject)
            $mail->setSubject($subject);
        else
            $mail->setSubject('Mail from '.Yii::app()->name);
        $mail->to=$recipient;
        $mail->from=Yii::app()->params['adminEmail'];
        $mail->body=$controller->renderPartial($view,$data,true);
        $mail->send();
    }

    /**
     * Renders the email template given in $name.
     *
     * @param string $name of the email template
     * @param array $data to be rendered
     * @access public
     * @return string the email body
     */
    public function renderMail($name,$data=array()) {
        $c=$this->getController();

        // Yii doesn't handle absolute view names like '/email/notify' not
        // correctly if the view belongs to a module and a theme is used.
        // So we resolve the name manually for now.
        if(($theme=Yii::app()->getTheme())!==null && ($file=$theme->getViewFile($c,'/p2/email/'.$name))!==false)
            $viewFile=$file;
        else
            $viewFile=$c->getViewFile('/email/'.$name);

        return $c->renderFile($viewFile,$data,true);
    }

    /**
     * @return CController the currently active controller
     */
    public function getController() {
        if (($c=Yii::app()->getController())===null)
            throw new CException('No active controller available!');
        return $c;
    }


}

/**
 * Class file adapter for mailers
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2Mailer.php 506 2010-03-24 00:32:15Z schmunk $
 * @package p2.components
 * @since 2.0
 */
class P2Mail {
    public $from;
    public $to;
    public $body;

    private $_subject;

    /**
     * Sends this email
     */
    public function send() {
        // TODO: Allow usage of different mailers, use UTF-8
        $trans = array("€" => "EUR", "–" => "-"); // very basic fixes
        $this->body = strtr($this->body, $trans);
        $this->body = utf8_decode($this->body); // Outlook expects ISO encoding
        mail($this->to, $this->_subject, $this->body, 'From: '.$this->from."\r\n");
        Yii::log("Sending e-mail to <{$this->to}>; Subject: {$this->_subject}", CLogger::LEVEL_INFO, "p2.mailer");
    }

    public function setSubject($value) {
        $this->_subject=$value;
    }

    public function getSubject() {
        return $this->_subject;
    }
}
