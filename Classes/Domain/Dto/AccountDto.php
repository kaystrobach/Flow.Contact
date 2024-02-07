<?php

namespace KayStrobach\Contact\Domain\Dto;

use KayStrobach\Contact\Domain\Model\User;
use Neos\Flow\Security\Account;

class AccountDto
{
    public Account $account;
    public User $user;
    public string $password;
    public string $passwordRepeat;
}
