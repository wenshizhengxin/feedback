<?php
/**
 * 描述：
 * Created at 2021/5/17 16:27 by Temple Chan
 */

namespace wenshizhengxin\feedback\libs;


use epii\admin\center\config\Settings;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    public static function send($addresses, $title, $content, $text = '')
    {
        if ($addresses === '*') {
            $addresses = array_filter(explode(',', Settings::get(Constant::ADDONS . '.mail_send_addresses')));
        }
        $mail = new PHPMailer(true);

        //服务器配置
        $mail->CharSet = "UTF-8";                     //设定邮件编码
        $mail->SMTPDebug = 0;                        // 调试模式输出
        $mail->isSMTP();                             // 使用SMTP
        $mail->Host = 'smtp.qq.com';                // SMTP服务器
        $mail->SMTPAuth = true;                      // 允许 SMTP 认证
        $mail->Username = Settings::get(Constant::ADDONS . '.mail_account');                // SMTP 用户名  即邮箱的用户名
        $mail->Password = Settings::get(Constant::ADDONS . '.mail_verify_code');             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
        $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

        $mail->setFrom($mail->Username, '文始征信-反馈助手');  //发件人

        foreach ($addresses as $key => $value) { // 'xxx@qq.com' or 'xxx@qq.com'=>'wwoqu'
            if (is_int($key)) {
                $mail->addAddress($value);
            } else {
                $mail->addAddress($key, $value);
            }
        }

        //Content
        $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = $title;
        $mail->Body = $content;
        $mail->AltBody = $text;

        $mail->send();
    }

}