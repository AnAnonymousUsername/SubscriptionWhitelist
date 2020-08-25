<?php

namespace App\Http\Controllers;

use App\Jobs\SyncAllMinecraftNames;
use App\Jobs\SyncChannel;
use App\Mail\Contact;
use App\Models\RequestStat;
use App\Models\Whitelist;
use App\Utils\TwitchUtils;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class BroadcasterController extends Controller
{
    public function index() {
        $user = TwitchUtils::getDbUser();
        $channel = $user->channel;
        $id = Hashids::encode($channel->id);
        $base_url = route('home'). "/list/$id/";
        $db_plans = json_decode($channel->valid_plans);
        if (is_null($db_plans)) {
            $plans = [
                'prime' => true,
                'tier1' => true,
                'tier2' => true,
                'tier3' => true
            ];
        } else {
            $plans = [
                //'prime' => in_array('Prime', $db_plans),
                'tier1' => in_array('1000', $db_plans) || in_array('Prime', $db_plans),
                'tier2' => in_array('2000', $db_plans),
                'tier3' => in_array('3000', $db_plans),
            ];
        }
        return view('broadcaster.index', [
            'name' => $channel->owner->name,
            'enabled' => $channel->enabled,
            'base_url' => $base_url,
            'plans' => $plans,
            'sync' => $channel->sync,
            'sync_option' => $channel->sync_option
        ]);
    }

    public function links() {
        $user = TwitchUtils::getDbUser();
        $channel = $user->channel;
        $id = Hashids::encode($channel->id);
        $base_url = route('home'). "/list/$id/";

        return view('broadcaster.links', [
            'name' => $channel->owner->name,
            'base_url' => $base_url
        ]);
    }

    public function updateSettings(Request $request) {
        $inputs = $request->validate([
            'list_toggle' => 'required|boolean',
            'sync_option' => [
                'required',
                Rule::in(['1day', '2day', '7day'])
            ],
            'sync_toggle' => 'required|boolean',
            'plan' => 'required|array',
            'plan.*' => 'boolean'

        ]);
        $plans = $inputs['plan'];
        $new_plans = array();
        //if ($plans['prime']) array_push($new_plans, 'Prime');
        if ($plans['tier1']) array_push($new_plans, '1000', 'Prime');
        if ($plans['tier2']) array_push($new_plans, '2000');
        if ($plans['tier3']) array_push($new_plans, '3000');

        $user = TwitchUtils::getDbUser();
        $channel = $user->channel;
        $channel->valid_plans = json_encode($new_plans);
        $channel->enabled = $inputs['list_toggle'];
        $channel->sync = $inputs['sync_toggle'];
        $channel->sync_option = $inputs['sync_option'];
        $channel->save();


        if ($channel->sync && is_null($user->access_token)) {
            $user->access_token = TwitchUtils::getSessionAccessToken();
            $user->save();
        } else if (!$channel->sync && !is_null($user->access_token)) {
            $user->access_token = null;
            $user->save();
        }

        return redirect()->route('broadcaster')->with('success', 'Successfully saved settings');
    }

    public function contact(Request $request) {
        $validated = $request->validate([
            'contact_email' => 'required|email',
            'contact_message' => 'required'
        ]);
        $to = $validated['contact_email'];
        $user = TwitchUtils::getDbUser();
        $message = $validated['contact_message'];
        Mail::to("whitelist@gorymoon.se")->queue(new Contact($user->display_name, $user->name, $to, $message));

        return redirect()->route('broadcaster')->with('success', 'Message successfully sent');
    }

    public function userlist() {
        $db_user = TwitchUtils::getDbUser();
        return view('broadcaster.userlist',
            [
                'channel_id' => $db_user->channel->id,
                'name' => $db_user->name
            ]);
    }

    public function userlistData(Request $request) {
        $channel = TwitchUtils::getDbUser()->channel;
        $query = $channel->whitelist()->newQuery();

        if ($request->has('sort') && $request->sort != "") {
            // handle multisort
            $sorts = explode(',', $request->sort);
            foreach ($sorts as $sort) {
                list($sortCol, $sortDir) = explode('|', $sort);
                $query = $query->orderBy($sortCol, $sortDir);
            }
        } else {
            $query = $query->orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $q->where('username', 'like', "%{$request->filter}%");
            });
        }

        $perPage = $request->has('per_page') ? (int) $request->per_page : 15;

        $pagination = $query->paginate($perPage);
        $pagination->appends([
            'sort' => $request->sort,
            'filter' => $request->filter,
            'per_page' => $request->per_page
        ]);

        return response()->json($pagination);
    }

    /**
     * @param $id
     * @return Builder
     */
    private static function getStatBase($id) {
        return DB::table('whitelists')->selectRaw('COUNT(id) as num')->where('channel_id', $id);
    }

    private function getStats() {
        $channel = TwitchUtils::getDbUser()->channel;
        $total = self::getStatBase($channel->id);
        $subs = self::getStatBase($channel->id)->whereNotNull('user_id');
        $custom = self::getStatBase($channel->id)->whereNull('user_id');
        $invalid = self::getStatBase($channel->id)->where('valid', false);
        $minecraft = self::getStatBase($channel->id)->whereNotNull('minecraft_id');
        return self::getStatBase($channel->id)->whereNotNull('steam_id')
            ->unionAll($minecraft)
            ->unionAll($invalid)
            ->unionAll($custom)
            ->unionAll($subs)
            ->unionAll($total)
            ->get();
    }

    public function listStats() {
        $result = $this->getStats();
        return response()->json([
            'total' => $result[5]->num,
            'subscribers' => $result[4]->num,
            'custom' => $result[3]->num,
            'invalid' => $result[2]->num,
            'minecraft' => $result[1]->num,
            'steam' => $result[0]->num,
        ]);
    }

    public function addUser(Request $request) {
        $channel = TwitchUtils::getDbUser()->channel;
        $inputs = $request->validate([
            'usernames' => 'required|array',
            'usernames.*' => Rule::unique('whitelists', 'username')->where(function ($query) use($channel) {
                return $query->where('channel_id', $channel->id);
            })
        ]);

        if (is_null($channel)) {
            response()->json('Invalid user', 403);
        }

        $whitelist = [];
        foreach(array_filter($inputs['usernames']) as $user) {
            $entry = new Whitelist;
            $entry->username = $user;
            $entry->channel()->associate($channel);
            $entry->save();
            $whitelist[] = $entry;
        }
        SyncAllMinecraftNames::dispatch($channel, $whitelist);
        $channel->whitelist_dirty = true;
        $channel->save();

        return redirect()->route('broadcaster.list')->with('success', 'Names successfully added to the whitelist');
    }

    public function removeAll() {
        $channel = TwitchUtils::getDbUser()->channel;
        if (is_null($channel)) {
            response()->json('Invalid user', 403);
        }
        $channel->whitelist()->delete();
        return response()->json();
    }

    public function removeInvalid() {
        $channel = TwitchUtils::getDbUser()->channel;
        if (is_null($channel)) {
            response()->json('Invalid user', 403);
        }
        $channel->whitelist()->where('valid', '0')->delete();
        return response()->json();
    }

    public function removeEntry($id) {
        $channel = TwitchUtils::getDbUser()->channel;
        if (is_null($channel)) {
            response()->json('Invalid user', 403);
        }

        $entry = $channel->whitelist->find($id);
        if (is_null($entry)) {
            return response()->json('User not in whitelist', 404);
        }
        $name = $entry->username;
        $entry->delete();
        $channel->whitelist_dirty = true;
        $channel->save();

        return response()->json([ 'user' => $name ]);
    }

    public function sync() {
        $channel = TwitchUtils::getDbUser()->channel;
        if (is_null($channel)) {
            response()->json('Invalid user', 403);
        }

        SyncChannel::dispatch($channel);
        return response()->json();
    }

    public function stats() {
        $channel = TwitchUtils::getDbUser()->channel;

        $result = $this->getStats();
        list($formatted, $day, $twodays) = RequestStat::parseStats($channel->stats);

        return view('broadcaster.stats', [
            'stats' => json_encode($formatted),
            'total' => $channel->requests,
            'day' => $day,
            'twodays' => $twodays,
            'whitelist' => (object)[
                'total' => $result[5]->num,
                'subscribers' => $result[4]->num,
                'custom' => $result[3]->num,
                'invalid' => $result[2]->num,
                'minecraft' => $result[1]->num,
                'steam' => $result[0]->num
            ]
        ]);
    }
}
