<table class="table-bordered">
    <thead>
    <tr>
        <th>Teams</th>
        @foreach($teams as $team)
            <th>{{$team->name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($teams as $teamA)
        <tr>
            <td>{{$teamA->name}}</td>
            @foreach($teams as $teamB)
                @if($teamA->id === $teamB->id)
                    <td style="background:#f00"></td>
                @else
                    @php
                      /** @var \App\Models\Game $game */
                      $game = $teamA->gamesWithTeam($teamB)->first();
                    @endphp
                    <td>
                        {{ $game->score_a }} : {{ $game->score_b }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>