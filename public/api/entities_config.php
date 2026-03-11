<?php

function getEntityConfigs() {
    return [
        'landlords' => [
            'table' => 'Landlord',
            'primaryKey' => 'landlord_id',
            'fields' => ['first_name', 'last_name', 'phone_number', 'email'],
            'required' => ['first_name', 'last_name']
        ],
        'renters' => [
            'table' => 'Renter',
            'primaryKey' => 'renter_id',
            'fields' => ['first_name', 'last_name', 'phone_number', 'email', 'date_of_birth'],
            'required' => ['first_name', 'last_name']
        ],
        'properties' => [
            'table' => 'Property',
            'primaryKey' => 'property_id',
            'fields' => ['property_type', 'addres', 'city', 'area_property', 'bedrooms', 'descriptions', 'rent_sale', 'price', 'landlord_id', 'zip_code'],
            'required' => ['addres', 'city', 'zip_code', 'landlord_id']
        ],
        'rentals' => [
            'table' => 'Rental',
            'primaryKey' => 'rental_id',
            'fields' => ['property_id', 'renter_id', 'start_date', 'end_date', 'monthly_rent', 'security_deposit'],
            'required' => ['property_id', 'renter_id', 'start_date', 'end_date']
        ],
        'payments' => [
            'table' => 'Payment',
            'primaryKey' => 'payment_id',
            'fields' => ['rental_id', 'property_id', 'amount', 'payment_date'],
            'required' => ['rental_id', 'property_id', 'amount', 'payment_date']
        ],
        'services' => [
            'table' => 'services',
            'primaryKey' => 'service_id',
            'fields' => ['services_name'],
            'required' => ['services_name']
        ],
        'inspections' => [
            'table' => 'Inspection',
            'primaryKey' => 'inspection_id',
            'fields' => ['property_id', 'inspection_date', 'findings', 'conducted_by'],
            'required' => ['property_id', 'inspection_date', 'findings', 'conducted_by']
        ],
        'propertyservices' => [
            'table' => 'PropertyServices',
            'primaryKey' => 'property_service_id',
            'fields' => ['property_id', 'service_id'],
            'required' => ['property_id', 'service_id']
        ]
    ];
}
