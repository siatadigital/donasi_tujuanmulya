<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GroupPrivilege;
use App\Models\GroupPrivilegeDetail;
use App\Models\MenuAdmin;
use Illuminate\Http\RedirectResponse;

class GroupPrivilegeController extends Controller
{
    public function __construct()
    {

    }

    public function getGroupPrivilege()
    {
    	$data = [
            'title' => 'Group Privilege',
            'data' => GroupPrivilege::orderBy('id','desc')->get()
    	];
    	return view('admin::contents.group_privilege.index', $data);
    }

    public function createGroupPrivilege()
    {
    	$data = [
            'title' => 'Create Group Privilege',
            'menu_admin' => MenuAdmin::get()
    	];
    	return view('admin::contents.group_privilege.create', $data);
    }

    public function storeGroupPrivilege(Request $request)
    {

        if (!empty($request->menu)) {
          $group_privilege = GroupPrivilege::create(array(
            'title' => $request->title,
            'description' => $request->description
          ));

            $inputMenu = $request->menu;
    
            for($i = 0; $i < count($inputMenu); $i++) {
              if (!empty($inputMenu[$i])) {
                GroupPrivilegeDetail::create(array(
                  'group_privilege_id' => $group_privilege->id,
                  'menu_admin_id' => $inputMenu[$i],
                ));
              }
            }

            return redirectMessage(
              route('admin.group_privilege.getGroupPrivilege'),
              'Successfully Created !!',
              '',
              'success'
            );
          }else{
            return redirectMessage(
              route('admin.group_privilege.createGroupPrivilege'),
              'Pilih Menu Admin Terlebih Dahulu',
              '',
              'danger'
            );
          }
    }

    public function editGroupPrivilege($id)
    {

      $menu = array();
      $group = GroupPrivilegeDetail::with('menuAdmin')->where('group_privilege_id',$id)->get();

      foreach($group as $item){
        array_push($menu,$item['menu_admin_id']);
      }

    	$data = [
            'title' => 'Edit Group Privilege',
            'data' => GroupPrivilege::where('id',$id)->first(),
            'menu_admin' => MenuAdmin::whereNotIn('id',$menu)->get(),
            'detail' => GroupPrivilegeDetail::with('menuAdmin')->where('group_privilege_id',$id)->get()
    	];
    	return view('admin::contents.group_privilege.edit', $data);
    }

    public function updateGroupPrivilege($id, Request $request)
    {

        $group_privilege = GroupPrivilege::where('id',$id)->update(array(
            'title' => $request->title,
            'description' => $request->description
        ));

        GroupPrivilegeDetail::where('group_privilege_id',$id)->delete();

        if (!empty($request->menu1)) {
            $inputMenu1 = $request->menu1;
    
            for($i = 0; $i < count($inputMenu1); $i++) {
              if (!empty($inputMenu1[$i])) {
                GroupPrivilegeDetail::create(array(
                  'group_privilege_id' => $id,
                  'menu_admin_id' => $inputMenu1[$i],
                ));
              }
            }
          }

          if (!empty($request->menu2)) {
            $inputMenu2 = $request->menu2;
    
            for($i = 0; $i < count($inputMenu2); $i++) {
              if (!empty($inputMenu2[$i])) {
                GroupPrivilegeDetail::create(array(
                  'group_privilege_id' => $id,
                  'menu_admin_id' => $inputMenu2[$i],
                ));
              }
            }
          }

    	return redirectMessage(
            route('admin.group_privilege.getGroupPrivilege'),
            'Successfully Edited !!',
            '',
            'success'
        );
    }

    public function deleteGroupPrivilege($id)
    {
      GroupPrivilegeDetail::where('group_privilege_id',$id)->delete();
      GroupPrivilege::find($id)->delete();

      return redirectMessage(
        route('admin.group_privilege.getGroupPrivilege'),
        ' successfully Deleted !!',
        '',
        'success'
        );
    }

    public function getJsonGroupPrivilegeDetails(Request $request)
    {
      $details = GroupPrivilegeDetail::query();

      if ($request->has('group_privilege_id')) {
        $details = $details->where('group_privilege_id', $request->group_privilege_id);
      }

      $details = $details->get();

      return response()->json(['data' => $details]);
    }
}