@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="wrap">
            <div class="container">
                <div id="divLoading"></div>
                <div id="content" class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="jumbotron jumbotron-main-page">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <div class="tile chosen">
                                        <img class="cover-image" src='{{ asset('images/clubs/menu/football-grass.jpg') }}'>
                                        <h1 class="info-header"><i class="fa fa-hourglass-start"></i>
                                            {{ $remainingContractDuration. ' days left' }}
                                        </h1>
                                        <div class="text text-center">
                                            <h1>Binding contract</h1>
                                            <h1><img src="https://mdbootstrap.com/img/Photos/Avatars/avatar-2.jpg"
                                                     width="60" height="60" class="img-fluid rounded-circle"></h1>
                                            <h1><i class="fa fa-long-arrow-down"></i></h1>
                                            <h1><img src="{{ asset('images/contracts/signature.png') }}" width="130"></h1>
                                            <h1><i class="fa fa-long-arrow-down"></i></h1>
                                            <h1><img src="{{ asset('images/clubs/'. $contract->club->logo) }}" width="60" height="60"></h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <h1 class="font-bold font-italic">Contract details:</h1>
                                    <br>
                                    <h3 class="font-italic"><i class="fa fa-calendar fa-fw"></i>Signed on: {{ $contract->date_and_time_of_signing }}</h3>
                                    <h3 class="font-italic"><i class="fa fa-clock-o fa-fw"></i>Contract duration: {{ $contract->duration }}</h3>
                                    <h3 class="font-italic"><i class="fa fa-calendar-minus-o fa-fw"></i>End of contract: {{ $contract->date_and_time_of_end }}</h3>
                                    <br><br>
                                    <h1 class="font-bold font-italic">Transfer:</h1>
                                    <br>
                                    <h3>
                                        <img src="https://mdbootstrap.com/img/Photos/Avatars/avatar-2.jpg"
                                             width="60" height="60" class="img-fluid rounded-circle"> {{ $contract->user->username }}
                                        <i class="fa fa-long-arrow-right"></i>
                                        <img src="{{ asset('images/clubs/'. $contract->club->logo) }}" width="60" height="60"> {{ $contract->club->name }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection