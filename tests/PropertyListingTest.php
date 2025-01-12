<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class PropertyListingTest extends TestCase
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

    public function testCreatePropertyListing()
    {
        try {
            $propertyId = 'test_prop_' . uniqid();
            $userId = 'test_user_' . uniqid();
            
            $propertyData = [
                'id' => $propertyId,
                'user_id' => $userId,
                'property_name' => 'Test Property',
                'address' => 'Test Address, City',
                'price' => '250000',
                'type' => 'flat',
                'offer' => 'sale',
                'status' => 'ready to move',
                'furnished' => 'furnished',
                'bhk' => '2',
                'deposite' => '20000',
                'bedroom' => '2',
                'bathroom' => '2',
                'balcony' => '1',
                'carpet' => '1200',
                'age' => '2',
                'total_floors' => '5',
                'room_floor' => '3',
                'loan' => 'available'
            ];

            $stmt = $this->conn->prepare("INSERT INTO property 
                (id, user_id, property_name, address, price, type, offer, status, 
                furnished, bhk, deposite, bedroom, bathroom, balcony, carpet, 
                age, total_floors, room_floor, loan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                $this->fail("Prepare failed");
            }

            if (!$stmt->execute([
                $propertyData['id'],
                $propertyData['user_id'],
                $propertyData['property_name'],
                $propertyData['address'],
                $propertyData['price'],
                $propertyData['type'],
                $propertyData['offer'],
                $propertyData['status'],
                $propertyData['furnished'],
                $propertyData['bhk'],
                $propertyData['deposite'],
                $propertyData['bedroom'],
                $propertyData['bathroom'],
                $propertyData['balcony'],
                $propertyData['carpet'],
                $propertyData['age'],
                $propertyData['total_floors'],
                $propertyData['room_floor'],
                $propertyData['loan']
            ])) {
                $this->fail("Execute failed");
            }

            $stmt = $this->conn->prepare("SELECT * FROM property WHERE id = ?");
            if (!$stmt) {
                $this->fail("Prepare failed");
            }
            
            if (!$stmt->execute([$propertyId])) {
                $this->fail("Execute failed");
            }
            
            $property = $stmt->fetch(\PDO::FETCH_ASSOC);

            $this->assertNotNull($property, "Property should be found in database");
            $this->assertEquals($propertyData['property_name'], $property['property_name'], "Property name should match");
            $this->assertEquals($propertyData['price'], $property['price'], "Property price should match");
            
            $this->conn->exec("DELETE FROM property WHERE id = '$propertyId'");
            
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testSearchProperties()
    {
        try {
            $propertyId = 'test_search_' . uniqid();
            $userId = 'test_user_' . uniqid();
            
            $testProperty = [
                'id' => $propertyId,
                'user_id' => $userId,
                'property_name' => 'Searchable Property',
                'address' => 'Search City Location',
                'price' => '300000',
                'type' => 'house',
                'offer' => 'sale'
            ];

            $stmt = $this->conn->prepare("INSERT INTO property 
                (id, user_id, property_name, address, price, type, offer) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                $this->fail("Prepare failed");
            }

            if (!$stmt->execute([
                $testProperty['id'],
                $testProperty['user_id'],
                $testProperty['property_name'],
                $testProperty['address'],
                $testProperty['price'],
                $testProperty['type'],
                $testProperty['offer']
            ])) {
                $this->fail("Execute failed");
            }

            $searchTerm = 'Search City';
            $stmt = $this->conn->prepare("SELECT * FROM property WHERE address LIKE ?");
                
            if (!$stmt) {
                $this->fail("Prepare failed");
            }
            
            if (!$stmt->execute(['%' . $searchTerm . '%'])) {
                $this->fail("Execute failed");
            }

            $found = false;
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                if ($row['id'] === $propertyId) {
                    $found = true;
                    $this->assertEquals($testProperty['address'], $row['address'], "Property address should match");
                    break;
                }
            }
            
            $this->assertTrue($found, "Should find the test property");
            
            $this->conn->exec("DELETE FROM property WHERE id = '$propertyId'");
            
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
