<?php

namespace App\Mail;

use Google\Client as GoogleClient;
use Google\Service\Gmail;
use Google\Service\Gmail\Message as GmailMessage;
use League\OAuth2\Client\Provider\Google as GoogleOAuthProvider;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use RuntimeException;
use Throwable;

class GMailer
{
    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function getGoogleToken() {
        $params = [
            'clientId' => config('gmailer.client_id'),
            'clientSecret' => config('gmailer.client_secret'),
            'redirectUri' => config('gmailer.redirect_url'),
            'accessType' => config('gmail.access_type')
        ];

        $provider = new GoogleOAuthProvider($params);
        $options = [
            'scope' => [
                'https://mail.google.com/'
            ]
        ];

        if (!isset($_GET['code'])) {
            $authUrl = $provider->getAuthorizationUrl($options);
            header('Location: ' . $authUrl);
            exit;
        }

        $token = $provider->getAccessToken(
            'authorization_code',
            [
                'code' => $_GET['code']
            ]
        );

        return $token->getRefreshToken();
    }

    public function send() {

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';

        $clientId = config('gmailer.client_id');
        $clientSecret = config('gmailer.client_secret');
        $refreshToken = config('gmailer.refresh_token');

        $provider = new GoogleOAuthProvider([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
        ]);

        $mail->setOAuth(new OAuth([
            'provider' => $provider,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'refreshToken' => $refreshToken,
            'userName' => config('gmailer.mail_from'),
        ]));

        $isArray = false;
        if (isset($this->event['filename'])) {
            // $attachments is an array with file paths of attachments
            if (is_array($this->event['filename']) && count($this->event['filename'])>0) {
                $isArray = true;
            }

            if ($isArray) {
                foreach($this->event['filename'] as $filePath){
                    $mail->addAttachment(public_path().'/uploads/'.$filePath);
                }
            } else {
                $mail->addAttachment(public_path().'/uploads/'.$this->event['filename']);
            }
        }

        // Recipients
        if (isset($this->event['from']))
            $from = $this->event['from'];
        else $from = 'Berdvaye';

        $mail->setFrom(config('gmailer.mail_from'), $from);
        $mail->addReplyTo(config('gmailer.mail_from'), $from);

        if (isset($this->event['fullname']))
            $fullname=$this->event['fullname'];
        else $fullname = $this->event['to'];

        $mail->addAddress($this->event['to'], $fullname);

        $mail->Subject = $this->event['subject'];
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        $html = view($this->event['template'], $this->event)->render();
        $mail->msgHTML($html);
        //$mail->AltBody = 'This is a plain-text message body';

        // SMTP delivery remains the primary operation. Gmail normally adds these
        // submissions to Sent automatically.
        $mail->send();

        // Some alias/account configurations do not show SMTP submissions in Sent.
        // Filing a copy is deliberately best-effort and must never fail delivery.
        try {
            $this->saveToSentIfMissing($mail);
        } catch (Throwable $exception) {
            logger()->warning('Email delivered, but its Sent-folder copy could not be verified.', [
                'message_id' => $mail->getLastMessageID(),
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    private function saveToSentIfMissing(PHPMailer $mail): void
    {
        $gmail = new Gmail($this->googleClient());
        $messageId = trim($mail->getLastMessageID(), '<>');

        if ($messageId !== '') {
            $existing = $gmail->users_messages->listUsersMessages('me', [
                'q' => 'in:sent rfc822msgid:' . $messageId,
                'maxResults' => 1,
            ]);

            if (count($existing->getMessages() ?? []) > 0) {
                return;
            }
        }

        $message = new GmailMessage();
        $message->setRaw($this->base64UrlEncode($mail->getSentMIMEMessage()));
        $message->setLabelIds(['SENT']);

        $gmail->users_messages->insert('me', $message, [
            'internalDateSource' => 'dateHeader',
        ]);
    }

    private function googleClient(): GoogleClient
    {
        $client = new GoogleClient();
        $client->setClientId(config('gmailer.client_id'));
        $client->setClientSecret(config('gmailer.client_secret'));

        $accessToken = $client->fetchAccessTokenWithRefreshToken(
            config('gmailer.refresh_token')
        );

        if (isset($accessToken['error'])) {
            throw new RuntimeException(
                'Unable to refresh the Gmail access token: '
                . ($accessToken['error_description'] ?? $accessToken['error'])
            );
        }

        $client->setAccessToken($accessToken);

        return $client;
    }

    private function base64UrlEncode(string $message): string
    {
        return rtrim(strtr(base64_encode($message), '+/', '-_'), '=');
    }
}
