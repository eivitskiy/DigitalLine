<table class="games playoff-games">
    <tbody>
    @for($i = \App\Enums\GameRound::OneFourth; $i != null; $i = $i->nextRound())
        <tr>
            <td>Round: {{$i->value}}</td>
            @foreach($games as $game)
                @if($i === $game->round)
                    <td colspan="
                    @switch($i)
                    @case(\App\Enums\GameRound::TheFinal) 4
                    @case(\App\Enums\GameRound::SemiFinal) 2
                    @default 0
                    @endswitch
                    ">
                        @if($game->winner === $game->participantA)
                            {{ $game->participantA->name }} VS
                            <span style="text-decoration:line-through">{{ $game->participantB->name }}</span>
                        @else
                            <span style="text-decoration:line-through">{{ $game->participantA->name }}</span>
                            VS {{ $game->participantB->name }}
                        @endif
                            <br/>
                            {{ $game->score_a . ':' .  $game->score_b }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endfor
    <tr>
        <td>Winner:</td>
        <td colspan="4">
            {{$game->winner->name}}
        </td>
    </tr>
    </tbody>
</table>