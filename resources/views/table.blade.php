<table class="group-games">
    <thead>
    <tr>
        <th>Teams</th>
        @foreach($teams as $team)
            <th>{{$team->name}}</th>
        @endforeach
        <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @foreach($teams as $teamA)
        <tr>
            <td>{{$teamA->name}}</td>
            @foreach($teams as $teamB)
                @if($teamA->id === $teamB->id)
                    <td class="disabled"></td>
                @else
                    @php
                      /** @var \App\Models\Game $game */
                      $game = $teamA->gamesWithTeam($teamB)->first();
                    @endphp
                    <td title="{{ $game->winner->name ?? 'draw' }}">
                        {{ $game->score_a }} : {{ $game->score_b }}
                    </td>
                @endif
            @endforeach
            <td>{{$teamA->score}}</td>
        </tr>
    @endforeach
    </tbody>
</table>