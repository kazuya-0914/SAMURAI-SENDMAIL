<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Sendmail{
  public $name;
  public $email;
  public $message;
  public $submit;

  // セッションがあれば変数に格納
  public function __construct() {
    $this->name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $this->email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $this->message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
  }

  public function sendmail() {
    try {
      // ここからはMailtrapの内容です
      $phpmailer = new PHPMailer();
      $phpmailer->isSMTP();
      $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
      $phpmailer->SMTPAuth = true;
      $phpmailer->Port = 2525;
      $phpmailer->Username = '958f4683846a82';
      $phpmailer->Password = 'd1447e2aac7c0c';
      // ここまでMailtrapの内容です
    
      $phpmailer->setFrom($this->email, $this->name); // Fromに当たります
      $phpmailer->addAddress('test@test.com', 'テスト太郎'); // toに当たります
    
      $phpmailer->CharSet = 'UTF-8';
      $phpmailer->Subject = 'お問い合わせフォームからのメッセージ';
      $phpmailer->Body    = $this->message;
      $phpmailer->send();

      $msg = <<< EOM
      <h3>{$this->name}様、お問い合わせを承りました。</h3>
      <p>ありがとうございました。今後の参考にさせていただきます</p>
      EOM;
    } catch (Exception $e) {
      $msg = "メール送信に失敗しました -> {$e->getMessage()}"; // 変数名は何でも大丈夫です
    }

    // セッション変数を初期化
    $_SESSION = array();

    // セッションを終了
    session_destroy();

    return $msg;
  }
}
$sendmail = new Sendmail();