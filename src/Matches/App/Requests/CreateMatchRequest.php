<?php

declare(strict_types=1);

namespace Src\Matches\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Games\Domain\Models\Game;
use Src\Matches\Domain\DataTransferObjects\MatchDto;
use Src\Users\Domain\Models\User;

class CreateMatchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'creator_id' => ['required', Rule::exists(User::class, 'id')],
            'game_id' => ['required', Rule::exists(Game::class, 'id')],
        ];
    }

    public function toDto(): MatchDto
    {
        return new MatchDto(
            creatorId: $this->integer("creator_id"),
            gameId: $this->integer("game_id"),
        );
    }
}
