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

    public const string ROUND_DURATION = 'round_duration';

    public const string ROUNDS = 'rounds';

    public const string ENDING = 'ending';

    public const string HAS_TURNS = 'has_turns';

    public const string HAS_TEAMS = 'has_teams';

    public const string MIN_TEAM_LENGTH = 'min_team_length';

    public const string MAX_TEAM_LENGTH = 'max_team_length';

    public const string RULES = 'rules';

    public const string STARTING_POINTS = 'starting_points';

    public const string FINISHING_POINTS = 'finishing_points';

    public const string IS_WINNING = 'is_winning';

    public const string ICON = 'icon';

    public const string COLOR = 'color';

    public const string BG_COLOR = 'bg_color';

    public const string DESCRIPTION = 'description';

    public function rules(): array
    {
        return [
            self::NAME => ['required', 'string'],
            self::NUMBER_OF_PLAYERS => ['required', 'numeric', 'min:1'],
            self::TURN_DURATION => ['nullable', 'numeric'],
            self::HAS_TURNS => ['required', 'boolean'],
            self::HAS_TEAMS => ['required', 'boolean'],
            self::MIN_TEAM_LENGTH => ['required', 'numeric', 'min:0'],
            self::MAX_TEAM_LENGTH => ['required', 'numeric', 'max:20'],
            self::ROUND_DURATION => ['required', 'numeric'],
            self::ROUNDS => ['required', 'numeric'],
            self::RULES => ['string'],
            self::STARTING_POINTS=> ['required', 'numeric'],
            self::FINISHING_POINTS => ['required', 'numeric'],
            self::IS_WINNING => ['required', 'boolean'],
            self::ICON => ['required', 'string'],
            self::COLOR => ['required', 'string'],
            self::BG_COLOR => ['required', 'string'],
            self::DESCRIPTION => ['nullable', 'string'],
        ];
    }

    public function toDto(): GameDto
    {
        return new GameDto(
            name: $this->string(self::NAME)->toString(),
            number_of_players: $this->integer(self::NUMBER_OF_PLAYERS),
            turn_duration: $this->integer(self::TURN_DURATION),
            round_duration: $this->integer(self::ROUND_DURATION),
            rounds: $this->integer(self::ROUNDS),
            hasTurns: $this->boolean(self::HAS_TURNS),
            hasTeams: $this->boolean(self::HAS_TEAMS),
            min_team_length: $this->integer(self::MIN_TEAM_LENGTH),
            max_team_length: $this->integer(self::MAX_TEAM_LENGTH),
            rules: $this->string(self::RULES)->toString(),
            starting_points: $this->integer(self::STARTING_POINTS),
            finishing_points: $this->integer(self::FINISHING_POINTS),
            is_winning: $this->boolean(self::IS_WINNING),
            color: $this->string(self::COLOR)->toString(),
            bgColor: $this->string(self::BG_COLOR)->toString(),
            icon: $this->string(self::ICON)->toString(),
            description: $this->string(self::DESCRIPTION)->toString(),
        );
    }
}
