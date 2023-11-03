<?php

namespace App\Providers;

use App\Models\LocationImages;
use App\Models\Locations;
use App\Models\Members;
use App\Models\LocationTypes;
use App\Models\PersonalPreference;
use App\Models\Provinces;
use App\Models\Preferences;
use App\Models\Zones;
use Illuminate\Support\ServiceProvider;
use stdClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->singleton('getUsername', function ($app) {
            return function ($memberId) {
                $member = Members::where('member_id', $memberId)->first();
                return $member['username'];
            };
        });

        app()->singleton('getLocationsByPref', function ($app) {
            $getPersonPref = $app->make('getPersonPref');
            return function ($province_id, $member_id) use ($getPersonPref) {

                $locations = Locations::select(
                    'locations.location_id',
                    'locations.location_name',
                    'locations.address',
                    'locations.detail',
                    'locations.s_time',
                    'locations.latitude',
                    'locations.longitude',
                    Preferences::raw('GROUP_CONCAT(preferences.preference_id SEPARATOR ",") AS PrefId'),
                    Preferences::raw('GROUP_CONCAT(preferences.preference_name SEPARATOR ", ") AS Preferences'),
                    LocationImages::raw('GROUP_CONCAT(DISTINCT location_images.img_path SEPARATOR ", ") AS Images'),
                    'location_images.credit'
                )
                    ->join('location_types', 'locations.location_id', '=', 'location_types.location_id')
                    ->join('preferences', 'location_types.preference_id', '=', 'preferences.preference_id')
                    ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
                    ->where('locations.province_id', $province_id)
                    ->groupBy(
                        'locations.location_id',
                        'locations.location_name',
                        'locations.address',
                        'locations.detail',
                        'locations.s_time',
                        'locations.latitude',
                        'locations.longitude',
                        'location_images.credit'
                    )
                    ->get();

                $locationMatrix = [];
                $preferences = Preferences::get();

                foreach ($locations as $location) {
                    $locationId = $location['location_id'];
                    $locationPreferences = explode(',', $location['PrefId']);
                    $row = [];
                    foreach ($preferences as $preference) {
                        if (in_array($preference['preference_id'], $locationPreferences)) {
                            $row[] = 1;
                        } else {
                            $row[] = 0;
                        }
                    }
                    $locationMatrix[$locationId] = $row;
                }

                $userMatrix = [];
                $users = $getPersonPref($member_id);

                foreach ($users as $user) {
                    $userMatrix[] = $user['score'];
                }

                $similarities = [];

                foreach ($locationMatrix as $locationId => $location) {
                    $similarity = cosineSimilarity($location, $userMatrix);
                    $similarityObject = new stdClass();
                    $similarityObject->location_id = $locationId;
                    $similarityObject->score = $similarity;

                    $similarities[] = $similarityObject;
                }

                $similarities = collect($similarities);
                $similarities = $similarities->sortByDesc('score')->values()->all();


                // foreach ($similarities as $similarity) {
                //     echo "Location ID: " . $similarity->location_id . ", Similarity Score: " . $similarity->score . "<br>";
                // }

                $sortedLocations = [];

                foreach ($similarities as $similarity) {
                    $locationId = $similarity->location_id;
                    if (isset($locations[$locationId])) {

                        $index = findIndex($locations, $locationId);
                        $sortedLocations[] = $locations[$index];
                    }
                }

                return $sortedLocations;
            };
        });

        function findIndex($arr, $id)
        {
            foreach ($arr as $index => $a) {
                if ($a['location_id'] == $id) {
                    return $index;
                }
            }
        }

        function cosineSimilarity($vectorA, $vectorB)
        {
            // Calculate the dot product of the two vectors
            $dotProduct = 0;
            $magnitudeA = 0;
            $magnitudeB = 0;

            foreach ($vectorA as $key => $valueA) {
                if (array_key_exists($key, $vectorB)) {
                    $dotProduct += $valueA * $vectorB[$key];
                }
                $magnitudeA += $valueA ** 2;
            }

            foreach ($vectorB as $valueB) {
                $magnitudeB += $valueB ** 2;
            }

            // Calculate the magnitudes of both vectors
            $magnitudeA = sqrt($magnitudeA);
            $magnitudeB = sqrt($magnitudeB);

            // Calculate the cosine similarity
            if ($magnitudeA == 0 || $magnitudeB == 0) {
                return 0; // Handle division by zero
            } else {
                return $dotProduct / ($magnitudeA * $magnitudeB);
            }
        }

        app()->singleton('getPersonPref', function ($app) {
            return function ($member_id) {
                $preferences = PersonalPreference::select('member_id', 'preference_id', 'score')
                    ->where('member_id', $member_id)
                    ->get();
                return $preferences;
            };
        });

        app()->singleton('findProvinceId', function ($app) {
            return function ($provinceName) {
                $provinceId = Provinces::where('province_name', $provinceName)
                    ->value('province_id');
                return $provinceId;
            };
        });

        app()->singleton('getZoneswithProvince', function ($app) {
            return function () {
                $zones = Zones::select('zones.zone_id', 'zones.zone_name')
                    ->selectRaw('GROUP_CONCAT(provinces.province_name SEPARATOR ", ") AS Provinces')
                    ->join('provinces', 'zones.zone_id', '=', 'provinces.zone_id')
                    ->groupBy('zones.zone_id', 'zones.zone_name')
                    ->get();

                $resultArray = [];
                foreach ($zones as $zone) {
                    $provinces = explode(', ', $zone->Provinces);
                    $resultArray[] = (object)[
                        'zone_id' => $zone->zone_id,
                        'zone_name' => $zone->zone_name,
                        'provinces' => $provinces,
                    ];
                }

                return $resultArray;
            };
        });

        app()->singleton('maxLength', function ($app) {
            return function ($string, $maxLength = 250) {
                if (mb_strlen($string, 'UTF-8') > $maxLength) {
                    return mb_substr($string, 0, $maxLength, 'UTF-8');
                } else {
                    return $string;
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
