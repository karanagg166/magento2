<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Api\Data;

interface EmployeeInterface
{
    public const ID = 'id';
    public const EMPLOYEE_ID = 'employee_id';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const NAME = 'name';
    public const EMAIL_ID = 'email_id';
    public const EMAIL = 'email';
    public const ADDRESS = 'address';
    public const PHONE_NUMBER = 'phone_number';
    public const DEPARTMENT = 'department';
    public const POSITION = 'position';
    public const SALARY = 'salary';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get Employee Code ID
     *
     * @return string|null
     */
    public function getEmployeeId();

    /**
     * Set Employee Code ID
     *
     * @param string $employeeId
     * @return $this
     */
    public function setEmployeeId($employeeId);

    /**
     * Get First Name
     *
     * @return string|null
     */
    public function getFirstName();

    /**
     * Set First Name
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * Get Last Name
     *
     * @return string|null
     */
    public function getLastName();

    /**
     * Set Last Name
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set Name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get Email
     *
     * @return string|null
     */
    public function getEmail();

    /**
     * Set Email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * Get Address
     *
     * @return string|null
     */
    public function getAddress();

    /**
     * Set Address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address);

    /**
     * Get Phone Number
     *
     * @return string|null
     */
    public function getPhoneNumber();

    /**
     * Set Phone Number
     *
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber);

    /**
     * Get Department
     *
     * @return string|null
     */
    public function getDepartment();

    /**
     * Set Department
     *
     * @param string $department
     * @return $this
     */
    public function setDepartment($department);

    /**
     * Get Position
     *
     * @return string|null
     */
    public function getPosition();

    /**
     * Set Position
     *
     * @param string $position
     * @return $this
     */
    public function setPosition($position);

    /**
     * Get Salary
     *
     * @return float|null
     */
    public function getSalary();

    /**
     * Set Salary
     *
     * @param float $salary
     * @return $this
     */
    public function setSalary($salary);
}
