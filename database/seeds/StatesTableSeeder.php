<?php

use Illuminate\Database\Seeder;
use App\Models\State;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stateAbbreviations = [
            'ALABAMA' => 'AL',
            'ALASKA' => 'AK',
            'ARIZONA' => 'AZ',
            'ARKANSAS' => 'AR',
            'CALIFORNIA' => 'CA',
            'COLORADO' => 'CO',
            'CONNECTICUT' => 'CT',
            'DELAWARE' => 'DE',
            'FLORIDA' => 'FL',
            'GEORGIA' => 'GA',
            'HAWAII' => 'HI',
            'IDAHO' => 'ID',
            'ILLINOIS' => 'IL',
            'INDIANA' => 'IN',
            'IOWA' => 'IA',
            'KANSAS' => 'KS',
            'KENTUCKY' => 'KY',
            'LOUISIANA' => 'LA',
            'MAINE' => 'ME',
            'MARYLAND' => 'MD',
            'MASSACHUSETTS' => 'MA',
            'MICHIGAN' => 'MI',
            'MINNESOTA' => 'MN',
            'MISSISSIPPI' => 'MS',
            'MISSOURI' => 'MO',
            'MONTANA' => 'MT',
            'NEBRASKA' => 'NE',
            'NEVADA' => 'NV',
            'NEW HAMPSHIRE' => 'NH',
            'NEW JERSEY' => 'NJ',
            'NEW MEXICO' => 'NM',
            'NEW YORK' => 'NY',
            'NORTH CAROLINA' => 'NC',
            'NORTH DAKOTA' => 'ND',
            'OHIO' => 'OH',
            'OKLAHOMA' => 'OK',
            'OREGON' => 'OR',
            'PENNSYLVANIA' => 'PA',
            'RHODE ISLAND' => 'RI',
            'SOUTH CAROLINA' => 'SC',
            'SOUTH DAKOTA' => 'SD',
            'TENNESSEE' => 'TN',
            'TEXAS' => 'TX',
            'UTAH' => 'UT',
            'VERMONT' => 'VT',
            'VIRGINIA' => 'VA',
            'WASHINGTON' => 'WA',
            'WEST VIRGINIA' => 'WV',
            'WISCONSIN' => 'WI',
            'WYOMING' => 'WY',
            'GUAM' => 'GU',
            'PUERTO RICO' => 'PR',
            'VIRGIN ISLANDS' => 'VI',
        ];
        
        foreach($stateAbbreviations as $state => $abbreviation) {
            State::create([
                'full_name' => $state,
                'short_name' => $abbreviation,
            ]);
        }
    }
}
