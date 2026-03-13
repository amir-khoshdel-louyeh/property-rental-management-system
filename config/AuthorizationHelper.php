<?php
/**
 * Authorization Helper
 * Provides resource-level access control checks
 */

require_once 'session.php';
require_once 'RoleConstants.php';
require_once 'ErrorHandler.php';

class AuthorizationHelper {
    
    /**
     * Check if user can manage properties
     */
    public static function canManageProperties() {
        return hasRole([Role::ADMIN, Role::LANDLORD]);
    }
    
    /**
     * Check if user can view all users
     */
    public static function canViewAllUsers() {
        return hasRole(Role::ADMIN);
    }
    
    /**
     * Check if user can manage users
     */
    public static function canManageUsers() {
        return hasRole(Role::ADMIN);
    }
    
    /**
     * Check if user can change user roles
     */
    public static function canChangeUserRoles() {
        return hasRole(Role::ADMIN);
    }
    
    /**
     * Check if user can view reports
     */
    public static function canViewReports() {
        return hasRole([Role::ADMIN, Role::LANDLORD]);
    }
    
    /**
     * Check if user can manage payments
     */
    public static function canManagePayments() {
        return hasRole([Role::ADMIN, Role::LANDLORD]);
    }
    
    /**
     * Check if user can view inspections
     */
    public static function canViewInspections() {
        return hasRole([Role::ADMIN, Role::LANDLORD, Role::RENTER]);
    }
    
    /**
     * Check if user can create inspections
     */
    public static function canCreateInspections() {
        return hasRole([Role::ADMIN, Role::LANDLORD]);
    }
    
    /**
     * Check if user can access dashboard
     */
    public static function canAccessDashboard() {
        return isLoggedIn();
    }
    
    /**
     * Check if user can view own profile
     */
    public static function canViewOwnProfile() {
        return isLoggedIn();
    }
    
    /**
     * Check if user can edit own profile
     */
    public static function canEditOwnProfile() {
        return isLoggedIn();
    }
    
    /**
     * Check if user can view property
     */
    public static function canViewProperty($property_id = null) {
        // All logged-in users can view properties
        return isLoggedIn();
    }
    
    /**
     * Check if user can edit property
     */
    public static function canEditProperty($property_landlord_id = null) {
        // Admins can edit any property
        if (isAdmin()) {
            return true;
        }
        
        // Landlords can only edit their own properties
        if (isLandlord() && $property_landlord_id !== null) {
            $current_user_id = getCurrentUserId();
            return $current_user_id == $property_landlord_id;
        }
        
        return false;
    }
    
    /**
     * Check if user can delete property
     */
    public static function canDeleteProperty($property_landlord_id = null) {
        return self::canEditProperty($property_landlord_id);
    }
    
    /**
     * Check role hierarchy (can perform admin actions on user if in lower role)
     */
    public static function canModifyUser($target_user_role) {
        $current_role = getCurrentUserRole();
        
        $role_hierarchy = [
            Role::ADMIN => 1,
            Role::LANDLORD => 2,
            Role::RENTER => 3
        ];
        
        // Only Admins can modify other users
        if ($current_role !== Role::ADMIN) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Require permission for specific action
     */
    public static function requirePermission($callable) {
        if (!call_user_func($callable)) {
            AppErrorHandler::forbidden('Access Denied. You do not have permission to perform this action.');
        }
    }
    
    /**
     * Get role-based redirect destination
     */
    public static function getRoleRedirectPage($role = null) {
        $role = $role ?? getCurrentUserRole();
        
        switch ($role) {
            case Role::ADMIN:
                return 'admin_dashboard.php';
            case Role::LANDLORD:
                return 'landlord_dashboard.php';
            case Role::RENTER:
                return 'renter_dashboard.php';
            default:
                return 'index.php';
        }
    }
}
?>
