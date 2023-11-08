<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use Microservice\Application\Services\Get\GetLeadInterface;

class GetLeadController extends Controller
{
    private GetLeadInterface $getRideServiceApplication;
    public function __construct(
        GetLeadInterface $getRideServiceApplication
    ) {
        $this->getRideServiceApplication = $getRideServiceApplication;
    }
    /**
     * @OA\Get(
     * 		path="/lead/{lead_id}",
     * 		tags={"Leads"},
     * 		summary="Get a lead",
     * 		description="Get a lead",
     * 		operationId="GetLeadService",
     * 		security={
     *			{"bearerAuth": {}},
     *		},
     * 	   @OA\Parameter(
     * 		  name="lead_id",
     * 		  in="path",
     *        required=true,
     * 		  description="Filter by lead_id",
     * 		    @OA\Schema(
     *			    type="string"
     *		    )
     * 	    ),
     *		@OA\Response(
     *			response=200,
     *			description="Get a lead",
     *			@OA\JsonContent(ref="#/components/schemas/Lead")
     *		),
     *		@OA\Response(
     *			response=404,
     *			description="Not found"
     *		),
     *		@OA\Response(
     *			response=401,
     *			description="Unauthorized"
     *		)
     * )
     */
    public function __invoke(String $leadId): array
    {
        return ($this->getRideServiceApplication)($leadId)->toArray();
    }
}
