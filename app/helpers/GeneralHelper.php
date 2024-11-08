<?php

namespace App\Helpers;

use App\Core\Router;

class GeneralHelper
{
    private $router;

    public function __construct()
    {
        $this->router = new Router($this);
    }

    public function dump($data = null, $split_values = true, $prettify = true, $die = false)
    {
        $style = $prettify ? "background-color: #ccc;padding: 12px;border-radius: 4px;" : "";
        if ($prettify) {
            if ($split_values) {
                if (is_array($data)) {
                    foreach ($data as $part) {
                        echo "<pre style=\"$style\">";
                        print_r($part);
                        echo "</pre>";
                        echo "<br>";
                    }
                } else {
                    echo "<pre style=\"$style\">";
                    print_r($data);
                    echo "</pre>";
                }
            } else {
                echo "<pre style=\"$style\">";
                print_r($data);
                echo "</pre>";
            }
        } else {
            if ($split_values) {
                if (is_array($data)) {
                    foreach ($data as $part) {
                        var_dump($part);
                        echo "<br>";
                    }
                } else {
                    var_dump($data);
                }
            } else {
                var_dump($data);
            }
        }
        if ($die) die();
    }

    public function dd($data = null, $split_values = true, $prettify = true)
    {
        $this->dump($data, $split_values, $prettify, true);
    }

    public function getSessionUser($logoutIfNull = true)
    {
        session_start();
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        if (is_null($user) && $logoutIfNull) {
            $this->router->redirect("/logout");
            exit();
        }
        return $user;
    }

    public function respondToClient($data = [], $status = 200, $message = "success")
    {
        echo json_encode(compact("data", "status", "message"));
        die();
    }

    public function getFileExtension($fileType)
    {
        $extension = null;
        if ($fileType == 'image/jpeg') {
            $extension = '.jpg';
        } elseif ($fileType == 'image/png') {
            $extension = '.png';
        } elseif ($fileType == 'image/gif') {
            $extension = '.gif';
        } elseif ($fileType == 'image/webp') {
            $extension = '.webp';
        } elseif ($fileType == 'image/bmp') {
            $extension = '.bmp';
        } elseif ($fileType == 'image/tiff') {
            $extension = '.tiff';
        } elseif ($fileType == 'application/pdf') {
            $extension = '.pdf';
        } elseif ($fileType == 'application/msword') {
            $extension = '.doc';
        } elseif ($fileType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            $extension = '.docx';
        } elseif ($fileType == 'application/vnd.ms-excel') {
            $extension = '.xls';
        } elseif ($fileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $extension = '.xlsx';
        } elseif ($fileType == 'text/plain') {
            $extension = '.txt';
        } elseif ($fileType == 'application/zip') {
            $extension = '.zip';
        } elseif ($fileType == 'application/x-rar-compressed') {
            $extension = '.rar';
        }
        return $extension;
    }

    public function checkIfFileTypeIsAcceptable($fileType)
    {
        $acceptable = false;
        if ($fileType == 'image/avif') {
            $acceptable = true;
        } elseif ($fileType == 'image/jpeg') {
            $acceptable = true;
        } elseif ($fileType == 'image/png') {
            $acceptable = true;
        } elseif ($fileType == 'image/webp') {
            $acceptable = true;
        } elseif ($fileType == 'image/tiff') {
            $acceptable = true;
        }
        return $acceptable;
    }
}
