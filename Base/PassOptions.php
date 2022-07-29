<?php

namespace Base;

class PassOptions {

    public static function userPasswordTreatment(string $password): string
    {
        return hash('xxh128', hash('murmur3a', $password) . 'mv' . hash('crc32c', $password));
    }
    public static function createPass($password): string
    {
        return password_hash( self::userPasswordTreatment($password), PASSWORD_DEFAULT );
    }
    public static function checkVerifyPass(string $passwordSpecified, string $passwordHashFromDb): bool
    {
        return password_verify( self::userPasswordTreatment($passwordSpecified), $passwordHashFromDb);
    }
    public static function checkNeedsRehash(string $userPassword): bool
    {
        return password_needs_rehash( self::userPasswordTreatment($userPassword), PASSWORD_DEFAULT);
    }

}