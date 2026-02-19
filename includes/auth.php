<?php
if(defined('INCLUDES_AUTH')) return; // prevent multiple includes/ redeclaration
define('INCLUDES_AUTH', true);

if(session_status() === PHP_SESSION_NONE) session_start();

// Role hierarchy: higher index = higher privileges
$ROLE_HIERARCHY = [
    'counselor' => 0,
    'agm' => 1,
    'gm' => 2,
    'admin' => 3
];

if(!function_exists('require_login')) {
    function require_login() {
        if(empty($_SESSION['role'])) {
            header("Location: /login.php");
            exit();
        }
    }
}

/**
 * Require one of allowed roles (array) or a minimum hierarchy level.
 * Example: require_role(['admin']); OR require_role('gm') to allow gm and above.
 */
if(!function_exists('require_role')) {
function require_role($roles_or_min) {
    global $ROLE_HIERARCHY;
    require_login();
    $current = $_SESSION['role'] ?? '';
    if(is_array($roles_or_min)) {
        if(!in_array($current, $roles_or_min)) {
            header("Location: /login.php");
            exit();
        }
    } else {
        // treat as minimum role
        $min = $roles_or_min;
        if((($ROLE_HIERARCHY[$current] ?? -1) < ($ROLE_HIERARCHY[$min] ?? 0))) {
            header("Location: /login.php");
            exit();
        }
    }
}
    }
/**
 * Check whether the current admin can view a given application row.
 * Expects application array with at least 'state', 'region', 'district', 'email'.
 */
if(!function_exists('can_view_application')) {
function can_view_application(array $app) {
    $role = $_SESSION['role'] ?? '';
    if($role === 'admin') return true;
    if($role === 'gm') {
        return (!empty($_SESSION['state']) && !empty($app['state']) && strcasecmp(trim($_SESSION['state']), trim($app['state'])) === 0);
    }
    if($role === 'agm') {
        // prefer district if available, else region
        if(!empty($_SESSION['district']) && !empty($app['district'])) {
            return strcasecmp(trim($_SESSION['district']), trim($app['district'])) === 0;
        }
        if(!empty($_SESSION['region']) && !empty($app['region'])) {
            return strcasecmp(trim($_SESSION['region']), trim($app['region'])) === 0;
        }
        return false;
    }
    if($role === 'counselor') {
        // counselor can view only assigned student email(s)
        if(!empty($_SESSION['assigned_student_email'])) {
            return strcasecmp(trim($_SESSION['assigned_student_email']), trim($app['email'])) === 0;
        }
        return false;
    }
    return false;
}

}
