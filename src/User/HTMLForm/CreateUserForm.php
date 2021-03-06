<?php

namespace Anax\User\HTMLForm;

use Anax\User\User;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

/**
* Example of FormModel implementation.
*/
class CreateUserForm extends FormModel
{
    /**
    * Constructor injects with DI container.
    *
    * @param Psr\Container\ContainerInterface $di a service container
    */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Create user",
            ],
            [
                "acronym" => [
                    "type"        => "text",
                ],
                "email" => [
                    "type"        => "text",
                ],

                "password" => [
                    "type"        => "password",
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Create user",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    public function callbackSubmit()
    {
        $acronym       = $this->form->value("acronym");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");
        $email = $this->form->value("email");
        $created   = date("Y/m/d G:i:s", time());
        $active = 0;

        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        $db = $this->di->get("dbqb");
        $password = password_hash($password, PASSWORD_DEFAULT);
        $db->connect()
        ->insert("User", ["acronym", "password", "created", "email", "active"])
        ->execute([$acronym, $password, $created, $email, $active]);

        $this->form->addOutput("User was created.");
        return true;
    }
}
