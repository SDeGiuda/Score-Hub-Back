<?php

declare(strict_types=1);

namespace Src\Games\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Games\Domain\DataTransferObjects\GameDto;

class UpsertGameRequest extends FormRequest
{
    public const string NAME = 'name';

    public const string NUMBER_OF_PLAYERS = 'number_of_players';

    public const string TURN_DURATION = 'turn_duration';

    public const string HAS_TURNS = 'has_turns';

    public const string HAS_TEAMS = 'has_teams';

    public const string TEAM_LENGTH = 'team_length';

    public const string RULES = 'rules';

    public function rules(): array
    {
        return [
            self::NAME => ['required', 'string'],
            self::NUMBER_OF_PLAYERS => ['required', 'numeric', 'min:1'],
            self::TURN_DURATION => ['required', 'numeric'],
            self::HAS_TURNS => ['required', 'boolean'],
            self::HAS_TEAMS => ['required', 'boolean'],
            self::TEAM_LENGTH => ['required', 'numeric', 'min:0'],
            self::RULES => ['string'],
        ];
    }

    public function toDto(): GameDto
    {
        return new GameDto(
            name: $this->string(self::NAME)->toString(),
            rules: $this->string(self::RULES)->toString(),
            number_of_players: $this->integer(self::NUMBER_OF_PLAYERS),
            way_of_ending_id: 1, // Default value - needs form validation
            visibility: 1, // Default value - needs form validation
            winner_criteria: 1, // Default value - needs form validation
            description: $this->string('description')->toString(),
            hasTeams: $this->boolean(self::HAS_TEAMS),
            hasTurns: $this->boolean(self::HAS_TURNS),
            turn_duration: $this->integer(self::TURN_DURATION),
            number_of_teams: $this->integer(self::TEAM_LENGTH) // Mapping team_length to number_of_teams
        );
    }
}
