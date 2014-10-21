<?php

class HasMxTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set up for tests in this file.
     *
     * @access private
     */
    private function setupTest()
    {
        $this->validator = new \EmailValidator\Validator();
    }

    /**
     * Test hasMx
     *
     * @cover \EmailValidator\Validator::hasMx
     */
    public function testHasMx()
    {
        $this->setupTest();

        // Not an email
        $this->assertNull(
            $this->validator->hasMx('example.com')
        );

        // No Records
        $this->assertFalse(
            $this->validator->hasMx('example@example.com')
        );

        $this->assertFalse(
            $this->validator->hasMx('example@localhost')
        );

        // Records
        $this->assertTrue(
            $this->validator->hasMx('example@gmail.com')
        );

        $this->assertTrue(
            $this->validator->hasMx('example@yahoo.com')
        );
    }
}