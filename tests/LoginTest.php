<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        try {
            $this->conn = TestConfig::getConnection();
        } catch (\Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public function testAdminLogin()
    {
        try {
            $admin_id = 'test_admin_' . uniqid();
            $name = 'test_admin';
            $password = sha1('test123'); 

            $stmt = $this->conn->prepare("INSERT INTO admins (id, name, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                $this->fail("Prepare failed");
            }
            
            if (!$stmt->execute([$admin_id, $name, $password])) {
                $this->fail("Execute failed");
            }

            $stmt = $this->conn->prepare("SELECT * FROM admins WHERE name = ? AND password = ?");
            if (!$stmt) {
                $this->fail("Prepare failed");
            }
            
            $test_password = sha1('test123');
            if (!$stmt->execute([$name, $test_password])) {
                $this->fail("Execute failed");
            }
            
            $admin = $stmt->fetch(\PDO::FETCH_ASSOC);

            $this->assertNotNull($admin, "Admin should be found in database");
            $this->assertEquals($name, $admin['name'], "Admin name should match");
            
            $this->conn->exec("DELETE FROM admins WHERE id = '$admin_id'");
            
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFailedAdminLogin()
    {
        try {
            $name = 'nonexistent_admin';
            $password = sha1('wrong_password');
            
            $stmt = $this->conn->prepare("SELECT * FROM admins WHERE name = ? AND password = ?");
            if (!$stmt) {
                $this->fail("Prepare failed");
            }
            
            if (!$stmt->execute([$name, $password])) {
                $this->fail("Execute failed");
            }
            
            $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->assertFalse($admin, "No admin should be found with incorrect credentials");
            
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    protected function tearDown(): void
    {
        $this->conn = null;
    }
    
    public static function tearDownAfterClass(): void
    {
        TestConfig::cleanupDatabase();
    }
}
