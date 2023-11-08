<?php

namespace App\Http\Controllers\Lead;

use App\Dtos\PutLeadDto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Microservice\Application\Services\Put\PutLeadInterface;


class PutLeadController extends Controller
{
    private PutLeadInterface $updateLeadApplication;

    public function __construct(PutLeadInterface $updateLeadApplication)
    {
        $this->updateLeadApplication = $updateLeadApplication;
    }

    /**
     * @OA\Put(
     *      path="/lead/{uuid}",
     *      tags={"Leads"},
     *      summary="Update a lead",
     *      description="Update an existing lead",
     *      operationId="PutLeadService",
     *      security={
     *         {"bearerAuth": {}},
     *      },
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          required=true,
     *          description="UUID of the lead to update",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email"},
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="phone", type="string", nullable=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Lead updated correctly"
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
    public function __invoke(Request $request, string $uuid): JsonResponse
    {
        try {
            $data = $request->only(['name', 'email', 'phone']) + ['uuid' => $uuid];
            $putDto = new PutLeadDto($data);
            ($this->updateLeadApplication)($putDto);
            return response()->json(['message' => 'Lead updated correctly'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $status = $e->getCode() == 0 ? 404 : $e->getCode();
            return response()->json(['message' => $e->getMessage()], $status);
        }
    }
}
