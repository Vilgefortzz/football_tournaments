@extends('layouts.app')

@section('content')

    <div href="{{ route('user-contracts-binding', Auth::user()->id) }}" class="contract-binding" hidden></div>
    <div href="{{ route('user-contracts-created', Auth::user()->id) }}" class="contracts-created" hidden></div>

    <div id="loading"></div>
    <div id="contract-sign-loading"></div>
    <div id="content-search" class="row justify-content-center"></div>
    <div id="content" class="row justify-content-center">
        @if(Auth::user()->haveClub())
            <div class="tile menu-card dynamic-content-card">
                <img class="cover-image" src='{{ asset('images/clubs/menu/football-grass.jpg') }}'>
                <br>
                <div class="text text-center">
                    <h1>Your club</h1>
                    <h1><i class="fa fa-users fa-2x"></i></h1>
                    <h2 class="animate-text">Go to your club</h2>
                    <p class="animate-text">See club details, footballers, tournaments,
                        matches, showcase with trophies. </p>
                </div>
            </div>
        @endif
        <div id="clubs-list" href="{{ route('clubs-list-search') }}" class="tile menu-card">
            <img class="cover-image" src='{{ asset('images/clubs/menu/football-grass.jpg') }}'>
            <br>
            <div class="text text-center">
                <h1>Clubs</h1>
                <h1><i class="fa fa-search fa-2x"></i></h1>
                <h2 class="animate-text">Search or find your club</h2>
                <p class="animate-text">Search, see clubs, see their stats, trophies, ratings.
                    Try to join the club and become part of it. </p>
            </div>
        </div>
        @if(Auth::user()->isFootballer())
            <div href="{{ route('club-create') }}" class="tile menu-card dynamic-content-card">
                <img class="cover-image" src='{{ asset('images/clubs/menu/football-grass.jpg') }}'>
                <br>
                <div class="text text-center">
                    <h1>Create club</h1>
                    <h1><i class="fa fa-hand-o-right fa-2x"></i></h1>
                    <h2 class="animate-text">Set up your own club</h2>
                    <p class="animate-text">Start a new club, become a president of the club, manage club,
                        take part in tournaments, sign contracts with footballers. </p>
                </div>
            </div>
        @endif
    </div>

@endsection