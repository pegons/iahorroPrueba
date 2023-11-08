<?php

namespace App\Dtos;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * @OA\Schema(
 *   schema="PostLeadDto",
 *   title="Post Lead Data Transfer Object"
 * )
 */
class PostLeadDto extends DataTransferObject
{
    /**
     * @OA\Property(
     *  property="name",
     *  type="string",
     *  example="John Doe"
     * )
     */
    public string $name;

    /**
     * @OA\Property(
     *  property="email",
     *  type="string",
     *  format="email",
     *  example="john.doe@example.com"
     * )
     */
    public string $email;

    /**
     * @OA\Property(
     *  property="phone",
     *  type="string",
     *  example="+1234567890",
     *  nullable=true
     * )
     */
    public ?string $phone;

    /**
     * @OA\Property(
     *  property="createdAt",
     *  type="string",
     *  format="date-time",
     *  example="2023-11-07T12:34:56Z"
     * )
     */
}
