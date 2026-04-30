<?php

return [
    'slot_duration_hours' => 2,

    'fixed_slots' => [
        ['start' => '10:00', 'end' => '12:00'],
        ['start' => '12:00', 'end' => '14:00'],
    ],

    // Evening slots keep expanding in 2-hour windows until the configured last end time.
    'evening_slots' => [
        'start' => env('DELIVERY_EVENING_SLOT_START', '16:00'),
        'last_end' => env('DELIVERY_LAST_SLOT_END', '20:00'),
    ],
];
