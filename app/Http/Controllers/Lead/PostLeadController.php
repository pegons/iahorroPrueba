<?php

namespace App\Http\Controllers\Lead;

use Illuminate\Support\Facades\Log;
use App\Dtos\PostLeadDto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Microservice\Application\Services\Post\PostLeadInterface;

class PostLeadController extends Controller
{
    private PostLeadInterface $createLeadApplication;

    public function __construct(PostLeadInterface $createLeadApplication)
    {
        $this->createLeadApplication = $createLeadApplication;
    }

    /**
     * @OA\Post(
     *      path="/lead",
     *      tags={"Leads"},
     *      summary="Create a lead",
     *      description="Create a new lead",
     *      operationId="PostLeadService",
     *      security={
     *         {"bearerAuth": {}},
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "phone", "email"},
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="phone", type="string"),
     *              @OA\Property(property="email", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Client created correctly"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $postDto = new PostLeadDto($request->only('name', 'phone', 'email'));
            $lead = ($this->createLeadApplication)($postDto);
            return response()->json(['message' => 'Client created correctly', 'data' => $lead], 200);
        } catch (\Exception $e) {
            // Log the error message
            Log::error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
