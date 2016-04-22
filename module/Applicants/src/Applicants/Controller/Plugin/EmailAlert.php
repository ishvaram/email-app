<?php 

/**
 * CommonData Helper for application module. * 
 */

namespace Applicants\Controller\Plugin; 

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
// email library
use Zend\Mail;
use Zend\Mime;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use PHPMailer;

class EmailAlert extends AbstractPlugin
{

    protected $tableModel;
    protected $emailConfig;
    protected $emailData;

    function __construct($table)
    {
        $this->tableModel = $table;
    }


    /**
    * Mail with embed image
    */

    public function sendEmailImage()
    {
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'leadgen.alerts@hotchalk.com';                 // SMTP username
        $mail->Password = 'Hotchalk!23';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->From = 'leadgen.alerts@hotchalk.com';
        $mail->FromName = 'HOTCHALK';
        $mail->addAddress('saranya@svapas.com', 'Saranya');     // Add a recipient
        

        $mail->isHTML(true);                                  // Set email format to HTML

        $html = 'This is the HTML message body <b>in bold!</b><br/>';
        $html .= '<img src="/var/www/FMS-PHP/public/img/hotchalkcolored.jpg" />';
        
        
        $mail->Subject = 'Testing mail with embed image';        
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';        
        $mail->MsgHTML($html);
        
        if(!$mail->send()) {
            return false;
        } else {
            return true;
        }

    }

    public function sendEmailWithImage($username,$password,$toEmail,$toName,$fromEmail,$fromName,$subject,$body,$img,$add_email)
    {
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $username;                 // SMTP username
        $mail->Password = $password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->From = $fromEmail;
        $mail->FromName = $fromName;
        
        if($add_email!=''){
            $mail->addAddress($add_email, $toName);
            $mail->addCC($toEmail, $toName);
        } else {
            $mail->addAddress($toEmail, $toName);     // Add a recipient
        }    


        $mail->isHTML(true);                                  // Set email format to HTML

        $html = "<img src='$img' />";
        $html .= $body;


        $mail->Subject = $subject;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->MsgHTML($html);

        if(!$mail->send()) {
            return false;
        } else {
            return true;
        }

    }


    public function setupEmail($partnerID){
//        $partnerID = 3;
        $this->emailConfig = $this->tableModel->getEmailConfig($partnerID);

        if(count($this->emailConfig) == 0){
            return false;
        }

        return true;
    }

    public function setupEmailData($emailData){
        $this->emailData = $emailData;
    }

    private function getImageURL($type){
        $banner = $_SERVER['DOCUMENT_ROOT'].'/'.$this->emailConfig['templates'][$type]['banner'];
        return $banner;
    }

    private function loadTemplate($type){
        $template = $this->emailConfig['templates'][$type]['body'];

        $subject = $this->emailConfig['templates'][$type]['subject'];

        $data = $this->emailData;
        //url trivial

//        $imageURL = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->emailConfig['templates'][$type]['banner'];

        $template = str_replace('[Candidate]',$data['candidate'],$template);
        $template = str_replace('[Course Number]',$data['crn'],$template);
        $template = str_replace('[Course Name]',$data['courseName'],$template);
        $template = str_replace('[Start Date]',$data['startDate'],$template);
        $template = str_replace('[End Date]',$data['endDate'],$template);
        $template = str_replace('[Code]',$data['code'],$template);
        $template = str_replace('[URL]',$this->emailConfig['templates'][$type]['url'],$template);
		$template = str_replace('[Acceptlink]',$data['acceptlink'],$template);
        $template = str_replace('[Declinelink]',$data['declinelink'],$template);
//        $template = str_replace('[IMG]',$imageURL,$template);
        //TODO: Add [Salary]

//        var_dump($_SERVER['HTTP_HOST']);
//echo $template;
//var_dump($template);
//        exit();
        $return = array(
            'body' => $template,
            'subject' => $subject,
        );

        return $return;
    }

    private function getSubject($type){
        return $this->emailConfig['templates'][$type]['subject'];
    }

    private function getFrom($type){
        return $this->emailConfig['templates'][$type]['addressFrom'];
    }
    
    public function sendBulkEmail($to_email,$to_name,$email_template) {
        $to_email = $to_email;
        $subject = 'Maintanence Schedule';        
        $body_content = $email_template;
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'leadgen.alerts@hotchalk.com';         // SMTP username
        $mail->Password = 'Hotchalk!23';                      // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->From = 'noreply@veeva.com';
        $mail->FromName = 'Veeva Email App';
        $mail->addAddress($to_email, $to_name);               // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML            
        $mail->Subject = $subject;            
        $mail->MsgHTML($body_content);          
        if(!$mail->send()) {
            echo $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    }
    
    public function getBodyContent($content, $to_name, $from_status, $to_status){
        $template = $content;
        $template = str_replace('{user}',$to_name,$template);
        $template = str_replace('{previous_status}',$from_status,$template);
        $template = str_replace('{current_status}',$to_status,$template);
        return $template;
    }
    
}	
?>
