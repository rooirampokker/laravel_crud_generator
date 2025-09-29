<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\[ModelName];
use App\Models\Team;//TODO: update to match the relation to test against in testAdminUserCanSearch[ModelName]Relation
use Tests\TestCase;


class [ModelName]Test extends TestCase
{
    use WithFaker;

    protected $tenancy = true;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seedDatabase();
        $this->[modelName] = [ModelName]::factory()->create();
    }

    /**
     * GET ../api/[modelName]s
     *
     * @return void
     */
    public function testUserCanGet[ModelName]Index()
    {
        $response = $this->actingAs($this->admin, 'api')->getJson($this->tenantURL . '/[modelName]s');

        $response->assertJson($this->apiResponse(
            true,
            200,
            __('[modelName]s.index.success'),
        ));
    }

    /**
     * GET ../api/[modelName]s/{[modelName]_id}
     *
     * @return void
     */
    public function testUserCanGet[ModelName]Show()
    {
        $response = $this->actingAs($this->admin, 'api')->getJson($this->tenantURL . '/[modelName]s/' . $this->[modelName]->id);

        $response->assertJson($this->apiResponse(
            true,
            200,
            __('[modelName]s.show.success'),
        ));
    }

    /**
     * POST ../api/[modelName]s
     *
     * @return void
     */
    public function testUserCanStore[ModelName]()
    {
        $attributesArray = [];
        $response = $this->actingAs($this->admin, 'api')->postJson($this->tenantURL . '/[modelName]s', $attributesArray);

        $response->assertJson($this->apiResponse(
            true,
            200,
            __('[modelName]s.store.success'),
        ));
    }

    /**
     * UPDATE ../api/[modelName]s/{[modelName]_id}
     *
     * @return void
     */
    public function testUserCanUpdate[ModelName]()
    {
        $response = $this->actingAs($this->admin, 'api')->putJson($this->tenantURL . '/[modelName]s/' . $this->[modelName]->id, [
            'name' => 'new_[modelName]'
        ]);

        $response->assertJson($this->apiResponse(
            true,
            200,
            __('[modelName]s.update.success'),
        ));
    }

    /**
     * DELETE ../api/[modelName]s/{[modelName]_id}
     *
     * @return void
     */
    public function testUserCanDelete[ModelName]()
    {
        $response = $this->actingAs($this->admin, 'api')->deleteJson($this->tenantURL . '/[modelName]s/' . $this->[modelName]->id);

        $response->assertJson($this->apiResponse(
            true,
            200,
            __('[modelName]s.delete.success', ['id' => $this->[modelName]->id]),
        ));
    }

    /**
     * DELETE/PATCH ..api/[modelName]s/:[modelName]_id
     * SUPER ADMIN CAN RESTORE A DELETED RECORD
     */
    public function testSuperAdminCanRestore[ModelName]()
    {
        //DELETE FIRST, THEN RESTORE
        app([ModelName]::class)->find($this->[modelName]->id)->delete();

        $response = $this->actingAs($this->admin, 'api')->patchJson($this->tenantURL . '/[modelName]s/' . $this->[modelName]->id);

        $response->assertJson($this->apiResponse(
            true,
            200,
            __('[modelName]s.restore.success', ['id' => $this->[modelName]->id])
        ));
    }
}
