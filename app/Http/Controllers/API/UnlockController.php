<?php
namespace App\Http\Controllers\API;

use App\Models\LockUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UnlockController extends BaseController
{
    protected $modal = 'App\Models\LockUser';
    protected $resource = 'App\Http\Resources\LockUserResource';
    private $collection = 'App\Http\Resources\LockUserCollection';

    /**
     * @return array
     */
    public function index()
    {
        return $this->sendResponse(new $this->collection(
            $this->modal::where('is_locked', true)->paginate($this->pagination)), 200
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {
        $input = $request->all();

        return $this->sendResponse(new $this->collection(
            $this->modal::leftJoin("users", 'lock_users.user_id', '=', 'users.id')
                ->where(function ($query) use ($input) {
                    foreach ($input as $key => $value) {
                        if ($value == null)
                            continue;

                        switch ($key) {
                            case 'username':
                            case 'email':
                                $query->where('users.' . $key, 'LIKE', '%' . $value . '%');
                                break;
                            default:
                                break;
                        }
                    }
                })->paginate($this->pagination)
        ), 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     */
    public function update($id, Request $request)
    {
        $check = $this->unlockAccount($id);
        $msg = array(
            'message' => $check ? "success" : "false"
        );

        if ($check) {
            $data = User::select(['username', 'email'])->where('id', $check->user_id)->first()->toArray();
            $this->sendMailUnlock($data);
            return $this->sendResponse($msg, 200);
        }

        return $this->sendResponse($msg, 429);
    }

    /**
     * unlockAccount
     * @param $id
     * @return bool
     */
    public function unlockAccount($id)
    {
        if (!$id) {
            return false;
        }

        $user = LockUser::find($id);
        $user->is_locked = false;
        $user->save();

        return $user;
    }

    /**
     * sendMailUnlock
     *
     * @param $data
     */
    public function sendMailUnlock($data)
    {
        Mail::send('auth.email.unlock-complete', $data, function ($msg) use ($data) {
            $msg->to($data['email'], env('MAIL_FROM_NAME'));
            $msg->subject('New request Unlock Account');
        });
    }

}
