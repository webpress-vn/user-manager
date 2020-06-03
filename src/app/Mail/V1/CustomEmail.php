<?php

namespace VCComponent\Laravel\User\Mail\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use VCComponent\Laravel\User\Mail\Mailable;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $notifiable;
    public $content;
    public $subject;
    public $greeting;

    const FONT_FAMILY = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;';

    const STYLE = [
        /* Layout ------------------------------ */

        'body'                => 'margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;',
        'email-wrapper'       => 'width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;',

        /* Masthead ----------------------- */

        'email-masthead'      => 'padding: 25px 0; text-align: center;',
        'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',

        'email-body'          => 'width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;',
        'email-body_inner'    => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0;',
        'email-body_cell'     => 'padding: 35px;',

        'email-footer'        => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
        'email-footer_cell'   => 'color: #AEAEAE; padding: 35px; text-align: center;',

        /* Body ------------------------------ */

        'body_action'         => 'width: 100%; margin: 30px auto; padding: 0; text-align: center;',
        'body_sub'            => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2;',

        /* Type ------------------------------ */

        'anchor'              => 'color: #3869D4;',
        'header-1'            => 'margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;',
        'paragraph'           => 'margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;',
        'paragraph-sub'       => 'margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;',
        'paragraph-center'    => 'text-align: center;',

        /* Buttons ------------------------------ */

        'button'              => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',

        'button--green'       => 'background-color: #22BC66;',
        'button--red'         => 'background-color: #dc4d2f;',
        'button--blue'        => 'background-color: #3869D4;',
    ];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    public function subject($text)
    {
        $this->subject .= $text;
        return $this;
    }

    public function greeting($text)
    {
        $this->greeting .= $text;
        return $this;
    }

    public function line($content, $style = null)
    {
        if (!isset($style)) {
            $style = self::STYLE;
        }
        $this->content .= "<p style='{$style['paragraph']}'>{$content}</p>";
        return $this;
    }

    public function section($content)
    {
        $this->content .= "<div>{$content}</div>";
        return $this;
    }

    public function action($actionText, $actionUrl)
    {
        $style      = self::STYLE;
        $fontFamily = self::FONT_FAMILY;

        $this->content .= <<<EOT
<table style="{$style['body_action']}" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <a href="{$actionUrl}"
                style="{$fontFamily} {$style['button']} {$style['button--blue']}"
                class="button"
                target="_blank">
                {$actionText}
            </a>
        </td>
    </tr>
</table>
EOT;
        return $this;
    }
    public function actiondelete($actionText, $actionUrl)
    {
        $style      = self::STYLE;
        $fontFamily = self::FONT_FAMILY;

        $this->content .= <<<EOT
<table style="{$style['body_action']}" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <a href="{$actionUrl}"
                style="{$fontFamily} {$style['button']} {$style['button--red']}"
                class="button"
                target="_blank">
                {$actionText}
            </a>
        </td>
    </tr>
</table>
EOT;
        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user_component::emails.v1.custom', [
            'style'      => self::STYLE,
            'fontFamily' => self::FONT_FAMILY,
            'greeting'   => $this->greeting,
            'content'    => $this->content,
        ]);
    }
}
