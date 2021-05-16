@include('header')

<div class="game-main-wrapper">

<div class="game-content-left">

<h1>YatzyBonanza @if ($data['gameOver'] == 'false')<span class="green">Live!</span>@else<span class="red">Game Over!</span>@endif</h1>

<div class="statusbox">
    @if ($data['mode'] == 'challenge')
        <p><span class="bold">Game mode</span>: New challenge</p>
    @elseif ($data['mode'] == 'accept')
        <p><span class="bold">Game mode</span>: Challenge accepted</p>
    @else
        <p><span class="bold">Game mode</span>: Single player (reach 250 or more to win)</p>
    @endif
        <p><span class="bold">Amount at stake</span>: {{ $data['bet'] }} ¥</p>
</div>

    <form method="post" class="yatzy-form" action="{{ url('/yatzy') }}">
        @csrf
        <div class="yatzy-dice">
            @foreach ($data['diceArray'] as $key => $value)
            <div class="onedice">
                <img src="{{ asset('/img/' . $value . '.png') }}" class="dice-image"><br>
                @if ($data['twoRerollsMade'] == 'false')
                <input type="checkbox" name="{{ $key }}" value="selected">
                @else
                <input type="checkbox" name="{{ $key }}" value="selected" disabled>
                @endif
            </div>
            @endforeach
        </div>

        @if ($data['twoRerollsMade'] == 'false')
        <div class="statusbox">
            <h2>Rolls made: {{ $data['nrOfRerolls']+1 }} of 3</h2>
            <p>Select which dice you want to roll again, then click below to roll</p>
            <input type="submit" name="roll" value="Roll!" class="submit">
        </div>
        @elseif ($data['gameOver'] == 'false')
        <div class="statusbox">
            <h2>Round nr. {{ $data['nrOfRoundsPlayed'] }} (of 15) is over!</h2>
            <p>Save the dice in your scorecard to start next round.</p>
        </div>
        @endif

    </form>

    @if ($data['gameOver'] == 'true')
    <div class="statusbox">
        <h2><span class="red">Game Over!</span> Your final score: {{ $data['totalPoints'] }}</h2>

        @if ($data['mode'] == 'single' && $data['totalPoints'] >= 250)
        <p>Congratulations! You collected over 250 points and won {{ $data['bet'] }} ¥!</p>
        @endif

        <form method="post" class="yatzy-form" action="{{ url('/highscores') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="mode" value="{{ $data['mode'] }}">
            <input type="hidden" name="bet" value="{{ $data['bet'] }}">
            <input type="hidden" name="opponent" value="{{ $data['opponent'] }}">
            <input type="hidden" name="challengeId" value="{{ $data['challengeId'] }}">
            <input type="hidden" name="score" value="{{ $data['totalPoints'] }}">
            <input type="hidden" name="bonus" value="{{ $data['bonus'] }}">
            @foreach ($data['pointsPerRound'] as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            @foreach ($data['frequency'] as $key => $value)
                <input type="hidden" name="dice_{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="submit" name="submit" value="Save results" class="submit">
        </form>
    </div>
    @endif

</div>

<div class="game-content-right">
    <div class="scorecard-wrapper">
        <h3>Yatzy Scorecard</h3>
        <table class="scorecard">
            <tr>
                <th>Round</th>
                <th>Points</th>
            </tr>

            @foreach ($data['pointsPerRound'] as $key => $value)
            <tr>
                <td>
                    <span class="scorecard-{{ $key }}"></span>
                </td>
                <td>
                @if ($value < 0)
                    <form method="post" action="{{ url('/yatzy') }}">
                    @csrf
                    <input type="hidden" name="roundOver" value="roundOver">
                    @if ($data['twoRerollsMade'] == 'true')
                    <button type="submit" name="selectedRound" value="{{ $key }}" class="submit button green">Save</button>
                    @endif
                    </form>
                @else
                    {{ $value }}
                @endif
                </td>

            @endforeach
            </tr>
            <tr class="bonus">
                <td>
                    Bonus
                </td>
                <td>
                @if ($data['bonus'] >= 0)
                    {{ $data['bonus'] }}
                @endif
                </td>
            </tr>
            <tr class="totalpoints">
                <td>
                    Total points:
                </td>
                <td>
                {{ $data['totalPoints'] }}
                </td>
            </tr>
        </table>
    </div>
</div>
</div>
@include('footer')
