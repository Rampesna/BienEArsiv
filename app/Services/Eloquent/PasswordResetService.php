<?php

namespace App\Services\Eloquent;

use App\Models\Eloquent\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new PasswordReset);
    }

    /**
     * @param string $token
     */
    public function getByToken(
        string $token
    )
    {
        return PasswordReset::where('token', $token)->first();
    }

    /**
     * @param string $relationType
     * @param int $relationId
     * @param string $datetime
     */
    public function checkPasswordReset(
        string $relationType,
        int    $relationId,
        string $datetime
    )
    {
        return PasswordReset::where('relation_type', $relationType)
            ->where('relation_id', $relationId)
            ->where('created_at', '>', $datetime)
            ->where('used', 0)
            ->exists();
    }

    /**
     * @param string $relationType
     * @param int $relationId
     */
    public function create(
        string $relationType,
        int    $relationId
    )
    {
        $passwordReset = new PasswordReset;
        $passwordReset->relation_type = $relationType;
        $passwordReset->relation_id = $relationId;
        $passwordReset->token = Str::random(32);
        $passwordReset->save();

        return $passwordReset;
    }

    /**
     * @param PasswordReset $passwordReset
     */
    public function setUsed(
        PasswordReset $passwordReset
    )
    {
        $passwordReset->used = true;
        $passwordReset->save();
    }
}
