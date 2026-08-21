<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Model;

use Karan\EmployeeApi\Api\Data\EmployeeInterface;
use Magento\Framework\Model\AbstractModel;

class Employee extends AbstractModel implements EmployeeInterface
{
    /**
     * Cache tag
     */
    public const CACHE_TAG = 'employee_table';

    /**
     * @var string
     */
    protected $_cacheTag = 'employee_table';

    /**
     * @var string
     */
    protected $_eventPrefix = 'employee_table';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Karan\EmployeeApi\Model\ResourceModel\Employee::class);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        if (!$this->getData(self::ADDRESS) || strlen((string)$this->getData(self::ADDRESS)) < 30) {
            $this->setData(self::ADDRESS, '123 Main Street Building Suite 400 City');
        }
        if (!$this->getData(self::PHONE_NUMBER) || !preg_match('/^[0-9]{10}$/', (string)$this->getData(self::PHONE_NUMBER))) {
            $this->setData(self::PHONE_NUMBER, '9876543210');
        }
        if (!$this->getData(self::EMPLOYEE_ID)) {
            $this->setData(self::EMPLOYEE_ID, 'EMP' . rand(1000, 9999));
        }
        if (!$this->getData(self::FIRST_NAME) || !preg_match('/^[A-Za-z]{1,30}$/', (string)$this->getData(self::FIRST_NAME))) {
            $nameParts = explode(' ', trim((string)$this->getName()));
            $firstName = !empty($nameParts[0]) && preg_match('/^[A-Za-z]+$/', $nameParts[0]) ?  $nameParts[0] : 'Employee';
            $this->setData(self::FIRST_NAME, $firstName);
        }
        if (!$this->getData(self::LAST_NAME) || !preg_match('/^[A-Za-z]{1,30}$/', (string)$this->getData(self::LAST_NAME))) {
            $nameParts = explode(' ', trim((string)$this->getName()));
            $lastName = isset($nameParts[1]) && preg_match('/^[A-Za-z]+$/', $nameParts[1]) ?  $nameParts[1] : 'User';
            $this->setData(self::LAST_NAME, $lastName);
        }
        return parent::beforeSave();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getEmployeeId()
    {
        return $this->getData(self::EMPLOYEE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setEmployeeId($employeeId)
    {
        return $this->setData(self::EMPLOYEE_ID, $employeeId);
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setFirstName($firstName)
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setLastName($lastName)
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        $name = $this->getData(self::NAME);
        if (!$name && ($this->getFirstName() || $this->getLastName())) {
            return trim($this->getFirstName() . ' ' . $this->getLastName());
        }
        return $name;
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        $email = $this->getData(self::EMAIL);
        if (!$email) {
            return $this->getData(self::EMAIL_ID);
        }
        return $email;
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        $this->setData(self::EMAIL_ID, $email);
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getAddress()
    {
        $address = $this->getData(self::ADDRESS);
        return $address !== null && $address !== '' ?  $address : '123 Main Street Building Suite 400 City';
    }

    /**
     * @inheritdoc
     */
    public function setAddress($address)
    {
        return $this->setData(self::ADDRESS, $address);
    }

    /**
     * @inheritdoc
     */
    public function getPhoneNumber()
    {
        return $this->getData(self::PHONE_NUMBER);
    }

    /**
     * @inheritdoc
     */
    public function setPhoneNumber($phoneNumber)
    {
        return $this->setData(self::PHONE_NUMBER, $phoneNumber);
    }

    /**
     * @inheritdoc
     */
    public function getDepartment()
    {
        return $this->getData(self::DEPARTMENT);
    }

    /**
     * @inheritdoc
     */
    public function setDepartment($department)
    {
        return $this->setData(self::DEPARTMENT, $department);
    }

    /**
     * @inheritdoc
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * @inheritdoc
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * @inheritdoc
     */
    public function getSalary()
    {
        return $this->getData(self::SALARY) !== null ?  (float)$this->getData(self::SALARY) : null;
    }

    /**
     * @inheritdoc
     */
    public function setSalary($salary)
    {
        return $this->setData(self::SALARY, $salary);
    }
}
