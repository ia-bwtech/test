<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HandicapperCollection;
use App\Http\Resources\PackageCollection;
use App\Http\Resources\SubscribedPicksCollection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function featuredHandicappers(){
        $featuredHandicappers = $this->leaderboardApi('allleagues', 'allsports', 'all');

        return new HandicapperCollection($featuredHandicappers);
    }

    public function packages(Request $request)
    {
        $users = User::has('packages', '>', 0)->with('packages')->orderBy('verified_win_loss_percentage', 'desc')->paginate(5);

        if (isset($request->sortBy)) {
            $users = User::has('packages', '>', 0)->with('packages')->orderBy($request->orderBy, $request->sortBy)->paginate(5);
        }
        if (isset($request->search)) {
            $users = User::has('packages', '>', 0)->with('packages')->where('name', 'like', '%' . $request->search . '%')->paginate(5);
        }
        return new PackageCollection($users);
    }

    public function subscribedpicks(Request $request)
    {
        $user=User::find(auth()->user()->id);
        $subscribedpicks=$user->subscribedPicks1();
        return new SubscribedPicksCollection($subscribedpicks);
    }

    public function leaderboardApi($leagueName, $sportName, $date)
    {
        if ($leagueName == 'allleagues' && $date == 'all') {
            $data = User::where('is_handicapper', 1)->with(['bets' => function ($q) {
                $q->where('is_verified', 1);
                $q->where('status', 0);
                $q->where('is_won', '!=', 2);
            }])->get();
        } else {
            if ($leagueName == 'NCAAF' && $sportName == 'football') {
                $league = 'NCAA';
                $sport = 'football';
            } else if ($leagueName == 'NCAAB' && $sportName == 'basketball') {
                $league = 'NCAA';
                $sport = 'basketball';
            } else {
                $league = $leagueName;
                $sport = $sportName;
            }

            if ($date == 'all') {
                // dd($league);
                $data = User::where('is_handicapper', 1)->with(['bets' => function ($q) use ($league, $sport, $date) {
                    $q->where('is_verified', 1);
                    $q->where('league', $league);
                    $q->where('sport', $sport);
                    $q->where('status', 0);
                    $q->where('is_won', '!=', 2);
                }])->get();
            } else {
                $daterange = Carbon::today()->subDays($date);
                if (empty($league) || $league == "allleagues") {
                    $data = User::where('is_handicapper', 1)->with(['bets' => function ($q) use ($league, $sport, $daterange) {
                        $q->where('is_verified', 1);
                        $q->where('status', 0);
                        $q->where('is_won', '!=', 2);
                        $q->whereBetween('created_at', array($daterange, today()));
                    }])->get();
                } else {
                    $data = User::where('is_handicapper', 1)->with(['bets' => function ($q) use ($league, $sport, $daterange) {
                        $q->where('is_verified', 1);
                        $q->where('league', $league);
                        $q->where('sport', $sport);
                        $q->where('status', 0);
                        $q->where('is_won', '!=', 2);
                        $q->whereBetween('created_at', array($daterange, today()));
                    }])->get();
                }

                // $products->WhereHas('category', function ($query) use ($search) {
                //     $query->where('name',$search);
                // })->paginate(25)->setPath('');
                // return $data;

            }
        }

        $array = [];
        $collection = collect();

        foreach ($data as $key => $user) {
            if ($user->bets->isNotEmpty()) {
                $totalBets = 0;
                $wins = 0;
                $units = 0;
                $netUnits = 0;
                $risk = 0;
                foreach ($user['bets'] as $bet) {
                    $wins += $bet->is_won;
                    $totalBets += 1;
                    $risk += $bet->risk;
                    if ($bet->is_won == 1) {
                        $netUnits += $bet->to_win;
                        $units += $bet->to_win;
                    } else {
                        $netUnits -= $bet->risk;
                        $units -= $bet->risk;
                    }
                }
                $winLossPercentage = $totalBets > 0 ? ($wins / $totalBets) * 100 : '-';
                $losses = $totalBets - $wins;
                $roi = $risk > 0 ? ($units / $risk) * 100 : '-';

                $data = ['rank' => $key + 1, 'user_id' => $user->id, 'name' => $user->name, 'image' => $user->image, 'win_loss_percentage' => round($winLossPercentage, 2), 'total_bets' => $totalBets, 'wins' => $wins, 'losses' => $losses, 'net_units' => round($netUnits, 2), 'roi' => round($roi, 2)];
                $collection->push($data);
                // array_push($array,$data);
            }
        }

        $sorted = $collection->sortByDesc('total_bets')->sortByDesc('roi')->paginate(5);
        // $sorted = $sorted->values()->all();
        return $sorted;

        // $data = User::where('is_handicapper', 1)->orderBy('verified_win_loss_percentage', 'desc')->paginate(5);

        // return $data;
    }
}