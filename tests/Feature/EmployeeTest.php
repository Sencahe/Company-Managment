<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Intervention\Image\Facades\Image;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;

class EmployeeTest extends TestCase
{
  
    use RefreshDatabase;

    private $employeeJsonStructure = ['id', 'name', 'lastName', 'company_id', 'email','phone', 'updated_at', 'created_at'];

    public function test_get_all_employees()
    { 
        
        $company = Company::factory(1)->create()->first();
        $amount = random_int(1, 10);
        Employee::factory($amount)->create([
            "company_id" => $company->id,
        ]);

        $response = $this->fetch_employee($admin = 1);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => $this->employeeJsonStructure
        ]);
        $response->assertJsonCount($amount);
    }

    public function test_get_all_employees_rest()
    { 
        $company = Company::factory(1)->create()->first();
        $amount = random_int(1, 10);
        Employee::factory($amount)->create([
            "company_id" => $company->id,
        ]);

        $response = $this->fetch_employee($admin = 1,$page=0,$companyId=0,$rest=true);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => $this->employeeJsonStructure
        ]);
        $response->assertJsonCount($amount);
    }


    public function test_get_all_employees_from_page()
    {
        $company = Company::factory(1)->create()->first();

        $amount = 40;
        $page = random_int(1, 4);
        Employee::factory($amount)->create([
            "company_id" => $company->id,
        ]);

        $response = $this->fetch_employee($admin = 1,$page);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['*' => $this->employeeJsonStructure]
        ]);
        $response->assertJsonFragment([
            'current_page' => $page,
        ]);
        $response->assertJsonCount(10, 'data');
    }

    public function test_get_single_employee()
    {

        $company = Company::factory(1)->create()->first();
        $employee = Employee::factory(1)->create([
            "company_id" => $company->id,
        ])->first();
        // Fetching company as adming, page = 0, and company Id
        $response = $this->fetch_employee($admin = 1, $page = 0,$employee->id);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->employeeJsonStructure);
        $responseBody = json_decode($response->getContent(), true);
        $this->assertTrue($responseBody['name'] == $employee->name);
        $this->assertTrue($responseBody['lastName'] == $employee->lastName );
        $this->assertTrue($responseBody['company_id'] == $employee->company_id);
        $this->assertTrue($responseBody['email'] == $employee->email);
        $this->assertTrue($responseBody['phone'] == $employee->phone);
    }

    public function test_get_single_employee_not_found()
    {
        // Not factoring a company, and fetching for Id 1
        $response = $this->fetch_employee($admin = 1,$page = 0, $employeeId = 1);
        $response->assertStatus(404);

    }

    public function test_create_employee()
    {
        $company = Company::factory(1)->create()->first();
        $employee = [
            'name' => 'Test',
            'lastName' => 'Employee',
            'company_id' => $company->id,
            'email'=> 'employe@email.com',
            'phone'=> '+123456789'
        ];

        $response = $this->handle_employee($employee,$admin=1);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->employeeJsonStructure);

        $responseBody = json_decode($response->getContent(), true);
        $this->assertTrue($responseBody['name'] == $employee['name']);
        $this->assertTrue($responseBody['lastName'] == $employee['lastName'] );
        $this->assertTrue($responseBody['company_id'] == $employee['company_id']);
        $this->assertTrue($responseBody['email'] == $employee['email'] );
        $this->assertTrue($responseBody['phone'] == $employee['phone']);
       
    }

    public function test_create_employee_malformed()
    {
        $employee = [
            'name' => 'Te',
            'lastName' => 'Us',
            'company_id' => '',
            'email'=> 'employe.com',
            'phone'=> '1'
        ];
        $response = $this->handle_employee($employee, $admin=1);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['name', 'lastName', 'company_id', 'email','phone']
        ]);
    }

    public function test_create_employee_nonexistent_company()
    {
        $employee = [
            'name' => 'Employee',
            'lastName' => 'Lastname',
            'company_id' => '1',
            'email'=> 'employe@email.com',
            'phone'=> '+123456789'
        ];
        $response = $this->handle_employee($employee, $admin=1);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['company_id']
        ]);
    }

    public function test_create_employee_unique_fields_taken()
    {
        $company = Company::factory(1)->create()->first();
        Employee::factory(1)->create([
            'name' => 'Original',
            'lastName' => 'Employee',
            'company_id' => $company->id,
            'email' => 'employee@email.com'
        ]);

        $employee = [
            'name' => 'Original',
            'lastName' => 'Employee',
            'company_id' => $company->id,
            'email' => 'employee@email.com'
        ];
        $response = $this->handle_employee($employee, $admin=1);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['email']
        ]);
    }

    public function test_create_employee_forbidden()
    {
        $company = Company::factory(1)->create()->first();
        $employee = [
            'name' => 'Original',
            'lastName' => 'Employee',
            'company_id' => $company->id,
            'email' => 'employee@email.com'
        ];

        $response = $this->handle_employee($employee, $admin=0);
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'message'
        ]);
    }
 
    public function test_update_employee()
    {
        $company = Company::factory(1)->create()->first();    
        $employee = Employee::factory(1)->create([
            'company_id' => $company->id,
        ])->first();

        $updateCompany = Company::factory(1)->create()->first();
        $updateEmployee = [
            'id' => $employee->id,
            'name' => 'Name Updated',
            'lastName' => 'Lastaname Updated',
            'company_id' => $updateCompany->id,
            'email' => 'employee@email.com',
            'phone' => '+123456789'
        ];

        $response = $this->handle_employee($updateEmployee, $admin=1);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->employeeJsonStructure);
        $responseBody = json_decode($response->getContent(), true);
       
        $this->assertTrue($responseBody['name'] != $employee->name && $responseBody['name'] == $updateEmployee['name']);
        $this->assertTrue($responseBody['lastName'] != $employee->lastName && $responseBody['lastName'] == $updateEmployee['lastName']);
        $this->assertTrue($responseBody['company_id'] != $employee->company_id && $responseBody['company_id'] == $updateEmployee['company_id']);
        $this->assertTrue($responseBody['email'] != $employee->email && $responseBody['email'] ==$updateEmployee['email'] );
        $this->assertTrue($responseBody['phone'] != $employee->phone && $responseBody['phone'] == $updateEmployee['phone']);

    }

    public function test_update_employee_not_found()
    {
        $company = Company::factory(1)->create()->first();    
        $updateEmployee = [
            'id' => 1,
            'name' => 'Name Updated',
            'lastName' => 'Lastaname Updated',
            'company_id' => $company->id,
            'email' => 'employee@email.com',
            'phone' => '+123456789'
        ];

        $response = $this->handle_employee($updateEmployee, $admin=1);
        $response->assertStatus(404);
    }

    public function test_update_employee_malformed()
    {
        $company = Company::factory(1)->create()->first();    
        $employee = Employee::factory(1)->create([
            'company_id' => $company->id,
        ])->first();

        $updateEmployee = [
            'id' => $employee->id,
            'name' => 'Na',
            'lastName' => 'La',
            'company_id' => '',
            'email' => 'employee.com',
            'phone' => '2'
        ];

        $response = $this->handle_employee($updateEmployee, $admin=1);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['name', 'lastName', 'company_id', 'email','phone']
        ]);
    }

    public function test_update_employee_forbidden()
    {
        $company = Company::factory(1)->create()->first();    
        $employee = Employee::factory(1)->create([
            'company_id' => $company->id,
        ])->first();

        $updateEmployee = [
            'name' => 'Name Updated',
            'lastName' => 'Lastaname Updated',
            'company_id' => $company->id,
            'email' => 'employee@email.com',
            'phone' => '+123456789'
        ];

        $response = $this->handle_employee($updateEmployee, 0);
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'message' 
        ]);
    }

    public function test_delete_employee()
    {
        $company = Company::factory(1)->create()->first();    
        $employee = Employee::factory(1)->create([
            'company_id' => $company->id,
        ])->first();


        $response = $this->remove_employee($employee->id, $admin=1);

        $response->assertStatus(200);
    }

    public function test_delete_employee_not_found()
    {
        $response = $this->remove_employee($employeeId = 1, $admin=1);

        $response->assertStatus(404);

    }

    public function test_delete_employee_forbidden()
    {
        $company = Company::factory(1)->create()->first();    
        $employee = Employee::factory(1)->create([
            'company_id' => $company->id,
        ])->first();

        $response = $this->remove_employee($employee->id, $admin=0);
        
        $response->assertStatus(403);
    }
    /* * * * * * * * * * * ** * * ** * ** 
    *
    *
    //      HELPER TEST FUNCTIONS
    *
    *
    */
    private function fetch_employee(int $admin = 1,int $page = 0, int $employeeId = 0, bool $rest = false){
       // Auth
        $user = User::factory()->create([
            'admin' => $admin
        ]);
        Sanctum::actingAs($user, ['*']);

        // Perform request based on args
        $path = $rest ? '/api':'/request';

        if($employeeId != 0){
            $response = $this->get($path . '/employee/' . $employeeId);
        } else if($page != 0){
            $response = $this->get($path . '/employees/?page=' . $page);
        } else {
            $response = $this->get($path . '/employees/');
        }
        // return response
        return $response;
    }


    private function handle_employee(array $employee, int $admin = 1)
    {
        // Auth
        $user = User::factory()->create([
            'admin' => $admin,
        ]);
        Sanctum::actingAs($user, ['*']);

        // Perform request
        $requestPath = isset($employee['id']) ? '/request/employee/' . $employee['id'] : '/request/employee/';
        $requestMethod = isset($employee['id']) ? 'PUT' : 'POST';
        $response = $this->json($requestMethod, $requestPath, $employee);

        // return response
        return $response;
    }

    private function remove_employee(int $employeeId, int $admin = 1){
        // Auth
        $user = User::factory()->create([
            'admin' => $admin,
        ]);
        Sanctum::actingAs($user, ['*']);
        // Perform request
        $response = $this->delete('/request/employee/' . $employeeId);
         // return response
        return $response;
    }
}
