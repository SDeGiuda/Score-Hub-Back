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
            'players' => ['required', 'array'],
            'players.*' => [
                'required',
                'string',
                Rule::when(
                    fn ($value): bool => $value !== 'guest',
                    ['exists:users,name']
                ),
            ],
        ];
    }

    public function toDto(): MatchDto
    {
        /** @var array<int, string> $players */
        $players = $this->array('players');

        return new MatchDto(
            creatorId: $this->integer('creator_id'),
            gameId: $this->integer('game_id'),
            players: $players
        );
    }
}
