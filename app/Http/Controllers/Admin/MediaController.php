<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaReport;
use App\Models\MediaBan;
use App\Models\History;
use Input,
    Redirect,
    DB,
    Form,
    Auth,
    App,
    Session,
    Validator;

class MediaController extends Controller
{
    public function makePrivate($id) {
        $media = Media::find($id);
        if($media->id) {
            $media->private = Media::STATUS_PRIVATE;
            $media->save();
            if(Input::get('mode') == 1){
                return Redirect::to('/admin/media')
                                ->with('message', 'Media has been set to private.');
            }else{
                return Redirect::to('/admin/media/reports')
                                ->with('message', 'Media has been set to private.');
            }
        }else{
            if(Input::get('mode') == 1){
                return Redirect::to('/admin/media')
                                ->with('message', 'Media does not exist');
            }else{
                return Redirect::to('/admin/media/reports')
                                ->with('message', 'Media does not exist');
            }
        }
    }
    
    public function makePublic($id) {       
        $media = Media::find($id);
        if($media->id) {
            $media->private = Media::STATUS_PUBLIC;
            $media->save();
            if(Input::get('mode') == 1){
                 return Redirect::to('/admin/media')
                                ->with('message', 'Media has been set to public.');
            }else{
               return Redirect::to('/admin/media/reports')
                                ->with('message', 'Media has been set to public.');
            }
        }else{
            if(Input::get('mode') == 1){
                return Redirect::to('/admin/media')
                                ->with('message', 'Media does not exist');
            }else{
                return Redirect::to('/admin/media/reports')
                                ->with('message', 'Media does not exist');
            }
        }
    }
    
    // DELETE /{id}
    // Delete media by id
    public function delete (Request $request, $id) {
      $media = Media::find($id);
      $title = $media->title;
      $media->delete();

      if ($request->ajax()) {
        return response()->ajax($media);
      }
      return back()->with('message', "Media '{$title}' has been deleted successfully");
    }

    private function deleteAWSObject($media) {
        if ($media->type == "image" || $media->type == "text") {
            $s3 = App::make('aws')->createClient('s3');
            $s3->deleteObject([
                'Bucket' => 'enfolinkresources',
                'Key' => Auth::user()->screen_name . '/' . $media->file_name,
            ]);
        }
    }

    public function ban($id) {
        if (Session::token() != Input::get('_token')) {
            $data['page_title'] = "Something Wrong";
            $data['message'] = "Invalid CSRF Token! Please Back to previous page and reload the page.";
            return view('admin.error', $data);
        } else {
            $media = Media::find($id);
            $checkBan = MediaBan::where('web_link', $media->web_link)->first();
            if(!$checkBan) {
                $banData = [
                    'web_link' => $media->web_link,
                    'reason' => Input::get('reason'),
                    'user_id' => $media->user_id
                ];
                $validator = $this->getMediaBanValidator($banData);
                if ($validator->passes()) {
                    $ban = new MediaBan;
                    $ban->web_link = $media->web_link;
                    $ban->reason = Input::get('reason');
                    $ban->user_id = $media->user_id;
                    $ban->save();
                } else {
                    return redirect()->back()->withErrors($validator);
                }
            }
            return redirect()->to('admin/media/delete/'.$id.'?_token='.Input::get('_token'));
        }
    }
    
    private function getMediaBanValidator($input) {
        $validationRules = MediaBan::getValidationRules();
        return Validator::make($input, $validationRules);
    }

    public function showReports() {
        $data['page_title'] = "Media Reports";
        $data['reports'] = MediaReport::orderBy('created_at', 'DESC')->get();
        return view('admin.media.reports', $data);
    }
    
    public function showReport($id) {
        $data['page_title'] = "Report";
        $data['report'] = MediaReport::find($id);
        return view('admin.media.report', $data);
    }
    
    public function deleteReport($id) {
        if (Session::token() != Input::get('_token')) {
            $data['page_title'] = "Something Wrong";
            $data['message'] = "Invalid CSRF Token! Please Back to previous page and reload the page.";
            return view('admin.error', $data);
        } else {
            $report = MediaReport::find($id);
            if($report) {
                $media = Media::find($report->media_id);
                if($media) {
                    if($media->private == Media::STATUS_PRIVATE) {
                        $media->private = Media::STATUS_PUBLIC;
                        $media->save();
                    }
                }
                $report->delete();
            }
            if (Input::get('mode') == 1){
                return Redirect::to('/admin/media')
                                ->with('message', 'Report has been deleted successfully.');
            }else{
                return Redirect::to('/admin/media/reports')
                                ->with('message', 'Report has been deleted successfully.');
            }
        }
    }
    
    /**
     * Author: Jinandra
     * Date: 07-02-2017
     * GET /
     * list of all media
     *
     * @return array of media
     */
    public function index (Request $request) {
        $search = $request->query('s');
        if (strlen($search) == 10) {
            $search = substr($search, 0, 5) . "-" . substr($search, 5, 5);
        }
      
        $result = Media::getMediaList($search);
        $data['page_title'] = "All Media";
        $data['s'] = $request->query('s');
        $data['medialist'] = $result;
        return view('admin.media.media_list', $data);
    }
}
