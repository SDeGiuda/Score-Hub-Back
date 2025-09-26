<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\MatchResults\Domain\DataTransferObjects\ResultDto;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\Users\Domain\Models\User;

class StoreResultsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'results' => ['required', 'array'],
            'game_id' => ['required', 'integer', Rule::exists('games', 'id')],
            'results.*.user_id' => ['required', Rule::exists(User::class, 'id')],
            'results.*.position' => ['required', 'integer', 'min:1'],
            'results.*.points'   => ['required', 'integer', 'min:0'],
            'results.*.status'=>[Rule::enum(ResultStatusEnum::class)],
        ];
    }

    public function toDtos(): array
    {
        $gameId = $this->integer('game_id');

        /** @var array<int, array{user_id:int|string, position:int|string, points:int|string, status:string}> $results */
        $results = $this->validated('results');

        $resultsArray = [];
        foreach ($results as $result) {
            $resultsArray[] = new ResultDto(
                game_id: $gameId,
                user_id: (int) $result['user_id'],
                position: (int) $result['position'],
                points: (int) $result['points'],
                status: ResultStatusEnum::from($result['status']),
            );
        }

        return $resultsArray;
    }
}
