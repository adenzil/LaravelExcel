<?php

namespace Tests\Unit;

use Tests\TestCase;

class LaravelExcelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $file = new \Illuminate\Http\UploadedFile(resource_path('users.xlsx'), 'users.xlsx', null, null, true);
        $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
        ])->post('/api/user-import', ['your_file' => $file]);

        if($response->assertStatus(200)) {
            $this->assertDatabaseHas('users', [
                'name' => 'alister',
                'email' => 'alister.cabral@bombayworks.se',
            ]);

            $this->assertDatabaseHas('users', [
                'name' => 'francis',
                'email' => 'francis.rodrigues@bombayworks.se',
            ]);
        }
    }
}
