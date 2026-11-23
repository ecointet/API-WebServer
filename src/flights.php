<?php

function GetFlights($airportFilter = null)
{
    $flights = flightsMockData();

    if ($airportFilter !== null) {
        $flights = array_values(array_filter($flights, function ($f) use ($airportFilter) {
            return $f['origin']['iata'] === $airportFilter
                || $f['destination']['iata'] === $airportFilter;
        }));
    }

    $summary = array_map(function ($f) {
        return [
            'flightCode'          => $f['flightCode'],
            'airline'             => $f['airline'],
            'origin'              => $f['origin']['iata'],
            'destination'         => $f['destination']['iata'],
            'scheduledDeparture'  => $f['scheduledDeparture'],
            'scheduledArrival'    => $f['scheduledArrival'],
            'status'              => $f['status'],
        ];
    }, $flights);

    $result = [
        'total'          => count($summary),
        'airport_filter' => $airportFilter,
        'flights'        => $summary,
    ];

    return json_encode($result);
}

function GetFlightDetails($flightCode)
{
    $flights = flightsMockData();

    foreach ($flights as $f) {
        if ($f['flightCode'] === $flightCode) {
            return json_encode($f);
        }
    }

    http_response_code(404);
    return json_encode(['message' => "Flight '$flightCode' not found."]);
}

function flightsMockData()
{
    return [
        [
            'flightCode'         => 'AF011',
            'airline'            => 'Air France',
            'aircraft'           => 'Airbus A380-800',
            'origin'             => [
                'iata'    => 'CDG',
                'name'    => 'Charles de Gaulle Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'destination'        => [
                'iata'    => 'JFK',
                'name'    => 'John F. Kennedy International Airport',
                'city'    => 'New York',
                'country' => 'United States',
            ],
            'scheduledDeparture' => '2026-11-23T08:30:00Z',
            'actualDeparture'    => '2026-11-23T08:30:00Z',
            'scheduledArrival'   => '2026-11-23T11:50:00Z',
            'estimatedArrival'   => '2026-11-23T11:50:00Z',
            'status'             => 'in_flight',
            'delayMinutes'       => 0,
            'gate'               => '2E-K32',
            'terminal'           => '2E',
        ],
        [
            'flightCode'         => 'AF154',
            'airline'            => 'Air France',
            'aircraft'           => 'Boeing 777-200ER',
            'origin'             => [
                'iata'    => 'CDG',
                'name'    => 'Charles de Gaulle Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'destination'        => [
                'iata'    => 'FCO',
                'name'    => 'Leonardo da Vinci International Airport',
                'city'    => 'Rome',
                'country' => 'Italy',
            ],
            'scheduledDeparture' => '2026-11-23T09:15:00Z',
            'actualDeparture'    => '2026-11-23T09:38:00Z',
            'scheduledArrival'   => '2026-11-23T11:20:00Z',
            'estimatedArrival'   => '2026-11-23T11:43:00Z',
            'status'             => 'delayed',
            'delayMinutes'       => 23,
            'gate'               => '2F-G14',
            'terminal'           => '2F',
        ],
        [
            'flightCode'         => 'VY9811',
            'airline'            => 'Vueling',
            'aircraft'           => 'Airbus A320neo',
            'origin'             => [
                'iata'    => 'ORY',
                'name'    => 'Paris-Orly Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'destination'        => [
                'iata'    => 'AGP',
                'name'    => 'Málaga-Costa del Sol Airport',
                'city'    => 'Málaga',
                'country' => 'Spain',
            ],
            'scheduledDeparture' => '2026-11-23T07:00:00Z',
            'actualDeparture'    => null,
            'scheduledArrival'   => '2026-11-23T09:30:00Z',
            'estimatedArrival'   => null,
            'status'             => 'cancelled',
            'delayMinutes'       => 0,
            'gate'               => null,
            'terminal'           => 'W',
        ],
        [
            'flightCode'         => 'IB3461',
            'airline'            => 'Iberia',
            'aircraft'           => 'Airbus A321',
            'origin'             => [
                'iata'    => 'AGP',
                'name'    => 'Málaga-Costa del Sol Airport',
                'city'    => 'Málaga',
                'country' => 'Spain',
            ],
            'destination'        => [
                'iata'    => 'CDG',
                'name'    => 'Charles de Gaulle Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'scheduledDeparture' => '2026-11-23T10:45:00Z',
            'actualDeparture'    => null,
            'scheduledArrival'   => '2026-11-23T13:15:00Z',
            'estimatedArrival'   => null,
            'status'             => 'boarding',
            'delayMinutes'       => 0,
            'gate'               => 'B22',
            'terminal'           => 'T3',
        ],
        [
            'flightCode'         => 'AZ610',
            'airline'            => 'ITA Airways',
            'aircraft'           => 'Airbus A330-200',
            'origin'             => [
                'iata'    => 'FCO',
                'name'    => 'Leonardo da Vinci International Airport',
                'city'    => 'Rome',
                'country' => 'Italy',
            ],
            'destination'        => [
                'iata'    => 'JFK',
                'name'    => 'John F. Kennedy International Airport',
                'city'    => 'New York',
                'country' => 'United States',
            ],
            'scheduledDeparture' => '2026-11-23T11:00:00Z',
            'actualDeparture'    => '2026-11-23T11:52:00Z',
            'scheduledArrival'   => '2026-11-23T15:30:00Z',
            'estimatedArrival'   => '2026-11-23T16:22:00Z',
            'status'             => 'delayed',
            'delayMinutes'       => 52,
            'gate'               => 'G7',
            'terminal'           => 'T3',
        ],
        [
            'flightCode'         => 'UA185',
            'airline'            => 'United Airlines',
            'aircraft'           => 'Boeing 767-400ER',
            'origin'             => [
                'iata'    => 'EWR',
                'name'    => 'Newark Liberty International Airport',
                'city'    => 'Newark',
                'country' => 'United States',
            ],
            'destination'        => [
                'iata'    => 'CDG',
                'name'    => 'Charles de Gaulle Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'scheduledDeparture' => '2026-11-23T17:35:00Z',
            'actualDeparture'    => null,
            'scheduledArrival'   => '2026-11-24T07:25:00Z',
            'estimatedArrival'   => null,
            'status'             => 'scheduled',
            'delayMinutes'       => 0,
            'gate'               => 'C120',
            'terminal'           => 'C',
        ],
        [
            'flightCode'         => 'TP454',
            'airline'            => 'TAP Air Portugal',
            'aircraft'           => 'Airbus A321LR',
            'origin'             => [
                'iata'    => 'ORY',
                'name'    => 'Paris-Orly Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'destination'        => [
                'iata'    => 'LIS',
                'name'    => 'Humberto Delgado Airport',
                'city'    => 'Lisbon',
                'country' => 'Portugal',
            ],
            'scheduledDeparture' => '2026-11-23T13:20:00Z',
            'actualDeparture'    => '2026-11-23T13:20:00Z',
            'scheduledArrival'   => '2026-11-23T15:05:00Z',
            'estimatedArrival'   => '2026-11-23T15:05:00Z',
            'status'             => 'in_flight',
            'delayMinutes'       => 0,
            'gate'               => 'J11',
            'terminal'           => 'J',
        ],
        [
            'flightCode'         => 'LH2342',
            'airline'            => 'Lufthansa',
            'aircraft'           => 'Airbus A319',
            'origin'             => [
                'iata'    => 'MXP',
                'name'    => 'Milan Malpensa Airport',
                'city'    => 'Milan',
                'country' => 'Italy',
            ],
            'destination'        => [
                'iata'    => 'FRA',
                'name'    => 'Frankfurt Airport',
                'city'    => 'Frankfurt',
                'country' => 'Germany',
            ],
            'scheduledDeparture' => '2026-11-23T14:00:00Z',
            'actualDeparture'    => '2026-11-23T14:00:00Z',
            'scheduledArrival'   => '2026-11-23T15:25:00Z',
            'estimatedArrival'   => '2026-11-23T15:25:00Z',
            'status'             => 'landed',
            'delayMinutes'       => 0,
            'gate'               => 'A44',
            'terminal'           => 'T1',
        ],
        [
            'flightCode'         => 'DL402',
            'airline'            => 'Delta Air Lines',
            'aircraft'           => 'Airbus A350-900',
            'origin'             => [
                'iata'    => 'JFK',
                'name'    => 'John F. Kennedy International Airport',
                'city'    => 'New York',
                'country' => 'United States',
            ],
            'destination'        => [
                'iata'    => 'CDG',
                'name'    => 'Charles de Gaulle Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'scheduledDeparture' => '2026-11-23T22:55:00Z',
            'actualDeparture'    => null,
            'scheduledArrival'   => '2026-11-24T12:40:00Z',
            'estimatedArrival'   => null,
            'status'             => 'scheduled',
            'delayMinutes'       => 0,
            'gate'               => 'B34',
            'terminal'           => 'T4',
        ],
        [
            'flightCode'         => 'FR8821',
            'airline'            => 'Ryanair',
            'aircraft'           => 'Boeing 737-800',
            'origin'             => [
                'iata'    => 'ORY',
                'name'    => 'Paris-Orly Airport',
                'city'    => 'Paris',
                'country' => 'France',
            ],
            'destination'        => [
                'iata'    => 'AGP',
                'name'    => 'Málaga-Costa del Sol Airport',
                'city'    => 'Málaga',
                'country' => 'Spain',
            ],
            'scheduledDeparture' => '2026-11-23T06:30:00Z',
            'actualDeparture'    => '2026-11-23T06:30:00Z',
            'scheduledArrival'   => '2026-11-23T09:00:00Z',
            'estimatedArrival'   => '2026-11-23T09:00:00Z',
            'status'             => 'arrived',
            'delayMinutes'       => 0,
            'gate'               => 'D05',
            'terminal'           => 'D',
        ],
    ];
}
