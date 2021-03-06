<?php
namespace App\Modules\Sidebars\Controllers;

use App\Modules\System\Controllers\BaseController as Controller;
use App\Modules\Sidebars\Models\Sidebar;
use App\Modules\System\Models\UserLog;

use Auth;
use Config;
use Input;
use Redirect;
use Validator;

class Admin extends Controller
{
    public function index()
    {
        $sidebars = Sidebar::paginate(25);
        $leftSidebars = Sidebar::where('position', 'LIKE', '%Left%')->paginate(25);
        $rightSidebars = Sidebar::where('position', 'LIKE', '%Right%')->paginate(25);
        return $this->getView()
        ->shares('title', 'Manage Sidebars')
        ->withLeftSidebars($leftSidebars)
        ->withRightSidebars($rightSidebars)
        ->withSidebars($sidebars);
    }

    public function create()
    {
        return $this->getView()->shares('title', 'Create Sidebar');
    }

    public function store()
    {
        $input = Input::all();

        $validate = $this->validator($input);

        if ($validate->passes()) {

            //save
            $sidebar = new Sidebar();

            if (is_array($input['position'])) {
                $sidebar->position = implode(',', $input['position']);
            }

            $sidebar->title        = $input['title'];
            $sidebar->displayTitle = (isset($input['displayTitle'])) ? $input['displayTitle'] : '';
            $sidebar->content      = $input['content'];
            $sidebar->class        = $input['class'];
            $sidebar->save();

            $log          = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->title   = "Created Sidebar: {$sidebar->title}.";
            $log->section = "sidebars";
            $log->link    = "admin/sidebars/{$sidebar->id}/edit";
            $log->refID   = $sidebar->id;
            $log->type    = 'Create';
            $log->save();

            return Redirect::to('admin/sidebars')->withStatus('Sidebar Created');
        }

        return Redirect::back()->withStatus($validate->errors(), 'danger')->withInput();

    }

    public function edit($id)
    {
        $sidebar = Sidebar::find($id);

        if ($sidebar === null) {
            return Redirect::to('admin/sidebars')->withStatus('Sidebar not found', 'danger');
        }

        return $this->getView()->shares('title', 'Edit Sidebae')->withSidebar($sidebar);
    }

    public function update($id)
    {
        $sidebar = Sidebar::find($id);

        if ($sidebar === null) {
            return Redirect::to('admin/sidebars')->withStatus('sidebar not found', 'danger');
        }

        $input = Input::all();

        $validate = $this->validator($input);

        if ($validate->passes()) {

            if (is_array($input['position'])) {
                $sidebar->position = implode(',', $input['position']);
            }

            //save
            $sidebar->title        = $input['title'];
            $sidebar->displayTitle = (isset($input['displayTitle'])) ? $input['displayTitle'] : '';
            $sidebar->content      = $input['content'];
            $sidebar->class        = $input['class'];
            $sidebar->save();

            $log          = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->title   = "Updated Sidebar: {$sidebar->title}.";
            $log->section = "sidebars";
            $log->link    = "admin/sidebars/{$sidebar->id}/edit";
            $log->refID   = $sidebar->id;
            $log->type    = 'Update';
            $log->save();

            return Redirect::to('admin/sidebars')->withStatus('Sidebar Updated');
        }

        return Redirect::back()->withStatus($validate->errors(), 'danger')->withInput();
    }

    public function destroy($id)
    {
        $sidebar = Sidebar::find($id);

        if ($sidebar === null) {
            return Redirect::to('admin/sidebars')->withStatus('sidebar not found', 'danger');
        }

        $log          = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->title   = "Deleted Sidebar: {$sidebar->title}.";
        $log->section = "sidebars";
        $log->refID   = $sidebar->id;
        $log->type    = 'Delete';
        $log->save();

        $sidebar->delete();

        return Redirect::to('admin/sidebars')->withStatus('Sidebar Deleted');
    }

    protected function validator($data)
    {
        $rules = [
            'title' => 'required|min:3',
            'position' => 'required'
        ];

        return Validator::make($data, $rules);
    }
}
