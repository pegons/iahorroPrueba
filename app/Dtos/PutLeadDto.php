<?php

namespace App\Dtos;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * @OA\Schema(
 *   schema="PutLeadDto",
 *   title="Put Lead Data Transfer Object"
 * )
 */
class PutLeadDto extends DataTransferObject
{
    /**
     * @OA\Property(
     *  property="uuid",
     *  type="string",
     *  format="uuid",
     *  description="The unique identifier for the lead",
     *  example="123e4567-e89b-12d3-a456-426614174000"
     * )
     */
    public string $uuid;

    /**
     * @OA\Property(
     *  property="name",
     *  type="string",
     *  example="John Doe"
     * )
     */
    public ?string $name;

    /**
     * @OA\Property(
     *  property="email",
     *  type="string",
     *  format="email",
     *  example="john.doe@example.com"
     * )
     */
    public ?string $email;

    /**
     * @OA\Property(
     *  property="phone",
     *  type="string",
     *  example="+1234567890",
     *  nullable=true
     * )
     */
    public ?string $phone;
}
