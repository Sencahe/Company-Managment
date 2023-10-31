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

class CompanyTest extends TestCase
{

    use RefreshDatabase;

    private $companyJsonStructure = ['id', 'name', 'email', 'website', 'logo', 'updated_at', 'created_at'];

    public function test_get_all_companies()
    { 
        $amount = random_int(1, 10);
        Company::factory($amount)->create();

        $response = $this->fetch_company($admin = 1);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => $this->companyJsonStructure
        ]);
        $response->assertJsonCount($amount);
    }

    public function test_get_all_companies_rest()
    { 
        $amount = random_int(1, 10);
        Company::factory($amount)->create();

        $response = $this->fetch_company($admin = 1,$page=0,$companyId=0,$rest=true);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => $this->companyJsonStructure
        ]);
        $response->assertJsonCount($amount);
    }


    public function test_get_all_companies_from_page()
    {
        $amount = 40;
        $page = random_int(1, 4);
        Company::factory($amount)->create();

        $response = $this->fetch_company($admin = 1,$page);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['*' => $this->companyJsonStructure]
        ]);
        $response->assertJsonFragment([
            'current_page' => $page,
        ]);
        $response->assertJsonCount(10, 'data');
    }

    public function test_get_single_company()
    {

        $company = Company::factory(1)->create()->first();
        // Fetching company as adming, page = 0, and company Id
        $response = $this->fetch_company($admin = 1, $page = 0,$company->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id', 'name', 'email', 'website', 'logo', 'updated_at', 'created_at'
            ]);
        $responseBody = json_decode($response->getContent(), true);
        $this->assertTrue($responseBody['name'] == $company->name);
        $this->assertTrue($responseBody['email'] == $company->email );
        $this->assertTrue($responseBody['website'] == $company->website);

    }

    public function test_get_single_company_not_found()
    {
        // Not factoring a company, and fetching for Id 1
        $response = $this->fetch_company($admin = 1,$page = 0, $companyId = 1);
        $response->assertStatus(404);

    }

    public function test_create_company()
    {
        $company = [
            'name' => 'Test Company',
            'email' => 'testcompany@test.com',
            'website' => 'https://www.testcompany.com',
        ];

        $response = $this->handle_company($company, $admin=1, $square=100);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'website',
            'logo',
            'updated_at',
            'created_at'
        ]);

        $responseBody = json_decode($response->getContent(), true);
        $this->assertTrue($responseBody['name'] == $company['name']);
        $this->assertTrue($responseBody['email'] == $company['email'] );
        $this->assertTrue($responseBody['website'] == $company['website']);
        // get stored file
        $responseTestLogo = $responseBody['logo'];
        $storageTestLogoPath = Str::replace('storage', 'public', $responseTestLogo);
        $this->assertTrue(Storage::exists($storageTestLogoPath));
        // Delete the stored test file 
        Storage::delete($storageTestLogoPath);
    }

    public function test_create_company_malformed()
    {
        $company = [
            'name' => '',
            'email' => 'testcompany',
            'website' => 'testcompany.com',
        ];
        $response = $this->handle_company($company, $admin=1, $square=50);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['name', 'email', 'website', 'logoFile',]
        ]);
    }

    public function test_create_company_unique_fields_taken()
    {
        Company::factory()->create([
            'name' => 'CompanyName',
            'email' => 'companyemail@email.com'
        ]);

        $company = [
            'name' => 'CompanyName',
            'email' => 'companyemail@email.com',
            'website' => 'https://www.website.com'
        ];
        $response = $this->handle_company($company, $admin=1, $square=50);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['name', 'email']
        ]);
    }

    public function test_create_company_forbidden()
    {
        $company = [
            'name' => 'Test Company',
            'email' => 'testcompany@test.com',
            'website' => 'https://www.testcompany.com',
        ];

        $response = $this->handle_company($company, $admin=0, $square=100);
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function test_update_company()
    {
        $company = Company::factory(1)->create()->first();

        $updateCompany = [
            'id' => $company->id,
            'name' => 'Updated Name',
            'email' => 'UpdatedEmail@email.com',
            'website' => 'https://www.updated.com',
        ];

        $response = $this->handle_company($updateCompany, $admin=1, $square=100);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'website',
            'logo',
            'updated_at',
            'created_at'
        ]);
        $responseBody = json_decode($response->getContent(), true);
        $storageUpdatedLogoPath = Str::replace('storage', 'public', $responseBody['logo']);
        $this->assertTrue($responseBody['name'] != $company->name && $responseBody['name'] == $updateCompany['name']);
        $this->assertTrue($responseBody['email'] != $company->email && $responseBody['email'] == $updateCompany['email']);
        $this->assertTrue($responseBody['website'] != $company->website && $responseBody['website'] == $updateCompany['website']);
        $this->assertTrue($responseBody['logo'] != $company->logo);
        $this->assertTrue(Storage::exists($storageUpdatedLogoPath));
        Storage::delete($storageUpdatedLogoPath);
    }

    public function test_update_company_not_found()
    {
        $updateCompany = [
            'id' => 1,
            'name' => 'Updated Name',
            'email' => 'UpdatedEmail@email.com',
            'website' => 'https://www.updated.com',
        ];

        $response = $this->handle_company($updateCompany, $admin=1, $square=100);
        $response->assertStatus(404);
    }

    public function test_update_company_malformed()
    {
        $company = Company::factory(1)->create()->first();

        $updateCompany = [
            'id' => $company->id,
            'name' => '',
            'email' => 'UpdatedEmailMalformed',
            'website' => 'malformedwebsite.com',
        ];

        $response = $this->handle_company($updateCompany, $admin=1, $square=50);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => ['name', 'email', 'website', 'logoFile',]
        ]);
    }

    public function test_update_company_forbidden()
    {
        $company = Company::factory(1)->create()->first();

        $updateCompany = [
            'id' => $company->id,
            'name' => 'Updated Name',
            'email' => 'UpdatedEmail@email.com',
            'website' => 'https://www.updated.com',
        ];

        $response = $this->handle_company($updateCompany, 0, 100);
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'message' 
        ]);
    }

    public function test_delete_company()
    {
        $company = Company::factory(1)->create()->first();

        $response = $this->remove_company($company->id, $admin=1);

        $response->assertStatus(200);
    }

    public function test_delete_company_not_found()
    {
        $response = $this->remove_company($companyId = 1, $admin=1);

        $response->assertStatus(404);

    }

    public function test_delete_company_forbidden()
    {
        $company = Company::factory(1)->create()->first();

        $response = $this->remove_company($company->id, $admin=0);
        
        $response->assertStatus(403);
    }

    /* * * * * * * * * * * ** * * ** * ** 
    *
    *
    //      HELPER TEST FUNCTIONS
    *
    *
    */
    private function fetch_company(int $admin = 1,int $page = 0, int $companyId = 0, bool $rest = false){
        $user = User::factory()->create([
            'admin' => $admin
        ]);
        Sanctum::actingAs($user, ['*']);

        $path = $rest ? '/api':'/request';

        if($companyId != 0){
            $response = $this->get($path . '/company/' . $companyId);
        } else if($page != 0){
            $response = $this->get($path . '/companies/?page=' . $page);
        } else {
            $response = $this->get($path . '/companies/');
        }

        return $response;
    }


    private function handle_company(array $company, int $admin = 1, int $squareSize = 100, string $fileName = "test_logo.jpg")
    {
        // Auth
        $user = User::factory()->create([
            'admin' => $admin,
        ]);
        Sanctum::actingAs($user, ['*']);
        // create test logo file
        $filePath = $this->create_test_logo($squareSize, $fileName);
        $company['logoFile'] = new UploadedFile($filePath, $fileName, 'image/jpeg', null, true);

        // Perform request
        $requestPath = isset($company['id']) ? '/request/company/' . $company['id'] : '/request/company/';
        $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
        ])->json('POST', $requestPath, $company);

        // delete test logo file
        unlink($filePath);
        // return response
        return $response;
    }

    private function remove_company(int $companyId, int $admin = 1){
        // Auth
        $user = User::factory()->create([
            'admin' => $admin,
        ]);
        Sanctum::actingAs($user, ['*']);
         // Perform request
        $response = $this->delete('/request/company/' . $companyId);
         // return response
        return $response;
    }

    private function create_test_logo(int $squareSize = 100, string $logoFileName = "test_logo.jpg")
    {
        // Create test logo
        $file = UploadedFile::fake()->image($logoFileName);
        $filePath = public_path('/storage/images/' . $logoFileName);
        $image = Image::make($file)->resize($squareSize, $squareSize);
        $image->save($filePath);

        return $filePath;
    }
}
