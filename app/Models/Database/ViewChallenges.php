<?php

declare(strict_types=1);

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @method where(mixed $columnOrArray = '', mixed $columnValue = '')
*/
class ViewChallenges extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'v_challenges';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all open challenges where user has challenged another user
     *
     * @param string $userId
     * @property object $result
     * @property array $openChallengesSent
     * @return array $openChallengesSent
     */
    public function getOpenChallengesSent($userId): array
    {
        $result = $this->where(['challenger_id' => $userId, 'opponent_result_id' => null])
                                ->get();

        $openChallengesSent = [];

        foreach ($result as $row) {
            array_push($openChallengesSent, [
                'challenge_id' => $row->challenge_id,
                'time' => substr($row->time, 0, 10),
                'bet' => $row->bet,
                'opponent_id' => $row->opponent_id,
                'opponent_name' => $row->opponent_name,
                'denied' => $row->denied
            ]);
        }

        return $openChallengesSent;
    }

    /**
     * Get all open challenges where user has been challenged
     *
     * @param string $userId
     * @property object $result
     * @property array $openChallenges
     * @return array $openChallenges
     */
    public function getOpenChallenges($userId): array
    {
        $result = $this->where(['opponent_id' => $userId, 'opponent_result_id' => null])
                                ->get();

        $openChallenges = [];

        foreach ($result as $row) {
            array_push($openChallenges, [
                'challenge_id' => $row->challenge_id,
                'time' => substr($row->time, 0, 10),
                'bet' => $row->bet,
                'challenger_id' => $row->challenger_id,
                'challenger_name' => $row->challenger_name,
                'denied' => $row->denied
            ]);
        }

        return $openChallenges;
    }

    /**
     * Get all challenges for one player from the v_challenges view
     *
     * @param int $challengeId
     * @property array $result
     * @property array $challenge
     * @return array $challenge
     */
    public function getOneChallenge(int $challengeId)
    {
        $result = $this->where('challenge_id', $challengeId)
                        ->get();

        $challenge = [
            'challenge_id' => $result[0]->challenge_id,
            'time' => substr($result[0]->time, 0, 10),
            'bet' => $result[0]->bet,
            'challenger_result_id' => $result[0]->challenger_result_id,
            'challenger_id' => $result[0]->challenger_id,
            'challenger_name' => $result[0]->challenger_name,
            'challenger_score' => $result[0]->challenger_score,
            'opponent_result_id' => $result[0]->opponent_result_id,
            'opponent_id' => $result[0]->opponent_id,
            'opponent_name' => $result[0]->opponent_name,
            'opponent_score' => $result[0]->opponent_score,
            'winner' => $result[0]->winner
        ];

        return $challenge;
    }

    /**
     * Get all challenges for one player from the v_challenges view
     *
     * @param string $userId
     * @property array $result
     * @property array $finishedChallenges
     * @property array $row
     * @return array $finishedChallenges
     */
    public function getFinishedChallenges($userId)
    {
        $result = $this->where('challenger_id', $userId)
                        ->whereNotNull('opponent_score')
                        ->orWhere('opponent_id', $userId)
                        ->whereNotNull('opponent_score')
                        ->get();

        $finishedChallenges = [];

        foreach ($result as $row) {
            array_push($finishedChallenges, [
                'challenge_id' => $row->challenge_id,
                'time' => substr($row->time, 0, 10),
                'bet' => $row->bet,
                'challenger_result_id' => $row->challenger_result_id,
                'challenger_id' => $row->challenger_id,
                'challenger_name' => $row->challenger_name,
                'challenger_score' => $row->challenger_score,
                'opponent_result_id' => $row->opponent_result_id,
                'opponent_id' => $row->opponent_id,
                'opponent_name' => $row->opponent_name,
                'opponent_score' => $row->opponent_score,
                'winner' => $row->winner
            ]);
        }

        return $finishedChallenges;
    }
}
