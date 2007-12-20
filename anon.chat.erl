% Everyone is allowed to use and modify
% this work in any way for any purpose.

-module( server ).
-export( [ start/1, responder/2, listen/5, client/2,
    client_listen/2, client_mainloop/6, timestamp/2 ] ).

start( Port )
    ->
    % Start up the responder so messages can get sent to it.
    PID_Responder = spawn( server, responder, [ [], 0 ] ),

    % Create the server socket and start the server
    case gen_tcp:listen( Port, [ list, { active, false }, { packet, line } ] ) of
        { ok, ServSock }
            ->
            { ok, Port } = inet:port( ServSock ),
            io:format( "Listening on port ~w.~n", [ Port ] ),
            listen( ServSock, PID_Responder, invalid, 0,
                    [ { 82, 17, 66, 43 }, {85,187,153,9} ]);
        { error, Reason }
            ->
            { error, Reason }
    end
    .

listen( ServerSock, PID_Responder, LastIP, IPtimes, BannedIPs )
    ->
    % Grab the client attempting to connect
    case gen_tcp:accept( ServerSock ) of
        { ok, ClientSock }
            ->
            % IP checking and banning
            { ok, { IP, _ } } = inet:peername( ClientSock ),
            
            case lists:member(IP, BannedIPs) of
                true
                    ->
                    gen_tcp:send( ClientSock, "B&\n" ),
                    gen_tcp:close( ClientSock ),
                    listen( ServerSock, PID_Responder, IP, 0, BannedIPs );
                false
                    ->
                    % Set up the thread to listen for that client
                    spawn( server, client, [ClientSock, PID_Responder] ),

                    io:format( "Client connected!~n", [] ),
                    case IP == LastIP of
                        true
                            ->
                            case IPtimes < 10 of
                                true
                                    ->
                                    listen( ServerSock, PID_Responder, IP, IPtimes + 1, BannedIPs );
                                false
                                    ->
                                    io:format( "BANNED ~p!~n", [ IP ] ),
                                    listen( ServerSock, PID_Responder, IP, 0, [ IP | BannedIPs ] )
                            end
                            ;
                        false
                            ->
                            listen( ServerSock, PID_Responder, IP, 0, BannedIPs )
                    end
             end
             ;
        Other
            ->
            io:format( "gen_tcp:accept returned ~p. Quitting.~n", [ Other ] )
    end
    .

client( ClientSock, PID_Responder )
    ->
    % Welcome message
    gen_tcp:send( ClientSock,
"HAVE YOU READ YOUR SICP TODAY?

Use /echo to toggle echoing.
Use /time to toggle timestamps (UTC).
Use /size to get number of people connected.
Use /ping to grab the attention of the other.
Use /next to discard current chat partner for this session.

Waiting for a chatter...\n"),

    % Make thread to handle connection
    spawn( server, client_listen, [ ClientSock, self() ] ),

    % Add to list of available chatters
    PID_Responder ! { inc_count },
    PID_Responder ! { add_client, self(), [] },

    % Go into the main loop
    client_mainloop( ClientSock, PID_Responder, nope, false, false, [] )
    .

client_mainloop( ClientSock, PID_Responder, Otherperson, Echo, Timestamp, Nexted )
    ->
    receive
        { toggle_time }
            ->
            % Show new status
            gen_tcp:send( ClientSock,
                io_lib:format("Timestamp mode set to ~p~n", [ not Timestamp ] )),

            % Loop with new status
            client_mainloop( ClientSock, PID_Responder, Otherperson, Echo, not Timestamp, Nexted );

        { get_count }
            ->
            % Pass message further
            PID_Responder ! { get_count, self() },

            % Loop
            client_mainloop( ClientSock, PID_Responder, Otherperson, Echo,  Timestamp, Nexted );

        { got_count, Count }
            ->
            % Show it
            
            gen_tcp:send( ClientSock,
                io_lib:format("Number of people connected: ~p~n", [ Count ] )),

            % Loop
            client_mainloop( ClientSock, PID_Responder, Otherperson, Echo, Timestamp, Nexted );

        { toggle_echo }
            ->
            % Show new status
            gen_tcp:send( ClientSock,
                io_lib:format("Echo mode set to ~p~n", [ not Echo ] )),

            % Loop with new status
            client_mainloop( ClientSock, PID_Responder, Otherperson, not Echo, Timestamp, Nexted );

        { die }
            ->
            case Otherperson of
                nope
                ->
                    ok;
                PID
                ->
                    PID ! { disconnected }
            end
            ,
            PID_Responder ! { remove_client, self() },
            PID_Responder ! { dec_count },

            % Loop
            client_mainloop( ClientSock, PID_Responder, nope, Echo, Timestamp, Nexted );

        { removed }
            ->
            % We have been removed. No looping, so it exits
           normal;

        { disconnected }
            ->
            % Other party disconnected
            timestamp( ClientSock, Timestamp ),
            gen_tcp:send( ClientSock, "Disconnected...\n" ),

            NewNexted =
                case Otherperson of
                    nope
                    ->
                        Nexted;
                    Other
                    ->
                        [ Other | Nexted ]
                end
            ,

            % Get back in the available list
            PID_Responder ! { add_client, self(), NewNexted },
            
            % Loop
            client_mainloop( ClientSock, PID_Responder, nope, Echo, Timestamp, NewNexted );

        { connected, PID }
            ->
            io:format("~p connected to ~p~n", [self(), PID]),

            % We got connected
            timestamp( ClientSock, Timestamp ),
            gen_tcp:send( ClientSock, "Connected to a chatter.\n\n" ),
            % Loop
            client_mainloop( ClientSock, PID_Responder, PID, Echo, Timestamp, Nexted );

        { disconnect }
            ->
            case Otherperson of
                nope
                    ->
                    ok;
                PID
                    ->
                    PID    ! { disconnected },
                    self() ! { disconnected }
            end
            ,

            % Loop
            client_mainloop( ClientSock, PID_Responder, Otherperson, Echo, Timestamp, Nexted );

        { message, Message }
            ->
            timestamp( ClientSock, Timestamp ),
            gen_tcp:send( ClientSock, io_lib:format("<Anonymous> ~s~n", [ Message ] ) ),

            % Loop
            client_mainloop( ClientSock, PID_Responder, Otherperson, Echo, Timestamp, Nexted );

        { send_message, Message }
            ->
            case Otherperson of
                nope
                    ->
                    ok;
                PID
                    ->
                    case Echo of
                        true
                            ->
                            timestamp( ClientSock, Timestamp ),
                            gen_tcp:send( ClientSock,
                            io_lib:format("<Me> ~s~n", [ Message ] ) );
                        false
                            ->
                            ok
                    end
                    ,
                    PID ! { message, Message }
            end
            ,

            % Loop
            client_mainloop( ClientSock, PID_Responder, Otherperson, Echo, Timestamp, Nexted )
    end
    .

timestamp( ClientSock, Show )
    ->
    case Show of
        false
            ->
            ok;
        true
            ->
            { _Date, { Hour, Minute, Second } } = calendar:universal_time(),
            gen_tcp:send( ClientSock,
                io_lib:format("~2..0s:~2..0s:~2..0s ", [
                    integer_to_list(Hour),
                    integer_to_list(Minute),
                    integer_to_list(Second) ]
                    )
                 )
    end
    .

isprintable( Character )
    ->
    Character >= 32 andalso Character =< 126
    .

client_listen( ClientSock, ClientPID )
    ->
    % Just wait for something to happen.
    case gen_tcp:recv( ClientSock, 0 ) of
        { ok, RawMsg }
            ->
            Msg = lists:filter( fun isprintable/1, RawMsg ),

            case lists:sublist(Msg, 5) of
                []
                    ->
                    ok;

                "/next"
                    ->
                    ClientPID ! { disconnect };

                "/echo"
                    ->
                    ClientPID ! { toggle_echo };

                "/size"
                    ->
                    ClientPID ! { get_count };

                "/time"
                    ->
                    ClientPID ! { toggle_time };

                "/ping"
                    ->
                    ClientPID ! { send_message, "\7*ping*" };

                _
                    ->
                    ClientPID ! { send_message, Msg }
            end
            ,

            % Loop
            client_listen( ClientSock, ClientPID );

        { error, _ }
            ->
            gen_tcp:close(ClientSock),
            ClientPID ! { die }
    end
    .

connect_clients( AvailableList )
    ->
    io:format("Available: ~p~n", [ AvailableList ] ),
    Pairs =
    [ { X, Y } ||
      { X, NX } <- AvailableList,
      { Y, NY } <- AvailableList,
      X < Y, not lists:member( X, NY ), not lists:member( Y, NX ) ],

    io:format("Pairs: ~p~n", [ Pairs ] ),
    case length( Pairs ) > 0 of
        false
            ->
            AvailableList;

        true
            ->
            {Elem1, Elem2} =
                lists:nth(random:uniform( length( Pairs ) ), Pairs),
            io:format("Connecting ~p and ~p~n", [Elem1, Elem2]),
            Elem1 ! { connected, Elem2 },
            Elem2 ! { connected, Elem1 },
            connect_clients(
                lists:keydelete( Elem1, 1, lists:keydelete( Elem2, 1, AvailableList ) )
            )
    end
    .

responder( AvailableList, Count )
    ->
    % Message handler
    receive
        { inc_count }
            ->
            responder( AvailableList, Count + 1);

        { dec_count }
            ->
            responder( AvailableList, Count - 1);

        { get_count, ClientPID }
            ->
            ClientPID ! { got_count,  Count },

            responder( AvailableList, Count );

        { remove_client, ClientPID }
            ->
            io:format( "remove_client ~p~n", [ ClientPID ] ),
            % Remove the entry from the list, if it exists.
            NewAvailableList = lists:keydelete( ClientPID, 1,  AvailableList ),

            % Callback to try to avoid synchronization issues
            ClientPID ! {removed},

            % Loop
            responder( NewAvailableList, Count );

        { add_client, ClientPID, DoNotWant }
            ->
            io:format( "add_client ~p~n", [ ClientPID ] ),

            % Add the client into the list and try to connect client pairs
            NewAvailableList = connect_clients( [ { ClientPID, DoNotWant} | AvailableList ] ),

            % Loop
            responder( NewAvailableList, Count )
    end
    .