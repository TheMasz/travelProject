<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use App\Models\ReviewsLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ReviewsController extends Controller
{
    function postReview(Request $request)
    {
        $rating = $request->input('rating');
        $review_txt = $request->input('review_txt');
        $location_id = $request->input('location_id');
        $member_id = session('member_id');
        $review = new Reviews();
        $review->member_id = $member_id;
        $review->location_id = $location_id;
        $review->review = $review_txt;
        $review->rating = $rating;
        $review->save();
        return response()->json(['success' => true]);
    }

    function getReviews($location_id)
    {
        $reviewsFunction = App::make('getReviews');
        $reviews = $reviewsFunction($location_id)->take(3);
        return $reviews;
    }
    function getMyReviews($sorted)
    {
        $reviewsFunction = App::make('getMyReviews');
        $myReviews = $reviewsFunction($sorted)->take(6);
        return $myReviews;
    }
    function loadMoreReviews($location_id, $offset)
    {
        $reviewsFunction = App::make('getReviews');
        $reviews = $reviewsFunction($location_id)->skip($offset)->take(5);
        return $reviews;
    }
    function loadMoreMyReviews($offset, $sorted)
    {
        $reviewsFunction = App::make('getMyReviews');
        $reviews = $reviewsFunction($sorted)->skip($offset)->take(6);
        return $reviews;
    }

    function likeActions(Request $request)
    {
        $action = $request->input('action');
        $review_id = $request->input('review_id');
        $member_id = session('member_id');

        if ($action == "like") {
            $like = new ReviewsLike();
            $like->review_id = $review_id;
            $like->member_id = $member_id;
            $like->save();

            $likeCount = ReviewsLike::where('review_id', $review_id)->count();
            return response()->json(['liked' => true,  'like_count' => $likeCount]);
        } elseif ($action == "unlike") {
            ReviewsLike::where('review_id', $review_id)
                ->where('member_id', $member_id)
                ->delete();
            $likeCount = ReviewsLike::where('review_id', $review_id)->count();
            return response()->json(['unliked' => true,  'like_count' => $likeCount]);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid action']);
        }
    }

    function removeReview(Request $request, $review_id)
    {
        // $review_id = $request->input('review_id');
        $deleted = Reviews::where('review_id', $review_id)->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['message' => 'Review not found or could not be removed'], 404);
        }
    }
}
