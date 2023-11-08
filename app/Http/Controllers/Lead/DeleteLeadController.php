<?php

namespace App\Http\Controllers\Lead;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Microservice\Application\Services\Delete\DeleteLeadInterface;

class DeleteLeadController extends Controller
{
    private DeleteLeadInterface $deleteLeadApplication;

    public function __construct(DeleteLeadInterface $deleteLeadApplication)
    {
        $this->deleteLeadApplication = $deleteLeadApplication;
    }

    /**
     * @OA\Delete(
     *      path="/lead/{uuid}",
     *      tags={"Leads"},
     *      summary="Delete a lead",
     *      description="Delete an existing lead",
     *      operationId="DeleteLeadService",
     *      security={
     *         {"bearerAuth": {}},
     *      },
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          required=true,
     *          description="UUID of the lead to delete",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Lead deleted successfully"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found"
     *      )
     * )
     */
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            ($this->deleteLeadApplication)($uuid);
            return response()->json(['message' => 'Lead deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $status = $e->getCode() == 0 ? 500 : $e->getCode();
            return response()->json(['message' => $e->getMessage()], $status);
        }
    }
}
