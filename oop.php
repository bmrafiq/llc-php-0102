<?php

class User
{
    private $username, $phone_number;

    public function setUsername($username): void
    {
        $this->username = strtolower($username);
    }

    public function setPhoneNumber($phone_number): void
    {
        if (strlen($phone_number) === 11) {
            $this->phone_number = $phone_number;
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPhoneNumber(): string
    {
        return str_replace(substr($this->phone_number, 0, 4), 'XXXX', $this->phone_number);
    }
}

$shohan = new User();
$shohan->setUsername('Shohan');
$shohan->setPhoneNumber('01111111111');
echo $shohan->getPhoneNumber();

echo '<br/>';

$shoharto = new User();
$shoharto->setUsername('Shoharto');
$shoharto->setPhoneNumber('02222222222');
echo $shoharto->getPhoneNumber();

//
//echo '<br/>';
//
//$shoharto = new User();
//$shoharto->setUsername('Shoharto');
//$shoharto->show();
//
//echo '<br/>';
//
//$nipun = new User();
//$nipun->setUsername('Nipun');
//$nipun->show();
