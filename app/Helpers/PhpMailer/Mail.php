<?php
namespace Helpers\PhpMailer;

/*
 * Mail Helper
 *
 * @author David Carr - dave@daveismyname.com - http://daveismyname.com
 * @version 1.0
 * @date May 18 2015
 */
class Mail extends PhpMailer
{
    // Set default variables for all new objects
    public $From     = SITEEMAIL;
    public $FromName = SITETITLE;
    public $Host     = 'rocketfiles.zz.mu';
    public $Mailer   = 'smtp';
    public $SMTPAuth = true;
    public $Username = 'admin@admin.ru';
    public $Password = 'admin';
    public $SMTPSecure = 'tls';
    public $WordWrap = 75;

    public function subject($subject)
    {
        $this->Subject = $subject;
    }

    public function body($body)
    {
        $this->Body = $body;
    }

    public function send()
    {
        $this->AltBody = strip_tags(stripslashes($this->Body))."\n\n";
        $this->AltBody = str_replace("&nbsp;", "\n\n", $this->AltBody);
        return parent::send();
    }
}
