<?php

namespace VCComponent\Laravel\User\Mail;

use Illuminate\Mail\Mailable as BaseMailable;

class Mailable extends BaseMailable
{

    public function view($view, array $data = [])
    {
        $this->view     = $view;
        $this->viewData = $data;

        return $this;
    }
}
