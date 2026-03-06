<?php
/**
 * Role Constants
 * Defines all available user roles in the system
 */

class Role {
    const ADMIN = 'Admin';
    const LANDLORD = 'Landlord';
    const RENTER = 'Renter';
    
    // Array of all valid roles
    const VALID_ROLES = ['Admin', 'Landlord', 'Renter'];
    
    // Role descriptions
    const ROLE_DESCRIPTIONS = [
        'Admin' => 'System Administrator - Full access to all features',
        'Landlord' => 'Property Owner - Manage properties and rentals',
        'Renter' => 'Tenant - Browse and rent properties'
    ];
    
    /**
     * Check if a role is valid
     */
    public static function isValid($role) {
        return in_array($role, self::VALID_ROLES);
    }
    
    /**
     * Get role description
     */
    public static function getDescription($role) {
        return self::ROLE_DESCRIPTIONS[$role] ?? 'Unknown role';
    }
    
    /**
     * Get all roles
     */
    public static function getAllRoles() {
        return self::VALID_ROLES;
    }
    
    /**
     * Get all roles with descriptions
     */
    public static function getAllRolesWithDescriptions() {
        return self::ROLE_DESCRIPTIONS;
    }
}
?>
