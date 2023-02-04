<?php

/**
 *  Functions to specify access rights/privileges to resources
 */

class Authorization {

    public static function provideIfUserDirectlyAfterRegistration() {
        return isset($_SESSION['is_redirect_after_registration']) && $_SESSION['is_redirect_after_registration'];
    }

    public static function destroySessionCompletely() {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

    }

    public static function checkAuthorization() {
        if (!isset($_SESSION['userLogged'])) {
            Url::redirect('noauthorization.php');
        }
    }

}