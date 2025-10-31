<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Matches\Domain\Models\GameMatch;
use Src\MatchResults\Domain\DataTransferObjects\ResultDto;
use Src\MatchResults\Domain\Models\MatchResult;
use Src\Users\Domain\Models\User;

class UpdateResultsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'results' => ['required', 'array'],
            'match_id' => ['required', 'integer', Rule::exists(GameMatch::class, 'id')],
            'results.*'=>['required', 'array'],
            'results.*.user_id' => ['required', Rule::exists(User::class, 'id')],
            'results.*.position' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function toDtos(): array
    {
        /** @var array<int, array{user_id:int|string, position:int|string, points:int|string, status:string}> $results */
        $results = $this->validated('results');

        $matchResults = MatchResult::where('match_id', $this->integer('match_id'))->get();

        $resultsArray = [];
        foreach ($results as $result) {
            $resultsArray[] = new ResultDto(
                matchResult: $matchResults->where('user_id', $result['user_id'])->firstOrFail(),
                position: (int) $result['position'],
                points: (int) $result['points'],
            );
        }

        return $resultsArray;
    }
}
