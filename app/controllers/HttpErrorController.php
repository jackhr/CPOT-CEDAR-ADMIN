<?php

namespace App\Controllers;

use App\Helpers\GeneralHelper;

class HttpErrorController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new GeneralHelper();
    }

    public function render404Page()
    {
        $user = $this->helper->getSessionUser();
        $this->view("errors/404.php", compact("user"));
    }
}
