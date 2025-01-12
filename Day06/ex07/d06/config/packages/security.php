<?php
// config/packages/security.php
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security): void {
    // ...

    $security->roleHierarchy('ROLE_STANDARD', ['ROLE_BASIC']);
    $security->roleHierarchy('ROLE_PREMIUM', ['ROLE_STANDARD']);
    $security->roleHierarchy('ROLE_GOLD', ['ROLE_PREMIUM']);
    $security->roleHierarchy('ROLE_ADMIN', ['ROLE_GOLD']);
};