<?php

namespace App\Http\Controllers;

use App\Club;
use App\Match;
use App\Tournament;
use Auth;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function menu(){

        if (request()->ajax()){

            $view = view('dynamic-content.tournaments.menu')->render();
            return response()->json($view);
        }
        else{
            return view('tournaments.menu');
        }
    }

    public function show(Tournament $tournament){

        $clubs = $tournament->clubs;
        $numberOfRounds = $tournament->matches->last()->round;

        if (request()->ajax()){

            $view = view('dynamic-content.tournaments.show',
                compact('tournament', 'clubs', 'numberOfRounds'))->render();
            return response()->json($view);
        }
        else{
            return view('tournaments.show', compact('tournament', 'clubs', 'numberOfRounds'));
        }
    }

    public function getFirstRoundMatches(Tournament $tournament){

        $firstRoundMatches = $tournament->matches()
            ->where('round', 1)
            ->whereNotNull('first_club_id')
            ->whereNotNull('second_club_id')
            ->orderBy('start_date_and_time', 'asc')
            ->paginate(2);

        if (request()->ajax()){

            $view = view('layouts.elements.matches.firstRoundMatches',
                compact('tournament', 'firstRoundMatches'))->render();
            return response()->json($view);
        }
        else{
            return view('layouts.elements.matches.firstRoundMatches',
                compact('tournament', 'firstRoundMatches'));
        }
    }

    public function getSecondRoundMatches(Tournament $tournament){

        $secondRoundMatches = $tournament->matches()
            ->where('round', 2)
            ->whereNotNull('first_club_id')
            ->whereNotNull('second_club_id')
            ->orderBy('start_date_and_time', 'asc')
            ->paginate(2);

        $numberOfMatches = $tournament->numberOfMatches();

        $isFinalRound = $tournament->isFinalRound(2);
        $thirdPlaceMatchId = $tournament->matches->get($numberOfMatches - 1)->id;
        $firstPlaceMatchId = $tournament->matches->get($numberOfMatches - 2)->id;

        if (request()->ajax()){

            $view = view('layouts.elements.matches.secondRoundMatches',
                compact('tournament', 'isFinalRound',
                    'secondRoundMatches', 'thirdPlaceMatchId', 'firstPlaceMatchId'))->render();
            return response()->json($view);
        }
        else{
            return view('layouts.elements.matches.secondRoundMatches',
                compact('tournament', 'isFinalRound',
                    'secondRoundMatches', 'thirdPlaceMatchId', 'firstPlaceMatchId'));
        }
    }

    public function getThirdRoundMatches(Tournament $tournament){

        $thirdRoundMatches = $tournament->matches()
            ->where('round', 3)
            ->whereNotNull('first_club_id')
            ->whereNotNull('second_club_id')
            ->orderBy('start_date_and_time', 'asc')
            ->paginate(2);

        $numberOfMatches = $tournament->numberOfMatches();

        $isFinalRound = $tournament->isFinalRound(3);
        $thirdPlaceMatchId = $tournament->matches->get($numberOfMatches - 1)->id;
        $firstPlaceMatchId = $tournament->matches->get($numberOfMatches - 2)->id;

        if (request()->ajax()){

            $view = view('layouts.elements.matches.thirdRoundMatches',
                compact('tournament', 'isFinalRound',
                    'thirdRoundMatches', 'thirdPlaceMatchId', 'firstPlaceMatchId'))->render();
            return response()->json($view);
        }
        else{
            return view('layouts.elements.matches.thirdRoundMatches',
                compact('tournament', 'isFinalRound',
                    'thirdRoundMatches',  'thirdPlaceMatchId', 'firstPlaceMatchId'));
        }
    }

    public function getFourthRoundMatches(Tournament $tournament){

        $fourthRoundMatches = $tournament->matches()
            ->where('round', 4)
            ->whereNotNull('first_club_id')
            ->whereNotNull('second_club_id')
            ->orderBy('start_date_and_time', 'asc')
            ->paginate(2);

        $numberOfMatches = $tournament->numberOfMatches();

        $isFinalRound = $tournament->isFinalRound(4);
        $thirdPlaceMatchId = $tournament->matches->get($numberOfMatches - 1)->id;
        $firstPlaceMatchId = $tournament->matches->get($numberOfMatches - 2)->id;

        if (request()->ajax()){

            $view = view('layouts.elements.matches.fourthRoundMatches',
                compact('tournament', 'isFinalRound',
                    'fourthRoundMatches',  'thirdPlaceMatchId', 'firstPlaceMatchId'))->render();
            return response()->json($view);
        }
        else{
            return view('layouts.elements.matches.fourthRoundMatches',
                compact('tournament', 'isFinalRound',
                    'fourthRoundMatches', 'thirdPlaceMatchId', 'firstPlaceMatchId'));
        }
    }

    public function create(){

        if (request()->ajax()){

            $view = view('dynamic-content.tournaments.create')->render();
            return response()->json($view);
        }
        else{
            return view('tournaments.create');
        }
    }

    public function store(Request $request){

        if ($request->has('name') && $request->has('start_date') && $request->has('end_date') &&
            $request->has('country') && $request->has('city') && $request->has('tournament_points') &&
            $request->has('number_of_seats') && $request->has('game_system')){

            $this->validate($request, [
                'name' => 'required|string|min:5|max:40',
                'country' => 'required|string|min:3|max:12',
                'city' => 'required|string|min:3|max:12',
                'tournament_points' => 'required|integer|min:20|max:2000'
            ]);

            $tournament = new Tournament;
            $tournament->name = $request->name;

            $tournament->start_date = date_format(date_create_from_format('d/m/Y', $request->start_date), 'Y-m-d');
            $tournament->end_date = date_format(date_create_from_format('d/m/Y', $request->end_date), 'Y-m-d');
            $tournament->country = $request->country;
            $tournament->city = $request->city;
            $tournament->tournament_points = $request->tournament_points;
            $tournament->number_of_seats = $request->number_of_seats;
            $tournament->number_of_available_seats = $tournament->number_of_seats;
            $tournament->game_system = $request->game_system;

            if ($request->has('prize_first_place')){
                $tournament->prize_first_place = $request->prize_first_place;
            }

            if ($request->has('prize_second_place')){
                $tournament->prize_second_place = $request->prize_second_place;
            }

            if ($request->has('prize_third_place')){
                $tournament->prize_third_place = $request->prize_third_place;
            }

            $tournament->user_id = Auth::user()->id;
            $tournament->save();

            flashy()->success('Tournament was successfully created !');
            return redirect()->to(route('home'));
        }

        flashy()->error('Tournament cannot be created. Fill out all missing required fields');
        return redirect()->back();
    }

    public function join(Tournament $tournament){

        if (request()->ajax()){
            if ($tournament){
                if ($tournament->isOpen()){
                    if ($tournament->isExpired()){
                        $tournament->delete();
                        return response()->json('Cannot join. This tournament has already expired');
                    }
                    else{

                        $clubId = Auth::user()->club->id;
                        $tournament->clubs()->attach($clubId);

                        $tournament->number_of_occupied_seats++;
                        $tournament->number_of_available_seats--;

                        // Tournament starts and matches are generated
                        if ($tournament->number_of_available_seats === 0) {
                            $tournament->in_game_clubs = $tournament->number_of_occupied_seats;
                            $tournament->status = 'ongoing';

                            $this->generateMatches($tournament->id, $tournament->clubs);
                        }

                        $tournament->save();

                        return response()->json('Your club joined to the tournament');
                    }
                }

                return response()->json('Cannot join. This tournament has already started');
            }

            return response()->json('This tournament doesn\'t exist');
        }
    }

    public function leave(Tournament $tournament){

        if (request()->ajax()){
            if ($tournament){
                if ($tournament->isOpen()){
                    if ($tournament->isExpired()){
                        $tournament->delete();
                        return response()->json('This tournament has already expired');
                    }
                    else{

                        $clubId = Auth::user()->club->id;
                        $tournament->clubs()->detach($clubId);

                        $tournament->number_of_occupied_seats--;
                        $tournament->number_of_available_seats++;

                        $tournament->save();

                        return response()->json('Your club left tournament');
                    }
                }

                return response()->json('Cannot leave. This tournament has already started');
            }

            return response()->json('This tournament doesn\'t exist');
        }
    }

    public function close(Tournament $tournament){

        if (request()->ajax()){
            if (Auth::user()->isOrganizer() && $tournament->isYourTournament()
                && $tournament->isOngoing() && $tournament->areAllMatchesCompleted()){

                $tournament->status = 'closed';
                $tournament->save();

                return response()->json('You have successfully closed this tournament');
            }

            return response()->json('Cannot close this tournament');
        }
    }

    public function treeView(Tournament $tournament){

        if (request()->ajax()){

            $numberOfRounds = $tournament->matches->last()->round;
            $numberOfAllMatches = $tournament->numberOfMatches();
            $numberOfFirstRoundMatches = $tournament->numberOfFirstRoundMatches();

            $matches = $tournament->matches;

            return response()->json([
                'numberOfRounds' => $numberOfRounds,
                'numberOfAllMatches' => $numberOfAllMatches,
                'numberOfFirstRoundMatches' => $numberOfFirstRoundMatches,
                'matches' => $matches
            ]);
        }
    }

    // Search tournaments - search, filters and sort

    public function listAndSearch(Request $request){

        $expiredTournaments = Tournament::where('status', 'open')
            ->where('start_date', '<', date('Y-m-d'));
        $expiredTournaments->delete();

        $tournaments = Tournament::orderBy('start_date', 'asc')->paginate(5);

        if ($request->sortBy === 'name'){

            if ($request->direction === 'desc'){
                $tournaments = Tournament::orderBy('name', 'desc')->paginate(5);
            }
            else{
                $tournaments = Tournament::orderBy('name', 'asc')->paginate(5);
            }
        }
        elseif ($request->sortBy === 'start_date'){

            if($request->direction === 'desc'){
                $tournaments = Tournament::orderBy('start_date', 'desc')->paginate(5);
            }
            else{
                $tournaments = Tournament::orderBy('start_date', 'asc')->paginate(5);
            }
        }
        elseif ($request->sortBy === 'end_date'){

            if($request->direction === 'desc'){
                $tournaments = Tournament::orderBy('end_date', 'desc')->paginate(5);
            }
            else{
                $tournaments = Tournament::orderBy('end_date', 'asc')->paginate(5);
            }
        }
        elseif ($request->sortBy === 'country'){

            if($request->direction === 'desc'){
                $tournaments = Tournament::orderBy('country', 'desc')->paginate(5);
            }
            else{
                $tournaments = Tournament::orderBy('country', 'asc')->paginate(5);
            }
        }
        elseif ($request->sortBy === 'city'){

            if($request->direction === 'desc'){
                $tournaments = Tournament::orderBy('city', 'desc')->paginate(5);
            }
            else{
                $tournaments = Tournament::orderBy('city', 'asc')->paginate(5);
            }
        }
        elseif ($request->sortBy === 'tournament points'){

            if($request->direction === 'desc'){
                $tournaments = Tournament::orderBy('tournament_points', 'desc')->paginate(5);
            }
            else{
                $tournaments = Tournament::orderBy('tournament_points', 'asc')->paginate(5);
            }
        }
        elseif ($request->sortBy === 'number_of_available_seats'){

            if($request->direction === 'desc'){
                $tournaments = Tournament::orderBy('number_of_available_seats', 'desc')->paginate(5);
            }
            else{
                $tournaments = Tournament::orderBy('number_of_available_seats', 'asc')->paginate(5);
            }
        }

        if (request()->ajax()){

            $firstView = view('layouts.elements.tournaments.search.search')->render();
            $secondView = view('dynamic-content.tournaments.list', compact('tournaments'))->render();

            return response()->json([
                'search' => $firstView,
                'list' => $secondView
            ]);
        }
        else{
            return view('tournaments.list', compact('tournaments'));
        }
    }

    public function search(Request $request){

        if(request()->ajax()){

            $tournamentStartDateValue = $request->tournamentStartDateValue;
            $tournamentEndDateValue = $request->tournamentEndDateValue;

            $tournamentMinTournamentPointsValue = $request->tournamentMinTournamentPointsValue;
            $tournamentMaxTournamentPointsValue = $request->tournamentMaxTournamentPointsValue;

            $tournamentNumberOfSeatsValue = $request->tournamentNumberOfSeatsValue;
            $tournamentGameSystemValue = $request->tournamentGameSystemValue;
            $tournamentStatusValue = $request->tournamentStatusValue;

            $tournaments = Tournament::orderBy('start_date', 'asc')
                ->where('name', 'like', $request->tournamentNameValue. '%')
                ->where('country', 'like', $request->tournamentCountryValue. '%')
                ->where('city', 'like', $request->tournamentCityValue. '%')
                ->when($tournamentStartDateValue, function ($query) use ($tournamentStartDateValue) {
                    return $query->where('start_date', '>=',
                        date_format(date_create_from_format('d/m/Y', $tournamentStartDateValue), 'Y-m-d'));
                })
                ->when($tournamentEndDateValue, function ($query) use ($tournamentEndDateValue) {
                    return $query->where('end_date', '<=',
                        date_format(date_create_from_format('d/m/Y', $tournamentEndDateValue), 'Y-m-d'));
                })
                ->when($tournamentMinTournamentPointsValue, function ($query) use ($tournamentMinTournamentPointsValue) {
                    return $query->where('tournament_points', '>=', $tournamentMinTournamentPointsValue);
                })
                ->when($tournamentMaxTournamentPointsValue, function ($query) use ($tournamentMaxTournamentPointsValue) {
                    return $query->where('tournament_points', '<=', $tournamentMaxTournamentPointsValue);
                })
                ->when($tournamentNumberOfSeatsValue, function ($query) use ($tournamentNumberOfSeatsValue) {
                    return $query->where('number_of_seats', $tournamentNumberOfSeatsValue);
                })
                ->when($tournamentGameSystemValue, function ($query) use ($tournamentGameSystemValue) {
                    return $query->where('game_system', $tournamentGameSystemValue);
                })
                ->when($tournamentStatusValue, function ($query) use ($tournamentStatusValue) {
                    return $query->where('status', $tournamentStatusValue);
                })
                ->paginate(3);

            $view = view('dynamic-content.tournaments.searchable-cards', compact('tournaments'))->render();
            return response()->json($view);

        }
    }

    private function generateMatches(int $tournamentId, $teams){

        $numberOfTeams = $teams->count();

        // First round matches
        for ($i = 0; $i < $numberOfTeams; $i+=2){

            $j = $i+1;

            $match = new Match;
            $match->first_club_id = $teams->get($i)->id;
            $match->second_club_id = $teams->get($j)->id;
            $match->round = 1;
            $match->tournament_id = $tournamentId;
            $match->save();
        }

        $tmpNumberOfTeams = $numberOfTeams / 2;
        $tmpRound = 2;

        // Next round matches + final match
        while ($tmpNumberOfTeams % 2 == 0) {
            for ($i = 0; $i < $tmpNumberOfTeams; $i+=2){
                $match = new Match;
                $match->tournament_id = $tournamentId;
                $match->round = $tmpRound;

                $match->save();
            }
            $tmpRound++;
            $tmpNumberOfTeams = $tmpNumberOfTeams / 2;
        }

        // Third place match
        $match = new Match;
        $match->tournament_id = $tournamentId;
        $match->round = $tmpRound - 1;

        $match->save();
    }
}
