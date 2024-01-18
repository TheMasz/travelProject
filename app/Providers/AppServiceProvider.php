<?php

namespace App\Providers;

use App\Models\LocationImages;
use App\Models\Locations;
use App\Models\Members;
use App\Models\LocationTypes;
use App\Models\PersonalPreference;
use App\Models\PlansTrip;
use App\Models\Provinces;
use App\Models\Preferences;
use App\Models\Reviews;
use App\Models\Zones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use stdClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        app()->singleton('getLocation', function ($app) {
            return function ($location_id) {
                $location = Locations::select(
                    'locations.location_id',
                    'locations.location_name',
                    'locations.address',
                    'locations.detail',
                    'locations.province_id',
                    'locations.s_time',
                    'locations.e_time',
                    'locations.latitude',
                    'locations.longitude',
                    Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_id SEPARATOR ",") AS PrefId'),
                    Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_name SEPARATOR ", ") AS Preferences'),
                    LocationImages::raw('GROUP_CONCAT(DISTINCT location_images.img_path SEPARATOR ", ") AS Images'),
                    'location_images.credit'
                )
                    ->join('location_types', 'locations.location_id', '=', 'location_types.location_id')
                    ->join('preferences', 'location_types.preference_id', '=', 'preferences.preference_id')
                    ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
                    ->where('locations.location_id', $location_id)
                    ->groupBy(
                        'locations.location_id',
                        'locations.location_name',
                        'locations.address',
                        'locations.detail',
                        'locations.province_id',
                        'locations.s_time',
                        'locations.e_time',
                        'locations.latitude',
                        'locations.longitude',
                        'location_images.credit'
                    )
                    ->first();
                return $location;
            };
        });

        app()->singleton('getLocations', function ($app) {
            return function ($arr) {
                $locations = [];
                foreach ($arr as $location_id) {
                    $location = Locations::select(
                        'locations.location_id',
                        'locations.location_name',
                        'locations.address',
                        'locations.detail',
                        'locations.province_id',
                        'locations.s_time',
                        'locations.e_time',
                        'locations.latitude',
                        'locations.longitude',
                        Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_id SEPARATOR ",") AS PrefId'),
                        Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_name SEPARATOR ", ") AS Preferences'),
                        LocationImages::raw('GROUP_CONCAT(DISTINCT location_images.img_path SEPARATOR ", ") AS Images'),
                        'location_images.credit'
                    )
                        ->join('location_types', 'locations.location_id', '=', 'location_types.location_id')
                        ->join('preferences', 'location_types.preference_id', '=', 'preferences.preference_id')
                        ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
                        ->where('locations.location_id', $location_id)
                        ->groupBy(
                            'locations.location_id',
                            'locations.location_name',
                            'locations.address',
                            'locations.detail',
                            'locations.province_id',
                            'locations.s_time',
                            'locations.e_time',
                            'locations.latitude',
                            'locations.longitude',
                            'location_images.credit'
                        )
                        ->first();
                    if ($location) {
                        $locations[] = $location;
                    }
                }
                return $locations;
            };
        });

        app()->singleton('getPlansByPref', function ($app) {
            $getPersonPref = $app->make('getPersonPref');
            return function ($member_id) use ($getPersonPref) {
                $myPref = $getPersonPref($member_id);
                $personPref = PersonalPreference::select('member_id', PersonalPreference::raw('GROUP_CONCAT(score) as scores'))
                    ->where('member_id', '!=', session('member_id'))
                    ->groupBy('member_id')
                    ->get();

                $num_of_pref = Preferences::all()->count();
                $userMatrix = [];
                $anotherMatrix = [];

                foreach ($myPref as $user) {
                    $userMatrix[] = $user['score'];
                }

                foreach ($personPref as $user) {
                    $member_id = $user->member_id;
                    $scores = explode(',', $user->scores);
                    $scores = array_map('intval', $scores);
                    $anotherMatrix[] = [$member_id, $scores];
                }

                $formattedAnotherMatrix = extractScores($anotherMatrix);

                // dd($userMatrix);
                // dd($anotherMatrix);
                // dd($formattedAnotherMatrix);

                $similarities = [];
                foreach ($anotherMatrix as $index => $data) {
                    $user = $data[0];
                    $similarity = cosineSimilarity($userMatrix, $formattedAnotherMatrix[$index]);
                    $similarities[] = ['member_id' => $user, 'similarity' => $similarity];
                }
                $similarities = collect($similarities);
                $similarities = $similarities->sortByDesc('similarity')->values()->all();

                // dd($similarities);

                //find plans
                $plans = [];
                $maxPlans = 5;
                foreach ($similarities as $user) {
                    $userPlans = PlansTrip::select('plan_name')
                        ->selectRaw('GROUP_CONCAT(location_id SEPARATOR ", ") AS locations_id')
                        ->where('member_id', $user['member_id'])
                        ->groupBy('plan_name')
                        ->get();

                    if ($userPlans->isNotEmpty() && count($plans) < $maxPlans) {
                        foreach ($userPlans as $plan) {
                            $plans[] = [
                                'member_id' => $user['member_id'],
                                'plan_name' => $plan->plan_name,
                                'locations_id' => $plan->locations_id,
                            ];
                        }
                    }

                    if (count($plans) >= $maxPlans) {
                        break;
                    }
                }
                return $plans;
            };
        });

        function extractScores($matrix)
        {
            $formattedScores = [];
            foreach ($matrix as $data) {
                $scores = $data[1];
                $formattedScores[] = $scores;
            }

            return $formattedScores;
        }

        app()->singleton('getLocationsByPref', function ($app) {
            $getPersonPref = $app->make('getPersonPref');
            return function ($province_id, $member_id, $selectedPreferences) use ($getPersonPref) {
                $query = Locations::select(
                    'locations.location_id',
                    'locations.location_name',
                    'locations.address',
                    'locations.detail',
                    'locations.s_time',
                    'locations.e_time',
                    'locations.latitude',
                    'locations.longitude',
                    Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_id SEPARATOR ",") AS PrefId'),
                    Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_name SEPARATOR ", ") AS Preferences'),
                    LocationImages::raw('GROUP_CONCAT(DISTINCT location_images.img_path SEPARATOR ", ") AS Images'),
                    'location_images.credit',
                    'provinces.province_id',
                    'provinces.province_name'
                )
                    ->join('location_types', 'locations.location_id', '=', 'location_types.location_id')
                    ->join('preferences', 'location_types.preference_id', '=', 'preferences.preference_id')
                    ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
                    ->join('provinces', 'locations.province_id', '=', 'provinces.province_id');

                if ($province_id) {
                    $query->where('locations.province_id', $province_id);
                }

                $query->groupBy(
                    'locations.location_id',
                    'locations.location_name',
                    'locations.address',
                    'locations.detail',
                    'locations.s_time',
                    'locations.e_time',
                    'locations.latitude',
                    'locations.longitude',
                    'location_images.credit',
                    'provinces.province_name',
                    'provinces.province_id'
                );

                if (!empty($selectedPreferences)) {
                    session(['selectedPreferences' => $selectedPreferences]);
                    $query->whereIn('locations.location_id', function ($subQuery) use ($selectedPreferences) {
                        $subQuery->select('lt.location_id')
                            ->from('location_types as lt')
                            ->whereIn('lt.preference_id', $selectedPreferences);
                    });
                }

                $locations = $query->get();
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
                $userWithPersonalPref = $getPersonPref($member_id);
                foreach ($userWithPersonalPref as $user) {
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

                // foreach ($similarities as $similar) {
                //     echo "locationID: " . $similar->location_id . " similarity score: " . $similar->score . "</br>";
                // }

                $sortedLocations = [];
                $weight = weightProfile($member_id);
                $processedLocationIds = [];
                foreach ($similarities as $similarity) {
                    $locationId = $similarity->location_id;
                    if (in_array($locationId, $processedLocationIds)) {
                        continue;
                    }

                    $index = findIndex($locations, $locationId);
                    $location = $locations[$index];
                    $locationPreferences = LocationTypes::where('location_id', $locationId)->get();
                    $similarityScore = $similarity->score;
                    $adjustedScore = 0;
                    $useOriginalScore = true;

                    foreach ($locationPreferences as $pref) {
                        if (isset($weight[$pref->preference_id])) {
                            $adjustedScore += $similarityScore * $weight[$pref->preference_id];
                            $useOriginalScore = false;
                        }
                    }

                    $location['adjusted_score'] = $useOriginalScore ? $similarityScore : $adjustedScore;
                    $sortedLocations[] = $location;
                    $processedLocationIds[] = $locationId;
                }

                usort($sortedLocations, function ($a, $b) {
                    return $b['adjusted_score'] <=> $a['adjusted_score'];
                });
                return $sortedLocations;
            };
        });

        function weightProfile($member_id)
        {
            $reviews = DB::table('reviews as r')
                ->select('r.review_id', 'lt.preference_id', DB::raw('AVG(r.rating) as average_rating'))
                ->join('location_types as lt', 'r.location_id', '=', 'lt.location_id')
                ->where('r.member_id', '=', $member_id)
                ->groupBy('r.review_id', 'lt.preference_id')
                ->get();

            $categoryScores = [];
            $categoryCounts = [];

            foreach ($reviews as $review) {
                $rating = $review->average_rating;
                $prefId = $review->preference_id;

                if (!isset($categoryScores[$prefId])) {
                    $categoryScores[$prefId] = $rating;
                    $categoryCounts[$prefId] = 1;
                } else {
                    $categoryScores[$prefId] += $rating;
                    $categoryCounts[$prefId]++;
                }
            }

            $weightedProfile = [];
            foreach ($categoryScores as $prefId => $score) {
                $averageScore = $score / $categoryCounts[$prefId];
                $normalizedScore = ($averageScore - 1) / (5 - 1);
                $weightedProfile[$prefId] = $normalizedScore;
            }

            return $weightedProfile;
        }


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

        app()->singleton('findPrefId', function ($app) {
            return function ($prefName) {
                $prefId = Preferences::where('preference_name', $prefName)
                    ->value('preference_id');
                return $prefId;
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

        app()->singleton('getUsername', function ($app) {
            return function ($member_id) {
                $member = Members::where('member_id', $member_id)->first();
                return $member['username'];
            };
        });

        app()->singleton('getMemberImg', function ($app) {
            return function ($member_id) {
                $member = Members::where('member_id', $member_id)->first();
                return $member['member_img'];
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

        app()->singleton('getReviews', function ($app) {
            return function ($location_id) {
                $member_id = session('member_id');

                $reviews = DB::table('reviews as r')
                    ->leftJoin('reviews_like as rl', function ($join) use ($member_id) {
                        $join->on('rl.review_id', '=', 'r.review_id')
                            ->where('rl.member_id', '=', $member_id);
                    })
                    ->join('members as m', 'm.member_id', '=', 'r.member_id')
                    ->select(
                        'r.review_id',
                        'r.member_id',
                        'r.review',
                        'r.rating',
                        'r.created_at',
                        'm.username',
                        'm.member_img',
                        DB::raw('COUNT(rl.review_id) AS like_count'),
                        DB::raw('IF(COUNT(rl.review_id) > 0, true, false) AS liked_by_current_member')
                    )
                    ->where('r.location_id', $location_id)
                    ->groupBy('r.review_id', 'r.review', 'r.rating', 'r.created_at', 'r.member_id', 'm.username', 'm.member_img')
                    ->orderByRaw("CASE WHEN r.member_id = ? THEN r.created_at ELSE COUNT(rl.review_id) END DESC", [$member_id])
                    ->get();

                return $reviews;
            };
        });

        app()->singleton('getMyReviews', function ($app) {
            return function ($sorted) {
                $member_id = session('member_id');
                $reviews = Reviews::select(
                    'reviews.review_id',
                    'reviews.location_id',
                    'reviews.review',
                    'reviews.rating',
                    'reviews.created_at',
                    'members.member_id',
                    'members.username',
                    'members.member_img',
                    'locations.location_name',
                    'locations.province_id'
                )
                    ->join('members', 'members.member_id', '=', 'reviews.member_id')
                    ->join('locations', 'locations.location_id', '=', 'reviews.location_id')
                    ->where('reviews.member_id', $member_id)
                    ->orderBy('reviews.created_at', $sorted)
                    ->get();
                return $reviews;
            };
        });

        app()->singleton('compareTime', function ($app) {
            return function ($reviewTime) {
                $createdAt = Carbon::parse($reviewTime);
                $currentDate = Carbon::now();
                $monthsDifference = $createdAt->diffInMonths($currentDate);
                if ($monthsDifference > 0) {
                    if ($monthsDifference === 1) {
                        $formattedDate = '1 เดือนที่แล้ว';
                    } else {
                        $formattedDate = $monthsDifference . ' เดือนที่แล้ว';
                    }
                } else {
                    $daysDifference = $createdAt->diffInDays($currentDate);
                    if ($daysDifference === 1) {
                        $formattedDate = '1 วันที่แล้ว';
                    } else {
                        $formattedDate = $daysDifference . ' วันที่แล้ว';
                    }
                }
                return $formattedDate;
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
