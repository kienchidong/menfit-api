<?php


namespace common\models;


class PublicUser extends User
{
    public $unsetAttributes = ['password_salt', 'password_hash', 'user_id_path'];
}
